<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BranchWorkplaceModel;
use App\Models\SystemLogsModel;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = BranchWorkplaceModel::where('is_deleted', 0);

        if ($request->filled('is_branch')) {
            $query->where('is_branch', $request->is_branch);
        }

        $branches = $query->orderBy('brwp_code')->get();

        $title = "Branches/Workplaces - Savest HRIS";
        $menu = "Company Setup";
        return view('branches', compact(['branches', 'title', 'menu']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brwp_code' => 'required|string|max:20|unique:branches_workplaces,brwp_code',
            'name'      => 'required|string|max:255|unique:branches_workplaces,name',
            'location'  => 'nullable|string',
            'is_branch' => 'nullable',
        ]);

        BranchWorkplaceModel::create([
            'brwp_code'  => $request->brwp_code,
            'name'       => $request->name,
            'location'   => $request->location,
            'is_branch'  => $request->has('is_branch') ? 1 : 0,
            'is_visible' => $request->has('is_visible') ? 1 : 0,
            'is_deleted' => 0,
        ]);

        SystemLogsModel::addLog('Add New Branch/Workplace', "'{$request->brwp_code}' ({$request->name}) was created.", Auth::id());

        return redirect()->back()->with('success', 'Branch/Workplace created successfully.');
    }

    public function update(Request $request, $id)
    {
        $branch = BranchWorkplaceModel::findOrFail($id);

        $request->validate([
            'brwp_code' => 'required|string|max:20|unique:branches_workplaces,brwp_code,' . $branch->id . ',id',
            'name'      => 'required|string|max:255|unique:branches_workplaces,name,' . $branch->id . ',id',
            'location'  => 'nullable|string',
            'is_branch' => 'nullable',
        ]);

        $branch->update([
            'brwp_code'  => $request->brwp_code,
            'name'       => $request->name,
            'location'   => $request->location,
            'is_branch'  => $request->has('is_branch') ? 1 : 0,
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ]);

        SystemLogsModel::addLog('Update Branch/Workplace', "'{$branch->brwp_code}' was updated.", Auth::id());

        return redirect()->back()->with('success', 'Branch/Workplace updated successfully.');
    }

    public function destroy($id)
    {
        $branch = BranchWorkplaceModel::findOrFail($id);

        // Soft delete style (using is_deleted column)
        $branch->update(['is_deleted' => 1]);

        SystemLogsModel::addLog('Delete Branch/Workplace', "'{$branch->brwp_code}' was deleted.", Auth::id());

        return redirect()->back()->with('success', 'Branch/Workplace deleted successfully.');
    }
}
