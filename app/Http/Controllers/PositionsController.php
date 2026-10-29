<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PositionsModel;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;


class PositionsController extends Controller
{


    public function load_company_positions(Request $request)
    {
        $status = $request->input('filter_status', 'Active'); // default Active

        if ($status === 'Active') {
            $positions = $this->get_positions(['is_deleted' => 0, 'is_archived' => 0]);
        } else {
            $positions = $this->get_positions(['is_deleted' => 0, 'is_archived' => 1]);
        }
        $data =[
            'title' => 'Company Positions - SAVEST HRIS',
            'menu' => 'Company Setup',
            'positions_list' => $positions,
            'filter_status'  => $status
        ];
        return view("employee/company_positions", $data);
    }

    public function get_positions($where = [])
    {
        $data = PositionsModel::where($where)->get();

        foreach ($data as $position) {
            $position->num_employees = EmployeeModel::where('is_deleted', 0)
                ->where('position_id', $position->position_id)
                ->count();
        }
        return $data;
    }


    public function employees($position_id)
    {
        // Same filter as num_employees in get_positions() so the modal matches the count
        $employees = EmployeeModel::where('employee.is_deleted', 0)
            ->where('employee.position_id', $position_id)
            ->leftJoin('employment_status', 'employee.es_id', '=', 'employment_status.es_id')
            ->leftJoin('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->select(
                'employee.employee_id', 'employee.employee_number',
                'employee.lastname', 'employee.firstname', 'employee.middlename', 'employee.suffix',
                'employee.branch_code', 'branches.branch_name', 'employment_status.empl_status'
            )
            ->orderBy('employee.lastname')
            ->orderBy('employee.firstname')
            ->get()
            ->map(function ($e) {
                $e->profile_url = route('employee.employee_profile', $e->employee_id);
                return $e;
            });

        return response()->json($employees);
    }


    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'position_name' => 'required|string|max:150|unique:positions,position_name',
        ]);

        // Save new position
        $position = new PositionsModel();
        $position->position_name = $validated['position_name'];
        $position->save();
        SystemLogsModel::addLog('Created New Position', json_encode($validated), auth()->id());

        return redirect()->back()->with('success', 'New position created successfully!');
    }


    public function update(Request $request, $position_id)
    {
        $request->validate([
            'position_name' => 'required|string|max:150|unique:positions,position_name,' . $position_id . ',position_id',
        ]);

        $position = PositionsModel::findOrFail($position_id);
        $position->update([
            'position_name' => $request->position_name,
        ]);
        SystemLogsModel::addLog('Updated Position', json_encode($position), auth()->id());
        return redirect()->back()->with('success', 'Position updated successfully!');
    }


    public function archive($position_id)
    {
        $position = PositionsModel::findOrFail($position_id);
        $position->update([
            'is_archived' => 1,
        ]);
        SystemLogsModel::addLog('Archived Position', json_encode($position), auth()->id());
        return redirect()->route('positions.company_positions')->with('success', 'Position moved to archive!');
    }

    public function restore($position_id)
    {
        $position = PositionsModel::findOrFail($position_id);
        $position->update([
            'is_archived' => 0,
        ]);
        SystemLogsModel::addLog('Restored Position', json_encode($position), auth()->id());
        return redirect()->route('positions.company_positions')->with('success', 'Position restored successfully!');
    }

    public function filter(Request $request)
    {
        $status = $request->input('filter_status', 'Active'); // default Active

        if ($status === 'Active') {
            $positions = $this->get_positions(['is_deleted' => 0, 'is_archived' => 0]);
        } else {
            $positions = $this->get_positions(['is_deleted' => 0, 'is_archived' => 1]);
        }

        $data = [
            'title'          => 'Company Positions - SAVEST HRIS',
            'menu'           => 'Employee',
            'positions_list' => $positions,
            'filter_status'  => $status
        ];

        return view("employee/company_positions", $data);
    }


}
