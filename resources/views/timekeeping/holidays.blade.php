@extends("../layout")

@section("additional_head")
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">

<link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}">

<style>
    .tracking-tight  { letter-spacing: -0.5px; }
    .tracking-wider  { letter-spacing: 0.5px; }
    .font-12 { font-size: 12px; }
    .font-13 { font-size: 13px; }
    .flatpickr-calendar { z-index: 99999 !important; }

    /* ── Branch checkboxes ────────────────────────────────────── */
    .branch-check-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 6px;
        max-height: 180px;
        overflow-y: auto;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
    }
    body.dark .branch-check-grid {
        background: #0e1726;
        border-color: #1b2e4b;
    }
    .branch-check-item {
        display: flex; align-items: center; gap: 7px;
        padding: 5px 8px; border-radius: 6px; cursor: pointer;
        transition: background .12s;
        user-select: none;
    }
    .branch-check-item:hover { background: rgba(67,97,238,.07); }
    .branch-check-item input[type="checkbox"] { width: 14px; height: 14px; cursor: pointer; flex-shrink: 0; }
    .branch-check-item label { font-size: 12px; font-weight: 500; cursor: pointer; margin: 0; line-height: 1.3; }
    body.dark .branch-check-item label { color: #bfc9d4; }

    .branch-select-all-row {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 6px;
    }
</style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Holidays & Non-Working Days</h3>
            <p class="text-muted small mb-0">Manage regular holidays, special non-working days, and company-declared rest days.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Holidays</li>
                </ol>
            </nav>
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#holidayModal" id="addNewBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Holiday
            </button>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Type</label>
                        <select id="filter_type" class="form-select form-select-sm rounded">
                            <option value="ALL">All Types</option>
                            <option value="Regular Holiday">Regular Holiday</option>
                            <option value="Special Non-Working">Special Non-Working</option>
                            <option value="Company">Company</option>
                            <option value="Government">Government</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Year</label>
                        <select id="filter_year" class="form-select form-select-sm rounded">
                            <option value="ALL">All Years</option>
                            @for($y = now()->year + 1; $y >= now()->year - 2; $y--)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Search</label>
                        <input type="text" id="filter_search" class="form-control form-control-sm" placeholder="Search holiday name...">
                    </div>
                    <div class="col-md-2">
                        <button id="clearFilters" class="btn btn-sm btn-light-secondary w-100">Clear</button>
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
                    <table id="holidayTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Holiday / Day</th>
                                <th class="border-0 py-3">Date</th>
                                <th class="border-0 py-3">Type</th>
                                <th class="border-0 py-3">Duration</th>
                                <th class="border-0 py-3">Recurring</th>
                                <th class="border-0 py-3">Applied Branches</th>
                                <th class="border-0 py-3">Description</th>
                                <th class="border-0 pe-4 py-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="dt-loading-row">
                                <td colspan="8" class="text-center py-4 text-muted border-0">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
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
<div class="modal fade" id="holidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="holidayModalTitle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    New Holiday
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="holiday_id">

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Holiday / Day Name <span class="text-danger">*</span></label>
                        <input type="text" id="holiday_name" class="form-control form-control-sm"
                               placeholder="e.g. Christmas Day, Typhoon...">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-secondary small">Date <span class="text-danger">*</span></label>
                        <input type="text" id="holiday_date" class="form-control form-control-sm flatpickr-input"
                               placeholder="Select date...">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-secondary small">Type <span class="text-danger">*</span></label>
                        <select id="holiday_type" class="form-select form-select-sm">
                            <option value="Regular Holiday">Regular Holiday</option>
                            <option value="Special Non-Working">Special Non-Working</option>
                            <option value="Company">Company</option>
                            <option value="Government">Government</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small d-block mb-1">Coverage <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-4">
                            <div class="form-check">
                                <input type="radio" name="day_coverage" id="coverage_whole" class="form-check-input" value="whole" checked>
                                <label class="form-check-label small" for="coverage_whole">Whole Day</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="day_coverage" id="coverage_range" class="form-check-input" value="range">
                                <label class="form-check-label small" for="coverage_range">Specific Time Range</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 time-range-fields d-none">
                        <label class="form-label fw-medium text-secondary small">Time Start <span class="text-danger">*</span></label>
                        <input type="text" id="time_start" class="form-control form-control-sm flatpickr-input" placeholder="Select time...">
                    </div>

                    <div class="col-md-6 time-range-fields d-none">
                        <label class="form-label fw-medium text-secondary small">Time End <span class="text-danger">*</span></label>
                        <input type="text" id="time_end" class="form-control form-control-sm flatpickr-input" placeholder="Select time...">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Description <span class="text-muted">(optional)</span></label>
                        <textarea id="description" class="form-control form-control-sm" rows="2"
                                  placeholder="Additional notes..."></textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" id="is_recurring" class="form-check-input">
                            <label class="form-check-label text-dark small" for="is_recurring">
                                Recurring annually (same date every year)
                            </label>
                        </div>
                    </div>

                    {{-- Branch checkboxes --}}
                    <div class="col-12">
                        <div class="branch-select-all-row">
                            <label class="form-label fw-medium text-secondary small mb-0">
                                Applied to Branches
                                <span class="text-muted fw-normal">(leave empty = applies to all)</span>
                            </label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" id="selectAllBranches" class="form-check-input m-0" style="width:14px;height:14px;">
                                <label for="selectAllBranches" class="small fw-semibold mb-0" style="cursor:pointer;">Select All</label>
                            </div>
                        </div>
                        <div class="branch-check-grid" id="branchCheckGrid">
                            @foreach($branches as $branch)
                                <div class="branch-check-item">
                                    <input type="checkbox"
                                           class="branch-checkbox"
                                           id="br_{{ $branch->branch_code }}"
                                           value="{{ $branch->branch_code }}">
                                    <label for="br_{{ $branch->branch_code }}">{{ $branch->branch_name }}</label>
                                </div>
                            @endforeach
                        </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const datePicker = flatpickr('#holiday_date', { dateFormat: 'Y-m-d' });
    const timeStartPicker = flatpickr('#time_start', { enableTime: true, noCalendar: true, dateFormat: 'H:i', altInput: true, altFormat: 'h:i K' });
    const timeEndPicker   = flatpickr('#time_end',   { enableTime: true, noCalendar: true, dateFormat: 'H:i', altInput: true, altFormat: 'h:i K' });

    // ── Whole day / time range toggle ─────────────────────────
    function applyCoverageVisibility() {
        const isRange = $('#coverage_range').is(':checked');
        $('.time-range-fields').toggleClass('d-none', !isRange);
    }
    $('input[name="day_coverage"]').on('change', applyCoverageVisibility);

    // ── DataTable ──────────────────────────────────────────────
    let table = $('#holidayTable').DataTable({
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
                sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>',
                sNext:     '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>'
            },
            sInfo: "Showing entries _START_ to _END_ of _TOTAL_",
            sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
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
            url: "{{ route('holidays.data') }}",
            data: function (d) {
                d.type         = $('#filter_type').val();
                d.year         = $('#filter_year').val();
                d.search_value = $('#filter_search').val();
            }
        },
        columns: [
            { data: 'holiday_name' },
            { data: 'holiday_date_fmt' },
            { data: 'holiday_type_badge',  orderable: false },
            { data: 'duration_display',    orderable: false },
            { data: 'is_recurring_badge',  orderable: false },
            { data: 'branches_display',    orderable: false },
            { data: 'description', render: d => d ? `<span class="text-muted font-13">${d}</span>` : '<span class="text-muted opacity-50">—</span>' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#holidayTable').on('processing.dt', function (e, settings, processing) {
        if (processing) {
            var el = this;
            setTimeout(function () {
                $(el).find('tbody').html(
                    '<tr class="dt-loading-row"><td colspan="8" class="text-center py-4 text-muted border-0">' +
                    '<div class="d-flex align-items-center justify-content-center gap-2">' +
                    '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                    '<span class="font-13 fw-semibold">Processing...</span>' +
                    '</div></td></tr>'
                );
            }, 0);
        }
    });

    // ── Filters ────────────────────────────────────────────────
    $('#filter_type, #filter_year').on('change', () => table.ajax.reload());
    $('#filter_search').on('keyup', () => table.ajax.reload());
    $('#clearFilters').on('click', function () {
        $('#filter_type').val('ALL');
        $('#filter_year').val('{{ now()->year }}');
        $('#filter_search').val('');
        table.ajax.reload();
    });

    // ── Select All branches ────────────────────────────────────
    $('#selectAllBranches').on('change', function () {
        $('.branch-checkbox').prop('checked', this.checked);
    });

    // Keep "Select All" in sync when individual boxes change
    $(document).on('change', '.branch-checkbox', function () {
        var total   = $('.branch-checkbox').length;
        var checked = $('.branch-checkbox:checked').length;
        $('#selectAllBranches').prop('checked', total === checked)
                               .prop('indeterminate', checked > 0 && checked < total);
    });

    // ── Helper: reset branch checkboxes ───────────────────────
    function resetBranches(selectedCodes) {
        selectedCodes = selectedCodes || [];
        $('.branch-checkbox').each(function () {
            $(this).prop('checked', selectedCodes.includes(this.value));
        });
        var total   = $('.branch-checkbox').length;
        var checked = $('.branch-checkbox:checked').length;
        $('#selectAllBranches')
            .prop('checked', total > 0 && total === checked)
            .prop('indeterminate', checked > 0 && checked < total);
    }

    // ── Open modal — Add ───────────────────────────────────────
    $('#addNewBtn').on('click', function () {
        $('#holiday_id').val('');
        $('#holiday_name').val('');
        datePicker.clear();
        $('#holiday_type').val('Regular Holiday');
        $('#coverage_whole').prop('checked', true);
        timeStartPicker.clear();
        timeEndPicker.clear();
        applyCoverageVisibility();
        $('#description').val('');
        $('#is_recurring').prop('checked', false);
        resetBranches([]);
        $('#holidayModalTitle').html(`
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            New Holiday`);
    });

    // ── Open modal — Edit ──────────────────────────────────────
    $(document).on('click', '.editBtn', function () {
        const btn = $(this);
        $('#holiday_id').val(btn.data('id'));
        $('#holiday_name').val(btn.data('name'));
        datePicker.setDate(btn.data('date'));
        $('#holiday_type').val(btn.data('type'));

        const isWholeDay = btn.data('whole-day') == 1;
        $(isWholeDay ? '#coverage_whole' : '#coverage_range').prop('checked', true);
        timeStartPicker.setDate(btn.data('time-start') || null);
        timeEndPicker.setDate(btn.data('time-end') || null);
        applyCoverageVisibility();

        $('#description').val(btn.data('desc'));
        $('#is_recurring').prop('checked', btn.data('recurring') == 1);

        var codes = [];
        try { codes = JSON.parse(btn.attr('data-branches')) || []; } catch(e) {}
        resetBranches(codes);

        $('#holidayModalTitle').html(`
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit Holiday`);
        $('#holidayModal').modal('show');
    });

    // ── Save ───────────────────────────────────────────────────
    $('#saveBtn').on('click', function () {
        const id  = $('#holiday_id').val();
        const url = id ? '/holidays/update/' + id : '/holidays/store';

        // Collect selected branch codes
        var selectedBranches = [];
        $('.branch-checkbox:checked').each(function () {
            selectedBranches.push(this.value);
        });

        const isWholeDay = $('#coverage_whole').is(':checked');

        var payload = {
            holiday_name: $('#holiday_name').val(),
            holiday_date: $('#holiday_date').val(),
            holiday_type: $('#holiday_type').val(),
            is_whole_day: isWholeDay ? 1 : 0,
            time_start:   isWholeDay ? '' : $('#time_start').val(),
            time_end:     isWholeDay ? '' : $('#time_end').val(),
            description:  $('#description').val(),
            is_recurring: $('#is_recurring').is(':checked') ? 1 : 0,
        };

        if (!isWholeDay && (!payload.time_start || !payload.time_end)) {
            Swal.fire({ icon: 'warning', title: 'Missing time range', text: 'Please set both a start and end time.' });
            return;
        }

        // Append branch array
        selectedBranches.forEach(function (code, i) {
            payload['branches[' + i + ']'] = code;
        });

        $.post(url, payload, function () {
            $('#holidayModal').modal('hide');
            table.ajax.reload();
            Swal.fire({ icon: 'success', title: 'Saved', text: 'Holiday saved successfully.', timer: 1800, showConfirmButton: false });
        }).fail(function (xhr) {
            const msg = xhr.responseJSON?.message ?? 'Something went wrong.';
            Swal.fire({ icon: 'error', title: 'Error', text: msg });
        });
    });

    // ── Delete ─────────────────────────────────────────────────
    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete this holiday?',
            text: 'It will be removed from the list.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/holidays/delete/' + id,
                    type: 'DELETE',
                    success: function () {
                        table.ajax.reload();
                        Swal.fire({ icon: 'success', title: 'Deleted', timer: 1500, showConfirmButton: false });
                    }
                });
            }
        });
    });

});
</script>
@endsection
