<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\EmployeeSuspension;
use App\Models\EmployeeModel;
use App\Models\BranchModel;
use App\Models\SystemLogsModel;
use Yajra\DataTables\Facades\DataTables;

class SuspensionController extends Controller
{
    public function index()
    {
        if (!userCan('VIEW SUSPENDED EMPLOYEES')) abort(403);

        $assignedBranches = userBranches();
        $branches = BranchModel::where('is_deleted', 0)
            ->when($assignedBranches !== null, fn($q) => empty($assignedBranches) ? $q->whereRaw('1=0') : $q->whereIn('branch_code', $assignedBranches))
            ->orderBy('branch_name')->get();

        $employees = EmployeeModel::where('is_deleted', 0)
            ->when($assignedBranches !== null, fn($q) => empty($assignedBranches) ? $q->whereRaw('1=0') : $q->whereIn('branch_code', $assignedBranches))
            ->orderBy('lastname')->get();

        return view('timekeeping.suspensions', [
            'title'     => 'Suspended Employees - SAVEST HRIS',
            'menu'      => 'Timekeeping',
            'branches'  => $branches,
            'employees' => $employees,
        ]);
    }

    public function data(Request $request)
    {
        if (!userCan('VIEW SUSPENDED EMPLOYEES')) abort(403);

        $assignedBranches = userBranches();

        $query = EmployeeSuspension::where('employee_suspensions.is_deleted', 0)
            ->join('employee', 'employee_suspensions.employee_id', '=', 'employee.employee_id')
            ->leftJoin('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->when($assignedBranches !== null, fn($q) => empty($assignedBranches) ? $q->whereRaw('1=0') : $q->whereIn('employee.branch_code', $assignedBranches))
            ->select([
                'employee_suspensions.id',
                'employee_suspensions.employee_id',
                'employee_suspensions.start_date',
                'employee_suspensions.end_date',
                'employee_suspensions.total_days',
                'employee_suspensions.reason',
                DB::raw("CONCAT(employee.firstname, ' ', employee.lastname) AS employee_name"),
                'employee.employee_number',
                'branches.branch_name',
                'employee.branch_code',
            ]);

        if ($request->filled('branch_filter')) {
            $query->where('employee.branch_code', $request->branch_filter);
        }

        $canEdit   = userCan('UPDATE SUSPENDED EMPLOYEES');
        $canDelete = userCan('DELETE SUSPENDED EMPLOYEES');

        return DataTables::of($query)
            ->addColumn('employee_info', fn($r) => $r->employee_name . '|' . $r->employee_number . '|' . ($r->branch_name ?? $r->branch_code))
            ->addColumn('action', function ($r) use ($canEdit, $canDelete) {
                $btns = '<div class="d-flex align-items-center gap-2 pe-2">';
                if ($canEdit) {
                    $btns .= '<button class="border-0 bg-transparent text-primary p-0 editBtn" style="width:24px;height:24px;"
                        data-id="'         . $r->id          . '"
                        data-employee_id="'. $r->employee_id . '"
                        data-start="'      . $r->start_date  . '"
                        data-end="'        . $r->end_date    . '"
                        data-days="'       . $r->total_days  . '"
                        data-reason="'     . htmlspecialchars($r->reason ?? '', ENT_QUOTES) . '"
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
        if (!userCan('ADD SUSPENDED EMPLOYEES')) return response()->json(['message' => 'Unauthorized.'], 403);

        $request->validate([
            'employee_id' => 'required|integer|exists:employee,employee_id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'nullable|string|max:1000',
        ]);

        $totalDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;

        EmployeeSuspension::create([
            'employee_id' => $request->employee_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'total_days'  => $totalDays,
            'reason'      => $request->reason,
            'created_by'  => Auth::id(),
            'is_deleted'  => 0,
        ]);

        $emp  = EmployeeModel::find($request->employee_id);
        $name = $emp ? trim($emp->firstname . ' ' . $emp->lastname) : "ID:{$request->employee_id}";
        SystemLogsModel::addLog('Add Suspended Employee', "Suspension added for '{$name}' ({$request->start_date} to {$request->end_date}, {$totalDays} day(s)).", Auth::id());

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, int $id)
    {
        if (!userCan('UPDATE SUSPENDED EMPLOYEES')) return response()->json(['message' => 'Unauthorized.'], 403);

        $request->validate([
            'employee_id' => 'required|integer|exists:employee,employee_id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'nullable|string|max:1000',
        ]);

        $totalDays  = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
        $suspension = EmployeeSuspension::findOrFail($id);

        $suspension->update([
            'employee_id' => $request->employee_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'total_days'  => $totalDays,
            'reason'      => $request->reason,
            'updated_by'  => Auth::id(),
        ]);

        SystemLogsModel::addLog('Update Suspended Employee', "Suspension record (ID: {$id}) updated.", Auth::id());

        return response()->json(['status' => 'updated']);
    }

    public function delete(int $id)
    {
        if (!userCan('DELETE SUSPENDED EMPLOYEES')) return response()->json(['message' => 'Unauthorized.'], 403);

        $suspension = EmployeeSuspension::findOrFail($id);
        $suspension->update(['is_deleted' => 1, 'updated_by' => Auth::id()]);
        SystemLogsModel::addLog('Delete Suspended Employee', "Suspension record (ID: {$id}) soft-deleted.", Auth::id());

        return response()->json(['status' => 'deleted']);
    }
}
