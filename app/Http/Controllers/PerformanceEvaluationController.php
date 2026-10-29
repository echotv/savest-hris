<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PerformanceEvaluation;
use App\Models\PerformanceEvaluationFile;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use Yajra\DataTables\Facades\DataTables;

class PerformanceEvaluationController extends Controller
{
    /* Google Drive / Google Docs links only (the evaluation file lives in the company Drive). */
    private const URL_REGEX = '#^https?://(drive|docs)\.google\.com/#i';

    private const RATINGS = ['Outstanding', 'Very Satisfactory', 'Satisfactory', 'Fair', 'Poor'];
    private const TYPES   = ['Probationary', 'Quarterly', 'Semi-Annual', 'Annual', 'Regularization', 'Special / Ad-Hoc'];

    /** Restrict an evaluation query to the branches the signed-in user is assigned to. */
    private function scopeToUserBranches($query)
    {
        $assigned = userBranches();

        if ($assigned === null) return $query;          // no branch restriction

        if (empty($assigned)) return $query->whereRaw('1=0');

        return $query->whereHas('employee', fn($sq) => $sq->whereIn('branch_code', $assigned));
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
            'employee_id'       => 'required|integer|exists:employee,employee_id',
            'evaluation_period'  => 'required|string|max:100',
            'evaluation_type'    => 'required|string|max:100',
            'evaluation_date'    => 'required|date',
            'overall_score'      => 'required|numeric|min:0|max:100',
            'overall_rating'     => 'required|string|max:100',
            'remarks'            => 'nullable|string|max:2000',
            'file_urls'          => 'required|array|min:1|max:20',
            'file_urls.*'        => ['string', 'max:500', 'url', 'distinct', 'regex:' . self::URL_REGEX],
        ];
    }

    private function messages(): array
    {
        return [
            'file_urls.required'    => 'Please add at least one Google Drive file link.',
            'file_urls.min'         => 'Please add at least one Google Drive file link.',
            'file_urls.*.url'       => 'Each file link must be a valid URL.',
            'file_urls.*.regex'     => 'Each file link must be a Google Drive / Google Docs URL (drive.google.com or docs.google.com).',
            'file_urls.*.distinct'  => 'The same file link was entered more than once.',
            'file_urls.*.max'       => 'A file link may not be longer than 500 characters.',
            'employee_id.required'  => 'Please select an employee.',
            'employee_id.exists'    => 'The selected employee could not be found.',
        ];
    }

    private function denyIfOutsideBranches(int $employeeId)
    {
        $assigned = userBranches();
        if ($assigned === null) return null;

        $outside = empty($assigned)
            || !EmployeeModel::where('employee_id', $employeeId)->whereIn('branch_code', $assigned)->exists();

        return $outside
            ? response()->json(['message' => 'You can only record evaluations for employees in your assigned branches.'], 403)
            : null;
    }

    /** JSON snapshot shown in System Logs → View. */
    private function snapshot(PerformanceEvaluation $eval, array $urls): string
    {
        $emp = EmployeeModel::where('employee_id', $eval->employee_id)->first();
        $name = $emp ? trim($emp->firstname . ' ' . $emp->lastname) . ' (' . $emp->employee_number . ')' : $eval->employee_id;

        return json_encode([
            'evaluation_id'     => $eval->id,
            'employee'          => $name,
            'evaluation_period' => $eval->evaluation_period,
            'evaluation_type'   => $eval->evaluation_type,
            'evaluation_date'   => $eval->evaluation_date->format('Y-m-d'),
            'overall_score'     => (string) $eval->overall_score,
            'overall_rating'    => $eval->overall_rating,
            'remarks'           => $eval->remarks,
            'file_links'        => implode(' | ', $urls),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /** Years that have at least one evaluation, plus the current year, newest first. */
    private function availableYears()
    {
        $years = $this->scopeToUserBranches(PerformanceEvaluation::where('is_deleted', 0))
            ->selectRaw('DISTINCT YEAR(evaluation_date) as y')
            ->pluck('y');

        return $years->push((int) date('Y'))->unique()->sortDesc()->values();
    }

    public function index()
    {
        if (!userCan('VIEW PERFORMANCE EVALUATIONS')) abort(403);

        return view('employee.performance_evaluations', [
            'title'     => 'Performance Evaluations - SAVEST HRIS',
            'menu'      => 'Evaluations',
            'employees' => $this->scopedEmployees(),
            'types'     => self::TYPES,
            'ratings'   => self::RATINGS,
            'years'     => $this->availableYears(),
        ]);
    }

    public function data(Request $request)
    {
        if (!userCan('VIEW PERFORMANCE EVALUATIONS')) abort(403);

        $request->validate([
            'year_filter'     => 'nullable|integer|digits:4',
            'employee_filter' => 'nullable|integer',
            'type_filter'     => 'nullable|string|max:100',
            'rating_filter'   => 'nullable|string|max:100',
        ]);

        $query = $this->scopeToUserBranches(PerformanceEvaluation::where('performance_evaluations.is_deleted', 0));

        if ($request->filled('year_filter')) {
            $query->whereYear('performance_evaluations.evaluation_date', $request->year_filter);
        }

        if ($request->filled('employee_filter')) {
            $query->where('performance_evaluations.employee_id', $request->employee_filter);
        }

        if ($request->filled('type_filter')) {
            $query->where('performance_evaluations.evaluation_type', $request->type_filter);
        }

        if ($request->filled('rating_filter')) {
            $query->where('performance_evaluations.overall_rating', $request->rating_filter);
        }

        $query->select('performance_evaluations.*')
              ->with(['employee', 'files'])
              ->orderByDesc('performance_evaluations.evaluation_date')
              ->orderByDesc('performance_evaluations.id');

        $branchMap = \App\Models\BranchModel::pluck('branch_name', 'branch_code');
        $canEdit   = userCan('UPDATE PERFORMANCE EVALUATIONS');
        $canDelete = userCan('DELETE PERFORMANCE EVALUATIONS');

        return DataTables::of($query)
            ->addColumn('evaluation_date_fmt', fn($r) => $r->evaluation_date->format('M d, Y'))
            ->addColumn('employee_info', function ($r) use ($branchMap) {
                $emp = $r->employee;
                if (!$emp) return null;
                return trim($emp->firstname . ' ' . $emp->lastname)
                    . '|' . $emp->employee_number
                    . '|' . $branchMap->get($emp->branch_code, $emp->branch_code);
            })
            ->addColumn('score_fmt', fn($r) => $r->overall_score === null ? '—' : number_format((float) $r->overall_score, 2))
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
                $filesJson = htmlspecialchars($r->files->pluck('url')->values()->toJson(JSON_UNESCAPED_SLASHES), ENT_QUOTES);

                $btns = '<div class="d-flex align-items-center justify-content-end gap-2 pe-2">';
                if ($canEdit) {
                    $btns .= '<button class="border-0 bg-transparent text-primary p-0 editBtn" style="width:24px;height:24px;"
                        data-id="'         . $r->id                    . '"
                        data-employee_id="'. $r->employee_id           . '"
                        data-period="'     . htmlspecialchars($r->evaluation_period, ENT_QUOTES) . '"
                        data-type="'       . htmlspecialchars($r->evaluation_type, ENT_QUOTES)    . '"
                        data-date="'       . $r->evaluation_date->format('Y-m-d')                 . '"
                        data-score="'      . $r->overall_score                                    . '"
                        data-rating="'     . htmlspecialchars($r->overall_rating, ENT_QUOTES)      . '"
                        data-remarks="'    . htmlspecialchars($r->remarks ?? '', ENT_QUOTES)        . '"
                        data-files="'      . $filesJson . '"
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
        if (!userCan('ADD PERFORMANCE EVALUATIONS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $this->normalizeUrls($request);
        $data = $request->validate($this->rules(), $this->messages());

        if ($deny = $this->denyIfOutsideBranches((int) $data['employee_id'])) return $deny;

        $eval = DB::transaction(function () use ($data) {
            $eval = PerformanceEvaluation::create([
                'employee_id'       => $data['employee_id'],
                'evaluation_period' => $data['evaluation_period'],
                'evaluation_type'   => $data['evaluation_type'],
                'evaluation_date'   => $data['evaluation_date'],
                'overall_score'     => $data['overall_score'],
                'overall_rating'    => $data['overall_rating'],
                'remarks'           => $data['remarks'] ?? null,
                'created_by'        => Auth::id(),
                'is_deleted'        => 0,
            ]);

            foreach ($data['file_urls'] as $url) {
                PerformanceEvaluationFile::create(['evaluation_id' => $eval->id, 'url' => $url]);
            }

            return $eval;
        });

        SystemLogsModel::addLog('Add Performance Evaluation', $this->snapshot($eval, $data['file_urls']), Auth::id());

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, int $id)
    {
        if (!userCan('UPDATE PERFORMANCE EVALUATIONS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $eval = $this->scopeToUserBranches(PerformanceEvaluation::where('is_deleted', 0))->findOrFail($id);

        $this->normalizeUrls($request);
        $data = $request->validate($this->rules(), $this->messages());

        if ($deny = $this->denyIfOutsideBranches((int) $data['employee_id'])) return $deny;

        DB::transaction(function () use ($eval, $data) {
            $eval->update([
                'employee_id'       => $data['employee_id'],
                'evaluation_period' => $data['evaluation_period'],
                'evaluation_type'   => $data['evaluation_type'],
                'evaluation_date'   => $data['evaluation_date'],
                'overall_score'     => $data['overall_score'],
                'overall_rating'    => $data['overall_rating'],
                'remarks'           => $data['remarks'] ?? null,
                'updated_by'        => Auth::id(),
            ]);

            $eval->files()->delete();
            foreach ($data['file_urls'] as $url) {
                PerformanceEvaluationFile::create(['evaluation_id' => $eval->id, 'url' => $url]);
            }
        });

        $eval->refresh();
        SystemLogsModel::addLog('Update Performance Evaluation', $this->snapshot($eval, $data['file_urls']), Auth::id());

        return response()->json(['status' => 'updated']);
    }

    public function delete(int $id)
    {
        if (!userCan('DELETE PERFORMANCE EVALUATIONS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $eval = $this->scopeToUserBranches(PerformanceEvaluation::where('is_deleted', 0))->findOrFail($id);

        $urls = $eval->files()->pluck('url')->all();

        $eval->update(['is_deleted' => 1, 'updated_by' => Auth::id()]);

        SystemLogsModel::addLog('Delete Performance Evaluation', $this->snapshot($eval, $urls), Auth::id());

        return response()->json(['status' => 'deleted']);
    }
}
