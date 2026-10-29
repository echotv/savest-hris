@extends("../layout")
@section("additional_head")
<link href="{{ asset('assets/src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/src/autocomplete/css/autoComplete.02.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/filepond/filepond.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImagePreview.min.css') }}">
<link href="{{ asset('assets/src/plugins/css/light/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/css/dark/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/plugins/src/noUiSlider/nouislider.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/css/light/autocomplete/css/custom-autoComplete.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/css/dark/autocomplete/css/custom-autoComplete.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
<style>
/* ── Section card headers ─────────────────────────────── */
.section-card > .card-header {
    background: transparent;
    border-bottom: 1px solid var(--bs-border-color, #e0e6ed);
    padding: 0.9rem 1.25rem 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.section-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px; height: 26px;
    border-radius: 50%;
    background: #1b55e2;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    flex-shrink: 0;
}
.section-card > .card-header h5 { margin-bottom: 0; font-size: 0.95rem; }

/* ── Sub-section dividers ─────────────────────────────── */
.sub-section { padding: 0.1rem 0 0.5rem; margin-top: 0.75rem; }
.sub-section-label {
    display: inline-block;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: #888;
    padding-left: 0.5rem;
    border-left: 3px solid #1b55e2;
    margin-bottom: 0.75rem;
    line-height: 1;
}

/* ── Repeatable items ─────────────────────────────────── */
.repeatable-item {
    position: relative;
    background: rgba(0,0,0,0.02);
    border: 1px solid var(--bs-border-color, #e0e6ed);
    border-radius: 8px;
    padding: 1rem 1rem 0.25rem;
    margin-bottom: 0.75rem;
}
body.dark .repeatable-item { background: rgba(255,255,255,0.03); }

.btn-remove-row {
    position: absolute;
    top: 0.5rem; right: 0.5rem;
    width: 22px; height: 22px;
    border-radius: 50%;
    border: none;
    background: rgba(231,81,90,0.13);
    color: #e7515a;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    cursor: pointer;
    transition: background 0.15s;
}
.btn-remove-row:hover { background: rgba(231,81,90,0.28); }
.btn-remove-row svg { width: 12px; height: 12px; }

/* ── Add-row button ───────────────────────────────────── */
.btn-add-row {
    border: 1.5px dashed #1b55e2;
    color: #1b55e2;
    background: transparent;
    border-radius: 6px;
    padding: 0.3rem 0.85rem;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    transition: background 0.15s;
    cursor: pointer;
}
.btn-add-row:hover { background: rgba(27,85,226,0.07); color: #1b55e2; }
.btn-add-row svg { width: 14px; height: 14px; }

/* ── autoComplete sizing ──────────────────────────────── */
.autoComplete_wrapper > input,
#province, #city, #barangay, #religion {
    height: calc(1.5em + 0.5rem + 7px) !important;
    font-size: 0.875rem !important;
    padding: 0.25rem 0.5rem !important;
    line-height: 1.5 !important;
}
.autoComplete_result { font-size: 0.875rem; padding: 4px 8px; }
.autoComplete_wrapper { width: 100%; }
.autoComplete_wrapper > ul { font-size: 0.875rem; }
.autoComplete_wrapper > ul > li { padding: 4px 8px; }

/* ── Edu column labels ────────────────────────────────── */
.col-label { font-size: 0.8rem; font-weight: 600; color: #555; margin-bottom: 0.25rem; }
body.dark .col-label { color: #aaa; }

/* ── Sticky section nav ───────────────────────────────── */
.section-nav {
    position: fixed;
    right: 1.25rem;
    bottom: 2rem;
    z-index: 1050;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.35rem;
    pointer-events: none;
}
.section-nav-btn {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    pointer-events: auto;
    padding: 0.3rem 0.65rem 0.3rem 0.5rem;
    font-size: 0.72rem;
    font-weight: 600;
    color: #555;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    white-space: nowrap;
    max-width: 2rem;
    overflow: hidden;
    transition: max-width 0.25s ease, padding 0.25s ease, background 0.15s, color 0.15s;
}
.section-nav:hover .section-nav-btn,
.section-nav-btn:hover,
.section-nav-btn.active { max-width: 220px; }
.section-nav-btn:hover,
.section-nav-btn.active {
    background: #1b55e2;
    color: #fff;
    border-color: #1b55e2;
    box-shadow: 0 4px 12px rgba(27,85,226,0.3);
    text-decoration: none;
}
.section-nav-btn .nav-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px; height: 18px;
    border-radius: 50%;
    background: #1b55e2;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    flex-shrink: 0;
}
.section-nav-btn:hover .nav-num,
.section-nav-btn.active .nav-num { background: rgba(255,255,255,0.25); }
.section-nav-btn .nav-label { white-space: nowrap; overflow: hidden; }
body.dark .section-nav-btn { background: #1a1a2e; border-color: #333; color: #bbb; }
body.dark .section-nav-btn:hover,
body.dark .section-nav-btn.active { background: #1b55e2; color: #fff; border-color: #1b55e2; }
@media (max-width: 576px) {
    .section-nav { right: 0.5rem; bottom: 1rem; }
    .section-nav-btn .nav-label { display: none; }
    .section-nav-btn { max-width: 2rem !important; padding: 0.3rem 0.4rem !important; }
}
</style>
@endsection

@section("content")
<form action="{{ route('employee.update') }}" id="updateForm" method="post">
@csrf
<input type="hidden" id="employee_id" name="employee_id" value="{{ $employee->employee_id }}">
<div class="row layout-top-spacing">

    {{-- Page Title --}}
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Employee Information</h4>
            <p class="mb-0 text-muted" style="font-size:0.85rem;">Update employee record for <strong>{{ $employee->firstname }} {{ $employee->lastname }}</strong>.</p>
        </div>
        <nav class="breadcrumb-style-one mt-2 mt-md-0" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Update Employee</li>
            </ol>
        </nav>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════
         SECTION 1 — PERSONAL DATA
    ═══════════════════════════════════════════ --}}
    <div class="col-12 mb-3" id="sec-personal">
        <div class="card section-card">
            <div class="card-header">
                <span class="section-badge">1</span>
                <h5 class="fw-bold">Personal Data</h5>
            </div>
            <div class="card-body pt-2">

                {{-- Name & Identity --}}
                <div class="sub-section">
                    <span class="sub-section-label">Name &amp; Identity</span>
                    <div class="row">
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="lastname" name="lastname" value="{{ $employee->lastname }}" required>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="firstname" name="firstname" value="{{ $employee->firstname }}" required>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Middle Name</label>
                            <input type="text" class="form-control form-control-sm" id="middlename" name="middlename" value="{{ $employee->middlename }}">
                        </div>
                        <div class="col-md-1 col-6 mb-2">
                            <label class="form-label form-label-sm">Suffix</label>
                            <select class="form-select form-select-sm" id="suffix" name="suffix">
                                <option value="" @selected($employee->suffix == '')></option>
                                <option value="JR." @selected($employee->suffix == 'JR.')>JR.</option>
                                <option value="SR." @selected($employee->suffix == 'SR.')>SR.</option>
                                <option value="II"  @selected($employee->suffix == 'II')>II</option>
                                <option value="III" @selected($employee->suffix == 'III')>III</option>
                                <option value="IV"  @selected($employee->suffix == 'IV')>IV</option>
                                <option value="V"   @selected($employee->suffix == 'V')>V</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <label class="form-label form-label-sm">Gender <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="gender" name="gender">
                                <option value="Female" @selected($employee->gender == 'Female')>Female</option>
                                <option value="Male"   @selected($employee->gender == 'Male')>Male</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Date of Birth <span class="text-danger">*</span></label>
                            <input id="date_of_birth" name="date_of_birth" class="form-control form-control-sm flatpickr flatpickr-input active" type="text" placeholder="Select date…" value="{{ $employee->date_of_birth }}" required>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Place of Birth <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="place_of_birth" name="place_of_birth" value="{{ $employee->place_of_birth }}" required>
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <label class="form-label form-label-sm">Civil Status <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="civil_status" name="civil_status">
                                <option value="Single"    @selected($employee->civil_status == 'Single')>Single</option>
                                <option value="Married"   @selected($employee->civil_status == 'Married')>Married</option>
                                <option value="Widowed"   @selected($employee->civil_status == 'Widowed')>Widowed</option>
                                <option value="Separated" @selected($employee->civil_status == 'Separated')>Separated</option>
                                <option value="Divorced"  @selected($employee->civil_status == 'Divorced')>Divorced</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <label class="form-label form-label-sm">Religion <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="religion" name="religion" value="{{ $employee->religion }}" required>
                        </div>
                        <div class="col-md-2 col-12 mb-2">
                            <label class="form-label form-label-sm">Citizenship <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="citizenship" name="citizenship" value="{{ $employee->citizenship }}" required>
                        </div>
                    </div>
                </div>

                {{-- Physical --}}
                <div class="sub-section">
                    <span class="sub-section-label">Physical Information</span>
                    <div class="row">
                        <div class="col-md-2 col-6 mb-2">
                            <label class="form-label form-label-sm">Height (cm)</label>
                            <input type="number" step="0.01" class="form-control form-control-sm" id="height" name="height" value="{{ $employee->height }}">
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <label class="form-label form-label-sm">Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control form-control-sm" id="weight" name="weight" value="{{ $employee->weight }}">
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="sub-section">
                    <span class="sub-section-label">Contact Information</span>
                    <div class="row">
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm" id="personal_email" name="personal_email" value="{{ $employee->personal_email }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">Telephone No.</label>
                            <input type="text" class="form-control form-control-sm" id="telephone_no" name="telephone_no" value="{{ $employee->telephone_no }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">Cellphone No.</label>
                            <input type="text" class="form-control form-control-sm" id="cellphone_no" name="cellphone_no" value="{{ $employee->cellphone_no }}">
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div class="sub-section">
                    <span class="sub-section-label">Emergency Contact</span>
                    <div class="row">
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="eci_name" name="eci_name" value="{{ $employee->eci_name }}" required>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Contact No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="eci_cnumber" name="eci_cnumber" value="{{ $employee->eci_cnumber }}" required>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="eci_address" name="eci_address" value="{{ $employee->eci_address }}" required>
                        </div>
                    </div>
                </div>

                {{-- Government IDs --}}
                <div class="sub-section">
                    <span class="sub-section-label">Government IDs</span>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">SSS No.</label>
                            <input type="text" class="form-control form-control-sm" id="sss" name="sss" value="{{ $employee->sss }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">TIN No.</label>
                            <input type="text" class="form-control form-control-sm" id="tin" name="tin" value="{{ $employee->tin }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">PhilHealth No.</label>
                            <input type="text" class="form-control form-control-sm" id="philhealth" name="philhealth" value="{{ $employee->philhealth }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">PAGIBIG No.</label>
                            <input type="text" class="form-control form-control-sm" id="pagibig" name="pagibig" value="{{ $employee->pagibig }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">National ID</label>
                            <input type="text" class="form-control form-control-sm" id="national_id" name="national_id" value="{{ $employee->national_id }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">Driver's License No.</label>
                            <input type="text" class="form-control form-control-sm" id="drivers_license_no" name="drivers_license_no" value="{{ $employee->drivers_license_no }}">
                        </div>
                    </div>
                </div>

                {{-- Home Address --}}
                <div class="sub-section">
                    <span class="sub-section-label">Home Address</span>
                    <div class="row">
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Province <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="province" name="province" value="{{ $employee->province }}" required>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">City / Municipality <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="city" name="city" value="{{ $employee->city }}" required disabled>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Barangay <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="barangay" name="barangay" value="{{ $employee->barangay }}" required disabled>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Street / House No.</label>
                            <input type="text" class="form-control form-control-sm" id="street" name="street" value="{{ $employee->street }}">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECTION 2 — EMPLOYMENT DETAILS
    ═══════════════════════════════════════════ --}}
    <div class="col-12 mb-3" id="sec-employment">
        <div class="card section-card">
            <div class="card-header">
                <span class="section-badge">2</span>
                <h5 class="fw-bold">Employment Details</h5>
            </div>
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-md-3 col-12 mb-2">
                        <label class="form-label form-label-sm">Employee ID</label>
                        <input type="text" class="form-control form-control-sm" id="employee_number" name="employee_number" value="{{ $employee->employee_number }}" disabled>
                    </div>
                    <div class="col-md-3 col-12 mb-2">
                        <label class="form-label form-label-sm">Date Employed <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="date_employed" name="date_employed" value="{{ $employee->date_employed }}" required>
                    </div>
                    <div class="col-md-3 col-12 mb-2">
                        <label class="form-label form-label-sm">Account Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="account_status" name="account_status">
                            @if ($is_current_employee)
                            <option value="1" @selected($employee->account_status == 1)>Active</option>
                            <option value="0" @selected($employee->account_status == 0)>Inactive</option>
                            @else
                            <option value="1" disabled>Active</option>
                            <option value="0" selected>Inactive</option>
                            @endif
                        </select>
                        @unless ($is_current_employee)
                        <small class="text-muted">Inactive because the current employment status is not a current employee.</small>
                        @endunless
                    </div>
                    <div class="col-md-3 col-12 mb-2">
                        <label class="form-label form-label-sm">Branch <span class="text-danger">*</span></label>
                        <select id="branch_code" name="branch_code" autocomplete="off">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->branch_code }}" @selected($employee->branch_code == $branch->branch_code)>
                                    {{ $branch->branch_code }} {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECTION 3 — FAMILY BACKGROUND
    ═══════════════════════════════════════════ --}}
    <div class="col-12 mb-3" id="sec-family">
        <div class="card section-card">
            <div class="card-header">
                <span class="section-badge">3</span>
                <h5 class="fw-bold">Family Background</h5>
            </div>
            <div class="card-body pt-3">
                <div id="spouse_info"></div>
                <div class="row">
                    <div class="col-md-6 col-12 mb-2">
                        <label class="form-label form-label-sm">Father's Name</label>
                        <input type="text" class="form-control form-control-sm" id="father_name" name="father_name" value="{{ $employee->father_name }}">
                    </div>
                    <div class="col-md-6 col-12 mb-2">
                        <label class="form-label form-label-sm">Mother's Name</label>
                        <input type="text" class="form-control form-control-sm" id="mother_name" name="mother_name" value="{{ $employee->mother_name }}">
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label form-label-sm">Children <span class="text-muted small">(separate names with a comma)</span></label>
                        <textarea class="form-control form-control-sm" id="childrens" name="childrens" rows="2">{{ $employee->childrens }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECTION 4 — EDUCATIONAL BACKGROUND
    ═══════════════════════════════════════════ --}}
    <div class="col-12 mb-3" id="sec-education">
        <div class="card section-card">
            <div class="card-header">
                <span class="section-badge">4</span>
                <h5 class="fw-bold">Educational Background</h5>
            </div>
            <div class="card-body pt-2">

                {{-- College --}}
                <div class="sub-section">
                    <span class="sub-section-label">College</span>
                    <div class="row mb-1 d-none d-md-flex px-1">
                        <div class="col-md-4"><span class="col-label">Name of School <span class="text-danger">*</span></span></div>
                        <div class="col-md-4"><span class="col-label">Course / Major <span class="text-danger">*</span></span></div>
                        <div class="col-md-3"><span class="col-label">Year Graduated <span class="text-danger">*</span></span></div>
                    </div>
                    <div class="college_list">
                        @foreach ($colleges as $college)
                        <div class="college-item repeatable-item">
                            <button type="button" class="btn-remove-row delete-btn" title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <div class="row">
                                <div class="col-md-4 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Name of School <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="name_of_school[]" value="{{ $college->name_of_school }}" required>
                                </div>
                                <div class="col-md-4 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Course / Major <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="course[]" value="{{ $college->course }}" required>
                                </div>
                                <div class="col-md-3 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Year Graduated <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm year_graduated" name="year_graduated[]" min="1920" max="2025" value="{{ $college->year_graduated }}" required>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-add-row mt-1" id="addBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add College
                    </button>
                </div>

                {{-- Graduate Studies --}}
                <div class="sub-section mt-3">
                    <span class="sub-section-label">Graduate Studies</span>
                    <div class="row mb-1 d-none d-md-flex px-1">
                        <div class="col-md-3"><span class="col-label">Name of School</span></div>
                        <div class="col-md-3"><span class="col-label">School Address</span></div>
                        <div class="col-md-3"><span class="col-label">Course / Major</span></div>
                        <div class="col-md-2"><span class="col-label">Year Graduated</span></div>
                    </div>
                    <div class="grad_stud_list">
                        @foreach ($grad_studies as $gs)
                        <div class="grad_stud-item repeatable-item">
                            <button type="button" class="btn-remove-row delete-gs-btn" title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <div class="row">
                                <div class="col-md-3 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Name of School</label>
                                    <input type="text" class="form-control form-control-sm" name="gs_name_of_school[]" value="{{ $gs->gs_name_of_school }}" required>
                                </div>
                                <div class="col-md-3 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">School Address</label>
                                    <input type="text" class="form-control form-control-sm" name="gs_sch_address[]" value="{{ $gs->gs_sch_address }}" required>
                                </div>
                                <div class="col-md-3 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Course / Major</label>
                                    <input type="text" class="form-control form-control-sm" name="gs_course[]" value="{{ $gs->gs_course }}" required>
                                </div>
                                <div class="col-md-2 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Year Graduated</label>
                                    <input type="number" class="form-control form-control-sm year_graduated" name="gs_year_graduated[]" min="1920" max="2050" value="{{ $gs->gs_year_graduated }}" required>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-add-row mt-1" id="addGradStudBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Graduate Study
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECTION 5 — EMPLOYMENT RECORD
    ═══════════════════════════════════════════ --}}
    <div class="col-12 mb-3" id="sec-workex">
        <div class="card section-card">
            <div class="card-header">
                <span class="section-badge">5</span>
                <h5 class="fw-bold">Employment Record</h5>
            </div>
            <div class="card-body pt-3">
                <div class="workex_list">
                    @foreach ($grad_studies as $gs)
                    <div class="workex-item repeatable-item">
                        <button type="button" class="btn-remove-row delete-workex-btn" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="we_company_name[]" value="{{ $gs->we_company_name }}" required>
                            </div>
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="we_position[]" value="{{ $gs->we_position }}" required>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                                <label class="form-label form-label-sm">Salary (PHP) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" name="we_salary[]" min="1" value="{{ $gs->we_salary }}" required>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <label class="form-label form-label-sm">Date Started <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm we_date_started" name="we_date_started[]" value="{{ $gs->we_date_started }}" required>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <label class="form-label form-label-sm">Date Ended <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm we_date_ended" name="we_date_ended[]" value="{{ $gs->we_date_ended }}" required>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-row mt-1" id="add-workex-Btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Employment Record
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECTION 6 — CHARACTER REFERENCES
    ═══════════════════════════════════════════ --}}
    <div class="col-12 mb-3" id="sec-references">
        <div class="card section-card">
            <div class="card-header">
                <span class="section-badge">6</span>
                <h5 class="fw-bold">Character References</h5>
            </div>
            <div class="card-body pt-3">
                <div class="cref_list">
                    @foreach ($character_references as $cref)
                    <div class="cref-item repeatable-item">
                        <button type="button" class="btn-remove-row delete-cref-btn" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cr_name[]" value="{{ $cref->cr_name }}" required>
                            </div>
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cr_company[]" value="{{ $cref->cr_company }}" required>
                            </div>
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cr_position[]" value="{{ $cref->cr_position }}" required>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                                <label class="form-label form-label-sm">Contact No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cr_cnum[]" value="{{ $cref->cr_cnum }}" required>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-row mt-1" id="add-cref-Btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Reference
                </button>
            </div>
        </div>
    </div>

    {{-- Form Actions --}}
    <div class="col-12 mb-4 pb-2 d-flex justify-content-end gap-2">
        <a href="{{ route('employee.employee_list') }}" class="btn btn-dark">Cancel</a>
        <button type="submit" class="btn btn-info px-4">Update Record</button>
    </div>

</div>
</form>

{{-- Sticky Section Navigator — outside form so it never blocks inputs --}}
<nav class="section-nav" aria-label="Jump to section">
    <a href="#sec-personal"   class="section-nav-btn"><span class="nav-num">1</span><span class="nav-label">Personal Data</span></a>
    <a href="#sec-employment" class="section-nav-btn"><span class="nav-num">2</span><span class="nav-label">Employment Details</span></a>
    <a href="#sec-family"     class="section-nav-btn"><span class="nav-num">3</span><span class="nav-label">Family Background</span></a>
    <a href="#sec-education"  class="section-nav-btn"><span class="nav-num">4</span><span class="nav-label">Educational Background</span></a>
    <a href="#sec-workex"     class="section-nav-btn"><span class="nav-num">5</span><span class="nav-label">Employment Record</span></a>
    <a href="#sec-references" class="section-nav-btn"><span class="nav-num">6</span><span class="nav-label">Character References</span></a>
</nav>
@endsection

@section("additional_footer")
<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/filepond.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/custom-filepond.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/autocomplete/autoComplete.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/autocomplete/custom-autoComplete.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/input-mask/input-mask.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script>
flatpickr(document.getElementById('date_of_birth'), { defaultDate: "{{ $employee->date_of_birth }}" });
flatpickr(document.getElementById('date_employed'),  { defaultDate: "{{ $employee->date_employed }}" });

function initWorkExpPickers() {
    document.querySelectorAll('.we_date_started').forEach(function(el) {
        if (!el.classList.contains('flatpickr-input'))
            flatpickr(el, { dateFormat: "Y-m-d", defaultDate: new Date() });
    });
    document.querySelectorAll('.we_date_ended').forEach(function(el) {
        if (!el.classList.contains('flatpickr-input'))
            flatpickr(el, { dateFormat: "Y-m-d", defaultDate: new Date() });
    });
}
initWorkExpPickers();

/* ── Remove buttons (delegated) ───────────────────── */
document.addEventListener("click", function(e) {
    let btn = e.target.closest(".delete-btn");
    if (btn) { btn.closest(".college-item")?.remove(); return; }

    btn = e.target.closest(".delete-gs-btn");
    if (btn) { btn.closest(".grad_stud-item")?.remove(); return; }

    btn = e.target.closest(".delete-workex-btn");
    if (btn) { btn.closest(".workex-item")?.remove(); initWorkExpPickers(); return; }

    btn = e.target.closest(".delete-cref-btn");
    if (btn) { btn.closest(".cref-item")?.remove(); return; }
});

/* ── Add College ──────────────────────────────────── */
document.getElementById("addBtn").addEventListener("click", function() {
    document.querySelector(".college_list").insertAdjacentHTML('beforeend', `
        <div class="college-item repeatable-item">
            <button type="button" class="btn-remove-row delete-btn" title="Remove">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="row">
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Name of School <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="name_of_school[]" required>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Course / Major <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="course[]" required>
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Year Graduated <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm year_graduated" name="year_graduated[]" min="1920" max="2025" required>
                </div>
            </div>
        </div>`);
});

/* ── Add Graduate Study ───────────────────────────── */
document.getElementById("addGradStudBtn").addEventListener("click", function() {
    document.querySelector(".grad_stud_list").insertAdjacentHTML('beforeend', `
        <div class="grad_stud-item repeatable-item">
            <button type="button" class="btn-remove-row delete-gs-btn" title="Remove">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="row">
                <div class="col-md-3 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Name of School</label>
                    <input type="text" class="form-control form-control-sm" name="gs_name_of_school[]" required>
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">School Address</label>
                    <input type="text" class="form-control form-control-sm" name="gs_sch_address[]" required>
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Course / Major</label>
                    <input type="text" class="form-control form-control-sm" name="gs_course[]" required>
                </div>
                <div class="col-md-2 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Year Graduated</label>
                    <input type="number" class="form-control form-control-sm year_graduated" name="gs_year_graduated[]" min="1920" max="2050" required>
                </div>
            </div>
        </div>`);
});

/* ── Add Work Experience ──────────────────────────── */
document.getElementById("add-workex-Btn").addEventListener("click", function() {
    document.querySelector(".workex_list").insertAdjacentHTML('beforeend', `
        <div class="workex-item repeatable-item">
            <button type="button" class="btn-remove-row delete-workex-btn" title="Remove">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="row">
                <div class="col-md-6 col-12 mb-2">
                    <label class="form-label form-label-sm">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="we_company_name[]" required>
                </div>
                <div class="col-md-6 col-12 mb-2">
                    <label class="form-label form-label-sm">Position <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="we_position[]" required>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm">Salary (PHP) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" name="we_salary[]" min="1" required>
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label class="form-label form-label-sm">Date Started <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm we_date_started" name="we_date_started[]" required>
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label class="form-label form-label-sm">Date Ended <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm we_date_ended" name="we_date_ended[]" required>
                </div>
            </div>
        </div>`);
    initWorkExpPickers();
});

/* ── Add Character Reference ──────────────────────── */
document.getElementById("add-cref-Btn").addEventListener("click", function() {
    document.querySelector(".cref_list").insertAdjacentHTML('beforeend', `
        <div class="cref-item repeatable-item">
            <button type="button" class="btn-remove-row delete-cref-btn" title="Remove">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="row">
                <div class="col-md-6 col-12 mb-2">
                    <label class="form-label form-label-sm">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="cr_name[]" required>
                </div>
                <div class="col-md-6 col-12 mb-2">
                    <label class="form-label form-label-sm">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="cr_company[]" required>
                </div>
                <div class="col-md-6 col-12 mb-2">
                    <label class="form-label form-label-sm">Position <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="cr_position[]" required>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm">Contact No. <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="cr_cnum[]" required>
                </div>
            </div>
        </div>`);
});

/* ── Spouse fields ────────────────────────────────── */
$(document).ready(function () {
    new TomSelect('#branch_code', {
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        placeholder: 'Select or type a branch'
    });

    var spouseFields = `
        <div class="sub-section mb-2">
            <span class="sub-section-label">Spouse Information</span>
            <div class="row">
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm">Spouse's Name</label>
                    <input type="text" class="form-control form-control-sm" id="spouse_name" name="spouse_name" value="{{ $employee->spouse_name }}" required>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm">Spouse's Occupation</label>
                    <input type="text" class="form-control form-control-sm" id="spouse_occupation" name="spouse_occupation" value="{{ $employee->spouse_occupation }}" required>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm">Spouse's Employer</label>
                    <input type="text" class="form-control form-control-sm" id="spouse_empl_name" name="spouse_empl_name" value="{{ $employee->spouse_empl_name }}" required>
                </div>
            </div>
        </div>`;

    // Show spouse fields immediately if currently married
    if ($("#civil_status").val() === "Married") {
        $("#spouse_info").html(spouseFields);
    }

    $("#civil_status").change(function () {
        if ($(this).val() === "Married") {
            $("#spouse_info").html(spouseFields);
        } else {
            $("#spouse_info").empty();
        }
    });
});

/* ── Input masks ─────────────────────────────────── */
$('#tin').inputmask({mask: "999-999-999-999"});
$('#sss').inputmask({mask: "99-9999999-9"});
$('#national_id').inputmask({mask: "9999-9999-9999-9999"});
$('#philhealth').inputmask({mask: "99-999999999-9"});
$('#pagibig').inputmask({mask: "9999-9999-9999"});
$('#cellphone_no').inputmask({mask: "99999999999"});
$('#eci_cnumber').inputmask({mask: "99999999999"});



/* ── Active section highlight on scroll ───────────── */
(function () {
    const navLinks = document.querySelectorAll('.section-nav-btn');
    const sections = ['sec-personal','sec-employment','sec-family','sec-education','sec-workex','sec-references'];
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                navLinks.forEach(function(a) { a.classList.remove('active'); });
                const active = document.querySelector('.section-nav-btn[href="#' + entry.target.id + '"]');
                if (active) active.classList.add('active');
            }
        });
    }, { threshold: 0.25 });
    sections.forEach(function(id) {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
    });
})();

/* ── PSGC Address Autocomplete ───────────────────── */
async function fetchProvinces() {
    const res = await fetch('https://psgc.gitlab.io/api/provinces/');
    const data = await res.json();
    return data.map(p => ({ name: p.name, code: p.code }));
}
async function fetchCities(provinceCode) {
    const res = await fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`);
    const data = await res.json();
    return data.map(c => ({ name: c.name, code: c.code }));
}
async function fetchBarangays(cityCode) {
    const res = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`);
    const data = await res.json();
    return data.map(b => b.name);
}
(async () => {
    const provinces = await fetchProvinces();
    let citiesData = [];
    let barangaysData = [];

    const existingProvince = document.querySelector("#province").value;
    const existingCity     = document.querySelector("#city").value;

    if (existingProvince) {
        const province = provinces.find(p => p.name === existingProvince);
        if (province) {
            citiesData = await fetchCities(province.code);
            document.querySelector("#city").disabled = false;
            if (existingCity) {
                const city = citiesData.find(c => c.name === existingCity);
                if (city) {
                    barangaysData = await fetchBarangays(city.code);
                    document.querySelector("#barangay").disabled = false;
                }
            }
        }
    }

    const provinceAuto = new autoComplete({
        selector: "#province",
        placeHolder: "Search Province",
        data: { src: provinces.map(p => p.name), cache: true },
        resultItem: { highlight: { render: true } },
        events: { input: { selection: async (event) => {
            const name = event.detail.selection.value;
            const prov = provinces.find(p => p.name === name);
            provinceAuto.input.value = name;
            document.querySelector("#city").value = "";
            document.querySelector("#barangay").value = "";
            document.querySelector("#barangay").disabled = true;
            citiesData = await fetchCities(prov.code);
            document.querySelector("#city").disabled = false;
            if (window.cityAutoInstance) window.cityAutoInstance.data.src = citiesData.map(c => c.name);
        }}}
    });

    window.cityAutoInstance = new autoComplete({
        selector: "#city",
        placeHolder: "Search City/Municipality",
        data: { src: citiesData.map(c => c.name), cache: false },
        resultItem: { highlight: { render: true } },
        events: { input: { selection: async (event) => {
            const name = event.detail.selection.value;
            window.cityAutoInstance.input.value = name;
            document.querySelector("#barangay").value = "";
            const city = citiesData.find(c => c.name === name);
            if (!city) return;
            barangaysData = await fetchBarangays(city.code);
            document.querySelector("#barangay").disabled = false;
            if (window.barangayAutoInstance) window.barangayAutoInstance.data.src = barangaysData;
        }}}
    });

    window.barangayAutoInstance = new autoComplete({
        selector: "#barangay",
        placeHolder: "Search Barangay",
        data: { src: barangaysData, cache: false },
        resultItem: { highlight: { render: true } },
        events: { input: { selection(event) {
            window.barangayAutoInstance.input.value = event.detail.selection.value;
        }}}
    });
})();

document.addEventListener("DOMContentLoaded", function() {
    const religions = [
        "Roman Catholic", "Iglesia ni Cristo (INC)", "Evangelical / Born Again Christian",
        "Aglipayan (Philippine Independent Church)", "Seventh-day Adventist", "Jehovah's Witnesses",
        "Baptist", "Methodist", "Pentecostal",
        "Church of Jesus Christ of Latter-day Saints (Mormon)", "Islam (Sunni Muslim)",
        "Islam (Shia Muslim)", "Buddhism", "Hinduism", "Judaism", "Sikhism", "Baháʼí Faith",
        "Unitarian Universalist", "Anito / Indigenous Philippine Folk Religion",
        "Lumad Traditional Beliefs", "Ifugao / Cordilleran Indigenous Religion",
        "Atheist", "Agnostic", "No Religion / None"
    ];
    const autoCompleteJS = new autoComplete({
        selector: "#religion",
        placeHolder: "Search Religion...",
        data: { src: religions, cache: true },
        resultItem: { highlight: true },
        events: { input: { selection(event) {
            autoCompleteJS.input.value = event.detail.selection.value;
        }}}
    });
});
</script>
@endsection
