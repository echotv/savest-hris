<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BranchAreaMovement;
use App\Models\BranchWorkplaceModel;
use App\Models\SystemLogsModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BranchAreaMovementController extends Controller
{
    /**
     * Every branch (is_branch = 1 only — plain workplaces are never area-grouped),
     * grouped by its current area assignment ("current" = the one non-deleted
     * record per branch with end_date IS NULL). Branches with no assignment yet
     * are listed separately as unassigned.
     */
    public function index()
    {
        if (!userCan('VIEW AREA GROUPING')) abort(403);

        $branches = BranchWorkplaceModel::where('is_branch', 1)
            ->where('is_deleted', 0)
            ->orderBy('brwp_code')
            ->get();

        $current = BranchAreaMovement::where('is_deleted', 0)
            ->whereNull('end_date')
            ->get()
            ->keyBy('brwp_id');

        $branches->each(function ($branch) use ($current) {
            $branch->current_area_movement = $current->get($branch->id);
        });

        $grouped = $branches->filter(fn ($b) => $b->current_area_movement)
            ->groupBy(fn ($b) => $b->current_area_movement->area)
            ->sortKeys();

        $unassigned = $branches->filter(fn ($b) => !$b->current_area_movement)->values();

        $records = BranchAreaMovement::where('branch_area_movements.is_deleted', 0)
            ->join('branches_workplaces', 'branch_area_movements.brwp_id', '=', 'branches_workplaces.id')
            ->select('branch_area_movements.*', 'branches_workplaces.brwp_code', 'branches_workplaces.name as branch_name')
            ->orderBy('branch_area_movements.area')
            ->orderBy('branch_area_movements.effective_date', 'desc')
            ->get();

        // History table is organized per Effective/End Date (one row per date range),
        // not per branch — every branch sharing that date range is grouped inside it
        // by area, matching how they're assigned together in the Add form.
        $dateGroups = $records
            ->groupBy(fn ($r) => $r->effective_date->toDateString() . '|' . ($r->end_date?->toDateString() ?? 'present'))
            ->map(function ($group) {
                return (object) [
                    'effective_date' => $group->first()->effective_date,
                    'end_date'       => $group->first()->end_date,
                    'areas'          => $group->groupBy('area')->sortKeys(),
                ];
            })
            ->sortByDesc(fn ($g) => $g->effective_date)
            ->values();

        $title = 'Area Grouping - SAVEST HRIS';
        $menu = 'Company Setup';

        return view('area_grouping', compact('grouped', 'unassigned', 'branches', 'records', 'dateGroups', 'title', 'menu'));
    }

    /**
     * One Effective/End Date shared across any number of Area rows, each row
     * assigning one or more branches to that area in a single submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'effective_date'     => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:effective_date',
            'rows'                => 'required|array|min:1',
            'rows.*.area'         => 'required|integer|min:1',
            'rows.*.brwp_ids'     => 'required|array|min:1',
            'rows.*.brwp_ids.*'   => 'integer|exists:branches_workplaces,id',
        ]);

        $conflicts = $this->findConflicts($request->rows, $request->effective_date, $request->end_date);
        if ($conflicts) {
            return back()->withInput()->withErrors(['rows' => $conflicts]);
        }

        [$saved, $skipped] = $this->saveRows($request->rows, $request->effective_date, $request->end_date);

        $message = count($saved) . ' area assignment(s) saved.';
        if ($skipped) {
            $message .= ' Skipped: ' . implode(', ', $skipped) . '.';
        }

        SystemLogsModel::addLog('Add Area Assignment', ($saved ? implode(', ', $saved) : 'none') . '. ' . ($skipped ? 'Skipped: ' . implode(', ', $skipped) . '.' : ''), Auth::id());

        return back()->with($saved ? 'success' : 'error', $message);
    }

    /**
     * Bulk-edit an entire date-group at once (everything sharing the same
     * Effective/End Date in the Assignment History table) instead of editing
     * one branch's record at a time. The group's original records are
     * retired and replaced with whatever rows/branches/dates are submitted.
     */
    public function updateGroup(Request $request)
    {
        $request->validate([
            'bam_ids'            => 'required|array|min:1',
            'bam_ids.*'          => 'integer|exists:branch_area_movements,bam_id',
            'effective_date'     => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:effective_date',
            'rows'                => 'required|array|min:1',
            'rows.*.area'         => 'required|integer|min:1',
            'rows.*.brwp_ids'     => 'required|array|min:1',
            'rows.*.brwp_ids.*'   => 'integer|exists:branches_workplaces,id',
        ]);

        $conflicts = $this->findConflicts($request->rows, $request->effective_date, $request->end_date, $request->bam_ids);
        if ($conflicts) {
            return back()->withInput()->withErrors(['rows' => $conflicts]);
        }

        BranchAreaMovement::whereIn('bam_id', $request->bam_ids)->update(['is_deleted' => 1]);

        [$saved, $skipped] = $this->saveRows($request->rows, $request->effective_date, $request->end_date);

        $message = count($saved) . ' area assignment(s) saved.';
        if ($skipped) {
            $message .= ' Skipped: ' . implode(', ', $skipped) . '.';
        }

        SystemLogsModel::addLog('Update Area Assignment', ($saved ? implode(', ', $saved) : 'none') . '. ' . ($skipped ? 'Skipped: ' . implode(', ', $skipped) . '.' : ''), Auth::id());

        return back()->with($saved ? 'success' : 'error', $message);
    }

    /**
     * Shared by store() and updateGroup(): checked upfront, before anything is
     * saved. A branch's currently open assignment is never a conflict — it's
     * superseded automatically (see closePreviousOpen()) — unless the new
     * effective date doesn't actually come after it, or the new range overlaps
     * an already-closed historical record, either of which blocks the whole
     * submission with a validation error instead of being silently skipped.
     * $excludeBamIds lets updateGroup() ignore the very records it's about to
     * replace.
     */
    private function findConflicts(array $rows, string $effectiveDate, ?string $endDate, array $excludeBamIds = []): array
    {
        $conflicts = [];

        foreach ($rows as $row) {
            foreach ($row['brwp_ids'] as $brwpId) {
                $branch = BranchWorkplaceModel::find($brwpId);
                if (!$branch) {
                    continue;
                }

                $active = $this->activeOpenRecord($brwpId, $excludeBamIds);
                if ($active && strtotime($effectiveDate) <= strtotime($active->effective_date)) {
                    $conflicts[] = "{$branch->brwp_code}: the new effective date must be after the current assignment's effective date ({$active->effective_date->toDateString()}).";
                    continue;
                }

                if ($this->overlapsClosed($brwpId, $effectiveDate, $endDate, $excludeBamIds)) {
                    $conflicts[] = "{$branch->brwp_code}: the date range overlaps with an existing area assignment for this branch.";
                }
            }
        }

        return $conflicts;
    }

    /**
     * Shared by store() and updateGroup(): create one BranchAreaMovement per
     * (area, branch) pair, skipping branches that aren't actual branches or
     * whose dates overlap a closed historical record. A currently open
     * assignment for the branch is superseded (end-dated to the day before
     * the new one starts) rather than blocking the new row.
     */
    private function saveRows(array $rows, string $effectiveDate, ?string $endDate): array
    {
        $saved   = [];
        $skipped = [];

        foreach ($rows as $row) {
            foreach ($row['brwp_ids'] as $brwpId) {
                $branch = BranchWorkplaceModel::find($brwpId);

                if (!$branch || !$branch->is_branch) {
                    $skipped[] = ($branch->brwp_code ?? "ID:{$brwpId}") . ' (not a branch)';
                    continue;
                }

                if ($this->overlapsClosed($brwpId, $effectiveDate, $endDate)) {
                    $skipped[] = "{$branch->brwp_code} (overlapping dates)";
                    continue;
                }

                $this->closePreviousOpen($brwpId, $effectiveDate);

                BranchAreaMovement::create([
                    'brwp_id'        => $brwpId,
                    'area'           => $row['area'],
                    'effective_date' => $effectiveDate,
                    'end_date'       => $endDate ?: null,
                ]);

                $saved[] = "{$branch->brwp_code} → Area {$row['area']}";
            }
        }

        return [$saved, $skipped];
    }

    public function update(Request $request, $id)
    {
        $movement = BranchAreaMovement::findOrFail($id);

        $request->validate([
            'area'           => 'required|integer|min:1',
            'effective_date' => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:effective_date',
        ]);

        $active = $this->activeOpenRecord($movement->brwp_id, [$movement->bam_id]);
        if ($active && strtotime($request->effective_date) <= strtotime($active->effective_date)) {
            return back()->withInput()->withErrors([
                'effective_date' => "The new effective date must be after the current assignment's effective date ({$active->effective_date->toDateString()})."
            ]);
        }

        if ($this->overlapsClosed($movement->brwp_id, $request->effective_date, $request->end_date, [$movement->bam_id])) {
            return back()->withInput()->withErrors([
                'effective_date' => 'The date range overlaps with an existing area assignment for this branch.'
            ]);
        }

        if ($active) {
            $active->update(['end_date' => Carbon::parse($request->effective_date)->subDay()->toDateString()]);
        }

        $movement->update([
            'area'           => $request->area,
            'effective_date' => $request->effective_date,
            'end_date'       => $request->end_date ?: null,
        ]);

        SystemLogsModel::addLog('Update Area Assignment', "Area assignment (ID: {$id}) updated.", Auth::id());

        return back()->with('success', 'Area assignment updated successfully.');
    }

    public function destroy($id)
    {
        $movement = BranchAreaMovement::findOrFail($id);
        $movement->update(['is_deleted' => 1]);

        SystemLogsModel::addLog('Delete Area Assignment', "Area assignment (ID: {$id}) deleted.", Auth::id());

        // Re-open the previous assignment for this branch, if any.
        $previous = BranchAreaMovement::where('brwp_id', $movement->brwp_id)
            ->where('is_deleted', 0)
            ->where('bam_id', '!=', $movement->bam_id)
            ->where('effective_date', '<', $movement->effective_date)
            ->orderBy('effective_date', 'desc')
            ->first();

        if ($previous) {
            $previous->update(['end_date' => null]);
        }

        return back()->with('success', 'Area assignment deleted successfully.');
    }

    /**
     * The one non-deleted, currently open (end_date IS NULL) record for this
     * branch, if any — the record that a new/edited assignment would supersede.
     */
    private function activeOpenRecord($brwpId, array $excludeIds = [])
    {
        return BranchAreaMovement::where('brwp_id', $brwpId)
            ->where('is_deleted', 0)
            ->whereNull('end_date')
            ->when($excludeIds, fn ($q) => $q->whereNotIn('bam_id', $excludeIds))
            ->first();
    }

    /**
     * Ends the branch's currently open assignment the day before the new one
     * starts, so a fresh assignment supersedes it instead of conflicting with it.
     */
    private function closePreviousOpen($brwpId, $newEffectiveDate, array $excludeIds = []): void
    {
        $active = $this->activeOpenRecord($brwpId, $excludeIds);

        if ($active) {
            $active->update(['end_date' => Carbon::parse($newEffectiveDate)->subDay()->toDateString()]);
        }
    }

    /**
     * Overlap against already-closed (end_date IS NOT NULL) historical records
     * only — an open record is handled separately via closePreviousOpen().
     */
    private function overlapsClosed($brwpId, $newStart, $newEnd, array $excludeIds = [])
    {
        return BranchAreaMovement::where('brwp_id', $brwpId)
            ->where('is_deleted', 0)
            ->whereNotNull('end_date')
            ->when($excludeIds, fn ($q) => $q->whereNotIn('bam_id', $excludeIds))
            ->where(function ($q) use ($newStart, $newEnd) {
                if ($newEnd) {
                    $q->whereDate('effective_date', '<=', $newEnd)
                      ->whereDate('end_date', '>=', $newStart);
                } else {
                    $q->whereDate('end_date', '>=', $newStart);
                }
            })
            ->exists();
    }
}
