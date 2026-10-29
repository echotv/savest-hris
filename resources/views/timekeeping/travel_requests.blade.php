@extends("../layout")

@section("additional_head")
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">

<link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet">
<link href="{{ asset('assets/src/assets/css/light/components/modal.css') }}" rel="stylesheet">
<link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet">
<link href="{{ asset('assets/src/assets/css/dark/components/modal.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}">

<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">

<style>
    .tracking-tight { letter-spacing: -0.5px; }
    .tracking-wider { letter-spacing: 0.5px; }
    .font-11 { font-size: 11px; }
    .font-12 { font-size: 12px; }
    .font-13 { font-size: 13px; }
    .flatpickr-calendar { z-index: 99999 !important; }

    .ts-wrapper.form-select-sm { min-height: unset; }
    .ts-wrapper.form-select-sm .ts-control {
        padding-top: .25rem !important; padding-bottom: .25rem !important;
        padding-left: .5rem !important;
        font-size: .875rem !important; line-height: 1.5 !important;
    }
    .ts-wrapper.form-select-sm .ts-control input {
        font-size: .875rem !important; line-height: 1.5 !important;
        padding: 0 !important; margin: 0 !important;
    }

    /* Filter TomSelects — compact size matching form-select-sm */
    .filter-ts .ts-control {
        padding-top: .25rem !important; padding-bottom: .25rem !important;
        padding-left: .5rem !important;
        font-size: .875rem !important; line-height: 1.5 !important;
        min-height: unset !important;
    }
    .filter-ts { min-height: unset; }

    /* Days table inside modal */
    #daysTableWrap { max-height: 280px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 8px; }
    body.dark #daysTableWrap { border-color: #1b2e4b; }
    #daysTable thead th { position: sticky; top: 0; z-index: 2; background: #f8f9fa; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; }
    body.dark #daysTable thead th { background: #1b2e4b; }
    #daysTable td { padding: .4rem .6rem; vertical-align: middle; }
    #daysTable input[type="time"] {
        width: 100%; padding: .25rem .5rem; font-size: .8rem;
        border: 1px solid #ced4da; border-radius: 6px;
        background: transparent; color: inherit;
    }
    body.dark #daysTable input[type="time"] { border-color: #1b2e4b; background: #0e1726; color: #888ea8; }
    #daysEmpty { display: none; padding: 1rem; text-align: center; color: #adb5bd; font-size: 13px; }
</style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">On-Travel / Seminar / Training</h3>
            <p class="text-muted small mb-0">Manage employee travel, seminar, and training requests with daily schedule.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">On-Travel / Seminar / Training</li>
                </ol>
            </nav>
            @if(userCan('ADD ON-TRAVEL & SEMINAR REQUESTS'))
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#travelModal" id="addNewBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Record
            </button>
            @endif
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Branch</label>
                        <select id="filter_branch">
                            <option value="">All Branches</option>
                            @foreach($branches as $br)
                                <option value="{{ $br->branch_code }}">{{ $br->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-bold text-uppercase">Type</label>
                        <select id="filter_type" class="form-select form-select-sm rounded">
                            <option value="">All Types</option>
                            <option value="On-Travel">On-Travel</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Training">Training</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Purpose</label>
                        <select id="filter_purpose">
                            <option value="">All Purposes</option>
                            @foreach($purposes as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-bold text-uppercase">Date From</label>
                        <input type="text" id="filter_date_from" class="form-control form-control-sm rounded" placeholder="From…" autocomplete="off">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-bold text-uppercase">Date To</label>
                        <input type="text" id="filter_date_to" class="form-control form-control-sm rounded" placeholder="To…" autocomplete="off">
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="travelTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Employee</th>
                                <th class="border-0 py-3">Type</th>
                                <th class="border-0 py-3">Start Date</th>
                                <th class="border-0 py-3">End Date</th>
                                <th class="border-0 py-3 text-center">Days</th>
                                <th class="border-0 py-3">Purpose</th>
                                <th class="border-0 pe-4 py-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="dt-loading-row">
                                <td colspan="7" class="text-center py-4 text-muted border-0">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></div>
                                        <span class="font-13 fw-semibold">Processing...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="travelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="travelModalTitle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    New Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="travel_id">

                <div class="row g-3">

                    {{-- Employee --}}
                    <div class="col-md-8">
                        <label class="form-label fw-medium text-secondary small">
                            Employee(s) <span class="text-danger">*</span>
                        </label>
                        <select id="travel_employee_id" class="form-select form-select-sm rounded" multiple>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">
                                    {{ trim($emp->firstname . ' ' . $emp->lastname) }} ({{ $emp->employee_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Request Type --}}
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-secondary small">Type <span class="text-danger">*</span></label>
                        <select id="travel_type" class="form-select form-select-sm rounded">
                            <option value="">— Select Type —</option>
                            <option value="On-Travel">On-Travel</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Training">Training</option>
                        </select>
                    </div>

                    {{-- Purpose --}}
                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Purpose / Title <span class="text-danger">*</span></label>
                        <input type="text" id="travel_purpose" class="form-control form-control-sm" maxlength="255" placeholder="e.g. Annual Sales Conference 2026">
                    </div>

                    {{-- Dates --}}
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-secondary small">Start Date <span class="text-danger">*</span></label>
                        <input type="text" id="travel_start_date" class="form-control form-control-sm" placeholder="Select start date…" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-secondary small">End Date <span class="text-danger">*</span></label>
                        <input type="text" id="travel_end_date" class="form-control form-control-sm" placeholder="Select end date…" autocomplete="off">
                    </div>

                    {{-- Per-Day Schedule --}}
                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small d-flex align-items-center gap-2">
                            Daily Schedule
                            <span class="badge bg-light-primary text-primary border border-primary-subtle font-11 fw-normal" id="dayCountBadge" style="display:none!important"></span>
                        </label>
                        <div id="daysEmpty" style="display:block;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted mb-1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <div class="mt-1">Select start and end dates to set daily times.</div>
                        </div>
                        <div id="daysTableWrap" style="display:none;">
                            <table id="daysTable" class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:38%">Date</th>
                                        <th style="width:10%">Day</th>
                                        <th style="width:26%">Time Start</th>
                                        <th style="width:26%">Time End</th>
                                    </tr>
                                </thead>
                                <tbody id="daysBody"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Notes <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea id="travel_notes" class="form-control form-control-sm" rows="2" placeholder="Additional details or remarks…"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-primary px-4 shadow-sm" id="saveBtn">Save</button>
            </div>

        </div>
    </div>
</div>
@endsection

@section("additional_footer")
<script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    let table; // declared early so filter onChange callbacks can reference it

    // ── Filter TomSelects ────────────────────────────────────────────────
    const branchTs = new TomSelect('#filter_branch', {
        allowEmptyOption: true,
        onChange: () => table && table.ajax.reload()
    });
    branchTs.wrapper.classList.add('filter-ts');

    const purposeTs = new TomSelect('#filter_purpose', {
        allowEmptyOption: true,
        create: false,
        onChange: () => table && table.ajax.reload()
    });
    purposeTs.wrapper.classList.add('filter-ts');

    // ── Filter date range pickers ────────────────────────────────────────
    const fpFrom = flatpickr('#filter_date_from', {
        dateFormat: 'Y-m-d',
        onChange(sel) {
            fpTo.set('minDate', sel[0] || null);
            if (table) table.ajax.reload();
        }
    });
    const fpTo = flatpickr('#filter_date_to', {
        dateFormat: 'Y-m-d',
        onChange(sel) {
            fpFrom.set('maxDate', sel[0] || null);
            if (table) table.ajax.reload();
        }
    });

    // ── Modal TomSelect (multi-select) ───────────────────────────────────
    const empTs = new TomSelect('#travel_employee_id', {
        maxItems: null,
        placeholder: 'Search and select employee(s)…'
    });

    // ── Flatpickr dates ──────────────────────────────────────────────────
    const fpStart = flatpickr('#travel_start_date', {
        dateFormat: 'Y-m-d',
        onChange(sel) {
            fpEnd.set('minDate', sel[0] || null);
            rebuildDays();
        }
    });
    const fpEnd = flatpickr('#travel_end_date', {
        dateFormat: 'Y-m-d',
        onChange(sel) {
            fpStart.set('maxDate', sel[0] || null);
            rebuildDays();
        }
    });

    // ── Helpers ──────────────────────────────────────────────────────────
    const DAY_NAMES = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const SHORT_MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    function fmtDate(ds) {
        const d = new Date(ds + 'T00:00:00');
        return SHORT_MONTHS[d.getMonth()] + ' ' + String(d.getDate()).padStart(2,'0') + ', ' + d.getFullYear();
    }
    function fmtDay(ds) {
        return DAY_NAMES[new Date(ds + 'T00:00:00').getDay()].substring(0, 3);
    }

    function rebuildDays(prefill) {
        const s = $('#travel_start_date').val();
        const e = $('#travel_end_date').val();
        const body = $('#daysBody');
        body.empty();

        if (!s || !e) {
            $('#daysTableWrap').hide();
            $('#daysEmpty').show();
            $('#dayCountBadge').hide();
            return;
        }

        const start = new Date(s + 'T00:00:00');
        const end   = new Date(e + 'T00:00:00');
        const rows  = [];
        let cur = new Date(start);
        while (cur <= end) {
            rows.push(cur.toISOString().slice(0, 10));
            cur.setDate(cur.getDate() + 1);
        }

        rows.forEach(function (ds) {
            const pre = prefill ? prefill.find(function(p) { return p.date === ds; }) : null;
            const ts  = pre ? pre.time_start : '08:00';
            const te  = pre ? pre.time_end   : '17:00';
            body.append(
                '<tr data-date="' + ds + '">' +
                '<td class="text-dark fw-medium font-12">' + fmtDate(ds) + '</td>' +
                '<td class="text-muted font-11">' + fmtDay(ds) + '</td>' +
                '<td><input type="time" class="day-ts" value="' + ts + '" required></td>' +
                '<td><input type="time" class="day-te" value="' + te + '" required></td>' +
                '</tr>'
            );
        });

        $('#daysEmpty').hide();
        $('#daysTableWrap').show();
        $('#dayCountBadge').text(rows.length + (rows.length === 1 ? ' day' : ' days')).css('display','inline-flex');
    }

    // ── DataTable ────────────────────────────────────────────────────────
    const TYPE_BADGES = {
        'On-Travel': 'bg-light-info text-info border border-info-subtle',
        'Seminar':   'bg-light-primary text-primary border border-primary-subtle',
        'Training':  'bg-light-success text-success border border-success-subtle',
    };

    table = $('#travelTable').DataTable({
        destroy: true,
        responsive: false,
        dom:
            "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
            "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'" +
            "<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
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
        ordering: false,
        searching: false,
        processing: false,
        serverSide: true,
        ajax: {
            url: "{{ route('travel.data') }}",
            data: function (d) {
                d.branch_filter   = branchTs  ? branchTs.getValue()  : '';
                d.type_filter     = $('#filter_type').val();
                d.purpose_filter  = purposeTs ? purposeTs.getValue() : '';
                d.date_from       = $('#filter_date_from').val();
                d.date_to         = $('#filter_date_to').val();
            }
        },
        columns: [
            {
                data: 'employees_info',
                render: function (d) {
                    if (!d) return '<span class="text-muted">—</span>';
                    return d.split(';;').map(function (emp) {
                        const p = emp.split('|');
                        return '<div class="mb-1"><div class="fw-semibold font-13">' + p[0] + '</div>' +
                               '<div class="text-muted font-12">' + p[1] + ' &bull; ' + p[2] + '</div></div>';
                    }).join('');
                }
            },
            {
                data: 'request_type',
                render: function (d) {
                    const cls = TYPE_BADGES[d] || 'bg-light-secondary text-secondary border border-secondary-subtle';
                    return '<span class="badge ' + cls + ' px-2 py-1 font-11 fw-semibold">' + d + '</span>';
                }
            },
            {
                data: 'start_date',
                render: function (d) {
                    if (!d) return '—';
                    return new Date(d).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: '2-digit' });
                }
            },
            {
                data: 'end_date',
                render: function (d) {
                    if (!d) return '—';
                    return new Date(d).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: '2-digit' });
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (d, t, row) {
                    const s   = new Date(row.start_date);
                    const e   = new Date(row.end_date);
                    const cnt = Math.round((e - s) / 86400000) + 1;
                    return '<span class="fw-bold text-primary font-13">' + cnt + '</span>';
                }
            },
            {
                data: 'purpose',
                render: function (d) {
                    return '<span class="font-12" style="max-width:200px;display:inline-block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="' + d + '">' + d + '</span>';
                }
            },
            { data: 'action', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#travelTable').on('processing.dt', function (e, settings, processing) {
        if (processing) {
            var el = this;
            setTimeout(function () {
                $(el).find('tbody').html(
                    '<tr class="dt-loading-row"><td colspan="7" class="text-center py-4 text-muted border-0">' +
                    '<div class="d-flex align-items-center justify-content-center gap-2">' +
                    '<div class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></div>' +
                    '<span class="font-13 fw-semibold">Processing...</span>' +
                    '</div></td></tr>'
                );
            }, 0);
        }
    });

    // ── Type filter (plain select) ───────────────────────────────────────
    $('#filter_type').on('change', function () { table.ajax.reload(); });

    // ── Reset modal ──────────────────────────────────────────────────────
    function resetModal() {
        $('#travel_id').val('');
        empTs.clear(true);
        $('#travel_type').val('');
        $('#travel_purpose').val('');
        $('#travel_notes').val('');
        fpStart.clear(); fpStart.set('maxDate', null);
        fpEnd.clear();   fpEnd.set('minDate', null);
        $('#daysBody').empty();
        $('#daysTableWrap').hide();
        $('#daysEmpty').show();
        $('#dayCountBadge').hide();
    }

    // ── Open — Add ───────────────────────────────────────────────────────
    $('#addNewBtn').on('click', function () {
        resetModal();
        $('#travelModalTitle').html(
            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> New Request'
        );
    });

    // ── Open — Edit ──────────────────────────────────────────────────────
    $(document).on('click', '.editBtn', function () {
        const btn = $(this);
        resetModal();
        $('#travel_id').val(btn.data('id'));
        let empIds = [];
        try { empIds = JSON.parse(btn.attr('data-employees') || '[]'); } catch(e) {}
        empIds.forEach(function (eid) { empTs.addItem(String(eid)); });
        $('#travel_type').val(btn.data('type'));
        $('#travel_purpose').val(btn.data('purpose'));
        $('#travel_notes').val(btn.data('notes'));

        const start = btn.data('start');
        const end   = btn.data('end');
        fpStart.setDate(start);
        fpEnd.setDate(end);
        fpEnd.set('minDate', start);
        fpStart.set('maxDate', end);

        let days = [];
        try { days = JSON.parse(btn.attr('data-days')); } catch(e) {}
        rebuildDays(days);

        $('#travelModalTitle').html(
            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Request'
        );
        $('#travelModal').modal('show');
    });

    // ── Save ─────────────────────────────────────────────────────────────
    $('#saveBtn').on('click', function () {
        const id      = $('#travel_id').val();
        const url     = id ? '/travel-requests/update/' + id : '/travel-requests/store';
        const empIds  = empTs.items; // array of selected employee IDs

        if (!empIds || empIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Please select at least one employee.', timer: 2000, showConfirmButton: false });
            return;
        }
        if (!$('#travel_type').val()) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Please select a request type.', timer: 2000, showConfirmButton: false });
            return;
        }
        if (!$('#travel_purpose').val().trim()) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Purpose / Title is required.', timer: 2000, showConfirmButton: false });
            return;
        }
        if (!$('#travel_start_date').val() || !$('#travel_end_date').val()) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Start and end dates are required.', timer: 2000, showConfirmButton: false });
            return;
        }

        // Collect per-day rows
        const days = [];
        let hasTimeError = false;
        $('#daysBody tr').each(function () {
            const ts = $(this).find('.day-ts').val();
            const te = $(this).find('.day-te').val();
            if (!ts || !te) { hasTimeError = true; return false; }
            if (te <= ts)   { hasTimeError = true; return false; }
            days.push({ date: $(this).data('date'), time_start: ts, time_end: te });
        });

        if (hasTimeError) {
            Swal.fire({ icon: 'warning', title: 'Invalid Time', text: 'Each day must have a valid start and end time. End time must be after start time.', timer: 2500, showConfirmButton: false });
            return;
        }
        if (days.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Please select valid dates to set the daily schedule.', timer: 2000, showConfirmButton: false });
            return;
        }

        const payload = {
            request_type: $('#travel_type').val(),
            purpose:      $('#travel_purpose').val().trim(),
            start_date:   $('#travel_start_date').val(),
            end_date:     $('#travel_end_date').val(),
            notes:        $('#travel_notes').val().trim(),
        };

        // Always send employees as array (both add and update)
        empIds.forEach(function (eid, i) {
            payload['employee_id[' + i + ']'] = eid;
        });

        days.forEach(function (d, i) {
            payload['days[' + i + '][date]']       = d.date;
            payload['days[' + i + '][time_start]'] = d.time_start;
            payload['days[' + i + '][time_end]']   = d.time_end;
        });

        $.post(url, payload, function () {
            $('#travelModal').modal('hide');
            table.ajax.reload();
            const saved = id ? 'Record updated successfully.' : 'Request saved with ' + empIds.length + ' employee(s).';
            Swal.fire({ icon: 'success', title: 'Saved', text: saved, timer: 1800, showConfirmButton: false });
        }).fail(function (xhr) {
            const msg = xhr.responseJSON?.message ?? 'Something went wrong.';
            Swal.fire({ icon: 'error', title: 'Error', text: msg });
        });
    });

    // ── Delete ────────────────────────────────────────────────────────────
    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete this record?',
            text: 'The request and all its daily schedule entries will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (r.isConfirmed) {
                $.ajax({ url: '/travel-requests/delete/' + id, type: 'DELETE' })
                    .done(function () {
                        table.ajax.reload();
                        Swal.fire({ icon: 'success', title: 'Deleted', timer: 1500, showConfirmButton: false });
                    });
            }
        });
    });

    // ── Reset flatpickr maxDate when modal hidden ─────────────────────────
    $('#travelModal').on('hidden.bs.modal', function () {
        fpStart.set('maxDate', null);
        fpEnd.set('minDate', null);
    });
});
</script>
@endsection
