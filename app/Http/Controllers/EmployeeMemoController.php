<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeMemo;
use App\Models\EmployeeMemoEmployee;
use App\Models\EmployeeMemoFile;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use Yajra\DataTables\Facades\DataTables;

class EmployeeMemoController extends Controller
{
    /* Google Drive / Google Docs links only (the memo file lives in the company Drive). */
    private const URL_REGEX = '#^https?://(drive|docs)\.google\.com/#i';

    /** Restrict a memo query to the branches the signed-in user is assigned to. */
    private function scopeToUserBranches($query)
    {
        $assigned = userBranches();

        if ($assigned === null) return $query;          // no branch restriction

        if (empty($assigned)) return $query->whereRaw('1=0');

        return $query->whereHas('employeeLinks', fn($sq) =>
            $sq->whereHas('employee', fn($esq) => $esq->whereIn('branch_code', $assigned))
        );
    }

    private function scopedEmployees()
    {
        $assigned = userBranches();

        return EmployeeModel::where('is_deleted', 0)
            ->when($assigned !== null, fn($q) => empty($assigned) ? $q->whereRaw('1=0') : $q->whereIn('branch_code', $assigned))
            ->orderBy('lastname')->orderBy('firstname')
            ->get();
    }

    /** Trim links, drop blanks / non-strings so a stray empty row never triggers a server error. */
    private function normalizeUrls(Request $request): void
    {
        $urls = is_array($request->input('file_urls')) ? $request->input('file_urls') : [];
        $urls = array_values(array_filter(array_map('trim', array_filter($urls, 'is_string')), 'strlen'));
        $request->merge(['file_urls' => $urls]);
    }

    private function rules(): array
    {
        return [
            'subject'       => 'required|string|max:255',
            'date_issued'   => 'required|date',
            'employee_id'   => 'required|array|min:1',
            'employee_id.*' => 'integer|distinct|exists:employee,employee_id',
            'file_urls'     => 'required|array|min:1|max:20',
            'file_urls.*'   => ['string', 'max:500', 'url', 'distinct', 'regex:' . self::URL_REGEX],
        ];
    }

    private function messages(): array
    {
        return [
            'file_urls.required'   => 'Please add at least one Google Drive file link.',
            'file_urls.min'        => 'Please add at least one Google Drive file link.',
            'file_urls.*.url'      => 'Each file link must be a valid URL.',
            'file_urls.*.regex'    => 'Each file link must be a Google Drive / Google Docs URL (drive.google.com or docs.google.com).',
            'file_urls.*.distinct' => 'The same file link was entered more than once.',
            'file_urls.*.max'      => 'A file link may not be longer than 500 characters.',
            'employee_id.required' => 'Please select at least one employee.',
            'employee_id.min'      => 'Please select at least one employee.',
        ];
    }

    private function denyIfOutsideBranches(array $employeeIds)
    {
        $assigned = userBranches();
        if ($assigned === null) return null;

        $outside = empty($assigned)
            || EmployeeModel::whereIn('employee_id', $employeeIds)->whereNotIn('branch_code', $assigned)->exists();

        return $outside
            ? response()->json(['message' => 'You can only issue memos to employees in your assigned branches.'], 403)
            : null;
    }

    /** JSON snapshot shown in System Logs → View. */
    private function snapshot(EmployeeMemo $memo, array $employeeIds, array $urls): string
    {
        $names = EmployeeModel::whereIn('employee_id', $employeeIds)->get()
            ->map(fn($e) => trim($e->firstname . ' ' . $e->lastname) . ' (' . $e->employee_number . ')')
            ->values()->all();

        return json_encode([
            'memo_id'     => $memo->id,
            'subject'     => $memo->subject,
            'date_issued' => $memo->date_issued->format('Y-m-d'),
            'employees'   => implode(', ', $names),
            'file_links'  => implode(' | ', $urls),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function index()
    {
        if (!userCan('VIEW EMPLOYEE MEMOS')) abort(403);

        $subjects = $this->scopeToUserBranches(EmployeeMemo::where('is_deleted', 0))
            ->distinct()->orderBy('subject')->pluck('subject');

        return view('employee.memos', [
            'title'     => 'Employee Memos - SAVEST HRIS',
            'menu'      => 'Memos',
            'employees' => $this->scopedEmployees(),
            'subjects'  => $subjects,
        ]);
    }

    public function data(Request $request)
    {
        if (!userCan('VIEW EMPLOYEE MEMOS')) abort(403);

        $request->validate([
            'date_from'       => 'nullable|date',
            'date_to'         => 'nullable|date',
            'employee_filter' => 'nullable|integer',
            'subject_filter'  => 'nullable|string|max:255',
        ]);

        $query = $this->scopeToUserBranches(EmployeeMemo::where('employee_memos.is_deleted', 0));

        // Date issued range — skipped entirely when "All dates" is ticked.
        if (!$request->boolean('all_dates')) {
            if ($request->filled('date_from')) $query->whereDate('employee_memos.date_issued', '>=', $request->date_from);
            if ($request->filled('date_to'))   $query->whereDate('employee_memos.date_issued', '<=', $request->date_to);
        }

        if ($request->filled('employee_filter')) {
            $query->whereHas('employeeLinks', fn($sq) => $sq->where('employee_id', $request->employee_filter));
        }

        if ($request->filled('subject_filter')) {
            $query->where('employee_memos.subject', $request->subject_filter);
        }

        $query->select('employee_memos.*')
              ->with(['employeeLinks.employee', 'files'])
              ->orderByDesc('employee_memos.date_issued')
              ->orderByDesc('employee_memos.id');

        $branchMap = \App\Models\BranchModel::pluck('branch_name', 'branch_code');
        $canEdit   = userCan('UPDATE EMPLOYEE MEMOS');
        $canDelete = userCan('DELETE EMPLOYEE MEMOS');

        return DataTables::of($query)
            ->addColumn('date_issued_fmt', fn($r) => $r->date_issued->format('M d, Y'))
            ->addColumn('employees_info', function ($r) use ($branchMap) {
                return $r->employeeLinks->map(function ($link) use ($branchMap) {
                    $emp = $link->employee;
                    if (!$emp) return null;
                    return trim($emp->firstname . ' ' . $emp->lastname)
                        . '|' . $emp->employee_number
                        . '|' . $branchMap->get($emp->branch_code, $emp->branch_code)
                        . '|' . $emp->employee_id;
                })->filter()->join(';;');
            })
            ->addColumn('files_html', function ($r) {
                if ($r->files->isEmpty()) return '<span class="text-muted">—</span>';

                return $r->files->map(function ($f, $i) {
                    return '<a href="' . e($f->url) . '" target="_blank" rel="noopener noreferrer" '
                        . 'class="badge bg-light-primary text-primary border border-primary-subtle text-decoration-none me-1 mb-1 px-2 py-1" '
                        . 'title="' . e($f->url) . '">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>'
                        . 'File ' . ($i + 1) . '</a>';
                })->join('');
            })
            ->addColumn('action', function ($r) use ($canEdit, $canDelete) {
                $empIdsJson = htmlspecialchars($r->employeeLinks->pluck('employee_id')->values()->toJson(), ENT_QUOTES);
                $filesJson  = htmlspecialchars($r->files->pluck('url')->values()->toJson(JSON_UNESCAPED_SLASHES), ENT_QUOTES);

                $btns = '<div class="d-flex align-items-center justify-content-end gap-2 pe-2">';
                if ($canEdit) {
                    $btns .= '<button class="border-0 bg-transparent text-primary p-0 editBtn" style="width:24px;height:24px;"
                        data-id="'        . $r->id . '"
                        data-subject="'   . htmlspecialchars($r->subject, ENT_QUOTES) . '"
                        data-date="'      . $r->date_issued->format('Y-m-d') . '"
                        data-employees="' . $empIdsJson . '"
                        data-files="'     . $filesJson . '"
                        title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>';
                }
                if ($canDelete) {
                    $btns .= '<button class="border-0 bg-transparent text-danger p-0 deleteBtn" style="width:24px;height:24px;"
                        data-id="' . $r->id . '" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>';
                }
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['files_html', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        if (!userCan('ADD EMPLOYEE MEMOS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $this->normalizeUrls($request);
        $data = $request->validate($this->rules(), $this->messages());

        if ($deny = $this->denyIfOutsideBranches($data['employee_id'])) return $deny;

        $memo = DB::transaction(function () use ($data) {
            $memo = EmployeeMemo::create([
                'subject'     => $data['subject'],
                'date_issued' => $data['date_issued'],
                'created_by'  => Auth::id(),
                'is_deleted'  => 0,
            ]);

            foreach ($data['employee_id'] as $empId) {
                EmployeeMemoEmployee::create(['memo_id' => $memo->id, 'employee_id' => $empId]);
            }
            foreach ($data['file_urls'] as $url) {
                EmployeeMemoFile::create(['memo_id' => $memo->id, 'url' => $url]);
            }

            return $memo;
        });

        SystemLogsModel::addLog('Add Employee Memo', $this->snapshot($memo, $data['employee_id'], $data['file_urls']), Auth::id());

        return response()->json(['status' => 'success', 'subject' => $memo->subject]);
    }

    public function update(Request $request, int $id)
    {
        if (!userCan('UPDATE EMPLOYEE MEMOS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $memo = $this->scopeToUserBranches(EmployeeMemo::where('is_deleted', 0))->findOrFail($id);

        $this->normalizeUrls($request);
        $data = $request->validate($this->rules(), $this->messages());

        if ($deny = $this->denyIfOutsideBranches($data['employee_id'])) return $deny;

        DB::transaction(function () use ($memo, $data) {
            $memo->update([
                'subject'     => $data['subject'],
                'date_issued' => $data['date_issued'],
                'updated_by'  => Auth::id(),
            ]);

            $memo->employeeLinks()->delete();
            foreach ($data['employee_id'] as $empId) {
                EmployeeMemoEmployee::create(['memo_id' => $memo->id, 'employee_id' => $empId]);
            }

            $memo->files()->delete();
            foreach ($data['file_urls'] as $url) {
                EmployeeMemoFile::create(['memo_id' => $memo->id, 'url' => $url]);
            }
        });

        $memo->refresh();
        SystemLogsModel::addLog('Update Employee Memo', $this->snapshot($memo, $data['employee_id'], $data['file_urls']), Auth::id());

        return response()->json(['status' => 'updated', 'subject' => $memo->subject]);
    }

    public function delete(int $id)
    {
        if (!userCan('DELETE EMPLOYEE MEMOS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $memo = $this->scopeToUserBranches(EmployeeMemo::where('is_deleted', 0))->findOrFail($id);

        $employeeIds = $memo->employeeLinks()->pluck('employee_id')->all();
        $urls        = $memo->files()->pluck('url')->all();

        $memo->update(['is_deleted' => 1, 'updated_by' => Auth::id()]);

        SystemLogsModel::addLog('Delete Employee Memo', $this->snapshot($memo, $employeeIds, $urls), Auth::id());

        return response()->json(['status' => 'deleted']);
    }
}
