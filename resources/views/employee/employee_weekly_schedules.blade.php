@extends("../layout")
@section("additional_head")
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
<link href="{{ asset('assets/src/assets/css/light/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/dark/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
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

/* ── Action buttons ────────────────────────────────────────── */
.tbtn {
    width: 28px; height: 28px; border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    border: none; background: transparent; padding: 0;
    transition: background 0.15s;
}
.tbtn:hover:not(:disabled) { background: rgba(0,0,0,0.06); }
body.dark .tbtn:hover:not(:disabled) { background: rgba(255,255,255,0.08); }
.tbtn:disabled { opacity: 0.3; cursor: not-allowed; }
.tbtn.tbtn-edit  { color: #00b5b5; }
.tbtn.tbtn-del   { color: #e7515a; }
.tbtn.pe-none    { opacity: 0.28; pointer-events: none; }

/* ── Day-off cell ──────────────────────────────────────────── */
.day-off-cell { color: #999; font-style: italic; font-size: 0.78rem; text-align: center; }

/* ── Current row ───────────────────────────────────────────── */
tr.row-current > td:first-child { border-left: 3px solid #1b55e2; }
tr.row-current { background: rgba(27,85,226,0.025) !important; }
body.dark tr.row-current { background: rgba(27,85,226,0.07) !important; }

/* ── Base thead th ─────────────────────────────────────────── */
#zero-config thead th { background: #f1f3f9 !important; color: #555; }
body.dark #zero-config thead th { background: rgba(255,255,255,0.06) !important; color: #aaa; }

/* ── Day column tints — body cells ────────────────────────── */
.td-mon { background: rgba(0,171,85,0.05) !important; }
.td-tue { background: rgba(27,85,226,0.05) !important; }
.td-wed { background: rgba(0,181,181,0.05) !important; }
.td-thu { background: rgba(136,142,168,0.06) !important; }
.td-fri { background: rgba(226,160,63,0.06) !important; }
.td-sat, .td-sun { background: rgba(231,81,90,0.05) !important; }

/* ── Day column tints — header cells (#zero-config prefix wins specificity) ── */
#zero-config thead .td-mon { background: rgba(0,171,85,0.15) !important; color: #007a3d; font-weight: 700; }
#zero-config thead .td-tue { background: rgba(27,85,226,0.13) !important; color: #1b55e2; font-weight: 700; }
#zero-config thead .td-wed { background: rgba(0,181,181,0.13) !important; color: #007f7f; font-weight: 700; }
#zero-config thead .td-thu { background: rgba(136,142,168,0.16) !important; color: #444;   font-weight: 700; }
#zero-config thead .td-fri { background: rgba(226,160,63,0.15) !important; color: #9a6800; font-weight: 700; }
#zero-config thead .td-sat,
#zero-config thead .td-sun { background: rgba(231,81,90,0.13) !important; color: #c0392b; font-weight: 700; }

/* Dark mode body cells */
body.dark .td-mon { background: rgba(0,171,85,0.09) !important; }
body.dark .td-tue { background: rgba(27,85,226,0.09) !important; }
body.dark .td-wed { background: rgba(0,181,181,0.09) !important; }
body.dark .td-thu { background: rgba(136,142,168,0.10) !important; }
body.dark .td-fri { background: rgba(226,160,63,0.10) !important; }
body.dark .td-sat,
body.dark .td-sun { background: rgba(231,81,90,0.09) !important; }

/* Dark mode header cells */
body.dark #zero-config thead .td-mon { background: rgba(0,171,85,0.22) !important; color: #4dd68c; }
body.dark #zero-config thead .td-tue { background: rgba(27,85,226,0.24) !important; color: #7ea8f8; }
body.dark #zero-config thead .td-wed { background: rgba(0,181,181,0.22) !important; color: #4dd4d4; }
body.dark #zero-config thead .td-thu { background: rgba(136,142,168,0.24) !important; color: #aaa; }
body.dark #zero-config thead .td-fri { background: rgba(226,160,63,0.22) !important; color: #f0c060; }
body.dark #zero-config thead .td-sat,
body.dark #zero-config thead .td-sun { background: rgba(231,81,90,0.22) !important; color: #f07070; }

/* ── Time text ─────────────────────────────────────────────── */
.time-text { font-size: 0.8rem; white-space: nowrap; font-variant-numeric: tabular-nums; }
</style>
@endsection

@section("content")
<div class="row layout-top-spacing">

    {{-- ── Page header ─────────────────────────────────────── --}}
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div class="d-flex align-items-center gap-3">
            <div style="width:46px;height:46px;border-radius:12px;background:rgba(27,85,226,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1b55e2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
                <h4 class="mb-0 fw-bold" style="font-size:1.15rem;">Employee Weekly Schedules</h4>
                <p class="mb-0 text-muted" style="font-size:0.8rem;">View and manage this employee's weekly work schedule history.</p>
            </div>
        </div>
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Weekly Schedules</li>
            </ol>
        </nav>
    </div>

    {{-- ── Alerts ──────────────────────────────────────────── --}}
    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong>
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

    {{-- ── Table card ──────────────────────────────────────── --}}
    <div class="col-12">
        <div class="card" style="border-radius:10px;overflow:hidden;">

            {{-- Card header --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2"
                 style="padding:0.9rem 1.25rem;border-bottom:1px solid var(--bs-border-color,#e0e6ed);">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1b55e2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <h6 class="mb-0 fw-bold">Schedule History</h6>
                    <span style="font-size:0.71rem;font-weight:600;color:#fff;background:#1b55e2;padding:0.2rem 0.65rem;border-radius:20px;">{{ $weeklySchedules->count() }} {{ Str::plural('record', $weeklySchedules->count()) }}</span>
                </div>
                <a href="{{ route('ws.load', $employee->employee_id) }}"
                   class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 px-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Set New Schedule
                </a>
            </div>

            {{-- Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="zero-config" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center ps-3" style="min-width:80px;">Actions</th>
                                <th rowspan="2" style="min-width:130px;">Effective Date</th>
                                <th rowspan="2" style="min-width:120px;">End Date</th>
                                <th colspan="2" class="td-mon text-center py-3" style="font-size:0.72rem;">Monday</th>
                                <th colspan="2" class="td-tue text-center py-3" style="font-size:0.72rem;">Tuesday</th>
                                <th colspan="2" class="td-wed text-center py-3" style="font-size:0.72rem;">Wednesday</th>
                                <th colspan="2" class="td-thu text-center py-3" style="font-size:0.72rem;">Thursday</th>
                                <th colspan="2" class="td-fri text-center py-3" style="font-size:0.72rem;">Friday</th>
                                <th colspan="2" class="td-sat text-center py-3" style="font-size:0.72rem;">Saturday</th>
                                <th colspan="2" class="td-sun text-center pe-3 py-3" style="font-size:0.72rem;">Sunday</th>
                            </tr>
                            <tr>
                                <th class="td-mon" style="font-size:0.68rem;min-width:100px;">Work</th>
                                <th class="td-mon" style="font-size:0.68rem;min-width:85px;">Break</th>
                                <th class="td-tue" style="font-size:0.68rem;min-width:100px;">Work</th>
                                <th class="td-tue" style="font-size:0.68rem;min-width:85px;">Break</th>
                                <th class="td-wed" style="font-size:0.68rem;min-width:100px;">Work</th>
                                <th class="td-wed" style="font-size:0.68rem;min-width:85px;">Break</th>
                                <th class="td-thu" style="font-size:0.68rem;min-width:100px;">Work</th>
                                <th class="td-thu" style="font-size:0.68rem;min-width:85px;">Break</th>
                                <th class="td-fri" style="font-size:0.68rem;min-width:100px;">Work</th>
                                <th class="td-fri" style="font-size:0.68rem;min-width:85px;">Break</th>
                                <th class="td-sat" style="font-size:0.68rem;min-width:100px;">Work</th>
                                <th class="td-sat" style="font-size:0.68rem;min-width:85px;">Break</th>
                                <th class="td-sun" style="font-size:0.68rem;min-width:100px;">Work</th>
                                <th class="td-sun" style="font-size:0.68rem;min-width:85px;">Break</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                        $day_td_class = [
                            'Monday'    => 'td-mon',
                            'Tuesday'   => 'td-tue',
                            'Wednesday' => 'td-wed',
                            'Thursday'  => 'td-thu',
                            'Friday'    => 'td-fri',
                            'Saturday'  => 'td-sat',
                            'Sunday'    => 'td-sun',
                        ];
                        @endphp
                        @foreach($weeklySchedules as $ws)
                        @php
                            $days = ['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null];
                            foreach ($ws->schedules as $s) { $days[$s->day_of_week] = $s; }
                            $last_sched = collect($ws->schedules)->last();
                            $ews_id     = $last_sched ? $last_sched->ews_id : null;
                            $is_current = ($ews_id == $current_record_id);
                            $can_delete = ($ews_id == $current_record_id && $ews_id != $oldest_record_id);
                        @endphp
                        <tr class="{{ $is_current ? 'row-current' : '' }}">
                            {{-- Actions --}}
                            <td class="text-center ps-3">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    {{-- Edit --}}
                                    <button type="button"
                                        class="tbtn tbtn-edit {{ $is_current ? '' : 'disabled' }}"
                                        {{ $is_current ? '' : 'disabled' }}
                                        @if($is_current) onclick="location.href='{{ route('ews.load_update', ['ews_id' => $ews_id]) }}'" @endif
                                        title="Edit Schedule"
                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    {{-- Delete --}}
                                    @if($can_delete)
                                    <a href="{{ route('ews.delete', ['id' => $ews_id]) }}"
                                       onclick="return confirm('Delete this schedule record? This cannot be undone.')"
                                       class="tbtn tbtn-del"
                                       title="Delete Schedule"
                                       data-bs-toggle="tooltip" data-bs-placement="top">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </a>
                                    @else
                                    <span class="tbtn tbtn-del pe-none" title="Cannot delete" data-bs-toggle="tooltip" data-bs-placement="top">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Effective date --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="font-size:0.8rem;font-weight:600;color:var(--bs-body-color,#3b3f5c);">{{ \Carbon\Carbon::parse($ws->effective_date)->format('M d, Y') }}</span>
                                    @if($is_current)
                                    <span class="badge bg-success" style="font-size:0.62rem;">Current</span>
                                    @endif
                                </div>
                            </td>

                            {{-- End date --}}
                            <td>
                                @if($ws->end_date)
                                    <span style="font-size:0.8rem;">{{ \Carbon\Carbon::parse($ws->end_date)->format('M d, Y') }}</span>
                                @else
                                    <span class="badge bg-success text-white" style="font-size:0.72rem;">Present</span>
                                @endif
                            </td>

                            {{-- Day columns --}}
                            @foreach($days as $day => $sched)
                            @php $dc = $day_td_class[$day]; @endphp
                            @if(!$sched || $sched->is_day_off)
                                <td class="day-off-cell {{ $dc }}">—</td>
                                <td class="day-off-cell {{ $dc }}">—</td>
                            @else
                                <td class="{{ $dc }} time-text">{{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}&nbsp;–&nbsp;{{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}</td>
                                <td class="{{ $dc }} time-text">@if($sched->break_start){{ \Carbon\Carbon::parse($sched->break_start)->format('h:i A') }}&nbsp;–&nbsp;{{ \Carbon\Carbon::parse($sched->break_end)->format('h:i A') }}@else<span class="text-muted">—</span>@endif</td>
                            @endif
                            @endforeach
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
<script src="{{ asset('assets/src/assets/js/elements/tooltip.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script>
$('#zero-config').DataTable({
    "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
           "<'table-responsive'tr>" +
           "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
    "oLanguage": {
        "oPaginate": {
            "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>',
            "sNext":     '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>'
        },
        "sInfo":             "Showing page _PAGE_ of _PAGES_",
        "sSearch":           '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
        "sSearchPlaceholder":"Search...",
        "sLengthMenu":       "Results : _MENU_"
    },
    "stripeClasses": [],
    "lengthMenu":  [7, 10, 20, 50],
    "pageLength":  10,
    "ordering":    false
});

document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
    new bootstrap.Tooltip(el, { trigger: 'hover' });
});
</script>
@endsection
