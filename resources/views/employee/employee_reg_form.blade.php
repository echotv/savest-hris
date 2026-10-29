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
    pointer-events: none; /* container is invisible — only buttons receive clicks */
}
.section-nav-btn {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    background: #fff;
    border: 1px solid #dee2e6;
    pointer-events: auto; /* re-enable on actual buttons */
    border-radius: 20px;
    padding: 0.3rem 0.65rem 0.3rem 0.5rem;
    font-size: 0.72rem;
    font-weight: 600;
    color: #555;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: background 0.15s, color 0.15s, border-color 0.15s, box-shadow 0.15s;
    white-space: nowrap;
    max-width: 2rem;
    overflow: hidden;
    transition: max-width 0.25s ease, padding 0.25s ease, background 0.15s, color 0.15s;
}
.section-nav:hover .section-nav-btn,
.section-nav-btn:hover,
.section-nav-btn.active {
    max-width: 220px;
}
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
.section-nav-btn.active .nav-num {
    background: rgba(255,255,255,0.25);
}
.section-nav-btn .nav-label {
    white-space: nowrap;
    overflow: hidden;
}
body.dark .section-nav-btn {
    background: #1a1a2e;
    border-color: #333;
    color: #bbb;
}
body.dark .section-nav-btn:hover,
body.dark .section-nav-btn.active {
    background: #1b55e2;
    color: #fff;
    border-color: #1b55e2;
}
@media (max-width: 576px) {
    .section-nav { right: 0.5rem; bottom: 1rem; }
    .section-nav-btn .nav-label { display: none; }
    .section-nav-btn { max-width: 2rem !important; padding: 0.3rem 0.4rem !important; }
}
</style>
@endsection

@section("content")
<form action="{{ route('employee.store') }}" id="storeForm" method="post">
@csrf
<div class="row layout-top-spacing">

    {{-- Page Title --}}
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Employee Registration Form</h4>
            <p class="mb-0 text-muted" style="font-size:0.85rem;">Fill in all required fields to register a new employee.</p>
        </div>
        <nav class="breadcrumb-style-one mt-2 mt-md-0" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Employee Registration</li>
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
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Middle Name</label>
                            <input type="text" class="form-control form-control-sm" id="middlename" name="middlename" value="{{ old('middlename') }}">
                        </div>
                        <div class="col-md-1 col-6 mb-2">
                            <label class="form-label form-label-sm">Suffix</label>
                            <select class="form-select form-select-sm" id="suffix" name="suffix">
                                <option value="" {{ old('suffix') == '' ? 'selected' : '' }}></option>
                                <option value="JR." {{ old('suffix') == 'JR.' ? 'selected' : '' }}>JR.</option>
                                <option value="SR." {{ old('suffix') == 'SR.' ? 'selected' : '' }}>SR.</option>
                                <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                                <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                                <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>IV</option>
                                <option value="V" {{ old('suffix') == 'V' ? 'selected' : '' }}>V</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6 mb-2">
                            <label class="form-label form-label-sm">Gender <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="gender" name="gender">
                                <option value="Female" {{ old('gender', 'Female') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Date of Birth <span class="text-danger">*</span></label>
                            <input id="date_of_birth" name="date_of_birth" class="form-control form-control-sm flatpickr flatpickr-input active" type="text" value="{{ old('date_of_birth') }}" placeholder="Select date…" required>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Place of Birth <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}" required>
                        </div>
                        <div class="col-md-4 col-6 mb-2">
                            <label class="form-label form-label-sm">Civil Status <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="civil_status" name="civil_status">
                                <option value="Single" {{ old('civil_status', 'Single') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                <option value="Divorced" {{ old('civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6 mb-2">
                            <label class="form-label form-label-sm">Religion <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="religion" name="religion" value="{{ old('religion') }}" required>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Citizenship <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="citizenship" name="citizenship" value="{{ old('citizenship') }}" required>
                        </div>
                    </div>
                </div>

                {{-- Physical --}}
                <div class="sub-section">
                    <span class="sub-section-label">Physical Information</span>
                    <div class="row">
                        <div class="col-md-2 col-6 mb-2">
                            <label class="form-label form-label-sm">Height (cm)</label>
                            <input type="number" step="0.01" min="50" max="250" class="form-control form-control-sm" id="height" name="height" value="{{ old('height') }}">
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <label class="form-label form-label-sm">Weight (kg)</label>
                            <input type="number" step="0.01" min="20" max="400" class="form-control form-control-sm" id="weight" name="weight" value="{{ old('weight') }}">
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="sub-section">
                    <span class="sub-section-label">Contact Information</span>
                    <div class="row">
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm" id="personal_email" name="personal_email" value="{{ old('personal_email') }}" placeholder="email@example.com">
                        </div>
                        <div class="col-md-4 col-6 mb-2">
                            <label class="form-label form-label-sm">Telephone No.</label>
                            <input type="text" class="form-control form-control-sm" id="telephone_no" name="telephone_no" value="{{ old('telephone_no') }}">
                        </div>
                        <div class="col-md-4 col-6 mb-2">
                            <label class="form-label form-label-sm">Cellphone No.</label>
                            <input type="text" class="form-control form-control-sm" id="cellphone_no" name="cellphone_no" value="{{ old('cellphone_no') }}">
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div class="sub-section">
                    <span class="sub-section-label">Emergency Contact</span>
                    <div class="row">
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="eci_name" name="eci_name" value="{{ old('eci_name') }}" required>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Contact No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="eci_cnumber" name="eci_cnumber" value="{{ old('eci_cnumber') }}" required>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="form-label form-label-sm">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="eci_address" name="eci_address" value="{{ old('eci_address') }}" required>
                        </div>
                    </div>
                </div>

                {{-- Government IDs --}}
                <div class="sub-section">
                    <span class="sub-section-label">Government IDs</span>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">SSS No.</label>
                            <input type="text" class="form-control form-control-sm" id="sss" name="sss" value="{{ old('sss') }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">TIN No.</label>
                            <input type="text" class="form-control form-control-sm" id="tin" name="tin" value="{{ old('tin') }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">PhilHealth No.</label>
                            <input type="text" class="form-control form-control-sm" id="philhealth" name="philhealth" value="{{ old('philhealth') }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">PAGIBIG No.</label>
                            <input type="text" class="form-control form-control-sm" id="pagibig" name="pagibig" value="{{ old('pagibig') }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">National ID</label>
                            <input type="text" class="form-control form-control-sm" id="national_id" name="national_id" value="{{ old('national_id') }}">
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <label class="form-label form-label-sm">Driver's License No.</label>
                            <input type="text" class="form-control form-control-sm" id="drivers_license_no" name="drivers_license_no" value="{{ old('drivers_license_no') }}">
                        </div>
                    </div>
                </div>

                {{-- Home Address --}}
                <div class="sub-section">
                    <span class="sub-section-label">Home Address</span>
                    <div class="row">
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Province <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="province" name="province" value="{{ old('province') }}" required>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">City / Municipality <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="city" name="city" value="{{ old('city') }}" required {{ old('province') ? '' : 'disabled' }}>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Barangay <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="barangay" name="barangay" value="{{ old('barangay') }}" required {{ old('city') ? '' : 'disabled' }}>
                        </div>
                        <div class="col-md-3 col-12 mb-2">
                            <label class="form-label form-label-sm">Street / House No.</label>
                            <input type="text" class="form-control form-control-sm" id="street" name="street" value="{{ old('street') }}">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECTION 2 — FAMILY BACKGROUND
    ═══════════════════════════════════════════ --}}
    <div class="col-12 mb-3" id="sec-family">
        <div class="card section-card">
            <div class="card-header">
                <span class="section-badge">2</span>
                <h5 class="fw-bold">Family Background</h5>
            </div>
            <div class="card-body pt-3">
                {{-- Rendered server-side too, so a validation error doesn't wipe the fields
                     that only exist because civil_status was "Married" --}}
                <div id="spouse_info">
                @if (old('civil_status') === 'Married')
                    <div class="sub-section mb-2">
                        <span class="sub-section-label">Spouse Information</span>
                        <div class="row">
                            <div class="col-md-4 col-12 mb-2">
                                <label class="form-label form-label-sm">Spouse's Name</label>
                                <input type="text" class="form-control form-control-sm" id="spouse_name" name="spouse_name" value="{{ old('spouse_name') }}" required>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                                <label class="form-label form-label-sm">Spouse's Occupation</label>
                                <input type="text" class="form-control form-control-sm" id="spouse_occupation" name="spouse_occupation" value="{{ old('spouse_occupation') }}" required>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                                <label class="form-label form-label-sm">Spouse's Employer</label>
                                <input type="text" class="form-control form-control-sm" id="spouse_empl_name" name="spouse_empl_name" value="{{ old('spouse_empl_name') }}" required>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
                <div class="row">
                    <div class="col-md-6 col-12 mb-2">
                        <label class="form-label form-label-sm">Father's Name</label>
                        <input type="text" class="form-control form-control-sm" id="father_name" name="father_name" value="{{ old('father_name') }}">
                    </div>
                    <div class="col-md-6 col-12 mb-2">
                        <label class="form-label form-label-sm">Mother's Name</label>
                        <input type="text" class="form-control form-control-sm" id="mother_name" name="mother_name" value="{{ old('mother_name') }}">
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label form-label-sm">Children <span class="text-muted small">(separate names with a comma)</span></label>
                        <textarea class="form-control form-control-sm" id="childrens" name="childrens" rows="2">{{ old('childrens') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECTION 3 — EMPLOYMENT DETAILS
    ═══════════════════════════════════════════ --}}
    <div class="col-12 mb-3" id="sec-employment">
        <div class="card section-card">
            <div class="card-header">
                <span class="section-badge">3</span>
                <h5 class="fw-bold">Employment Details</h5>
            </div>
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-md-4 col-12 mb-2">
                        <label class="form-label form-label-sm">Employee ID</label>
                        <input type="text" class="form-control form-control-sm" id="employee_number" name="employee_number" value="{{ old('employee_number') }}">
                        <div class="form-text text-warning" style="font-size:0.75rem;">Leave blank to auto-generate.</div>
                    </div>
                    <div class="col-md-4 col-12 mb-2">
                        <label class="form-label form-label-sm">Date Employed <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="date_employed" name="date_employed" value="{{ old('date_employed') }}" required>
                    </div>

                    <div class="col-md-4 col-12 mb-2">
                        <label class="form-label form-label-sm">Account Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="account_status" name="account_status">
                            <option value="1" {{ old('account_status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('account_status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <small class="text-muted d-none" id="account_status_hint">Inactive because the selected employment status is not a current employee.</small>
                    </div>

                    <div class="col-md-4 col-12 mb-2">
                        <label class="form-label form-label-sm">Branch <span class="text-danger">*</span></label>
                        <select id="branch_code" name="branch_code" autocomplete="off">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->branch_code }}" {{ old('branch_code') == $branch->branch_code ? 'selected' : '' }}>{{ $branch->branch_code }} {{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-12 mb-2">
                        <label class="form-label form-label-sm">Position <span class="text-danger">*</span></label>
                        <select id="position_id" name="position_id" autocomplete="off">
                            @foreach ($positions_list as $position)
                                <option value="{{ $position->position_id }}" {{ (string) old('position_id') === (string) $position->position_id ? 'selected' : '' }}>{{ $position->position_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-12 mb-2">
                        <label class="form-label form-label-sm">Employment Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="es_id" name="es_id">
                            @foreach ($employment_statuses as $status)
                                <option value="{{ $status->es_id }}" data-current="{{ (int) $status->is_current_employee }}" {{ (string) old('es_id') === (string) $status->es_id ? 'selected' : '' }}>{{ $status->empl_status }}</option>
                            @endforeach
                        </select>
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
                        <div class="col-md-4"><span class="col-label">Name of School</span></div>
                        <div class="col-md-4"><span class="col-label">Course / Major</span></div>
                        <div class="col-md-3"><span class="col-label">Year Graduated</span></div>
                    </div>
                    <div class="college_list">
                        {{-- Rebuild rows from old() input on validation failure; default to one empty row otherwise --}}
                        @php $oldSchools = old('name_of_school', []); @endphp
                        @foreach ($oldSchools as $i => $schoolVal)
                        <div class="college-item repeatable-item">
                            <button type="button" class="btn-remove-row delete-btn" title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <div class="row">
                                <div class="col-md-4 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Name of School</label>
                                    <input type="text" class="form-control form-control-sm" name="name_of_school[]" value="{{ $schoolVal }}">
                                </div>
                                <div class="col-md-4 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Course / Major</label>
                                    <input type="text" class="form-control form-control-sm" name="course[]" value="{{ old('course.'.$i) }}">
                                </div>
                                <div class="col-md-3 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Year Graduated</label>
                                    <input type="number" class="form-control form-control-sm year_graduated" name="year_graduated[]" value="{{ old('year_graduated.'.$i) }}" min="1920" max="2025">
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
                        @foreach (old('gs_name_of_school', []) as $i => $gsVal)
                        <div class="grad_stud-item repeatable-item">
                            <button type="button" class="btn-remove-row delete-gs-btn" title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <div class="row">
                                <div class="col-md-3 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Name of School</label>
                                    <input type="text" class="form-control form-control-sm" name="gs_name_of_school[]" value="{{ $gsVal }}">
                                </div>
                                <div class="col-md-3 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">School Address</label>
                                    <input type="text" class="form-control form-control-sm" name="gs_sch_address[]" value="{{ old('gs_sch_address.'.$i) }}">
                                </div>
                                <div class="col-md-3 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Course / Major</label>
                                    <input type="text" class="form-control form-control-sm" name="gs_course[]" value="{{ old('gs_course.'.$i) }}">
                                </div>
                                <div class="col-md-2 col-12 mb-2">
                                    <label class="form-label form-label-sm d-md-none">Year Graduated</label>
                                    <input type="number" class="form-control form-control-sm year_graduated" name="gs_year_graduated[]" value="{{ old('gs_year_graduated.'.$i) }}" min="1920" max="2050">
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
                    @php $oldWeCompanies = old('we_company_name', []); @endphp
                    @foreach ($oldWeCompanies as $i => $companyVal)
                    <div class="workex-item repeatable-item">
                        <button type="button" class="btn-remove-row delete-workex-btn" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="we_company_name[]" value="{{ $companyVal }}" required>
                            </div>
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="we_position[]" value="{{ old('we_position.'.$i) }}" required>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                                <label class="form-label form-label-sm">Salary (PHP) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" name="we_salary[]" value="{{ old('we_salary.'.$i) }}" min="1" required>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <label class="form-label form-label-sm">Date Started <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm we_date_started" name="we_date_started[]" value="{{ old('we_date_started.'.$i) }}" required>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <label class="form-label form-label-sm">Date Ended <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm we_date_ended" name="we_date_ended[]" value="{{ old('we_date_ended.'.$i) }}" required>
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
                    @php $oldCrNames = old('cr_name', []); @endphp
                    @foreach ($oldCrNames as $i => $crNameVal)
                    <div class="cref-item repeatable-item">
                        <button type="button" class="btn-remove-row delete-cref-btn" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cr_name[]" value="{{ $crNameVal }}" required>
                            </div>
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cr_company[]" value="{{ old('cr_company.'.$i) }}" required>
                            </div>
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cr_position[]" value="{{ old('cr_position.'.$i) }}" required>
                            </div>
                            <div class="col-md-6 col-12 mb-2">
                                <label class="form-label form-label-sm">Contact No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="cr_cnum[]" value="{{ old('cr_cnum.'.$i) }}" required>
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
        <button type="reset" class="btn btn-dark">Clear Form</button>
        <button type="submit" class="btn btn-info px-4">Submit Registration</button>
    </div>

</div>
</form>

{{-- Sticky Section Navigator — outside form so it never blocks inputs --}}
<nav class="section-nav" aria-label="Jump to section">
    <a href="#sec-personal"   class="section-nav-btn"><span class="nav-num">1</span><span class="nav-label">Personal Data</span></a>
    <a href="#sec-family"     class="section-nav-btn"><span class="nav-num">2</span><span class="nav-label">Family Background</span></a>
    <a href="#sec-employment" class="section-nav-btn"><span class="nav-num">3</span><span class="nav-label">Employment Details</span></a>
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
function getPhilippinesDate() {
    let now = new Date();
    let utc = now.getTime() + now.getTimezoneOffset() * 60000;
    return new Date(utc + (3600000 * 8));
}
function getPhilippinesDate23YearsAgo() {
    let d = getPhilippinesDate();
    d.setFullYear(d.getFullYear() - 23);
    return d;
}

// ✅ Only fall back to the computed default date when the field wasn't
// already repopulated from old() input after a validation error.
var dobField = document.getElementById('date_of_birth');
flatpickr(dobField, { defaultDate: dobField.value ? dobField.value : getPhilippinesDate23YearsAgo() });

var dateEmployedField = document.getElementById('date_employed');
flatpickr(dateEmployedField, { defaultDate: dateEmployedField.value ? dateEmployedField.value : getPhilippinesDate() });

/* ── Jump to the actual error instead of just landing at the top ─── */
(function () {
    const errorFields = @json($errors->keys());
    if (!errorFields.length) return;

    function resolveField(key) {
        // Repeatable rows come back as "name_of_school.0", "we_salary.2", etc.
        // Everything else is a plain field name.
        const dotIndex = key.indexOf('.');
        if (dotIndex === -1) {
            return document.querySelector(`[name="${key}"]`);
        }
        const base = key.slice(0, dotIndex);
        const rowIndex = parseInt(key.slice(dotIndex + 1), 10);
        const matches = document.querySelectorAll(`[name="${base}[]"]`);
        return matches[rowIndex] || null;
    }

    let firstTarget = null;
    errorFields.forEach(function (key) {
        const field = resolveField(key);
        if (field) {
            field.classList.add('is-invalid');
            if (!firstTarget) firstTarget = field;
        }
    });

    // Wait a tick so flatpickr / TomSelect / repeatable rows finish mounting
    // before we measure scroll position.
    setTimeout(function () {
        if (firstTarget) {
            firstTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstTarget.focus({ preventScroll: true });
        } else {
            // Errors that aren't tied to a specific input (e.g. a caught
            // exception message) — bring the alert box into view instead
            // of leaving the page sitting at the very top unexplained.
            const alertBox = document.querySelector('.alert-danger');
            if (alertBox) alertBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }, 150);
})();

function initWorkExpPickers() {
    document.querySelectorAll('.we_date_started').forEach(function(el) {
        if (!el.classList.contains('flatpickr-input')) {
            flatpickr(el, { dateFormat: "Y-m-d", defaultDate: el.value ? el.value : new Date() });
        }
    });
    document.querySelectorAll('.we_date_ended').forEach(function(el) {
        if (!el.classList.contains('flatpickr-input')) {
            flatpickr(el, { dateFormat: "Y-m-d", defaultDate: el.value ? el.value : new Date() });
        }
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
                    <label class="form-label form-label-sm d-md-none">Name of School</label>
                    <input type="text" class="form-control form-control-sm" name="name_of_school[]">
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Course / Major</label>
                    <input type="text" class="form-control form-control-sm" name="course[]">
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Year Graduated</label>
                    <input type="number" class="form-control form-control-sm year_graduated" name="year_graduated[]" min="1920" max="2025">
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
                    <input type="text" class="form-control form-control-sm" name="gs_name_of_school[]">
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">School Address</label>
                    <input type="text" class="form-control form-control-sm" name="gs_sch_address[]">
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Course / Major</label>
                    <input type="text" class="form-control form-control-sm" name="gs_course[]">
                </div>
                <div class="col-md-2 col-12 mb-2">
                    <label class="form-label form-label-sm d-md-none">Year Graduated</label>
                    <input type="number" class="form-control form-control-sm year_graduated" name="gs_year_graduated[]" min="1920" max="2050">
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

/* ── Spouse fields on Married ─────────────────────── */
$(document).ready(function () {
    var spouseFields = `
        <div class="sub-section mb-2">
            <span class="sub-section-label">Spouse Information</span>
            <div class="row">
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm">Spouse's Name</label>
                    <input type="text" class="form-control form-control-sm" id="spouse_name" name="spouse_name" required>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm">Spouse's Occupation</label>
                    <input type="text" class="form-control form-control-sm" id="spouse_occupation" name="spouse_occupation" required>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <label class="form-label form-label-sm">Spouse's Employer</label>
                    <input type="text" class="form-control form-control-sm" id="spouse_empl_name" name="spouse_empl_name" required>
                </div>
            </div>
        </div>`;
    // ✅ On real user interaction only — the initial "Married" render (after a
    // validation error) is already handled server-side in the blade above,
    // so we don't want this handler to stomp on those old() values on load.
    $("#civil_status").change(function () {
        if ($(this).val() === "Married") {
            if ($("#spouse_info").children().length === 0) {
                $("#spouse_info").html(spouseFields);
            }
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


document.addEventListener('DOMContentLoaded', function () {
    flatpickr(".timepicker", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true });
    new TomSelect('#position_id', {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "Select or type a position"
    });
    new TomSelect('#branch_code', {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "Select or type a branch"
    });
});

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

    const provinceAuto = new autoComplete({
        selector: "#province",
        placeHolder: "Search Province",
        data: { src: provinces.map(p => p.name), cache: true },
        resultItem: { highlight: { render: true } },
        events: { input: { selection: async (event) => {
            const selectedProvinceName = event.detail.selection.value;
            const selectedProvince = provinces.find(p => p.name === selectedProvinceName);
            provinceAuto.input.value = selectedProvinceName;
            document.querySelector("#city").value = "";
            document.querySelector("#barangay").value = "";
            document.querySelector("#barangay").disabled = true;
            citiesData = await fetchCities(selectedProvince.code);
            document.querySelector("#city").disabled = false;
            if (window.cityAutoInstance) window.cityAutoInstance.data.src = citiesData.map(c => c.name);
        }}}
    });

    window.cityAutoInstance = new autoComplete({
        selector: "#city",
        placeHolder: "Search City/Municipality",
        data: { src: [], cache: false },
        resultItem: { highlight: { render: true } },
        events: { input: { selection: async (event) => {
            const selectedCityName = event.detail.selection.value;
            window.cityAutoInstance.input.value = selectedCityName;
            document.querySelector("#barangay").value = "";
            const selectedCity = citiesData.find(c => c.name === selectedCityName);
            if (!selectedCity) return;
            const barangays = await fetchBarangays(selectedCity.code);
            document.querySelector("#barangay").disabled = false;
            if (window.barangayAutoInstance) window.barangayAutoInstance.data.src = barangays;
        }}}
    });

    window.barangayAutoInstance = new autoComplete({
        selector: "#barangay",
        placeHolder: "Search Barangay",
        data: { src: [], cache: false },
        resultItem: { highlight: { render: true } },
        events: { input: { selection(event) {
            window.barangayAutoInstance.input.value = event.detail.selection.value;
        }}}
    });

    // ✅ Rehydrate the province → city → barangay chain after a validation
    // error, so the autocomplete stays functional for the old() values that
    // are already sitting in the inputs.
    const oldProvinceName = document.querySelector("#province").value;
    if (oldProvinceName) {
        const matchedProvince = provinces.find(p => p.name === oldProvinceName);
        if (matchedProvince) {
            citiesData = await fetchCities(matchedProvince.code);
            window.cityAutoInstance.data.src = citiesData.map(c => c.name);

            const oldCityName = document.querySelector("#city").value;
            if (oldCityName) {
                const matchedCity = citiesData.find(c => c.name === oldCityName);
                if (matchedCity) {
                    const barangays = await fetchBarangays(matchedCity.code);
                    window.barangayAutoInstance.data.src = barangays;
                }
            }
        }
    }
})();

/* ── Active section highlight on scroll ───────────────── */
(function () {
    const navLinks = document.querySelectorAll('.section-nav-btn');
    const sections = ['sec-personal','sec-family','sec-employment','sec-education','sec-workex','sec-references'];
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

// Non-current employment status (e.g. Resigned) forces the account to Inactive
document.addEventListener("DOMContentLoaded", function() {
    const esSelect      = document.getElementById('es_id');
    const accountSelect = document.getElementById('account_status');
    const activeOption  = accountSelect.querySelector('option[value="1"]');
    const hint          = document.getElementById('account_status_hint');

    function syncAccountStatus() {
        const selected  = esSelect.options[esSelect.selectedIndex];
        const isCurrent = !selected || selected.dataset.current !== '0';
        if (!isCurrent) accountSelect.value = '0';
        activeOption.disabled = !isCurrent;
        hint.classList.toggle('d-none', isCurrent);
    }

    esSelect.addEventListener('change', syncAccountStatus);
    syncAccountStatus();
});
</script>
@endsection
