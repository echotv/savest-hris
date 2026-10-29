<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeePositionMovement;
use App\Models\PositionsModel;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeePositionMovementController extends Controller
{
    public function index($employee_id)
    {
        $employee = EmployeeModel::where('employee_id', $employee_id)
            ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->join('positions', 'employee.position_id', '=', 'positions.position_id')
            ->first();

        abort_if(!$employee, 404, 'Employee not found.');

       $epm_records = EmployeePositionMovement::select(
        'employee_position_movement.*',
        'positions.position_name'
    )
    ->join('positions', 'employee_position_movement.position_id', '=', 'positions.position_id')
    ->where('employee_position_movement.employee_id', $employee_id)
    ->where('employee_position_movement.is_deleted', 0)
    ->orderBy('employee_position_movement.effective_date', 'desc')
    ->get();

        $oldest_record_id = EmployeePositionMovement::where('employee_id', $employee_id)
            ->where('is_deleted', 0)
            ->orderBy('effective_date', 'asc')
            ->first()
            ?->ep_id;

        $current_record_id = optional(
            EmployeePositionMovement::where('employee_id', $employee_id)
                ->where('is_deleted', 0)
                ->orderBy('effective_date', 'desc')
                ->first()
        )->ep_id;

        $title = 'Employee Position Movement - SAVEST HRIS';
        $menu = 'Employee';

        $positions = PositionsModel::where('is_deleted', 0)
            ->where('is_archived', 0)
            ->get();

        return view('employee.cp_movement', compact(
            'employee',
            'epm_records',
            'current_record_id',
            'oldest_record_id',
            'positions',
            'title',
            'menu',
        ));
    }

    public function store(Request $request, $employee_id)
    {
        abort_unless(EmployeeModel::where('employee_id', $employee_id)->exists(), 404);

        $request->validate([
            'position_id'    => 'required|exists:positions,position_id',
            'effective_date' => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:effective_date',
        ]);

        $hasActive = EmployeePositionMovement::where('employee_id', $employee_id)
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

        $exists = EmployeePositionMovement::where('employee_id', $employee_id)
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
            return back()->withErrors([
                'effective_date' => 'The date range overlaps with an existing position movement record.'
            ]);
        }

        EmployeePositionMovement::create([
            'employee_id'    => $employee_id,
            'position_id'    => $request->position_id,
            'effective_date' => $request->effective_date,
            'end_date'       => $request->end_date ?: null,
        ]);

        EmployeeModel::where('employee_id', $employee_id)
            ->update(['position_id' => $request->position_id]);

        SystemLogsModel::addLog('Add Position Movement', "Position movement added for employee (ID: {$employee_id}), effective {$request->effective_date}.", Auth::id());

        return back()->with('success', 'Position movement saved successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'position_id'    => 'required|exists:positions,position_id',
            'effective_date' => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:effective_date',
        ]);

        $epm = EmployeePositionMovement::findOrFail($id);

        $newStart = $request->effective_date;
        $newEnd   = $request->end_date;

        $exists = EmployeePositionMovement::where('employee_id', $epm->employee_id)
            ->where('is_deleted', 0)
            ->where('ep_id', '!=', $epm->ep_id)
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
            return back()->withErrors([
                'effective_date' => 'The date range overlaps with an existing position movement record.'
            ]);
        }

        $epm->update([
            'position_id'    => $request->position_id,
            'effective_date' => $request->effective_date,
            'end_date'       => $request->end_date ?: null,
        ]);

        EmployeeModel::where('employee_id', $epm->employee_id)
            ->update(['position_id' => $request->position_id]);

        SystemLogsModel::addLog('Update Position Movement', "Position movement (ID: {$id}) updated.", Auth::id());

        return back()->with('success', 'Position movement updated successfully.');
    }

    public function destroy($id)
    {
        $epm = EmployeePositionMovement::findOrFail($id);

        $epm->update([
            'is_deleted' => 1
        ]);

        SystemLogsModel::addLog('Delete Position Movement', "Position movement (ID: {$id}) deleted.", Auth::id());

        // restore previous
        $previous = EmployeePositionMovement::where('employee_id', $epm->employee_id)
            ->where('is_deleted', 0)
            ->where('ep_id', '!=', $epm->ep_id)
            ->where('effective_date', '<', $epm->effective_date)
            ->orderBy('effective_date', 'desc')
            ->first();

        if ($previous) {
            $previous->update([
                'end_date' => null
            ]);

            EmployeeModel::where('employee_id', $previous->employee_id)
                ->update(['position_id' => $previous->position_id]);
        }

        return back()->with('success', 'Position movement deleted successfully.');
    }
}
