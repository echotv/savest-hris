<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmploymentStatusController;
use App\Http\Controllers\WeeklySchedulesController;
use App\Http\Controllers\SalaryGradeController;
use App\Http\Controllers\SalaryGradeMovementController;
use App\Http\Controllers\BiometricIdController;
use App\Http\Controllers\EmployeePositionMovementController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\BranchVisitController;
use App\Http\Controllers\SuspensionController;
use App\Http\Controllers\EmployeeMemoController;
use App\Http\Controllers\PerformanceEvaluationController;
use App\Http\Controllers\TravelRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/', function () {
      return view('login');
});
Route::get('/login', function () {
      $data = [];
      if(isset($error)) $data['error'] = $error;
      return view('login',$data);
})->name('login');
//Route::get('/check_login', [LoginController::class, 'check_login']);

Route::post('hris/check_login', [SigninController::class, 'check_login'])->name('hris.check_login');
Route::get('hris/logout', [SigninController::class, 'logout'])->name('hris.logout');

Route::middleware('auth')->group(function () {
    Route::get('system/dashboard', [SystemController::class, 'load_dashboard'])->name('system.dashboard');
    Route::get('system/system_logs', [SystemController::class, 'load_system_logs'])->name('system.logs');
    Route::post('system/logs_filter', [SystemController::class, 'logs_filter'])->name('system.logs_filter');
    Route::post('system/filter_acc', [SystemController::class, 'filter_acc'])->name('system.filter_acc');
    Route::get('system/user_accounts', [SystemController::class, 'user_accounts'])->name('system.user_accounts');
    Route::get('system/user_permissions', [SystemController::class, 'user_permissions'])->name('system.user_permissions');
    Route::get('system/user_manual', [SystemController::class, 'user_manual'])->name('system.user_manual');

    Route::get('employee/registration_form', [EmployeeController::class, 'load_employee_reg_form'])->name('employee.registration_form');
    Route::get('employee/bulk_upload_template', [EmployeeController::class, 'bulk_upload_template'])->name('employee.bulk_upload_template');
    Route::get('employee/employee_list', [EmployeeController::class, 'load_employee_list'])->name('employee.employee_list');
    Route::get('employee/upcoming_birthdays', [EmployeeController::class, 'load_upcoming_birthdays'])->name('employee.upcoming_birthdays');
    Route::get('employee/work_anniversarries', [EmployeeController::class, 'load_work_anniversarries'])->name('employee.work_anniversarries');
    Route::get('employee/employee_profile/{employee_id}', [EmployeeController::class, 'load_employee_profile'])->name('employee.employee_profile');
    Route::get('employee/employee_edit/{employee_id}', [EmployeeController::class, 'load_employee_edit'])->name('employee.employee_edit');
    Route::get('employee/export/{employee_id}', [EmployeeController::class, 'export'])->name('employee.export');
    Route::post('employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::post('employee/update-picture', [EmployeeController::class, 'updatePicture'])->name('employee.update-picture');
    Route::post('/upload-temp', [EmployeeController::class, 'uploadTemp'])->name('employee.upload-temp');
    Route::delete('/revert-temp', [EmployeeController::class, 'revertTemp'])->name('employee.revert-temp');
    Route::post('employee/update', [EmployeeController::class, 'update'])->name('employee.update');
    Route::post('employee/update_employment_status', [EmployeeController::class, 'update_employment_status'])->name('employee.update_employment_status');
    Route::post('employee/update_employee_number', [EmployeeController::class, 'update_employee_number'])->name('employee.update_employee_number');
    Route::delete('employee/delete/{employee_id}', [EmployeeController::class, 'destroy'])->name('employee.delete');
    Route::get('employee/deleted', [EmployeeController::class, 'deletedEmployees'])->name('employee.deleted_list');




    Route::get('positions/company_positions', [PositionsController::class, 'load_company_positions'])->name('positions.company_positions');
    Route::post('positions/store', [PositionsController::class, 'store'])->name('positions.store');
    Route::post('positions/filter', [PositionsController::class, 'filter'])->name('positions.filter');
    Route::post('positions/update/{position_id}', [PositionsController::class, 'update'])->name('positions.update');
    Route::get('positions/archive/{position_id}', [PositionsController::class, 'archive'])->name('positions.archive');
    Route::get('positions/restore/{position_id}', [PositionsController::class, 'restore'])->name('positions.restore');
    Route::get('positions/employees/{position_id}', [PositionsController::class, 'employees'])->name('positions.employees')->where('position_id', '[0-9]+');

    Route::get('employment_status/load', [EmploymentStatusController::class, 'load_es'])->name('employment_status.load');
    Route::post('employment_status/store', [EmploymentStatusController::class, 'store'])->name('employment_status.store');
   Route::post('/employment-status/update/{es_id}', [EmploymentStatusController::class, 'update'])
    ->name('employment_status.update');


Route::get('/salary-grades', [SalaryGradeController::class, 'index'])->name('salarygrades.index');
Route::post('/salary-grades/store', [SalaryGradeController::class, 'store'])->name('salarygrades.store');
Route::post('/salary-grades/update/{id}', [SalaryGradeController::class, 'update'])->name('salarygrades.update');
Route::post('/salary-grades/delete/{id}', [SalaryGradeController::class, 'destroy'])->name('salarygrades.delete');


Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::post('/branches/store', [BranchController::class, 'store'])->name('branches.store');
Route::post('/branches/update/{branch_code}', [BranchController::class, 'update'])->name('branches.update');
Route::post('/branches/delete/{branch_code}', [BranchController::class, 'destroy'])->name('branches.delete');

    Route::get('ws/load/{employee_id}', [WeeklySchedulesController::class, 'load_ws'])->name('ws.load');
    Route::get('ws/load_schedules/{employee_id}', [WeeklySchedulesController::class, 'load_schedules'])->name('schedules.load');
Route::post('/weekly-schedules/{employee_id}', [WeeklySchedulesController::class, 'store_ws'])
    ->name('weekly-schedules.store');

    Route::post('/user-type/store', [SystemController::class, 'store'])->name('user_type.store');
Route::get('/user-type/edit/{id}', [SystemController::class, 'edit']);
Route::post('/user-type/update/{id}', [SystemController::class, 'update']);
Route::post('/user-type/delete/{id}', [SystemController::class, 'destroy']);
Route::get('/user-type/accounts/{id}', [SystemController::class, 'user_type_accounts'])->name('user_type.accounts')->where('id', '[0-9]+');

Route::post('/system/update-user', [SystemController::class, 'update_user'])->name('system.update_user');
Route::post('/system/register-user', [SystemController::class, 'register_user'])->name('system.register_user');

Route::get('/salary-grade-movement/{employee_id}', [SalaryGradeMovementController::class, 'index'])
    ->name('salary-grade-movement.index');

Route::post('/salary-grade-movement/{employee_id}', [SalaryGradeMovementController::class, 'store'])
    ->name('salary-grade-movement.store');

    Route::post('/salary-grade-movement/update/{id}', [SalaryGradeMovementController::class, 'update'])
    ->name('salary-grade-movement.update');

Route::post('/salary-grade-movement/delete/{id}', [SalaryGradeMovementController::class, 'destroy'])
    ->name('salary-grade-movement.delete');


    // BIOMETRIC IDS

Route::get('/biometric-ids', [BiometricIdController::class, 'all'])
    ->name('biometric-ids.all');

Route::get('/biometric-ids/{employee_id}', [BiometricIdController::class, 'index'])
    ->name('biometric-ids.index');

Route::post('/biometric-ids/{employee_id}', [BiometricIdController::class, 'store'])
    ->name('biometric-ids.store');

Route::post('/biometric-ids/update/{id}', [BiometricIdController::class, 'update'])
    ->name('biometric-ids.update');

Route::post('/biometric-ids/delete/{id}', [BiometricIdController::class, 'destroy'])
    ->name('biometric-ids.delete');


    // EMPLOYMENT STATUS MOVEMENT

Route::get(
    '/employment-status-movement/{employee_id}',
    [App\Http\Controllers\EmploymentStatusMovementController::class, 'index']
)->name('employment-status-movement.load');

Route::post(
    '/employment-status-movement/store/{employee_id}',
    [App\Http\Controllers\EmploymentStatusMovementController::class, 'store']
)->name('employment-status-movement.store');

Route::post(
    '/employment-status-movement/update/{id}',
    [App\Http\Controllers\EmploymentStatusMovementController::class, 'update']
)->name('employment-status-movement.update');

Route::post(
    '/employment-status-movement/delete/{id}',
    [App\Http\Controllers\EmploymentStatusMovementController::class, 'destroy']
)->name('employment-status-movement.delete');

// List page (per employee)
    Route::get('/{employee_id}', [
        EmployeePositionMovementController::class,
        'index'
    ])->name('employee-position-movement.index')->where('employee_id', '[0-9]+');

    // Store new position movement
    Route::post('/{employee_id}/store', [
        EmployeePositionMovementController::class,
        'store'
    ])->name('employee-position-movement.store')->where('employee_id', '[0-9]+');

    // Update movement
    Route::post('/update/{id}', [
        EmployeePositionMovementController::class,
        'update'
    ])->name('employee-position-movement.update');

    // Soft delete movement
    Route::post('/delete/{id}', [
        EmployeePositionMovementController::class,
        'destroy'
    ])->name('employee-position-movement.delete');



    Route::get('ews/load_ews_update/{ews_id}', [
        WeeklySchedulesController::class,
        'load_update'
    ])->name('ews.load_update');


    // Update movement
    Route::post('ews/update/{id}', [
        WeeklySchedulesController::class,
        'update'
    ])->name('ews.update');

    // Soft delete movement
    Route::get('ews/delete/{id}', [
        WeeklySchedulesController::class,
        'destroy'
    ])->name('ews.delete');

    Route::get('ews/load_wsl', [
        WeeklySchedulesController::class,
        'load_wsl'
    ])->name('ews.load_wsl');

    Route::get('ews/load_bulk_update', [
        WeeklySchedulesController::class,
        'load_bulk_update'
    ])->name('ews.load_bulk_update');

    Route::post('ews/bulk_update/', [
        WeeklySchedulesController::class,
        'bulk_update'
    ])->name('ews.bulk_update');

    Route::get('system/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('system/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('system/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
 Route::get('/travel-requests',              [TravelRequestController::class, 'index'])->name('travel.index');
    Route::get('/travel-requests/data',         [TravelRequestController::class, 'data'])->name('travel.data');
    Route::post('/travel-requests/store',       [TravelRequestController::class, 'store'])->name('travel.store');
    Route::post('/travel-requests/update/{id}', [TravelRequestController::class, 'update'])->name('travel.update');
    Route::delete('/travel-requests/delete/{id}', [TravelRequestController::class, 'delete'])->name('travel.delete');
 Route::get('/branch-visits',              [BranchVisitController::class, 'index'])->name('branch-visits.index');
    Route::get('/branch-visits/data',         [BranchVisitController::class, 'data'])->name('branch-visits.data');
    Route::post('/branch-visits/store',       [BranchVisitController::class, 'store'])->name('branch-visits.store');
    Route::post('/branch-visits/update/{id}', [BranchVisitController::class, 'update'])->name('branch-visits.update');
    Route::delete('/branch-visits/delete/{id}', [BranchVisitController::class, 'delete'])->name('branch-visits.delete');

    Route::get('/employee-memos',                [EmployeeMemoController::class, 'index'])->name('memos.index');
    Route::get('/employee-memos/data',           [EmployeeMemoController::class, 'data'])->name('memos.data');
    Route::post('/employee-memos/store',         [EmployeeMemoController::class, 'store'])->name('memos.store');
    Route::post('/employee-memos/update/{id}',   [EmployeeMemoController::class, 'update'])->name('memos.update');
    Route::delete('/employee-memos/delete/{id}', [EmployeeMemoController::class, 'delete'])->name('memos.delete');

    Route::get('/performance-evaluations',                [PerformanceEvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/performance-evaluations/data',           [PerformanceEvaluationController::class, 'data'])->name('evaluations.data');
    Route::post('/performance-evaluations/store',         [PerformanceEvaluationController::class, 'store'])->name('evaluations.store');
    Route::post('/performance-evaluations/update/{id}',   [PerformanceEvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('/performance-evaluations/delete/{id}', [PerformanceEvaluationController::class, 'delete'])->name('evaluations.delete');

    Route::get('/suspensions',                  [SuspensionController::class, 'index'])->name('suspensions.index');
    Route::get('/suspensions/data',             [SuspensionController::class, 'data'])->name('suspensions.data');
    Route::post('/suspensions/store',           [SuspensionController::class, 'store'])->name('suspensions.store');
    Route::post('/suspensions/update/{id}',     [SuspensionController::class, 'update'])->name('suspensions.update');
    Route::delete('/suspensions/delete/{id}',   [SuspensionController::class, 'delete'])->name('suspensions.delete');
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::get('/holidays/data', [HolidayController::class, 'data'])->name('holidays.data');
    Route::post('/holidays/store', [HolidayController::class, 'store'])->name('holidays.store');
    Route::post('/holidays/update/{id}', [HolidayController::class, 'update'])->name('holidays.update');
    Route::delete('/holidays/delete/{id}', [HolidayController::class, 'delete'])->name('holidays.delete');

});
