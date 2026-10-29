@extends("../layout")
@section("additional_head")
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <link href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <style>
        .tracking-tight  { letter-spacing: -0.5px; }
        .tracking-wider  { letter-spacing: 0.5px; }
        .font-12 { font-size: 12px; }
        .font-13 { font-size: 13px; }
        .flatpickr-calendar { z-index: 9999 !important; }
        .tbtn { width:24px; height:24px; }
    </style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">System Logs</h3>
            <p class="text-muted small mb-0">Track and review all system activities and user actions.</p>
        </div>
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active text-primary" aria-current="page">System Logs</li>
            </ol>
        </nav>
    </div>

    {{-- FILTER --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <form id="filterForm" method="post" action="{{ route('system.logs_filter') }}">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="filter_date" class="form-label text-muted small fw-bold text-uppercase mb-0">Date</label>
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox"
                                           id="filter_all_dates" name="all_dates" value="1"
                                           {{ !empty($filtered_data['all_dates']) ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted" for="filter_all_dates">All dates</label>
                                </div>
                            </div>
                            <input type="text"
                                   id="filter_date"
                                   name="log_date"
                                   class="form-control form-control-sm"
                                   value="{{ $filtered_data['log_date'] ?? now()->format('Y-m-d') }}"
                                   placeholder="Select date"
                                   {{ !empty($filtered_data['all_dates']) ? 'disabled' : '' }} />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">User</label>
                            <select id="filter_user" name="user_id" class="form-select form-select-sm" autocomplete="off">
                                <option value="All" {{ ($filtered_data['user'] ?? '') == 'All' ? 'selected' : '' }}>All</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ ($filtered_data['user'] ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Activity</label>
                            <select id="filter_action" name="action" class="form-select form-select-sm" autocomplete="off">
                                <option value="All" {{ ($filtered_data['action'] ?? '') == 'All' ? 'selected' : '' }}>All</option>
                                @foreach ($actions as $action)
                                    <option value="{{ $action->action }}" {{ ($filtered_data['action'] ?? '') == $action->action ? 'selected' : '' }}>
                                        {{ $action->action }}
                                    </option>
                                @endforeach
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
                                <th class="border-0 ps-4 py-3">Date & Time</th>
                                <th class="border-0 py-3">User</th>
                                <th class="border-0 py-3">Activity</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                            <tr>
                                <td class="ps-4">{{ $log->log_date }}</td>
                                <td class="fw-semibold">{{ $log->user_name }}</td>
                                <td class="text-muted">{{ $log->action }}</td>
                                <td class="pe-4 text-end">
                                    <button type="button"
                                            class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn view-log-btn"
                                            title="View"
                                            data-date="{{ $log->log_date }}"
                                            data-user="{{ $log->user_name }}"
                                            data-action="{{ $log->action }}"
                                            data-message="{{ $log->message }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- LOG DETAILS MODAL --}}
<div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="logDetailsModalLabel">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-sm-4">
                        <div class="text-muted small fw-bold text-uppercase">Date &amp; Time</div>
                        <div id="logModalDate" class="fw-semibold">—</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small fw-bold text-uppercase">User</div>
                        <div id="logModalUser" class="fw-semibold">—</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small fw-bold text-uppercase">Activity</div>
                        <div id="logModalAction" class="fw-semibold">—</div>
                    </div>
                </div>

                <div class="text-muted small fw-bold text-uppercase mb-1">Details</div>

                {{-- Human-readable key/value view when the message is JSON --}}
                <div class="table-responsive" id="logModalTableWrap" hidden>
                    <table class="table table-sm table-striped align-middle mb-0">
                        <tbody id="logModalTableBody"></tbody>
                    </table>
                </div>

                {{-- Fallback raw view (plain text, or JSON we can't parse) --}}
                <pre id="logModalRaw" class="bg-light border rounded p-3 mb-0 font-13"
                     style="white-space:pre-wrap;word-break:break-word;max-height:45vh;overflow:auto;" hidden></pre>

                <div id="logModalEmpty" class="text-muted fst-italic" hidden>No additional details were recorded for this entry.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("additional_footer")
<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/flatpickr/custom-flatpickr.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script>
document.querySelectorAll('.btn[title], a.btn[title]').forEach(function(el) {
    new bootstrap.Tooltip(el, { trigger: 'hover' });
});

document.addEventListener('DOMContentLoaded', function () {
    const datePicker = flatpickr('#filter_date', {
        dateFormat: 'Y-m-d',
        onChange: function() {
            if (!document.getElementById('filter_all_dates').checked) {
                document.getElementById('filterForm').submit();
            }
        }
    });

    /* "All dates" checkbox — disable the date field when ticked and reload
       the list with no date filter. */
    const allDatesCb = document.getElementById('filter_all_dates');
    const dateInput  = document.getElementById('filter_date');

    function syncAllDates(submit) {
        const on = allDatesCb.checked;
        dateInput.disabled = on;
        if (on && datePicker) datePicker.close();
        if (submit) document.getElementById('filterForm').submit();
    }

    allDatesCb.addEventListener('change', function () { syncAllDates(true); });
    syncAllDates(false); // keep the field state in sync on first load

    const tomOpts = {
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        dropdownParent: 'body',
        onChange: function() { document.getElementById('filterForm').submit(); }
    };
    new TomSelect('#filter_user',   tomOpts);
    new TomSelect('#filter_action', tomOpts);

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
});

/* =========================================================
   LOG DETAILS — "View" button
   Delegated so it keeps working after DataTables re-draws.
   ========================================================= */
(function () {
    var modalEl = document.getElementById('logDetailsModal');
    if (!modalEl) return;
    var modal = new bootstrap.Modal(modalEl);

    function prettyLabel(key) {
        return String(key)
            .replace(/[_\-]+/g, ' ')
            .replace(/\b\w/g, function (c) { return c.toUpperCase(); });
    }

    function renderValue(val) {
        if (val === null || val === '') return '—';
        if (typeof val === 'object') return JSON.stringify(val);
        return String(val);
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.view-log-btn');
        if (!btn) return;

        document.getElementById('logModalDate').textContent   = btn.dataset.date   || '—';
        document.getElementById('logModalUser').textContent    = btn.dataset.user   || '—';
        document.getElementById('logModalAction').textContent  = btn.dataset.action || '—';

        var wrap  = document.getElementById('logModalTableWrap');
        var body  = document.getElementById('logModalTableBody');
        var raw   = document.getElementById('logModalRaw');
        var empty = document.getElementById('logModalEmpty');
        wrap.hidden = raw.hidden = empty.hidden = true;
        body.innerHTML = '';
        raw.textContent = '';

        var msg = (btn.dataset.message || '').trim();

        if (!msg || msg === '{}' || msg === '[]' || msg === 'null') {
            empty.hidden = false;
            modal.show();
            return;
        }

        var parsed = null;
        try { parsed = JSON.parse(msg); } catch (err) { parsed = null; }

        if (parsed && typeof parsed === 'object' && !Array.isArray(parsed) && Object.keys(parsed).length) {
            Object.keys(parsed).forEach(function (key) {
                var tr = document.createElement('tr');
                var th = document.createElement('th');
                th.className = 'text-muted fw-semibold ps-0';
                th.style.width = '38%';
                th.textContent = prettyLabel(key);
                var td = document.createElement('td');
                td.style.wordBreak = 'break-word';
                td.textContent = renderValue(parsed[key]);
                tr.appendChild(th);
                tr.appendChild(td);
                body.appendChild(tr);
            });
            wrap.hidden = false;
        } else {
            raw.textContent = parsed ? JSON.stringify(parsed, null, 2) : msg;
            raw.hidden = false;
        }

        modal.show();
    });
})();
</script>
@endsection
