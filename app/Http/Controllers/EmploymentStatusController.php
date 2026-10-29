<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\EmploymentStatusModel;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;


class EmploymentStatusController extends Controller
{


    public function load_es()
    {
        $data =[
            'title' => 'Employment Status - SAVEST HRIS',
            'menu' => 'Company Setup',
            'employment_statuses' => EmploymentStatusModel::where(['is_deleted'=> 0])->get(),
        ];
        return view("employee/employment_status", $data);
    }

    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'empl_status' => 'required|string|max:255|unique:employment_status,empl_status',
            'is_current_employee' => 'required|boolean',
            'is_visible' => 'required|boolean',
        ]);

        // Save new position
        $es = new EmploymentStatusModel();
        $es->empl_status = $validated['empl_status'];
        $es->is_current_employee = $validated['is_current_employee'];
        $es->is_visible = $validated['is_visible'];
        $es->save();
        SystemLogsModel::addLog('Created New Employment Status', json_encode($validated), auth()->id());

        return redirect()->back()->with('success', 'New employment status created successfully!');
    }


   public function update(Request $request, $es_id)
{
    $request->validate([
        'empl_status' => 'required|string|max:255|unique:employment_status,empl_status,' . $es_id . ',es_id',
        'is_current_employee' => 'required|boolean',
        'is_visible' => 'required|boolean',
    ]);

    $es = EmploymentStatusModel::findOrFail($es_id);

    $es->update([
        'empl_status' => $request->empl_status,
        'is_current_employee' => $request->is_current_employee,
        'is_visible' => $request->is_visible,
    ]);

    SystemLogsModel::addLog(
        'Updated Employment Status',
        json_encode($request->all()),
        auth()->id()
    );

    return redirect()->back()->with('success', 'Employment status updated successfully!');
}


}
