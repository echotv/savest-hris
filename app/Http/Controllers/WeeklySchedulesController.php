<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeeklySchedule;
use App\Models\EmployeeWeeklySchedule;
use App\Models\EmployeeModel;
use App\Models\BranchModel;
use App\Models\SystemLogsModel;
use Carbon\Carbon;


class WeeklySchedulesController extends Controller
{
   public function load_ws($employee_id)
{



    // All days (fixed order)
    $days = [
        'Monday', 'Tuesday', 'Wednesday',
        'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];

    // Get existing schedules (keyed by day)
    $existing = WeeklySchedule::where('employee_id', $employee_id)
        ->get()
        ->keyBy('day_of_week');

    // Build complete schedule (no missing days)
    $weeklySched = collect($days)->map(function ($day) use ($existing) {

        if (isset($existing[$day])) {
            return $existing[$day];
        }

        // Default row if missing
        return (object)[
            'day_of_week' => $day,
            'start_time'  => '08:00:00',
            'end_time'    => '17:00:00',
            'break_start' => null,
            'break_end'   => null,
            'is_day_off'  => 0,
        ];
    });

    $employee = EmployeeModel::where('employee_id', $employee_id)
        ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
        ->join('positions', 'employee.position_id', '=', 'positions.position_id')
        ->first();

    abort_if(!$employee, 404, 'Employee not found.');

    $title = 'Weekly Schedules - SAVEST HRIS';
    $menu = 'Employee';

    return view('employee.weekly_schedules', compact(
        'employee',
        'weeklySched',
        'title',
        'menu'
    ));
}

public function load_update($ews_id)
{



    // All days (fixed order)
    $days = [
        'Monday', 'Tuesday', 'Wednesday',
        'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];
    $ews = EmployeeWeeklySchedule::findOrFail($ews_id);

    // Get existing schedules (keyed by day)
    $existing = WeeklySchedule::where('ews_id', $ews->ews_id)
        ->get()
        ->keyBy('day_of_week');

    // Build complete schedule (no missing days)
    $weeklySched = collect($days)->map(function ($day) use ($existing) {

        if (isset($existing[$day])) {
            return $existing[$day];
        }

        // Default row if missing
        return (object)[
            'day_of_week' => $day,
            'start_time'  => '08:00:00',
            'end_time'    => '17:00:00',
            'break_start' => null,
            'break_end'   => null,
            'is_day_off'  => 0,
        ];
    });



    $title = 'Weekly Schedules - SAVEST HRIS';
    $menu = 'Employee';

    $employee = EmployeeModel::where('employee.employee_id', $ews->employee_id)
    ->leftJoin('branches', 'employee.branch_code', '=', 'branches.branch_code')
    ->leftJoin('positions', 'employee.position_id', '=', 'positions.position_id')
    ->first();

    abort_if(!$employee, 404, 'Employee not found.');

$effective_date = $ews->effective_date;
$end_date = $ews->end_date;
return view('employee.update_weekly_schedules', compact(
    'employee',
    'effective_date',
    'end_date',
    'weeklySched',
    'title',
    'menu'
))->with('ews_id', $ews->ews_id);
}

public function load_schedules($employee_id)
{
    $employee = EmployeeModel::where('employee.employee_id', $employee_id)
        ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
        ->join('positions', 'employee.position_id', '=', 'positions.position_id')
        ->first();

    abort_if(!$employee, 404, 'Employee not found.');

    $weeklySchedules = EmployeeWeeklySchedule::with('schedules')
        ->where('employee_id', $employee_id)
        ->where('is_deleted', 0)
        ->orderByDesc('effective_date')
        ->get();

    $oldest_record_id = optional(
    EmployeeWeeklySchedule::where('employee_id', $employee_id)
        ->where('is_deleted', 0)
        ->orderBy('effective_date', 'asc')
        ->first()
)->ews_id;

$current_record_id = optional(
    EmployeeWeeklySchedule::where('employee_id', $employee_id)->where('is_deleted', 0)
        ->orderBy('effective_date', 'desc')
        ->first()
)->ews_id;

    return view('employee.employee_weekly_schedules', [
        'employee' => $employee,
        'current_record_id' => $current_record_id,
        'oldest_record_id' => $oldest_record_id,
        'weeklySchedules' => $weeklySchedules,
        'title' => 'Employee Weekly Schedules - SAVEST HRIS',
        'menu' => 'Employee'
    ]);
}
public function load_wsl(Request $request)
{
    $branches = BranchModel::where('is_deleted', 0)->get();

    // All current (active, not deleted) employees, whether or not they have a schedule yet
    $employees = EmployeeModel::join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')
        ->where('employee.is_deleted', 0)
        ->where('employment_status.is_current_employee', 1)

        // RBAC FILTER
        ->whereIn('employee.branch_code', function ($q) {
            $q->select('branch_code')
                ->from('assigned_branches')
                ->where('ut_id', Auth::user()->ut_id);
        });

    // UI FILTER
    if ($request->branch_code && $request->branch_code != 'All') {
        $employees->where('employee.branch_code', $request->branch_code);
    }

    $employees = $employees
        ->select(
            'employee.employee_id',
            'employee.lastname',
            'employee.firstname',
            'employee.middlename',
            'employee.suffix',
            'employee.employee_number',
            'employee.branch_code'
        )
        ->get();

    // Record in effect as of today (Asia/Manila), per employee: non-deleted,
    // effective_date <= today, and either open-ended (end_date null) or still running.
    $today = Carbon::now('Asia/Manila')->toDateString();

    $current = EmployeeWeeklySchedule::with('schedules')
        ->whereIn('employee_id', $employees->pluck('employee_id'))
        ->where('is_deleted', 0)
        ->whereDate('effective_date', '<=', $today)
        ->where(function ($q) use ($today) {
            $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
        })
        ->orderByDesc('effective_date')
        ->get()
        ->unique('employee_id')
        ->keyBy('employee_id');

    $weeklySchedules = $employees
        ->each(fn ($emp) => $emp->setRelation('latest_ws', $current->get($emp->employee_id)))
        // UI FILTER: with/without a schedule in effect as of today
        ->when($request->has_schedule === 'with', fn ($c) => $c->filter(fn ($emp) => $emp->latest_ws !== null))
        ->when($request->has_schedule === 'without', fn ($c) => $c->filter(fn ($emp) => $emp->latest_ws === null))
        // alphabetical by employee name
        ->sortBy([
            fn ($a, $b) => strcasecmp($a->lastname, $b->lastname),
            fn ($a, $b) => strcasecmp($a->firstname, $b->firstname),
            fn ($a, $b) => strcasecmp((string) $a->middlename, (string) $b->middlename),
        ])
        ->values();

    return view('employee.weekly_schedules_list', [
        'branches' => $branches,
        'weeklySchedules' => $weeklySchedules,
        'title' => 'List of Weekly Schedules - SAVEST HRIS',
        'menu' => 'Employee'
    ]);
}

public function load_bulk_update()
{
    $employees = EmployeeModel::join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')
    ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
    ->where('employment_status.is_current_employee', 1)
    ->where('employee.is_deleted', 0)
    ->select(
        'employee.employee_id',
        'employee.lastname',
        'employee.firstname',
        'employee.middlename',
        'employee.suffix',
        'employee.employee_number',
        'employee.branch_code',
        'employment_status.empl_status',
        'branches.branch_name'
    )
    ->get();

    return view('employee.bulk_update_schedules', [
        'employees' => $employees,
        'title' => 'Bulk Update Schedules - SAVEST HRIS',
        'menu' => 'Employee'
    ]);
}

public function store_ws(Request $request, $employee_id)
{
    abort_unless(EmployeeModel::where('employee_id', $employee_id)->exists(), 404);

    $request->validate([
        'effective_date'  => 'required|date',
        'end_date'        => 'nullable|date|after_or_equal:effective_date',
        'day_of_week'     => 'required|array|min:1',
        'day_of_week.*'   => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'start_time'      => 'required|array',
        'start_time.*'    => 'nullable|date_format:H:i',
        'end_time'        => 'required|array',
        'end_time.*'      => 'nullable|date_format:H:i',
        'break_start'     => 'nullable|array',
        'break_start.*'   => 'nullable|date_format:H:i',
        'break_end'       => 'nullable|array',
        'break_end.*'     => 'nullable|date_format:H:i',
        'is_day_off'      => 'nullable|array',
        'is_day_off.*'    => 'nullable|boolean',
    ]);

    try {

        $hasActive = EmployeeWeeklySchedule::where('employee_id', $employee_id)
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

        $exists = EmployeeWeeklySchedule::where('employee_id', $employee_id)
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
                'effective_date' => 'The date range overlaps with an existing weekly schedule record.'
            ]);
        }

        $endDate = $request->end_date ?: null;

        $data = [];
        $ews = EmployeeWeeklySchedule::create([
            'employee_id'    => $employee_id,
            'effective_date' => $request->effective_date,
            'end_date'       => $endDate,
        ]);

        foreach ($request->day_of_week as $i => $day) {

            $isDayOff = $request->is_day_off[$i] ?? 0;

            $start = $request->start_time[$i] ?? null;
            $end   = $request->end_time[$i] ?? null;

            if ($isDayOff) {
                $start = '00:00:00';
                $end   = '00:00:00';
            } else {
                $start = $start ? $start . ':00' : '08:00:00';
                $end   = $end   ? $end . ':00'   : '17:00:00';
            }

            $data[] = [
                'employee_id'    => $employee_id,
                'ews_id'         => $ews->ews_id,
                'effective_date' => $request->effective_date,
                'end_date'       => $endDate,
                'day_of_week'    => $day,
                'start_time'     => $start,
                'end_time'       => $end,
                'break_start'    => !empty($request->break_start[$i]) ? $request->break_start[$i] . ':00' : null,
                'break_end'      => !empty($request->break_end[$i]) ? $request->break_end[$i] . ':00' : null,
                'is_day_off'     => $isDayOff,
            ];
        }

        WeeklySchedule::where('ews_id', $ews->ews_id)->delete();
        WeeklySchedule::insert($data);

        SystemLogsModel::addLog('Add Weekly Schedule', "Weekly schedule added for employee (ID: {$employee_id}), effective {$request->effective_date}.", Auth::id());

        return redirect()->route('schedules.load', $employee_id)
            ->with('success', 'Weekly schedule saved successfully!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withErrors(['error' => $e->getMessage()])
            ->withInput();
    }
}



public function update(Request $request, $id)
{
    $request->validate([
        'effective_date' => 'required|date',
        'end_date'       => 'nullable|date|after_or_equal:effective_date',
        'day_of_week'    => 'required|array|min:1',
        'day_of_week.*'  => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'start_time'     => 'required|array',
        'start_time.*'   => 'nullable|date_format:H:i',
        'end_time'       => 'required|array',
        'end_time.*'     => 'nullable|date_format:H:i',
        'break_start'    => 'nullable|array',
        'break_start.*'  => 'nullable|date_format:H:i',
        'break_end'      => 'nullable|array',
        'break_end.*'    => 'nullable|date_format:H:i',
        'is_day_off'     => 'nullable|array',
        'is_day_off.*'   => 'nullable|boolean',
    ]);

    $ews = EmployeeWeeklySchedule::findOrFail($id);

    $newStart = $request->effective_date;
    $newEnd   = $request->end_date;

    $exists = EmployeeWeeklySchedule::where('employee_id', $ews->employee_id)
        ->where('is_deleted', 0)
        ->where('ews_id', '!=', $ews->ews_id)
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
            'effective_date' => 'The date range overlaps with an existing weekly schedule record.'
        ]);
    }

    $endDate = $request->end_date ?: null;

    $data = [];

    EmployeeWeeklySchedule::where('ews_id', $ews->ews_id)->update([
        'effective_date' => $request->effective_date,
        'end_date'       => $endDate,
    ]);

    foreach ($request->day_of_week as $i => $day) {

        $isDayOff = $request->is_day_off[$i] ?? 0;

        $start = $request->start_time[$i] ?? null;
        $end   = $request->end_time[$i] ?? null;

        if ($isDayOff) {
            $start = '00:00:00';
            $end   = '00:00:00';
        } else {
            $start = $start ? $start . ':00' : '08:00:00';
            $end   = $end   ? $end . ':00'   : '17:00:00';
        }

        $data[] = [
            'employee_id'    => $ews->employee_id,
            'ews_id'         => $ews->ews_id,
            'effective_date' => $request->effective_date,
            'end_date'       => $endDate,
            'day_of_week'    => $day,
            'start_time'     => $start,
            'end_time'       => $end,
            'break_start'    => !empty($request->break_start[$i]) ? $request->break_start[$i] . ':00' : null,
            'break_end'      => !empty($request->break_end[$i]) ? $request->break_end[$i] . ':00' : null,
            'is_day_off'     => $isDayOff,
        ];
    }

    WeeklySchedule::where('ews_id', $ews->ews_id)->delete();
    WeeklySchedule::insert($data);

    SystemLogsModel::addLog('Update Weekly Schedule', "Weekly schedule (ID: {$id}) updated.", Auth::id());

    return back()->with('success', 'Weekly schedules updated successfully.');
}

public function bulk_update(Request $request)
    {
        $request->validate([
            'employee_id'      => 'required|array|min:1',
            'employee_id.*'    => 'required|exists:employee,employee_id',

            'effective_date'   => 'required|date',
            'end_date'         => 'nullable|date|after_or_equal:effective_date',

            'day_of_week'      => 'required|array|min:1',
            'day_of_week.*'    => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'       => 'required|array',
            'start_time.*'     => 'nullable|date_format:H:i',
            'end_time'         => 'required|array',
            'end_time.*'       => 'nullable|date_format:H:i',
            'break_start'      => 'nullable|array',
            'break_start.*'    => 'nullable|date_format:H:i',
            'break_end'        => 'nullable|array',
            'break_end.*'      => 'nullable|date_format:H:i',
            'is_day_off'       => 'nullable|array',
            'is_day_off.*'     => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {

            // =====================================================
            // GET EMPLOYEES WITH FULLNAME
            // =====================================================
            $employees = EmployeeModel::join(
                    'employment_status',
                    'employee.es_id',
                    '=',
                    'employment_status.es_id'
                )
                ->join(
                    'branches',
                    'employee.branch_code',
                    '=',
                    'branches.branch_code'
                )
                ->where('employment_status.is_current_employee', 1)
                ->where('employee.is_deleted', 0)
                ->select(
                    'employee.employee_id',
                    'employee.lastname',
                    'employee.firstname',
                    'employee.middlename',
                    'employee.suffix',
                    'employee.employee_number',
                    'employee.branch_code',
                    'employment_status.empl_status',
                    'branches.branch_name',

                    DB::raw("
                        CONCAT(
                            employee.lastname, ', ',
                            employee.firstname,

                            IF(
                                employee.suffix IS NOT NULL
                                AND employee.suffix != '',
                                CONCAT(' ', employee.suffix),
                                ''
                            ),

                            IF(
                                employee.middlename IS NOT NULL
                                AND employee.middlename != '',
                                CONCAT(' ', UPPER(LEFT(employee.middlename, 1)), '.'),
                                ''
                            )
                        ) as fullname
                    ")
                )
                ->get();

            // =====================================================
            // LOOP EMPLOYEES
            // =====================================================
            foreach ($request->employee_id as $employeeId) {

                $employee = $employees->firstWhere('employee_id', $employeeId);

                // =================================================
                // BLOCK IF ACTIVE RECORD EXISTS
                // =================================================
                $hasActive = EmployeeWeeklySchedule::where('employee_id', $employeeId)
                    ->where('is_deleted', 0)
                    ->whereNull('end_date')
                    ->exists();

                if ($hasActive) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'effective_date' =>
                            'Cannot add a new record for employee: '
                            . $employee->fullname .
                            '. Please set an end date for their current active record first.'
                    ]);
                }

                // =================================================
                // CHECK OVERLAPPING SCHEDULE
                // =================================================
                $newStart = $request->effective_date;
                $newEnd   = $request->end_date;

                $exists = EmployeeWeeklySchedule::where('employee_id', $employeeId)
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
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'effective_date' =>
                            'The date range overlaps with an existing schedule for employee: '
                            . $employee->fullname
                    ]);
                }

                // =================================================
                // CREATE NEW EMPLOYEE WEEKLY SCHEDULE
                // =================================================
                $endDate = $request->end_date ?: null;

                $ews = EmployeeWeeklySchedule::create([
                    'employee_id'    => $employeeId,
                    'effective_date' => $request->effective_date,
                    'end_date'       => $endDate,
                    'is_deleted'     => 0,
                ]);

                // =================================================
                // PREPARE WEEKLY SCHEDULE DATA
                // =================================================
                $data = [];

                foreach ($request->day_of_week as $i => $day) {

                    $isDayOff = isset($request->is_day_off[$i])
                        ? $request->is_day_off[$i]
                        : 0;

                    $start = $request->start_time[$i] ?? null;
                    $end   = $request->end_time[$i] ?? null;

                    if ($isDayOff) {

                        $start = '00:00:00';
                        $end   = '00:00:00';

                    } else {

                        $start = $start
                            ? $start . ':00'
                            : '08:00:00';

                        $end = $end
                            ? $end . ':00'
                            : '17:00:00';
                    }

                    $data[] = [
                        'employee_id'    => $employeeId,
                        'ews_id'         => $ews->ews_id,
                        'effective_date' => $request->effective_date,
                        'end_date'       => $endDate,
                        'day_of_week'    => $day,

                        'start_time'     => $start,
                        'end_time'       => $end,

                        'break_start'    => !empty($request->break_start[$i])
                            ? $request->break_start[$i] . ':00'
                            : null,

                        'break_end'      => !empty($request->break_end[$i])
                            ? $request->break_end[$i] . ':00'
                            : null,

                        'is_day_off'     => $isDayOff,

                    ];
                }

                // =================================================
                // INSERT WEEKLY SCHEDULES
                // =================================================
                WeeklySchedule::insert($data);
            }

            DB::commit();

            $empCount = count($request->employee_id);
            SystemLogsModel::addLog('Bulk Update Weekly Schedules', "Weekly schedules bulk-updated for {$empCount} employee(s), effective {$request->effective_date}.", Auth::id());

            return back()->with(
                'success',
                'Weekly schedules were successfully updated for selected employees.'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

public function destroy($id)
{
    $ews = EmployeeWeeklySchedule::findOrFail($id);

    // Soft delete current record
    $ews->update([
        'is_deleted' => 1
    ]);

    WeeklySchedule::where('ews_id', $ews->ews_id)->delete();

    SystemLogsModel::addLog('Delete Weekly Schedule', "Weekly schedule (ID: {$id}) deleted.", Auth::id());


    // Get the latest previous active record (before deleted one)
    $previous = EmployeeWeeklySchedule::where('employee_id', $ews->employee_id)
        ->where('is_deleted', 0)
        ->where('ews_id', '!=', $ews->ews_id)
        ->where('effective_date', '<', $ews->effective_date)
        ->orderBy('effective_date', 'desc')
        ->first();

    // Restore current active record
    if ($previous) {
        $previous->update([
            'end_date' => null
        ]);
    }

    return back()->with('success', 'Weekly Schedule was deleted successfully.');
}


}
