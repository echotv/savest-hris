<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryGrade;
use App\Models\SystemLogsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SalaryGradeController extends Controller
{
    public function index()
    {
        $salaryGrades = SalaryGrade::orderBy('grade_level')->orderBy('step')->get();
        $title = 'Salary Grades - SAVEST HRIS';
        $menu = 'Company Setup';
        return view('employee.salarygrade', compact(['salaryGrades', 'title' ,'menu']));
    }

 public function store(Request $request)
{
    $data = $request->validate([
    'grade_level'   => 'required|integer|min:0|max:100',
    'step'          => 'required|integer|min:0|max:100',
    'basic_salary'  => 'required|numeric|min:0|max:9999999999.99',
]);

    // Daily Rate / Hourly Rate are always derived from Basic Salary — never taken from the request.
    $data['daily_rate']  = round($data['basic_salary'] / 26, 2);
    $data['hourly_rate'] = round($data['daily_rate'] / 8, 2);

    $data['is_active'] = $request->has('is_active') ? 1 : 0;

    // Better duplicate check
    $exists = SalaryGrade::where('grade_level', $data['grade_level'])
        ->where('step', $data['step'])
        ->exists();

    if ($exists) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Salary grade already exists.');
    }

    $grade = SalaryGrade::create($data);

    SystemLogsModel::addLog('Add New Salary Grade', "Salary Grade (Level {$grade->grade_level}, Step {$grade->step}) was created.", Auth::id());

    return redirect()->back()->with('success', 'Salary Grade created successfully.');
}

    public function update(Request $request, $id)
{
    $salaryGrade = SalaryGrade::findOrFail($id);

    $data = $request->validate([
    'grade_level'   => 'required|integer|min:0|max:100',
    'step'          => 'required|integer|min:0|max:100',
    'basic_salary'  => 'required|numeric|min:0|max:9999999999.99',
]);

    // Daily Rate / Hourly Rate are always derived from Basic Salary — never taken from the request.
    $data['daily_rate']  = round($data['basic_salary'] / 26, 2);
    $data['hourly_rate'] = round($data['daily_rate'] / 8, 2);

    // Composite unique validation (grade_level + step)
    $exists = SalaryGrade::where('grade_level', $data['grade_level'])
        ->where('step', $data['step'])
        ->where('salary_grade_id', '!=', $salaryGrade->salary_grade_id)
        ->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->withErrors([
                'grade_level' => 'The combination of grade level and step already exists.'
            ]);
    }

    // checkbox handling
    $data['is_active'] = $request->has('is_active') ? 1 : 0;

    $salaryGrade->update($data);

    SystemLogsModel::addLog('Update Salary Grade', "Salary Grade (Level {$salaryGrade->grade_level}, Step {$salaryGrade->step}) was updated.", Auth::id());

    return redirect()->back()->with('success', 'Salary Grade updated successfully.');
}

    public function destroy($id)
    {
        $salaryGrade = SalaryGrade::findOrFail($id);
        $salaryGrade->delete();

        SystemLogsModel::addLog('Delete Salary Grade', "Salary Grade (Level {$salaryGrade->grade_level}, Step {$salaryGrade->step}) was deleted.", Auth::id());

        return redirect()->back()->with('success', 'Salary Grade deleted successfully.');
    }
}
