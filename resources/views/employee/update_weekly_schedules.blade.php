@extends("../layout")
@section("additional_head")
<link href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
<style>
/* ── Employee strip ────────────────────────────────────────── */
.emp-strip {
    display: flex; flex-wrap: wrap; gap: 0.5rem 1.5rem; align-items: center;
    padding: 0.9rem 1.25rem;
    background: rgba(27,85,226,0.04);
    border: 1px solid rgba(27,85,226,0.12);
    border-left: 4px solid #1b55e2;
    border-radius: 10px;
}
body.dark .emp-strip { background: rgba(27,85,226,0.09); border-color: rgba(27,85,226,0.22); }
.emp-strip-id {
    font-size: 0.72rem; font-weight: 700; color: #1b55e2;
    background: rgba(27,85,226,0.1); padding: 0.2rem 0.65rem; border-radius: 20px;
}
.emp-strip-name { font-weight: 700; font-size: 0.92rem; }
.emp-strip-meta { font-size: 0.79rem; color: #888; display: flex; align-items: center; gap: 0.3rem; margin-top: 0.1rem; flex-wrap: wrap; }

/* ── Date card ─────────────────────────────────────────────── */
.date-card {
    border-radius: 10px;
    border: 1px solid var(--bs-border-color, #e0e6ed);
    padding: 1.1rem 1.25rem 0.85rem;
    background: var(--bs-body-bg, #fff);
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
.day-circle[data-day="Saturday"]  { background: #fdecea; color: #e7515a; }
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
<form action="{{ route('ews.update', $ews_id) }}" method="POST">
@csrf
<div class="row layout-top-spacing">

    {{-- ── Page header ─────────────────────────────────────── --}}
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div class="d-flex align-items-center gap-3">
            <div style="width:46px;height:46px;border-radius:12px;background:rgba(0,171,85,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00ab55" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <div>
                <h4 class="mb-0 fw-bold" style="font-size:1.15rem;">Update Weekly Schedule</h4>
                <p class="mb-0 text-muted" style="font-size:0.8rem;">Edit working hours and break times for each day of the week.</p>
            </div>
        </div>
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Update Weekly Schedule</li>
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

    {{-- ── Employee strip ──────────────────────────────────── --}}
    <div class="col-12 mb-3">
        <div class="emp-strip">
            <div style="width:38px;height:38px;border-radius:50%;background:rgba(27,85,226,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1b55e2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
                <div class="emp-strip-name">{{ $employee->lastname }}, {{ $employee->firstname }}</div>
                <div class="emp-strip-meta">
                    <span class="emp-strip-id">{{ $employee->employee_number }}</span>
                    <span style="color:#ccc;">·</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    {{ $employee->branch_code }} {{ $employee->branch_name }}
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
                    <input type="text" name="effective_date" class="form-control form-control-sm datepicker"
                        value="{{ old('effective_date', $effective_date ? \Carbon\Carbon::parse($effective_date)->format('Y-m-d') : '') }}"
                        placeholder="Select date" required readonly>
                    <small class="text-muted" style="font-size:0.7rem;">When this schedule starts</small>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="field-label">
                        End Date
                        <span class="text-muted fw-normal" style="text-transform:none;letter-spacing:0;">(blank = Present)</span>
                    </label>
                    <input type="text" name="end_date" class="form-control form-control-sm datepicker"
                        value="{{ old('end_date', isset($end_date) && $end_date ? \Carbon\Carbon::parse($end_date)->format('Y-m-d') : '') }}"
                        placeholder="Select date (optional)" readonly>
                    <small class="text-muted" style="font-size:0.7rem;">Leave blank if still active</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Schedule builder card ────────────────────────────── --}}
    <div class="col-12 mb-4">
        <div class="card" style="border-radius:10px;overflow:hidden;">

            {{-- Card header --}}
            <div class="sched-section-hdr">
                <div class="sched-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#00ab55" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Weekly Schedule Builder
                    <span class="text-muted fw-normal" style="font-size:0.72rem;">— edit hours per day</span>
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
            $day_initial = ['Monday'=>'M','Tuesday'=>'T','Wednesday'=>'W','Thursday'=>'Th','Friday'=>'F','Saturday'=>'Sa','Sunday'=>'Su'];
            @endphp

            @foreach ($weeklySched as $ws)
            <div class="sched-row {{ $ws->is_day_off ? 'is-day-off' : '' }}" data-day="{{ $ws->day_of_week }}">
                <input type="hidden" name="day_of_week[]" value="{{ $ws->day_of_week }}">

                <div class="day-col">
                    <div class="day-circle" data-day="{{ $ws->day_of_week }}">{{ $day_initial[$ws->day_of_week] }}</div>
                    <span class="day-abr">{{ substr($ws->day_of_week, 0, 3) }}</span>
                </div>

                <div class="sched-fields">
                    <div class="field-grp">
                        <div class="field-grp-lbl lbl-work">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Work Hours
                        </div>
                        <div class="time-pair">
                            <div class="form-group">
                                <input name="start_time[]" class="form-control form-control-sm timepicker" type="text"
                                    value="{{ $ws->start_time ? \Carbon\Carbon::parse($ws->start_time)->format('H:i') : '08:00' }}"
                                    {{ $ws->is_day_off ? 'disabled' : 'required' }} placeholder="Start">
                            </div>
                            <span class="time-sep">&#x2192;</span>
                            <div class="form-group">
                                <input name="end_time[]" class="form-control form-control-sm timepicker" type="text"
                                    value="{{ $ws->end_time ? \Carbon\Carbon::parse($ws->end_time)->format('H:i') : '17:00' }}"
                                    {{ $ws->is_day_off ? 'disabled' : 'required' }} placeholder="End">
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
                                <input name="break_start[]" class="form-control form-control-sm timepicker" type="text"
                                    value="{{ $ws->break_start ? \Carbon\Carbon::parse($ws->break_start)->format('H:i') : '' }}"
                                    {{ $ws->is_day_off ? 'disabled' : '' }} placeholder="Start">
                            </div>
                            <span class="time-sep">&#x2192;</span>
                            <div class="form-group">
                                <input name="break_end[]" class="form-control form-control-sm timepicker" type="text"
                                    value="{{ $ws->break_end ? \Carbon\Carbon::parse($ws->break_end)->format('H:i') : '' }}"
                                    {{ $ws->is_day_off ? 'disabled' : '' }} placeholder="End">
                            </div>
                        </div>
                    </div>
                </div>

                <span class="dayoff-tag">Day Off</span>

                <div class="toggle-col">
                    <span class="toggle-lbl">Day Off</span>
                    <div class="form-check form-switch mb-0">
                        <input type="hidden" name="is_day_off[{{ $loop->index }}]" value="0">
                        <input class="form-check-input checkbox_child" type="checkbox"
                            name="is_day_off[{{ $loop->index }}]" value="1"
                            {{ $ws->is_day_off ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="actions-bar">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="15 18 9 12 15 6"/></svg>
                    Cancel
                </a>
                <button class="btn btn-success btn-sm px-4" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Changes
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

    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "M d, Y",
        allowInput: false,
        disableMobile: true
    });

    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    function updateSummary() {
        let total = document.querySelectorAll(".sched-row").length;
        let off   = document.querySelectorAll(".sched-row.is-day-off").length;
        document.getElementById("work-count").textContent = total - off;
        document.getElementById("off-count").textContent  = off;
    }
    updateSummary();

    function timeToMinutes(time) {
        if (!time) return null;
        let [h, m] = time.split(":").map(Number);
        return h * 60 + m;
    }
    function clearErrors(row) {
        row.querySelectorAll(".timepicker").forEach(el => el.classList.remove("is-invalid"));
        row.querySelectorAll(".error-text").forEach(el => el.remove());
    }
    function showError(input, message) {
        input.classList.add("is-invalid");
        let parent = input.closest(".form-group");
        if (parent && !parent.querySelector(".error-text")) {
            let small = document.createElement("small");
            small.className = "text-danger error-text d-block mt-1";
            small.style.fontSize = "0.7rem";
            small.innerText = message;
            parent.appendChild(small);
        }
    }
    function validateRow(row) {
        clearErrors(row);
        let start = row.querySelector('[name="start_time[]"]');
        let end   = row.querySelector('[name="end_time[]"]');
        let bsEl  = row.querySelector('[name="break_start[]"]');
        let beEl  = row.querySelector('[name="break_end[]"]');
        if (row.querySelector('.checkbox_child').checked) return true;
        let valid = true;
        let startVal = start.value, endVal = end.value;
        let bsVal = bsEl.value, beVal = beEl.value;
        let startMin = timeToMinutes(startVal), endMin = timeToMinutes(endVal);
        if (!startVal) { showError(start, "Start time required"); valid = false; }
        if (!endVal)   { showError(end,   "End time required");   valid = false; }
        if (startVal && endVal && startMin >= endMin) {
            showError(start, "Must be before end time"); showError(end, "Must be after start time"); valid = false;
        }
        if (bsVal && !beVal)  { showError(beEl, "Break end required");   valid = false; }
        if (!bsVal && beVal)  { showError(bsEl, "Break start required"); valid = false; }
        if (bsVal && beVal) {
            let bsMin = timeToMinutes(bsVal), beMin = timeToMinutes(beVal);
            if (bsMin >= beMin) { showError(bsEl, "Invalid break range"); showError(beEl, "Invalid break range"); valid = false; }
            if (bsMin < startMin || beMin > endMin) {
                showError(bsEl, "Must be within work hours"); showError(beEl, "Must be within work hours"); valid = false;
            }
        }
        return valid;
    }

    document.addEventListener("change", function (e) {
        if (!e.target.classList.contains("checkbox_child")) return;
        let row    = e.target.closest(".sched-row");
        let inputs = row.querySelectorAll(".timepicker");
        clearErrors(row);
        if (e.target.checked) {
            row.classList.add("is-day-off");
            inputs.forEach(inp => { inp.value = "00:00"; inp.disabled = true; inp.removeAttribute("required"); });
        } else {
            row.classList.remove("is-day-off");
            inputs.forEach(inp => { inp.value = ""; inp.disabled = false; inp.setAttribute("required", true); });
        }
        updateSummary();
    });

    document.addEventListener("input", function (e) {
        if (e.target.classList.contains("timepicker")) validateRow(e.target.closest(".sched-row"));
    });

    document.querySelector("form").addEventListener("submit", function (e) {
        let isValid = true;
        document.querySelectorAll(".sched-row").forEach(row => { if (!validateRow(row)) isValid = false; });
        if (!isValid) e.preventDefault();
    });
});
</script>
@endsection
