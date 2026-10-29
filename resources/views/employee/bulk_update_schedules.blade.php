@extends("../layout")
@section("additional_head")
<link href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

<style>
/* =========================================
   CUSTOM MULTI-SELECT (unchanged)
   ========================================= */
.custom-multiselect {
    position: relative;
    width: 100%;
    font-size: 0.875rem;
}
.cms-control {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 4px;
    min-height: calc(1.5em + 0.75rem + 2px);
    padding: 4px 8px;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 0.375rem;
    background: var(--bs-body-bg, #fff);
    cursor: text;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.custom-multiselect.is-open .cms-control,
.cms-control:focus-within {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
.cms-tags-wrap {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 4px;
    flex: 1;
    min-width: 0;
}
.cms-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    background: #0d6efd;
    color: #fff;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 500;
    line-height: 1.6;
    white-space: nowrap;
    max-width: 280px;
    flex-shrink: 0;
}
.cms-tag-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.cms-tag-remove {
    background: none;
    border: none;
    color: rgba(255,255,255,0.75);
    cursor: pointer;
    padding: 0 0 0 2px;
    line-height: 1;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    transition: color 0.1s;
}
.cms-tag-remove:hover { color: #fff; }
.cms-input {
    flex: 1 1 80px;
    min-width: 80px;
    border: none;
    outline: none;
    background: transparent;
    font-size: 0.875rem;
    padding: 2px 2px;
    color: inherit;
    line-height: 1.6;
}
.cms-input::placeholder { color: #adb5bd; }
.cms-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: var(--bs-body-bg, #fff);
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 0.375rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    z-index: 1055;
    max-height: 260px;
    overflow: hidden;
    flex-direction: column;
}
.custom-multiselect.is-open .cms-dropdown { display: flex; }
.cms-dropdown-search {
    padding: 8px 10px;
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
}
.cms-dropdown-search input {
    width: 100%;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 4px 8px;
    font-size: 0.8rem;
    outline: none;
    background: var(--bs-body-bg, #fff);
    color: inherit;
}
.cms-dropdown-search input:focus { border-color: #86b7fe; }
.cms-toolbar {
    display: flex;
    gap: 8px;
    padding: 4px 10px;
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
    background: var(--bs-secondary-bg, #f8f9fa);
}
.cms-toolbar button {
    background: none;
    border: none;
    font-size: 0.75rem;
    color: #0d6efd;
    cursor: pointer;
    padding: 2px 4px;
    border-radius: 3px;
}
.cms-toolbar button:hover { background: rgba(13, 110, 253, 0.1); }
.cms-toolbar .cms-count {
    margin-left: auto;
    font-size: 0.75rem;
    color: #6c757d;
    line-height: 1.8;
}
.cms-options { overflow-y: auto; flex: 1; }
.cms-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    cursor: pointer;
    font-size: 0.84rem;
    transition: background 0.1s;
    border-bottom: 1px solid rgba(0,0,0,0.04);
}
.cms-option:last-child { border-bottom: none; }
.cms-option:hover { background: rgba(13, 110, 253, 0.07); }
.cms-option.is-selected { background: rgba(13, 110, 253, 0.1); }
.cms-option.is-hidden { display: none; }
.cms-checkbox {
    width: 15px;
    height: 15px;
    border: 2px solid #adb5bd;
    border-radius: 3px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.1s, border-color 0.1s;
}
.cms-option.is-selected .cms-checkbox { background: #0d6efd; border-color: #0d6efd; }
.cms-checkbox svg { display: none; width: 10px; height: 10px; }
.cms-option.is-selected .cms-checkbox svg { display: block; }
.cms-option-label { flex: 1; line-height: 1.3; }
.cms-option-sub   { font-size: 0.75rem; color: #6c757d; }
.cms-no-results {
    padding: 12px;
    text-align: center;
    color: #6c757d;
    font-size: 0.84rem;
    display: none;
}

/* ── Timepicker tweaks ──────────────────────────────────────── */
.form-control-sm.timepicker {
    height: calc(1.5em + 0.5rem + 7px);
    font-size: 0.875rem;
}

/* ── Section labels ─────────────────────────────────────────── */
.section-lbl {
    display: inline-flex; align-items: center; gap: 0.45rem;
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; color: #888;
    padding-left: 0.5rem; border-left: 3px solid #1b55e2;
    margin-bottom: 0.85rem;
}

/* ── Date card ─────────────────────────────────────────────── */
.date-card {
    border-radius: 10px;
    border: 1px solid var(--bs-border-color, #e0e6ed);
    padding: 1.1rem 1.25rem 0.85rem;
    background: var(--bs-body-bg, #fff);
    margin-bottom: 1.25rem;
}
.date-card-title {
    font-size: 0.66rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.09em; color: #1b55e2; margin-bottom: 0.85rem;
    display: flex; align-items: center; gap: 0.4rem;
}
.field-label {
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.07em; color: #777; margin-bottom: 0.3rem; display: block;
}

/* ── Schedule card header ──────────────────────────────────── */
.sched-section-hdr {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color, #e0e6ed);
}
.sched-section-title {
    display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; font-weight: 700;
}
.sched-summary-pill { display: flex; gap: 0.5rem; align-items: center; }
.sched-summary-pill .pill {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.22rem 0.65rem; border-radius: 20px; font-weight: 600; font-size: 0.7rem;
}
.pill-work { background: rgba(0,171,85,0.1); color: #00ab55; }
.pill-off  { background: rgba(231,81,90,0.1); color: #e7515a; }
body.dark .pill-work { background: rgba(0,171,85,0.18); }
body.dark .pill-off  { background: rgba(231,81,90,0.18); }

/* ── Column header bar ─────────────────────────────────────── */
.col-header-bar {
    display: flex; gap: 0.75rem 1.25rem; align-items: center;
    padding: 0.5rem 1.1rem;
    background: rgba(27,85,226,0.05);
    border-bottom: 1px solid rgba(27,85,226,0.1);
}
.col-header-bar .ch-day   { min-width: 72px; flex-shrink: 0; }
.col-header-bar .ch-times { flex: 1; display: flex; gap: 0.75rem 1.75rem; flex-wrap: wrap; }
.col-header-bar .ch-tg    { flex: 1; min-width: 195px; }
.col-header-bar .ch-tog   { min-width: 64px; flex-shrink: 0; text-align: center; }
.ch-label {
    font-size: 0.58rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; display: inline-flex; align-items: center; gap: 0.3rem;
}
body.dark .col-header-bar { background: rgba(27,85,226,0.11); border-color: rgba(27,85,226,0.2); }

/* ── Schedule rows ─────────────────────────────────────────── */
.sched-row {
    display: flex; align-items: flex-start; gap: 0.75rem 1.25rem;
    padding: 0.9rem 1.1rem 0.75rem;
    border-bottom: 1px solid var(--bs-border-color, #f0f3f8);
    border-left: 4px solid transparent;
    background: var(--bs-body-bg, #fff);
    transition: opacity 0.25s, background 0.2s;
    flex-wrap: wrap; position: relative;
}
.sched-row:last-of-type { border-bottom: none; }
body.dark .sched-row { border-color: #2d2d2d; }

.sched-row[data-day="Monday"]    { border-left-color: #00ab55; background: rgba(0,171,85,0.03); }
.sched-row[data-day="Tuesday"]   { border-left-color: #1b55e2; background: rgba(27,85,226,0.03); }
.sched-row[data-day="Wednesday"] { border-left-color: #00b5b5; background: rgba(0,181,181,0.03); }
.sched-row[data-day="Thursday"]  { border-left-color: #888ea8; background: rgba(136,142,168,0.04); }
.sched-row[data-day="Friday"]    { border-left-color: #e2a03f; background: rgba(226,160,63,0.04); }
.sched-row[data-day="Saturday"],
.sched-row[data-day="Sunday"]    { border-left-color: #e7515a; background: rgba(231,81,90,0.03); }

.sched-row[data-day="Monday"]:hover    { background: rgba(0,171,85,0.07); }
.sched-row[data-day="Tuesday"]:hover   { background: rgba(27,85,226,0.07); }
.sched-row[data-day="Wednesday"]:hover { background: rgba(0,181,181,0.07); }
.sched-row[data-day="Thursday"]:hover  { background: rgba(136,142,168,0.08); }
.sched-row[data-day="Friday"]:hover    { background: rgba(226,160,63,0.08); }
.sched-row[data-day="Saturday"]:hover,
.sched-row[data-day="Sunday"]:hover    { background: rgba(231,81,90,0.07); }

body.dark .sched-row[data-day="Monday"]    { background: rgba(0,171,85,0.07); }
body.dark .sched-row[data-day="Tuesday"]   { background: rgba(27,85,226,0.07); }
body.dark .sched-row[data-day="Wednesday"] { background: rgba(0,181,181,0.07); }
body.dark .sched-row[data-day="Thursday"]  { background: rgba(136,142,168,0.08); }
body.dark .sched-row[data-day="Friday"]    { background: rgba(226,160,63,0.08); }
body.dark .sched-row[data-day="Saturday"],
body.dark .sched-row[data-day="Sunday"]    { background: rgba(231,81,90,0.07); }
body.dark .sched-row[data-day="Monday"]:hover    { background: rgba(0,171,85,0.13); }
body.dark .sched-row[data-day="Tuesday"]:hover   { background: rgba(27,85,226,0.13); }
body.dark .sched-row[data-day="Wednesday"]:hover { background: rgba(0,181,181,0.13); }
body.dark .sched-row[data-day="Thursday"]:hover  { background: rgba(136,142,168,0.14); }
body.dark .sched-row[data-day="Friday"]:hover    { background: rgba(226,160,63,0.14); }
body.dark .sched-row[data-day="Saturday"]:hover,
body.dark .sched-row[data-day="Sunday"]:hover    { background: rgba(231,81,90,0.13); }

.sched-row.is-day-off { opacity: 0.45; }

.dayoff-tag {
    display: none; position: absolute; right: 4.5rem; top: 50%;
    transform: translateY(-50%);
    font-size: 0.67rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
    color: #e7515a; background: rgba(231,81,90,0.1); border-radius: 20px;
    padding: 0.2rem 0.7rem; pointer-events: none;
}
.sched-row.is-day-off .dayoff-tag { display: inline-flex; }

/* ── Day circle avatar ─────────────────────────────────────── */
.day-col {
    min-width: 72px; flex-shrink: 0;
    display: flex; flex-direction: column; align-items: center;
    padding-top: 0.1rem; gap: 0.22rem;
}
.day-circle {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; font-weight: 800; letter-spacing: -0.03em;
}
.day-circle[data-day="Monday"]    { background: #e6f9f1; color: #00ab55; }
.day-circle[data-day="Tuesday"]   { background: #eaeffc; color: #1b55e2; }
.day-circle[data-day="Wednesday"] { background: #e0f7f7; color: #00a0a0; }
.day-circle[data-day="Thursday"]  { background: #f0f0f4; color: #6b7197; }
.day-circle[data-day="Friday"]    { background: #fdf5e6; color: #c07a10; }
.day-circle[data-day="Saturday"],
.day-circle[data-day="Sunday"]    { background: #fdecea; color: #e7515a; }
body.dark .day-circle[data-day="Monday"]    { background: rgba(0,171,85,0.18); }
body.dark .day-circle[data-day="Tuesday"]   { background: rgba(27,85,226,0.2); }
body.dark .day-circle[data-day="Wednesday"] { background: rgba(0,181,181,0.18); }
body.dark .day-circle[data-day="Thursday"]  { background: rgba(136,142,168,0.2); }
body.dark .day-circle[data-day="Friday"]    { background: rgba(226,160,63,0.18); }
body.dark .day-circle[data-day="Saturday"],
body.dark .day-circle[data-day="Sunday"]    { background: rgba(231,81,90,0.18); }
.day-abr { font-size: 0.59rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #aaa; }

/* ── Time field groups ─────────────────────────────────────── */
.sched-fields { flex: 1; display: flex; gap: 0.75rem 1.75rem; flex-wrap: wrap; }
.field-grp { flex: 1; min-width: 195px; }
.field-grp-lbl {
    font-size: 0.62rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; margin-bottom: 0.35rem;
    display: inline-flex; align-items: center; gap: 0.3rem;
}
.lbl-work  { color: #1b55e2; }
.lbl-break { color: #c07a10; }
.time-pair { display: flex; align-items: center; gap: 0.35rem; }
.time-pair .form-group { flex: 1; margin-bottom: 0; }
.time-pair .form-control { font-size: 0.82rem; }
.time-sep { color: #bbb; font-size: 0.82rem; flex-shrink: 0; font-weight: 600; }

/* ── Day-off toggle ────────────────────────────────────────── */
.toggle-col {
    flex-shrink: 0; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    min-width: 64px; padding-top: 0.15rem; gap: 0.32rem;
}
.toggle-lbl { font-size: 0.59rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #888; }
.form-check.form-switch .form-check-input { width: 2.2rem; height: 1.15rem; cursor: pointer; }
.form-check.form-switch .form-check-input:checked { background-color: #e7515a; border-color: #e7515a; }

/* ── Actions bar ───────────────────────────────────────────── */
.actions-bar {
    display: flex; justify-content: flex-end; align-items: center; gap: 0.75rem;
    padding: 0.9rem 1.25rem;
    border-top: 1px solid var(--bs-border-color, #e0e6ed);
}
</style>
@endsection

@section("content")
<form action="{{ route('ews.bulk_update') }}" method="POST">
@csrf
<div class="row layout-top-spacing">

    {{-- ── Page header ─────────────────────────────────────── --}}
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div class="d-flex align-items-center gap-3">
            <div style="width:46px;height:46px;border-radius:12px;background:rgba(27,85,226,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1b55e2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
                <h4 class="mb-0 fw-bold" style="font-size:1.15rem;">Bulk Scheduling</h4>
                <p class="mb-0 text-muted" style="font-size:0.8rem;">Assign a single weekly schedule to multiple employees at once.</p>
            </div>
        </div>
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Bulk Scheduling</li>
            </ol>
        </nav>
    </div>

    {{-- ── Alerts ──────────────────────────────────────────── --}}
    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
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

    {{-- ── Employees multiselect ───────────────────────────── --}}
    <div class="col-12 mb-3">
        <div class="date-card">
            <div class="date-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Select Employees
            </div>

            <div id="employee-hidden-inputs"></div>

            <div class="custom-multiselect" id="employeeMultiSelect"
                 role="combobox" aria-expanded="false" aria-haspopup="listbox">
                <div class="cms-control" id="cmsControl">
                    <div class="cms-tags-wrap" id="cmsTagsContainer">
                        <input
                            type="text"
                            class="cms-input"
                            id="cmsSearchInline"
                            autocomplete="off"
                            placeholder="Select employees..."
                            aria-label="Search employees"
                        >
                    </div>
                </div>
                <div class="cms-dropdown" id="cmsDropdown" role="listbox" aria-multiselectable="true">
                    <div class="cms-dropdown-search">
                        <input type="text" id="cmsDropdownSearch" placeholder="Search employees..." autocomplete="off">
                    </div>
                    <div class="cms-toolbar">
                        <button type="button" id="cmsSelectAll">Select all</button>
                        <button type="button" id="cmsClearAll">Clear all</button>
                        <span class="cms-count" id="cmsCount">0 selected</span>
                    </div>
                    <div class="cms-options" id="cmsOptions" role="listbox">
                        @foreach($employees as $emp)
                            @php
                                $label = $emp->employee_number . ' - ' .
                                         $emp->lastname . ', ' .
                                         $emp->firstname . ' ' .
                                         $emp->suffix . ' ' .
                                         ($emp->middlename ? strtoupper(substr($emp->middlename,0,1)).'.' : '');
                                $sub   = '(' . $emp->branch_code . '-' . $emp->branch_name . ')';
                            @endphp
                            <div
                                class="cms-option"
                                data-value="{{ $emp->employee_id }}"
                                data-label="{{ $label }}"
                                data-search="{{ strtolower($label . ' ' . $sub) }}"
                                role="option"
                                aria-selected="false"
                            >
                                <span class="cms-checkbox">
                                    <svg viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="cms-option-label">
                                    {{ $label }}
                                    <span class="cms-option-sub d-block">{{ $sub }}</span>
                                </span>
                            </div>
                        @endforeach
                        <div class="cms-no-results" id="cmsNoResults">No employees found.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Date range card ─────────────────────────────────── --}}
    <div class="col-12 mb-3">
        <div class="date-card">
            <div class="date-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Schedule Period
            </div>
            <div class="row g-3">
                <div class="col-md-4 col-sm-6">
                    <label class="field-label">Effective Date <span class="text-danger">*</span></label>
                    <input type="text" id="effectiveDate" name="effective_date"
                        class="form-control form-control-sm"
                        placeholder="Select effective date..." autocomplete="off" required>
                    <small class="text-muted" style="font-size:0.7rem;">When this schedule starts</small>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="field-label">
                        End Date
                        <span class="text-muted fw-normal" style="text-transform:none;letter-spacing:0;">(blank = Present)</span>
                    </label>
                    <input type="text" id="endDate" name="end_date"
                        class="form-control form-control-sm"
                        placeholder="Leave blank for present..." autocomplete="off">
                    <small class="text-muted" style="font-size:0.7rem;">Leave blank if still active</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Schedule builder card ────────────────────────────── --}}
    <div class="col-12 mb-4">
        <div class="card" style="border-radius:10px;overflow:hidden;">

            <div class="sched-section-hdr">
                <div class="sched-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#00ab55" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Weekly Schedule Builder
                    <span class="text-muted fw-normal" style="font-size:0.72rem;">— set hours per day</span>
                </div>
                <div class="sched-summary-pill">
                    <span class="pill pill-work">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        <span id="work-count">0</span>&thinsp;work
                    </span>
                    <span class="pill pill-off">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        <span id="off-count">0</span>&thinsp;off
                    </span>
                </div>
            </div>

            {{-- Column labels (desktop only) --}}
            <div class="col-header-bar d-none d-lg-flex">
                <div class="ch-day"><span class="ch-label" style="color:#888;">Day</span></div>
                <div class="ch-times">
                    <div class="ch-tg">
                        <span class="ch-label" style="color:#1b55e2;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Work Hours (Start &#x2192; End)
                        </span>
                    </div>
                    <div class="ch-tg">
                        <span class="ch-label" style="color:#c07a10;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Break (Start &#x2192; End, optional)
                        </span>
                    </div>
                </div>
                <div class="ch-tog"><span class="ch-label" style="color:#888;">Day Off</span></div>
            </div>

            @php
            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            $day_initial = ['Monday'=>'M','Tuesday'=>'T','Wednesday'=>'W','Thursday'=>'Th','Friday'=>'F','Saturday'=>'Sa','Sunday'=>'Su'];
            @endphp

            @foreach ($days as $index => $day)
            <div class="sched-row" data-day="{{ $day }}">
                <input type="hidden" name="day_of_week[]" value="{{ $day }}">

                <div class="day-col">
                    <div class="day-circle" data-day="{{ $day }}">{{ $day_initial[$day] }}</div>
                    <span class="day-abr">{{ substr($day, 0, 3) }}</span>
                </div>

                <div class="sched-fields">
                    <div class="field-grp">
                        <div class="field-grp-lbl lbl-work">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Work Hours
                        </div>
                        <div class="time-pair">
                            <div class="form-group">
                                <input name="start_time[]" class="form-control form-control-sm timepicker" type="text" value="08:00" placeholder="Start">
                            </div>
                            <span class="time-sep">&#x2192;</span>
                            <div class="form-group">
                                <input name="end_time[]" class="form-control form-control-sm timepicker" type="text" value="17:00" placeholder="End">
                            </div>
                        </div>
                    </div>
                    <div class="field-grp">
                        <div class="field-grp-lbl lbl-break">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Break
                            <span class="text-muted fw-normal" style="font-size:0.58rem;text-transform:none;letter-spacing:0;">(optional)</span>
                        </div>
                        <div class="time-pair">
                            <div class="form-group">
                                <input name="break_start[]" class="form-control form-control-sm timepicker" type="text" value="12:00" placeholder="Start">
                            </div>
                            <span class="time-sep">&#x2192;</span>
                            <div class="form-group">
                                <input name="break_end[]" class="form-control form-control-sm timepicker" type="text" value="13:00" placeholder="End">
                            </div>
                        </div>
                    </div>
                </div>

                <span class="dayoff-tag">Day Off</span>

                <div class="toggle-col">
                    <span class="toggle-lbl">Day Off</span>
                    <div class="form-check form-switch mb-0">
                        <input type="hidden" name="is_day_off[{{ $index }}]" value="0">
                        <input type="checkbox" name="is_day_off[{{ $index }}]" value="1"
                            class="form-check-input checkbox_child">
                    </div>
                </div>
            </div>
            @endforeach

            <div class="actions-bar">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="15 18 9 12 15 6"/></svg>
                    Cancel
                </a>
                <button class="btn btn-primary btn-sm px-4" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Schedule
                </button>
            </div>

        </div>
    </div>

</div>
</form>
@endsection

@section("additional_footer")
<script src="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ================================================
    // FLATPICKR — TIME PICKERS
    // ================================================
    flatpickr(".timepicker", {
        enableTime : true,
        noCalendar : true,
        dateFormat : "H:i",
        time_24hr  : true
    });

    // ================================================
    // FLATPICKR — EFFECTIVE DATE
    // ================================================
    flatpickr("#effectiveDate", {
        dateFormat : "Y-m-d",
        altInput   : true,
        altFormat  : "M d, Y",
        allowInput : false,
        disableMobile: true
    });

    // ================================================
    // FLATPICKR — END DATE
    // ================================================
    flatpickr("#endDate", {
        dateFormat : "Y-m-d",
        altInput   : true,
        altFormat  : "M d, Y",
        allowInput : false,
        disableMobile: true
    });

    // ================================================
    // SUMMARY PILLS
    // ================================================
    function updateSummary() {
        let total = document.querySelectorAll(".sched-row").length;
        let off   = document.querySelectorAll(".sched-row.is-day-off").length;
        document.getElementById("work-count").textContent = total - off;
        document.getElementById("off-count").textContent  = off;
    }
    updateSummary();

    // ================================================
    // CUSTOM MULTI-SELECT
    // ================================================
    const wrapper       = document.getElementById("employeeMultiSelect");
    const control       = document.getElementById("cmsControl");
    const tagsContainer = document.getElementById("cmsTagsContainer");
    const searchInline  = document.getElementById("cmsSearchInline");
    const dropdown      = document.getElementById("cmsDropdown");
    const dropSearch    = document.getElementById("cmsDropdownSearch");
    const optionsList   = document.getElementById("cmsOptions");
    const noResults     = document.getElementById("cmsNoResults");
    const countEl       = document.getElementById("cmsCount");
    const hiddenInputs  = document.getElementById("employee-hidden-inputs");
    const btnSelectAll  = document.getElementById("cmsSelectAll");
    const btnClearAll   = document.getElementById("cmsClearAll");

    const selected = new Map();

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/"/g, "&quot;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }

    let suppressNextOpen = false;

    function openDropdown() {
        if (suppressNextOpen) { suppressNextOpen = false; return; }
        wrapper.classList.add("is-open");
        wrapper.setAttribute("aria-expanded", "true");
        dropSearch.value = "";
        filterOptions("");
    }

    function closeDropdown() {
        wrapper.classList.remove("is-open");
        wrapper.setAttribute("aria-expanded", "false");
        searchInline.value = "";
    }

    function isOpen() {
        return wrapper.classList.contains("is-open");
    }

    control.addEventListener("mousedown", function (e) {
        if (e.target.closest(".cms-tag-remove")) return;
        e.preventDefault();
        if (isOpen()) { closeDropdown(); } else { openDropdown(); searchInline.focus(); }
    });

    document.addEventListener("mousedown", function (e) {
        if (!wrapper.contains(e.target)) closeDropdown();
    });

    dropdown.addEventListener("mousedown", function (e) { e.stopPropagation(); });

    searchInline.addEventListener("input", function () {
        if (!isOpen()) openDropdown();
        filterOptions(this.value.trim().toLowerCase());
    });

    dropSearch.addEventListener("input", function () {
        filterOptions(this.value.trim().toLowerCase());
    });

    function filterOptions(q) {
        let visible = 0;
        optionsList.querySelectorAll(".cms-option").forEach(function (opt) {
            const match = !q || opt.dataset.search.includes(q);
            opt.classList.toggle("is-hidden", !match);
            if (match) visible++;
        });
        noResults.style.display = visible === 0 ? "block" : "none";
    }

    function clearEmployeeValidation() {
        control.classList.remove("border-danger");
        wrapper.querySelectorAll(".employee-error").forEach(function (el) { el.remove(); });
    }

    optionsList.addEventListener("mousedown", function (e) {
        e.stopPropagation();
        const opt = e.target.closest(".cms-option");
        if (!opt || opt.classList.contains("cms-no-results")) return;
        clearEmployeeValidation();
        const value = opt.dataset.value;
        const label = opt.dataset.label.trim();
        if (selected.has(value)) {
            selected.delete(value);
            opt.classList.remove("is-selected");
            opt.setAttribute("aria-selected", "false");
        } else {
            selected.set(value, label);
            opt.classList.add("is-selected");
            opt.setAttribute("aria-selected", "true");
        }
        renderTags(); syncHiddenInputs(); updateCount();
    });

    function renderTags() {
        tagsContainer.querySelectorAll(".cms-tag").forEach(function (t) { t.remove(); });
        searchInline.placeholder = selected.size === 0 ? "Select employees..." : "";
        selected.forEach(function (label, value) {
            const tag = document.createElement("span");
            tag.className = "cms-tag";
            tag.dataset.value = value;
            tag.innerHTML =
                '<span class="cms-tag-text" title="' + escapeHtml(label) + '">' + escapeHtml(label) + '</span>' +
                '<button type="button" class="cms-tag-remove" aria-label="Remove ' + escapeHtml(label) + '">&times;</button>';
            tag.querySelector(".cms-tag-remove").addEventListener("mousedown", function (e) {
                e.stopPropagation(); e.preventDefault(); removeEmployee(value);
            });
            tagsContainer.insertBefore(tag, searchInline);
        });
    }

    function removeEmployee(value) {
        selected.delete(value);
        const opt = optionsList.querySelector('.cms-option[data-value="' + value + '"]');
        if (opt) { opt.classList.remove("is-selected"); opt.setAttribute("aria-selected", "false"); }
        renderTags(); syncHiddenInputs(); updateCount();
    }

    function syncHiddenInputs() {
        hiddenInputs.innerHTML = "";
        selected.forEach(function (label, value) {
            const inp = document.createElement("input");
            inp.type = "hidden"; inp.name = "employee_id[]"; inp.value = value;
            hiddenInputs.appendChild(inp);
        });
    }

    function updateCount() { countEl.textContent = selected.size + " selected"; }

    btnSelectAll.addEventListener("mousedown", function (e) {
        e.stopPropagation(); clearEmployeeValidation();
        optionsList.querySelectorAll(".cms-option").forEach(function (opt) {
            if (opt.classList.contains("is-hidden")) return;
            const value = opt.dataset.value;
            if (!selected.has(value)) selected.set(value, opt.dataset.label.trim());
            opt.classList.add("is-selected"); opt.setAttribute("aria-selected", "true");
        });
        renderTags(); syncHiddenInputs(); updateCount();
    });

    btnClearAll.addEventListener("mousedown", function (e) {
        e.stopPropagation(); selected.clear();
        optionsList.querySelectorAll(".cms-option").forEach(function (opt) {
            opt.classList.remove("is-selected"); opt.setAttribute("aria-selected", "false");
        });
        renderTags(); syncHiddenInputs(); updateCount(); clearEmployeeValidation();
    });

    searchInline.addEventListener("keydown", function (e) {
        if (e.key === "Backspace" && this.value === "" && selected.size > 0) {
            removeEmployee(Array.from(selected.keys()).pop());
        }
        if (e.key === "Escape") closeDropdown();
    });

    dropSearch.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeDropdown();
    });

    updateCount();

    // ================================================
    // DAY OFF TOGGLE
    // ================================================
    document.addEventListener("change", function (e) {
        if (!e.target.classList.contains("checkbox_child")) return;
        const row    = e.target.closest(".sched-row");
        const inputs = row.querySelectorAll(".timepicker");
        clearErrors(row);
        if (e.target.checked) {
            row.classList.add("is-day-off");
            inputs.forEach(function (input) {
                input.dataset.prev = input.value;
                if (input._flatpickr) { input._flatpickr.setDate("00:00"); } else { input.value = "00:00"; }
                input.disabled = true;
            });
        } else {
            row.classList.remove("is-day-off");
            inputs.forEach(function (input) {
                input.disabled = false;
                const prev = input.dataset.prev || "";
                if (input._flatpickr) { input._flatpickr.setDate(prev); } else { input.value = prev; }
            });
        }
        updateSummary();
    });

    // ================================================
    // LIVE VALIDATION
    // ================================================
    document.addEventListener("input", function (e) {
        if (e.target.classList.contains("timepicker")) validateRow(e.target.closest(".sched-row"));
    });

    // ================================================
    // FORM SUBMIT VALIDATION
    // ================================================
    document.querySelector("form").addEventListener("submit", function (e) {
        let isValid = true;
        clearEmployeeValidation();
        if (selected.size === 0) {
            isValid = false;
            control.classList.add("border-danger");
            const error = document.createElement("small");
            error.className = "text-danger d-block mt-1 employee-error";
            error.innerText = "Please select at least one employee.";
            wrapper.appendChild(error);
        }
        document.querySelectorAll(".sched-row").forEach(function (row) {
            if (!validateRow(row)) isValid = false;
        });
        if (!isValid) {
            e.preventDefault();
            if (selected.size === 0) openDropdown();
        }
    });

    // ================================================
    // TIME TO MINUTES
    // ================================================
    function timeToMinutes(time) {
        if (!time) return null;
        const parts = time.split(":");
        return (parseInt(parts[0]) * 60) + parseInt(parts[1]);
    }

    // ================================================
    // CLEAR ERRORS
    // ================================================
    function clearErrors(row) {
        row.querySelectorAll(".timepicker").forEach(function (el) { el.classList.remove("is-invalid"); });
        row.querySelectorAll(".error-text").forEach(function (el) { el.remove(); });
    }

    // ================================================
    // SHOW ERROR
    // ================================================
    function showError(input, message) {
        input.classList.add("is-invalid");
        const parent = input.closest(".form-group") || input.parentElement;
        if (parent && !parent.querySelector(".error-text")) {
            const small = document.createElement("small");
            small.className = "text-danger error-text d-block";
            small.style.fontSize = "0.7rem";
            small.innerText = message;
            parent.appendChild(small);
        }
    }

    // ================================================
    // VALIDATE ROW
    // ================================================
    function validateRow(row) {
        clearErrors(row);
        const start      = row.querySelector('[name="start_time[]"]');
        const end        = row.querySelector('[name="end_time[]"]');
        const breakStart = row.querySelector('[name="break_start[]"]');
        const breakEnd   = row.querySelector('[name="break_end[]"]');
        const isDayOff   = row.querySelector(".checkbox_child").checked;
        if (isDayOff) return true;
        let valid = true;
        const startVal = start.value, endVal = end.value;
        const bsVal = breakStart.value, beVal = breakEnd.value;
        const startMin = timeToMinutes(startVal), endMin = timeToMinutes(endVal);
        if (!startVal) { showError(start, "Start time is required"); valid = false; }
        if (!endVal)   { showError(end,   "End time is required");   valid = false; }
        if (startVal && endVal && startMin >= endMin) {
            showError(start, "Start must be before end"); showError(end, "End must be after start"); valid = false;
        }
        if (bsVal && !beVal) { showError(breakEnd,   "Break end required");   valid = false; }
        if (!bsVal && beVal) { showError(breakStart, "Break start required"); valid = false; }
        if (bsVal && beVal) {
            const bsMin = timeToMinutes(bsVal), beMin = timeToMinutes(beVal);
            if (bsMin >= beMin) { showError(breakStart, "Break must be valid"); showError(breakEnd, "Break must be valid"); valid = false; }
            if (bsMin < startMin || beMin > endMin) {
                showError(breakStart, "Break must be within shift hours"); showError(breakEnd, "Break must be within shift hours"); valid = false;
            }
        }
        return valid;
    }

});
</script>
@endsection
