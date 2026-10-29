@extends("../layout")
@section("additional_head")
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
<link href="{{ asset('assets/src/assets/css/light/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/dark/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
<style>
.tbtn { width: 24px; height: 24px; }
.day-off-cell { color: #aaa; font-style: italic; font-size: 0.78rem; }
.filter-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #888; margin-bottom: 0.3rem; display: block; }

/* ── Base thead <th> — light neutral for non-day headers ── */
#zero-config thead th { background: #f1f3f9 !important; color: #555; }
body.dark #zero-config thead th { background: rgba(255,255,255,0.06) !important; color: #aaa; }

/* ── Per-day column tints — header cells slightly stronger than body ── */
/* Body cells */
.td-mon { background: rgba(0,171,85,0.06) !important; }
.td-tue { background: rgba(27,85,226,0.06) !important; }
.td-wed { background: rgba(0,181,181,0.06) !important; }
.td-thu { background: rgba(136,142,168,0.07) !important; }
.td-fri { background: rgba(226,160,63,0.07) !important; }
.td-sat, .td-sun { background: rgba(231,81,90,0.06) !important; }

/* Header cells — #zero-config prefix gives specificity over the base rule */
#zero-config thead .td-mon { background: rgba(0,171,85,0.15) !important; color: #007a3d; font-weight: 700; }
#zero-config thead .td-tue { background: rgba(27,85,226,0.13) !important; color: #1b55e2; font-weight: 700; }
#zero-config thead .td-wed { background: rgba(0,181,181,0.13) !important; color: #007f7f; font-weight: 700; }
#zero-config thead .td-thu { background: rgba(136,142,168,0.16) !important; color: #444; font-weight: 700; }
#zero-config thead .td-fri { background: rgba(226,160,63,0.15) !important; color: #9a6800; font-weight: 700; }
#zero-config thead .td-sat,
#zero-config thead .td-sun { background: rgba(231,81,90,0.13) !important; color: #c0392b; font-weight: 700; }

/* Dark mode body cells */
body.dark .td-mon { background: rgba(0,171,85,0.10) !important; }
body.dark .td-tue { background: rgba(27,85,226,0.10) !important; }
body.dark .td-wed { background: rgba(0,181,181,0.10) !important; }
body.dark .td-thu { background: rgba(136,142,168,0.11) !important; }
body.dark .td-fri { background: rgba(226,160,63,0.11) !important; }
body.dark .td-sat,
body.dark .td-sun { background: rgba(231,81,90,0.10) !important; }

/* Dark mode header cells */
body.dark #zero-config thead .td-mon { background: rgba(0,171,85,0.22) !important; color: #4dd68c; }
body.dark #zero-config thead .td-tue { background: rgba(27,85,226,0.24) !important; color: #7ea8f8; }
body.dark #zero-config thead .td-wed { background: rgba(0,181,181,0.22) !important; color: #4dd4d4; }
body.dark #zero-config thead .td-thu { background: rgba(136,142,168,0.24) !important; color: #aaa; }
body.dark #zero-config thead .td-fri { background: rgba(226,160,63,0.22) !important; color: #f0c060; }
body.dark #zero-config thead .td-sat,
body.dark #zero-config thead .td-sun { background: rgba(231,81,90,0.22) !important; color: #f07070; }
</style>
@endsection

@section("content")
<div class="row layout-top-spacing">

    {{-- Header --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <div>
            <h4 class="mb-1 fw-bold">Weekly Schedules</h4>
            <p class="mb-0 text-muted" style="font-size:0.85rem;">View and manage employee weekly work schedules by branch.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Weekly Schedules</li>
                </ol>
            </nav>
            <a href="{{ route('ews.load_bulk_update') }}"
               class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Bulk Scheduling
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
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

    {{-- Filter --}}
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body py-3">
                <form id="filterForm" method="GET" action="{{ route('ews.load_wsl') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-sm-5">
                            <label class="filter-label">Branch</label>
                            <select id="branch_code" name="branch_code" autocomplete="off"
                                    onchange="document.getElementById('filterForm').submit();">
                                <option value="All">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->branch_code }}"
                                        {{ request('branch_code') == $branch->branch_code ? 'selected' : '' }}>
                                        {{ $branch->branch_code }} {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-5">
                            <label class="filter-label">Schedule</label>
                            <select class="form-select form-select-sm" id="has_schedule" name="has_schedule"
                                    onchange="document.getElementById('filterForm').submit();">
                                <option value="all" {{ request('has_schedule', 'all') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="with" {{ request('has_schedule') == 'with' ? 'selected' : '' }}>With Schedule</option>
                                <option value="without" {{ request('has_schedule') == 'without' ? 'selected' : '' }}>Without Schedule</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="zero-config" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th rowspan="2" class="ps-4 py-3" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;">Actions</th>
                                <th rowspan="2" class="py-3" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;">Employee</th>
                                <th rowspan="2" class="py-3" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;">Effective</th>
                                <th rowspan="2" class="py-3" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.06em;">End Date</th>
                                <th colspan="2" class="td-mon text-center py-3" style="font-size:0.72rem;">Monday</th>
                                <th colspan="2" class="td-tue text-center py-3" style="font-size:0.72rem;">Tuesday</th>
                                <th colspan="2" class="td-wed text-center py-3" style="font-size:0.72rem;">Wednesday</th>
                                <th colspan="2" class="td-thu text-center py-3" style="font-size:0.72rem;">Thursday</th>
                                <th colspan="2" class="td-fri text-center py-3" style="font-size:0.72rem;">Friday</th>
                                <th colspan="2" class="td-sat text-center py-3" style="font-size:0.72rem;">Saturday</th>
                                <th colspan="2" class="td-sun text-center pe-4 py-3" style="font-size:0.72rem;">Sunday</th>
                            </tr>
                            <tr>
                                <th class="td-mon" style="font-size:0.68rem;">Work</th>
                                <th class="td-mon" style="font-size:0.68rem;">Break</th>
                                <th class="td-tue" style="font-size:0.68rem;">Work</th>
                                <th class="td-tue" style="font-size:0.68rem;">Break</th>
                                <th class="td-wed" style="font-size:0.68rem;">Work</th>
                                <th class="td-wed" style="font-size:0.68rem;">Break</th>
                                <th class="td-thu" style="font-size:0.68rem;">Work</th>
                                <th class="td-thu" style="font-size:0.68rem;">Break</th>
                                <th class="td-fri" style="font-size:0.68rem;">Work</th>
                                <th class="td-fri" style="font-size:0.68rem;">Break</th>
                                <th class="td-sat" style="font-size:0.68rem;">Work</th>
                                <th class="td-sat" style="font-size:0.68rem;">Break</th>
                                <th class="td-sun" style="font-size:0.68rem;">Work</th>
                                <th class="td-sun" style="font-size:0.68rem;">Break</th>
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
                            @foreach($weeklySchedules as $emp)
                            @php
                                $ws = $emp->latest_ws;
                                $days = ['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null];
                                if ($ws) {
                                    foreach ($ws->schedules as $sched) { $days[$sched->day_of_week] = $sched; }
                                }
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('schedules.load', ['employee_id' => $emp->employee_id]) }}"
                                       class="border-0 bg-transparent text-primary d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                       title="View Schedule">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                    @if($ws)
                                    <a href="{{ route('ews.load_update', ['ews_id' => $ws->ews_id]) }}"
                                       class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                       title="Edit Schedule">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    @else
                                    <a href="{{ route('ws.load', ['employee_id' => $emp->employee_id]) }}"
                                       class="border-0 bg-transparent text-success d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                       title="Add Schedule">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    </a>
                                    @endif
                                </td>
                                <td>
                                    <p class="mb-0 fw-semibold" style="font-size:0.85rem;">{{ $emp->lastname }}, {{ $emp->firstname }} {{ $emp->suffix }} {{ $emp->middlename ? strtoupper(substr($emp->middlename, 0, 1)) . '.' : '' }}</p>
                                    <small class="text-muted">{{ $emp->employee_number }}</small>
                                </td>
                                @if($ws)
                                <td style="font-size:0.82rem;">{{ \Carbon\Carbon::parse($ws->effective_date)->format('M d, Y') }}</td>
                                <td style="font-size:0.82rem;">
                                    @if($ws->end_date)
                                        {{ \Carbon\Carbon::parse($ws->end_date)->format('M d, Y') }}
                                    @else
                                        <span class="badge bg-success" style="font-size:0.7rem;">Present</span>
                                    @endif
                                </td>
                                @else
                                <td style="font-size:0.82rem;">—</td>
                                <td style="font-size:0.82rem;"><span class="badge bg-secondary" style="font-size:0.7rem;">No schedule yet</span></td>
                                @endif
                                @foreach($days as $day => $sched)
                                @php $dc = $day_td_class[$day]; @endphp
                                    @if(!$ws)
                                        <td class="{{ $dc }} text-muted">—</td>
                                        <td class="{{ $dc }} text-muted">—</td>
                                    @elseif(!$sched || $sched->is_day_off)
                                        <td class="day-off-cell {{ $dc }}">Day-off</td>
                                        <td class="day-off-cell {{ $dc }}">—</td>
                                    @else
                                        <td class="{{ $dc }}" style="font-size:0.78rem;white-space:nowrap;">{{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}</td>
                                        <td class="{{ $dc }}" style="font-size:0.78rem;white-space:nowrap;">{{ $sched->break_start ? \Carbon\Carbon::parse($sched->break_start)->format('h:i A') : '—' }} – {{ $sched->break_end ? \Carbon\Carbon::parse($sched->break_end)->format('h:i A') : '—' }}</td>
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
<script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script>
document.querySelectorAll('.tbtn[title]').forEach(function(el) {
    new bootstrap.Tooltip(el, { trigger: 'hover' });
});

new TomSelect('#branch_code', {
    create: false,
    sortField: { field: 'text', direction: 'asc' },
    placeholder: 'Select or type a branch'
});

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
    pageLength: 10,
    ordering: false
});
</script>
@endsection
