<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\BranchModel;
use App\Models\SystemLogsModel;
use Yajra\DataTables\Facades\DataTables;

class HolidayController extends Controller
{
    public function index()
    {
        if (!userCan('VIEW HOLIDAYS & NON-WORKING DAYS')) abort(403);

        $assignedBranches = userBranches();

        $branches = BranchModel::where('is_deleted', 0)
            ->when($assignedBranches !== null, fn($q) => empty($assignedBranches) ? $q->whereRaw('1=0') : $q->whereIn('branch_code', $assignedBranches))
            ->orderBy('branch_name')
            ->get();

        return view('timekeeping.holidays', [
            'title'    => 'Holidays & Non-Working Days - SAVEST HRIS',
            'menu'     => 'Timekeeping',
            'branches' => $branches,
        ]);
    }

    public function data(Request $request)
    {
        if (!userCan('VIEW HOLIDAYS & NON-WORKING DAYS')) abort(403);

        $assignedBranches = userBranches();

        $query = Holiday::where('is_deleted', 0)->with('branches');

        // Branch-scoped users see: all-branch holidays + holidays for their branches
        if ($assignedBranches !== null) {
            if (empty($assignedBranches)) {
                $query->whereRaw('1=0');
            } else {
                $query->where(function ($q) use ($assignedBranches) {
                    $q->whereDoesntHave('branches')
                      ->orWhereHas('branches', fn($b) => $b->whereIn('branches.branch_code', $assignedBranches));
                });
            }
        }

        if ($request->type && $request->type !== 'ALL') {
            $query->where('holiday_type', $request->type);
        }

        if ($request->year && $request->year !== 'ALL') {
            $query->whereYear('holiday_date', $request->year);
        }

        if ($request->search_value) {
            $query->where('holiday_name', 'like', '%' . $request->search_value . '%');
        }

        $query->orderBy('holiday_date', 'asc');

        $canEdit   = userCan('UPDATE HOLIDAYS & NON-WORKING DAYS');
        $canDelete = userCan('DELETE HOLIDAYS & NON-WORKING DAYS');

        return DataTables::of($query)
            ->addColumn('holiday_date_fmt', fn($r) => $r->holiday_date->format('M d, Y') . ' (' . $r->holiday_date->format('l') . ')')
            ->addColumn('holiday_type_badge', function ($r) {
                $map = [
                    'Regular Holiday'     => 'badge-light-danger',
                    'Special Non-Working' => 'badge-light-warning',
                    'Company'             => 'badge-light-primary',
                    'Government'          => 'badge-light-info',
                ];
                $cls = $map[$r->holiday_type] ?? 'badge-light-secondary';
                return '<span class="badge ' . $cls . '">' . $r->holiday_type . '</span>';
            })
            ->addColumn('duration_display', function ($r) {
                if ($r->is_whole_day || !$r->time_start || !$r->time_end) {
                    return '<span class="badge badge-light-success">Whole Day</span>';
                }
                $start = \Carbon\Carbon::parse($r->time_start)->format('g:i A');
                $end   = \Carbon\Carbon::parse($r->time_end)->format('g:i A');
                return '<span class="font-13 text-dark fw-medium">' . $start . ' - ' . $end . '</span>';
            })
            ->addColumn('is_recurring_badge', fn($r) => $r->is_recurring
                ? '<span class="badge badge-light-success">Yes</span>'
                : '<span class="badge badge-light-secondary">No</span>')
            ->addColumn('branches_display', function ($r) {
                if ($r->branches->isEmpty()) {
                    return '<span class="badge badge-light-secondary">All Branches</span>';
                }
                $html = '';
                foreach ($r->branches->take(3) as $b) {
                    $html .= '<span class="badge badge-light-info me-1" style="font-size:10px;">' . e($b->branch_name) . '</span>';
                }
                if ($r->branches->count() > 3) {
                    $html .= '<span class="badge badge-light-secondary" style="font-size:10px;">+' . ($r->branches->count() - 3) . ' more</span>';
                }
                return $html ?: '<span class="badge badge-light-secondary">All Branches</span>';
            })
            ->addColumn('action', function ($r) use ($canEdit, $canDelete) {
                $branchCodes = $r->branches->pluck('branch_code')->toArray();
                $btns = '<div class="d-flex align-items-center gap-2 pe-2">';
                if ($canEdit) {
                    $btns .= '<button class="border-0 bg-transparent text-primary p-0 editBtn" style="width:24px;height:24px;"
                        data-id="'        . $r->holiday_id   . '"
                        data-name="'      . htmlspecialchars($r->holiday_name, ENT_QUOTES) . '"
                        data-date="'      . $r->holiday_date->format('Y-m-d') . '"
                        data-type="'      . $r->holiday_type  . '"
                        data-whole-day="' . ($r->is_whole_day ? 1 : 0) . '"
                        data-time-start="' . ($r->time_start ? \Carbon\Carbon::parse($r->time_start)->format('H:i') : '') . '"
                        data-time-end="'   . ($r->time_end   ? \Carbon\Carbon::parse($r->time_end)->format('H:i')   : '') . '"
                        data-desc="'      . htmlspecialchars($r->description ?? '', ENT_QUOTES) . '"
                        data-recurring="' . $r->is_recurring  . '"
                        data-branches="'  . htmlspecialchars(json_encode($branchCodes), ENT_QUOTES) . '"
                        title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>';
                }
                if ($canDelete) {
                    $btns .= '<button class="border-0 bg-transparent text-danger p-0 deleteBtn" style="width:24px;height:24px;"
                        data-id="' . $r->holiday_id . '" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>';
                }
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['holiday_type_badge', 'duration_display', 'is_recurring_badge', 'branches_display', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        if (!userCan('ADD HOLIDAYS & NON-WORKING DAYS')) return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);

        $isWholeDay = $request->boolean('is_whole_day', true);

        $request->validate([
            'holiday_name' => 'required|string|max:150',
            'holiday_date' => 'required|date',
            'holiday_type' => 'required|string|max:50',
            'time_start'   => $isWholeDay ? 'nullable' : 'required|date_format:H:i',
            'time_end'     => $isWholeDay ? 'nullable' : 'required|date_format:H:i|after:time_start',
            'description'  => 'nullable|string|max:5000',
            'is_recurring' => 'nullable|boolean',
            'branches'     => 'nullable|array',
            'branches.*'   => 'string|exists:branches,branch_code',
        ]);

        $holiday = Holiday::create([
            'holiday_name' => $request->holiday_name,
            'holiday_date' => $request->holiday_date,
            'holiday_type' => $request->holiday_type,
            'is_whole_day' => $isWholeDay,
            'time_start'   => $isWholeDay ? null : $request->time_start,
            'time_end'     => $isWholeDay ? null : $request->time_end,
            'description'  => $request->description,
            'is_recurring' => $request->is_recurring ?? 0,
            'is_deleted'   => 0,
        ]);

        $holiday->branches()->sync($request->branches ?? []);

        SystemLogsModel::addLog('Add Holiday', "Holiday '{$request->holiday_name}' ({$request->holiday_type}) on {$request->holiday_date} was added.", auth()->user()?->id);

        return response()->json(['status' => 'success', 'message' => 'Holiday saved.']);
    }

    public function update(Request $request, int $id)
    {
        if (!userCan('UPDATE HOLIDAYS & NON-WORKING DAYS')) return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);

        $isWholeDay = $request->boolean('is_whole_day', true);

        $request->validate([
            'holiday_name' => 'required|string|max:150',
            'holiday_date' => 'required|date',
            'holiday_type' => 'required|string|max:50',
            'time_start'   => $isWholeDay ? 'nullable' : 'required|date_format:H:i',
            'time_end'     => $isWholeDay ? 'nullable' : 'required|date_format:H:i|after:time_start',
            'description'  => 'nullable|string|max:5000',
            'is_recurring' => 'nullable|boolean',
            'branches'     => 'nullable|array',
            'branches.*'   => 'string|exists:branches,branch_code',
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->update([
            'holiday_name' => $request->holiday_name,
            'holiday_date' => $request->holiday_date,
            'holiday_type' => $request->holiday_type,
            'is_whole_day' => $isWholeDay,
            'time_start'   => $isWholeDay ? null : $request->time_start,
            'time_end'     => $isWholeDay ? null : $request->time_end,
            'description'  => $request->description,
            'is_recurring' => $request->is_recurring ?? 0,
        ]);

        $holiday->branches()->sync($request->branches ?? []);

        SystemLogsModel::addLog('Update Holiday', "Holiday '{$request->holiday_name}' (ID: {$id}) was updated.", auth()->user()?->id);

        return response()->json(['status' => 'updated', 'message' => 'Holiday updated.']);
    }

    public function delete(int $id)
    {
        if (!userCan('DELETE HOLIDAYS & NON-WORKING DAYS')) return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);

        $holiday = Holiday::findOrFail($id);
        $holiday->update(['is_deleted' => 1]);
        SystemLogsModel::addLog('Delete Holiday', "Holiday '{$holiday->holiday_name}' (ID: {$id}) was deleted.", auth()->user()?->id);
        return response()->json(['status' => 'deleted', 'message' => 'Deleted.']);
    }
}
