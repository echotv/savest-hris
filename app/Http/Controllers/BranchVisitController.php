<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\BranchVisit;
use App\Models\BranchVisitEmployee;
use App\Models\BranchVisitBranch;
use App\Models\BranchVisitDay;
use App\Models\EmployeeModel;
use App\Models\BranchModel;
use App\Models\SystemLogsModel;
use Yajra\DataTables\Facades\DataTables;

class BranchVisitController extends Controller
{
    public function index()
    {
        if (!userCan('VIEW BRANCH VISITS')) abort(403);

        $assignedBranches = userBranches();

        $branches = BranchModel::where('is_deleted', 0)
            ->when($assignedBranches !== null, fn($q) => empty($assignedBranches) ? $q->whereRaw('1=0') : $q->whereIn('branch_code', $assignedBranches))
            ->orderBy('branch_name')->get();

        $employees = EmployeeModel::where('is_deleted', 0)
            ->when($assignedBranches !== null, fn($q) => empty($assignedBranches) ? $q->whereRaw('1=0') : $q->whereIn('branch_code', $assignedBranches))
            ->orderBy('lastname')->get();

        // All branches available to visit (all active branches, not filtered by user scope)
        $allBranches = BranchModel::where('is_deleted', 0)->orderBy('branch_name')->get();

        return view('timekeeping.branch_visits', [
            'title'      => 'Branch Visits - SAVEST HRIS',
            'menu'       => 'Timekeeping',
            'branches'   => $branches,
            'employees'  => $employees,
            'allBranches'=> $allBranches,
        ]);
    }

    public function data(Request $request)
    {
        if (!userCan('VIEW BRANCH VISITS')) abort(403);

        $assignedBranches = userBranches();

        $query = BranchVisit::where('employee_branch_visits.is_deleted', 0);

        if ($assignedBranches !== null) {
            if (empty($assignedBranches)) {
                $query->whereRaw('1=0');
            } else {
                $query->whereHas('employeeLinks', fn($sq) =>
                    $sq->whereHas('employee', fn($esq) =>
                        $esq->whereIn('branch_code', $assignedBranches)
                    )
                );
            }
        }

        if ($request->filled('branch_filter')) {
            $query->whereHas('employeeLinks', fn($sq) =>
                $sq->whereHas('employee', fn($esq) =>
                    $esq->where('branch_code', $request->branch_filter)
                )
            );
        }

        if ($request->filled('visited_branch_filter')) {
            $query->whereHas('branchLinks', fn($sq) =>
                $sq->where('branch_code', $request->visited_branch_filter)
            );
        }

        if ($request->filled('date_from')) {
            $query->where('end_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('start_date', '<=', $request->date_to);
        }

        $query->select('employee_branch_visits.*')
              ->with(['employeeLinks.employee', 'branchLinks.branch', 'days']);

        $branchMap = BranchModel::pluck('branch_name', 'branch_code');
        $canEdit   = userCan('UPDATE BRANCH VISITS');
        $canDelete = userCan('DELETE BRANCH VISITS');

        return DataTables::of($query)
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
            ->addColumn('branches_info', function ($r) {
                return $r->branchLinks->map(fn($bl) => $bl->branch?->branch_name ?? $bl->branch_code)->join(';;');
            })
            ->addColumn('action', function ($r) use ($canEdit, $canDelete) {
                $daysJson = htmlspecialchars(
                    $r->days->map(fn($d) => [
                        'date'       => Carbon::parse($d->visit_date)->format('Y-m-d'),
                        'time_start' => substr($d->time_start, 0, 5),
                        'time_end'   => substr($d->time_end,   0, 5),
                    ])->toJson(),
                    ENT_QUOTES
                );
                $empIdsJson     = htmlspecialchars($r->employeeLinks->pluck('employee_id')->values()->toJson(), ENT_QUOTES);
                $branchCodesJson = htmlspecialchars($r->branchLinks->pluck('branch_code')->values()->toJson(), ENT_QUOTES);

                $btns = '<div class="d-flex align-items-center gap-2 pe-2">';
                if ($canEdit) {
                    $btns .= '<button class="border-0 bg-transparent text-primary p-0 editBtn" style="width:24px;height:24px;"
                        data-id="'          . $r->id . '"
                        data-purpose="'     . htmlspecialchars($r->purpose, ENT_QUOTES) . '"
                        data-start="'       . Carbon::parse($r->start_date)->format('Y-m-d') . '"
                        data-end="'         . Carbon::parse($r->end_date)->format('Y-m-d') . '"
                        data-notes="'       . htmlspecialchars($r->notes ?? '', ENT_QUOTES) . '"
                        data-employees="'   . $empIdsJson . '"
                        data-branches="'    . $branchCodesJson . '"
                        data-days="'        . $daysJson . '"
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
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        if (!userCan('ADD BRANCH VISITS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $request->validate([
            'employee_id'       => 'required|array|min:1',
            'employee_id.*'     => 'integer|exists:employee,employee_id',
            'branch_codes'      => 'required|array|min:1',
            'branch_codes.*'    => 'string|exists:branches,branch_code',
            'purpose'           => 'required|string|max:255',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'notes'             => 'nullable|string|max:2000',
            'days'              => 'required|array|min:1',
            'days.*.date'       => 'required|date',
            'days.*.time_start' => 'required|date_format:H:i',
            'days.*.time_end'   => 'required|date_format:H:i|after:days.*.time_start',
        ]);

        $userId = Auth::id();

        $record = BranchVisit::create([
            'purpose'    => $request->purpose,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'notes'      => $request->notes,
            'created_by' => $userId,
            'is_deleted' => 0,
        ]);

        $empNames = [];
        foreach ($request->employee_id as $empId) {
            BranchVisitEmployee::create(['branch_visit_id' => $record->id, 'employee_id' => $empId]);
            $emp = EmployeeModel::find($empId);
            $empNames[] = $emp ? trim($emp->firstname . ' ' . $emp->lastname) : "ID:{$empId}";
        }

        foreach ($request->branch_codes as $bc) {
            BranchVisitBranch::create(['branch_visit_id' => $record->id, 'branch_code' => $bc]);
        }

        foreach ($request->days as $day) {
            BranchVisitDay::create([
                'branch_visit_id' => $record->id,
                'visit_date'      => $day['date'],
                'time_start'      => $day['time_start'],
                'time_end'        => $day['time_end'],
            ]);
        }

        $names   = implode(', ', $empNames);
        $branches = implode(', ', $request->branch_codes);
        SystemLogsModel::addLog('Add Branch Visit', "Branch visit added for: {$names} to {$branches} ({$request->start_date} to {$request->end_date}).", $userId);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, int $id)
    {
        if (!userCan('UPDATE BRANCH VISITS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $request->validate([
            'employee_id'       => 'required|array|min:1',
            'employee_id.*'     => 'integer|exists:employee,employee_id',
            'branch_codes'      => 'required|array|min:1',
            'branch_codes.*'    => 'string|exists:branches,branch_code',
            'purpose'           => 'required|string|max:255',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'notes'             => 'nullable|string|max:2000',
            'days'              => 'required|array|min:1',
            'days.*.date'       => 'required|date',
            'days.*.time_start' => 'required|date_format:H:i',
            'days.*.time_end'   => 'required|date_format:H:i|after:days.*.time_start',
        ]);

        $record = BranchVisit::findOrFail($id);
        $record->update([
            'purpose'    => $request->purpose,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'notes'      => $request->notes,
            'updated_by' => Auth::id(),
        ]);

        $record->employeeLinks()->delete();
        foreach ($request->employee_id as $empId) {
            BranchVisitEmployee::create(['branch_visit_id' => $record->id, 'employee_id' => $empId]);
        }

        $record->branchLinks()->delete();
        foreach ($request->branch_codes as $bc) {
            BranchVisitBranch::create(['branch_visit_id' => $record->id, 'branch_code' => $bc]);
        }

        $record->days()->delete();
        foreach ($request->days as $day) {
            BranchVisitDay::create([
                'branch_visit_id' => $record->id,
                'visit_date'      => $day['date'],
                'time_start'      => $day['time_start'],
                'time_end'        => $day['time_end'],
            ]);
        }

        SystemLogsModel::addLog('Update Branch Visit', "Branch visit (ID: {$id}) updated.", Auth::id());

        return response()->json(['status' => 'updated']);
    }

    public function delete(int $id)
    {
        if (!userCan('DELETE BRANCH VISITS')) return response()->json(['message' => 'Unauthorized.'], 403);

        $record = BranchVisit::findOrFail($id);
        $record->update(['is_deleted' => 1, 'updated_by' => Auth::id()]);
        SystemLogsModel::addLog('Delete Branch Visit', "Branch visit (ID: {$id}) deleted.", Auth::id());

        return response()->json(['status' => 'deleted']);
    }
}
