<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryGradeMovement;
use App\Models\SalaryGrade;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalaryGradeMovementController extends Controller
{
    public function index($employee_id)
    {
        $employee = EmployeeModel::where('employee_id', $employee_id)
            ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->join('positions', 'employee.position_id', '=', 'positions.position_id')
            ->first();

        abort_if(!$employee, 404, 'Employee not found.');

        $sgm_records = SalaryGradeMovement::where('employee_id', $employee_id)->where('is_deleted', 0)
    ->orderBy('effective_date', 'desc')
    ->get();

    $oldest_record_id = optional(
    SalaryGradeMovement::where('employee_id', $employee_id)
        ->where('is_deleted', 0)
        ->orderBy('effective_date', 'asc')
        ->first()
)->sgm_id;

$current_record_id = optional(
    SalaryGradeMovement::where('employee_id', $employee_id)->where('is_deleted', 0)
        ->orderBy('effective_date', 'desc')
        ->first()
)->sgm_id;

        $salary_grades = SalaryGrade::where('is_active', 1)->get();

        $title = 'Salary Grade Movement - SAVEST HRIS';
    $menu = 'Employee';

        return view('employee.salarygrademovement', compact(
    'employee',
    'sgm_records',
    'current_record_id',
    'oldest_record_id',
    'salary_grades',
    'title',
    'menu'
));
    }



public function store(Request $request, $employee_id)
{
    abort_unless(EmployeeModel::where('employee_id', $employee_id)->exists(), 404);

    $request->validate([
        'salary_grade_id' => 'required|exists:salary_grades,salary_grade_id',
        'effective_date'  => 'required|date',
        'end_date'        => 'nullable|date|after_or_equal:effective_date',
        'remarks'         => 'nullable|string|max:5000',
    ]);

    $hasActive = SalaryGradeMovement::where('employee_id', $employee_id)
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

    $exists = SalaryGradeMovement::where('employee_id', $employee_id)
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
            'effective_date' => 'The date range overlaps with an existing salary grade movement record.'
        ]);
    }

    $grade = SalaryGrade::findOrFail($request->salary_grade_id);

    SalaryGradeMovement::create([
        'employee_id'      => $employee_id,
        'salary_grade_id'  => $grade->salary_grade_id,
        'grade_level'      => $grade->grade_level,
        'step'             => $grade->step,
        'basic_salary'     => $grade->basic_salary,
        'effective_date'   => $request->effective_date,
        'end_date'         => $request->end_date ?: null,
        'remarks'          => $request->remarks,
    ]);

    SystemLogsModel::addLog('Add Salary Grade Movement', "Salary grade movement added for employee (ID: {$employee_id}), effective {$request->effective_date}.", Auth::id());

    return back()->with('success', 'Salary grade movement saved successfully.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'salary_grade_id' => 'required|exists:salary_grades,salary_grade_id',
        'effective_date'  => 'required|date',
        'end_date'        => 'nullable|date|after_or_equal:effective_date',
        'remarks'         => 'nullable|string|max:5000',
    ]);

    $sgm = SalaryGradeMovement::findOrFail($id);

    $newStart = $request->effective_date;
    $newEnd   = $request->end_date;

    $exists = SalaryGradeMovement::where('employee_id', $sgm->employee_id)
        ->where('is_deleted', 0)
        ->where('sgm_id', '!=', $sgm->sgm_id)
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
            'effective_date' => 'The date range overlaps with an existing salary grade movement record.'
        ]);
    }

    $grade = SalaryGrade::findOrFail($request->salary_grade_id);

    $sgm->update([
        'salary_grade_id' => $grade->salary_grade_id,
        'grade_level'     => $grade->grade_level,
        'step'            => $grade->step,
        'basic_salary'    => $grade->basic_salary,
        'effective_date'  => $request->effective_date,
        'end_date'        => $request->end_date ?: null,
        'remarks'         => $request->remarks,
    ]);

    SystemLogsModel::addLog('Update Salary Grade Movement', "Salary grade movement (ID: {$id}) updated.", Auth::id());

    return back()->with('success', 'Salary movement updated successfully.');
}

public function destroy($id)
{
    $sgm = SalaryGradeMovement::findOrFail($id);

    // Soft delete current record
    $sgm->update([
        'is_deleted' => 1
    ]);

    SystemLogsModel::addLog('Delete Salary Grade Movement', "Salary grade movement (ID: {$id}) deleted.", Auth::id());

    // Get the latest previous active record (before deleted one)
    $previous = SalaryGradeMovement::where('employee_id', $sgm->employee_id)
        ->where('is_deleted', 0)
        ->where('sgm_id', '!=', $sgm->sgm_id)
        ->where('effective_date', '<', $sgm->effective_date)
        ->orderBy('effective_date', 'desc')
        ->first();

    // Restore current active record
    if ($previous) {
        $previous->update([
            'end_date' => null
        ]);
    }

    return back()->with('success', 'Salary movement deleted successfully.');
}
}
