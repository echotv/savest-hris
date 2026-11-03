<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiometricId;
use App\Models\BranchWorkplaceModel;
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
            ->leftJoin('branches_workplaces', 'employee.brwp_id', '=', 'branches_workplaces.id')
            ->select('employee.*', 'branches_workplaces.brwp_code as branch_code', 'branches_workplaces.name as branch_name')
            ->first();

        abort_if(!$employee, 404, 'Employee not found.');

        $biometric_records = BiometricId::where('biometric_ids.employee_id', $employee_id)
            ->where('biometric_ids.is_deleted', 0)
            ->join('branches_workplaces', 'biometric_ids.brwp_id', '=', 'branches_workplaces.id')
            ->select('biometric_ids.*', 'branches_workplaces.brwp_code as branch_code', 'branches_workplaces.name as branch_name')
            ->orderBy('branches_workplaces.name')
            ->get();

        $branches = BranchWorkplaceModel::where('is_deleted', 0)->orderBy('name')->get();

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
            'brwp_id'       => 'required|exists:branches_workplaces,id',
            'biometric_id'  => 'required|string|max:100',
        ]);

        $dupForEmployee = BiometricId::where('employee_id', $employee_id)
            ->where('brwp_id', $request->brwp_id)
            ->where('is_deleted', 0)
            ->exists();

        if ($dupForEmployee) {
            return back()->withInput()->withErrors([
                'brwp_id' => 'This employee already has a biometric ID for that branch/workplace. Edit the existing row instead.'
            ]);
        }

        $dupForBranch = BiometricId::where('brwp_id', $request->brwp_id)
            ->where('biometric_id', $request->biometric_id)
            ->where('is_deleted', 0)
            ->exists();

        if ($dupForBranch) {
            return back()->withInput()->withErrors([
                'biometric_id' => 'That biometric ID is already assigned to another employee at this branch/workplace.'
            ]);
        }

        BiometricId::create([
            'employee_id'   => $employee_id,
            'brwp_id'       => $request->brwp_id,
            'biometric_id'  => $request->biometric_id,
        ]);

        $branchName = BranchWorkplaceModel::find($request->brwp_id)?->name;
        SystemLogsModel::addLog('Add Biometric ID', "Biometric ID '{$request->biometric_id}' assigned to employee (ID: {$employee_id}) for {$branchName}.", Auth::id());

        return back()->with('success', 'Biometric ID saved successfully.');
    }

    public function update(Request $request, $id)
    {
        $record = BiometricId::findOrFail($id);

        $request->validate([
            'brwp_id'       => 'required|exists:branches_workplaces,id',
            'biometric_id'  => 'required|string|max:100',
        ]);

        $dupForEmployee = BiometricId::where('employee_id', $record->employee_id)
            ->where('brwp_id', $request->brwp_id)
            ->where('id', '!=', $record->id)
            ->where('is_deleted', 0)
            ->exists();

        if ($dupForEmployee) {
            return back()->withInput()->withErrors([
                'brwp_id' => 'This employee already has a biometric ID for that branch/workplace.'
            ]);
        }

        $dupForBranch = BiometricId::where('brwp_id', $request->brwp_id)
            ->where('biometric_id', $request->biometric_id)
            ->where('id', '!=', $record->id)
            ->where('is_deleted', 0)
            ->exists();

        if ($dupForBranch) {
            return back()->withInput()->withErrors([
                'biometric_id' => 'That biometric ID is already assigned to another employee at this branch/workplace.'
            ]);
        }

        $record->update([
            'brwp_id'       => $request->brwp_id,
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
        $employees = EmployeeModel::join('assigned_branches', 'assigned_branches.brwp_id', '=', 'employee.brwp_id')
            ->join('branches_workplaces', 'employee.brwp_id', '=', 'branches_workplaces.id')
            ->where('employee.is_deleted', 0)
            ->where('employee.is_archived', 0)
            ->where('assigned_branches.ut_id', Auth::user()->ut_id)
            ->select('employee.*', 'branches_workplaces.brwp_code as branch_code', 'branches_workplaces.name as branch_name')
            ->distinct()
            ->orderBy('employee.lastname')
            ->get();

        // Same roster, filtered down for the table rows.
        $rowsQuery = EmployeeModel::join('assigned_branches', 'assigned_branches.brwp_id', '=', 'employee.brwp_id')
            ->join('branches_workplaces', 'employee.brwp_id', '=', 'branches_workplaces.id')
            ->where('employee.is_deleted', 0)
            ->where('employee.is_archived', 0)
            ->where('assigned_branches.ut_id', Auth::user()->ut_id);

        if ($request->filled('branch_code')) {
            $rowsQuery->where('employee.brwp_id', $request->branch_code);
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
            ->select('employee.*', 'branches_workplaces.brwp_code as branch_code', 'branches_workplaces.name as branch_name')
            ->distinct()
            ->orderBy('employee.lastname')
            ->get();

        // Every non-deleted biometric record for these employees, grouped by employee —
        // an employee can have more than one (one per branch/workplace), so this stays a list, not a map.
        $biometricByEmployee = BiometricId::where('biometric_ids.is_deleted', 0)
            ->whereIn('biometric_ids.employee_id', $employee_rows->pluck('employee_id'))
            ->join('branches_workplaces', 'biometric_ids.brwp_id', '=', 'branches_workplaces.id')
            ->join('employee', 'biometric_ids.employee_id', '=', 'employee.employee_id')
            ->select(
                'biometric_ids.*',
                'branches_workplaces.brwp_code as biometric_branch_code',
                'branches_workplaces.name as biometric_branch_name',
                'employee.lastname',
                'employee.firstname',
                'employee.employee_number'
            )
            ->orderBy('branches_workplaces.name')
            ->get()
            ->groupBy('employee_id');

        $employee_rows->each(function ($emp) use ($biometricByEmployee) {
            $emp->biometric_records = $biometricByEmployee->get($emp->employee_id, collect());
        });

        // Flattened, for the Edit/Delete modals and their TomSelects — one set per record, not per employee.
        $all_biometric_records = $employee_rows->flatMap(fn ($emp) => $emp->biometric_records);

        $branches = BranchWorkplaceModel::where('is_deleted', 0)->orderBy('name')->get();

        $title = 'Biometric IDs - SAVEST HRIS';
        $menu = 'Employee';

        return view('employee.biometric_ids_list', compact(
            'employee_rows', 'all_biometric_records', 'employees', 'branches', 'title', 'menu'
        ));
    }
}
