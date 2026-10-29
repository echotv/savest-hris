<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BranchModel;
use App\Models\SystemLogsModel;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = BranchModel::where('is_deleted', 0);

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        $branches = $query->orderBy('branch_code')->get();

        $areas = BranchModel::where('is_deleted', 0)
            ->whereNotNull('area')
            ->distinct()
            ->orderBy('area')
            ->pluck('area');

        $title = "Branches - Savest HRIS";
        $menu = "Company Setup";
        return view('branches', compact(['branches', 'areas', 'title', 'menu']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_code' => 'required|string|max:20|unique:branches,branch_code',
            'branch_name' => 'required|string|max:255',
            'area' => 'nullable|integer',
            'address' => 'nullable|string',
        ]);

        BranchModel::create([
            'branch_code' => $request->branch_code,
            'branch_name' => $request->branch_name,
            'area' => $request->area,
            'address' => $request->address,
            'is_visible' => $request->has('is_visible') ? 1 : 0,
            'is_deleted' => 0,
        ]);

        SystemLogsModel::addLog('Add New Branch', "Branch '{$request->branch_code}' ({$request->branch_name}) was created.", Auth::id());

        return redirect()->back()->with('success', 'Branch created successfully.');
    }

    public function update(Request $request, $branch_code)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'area' => 'nullable|integer',
            'address' => 'nullable|string',
        ]);

        $branch = BranchModel::findOrFail($branch_code);

        $branch->update([
            'branch_name' => $request->branch_name,
            'area' => $request->area,
            'address' => $request->address,
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ]);

        SystemLogsModel::addLog('Update Branch', "Branch '{$branch_code}' was updated.", Auth::id());

        return redirect()->back()->with('success', 'Branch updated successfully.');
    }

    public function destroy($branch_code)
    {
        $branch = BranchModel::findOrFail($branch_code);

        // Soft delete style (using is_deleted column)
        $branch->update(['is_deleted' => 1]);

        SystemLogsModel::addLog('Delete Branch', "Branch '{$branch_code}' was deleted.", Auth::id());

        return redirect()->back()->with('success', 'Branch deleted successfully.');
    }
}
