@extends("../layout")
@section("additional_head")
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <link href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/src/plugins/src/filepond/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImagePreview.min.css') }}">
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/plugins/css/light/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/plugins/css/dark/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <style>
        .tracking-tight  { letter-spacing: -0.5px; }
        .tracking-wider  { letter-spacing: 0.5px; }
        .font-12 { font-size: 12px; }
        .font-13 { font-size: 13px; }
        .flatpickr-calendar { z-index: 9999 !important; }
        .emp-avatar {
            width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
            border: 2px solid #e9ecef;
        }
        .emp-avatar-initials {
            width: 36px; height: 36px; border-radius: 50%;
            background: #4361ee; display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
            border: 2px solid #e9ecef; flex-shrink: 0;
        }
        .tbtn { width:24px; height:24px; }
    </style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Employees</h3>
            <p class="text-muted small mb-0">View and manage all registered employee records.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Employees</li>
                </ol>
            </nav>
            @if (in_array('DELETE EMPLOYEE', session('permissions', [])))
            <a href="{{ route('employee.deleted_list') }}" class="btn btn-sm btn-light-dark border d-flex align-items-center gap-1 px-3 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Deleted Employees
            </a>
            @endif
        </div>
    </div>

    {{-- ALERTS --}}
    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    </div>
    @endif

    {{-- FILTER --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <form action="{{ route('employee.employee_list') }}" method="get" id="filterForm">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Branch</label>
                            <select id="branch_code" name="branch_code" autocomplete="off"
                                    onchange="document.getElementById('filterForm').submit();">
                                <option value="All" @if ($data_filtered['branch_code'] == 'All') selected @endif>All</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->branch_code }}"
                                        @if ($data_filtered['branch_code'] == $branch->branch_code) selected @endif>
                                        {{ $branch->branch_code }} {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Position</label>
                            <select id="position_id" name="position_id" autocomplete="off"
                                    onchange="document.getElementById('filterForm').submit();">
                                <option value="All" @if ($data_filtered['position_id'] == 'All') selected @endif>All</option>
                                @foreach ($positions_list as $position)
                                    <option value="{{ $position->position_id }}"
                                        @if ($data_filtered['position_id'] == $position->position_id) selected @endif>
                                        {{ $position->position_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Employment Status</label>
                            <select class="form-select form-select-sm" id="employment_status" name="employment_status"
                                    onchange="document.getElementById('filterForm').submit();">
                                <option value="All" @if ($data_filtered['employment_status'] == 'All') selected @endif>All</option>
                                @foreach ($employment_statuses as $status)
                                    <option value="{{ $status->es_id }}"
                                        @if ($data_filtered['employment_status'] == $status->es_id) selected @endif>
                                        {{ $status->empl_status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Date Employed</label>
                            <input id="date_employed" class="form-control form-control-sm flatpickr-input"
                                   type="text" name="date_employed"
                                   value="{{ $data_filtered['date_employed'] }}"
                                   placeholder="Select Date..."
                                   onchange="document.getElementById('filterForm').submit();">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Salary Grade</label>
                            <select class="form-select form-select-sm" id="salary_grade" name="salary_grade"
                                    onchange="document.getElementById('filterForm').submit();">
                                <option value="all" @if ($data_filtered['salary_grade'] == 'all') selected @endif>All</option>
                                <option value="with" @if ($data_filtered['salary_grade'] == 'with') selected @endif>With Salary Grade</option>
                                <option value="without" @if ($data_filtered['salary_grade'] == 'without') selected @endif>Without Salary Grade</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="zero-config" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Employee</th>
                                <th class="border-0 py-3">Branch</th>
                                <th class="border-0 py-3">Position</th>
                                <th class="border-0 py-3">Employment Status</th>
                                <th class="border-0 py-3">Date Employed</th>
                                <th class="border-0 py-3">Salary Grade</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($employee->profile_picture)
                                            <img alt="avatar" class="emp-avatar"
                                                 src="{{ asset('storage/' . $employee->profile_picture) }}">
                                        @else
                                            <span class="emp-avatar-initials">{{ strtoupper(substr($employee->firstname, 0, 1)) }}</span>
                                        @endif
                                        <div>
                                            <p class="mb-0 fw-semibold font-13">{{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->suffix }} {{ $employee->middlename ? strtoupper(substr($employee->middlename, 0, 1)) . '.' : '' }}</p>
                                            <small class="text-muted">{{ $employee->employee_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-13">{{ $employee->branch_code }} {{ $employee->branch_name }}</td>
                                <td class="font-13">{{ $employee->position_name }}</td>
                                <td class="font-13">{{ $employee->empl_status }}</td>
                                <td class="font-13">{{ \Carbon\Carbon::parse($employee->date_employed)->format('M d, Y') }}</td>
                                <td class="font-13">
                                    @if($employee->has_salary_grade)
                                        <span class="badge bg-success-subtle text-success-emphasis">With Salary Grade</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis">Without Salary Grade Yet</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group dropdown-action" role="group">
                                        <button class="border-0 bg-transparent text-secondary d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="5" r="1.5"></circle><circle cx="12" cy="12" r="1.5"></circle><circle cx="12" cy="19" r="1.5"></circle></svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <a href="{{ route('employee.employee_profile', $employee->employee_id) }}" class="dropdown-item font-13" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                View Employee Info
                                            </a>
                                            @if (in_array('UPDATE EMPLOYEE INFO', session('permissions', [])))
                                            <a href="{{ route('employee.employee_edit', $employee->employee_id) }}" class="dropdown-item font-13">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                Update Employee Info
                                            </a>
                                            @endif
                                            @if (in_array('VIEW EMPLOYEE WEEKLY SCHEDULES', session('permissions', [])))
                                            <a href="{{ route('schedules.load', $employee->employee_id) }}" class="dropdown-item font-13">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                Weekly Schedules
                                            </a>
                                            <a href="{{ route('salary-grade-movement.index', $employee->employee_id) }}" class="dropdown-item font-13">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M7 21V3h6a4.5 4.5 0 0 1 0 9H7"></path><line x1="4" y1="7" x2="17" y2="7"></line><line x1="4" y1="11" x2="17" y2="11"></line></svg>
                                                Salary Grade Movement
                                            </a>
                                            <a href="{{ route('employee-position-movement.index', $employee->employee_id) }}" class="dropdown-item font-13">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"></circle><polyline points="16 12 12 8 8 12"></polyline><line x1="12" y1="16" x2="12" y2="8"></line></svg>
                                                Position Movement
                                            </a>
                                            @if (in_array('VIEW BIOMETRIC IDS', session('permissions', [])))
                                            <a href="{{ route('biometric-ids.index', $employee->employee_id) }}" class="dropdown-item font-13">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M17 11V9a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2"></path><rect x="5" y="11" width="14" height="10" rx="2"></rect><circle cx="12" cy="16" r="1.5"></circle></svg>
                                                Biometric IDs
                                            </a>
                                            @endif
                                            <a href="javascript:void(0);" class="dropdown-item font-13 update-picture-btn"
                                               data-id="{{ $employee->employee_id }}"
                                               data-fullname="{{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->suffix }} {{ $employee->middlename }}"
                                               data-bs-toggle="modal" data-bs-target="#UpdateProfilePicModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                                Update Profile Picture
                                            </a>
                                            <a href="javascript:void(0);" class="dropdown-item font-13"
                                               onclick="load_update_empnum_modal({{ $employee->employee_id }},'{{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->suffix }} {{ $employee->middlename }}','{{ $employee->employee_number }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                Update Employee ID
                                            </a>
                                            @endif
                                            @if (in_array('UPDATE EMPLOYMENT STATUS', session('permissions', [])))
                                            <a href="{{ route('employment-status-movement.load', $employee->employee_id) }}" class="dropdown-item font-13">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                Employment Status Movement
                                            </a>
                                            @endif
                                            @if (in_array('DELETE EMPLOYEE', session('permissions', [])))
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('employee.delete', $employee->employee_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item font-13 text-danger"
                                                        onclick="return confirm('Delete {{ $employee->lastname }}, {{ $employee->firstname }}? This also disables their user account. The record will move to Deleted Employees.');">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    Delete Employee
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section("additional_footer")

{{-- Update Profile Picture Modal --}}
<div id="UpdateProfilePicModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    Update Profile Picture
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdateProfilePicForm" method="POST" action="{{ route('employee.update-picture') }}" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <input type="hidden" name="employee_id" id="profile_pic_employee_id">
                    <input type="hidden" name="uploaded_profile_picture" id="uploaded_profile_picture">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Employee</label>
                        <input type="text" class="form-control form-control-sm bg-light" id="emp_fullname" readonly>
                    </div>
                    <div class="img-uploader-content">
                        <input type="file" class="filepond" name="profile_picture" id="profile_picture" accept="image/png, image/jpeg, image/gif"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="UpdateProfilePicForm" class="btn btn-primary px-4 shadow-sm" id="updatePicBtn" disabled>Update</button>
            </div>
        </div>
    </div>
</div>

{{-- Update Employee ID Modal --}}
<div id="EmployeeNumberModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Update Employee ID
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('employee.update_employee_number') }}" method="post" id="EmployeeNumberForm">
                    @csrf
                    <input type="hidden" id="empnum_employee_id" name="employee_id">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Employee</label>
                        <input type="text" class="form-control form-control-sm bg-light" id="empnum_fullname" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="empnum_employee_number" name="employee_number">
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="EmployeeNumberForm" class="btn btn-primary px-4 shadow-sm">Update</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/flatpickr/custom-flatpickr.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/filepond.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    window.load_update_empnum_modal = (employeeId, emp_fullname, employmentnumber) => {
        $('#empnum_employee_id').val(employeeId);
        $('#empnum_fullname').val(emp_fullname);
        $('#empnum_employee_number').val(employmentnumber);
        new bootstrap.Modal(document.getElementById('EmployeeNumberModal')).show();
    };

    flatpickr('#date_employed');

    $('#zero-config').DataTable({
        dom:
            "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
            "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'" +
            "<'dt--pages-count mb-sm-0 mb-3'i>" +
            "<'dt--pagination'p>>",
        oLanguage: {
            oPaginate: {
                sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                sNext:     '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            },
            sInfo: "Showing entries _START_ to _END_ of _TOTAL_",
            sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            sSearchPlaceholder: "Quick filter...",
            sLengthMenu: "Display: _MENU_"
        },
        stripeClasses: [],
        lengthMenu: [10, 20, 50],
        pageLength: 10
    });

    $('#zero-config').on('show.bs.dropdown', '.btn-group', function () {
        const $menu = $(this).find('.dropdown-menu');
        $menu.appendTo('body').css({
            display: 'block', position: 'absolute',
            top: $(this).offset().top + $(this).outerHeight(),
            left: $(this).offset().left, zIndex: 1055
        });
        if ($('body').hasClass('dark')) $menu.addClass('dropdown-menu-dark');
        else $menu.removeClass('dropdown-menu-dark');
    }).on('hide.bs.dropdown', '.btn-group', function () {
        $('body > .dropdown-menu').removeAttr('style').appendTo(this);
    });

    new TomSelect('#position_id', {
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        placeholder: 'Select or type a position'
    });

    new TomSelect('#branch_code', {
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        placeholder: 'Select or type a branch'
    });

    $(document).on('click', '.update-picture-btn', function () {
        $('#profile_pic_employee_id').val($(this).data('id'));
        $('#emp_fullname').val($(this).data('fullname'));
        $('#uploaded_profile_picture').val('');
    });

    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginImageCrop,
        FilePondPluginImageResize,
        FilePondPluginImageTransform
    );

    const pond = FilePond.create(document.querySelector('#profile_picture'), {
        allowMultiple: false,
        acceptedFileTypes: ['image/png', 'image/jpeg', 'image/gif'],
        instantUpload: true,
        server: {
            process: {
                url: '/upload-temp', method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                onload: (response) => {
                    $('#uploaded_profile_picture').val(response);
                    return response;
                }
            },
            revert: {
                url: '/revert-temp', method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                onload: () => { $('#uploaded_profile_picture').val(''); }
            }
        }
    });

    const updatePicBtn = document.getElementById('updatePicBtn');
    pond.on('processfilestart', () => { updatePicBtn.disabled = true; updatePicBtn.textContent = 'Uploading...'; });
    pond.on('processfile',      () => { updatePicBtn.disabled = false; updatePicBtn.textContent = 'Update'; });
    pond.on('processfileabort', () => { updatePicBtn.disabled = true;  updatePicBtn.textContent = 'Update'; });
    pond.on('removefile',       () => { updatePicBtn.disabled = true; });

    document.querySelectorAll('.btn[title], a.btn[title]').forEach(function(el) {
        new bootstrap.Tooltip(el, { trigger: 'hover' });
    });
});
</script>
@endsection
