<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentStatusMovement;
use App\Models\EmploymentStatusModel;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmploymentStatusMovementController extends Controller
{
    public function index($employee_id)
    {
        $employee = EmployeeModel::where('employee_id', $employee_id)
            ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->join('positions', 'employee.position_id', '=', 'positions.position_id')
            ->first();

        abort_if(!$employee, 404, 'Employee not found.');

        $esm_records = EmploymentStatusMovement::with('EmploymentStatus')
            ->where('employee_id', $employee_id)
            ->where('is_deleted', 0)
            ->orderBy('effective_date', 'desc')
            ->get();

        $oldest_record_id = EmploymentStatusMovement::where('employee_id', $employee_id)
    ->where('is_deleted', 0)
    ->orderBy('effective_date', 'asc')
    ->first()
    ?->esm_id;

        $current_record_id = optional(
            EmploymentStatusMovement::where('employee_id', $employee_id)
                ->where('is_deleted', 0)
                ->orderBy('effective_date', 'desc')
                ->first()
        )->esm_id;

        $employment_statuses = EmploymentStatusModel::where('is_deleted', 0)
            ->where('is_visible', 1)
            ->get();

        $title = 'Employment Status Movement - SAVEST HRIS';
        $menu = 'Employee';

        return view('employee.employment_status_movement', compact(
            'employee',
            'esm_records',
            'current_record_id',
            'oldest_record_id',
            'employment_statuses',
            'title',
            'menu'
        ));
    }

    public function store(Request $request, $employee_id)
    {
        abort_unless(EmployeeModel::where('employee_id', $employee_id)->exists(), 404);

        $request->validate([
            'es_id'          => 'required|exists:employment_status,es_id',
            'effective_date' => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:effective_date',
        ]);

        $hasActive = EmploymentStatusMovement::where('employee_id', $employee_id)
            ->where('is_deleted', 0)
            ->whereNull('end_date')
            ->exists();

        if ($hasActive) {
            return back()->withInput()->withErrors([
                'effective_date' => 'Cannot add a new record. Please set an end date for the current active record first.'
            ]);
        }

        $newStart = $request->effective_date;
        $newEnd   = $request->end_date;

        $exists = EmploymentStatusMovement::where('employee_id', $employee_id)
            ->where('is_deleted', 0)
            ->where(function ($q) use ($newStart, $newEnd) {
                if ($newEnd) {
                    $q->where(function ($q2) use ($newStart, $newEnd) {
                        $q2->whereNotNull('end_date')
                           ->whereDate('effective_date', '<=', $newEnd)
                           ->whereDate('end_date', '>=', $newStart);
                    })->orWhere(function ($q2) use ($newEnd) {
                        $q2->whereNull('end_date')
                           ->whereDate('effective_date', '<=', $newEnd);
                    });
                } else {
                    $q->where(function ($q2) use ($newStart) {
                        $q2->whereNotNull('end_date')
                           ->whereDate('end_date', '>=', $newStart);
                    })->orWhereNull('end_date');
                }
            })
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'effective_date' => 'The date range overlaps with an existing employment status movement period.'
            ]);
        }

        EmploymentStatusMovement::create([
            'employee_id'    => $employee_id,
            'es_id'          => $request->es_id,
            'effective_date' => $request->effective_date,
            'end_date'       => $request->end_date ?: null,
        ]);

        $this->syncCurrentStatus($employee_id);

        SystemLogsModel::addLog('Add Employment Status Movement', "Employment status movement added for employee (ID: {$employee_id}), effective {$request->effective_date}.", Auth::id());

        return back()->with('success', 'Employment status movement saved successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'es_id'          => 'required|exists:employment_status,es_id',
            'effective_date' => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:effective_date',
        ]);

        $esm = EmploymentStatusMovement::findOrFail($id);

        $newStart = $request->effective_date;
        $newEnd   = $request->end_date;

        $exists = EmploymentStatusMovement::where('employee_id', $esm->employee_id)
            ->where('is_deleted', 0)
            ->where('esm_id', '!=', $esm->esm_id)
            ->where(function ($q) use ($newStart, $newEnd) {
                if ($newEnd) {
                    $q->where(function ($q2) use ($newStart, $newEnd) {
                        $q2->whereNotNull('end_date')
                           ->whereDate('effective_date', '<=', $newEnd)
                           ->whereDate('end_date', '>=', $newStart);
                    })->orWhere(function ($q2) use ($newEnd) {
                        $q2->whereNull('end_date')
                           ->whereDate('effective_date', '<=', $newEnd);
                    });
                } else {
                    $q->where(function ($q2) use ($newStart) {
                        $q2->whereNotNull('end_date')
                           ->whereDate('end_date', '>=', $newStart);
                    })->orWhereNull('end_date');
                }
            })
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'effective_date' => 'The date range overlaps with an existing employment status movement period.'
            ]);
        }

        $esm->update([
            'es_id'          => $request->es_id,
            'effective_date' => $request->effective_date,
            'end_date'       => $request->end_date ?: null,
        ]);

        $this->syncCurrentStatus($esm->employee_id);

        SystemLogsModel::addLog('Update Employment Status Movement', "Employment status movement (ID: {$id}) updated.", Auth::id());

        return back()->with('success', 'Employment status movement updated successfully.');
    }

    public function destroy($id)
    {
        $esm = EmploymentStatusMovement::findOrFail($id);

        // Soft delete
        $esm->update([
            'is_deleted' => 1
        ]);

        SystemLogsModel::addLog('Delete Employment Status Movement', "Employment status movement (ID: {$id}) deleted.", Auth::id());

        // Restore previous record
        $previous = EmploymentStatusMovement::where('employee_id', $esm->employee_id)
            ->where('is_deleted', 0)
            ->where('esm_id', '!=', $esm->esm_id)
            ->where('effective_date', '<', $esm->effective_date)
            ->orderBy('effective_date', 'desc')
            ->first();

        if ($previous) {
            $previous->update([
                'end_date' => null
            ]);
        }

        $this->syncCurrentStatus($esm->employee_id);

        return back()->with('success', 'Employment status movement deleted successfully.');
    }

    /**
     * Point the employee's es_id at the current (latest) movement, and
     * deactivate the account when that status is not a current-employee status.
     */
    private function syncCurrentStatus($employee_id): void
    {
        $current = EmploymentStatusMovement::where('employee_id', $employee_id)
            ->where('is_deleted', 0)
            ->orderBy('effective_date', 'desc')
            ->first();

        $previousEsId = EmployeeModel::where('employee_id', $employee_id)->value('es_id');

        if ($current) {
            EmployeeModel::where('employee_id', $employee_id)->update(['es_id' => $current->es_id]);
        }

        EmployeeModel::syncAccountWithEmploymentStatus($employee_id, $previousEsId);
    }
}
