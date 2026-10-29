<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PositionsModel;
use App\Models\EmploymentStatusModel;
use App\Models\GraduateStudiesModel;
use App\Models\WorkExperiencesModel;
use App\Models\CollegesModel;
use App\Models\BranchModel;
use App\Models\CharacterReference;
use App\Models\EmployeeModel;
use App\Models\SystemLogsModel;
use App\Models\AssignedBranchModel;
use App\Models\EmploymentStatusMovement;
use App\Models\EmployeePositionMovement;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Log;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Response;
class EmployeeController extends Controller
{
    /**
     * The employee's email is also the login email of their user account (users.email is UNIQUE and
     * NOT NULL). Catch a clash here with a readable message instead of letting the database throw.
     * $ignoreEmployeeId = the employee being edited, so their own account doesn't count as a clash.
     */
    private function emailNotUsedByAnotherUser(?int $ignoreEmployeeId = null): \Closure
    {
        return function ($attribute, $value, $fail) use ($ignoreEmployeeId) {
            if (!$value) return;

            $query = User::where('email', $value);
            if ($ignoreEmployeeId) {
                $query->where(fn($q) => $q->whereNull('employee_id')->orWhere('employee_id', '!=', $ignoreEmployeeId));
            }

            if ($query->exists()) {
                $fail("The email address \"{$value}\" is already used by another user account in the system "
                    . "(it may belong to another employee or to a system user). Each user account needs its own email, "
                    . "so please enter a different email address.");
            }
        };
    }

    public function load_employee_reg_form()
    {
        $data =[
            'title' => 'Employee Registration Form - SAVEST HRIS',
            'menu' => 'Employee',
            'branches' => AssignedBranchModel::join('branches', 'assigned_branches.branch_code', '=', 'branches.branch_code')
            ->where(['branches.is_deleted' => 0, 'branches.is_visible' => 1, 'assigned_branches.ut_id'=>Auth::user()->ut_id])->get(),
            'positions_list' => PositionsModel::where(['is_deleted' => 0, 'is_archived' => 0])->get(),
                        'employment_statuses' => EmploymentStatusModel::where(['is_deleted'=> 0, 'is_visible'=> 1])->orderBy('empl_status', 'ASC')->get()
        ];
        return view("employee/employee_reg_form", $data);
    }

    public function load_upcoming_birthdays()
{
    $today = Carbon::now('Asia/Manila');
    $today_md = $today->format('m-d');

    $past_start = $today->copy()->subDays(7);
    $future_start = $today->copy()->addDay();       // start tomorrow
    $future_end = $today->copy()->addDays(14);      // end 14 days after tomorrow

    $data = [
        'title' => 'Upcoming Birthdays - SAVEST HRIS',
        'menu'  => 'Employee',

        // 🎉 Today's birthdays
        'todays_birthday' =>AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->where('employee.is_deleted', 0, )
       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
            ->where('employee.employment_status', '!=', 'Resigned')
            ->whereRaw("DATE_FORMAT(employee.date_of_birth, '%m-%d') = ?", [$today_md])
            ->get(),

        // ⬅️ Birthdays from the past 7 days
        'previous_birthdays' =>AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->where('employee.is_deleted', 0, )
       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
            ->where('employee.employment_status', '!=', 'Resigned')
            ->whereBetween(
                DB::raw("DATE_FORMAT(employee.date_of_birth, '%m-%d')"),
                [$past_start->format('m-d'), $today->copy()->subDay()->format('m-d')]
            )
            ->orderByRaw("DATE_FORMAT(employee.date_of_birth, '%m-%d') DESC")
            ->get(),

        // ➡️ Birthdays from tomorrow until the next 14 days
        'upcoming_birthdays' => AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->where('employee.is_deleted', 0, )
       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
            ->where('employee.employment_status', '!=', 'Resigned')
            ->whereBetween(
                DB::raw("DATE_FORMAT(employee.date_of_birth, '%m-%d')"),
                [$future_start->format('m-d'), $future_end->format('m-d')]
            )
            ->orderByRaw("DATE_FORMAT(employee.date_of_birth, '%m-%d') ASC")
            ->get(),
    ];

    return view("employee/upcoming_birthdays", $data);
}

    public function load_work_anniversarries()
{
    $today = Carbon::now('Asia/Manila');
    $today_md = $today->format('m-d');

    // Define date ranges
    $past_start   = $today->copy()->subDays(7);
    $future_start = $today->copy()->addDay();   // start from tomorrow
    $future_end   = $today->copy()->addDays(14); // up to next 14 days

    $data = [
        'title' => 'Upcoming Work Anniversaries - SAVEST HRIS',
        'menu'  => 'Employee',

        // 🎉 Today's anniversaries
        'todays_work_anniv' => AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->where('employee.is_deleted', 0, )
       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
            ->where('employee.employment_status', '!=', 'Resigned')
            ->whereRaw("DATE_FORMAT(employee.date_employed, '%m-%d') = ?", [$today_md])
            ->get(),

        // ⬅️ Past 7 days anniversaries
        'previous_work_anniv' => AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->where('employee.is_deleted', 0, )
       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
            ->where('employee.employment_status', '!=', 'Resigned')
            ->whereBetween(
                DB::raw("DATE_FORMAT(employee.date_employed, '%m-%d')"),
                [$past_start->format('m-d'), $today->copy()->subDay()->format('m-d')]
            )
            ->orderByRaw("DATE_FORMAT(date_employed, '%m-%d') DESC")
            ->get(),

        // ➡️ Upcoming anniversaries (starting tomorrow until next 14 days)
        'upcoming_work_anniv' => AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
       ->where('employee.is_deleted', 0, )
       ->where('assigned_branches.ut_id', Auth::user()->ut_id)
            ->where('employee.employment_status', '!=', 'Resigned')
            ->whereBetween(
                DB::raw("DATE_FORMAT(employee.date_employed, '%m-%d')"),
                [$future_start->format('m-d'), $future_end->format('m-d')]
            )
            ->orderByRaw("DATE_FORMAT(employee.date_employed, '%m-%d') ASC")
            ->get(),
    ];

    return view("employee/upcoming_work_anniversarries", $data);
}



    public function load_employee_profile($employee_id)
    {
        $employee = EmployeeModel::where('employee.employee_id', $employee_id)
            ->join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')
            ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->join('positions', 'employee.position_id', '=', 'positions.position_id', 'left')
            ->first();

        abort_if(!$employee, 404, 'Employee not found.');

        $data =[
            'title' => 'Employee Profile - SAVEST HRIS',
            'menu' => 'Employee',
            'employee' => $employee,
            'colleges' => CollegesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get(),
            'grad_studies' => GraduateStudiesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get(),
            'work_experiences' => WorkExperiencesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get(),
            'character_references' => CharacterReference::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get(),
        ];


        return view("employee/employee_view", $data);
    }

    public function load_employee_edit($employee_id)
    {
        $employee = EmployeeModel::where('employee.employee_id', $employee_id)->join('positions', 'employee.position_id', '=', 'positions.position_id', 'left')->select('employee.*', 'positions.*')->first();

        abort_if(!$employee, 404, 'Employee not found.');

        $data =[
            'title' => 'Employee Profile - SAVEST HRIS',
            'menu' => 'Employee',
            'employee' => $employee,
            'is_current_employee' => EmployeeModel::isCurrentEmployeeStatus($employee->es_id),
            'branches' => AssignedBranchModel::join('branches', 'assigned_branches.branch_code', '=', 'branches.branch_code')
            ->where(['branches.is_deleted' => 0, 'branches.is_visible' => 1, 'assigned_branches.ut_id'=>Auth::user()->ut_id])->get(),

            'colleges' => CollegesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get(),
            'grad_studies' => GraduateStudiesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get(),
            'work_experiences' => WorkExperiencesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get(),
            'character_references' => CharacterReference::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get(),
            'positions_list' => PositionsModel::where(['is_deleted' => 0, 'is_archived' => 0])->get(),
            'employment_statuses' => EmploymentStatusModel::where(['is_deleted'=> 0, 'is_visible'=> 1])->orderBy('empl_status', 'ASC')->get()
        ];

        return view("employee/employee_edit", $data);
    }

    public function load_employee_list(Request $request)
{
    $query = AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
        ->join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')
        ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
        ->join('positions', 'employee.position_id', '=', 'positions.position_id')
        ->where([
            'employee.is_deleted' => 0,
            'employee.is_archived' => 0,
            'assigned_branches.ut_id' => Auth::user()->ut_id
        ]);

    // FILTERS (cleaner than array $where)
    if (!empty($request->branch_code) && $request->branch_code != 'All') {
        $query->where('employee.branch_code', $request->branch_code);
    }

    if (!empty($request->position_id) && $request->position_id != 'All') {
        $query->where('employee.position_id', $request->position_id);
    }

    if (!empty($request->employment_status) && $request->employment_status != 'All') {
        $query->where('employee.es_id', $request->employment_status);
    }

    if (!empty($request->date_employed) && $request->date_employed != '') {
        $query->whereDate('employee.date_employed', $request->date_employed);
    }

    // Salary grade coverage: has at least one non-deleted salary_grade_movement row vs none.
    if ($request->salary_grade === 'with') {
        $query->whereExists(function ($q) {
            $q->selectRaw('1')
              ->from('salary_grade_movement')
              ->whereColumn('salary_grade_movement.employee_id', 'employee.employee_id')
              ->where('salary_grade_movement.is_deleted', 0);
        });
    } elseif ($request->salary_grade === 'without') {
        $query->whereNotExists(function ($q) {
            $q->selectRaw('1')
              ->from('salary_grade_movement')
              ->whereColumn('salary_grade_movement.employee_id', 'employee.employee_id')
              ->where('salary_grade_movement.is_deleted', 0);
        });
    }

    // Explicit column list — addSelect() below would otherwise collapse the implicit
    // "SELECT *" down to just assigned_branches.* (the base table), silently dropping
    // every joined column (employee_id included).
    $query->select(
        'employee.*',
        'branches.branch_name',
        'positions.position_name',
        'employment_status.empl_status'
    );

    // Salary grade status column: has at least one non-deleted salary_grade_movement row vs none.
    $query->addSelect(['has_salary_grade' => function ($q) {
        $q->selectRaw('1')
          ->from('salary_grade_movement')
          ->whereColumn('salary_grade_movement.employee_id', 'employee.employee_id')
          ->where('salary_grade_movement.is_deleted', 0)
          ->limit(1);
    }]);

    $data = [
        'title' => 'Employee List - SAVEST HRIS',
        'menu' => 'Employee',

        'positions_list' => PositionsModel::where([
            'is_deleted' => 0,
            'is_archived' => 0
        ])->get(),

        'employment_statuses' => EmploymentStatusModel::where([
            'is_deleted' => 0,
            'is_visible' => 1
        ])->orderBy('empl_status', 'ASC')->get(),

        'branches' => AssignedBranchModel::join(
                'branches',
                'assigned_branches.branch_code',
                '=',
                'branches.branch_code'
            )
            ->where([
                'branches.is_deleted' => 0,
                'branches.is_visible' => 1,
                'assigned_branches.ut_id' => Auth::user()->ut_id
            ])
            ->get(),

        'data_filtered' => [
            'branch_code' => $request->branch_code ?? 'All',
            'employment_status' => $request->employment_status ?? 'All',
            'position_id' => $request->position_id ?? 'All',
            'date_employed' => $request->date_employed ?? '',
            'salary_grade' => $request->salary_grade ?? 'all',
        ],

        'employees' => $query->distinct()->get(),
    ];

    return view("employee/employee_list", $data);
}





    public function store(Request $request)
{
   $validated = $request->validate([
        'lastname'        => 'required|string|max:100',
        'firstname'       => 'required|string|max:100',
        'middlename'      => 'nullable|string|max:100',
        'suffix'          => 'nullable|string|max:20',
        'gender'          => 'required|string|in:Male,Female',
        'date_of_birth'   => 'required|date',
        'place_of_birth'  => 'required|string|max:150',
        'personal_email'  => ['required', 'email', 'max:150', 'unique:employee,personal_email', $this->emailNotUsedByAnotherUser()],
        'civil_status'    => 'required|string|in:Single,Married,Widowed,Separated,Divorced',
        'religion'        => 'required|string|max:100',
        'eci_name'        => 'required|string|max:150',
        'eci_cnumber'     => 'required|string|max:50',
        'eci_address'     => 'required|string|max:255',
        'street'          => 'nullable|string|max:150',
        'barangay'        => 'required|string|max:150',
        'city'            => 'required|string|max:150',
        'province'        => 'required|string|max:150',
        'employee_number' => 'nullable|string|max:50|unique:employee,employee_number',
        'date_employed'   => 'required|date',
        'es_id'           => 'required|exists:employment_status,es_id',
        'branch_code'     => 'required|string|max:20|exists:branches,branch_code',
        'position_id'     => 'required|exists:positions,position_id',
        'pagibig'         => 'nullable|string|max:50',
        'philhealth'      => 'nullable|string|max:50',
        'tin'             => 'nullable|string|max:50',
        'sss'             => 'nullable|string|max:50',
        'national_id'     => 'nullable|string|max:50',
        'biometric_id'    => 'nullable|string|max:50',
        'father_name'     => 'nullable|string|max:150',
        'mother_name'     => 'nullable|string|max:150',
        'childrens'       => 'nullable|string|max:1000',
        'citizenship'     => 'required|string|max:100',
        'height'          => 'nullable|numeric|between:50,250',
        'weight'          => 'nullable|numeric|between:20,400',
        'drivers_license_no' => 'nullable|string|max:100',
        'telephone_no'    => 'nullable|string|max:50',
        'cellphone_no'    => 'nullable|string|max:50',
        'account_status'  => 'required|integer|in:0,1',
        'spouse_name'         => 'required_if:civil_status,Married|nullable|string|max:150',
        'spouse_occupation'   => 'required_if:civil_status,Married|nullable|string|max:150',
        'spouse_empl_name'    => 'required_if:civil_status,Married|nullable|string|max:150',

        // ✅ Repeatable sections — previously read straight off $request with
        // zero validation. Each *.* wildcard rule applies to every row.
        'name_of_school'      => 'nullable|array',
        'name_of_school.*'    => 'nullable|string|max:255',
        'course'               => 'nullable|array',
        'course.*'             => 'nullable|string|max:255',
        'year_graduated'       => 'nullable|array',
        'year_graduated.*'     => 'nullable|integer|min:1920|max:2100',

        'gs_name_of_school'    => 'nullable|array',
        'gs_name_of_school.*'  => 'nullable|string|max:255',
        'gs_sch_address'       => 'nullable|array',
        'gs_sch_address.*'     => 'nullable|string|max:255',
        'gs_course'            => 'nullable|array',
        'gs_course.*'          => 'nullable|string|max:255',
        'gs_year_graduated'    => 'nullable|array',
        'gs_year_graduated.*'  => 'nullable|integer|min:1920|max:2100',

        'we_company_name'      => 'nullable|array',
        'we_company_name.*'    => 'nullable|string|max:255',
        'we_position'           => 'nullable|array',
        'we_position.*'         => 'nullable|string|max:255',
        'we_salary'             => 'nullable|array',
        'we_salary.*'           => 'nullable|numeric|min:1|max:9999999.99', // ⚠ verify against we_salary's column precision
        'we_date_started'       => 'nullable|array',
        'we_date_started.*'     => 'nullable|string|max:50',
        'we_date_ended'         => 'nullable|array',
        'we_date_ended.*'       => 'nullable|string|max:50',

        'cr_name'               => 'nullable|array',
        'cr_name.*'             => 'nullable|string|max:255',
        'cr_company'            => 'nullable|array',
        'cr_company.*'          => 'nullable|string|max:255',
        'cr_position'           => 'nullable|array',
        'cr_position.*'         => 'nullable|string|max:255',
        'cr_cnum'               => 'nullable|array',
        'cr_cnum.*'             => 'nullable|string|max:50',
    ], [
        'personal_email.required' => 'Email Address is required — it is used to create and maintain the user account of the employee.',
        'personal_email.email'    => 'Please enter a valid email address (for example: name@example.com).',
        'personal_email.unique'   => 'This email address is already recorded for another employee. Please enter a different email address.',
    ], [
        'lastname'        => 'Last Name',
        'firstname'       => 'First Name',
        'middlename'      => 'Middle Name',
        'suffix'          => 'Suffix',
        'gender'          => 'Gender',
        'date_of_birth'   => 'Date of Birth',
        'place_of_birth'  => 'Place of Birth',
        'personal_email'  => 'Email Address',
        'civil_status'    => 'Civil Status',
        'religion'        => 'Religion',
        'eci_name'        => 'Emergency Contact Person',
        'eci_cnumber'     => 'Emergency Contact No.',
        'eci_address'     => 'Emergency Contact Address',
        'street'          => 'Street / House No.',
        'barangay'        => 'Barangay',
        'city'            => 'City / Municipality',
        'province'        => 'Province',
        'employee_number' => 'Employee ID',
        'date_employed'   => 'Date Employed',
        'es_id'           => 'Employment Status',
        'branch_code'     => 'Branch',
        'position_id'     => 'Position',
        'pagibig'         => 'PAGIBIG No.',
        'philhealth'      => 'PhilHealth No.',
        'tin'             => 'TIN No.',
        'sss'             => 'SSS No.',
        'national_id'     => 'National ID',
        'biometric_id'    => 'Biometric ID',
        'father_name'     => "Father's Name",
        'mother_name'     => "Mother's Name",
        'childrens'       => 'Children',
        'citizenship'     => 'Citizenship',
        'height'          => 'Height',
        'weight'          => 'Weight',
        'drivers_license_no' => "Driver's License No.",
        'telephone_no'    => 'Telephone No.',
        'cellphone_no'    => 'Cellphone No.',
        'account_status'  => 'Account Status',
        'spouse_name'         => "Spouse's Name",
        'spouse_occupation'   => "Spouse's Occupation",
        'spouse_empl_name'    => "Spouse's Employer",
        'name_of_school.*'    => 'Name of School',
        'course.*'             => 'Course / Major',
        'year_graduated.*'     => 'Year Graduated',
        'gs_name_of_school.*'  => 'Graduate School Name',
        'gs_sch_address.*'     => 'Graduate School Address',
        'gs_course.*'          => 'Graduate Course / Major',
        'gs_year_graduated.*'  => 'Graduate Year Graduated',
        'we_company_name.*'    => 'Company Name',
        'we_position.*'         => 'Position',
        'we_salary.*'           => 'Salary',
        'we_date_started.*'     => 'Date Started',
        'we_date_ended.*'       => 'Date Ended',
        'cr_name.*'             => 'Reference Name',
        'cr_company.*'          => 'Reference Company',
        'cr_position.*'         => 'Reference Position',
        'cr_cnum.*'             => 'Reference Contact No.',
    ]);


    try {
        DB::transaction(function () use ($request, $validated) { // ✅ wrap everything in a transaction

            // Auto-generate employee_number if blank
            if (empty($validated['employee_number'])) {
                $year = date('Y', strtotime($validated['date_employed']));
                $count = EmployeeModel::whereYear('date_employed', $year)->lockForUpdate()->count() + 1; // ✅ lock to prevent race condition
                $validated['employee_number'] = $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }

            // ✅ Use null-safe strtoupper via helper — avoids error on null values
            $toUpper = fn($val) => $val ? strtoupper($val) : null;

            $validated['lastname']         = strtoupper($validated['lastname']);
            $validated['firstname']        = strtoupper($validated['firstname']);
            $validated['middlename']       = $toUpper($validated['middlename'] ?? null);
            $validated['suffix']           = $toUpper($validated['suffix'] ?? null);
            $validated['father_name']      = $toUpper($validated['father_name'] ?? null);
            $validated['mother_name']      = $toUpper($validated['mother_name'] ?? null);
            $validated['eci_name']         = strtoupper($validated['eci_name']);
            $validated['date_inputted']    = Carbon::now('Asia/Manila');
            $validated['inputted_byname']  = Auth::user()->name ?? 'System';
            $validated['inputted_byid']    = Auth::id() ?? 0;
            $validated['updated_at']       = Carbon::now('Asia/Manila');
            $validated['updated_byname']   = Auth::user()->name ?? 'System';
            $validated['updated_by_id']     = Auth::id() ?? 0;

            // Non-current employment status (e.g. Resigned) can never have an active account
            if (!EmployeeModel::isCurrentEmployeeStatus($validated['es_id'])) {
                $validated['account_status'] = 0;
            }

            $employee = EmployeeModel::create($validated);

            // ✅ Build display name safely — skip null parts
            $displayName = strtoupper(implode(' ', array_filter([
                $employee->lastname,
                $employee->firstname,
                $employee->suffix,
                $employee->middlename,
            ])));

            User::create([
                'name'        => $displayName,
                'username'    => $employee->employee_number,
                'email'       => $employee->personal_email, // ✅ nullable is fine if DB column allows null
                'is_active'   => (int) $employee->account_status,
                'type'        => 'EMPLOYEE',
                'password'    => Hash::make('hris!2026'),
                'created_at'  => Carbon::now('Asia/Manila'),
                'updated_at'  => Carbon::now('Asia/Manila'),
                'employee_id' => $employee->employee_id,
            ]);

            // Colleges
            foreach ((array) $request->name_of_school as $index => $collegeName) {
                if (!empty($collegeName)) {
                    CollegesModel::create([
                        'employee_id'    => $employee->employee_id,
                        'name_of_school' => $collegeName,
                        'course'         => $request->course[$index] ?? null,
                        'year_graduated' => $request->year_graduated[$index] ?? null,
                    ]);
                }
            }

            EmploymentStatusMovement::create([
                'employee_id'    => $employee->employee_id,
                'es_id'          => $employee->es_id,
                'effective_date' => $employee->date_employed,
                'end_date'       => null,
            ]);

            EmployeePositionMovement::create([
                'employee_id'    => $employee->employee_id,
                'position_id'    => $employee->position_id,
                'effective_date' => $employee->date_employed,
                'end_date'       => null,
                'remarks'        => 'none',
            ]);

            // Graduate studies
            foreach ((array) $request->gs_name_of_school as $index => $gradSchool) {
                if (!empty($gradSchool)) {
                    GraduateStudiesModel::create([
                        'employee_id'       => $employee->employee_id,
                        'gs_name_of_school' => $gradSchool,
                        'gs_sch_address'    => $request->gs_sch_address[$index] ?? null,
                        'gs_course'         => $request->gs_course[$index] ?? null,
                        'gs_year_graduated' => $request->gs_year_graduated[$index] ?? null,
                    ]);
                }
            }

            // Work experiences
            foreach ((array) $request->we_company_name as $index => $company) {
                if (!empty($company)) {
                    WorkExperiencesModel::create([
                        'employee_id'     => $employee->employee_id,
                        'we_company_name' => $company,
                        'we_position'     => $request->we_position[$index] ?? null,
                        'we_salary'       => $request->we_salary[$index] ?? null,
                        'we_date_started' => $request->we_date_started[$index] ?? null,
                        'we_date_ended'   => $request->we_date_ended[$index] ?? null,
                    ]);
                }
            }

            // Character references
            foreach ((array) $request->cr_name as $index => $name) {
                if (!empty($name)) {
                    CharacterReference::create([
                        'employee_id' => $employee->employee_id,
                        'cr_name'     => $name,
                        'cr_company'  => $request->cr_company[$index] ?? null,
                        'cr_position' => $request->cr_position[$index] ?? null,
                        'cr_cnum'     => $request->cr_cnum[$index] ?? null,
                    ]);
                }
            }

            SystemLogsModel::addLog('Registered New Employee', $employee, auth()->id());
        });

        return redirect()->back()->with('success', 'Employee registered successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

public function update_employment_status(Request $request){
    $validated = $request->validate([
        'employee_id' => 'required|integer|exists:employee,employee_id',
        'es_id' => 'required|exists:employment_status,es_id',
    ]);
    unset($validated['employee_id']);
    try {
        $previousEsId = EmployeeModel::where('employee_id', $request->employee_id)->value('es_id');
        EmployeeModel::where('employee_id', $request->employee_id)->update($validated);
        EmployeeModel::syncAccountWithEmploymentStatus($request->employee_id, $previousEsId);
        SystemLogsModel::addLog('Updated Employment Status', json_encode($validated), auth()->id());
        return redirect()->route('employee.employee_list')->with('success', 'Employment status was updated successfully!');

    } catch (\Exception $e) {
        return redirect()->route('employee.employee_list')->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

public function update_employee_number(Request $request){
    $user = User::where('employee_id', $request->employee_id)->first();

    $request->validate([
        'employee_id'     => 'required|integer|exists:employee,employee_id',
        // max:20 (not 50) because this value is also written into users.username,
        // which is varchar(20) — anything longer would overflow that column.
        'employee_number' => [
            'required',
            'string',
            'max:20',
            Rule::unique('employee', 'employee_number')->ignore($request->employee_id, 'employee_id'),
            Rule::unique('users', 'username')->ignore(optional($user)->id),
        ],
    ]);

    $validated = ['employee_number' => $request->employee_number];

    try {
        DB::transaction(function () use ($request, $validated, $user) {
            EmployeeModel::where('employee_id', $request->employee_id)->update($validated);

            // username = employee_id for every employee account — keep it in sync.
            if ($user) {
                $user->update(['username' => $request->employee_number]);
            }
        });

        SystemLogsModel::addLog('Updated Employee Number', json_encode($validated), auth()->id());
        return redirect()->route('employee.employee_list')->with('success', 'Employee ID was updated successfully! The employee\'s username was updated to match.');
    } catch (\Exception $e) {
        return redirect()->route('employee.employee_list')->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

    public function update(Request $request)
    {
        $validated = $request->validate([
            // Identity
            'employee_id'    => 'required|integer|exists:employee,employee_id',
            'lastname'       => 'required|string|max:100',
            'firstname'      => 'required|string|max:100',
            'middlename'     => 'nullable|string|max:100',
            'suffix'         => 'nullable|string|max:20',
            'gender'         => 'required|string|in:Male,Female',
            'date_of_birth'  => 'required|date',
            'place_of_birth' => 'required|string|max:150',
            'civil_status'   => 'required|string|in:Single,Married,Widowed,Separated,Divorced',
            'religion'       => 'nullable|string|max:100',
            'citizenship'    => 'required|string|max:100',

            // Physical
            'height' => 'nullable|numeric|between:50,250',
            'weight' => 'nullable|numeric|between:20,400',

            // Contact — unique but ignoring this employee's own current row
            'personal_email' => ['required', 'email', 'max:150', 'unique:employee,personal_email,' . $request->employee_id . ',employee_id', $this->emailNotUsedByAnotherUser((int) $request->employee_id)],
            'telephone_no'   => 'nullable|string|max:50',
            'cellphone_no'   => 'nullable|string|max:50',

            // Emergency contact
            'eci_name'    => 'required|string|max:150',
            'eci_cnumber' => 'required|string|max:50',
            'eci_address' => 'required|string|max:255',

            // Government IDs
            'sss'                => 'nullable|string|max:50',
            'tin'                => 'nullable|string|max:50',
            'philhealth'         => 'nullable|string|max:50',
            'pagibig'            => 'nullable|string|max:50',
            'national_id'        => 'nullable|string|max:50',
            'drivers_license_no' => 'nullable|string|max:100',
            'biometric_id'       => 'nullable|string|max:50',

            // Address
            'province' => 'required|string|max:150',
            'city'     => 'required|string|max:150',
            'barangay' => 'required|string|max:150',
            'street'   => 'nullable|string|max:150',

            // Employment
            'date_employed'  => 'required|date',
            'account_status' => 'required|integer|in:0,1',
            'branch_code'    => 'required|string|max:20|exists:branches,branch_code',

            // Family
            'father_name' => 'nullable|string|max:150',
            'mother_name' => 'nullable|string|max:150',
            'childrens'   => 'nullable|string|max:1000',

            // Spouse (only required if married)
            'spouse_name'       => 'required_if:civil_status,Married|nullable|string|max:150',
            'spouse_occupation' => 'required_if:civil_status,Married|nullable|string|max:150',
            'spouse_empl_name'  => 'required_if:civil_status,Married|nullable|string|max:150',

            // College (repeatable)
            'name_of_school'    => 'nullable|array',
            'name_of_school.*'  => 'nullable|string|max:255',
            'course'             => 'nullable|array',
            'course.*'           => 'nullable|string|max:255',
            'year_graduated'     => 'nullable|array',
            'year_graduated.*'   => 'nullable|integer|min:1920|max:2100',

            // Graduate studies (repeatable)
            'gs_name_of_school'   => 'nullable|array',
            'gs_name_of_school.*' => 'nullable|string|max:255',
            'gs_sch_address'      => 'nullable|array',
            'gs_sch_address.*'    => 'nullable|string|max:255',
            'gs_course'           => 'nullable|array',
            'gs_course.*'         => 'nullable|string|max:255',
            'gs_year_graduated'   => 'nullable|array',
            'gs_year_graduated.*' => 'nullable|integer|min:1920|max:2100',

            // Work experience (repeatable)
            'we_company_name'   => 'nullable|array',
            'we_company_name.*' => 'nullable|string|max:255',
            'we_position'        => 'nullable|array',
            'we_position.*'      => 'nullable|string|max:255',
            'we_salary'          => 'nullable|array',
            'we_salary.*'        => 'nullable|numeric|min:1|max:9999999.99',
            'we_date_started'    => 'nullable|array',
            'we_date_started.*'  => 'nullable|string|max:50',
            'we_date_ended'      => 'nullable|array',
            'we_date_ended.*'    => 'nullable|string|max:50',

            // Character references (repeatable)
            'cr_name'      => 'nullable|array',
            'cr_name.*'    => 'nullable|string|max:255',
            'cr_company'   => 'nullable|array',
            'cr_company.*' => 'nullable|string|max:255',
            'cr_position'  => 'nullable|array',
            'cr_position.*'=> 'nullable|string|max:255',
            'cr_cnum'      => 'nullable|array',
            'cr_cnum.*'    => 'nullable|string|max:50',
        ], [
        'personal_email.required' => 'Email Address is required — it is used to create and maintain the user account of the employee.',
        'personal_email.email'    => 'Please enter a valid email address (for example: name@example.com).',
        'personal_email.unique'   => 'This email address is already recorded for another employee. Please enter a different email address.',
    ], [
            'employee_id'    => 'Employee ID',
            'lastname'       => 'Last Name',
            'firstname'      => 'First Name',
            'middlename'     => 'Middle Name',
            'suffix'         => 'Suffix',
            'gender'         => 'Gender',
            'date_of_birth'  => 'Date of Birth',
            'place_of_birth' => 'Place of Birth',
            'civil_status'   => 'Civil Status',
            'religion'       => 'Religion',
            'citizenship'    => 'Citizenship',
            'height'         => 'Height',
            'weight'         => 'Weight',
            'personal_email' => 'Email Address',
            'telephone_no'   => 'Telephone No.',
            'cellphone_no'   => 'Cellphone No.',
            'eci_name'       => 'Emergency Contact Person',
            'eci_cnumber'    => 'Emergency Contact No.',
            'eci_address'    => 'Emergency Contact Address',
            'sss'            => 'SSS No.',
            'tin'            => 'TIN No.',
            'philhealth'     => 'PhilHealth No.',
            'pagibig'        => 'PAGIBIG No.',
            'national_id'    => 'National ID',
            'drivers_license_no' => "Driver's License No.",
            'biometric_id'   => 'Biometric ID',
            'province'       => 'Province',
            'city'           => 'City / Municipality',
            'barangay'       => 'Barangay',
            'street'         => 'Street / House No.',
            'date_employed'  => 'Date Employed',
            'account_status' => 'Account Status',
            'branch_code'    => 'Branch',
            'father_name'    => "Father's Name",
            'mother_name'    => "Mother's Name",
            'childrens'      => 'Children',
            'spouse_name'         => "Spouse's Name",
            'spouse_occupation'   => "Spouse's Occupation",
            'spouse_empl_name'    => "Spouse's Employer",
            'name_of_school.*'    => 'Name of School',
            'course.*'            => 'Course / Major',
            'year_graduated.*'    => 'Year Graduated',
            'gs_name_of_school.*' => 'Graduate School Name',
            'gs_sch_address.*'    => 'Graduate School Address',
            'gs_course.*'         => 'Graduate Course / Major',
            'gs_year_graduated.*' => 'Graduate Year Graduated',
            'we_company_name.*'   => 'Company Name',
            'we_position.*'       => 'Position',
            'we_salary.*'         => 'Salary',
            'we_date_started.*'   => 'Date Started',
            'we_date_ended.*'     => 'Date Ended',
            'cr_name.*'           => 'Reference Name',
            'cr_company.*'        => 'Reference Company',
            'cr_position.*'       => 'Reference Position',
            'cr_cnum.*'           => 'Reference Contact No.',
        ]);

        try {
            DB::transaction(function () use ($request, $validated) {

                // Null-safe uppercasing helper
                $toUpper = fn($val) => $val ? strtoupper($val) : null;

                $validated['lastname']    = strtoupper($validated['lastname']);
                $validated['firstname']   = strtoupper($validated['firstname']);
                $validated['middlename']  = $toUpper($validated['middlename'] ?? null);
                $validated['suffix']      = $toUpper($validated['suffix'] ?? null);
                $validated['father_name'] = $toUpper($validated['father_name'] ?? null);
                $validated['mother_name'] = $toUpper($validated['mother_name'] ?? null);
                $validated['eci_name']    = strtoupper($validated['eci_name']);

                $validated['updated_at']     = Carbon::now('Asia/Manila');
                $validated['updated_byname'] = Auth::user()->name ?? 'System';
                $validated['updated_by_id']   = Auth::id() ?? 0;

                // ✅ Strip repeatable-section array fields — they don't belong to the employee table.
                // This is what was crashing: $validated still had name_of_school/course/year_graduated/
                // gs_*/we_*/cr_* as raw arrays, and EmployeeModel::update() tried to write them as columns.
                $employeeData = Arr::except($validated, [
                    'name_of_school', 'course', 'year_graduated',
                    'gs_name_of_school', 'gs_sch_address', 'gs_course', 'gs_year_graduated',
                    'we_company_name', 'we_position', 'we_salary', 'we_date_started', 'we_date_ended',
                    'cr_name', 'cr_company', 'cr_position', 'cr_cnum',
                ]);

                // Non-current employment status (e.g. Resigned) can never have an active account
                $currentEsId = EmployeeModel::where('employee_id', $employeeData['employee_id'])->value('es_id');
                if (!EmployeeModel::isCurrentEmployeeStatus($currentEsId)) {
                    $employeeData['account_status'] = 0;
                }

                // position_id intentionally excluded — never written
                EmployeeModel::where('employee_id', $employeeData['employee_id'])->update($employeeData);

                $employee = EmployeeModel::where('employee_id', $employeeData['employee_id'])->first();

                // Build display name safely — skip null parts
                $displayName = strtoupper(implode(' ', array_filter([
                    $employee->lastname,
                    $employee->firstname,
                    $employee->suffix,
                    $employee->middlename,
                ])));

                User::where('employee_id', $employeeData['employee_id'])->update([
                    'name'       => $displayName,
                    'email'      => $employeeData['personal_email'] ?? null,
                    'is_active'  => (int) $employeeData['account_status'],
                    'updated_at' => Carbon::now('Asia/Manila'),
                ]);

                // Colleges
                CollegesModel::where('employee_id', $employeeData['employee_id'])->delete();
                foreach ((array) $request->name_of_school as $index => $collegeName) {
                    if (!empty($collegeName)) {
                        CollegesModel::create([
                            'employee_id'    => $employeeData['employee_id'],
                            'name_of_school' => $collegeName,
                            'course'         => $request->course[$index] ?? null,
                            'year_graduated' => $request->year_graduated[$index] ?? null,
                        ]);
                    }
                }

                // Graduate studies
                GraduateStudiesModel::where('employee_id', $employeeData['employee_id'])->delete();
                foreach ((array) $request->gs_name_of_school as $index => $gradSchool) {
                    if (!empty($gradSchool)) {
                        GraduateStudiesModel::create([
                            'employee_id'       => $employeeData['employee_id'],
                            'gs_name_of_school' => $gradSchool,
                            'gs_sch_address'    => $request->gs_sch_address[$index] ?? null,
                            'gs_course'         => $request->gs_course[$index] ?? null,
                            'gs_year_graduated' => $request->gs_year_graduated[$index] ?? null,
                        ]);
                    }
                }

                // Work experience
                WorkExperiencesModel::where('employee_id', $employeeData['employee_id'])->delete();
                foreach ((array) $request->we_company_name as $index => $company) {
                    if (!empty($company)) {
                        WorkExperiencesModel::create([
                            'employee_id'     => $employeeData['employee_id'],
                            'we_company_name' => $company,
                            'we_position'     => $request->we_position[$index] ?? null,
                            'we_salary'       => $request->we_salary[$index] ?? null,
                            'we_date_started' => $request->we_date_started[$index] ?? null,
                            'we_date_ended'   => $request->we_date_ended[$index] ?? null,
                        ]);
                    }
                }

                // Character references
                CharacterReference::where('employee_id', $employeeData['employee_id'])->delete();
                foreach ((array) $request->cr_name as $index => $name) {
                    if (!empty($name)) {
                        CharacterReference::create([
                            'employee_id' => $employeeData['employee_id'],
                            'cr_name'     => $name,
                            'cr_company'  => $request->cr_company[$index] ?? null,
                            'cr_position' => $request->cr_position[$index] ?? null,
                            'cr_cnum'     => $request->cr_cnum[$index] ?? null,
                        ]);
                    }
                }

                SystemLogsModel::addLog('Updated Employee Information', json_encode($employeeData), Auth::id());
            });

            return redirect()->route('employee.employee_list')->with('success', 'Employee updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

 // ✅ Step 2.1: FilePond temporary upload handler
    public function uploadTemp(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->store('temp', 'public'); // stored in storage/app/public/temp
            return response($path, 200); // FilePond expects plain text
        }

        return response('No file uploaded', 400);
    }

    // ✅ Step 2.2: FilePond revert handler (optional)
    public function revertTemp(Request $request)
    {
        $filePath = $request->getContent(); // FilePond sends the filename as the request body

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return response('', 200);
        }

        return response('File not found', 404);
    }

    // ✅ Step 2.3: Final save after form submission
    public function updatePicture(Request $request)
    {
        $id = $request->input('employee_id');
        $uploadedPath = $request->input('uploaded_profile_picture'); // temp/xxxx.jpg

        if (!$uploadedPath || !Storage::disk('public')->exists($uploadedPath)) {
            return back()->withErrors(['profile_picture' => 'No valid profile picture uploaded.']);
        }

        // Move file from temp/ → profile_pictures/
        $newPath = str_replace('temp/', 'profile_pictures/', $uploadedPath);
        Storage::disk('public')->move($uploadedPath, $newPath);

        // Update both tables
        User::where('employee_id', $id)->update([
            'profile_picture' => $newPath,
        ]);
        SystemLogsModel::addLog('Updated Employee Profile Picture', json_encode([['profile_picture'=>$newPath]]), auth()->id());
        EmployeeModel::where('employee_id', $id)->update([
            'profile_picture' => $newPath,
            'updated_at' => Carbon::now('Asia/Manila'),
            'updated_by_id' => Auth::id(),
            'updated_byname' => Auth::user()->name,
        ]);

        return redirect()->route('employee.employee_list')->with('success', 'Employee’s profile was updated successfully!');
    }
    public function export($employee_id)
    {
        $employee = EmployeeModel::where('employee.employee_id', $employee_id)->join('positions', 'employee.position_id', '=', 'positions.position_id')->select('employee.*', 'positions.*')->first();

        abort_if(!$employee, 404, 'Employee not found.');

        $colleges = CollegesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get();
        $grad_studies = GraduateStudiesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get();
        $work_experiences = WorkExperiencesModel::where(['employee_id'=> $employee_id,'is_deleted' => 0])->get();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("templates/tlc_hris_empl_info_template.xlsx");

        $activeWorksheet = $spreadsheet->getSheet(0);
        $activeWorksheet->setCellValue('D6', now('Asia/Manila')->format('M d, Y - h:iA'));
        $activeWorksheet->setCellValue('A9', $employee->lastname);
        $activeWorksheet->setCellValue('B9', $employee->firstname);
        $activeWorksheet->setCellValue('D9', $employee->middlename);
        $activeWorksheet->setCellValue('E9', $employee->suffix);
        $activeWorksheet->setCellValue('A11', $employee->gender);
        $activeWorksheet->setCellValue('B11', $employee->date_of_birth);
        $activeWorksheet->setCellValue('D11', $employee->place_of_birth);
        $activeWorksheet->setCellValue('A13', $employee->civil_status);
        $activeWorksheet->setCellValue('B13', $employee->religion);
        $activeWorksheet->setCellValue('D13', $employee->eci_name);
        $activeWorksheet->setCellValue('A15', $employee->eci_cnumber);
        $activeWorksheet->setCellValue('B15', $employee->street.' '.$employee->barangay.' '.$employee->city.', '.$employee->province);

        $activeWorksheet->setCellValue('A18', $employee->employee_number);
        $activeWorksheet->setCellValue('B18', $employee->tlc_gsuite);
        $activeWorksheet->setCellValue('D18', $employee->date_employed);
        $activeWorksheet->setCellValue('A20', $employee->empl_status);
        $activeWorksheet->setCellValue('B20', $employee->college_code);
        $activeWorksheet->setCellValue('D20', $employee->department);
        $activeWorksheet->setCellValue('A22', $employee->position_name);
        $activeWorksheet->setCellValue('B22', $employee->account_status);
        $activeWorksheet->setCellValue('D22', $employee->smart_card_no);
        $activeWorksheet->setCellValue('A24', $employee->specialization);
        $activeWorksheet->setCellValue('B24', $employee->teaching_load);

        $activeWorksheet->setCellValue('A27', $employee->pagibig);
        $activeWorksheet->setCellValue('B27', $employee->philhealth);
        $activeWorksheet->setCellValue('D27', $employee->tin);
        $activeWorksheet->setCellValue('A29', $employee->sss);
        $activeWorksheet->setCellValue('B29', $employee->bank_account_no);
        $activeWorksheet->setCellValue('D29', $employee->biometric_id);

        $activeWorksheet->setCellValue('A32', $employee->spouse_name);
        $activeWorksheet->setCellValue('B32', $employee->spouse_occupation);
        $activeWorksheet->setCellValue('D32', $employee->spouse_empl_name);
        $activeWorksheet->setCellValue('A34', $employee->father_name);
        $activeWorksheet->setCellValue('C34', $employee->mother_name);
        $activeWorksheet->setCellValue('A36', $employee->childrens);
        $rn = 41;
foreach($colleges as $college){
    $activeWorksheet->setCellValue('A'.$rn, $college->name_of_school);
    $activeWorksheet->setCellValue('B'.$rn, $college->course);
    $activeWorksheet->setCellValue('D'.$rn, $college->year_graduated);
    $rn++;
}

$rn = 49;
foreach($grad_studies as $gs){
    $activeWorksheet->setCellValue('A'.$rn, $gs->gs_name_of_school);
    $activeWorksheet->setCellValue('B'.$rn, $gs->gs_sch_address);
    $activeWorksheet->setCellValue('C'.$rn, $gs->gs_course);
    $activeWorksheet->setCellValue('D'.$rn, $gs->gs_year_graduated);
    $rn++;
}

$rn = 57;
foreach($work_experiences as $we){
    $activeWorksheet->setCellValue('A'.$rn, $we->we_company_name);
    $activeWorksheet->setCellValue('B'.$rn, $we->we_position);
    $activeWorksheet->setCellValue('C'.$rn, $we->we_salary);
    $activeWorksheet->setCellValue('D'.$rn, $we->we_date_started.' - '.$we->we_date_ended);
    $rn++;
}

        // Protect sheet
        $activeWorksheet->getProtection()->setPassword('hris!2025');
        $activeWorksheet->getProtection()->setSheet(true);

        $writer = new Xlsx($spreadsheet);
        $filename = "Employee_Form_{$employee_id}_" . date("M_d_Y") . ".xlsx";
        $writer->save(public_path("xlsx/$filename"));
        SystemLogsModel::addLog('Exported to Xlxs - Employee Information', json_encode([['filename'=>$filename]]), auth()->id());
        return response()->download(
            public_path("xlsx/$filename"),
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    public function bulk_upload_template()
{
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load("templates/TLC_HRIS_BULK_EMPLOYEE_UPLOAD_TEMPLATE.xlsx");

    // Get the "positions" sheet by name
    $positionsSheet = $spreadsheet->getSheetByName('positions');
    $positions = PositionsModel::where(['is_deleted'=> 0, 'is_archived'=>0])->get();
    $rn = 1;

    foreach($positions as $position){
        $rn++;
        $positionsSheet->setCellValue('A'.$rn, $position->position_name);
    }

    // Protect ONLY the 'positions' sheet
    $positionsSheet->getProtection()->setPassword('hris!2025');
    $positionsSheet->getProtection()->setSheet(true);

    $writer = new Xlsx($spreadsheet);
    $filename = "TLC_HRIS_BULK_EMPLOYEE_UPLOAD_TEMPLATE_" . date("M_d_Y") . ".xlsx";
    $writer->save(public_path("xlsx/$filename"));

    SystemLogsModel::addLog('Bulk Upload Employees', json_encode([['filename'=>$filename]]), auth()->id());

    return response()->download(
        public_path("xlsx/$filename"),
        $filename,
        ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
    );
}

    /**
     * Soft-delete an employee (is_deleted = 1 only — the row is never removed)
     * and disable any user login account linked to them.
     */
    public function destroy($employee_id)
    {
        if (!userCan('DELETE EMPLOYEE')) abort(403);

        $employee = EmployeeModel::where('employee_id', $employee_id)
            ->where('is_deleted', 0)
            ->firstOrFail();

        $employee->update(['is_deleted' => 1, 'account_status' => 0]);

        $disabledAccounts = User::where('employee_id', $employee_id)->update(['is_active' => 0]);

        $fullname = trim($employee->firstname . ' ' . $employee->lastname);

        SystemLogsModel::addLog(
            'Delete Employee',
            json_encode([
                'employee_id'      => $employee->employee_id,
                'employee_number'  => $employee->employee_number,
                'name'             => $fullname,
                'account_disabled' => $disabledAccounts > 0,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            Auth::id()
        );

        return redirect()->route('employee.employee_list')->with('success', "{$fullname} was deleted and their account access was disabled.");
    }

    /** List of employees that have been deleted (is_deleted = 1), read-only. */
    public function deletedEmployees()
    {
        if (!userCan('DELETE EMPLOYEE')) abort(403);

        $query = AssignedBranchModel::join('employee', 'assigned_branches.branch_code', '=', 'employee.branch_code')
            ->join('employment_status', 'employee.es_id', '=', 'employment_status.es_id')
            ->join('branches', 'employee.branch_code', '=', 'branches.branch_code')
            ->join('positions', 'employee.position_id', '=', 'positions.position_id')
            ->where([
                'employee.is_deleted' => 1,
                'assigned_branches.ut_id' => Auth::user()->ut_id
            ]);

        return view('employee.deleted_employees', [
            'title'     => 'Deleted Employees - SAVEST HRIS',
            'menu'      => 'Employee',
            'employees' => $query->distinct()->get(),
        ]);
    }

}

