<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiometricId;
use App\Models\BranchModel;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use Illuminate\Support\Facades\Auth;

class BiometricIdController extends Controller
{
    /**
     * Per-employee list — reached from the employee list row dropdown.
     */
    public function index($employee_id)
    {
        $employee = EmployeeModel::where('employee_id', $employee_id)
            ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->select('employee.*', 'branches.branch_name')
            ->first();

        abort_if(!$employee, 404, 'Employee not found.');

        $biometric_records = BiometricId::where('biometric_ids.employee_id', $employee_id)
            ->where('biometric_ids.is_deleted', 0)
            ->join('branches', 'biometric_ids.branch_code', '=', 'branches.branch_code')
            ->select('biometric_ids.*', 'branches.branch_name')
            ->orderBy('branches.branch_name')
            ->get();

        $branches = BranchModel::where('is_deleted', 0)->orderBy('branch_name')->get();

        $title = 'Biometric IDs - SAVEST HRIS';
        $menu = 'Employee';

        return view('employee.biometric_ids', compact(
            'employee', 'biometric_records', 'branches', 'title', 'menu'
        ));
    }

    public function store(Request $request, $employee_id)
    {
        abort_unless(EmployeeModel::where('employee_id', $employee_id)->exists(), 404);

        $request->validate([
            'branch_code'   => 'required|exists:branches,branch_code',
            'biometric_id'  => 'required|string|max:100',
        ]);

        $dupForEmployee = BiometricId::where('employee_id', $employee_id)
            ->where('branch_code', $request->branch_code)
            ->where('is_deleted', 0)
            ->exists();

        if ($dupForEmployee) {
            return back()->withInput()->withErrors([
                'branch_code' => 'This employee already has a biometric ID for that branch. Edit the existing row instead.'
            ]);
        }

        $dupForBranch = BiometricId::where('branch_code', $request->branch_code)
            ->where('biometric_id', $request->biometric_id)
            ->where('is_deleted', 0)
            ->exists();

        if ($dupForBranch) {
            return back()->withInput()->withErrors([
                'biometric_id' => 'That biometric ID is already assigned to another employee at this branch.'
            ]);
        }

        BiometricId::create([
            'employee_id'   => $employee_id,
            'branch_code'   => $request->branch_code,
            'biometric_id'  => $request->biometric_id,
        ]);

        SystemLogsModel::addLog('Add Biometric ID', "Biometric ID '{$request->biometric_id}' assigned to employee (ID: {$employee_id}) for branch {$request->branch_code}.", Auth::id());

        return back()->with('success', 'Biometric ID saved successfully.');
    }

    public function update(Request $request, $id)
    {
        $record = BiometricId::findOrFail($id);

        $request->validate([
            'branch_code'   => 'required|exists:branches,branch_code',
            'biometric_id'  => 'required|string|max:100',
        ]);

        $dupForEmployee = BiometricId::where('employee_id', $record->employee_id)
            ->where('branch_code', $request->branch_code)
            ->where('id', '!=', $record->id)
            ->where('is_deleted', 0)
            ->exists();

        if ($dupForEmployee) {
            return back()->withInput()->withErrors([
                'branch_code' => 'This employee already has a biometric ID for that branch.'
            ]);
        }

        $dupForBranch = BiometricId::where('branch_code', $request->branch_code)
            ->where('biometric_id', $request->biometric_id)
            ->where('id', '!=', $record->id)
            ->where('is_deleted', 0)
            ->exists();

        if ($dupForBranch) {
            return back()->withInput()->withErrors([
                'biometric_id' => 'That biometric ID is already assigned to another employee at this branch.'
            ]);
        }

        $record->update([
            'branch_code'   => $request->branch_code,
            'biometric_id'  => $request->biometric_id,
        ]);

        SystemLogsModel::addLog('Update Biometric ID', "Biometric ID (ID: {$id}) updated.", Auth::id());

        return back()->with('success', 'Biometric ID updated successfully.');
    }

    public function destroy($id)
    {
        $record = BiometricId::findOrFail($id);
        $record->update(['is_deleted' => 1]);

        SystemLogsModel::addLog('Delete Biometric ID', "Biometric ID (ID: {$id}) deleted.", Auth::id());

        return back()->with('success', 'Biometric ID deleted successfully.');
    }

    /**
     * All-branches, all-employees list — reached from the sidebar.
     * One row per employee (with or without a biometric ID yet), filterable
     * by branch and by employee.
     */
    public function all(Request $request)
    {
        // RBAC-scoped roster, for the filter dropdowns + Add modal — unfiltered.
        $employees = EmployeeModel::join('assigned_branches', 'assigned_branches.branch_code', '=', 'employee.branch_code')
            ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->where('employee.is_deleted', 0)
            ->where('employee.is_archived', 0)
            ->where('assigned_branches.ut_id', Auth::user()->ut_id)
            ->select('employee.*', 'branches.branch_name')
            ->distinct()
            ->orderBy('employee.lastname')
            ->get();

        // Same roster, filtered down for the table rows.
        $rowsQuery = EmployeeModel::join('assigned_branches', 'assigned_branches.branch_code', '=', 'employee.branch_code')
            ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->where('employee.is_deleted', 0)
            ->where('employee.is_archived', 0)
            ->where('assigned_branches.ut_id', Auth::user()->ut_id);

        if ($request->filled('branch_code')) {
            $rowsQuery->where('employee.branch_code', $request->branch_code);
        }

        if ($request->filled('employee_id')) {
            $rowsQuery->where('employee.employee_id', $request->employee_id);
        }

        // Status filter: has at least one non-deleted biometric_ids row vs none.
        if ($request->status === 'with') {
            $rowsQuery->whereExists(function ($q) {
                $q->selectRaw('1')
                  ->from('biometric_ids')
                  ->whereColumn('biometric_ids.employee_id', 'employee.employee_id')
                  ->where('biometric_ids.is_deleted', 0);
            });
        } elseif ($request->status === 'without') {
            $rowsQuery->whereNotExists(function ($q) {
                $q->selectRaw('1')
                  ->from('biometric_ids')
                  ->whereColumn('biometric_ids.employee_id', 'employee.employee_id')
                  ->where('biometric_ids.is_deleted', 0);
            });
        }

        $employee_rows = $rowsQuery
            ->select('employee.*', 'branches.branch_name')
            ->distinct()
            ->orderBy('employee.lastname')
            ->get();

        // Every non-deleted biometric record for these employees, grouped by employee —
        // an employee can have more than one (one per branch), so this stays a list, not a map.
        $biometricByEmployee = BiometricId::where('biometric_ids.is_deleted', 0)
            ->whereIn('biometric_ids.employee_id', $employee_rows->pluck('employee_id'))
            ->join('branches', 'biometric_ids.branch_code', '=', 'branches.branch_code')
            ->join('employee', 'biometric_ids.employee_id', '=', 'employee.employee_id')
            ->select(
                'biometric_ids.*',
                'branches.branch_name as biometric_branch_name',
                'employee.lastname',
                'employee.firstname',
                'employee.employee_number'
            )
            ->orderBy('branches.branch_name')
            ->get()
            ->groupBy('employee_id');

        $employee_rows->each(function ($emp) use ($biometricByEmployee) {
            $emp->biometric_records = $biometricByEmployee->get($emp->employee_id, collect());
        });

        // Flattened, for the Edit/Delete modals and their TomSelects — one set per record, not per employee.
        $all_biometric_records = $employee_rows->flatMap(fn ($emp) => $emp->biometric_records);

        $branches = BranchModel::where('is_deleted', 0)->orderBy('branch_name')->get();

        $title = 'Biometric IDs - SAVEST HRIS';
        $menu = 'Employee';

        return view('employee.biometric_ids_list', compact(
            'employee_rows', 'all_biometric_records', 'employees', 'branches', 'title', 'menu'
        ));
    }
}
