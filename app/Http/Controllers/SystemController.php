<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use App\Models\UserTypeModel;
use App\Models\UserPrivilegeModel;
use App\Models\AssignedBranchModel;
use App\Models\BranchModel;
use App\Models\SalaryGrade;
use App\Models\PositionsModel;
use App\Models\EmploymentStatusModel;
use App\Models\BiometricId;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class SystemController extends Controller
{
    public function load_dashboard()
    {
        $today = Carbon::now('Asia/Manila')->format('m-d');

        // Every non-deleted employee visible to this user's assigned branches —
        // the shared base population for the breakdown charts below. A function
        // (not a query built once) so each call starts a fresh builder instead
        // of accumulating conditions from a previous use.
        $employeesBase = function () {
            return AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
                ->where('employee.is_deleted', 0)
                ->where('assigned_branches.ut_id', Auth::user()->ut_id);
        };

        $totalEmployees = $employeesBase()->count();

        // Weekly Schedule: has at least one non-deleted schedule record vs none at all.
        $schedWith = $employeesBase()
            ->whereExists(function ($q) {
                $q->selectRaw('1')
                  ->from('employee_weekly_schedules')
                  ->whereColumn('employee_weekly_schedules.employee_id', 'employee.employee_id')
                  ->where('employee_weekly_schedules.is_deleted', 0);
            })->count();
        $schedWithout = max(0, $totalEmployees - $schedWith);

        // Position: has a position assigned vs not yet (employee.position_id is
        // required on registration, but legacy/imported rows can still be null).
        $positionWith = $employeesBase()
            ->whereNotNull('employee.position_id')
            ->count();
        $positionWithout = max(0, $totalEmployees - $positionWith);

        // Employment status breakdown (employee.es_id is likewise a synced "current value").
        $statusBreakdown = $employeesBase()
            ->join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')
            ->select('employment_status.empl_status', DB::raw('count(*) as total'))
            ->groupBy('employment_status.empl_status')
            ->orderByDesc('total')
            ->get();

        // Salary grade: has at least one non-deleted salary_grade_movement row vs
        // none — there is no cache column for this on employee (unlike position/
        // status), so "has a grade" can only be checked via that movement table.
        $salaryGradeWith = $employeesBase()
            ->whereExists(function ($q) {
                $q->selectRaw('1')
                  ->from('salary_grade_movement')
                  ->whereColumn('salary_grade_movement.employee_id', 'employee.employee_id')
                  ->where('salary_grade_movement.is_deleted', 0);
            })->count();
        $salaryGradeWithout = max(0, $totalEmployees - $salaryGradeWith);

        // Biometric ID: has at least one record vs none at all.
        $biometricWith = $employeesBase()
            ->whereExists(function ($q) {
                $q->selectRaw('1')
                  ->from('biometric_ids')
                  ->whereColumn('biometric_ids.employee_id', 'employee.employee_id')
                  ->where('biometric_ids.is_deleted', 0);
            })->count();
        $biometricWithout = max(0, $totalEmployees - $biometricWith);

        $data =[
            'title' => 'Dashboard - SAVEST HRIS',
            'menu' => 'Dashboard',
            'num_active' => AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')
            ->where('employee.is_deleted', 0, )
       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
       ->where('employment_status.is_current_employee', 1)->count(),
        'todays_birthdays' =>  AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')

        ->where('employee.is_deleted', 0, )
       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
                ->where('employment_status.is_current_employee', 1)
                ->whereRaw("DATE_FORMAT(employee.date_of_birth, '%m-%d') = ?", [$today])
                ->get(),
            'todays_work_anniv' =>  AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')

            ->where('employee.is_deleted', 0, )

       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
                ->where('employment_status.is_current_employee', 1)
                ->whereRaw("DATE_FORMAT(employee.date_employed, '%m-%d') = ?", [$today])
                // Only real anniversaries — at least the 1st year (skip staff hired this year).
                ->whereRaw("TIMESTAMPDIFF(YEAR, employee.date_employed, CURDATE()) >= 1")
                ->get(),
            'logs' => SystemLogsModel::join('users', 'system_logs.user_id', '=', 'users.id')->orderBy('log_date', 'DESC')
                ->limit(6)
                ->get(),

            // Count cards
            'num_salary_grades' => SalaryGrade::where('is_active', 1)->count(),
            'num_positions' => PositionsModel::where('is_deleted', 0)->where('is_archived', 0)->count(),
            'num_employment_statuses' => EmploymentStatusModel::where('is_deleted', 0)->count(),
            'num_biometric_ids' => BiometricId::where('is_deleted', 0)->count(),

            // Pie charts
            'sched_with' => $schedWith,
            'sched_without' => $schedWithout,
            'position_with' => $positionWith,
            'position_without' => $positionWithout,
            'status_breakdown' => $statusBreakdown,
            'salary_grade_with' => $salaryGradeWith,
            'salary_grade_without' => $salaryGradeWithout,
            'biometric_with' => $biometricWith,
            'biometric_without' => $biometricWithout,
        ];
        return view("dashboard", $data);
    }
    public function load_system_logs(){
        $today = Carbon::now('Asia/Manila')->format('Y-m-d');
        $data = [
            'title' => 'System Logs - SAVEST HRIS',
            'menu' => 'Settings',
            'logs' => SystemLogsModel::get_logs($today, 'Logged in'),
            'users' => User::where('is_active', 1)->get(),
            'actions' => SystemLogsModel::select('action')
                ->distinct()
                ->orderBy('action', 'asc')
                ->get(),
            'filtered_data' => [
                'user_id'=>'All',
                'action'=>'Logged in',
                'log_date'=>$today,
            ]
        ];
        return view('system/system_logs', $data);
    }

    public function logs_filter(Request $request){
        // "All dates" checkbox — when ticked the date field is disabled (so no
        // log_date is posted); fall back to the 'All' sentinel = no date filter.
        $logDate = $request->boolean('all_dates') ? 'All' : $request->log_date;

        $data =[
            'title' => 'System Logs - SAVEST HRIS',
            'menu' => 'Settings',
            'logs' => SystemLogsModel::get_logs($logDate, $request->action, $request->user_id),
            'users' => User::where('is_active', 1)->get(),
            'actions' => SystemLogsModel::select('action')
                ->distinct()
                ->orderBy('action', 'asc')
                ->get(),
            'filtered_data' => $request->all()
        ];
        return view('system/system_logs', $data);
    }

    public function user_accounts(){
        $data =[
            'title' => 'User Accounts - SAVEST HRIS',
            'menu' => 'Settings',
            'user_accounts' => User::withoutDeletedEmployees()->get(),
            'types' => UserTypeModel::where('is_deleted', 0)->get(),
            'filtered_data' => [
                'ut_id'=>'All',
                'is_active'=>'All',
            ]
        ];
        return view('system/user_accounts', $data);
    }

    public function filter_acc(Request $request){

        $where = [];
        if($request->ut_id != "All") $where['ut_id'] = $request->ut_id;
        if($request->is_active != "All") $where['is_active'] = $request->is_active;

        $data =[
            'title' => 'User Accounts - SAVEST HRIS',
            'menu' => 'Settings',
            'user_accounts' => User::withoutDeletedEmployees()->where($where)->get(),
            'types' => UserTypeModel::where('is_deleted', 0)->get(),
            'filtered_data' => $request->all()
        ];

        return view('system/user_accounts', $data);

    }

    public function register_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'ut_id' => 'required|exists:user_type,ut_id',
            'is_active' => 'required|boolean',
            'password' => 'required|string|min:6|max:72',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'ut_id' => $request->ut_id,
            'is_active' => $request->is_active,
            'password' => $request->password,
        ]);

        SystemLogsModel::addLog('Registered New User', json_encode(['username' => $request->username]), Auth::id());

        return redirect()->back()->with('success', 'User registered successfully');
    }
    public function user_permissions()
{


    $data = [
        'title' => 'User Types & Privileges - SAVEST HRIS',
        'menu'  => 'Settings',

        // FIX: only active records + ordered
        'types' => UserTypeModel::where('is_deleted', 0)
                    ->orderBy('user_type', 'asc')
                    ->get(),
                    'branches' => BranchModel::where('is_deleted', 0)
    ->where('is_visible', 1)
    ->orderBy('branch_name')
    ->get()
    ];

    return view('system.user_permissions', $data);
}

    public function user_type_accounts($id)
    {
        // Same scope as the User Accounts page: deleted employees' accounts stay hidden
        $accounts = User::withoutDeletedEmployees()
            ->where('ut_id', $id)
            ->orderBy('name')
            ->get(['id', 'name', 'username', 'email', 'is_active', 'employee_id']);

        return response()->json($accounts);
    }

   public function store(Request $request)
{
    $request->validate([
        'user_type'          => 'required|string|max:50|unique:user_type,user_type',
        'badge_color'        => 'nullable|string|max:50',
        'privileges'         => 'nullable|array',
        'privileges.*'       => 'string|max:255',
        'assigned_branches'  => 'nullable|array',
        'assigned_branches.*'=> 'string|exists:branches,branch_code',
    ], [], [
        'user_type' => 'User Type',
    ]);

    $type = UserTypeModel::create([
        'user_type'  => $request->user_type,
        'color_code' => $request->badge_color ?: '#1b55e2',
        'is_deleted' => 0
    ]);

    /*
    |--------------------------------------------------------------------------
    | Save Privileges
    |--------------------------------------------------------------------------
    */

    if ($request->privileges) {

        foreach ($request->privileges as $priv) {

            UserPrivilegeModel::create([
                'ut_id'     => $type->ut_id,
                'privilege' => $priv
            ]);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Save Assigned Branches
    |--------------------------------------------------------------------------
    */

    if ($request->assigned_branches) {

        foreach ($request->assigned_branches as $branchCode) {

            AssignedBranchModel::create([
                'ut_id'       => $type->ut_id,
                'branch_code' => $branchCode
            ]);

        }
    }

    SystemLogsModel::addLog('Add New User Type', "User Type '{$type->user_type}' was created.", Auth::id());

    return back()->with('success', 'New user type was created successfully');
}

public function edit($id)
{
    $type = UserTypeModel::findOrFail($id);

    $userPrivileges = UserPrivilegeModel::where('ut_id', $id)
        ->pluck('privilege')
        ->toArray();

    return view('system.edit_user_type', compact('type', 'userPrivileges'));
}
public function update(Request $request, $id)
{
    $type = UserTypeModel::findOrFail($id);

    $request->validate([
        'user_type'          => 'required|string|max:50|unique:user_type,user_type,' . $id . ',ut_id',
        'badge_color'        => 'nullable|string|max:50',
        'privileges'         => 'nullable|array',
        'privileges.*'       => 'string|max:255',
        'assigned_branches'  => 'nullable|array',
        'assigned_branches.*'=> 'string|exists:branches,branch_code',
    ], [], [
        'user_type' => 'User Type',
    ]);

    $type->update([
        'user_type'  => $request->user_type,
        'color_code' => $request->badge_color ?: $type->color_code,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Update Privileges
    |--------------------------------------------------------------------------
    */

    UserPrivilegeModel::where('ut_id', $id)->delete();

    if ($request->privileges) {

        foreach ($request->privileges as $priv) {

            UserPrivilegeModel::create([
                'ut_id'     => $id,
                'privilege' => $priv
            ]);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update Assigned Branches
    |--------------------------------------------------------------------------
    */

    AssignedBranchModel::where('ut_id', $id)->delete();

    if ($request->assigned_branches) {

        foreach ($request->assigned_branches as $branchCode) {

            AssignedBranchModel::create([
                'ut_id'       => $id,
                'branch_code' => $branchCode
            ]);

        }
    }

    SystemLogsModel::addLog('Update User Type', "User Type '{$type->user_type}' (ID: {$id}) was updated.", Auth::id());

    return back()->with('success', 'Updated successfully');
}
public function destroy($id)
{
    $type = UserTypeModel::findOrFail($id);

    $type->update([
        'is_deleted' => 1
    ]);

    SystemLogsModel::addLog('Delete User Type', "User Type '{$type->user_type}' (ID: {$id}) was deleted.", Auth::id());

    return back()->with('success', 'Deleted successfully');
}

public function update_user(Request $request)
{
    $request->validate([
        'id' => 'required|exists:users,id',
        'ut_id' => 'required|exists:user_type,ut_id',
        'is_active' => 'required|boolean',
        'name' => 'required|string|max:255',
        'username' => 'nullable|string|max:20|unique:users,username,' . $request->id,
        'email' => 'nullable|email|max:255|unique:users,email,' . $request->id,
        'password' => 'nullable|string|min:6|max:72'
    ]);

    // Accounts of deleted employee profiles are hidden and must stay disabled
    if (User::findOrFail($request->id)->hasDeletedEmployee()) {
        return redirect()->back()->withErrors(['error' => 'This account belongs to a deleted employee profile and cannot be modified.']);
    }

    $data = [
        'ut_id' => $request->ut_id,
        'is_active' => $request->is_active,
        'username' => $request->username,
        'name' => $request->name,
        'email' => $request->email,
    ];

    // ONLY update password if filled
    if (!empty($request->password)) {
        $data['password'] = Hash::make($request->password);
    }

    User::where('id', $request->id)->update($data);

    SystemLogsModel::addLog('Update User Account', "User account (ID: {$request->id}) was updated." . (!empty($request->password) ? ' Password was reset.' : ''), Auth::id());

    return redirect()->back()->with('success', 'User updated successfully');
}

public function user_manual()
{
    return view('system.user_manual', [
        'title' => "User's Manual - SAVEST HRIS",
    ]);
}

}
