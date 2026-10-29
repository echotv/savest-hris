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

    #evalTable .eval-remarks { max-width: 260px; white-space: normal; word-break: break-word; }

    .rating-badge { font-size: 11px; font-weight: 600; padding: .2rem .55rem; border-radius: 999px; display: inline-block; white-space: nowrap; }
    .rating-outstanding      { background: rgba(0,171,85,.12);  color: #00ab55; }
    .rating-very-satisfactory{ background: rgba(27,85,226,.12); color: #1b55e2; }
    .rating-satisfactory     { background: rgba(0,181,181,.12); color: #00b5b5; }
    .rating-fair             { background: rgba(226,160,63,.14);color: #b5790a; }
    .rating-poor             { background: rgba(231,81,90,.12); color: #e7515a; }
    .rating-default          { background: rgba(136,142,168,.14); color: #888ea8; }
</style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Performance Evaluation</h3>
            <p class="text-muted small mb-0">Record employee performance evaluation results and keep the link to each evaluation file in Google Drive.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Performance Evaluation</li>
                </ol>
            </nav>
            @if(userCan('ADD PERFORMANCE EVALUATIONS'))
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#evalModal" id="addNewBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Evaluation
            </button>
            @endif
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">

                    <div class="col-lg-2">
                        <label class="form-label text-muted small fw-bold text-uppercase">Year</label>
                        <select id="filter_year">
                            <option value="">All Years</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Employee</label>
                        <select id="filter_employee">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">
                                    {{ trim($emp->lastname . ', ' . $emp->firstname) }} ({{ $emp->employee_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Evaluation Type</label>
                        <select id="filter_type">
                            <option value="">All Types</option>
                            @foreach($types as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Rating</label>
                        <select id="filter_rating">
                            <option value="">All Ratings</option>
                            @foreach($ratings as $r)
                                <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
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
                    <table id="evalTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Evaluation Date</th>
                                <th class="border-0 py-3">Employee</th>
                                <th class="border-0 py-3">Period / Type</th>
                                <th class="border-0 py-3">Score / Rating</th>
                                <th class="border-0 py-3">File(s)</th>
                                <th class="border-0 pe-4 py-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="dt-loading-row">
                                <td colspan="6" class="text-center py-4 text-muted border-0">
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

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="evalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="evalModalTitle">New Evaluation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="eval_id">

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Employee <span class="text-danger">*</span></label>
                        <select id="eval_employee_id">
                            <option value=""></option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">
                                    {{ trim($emp->firstname . ' ' . $emp->lastname) }} ({{ $emp->employee_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-secondary small">Evaluation Period <span class="text-danger">*</span></label>
                        <input type="text" id="eval_period" class="form-control form-control-sm" maxlength="100" placeholder="e.g. Jan – Jun 2026">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-secondary small">Evaluation Type <span class="text-danger">*</span></label>
                        <select id="eval_type">
                            <option value=""></option>
                            @foreach($types as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium text-secondary small">Evaluation Date <span class="text-danger">*</span></label>
                        <input type="text" id="eval_date" class="form-control form-control-sm" placeholder="Select date…" autocomplete="off">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium text-secondary small">Overall Score <span class="text-danger">*</span></label>
                        <input type="number" id="eval_score" class="form-control form-control-sm" min="0" max="100" step="0.01" placeholder="0 – 100">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium text-secondary small">Overall Rating <span class="text-danger">*</span></label>
                        <select id="eval_rating">
                            <option value=""></option>
                            @foreach($ratings as $r)
                                <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Remarks</label>
                        <textarea id="eval_remarks" class="form-control form-control-sm" rows="3" maxlength="2000" placeholder="Optional notes about this evaluation…"></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small d-flex justify-content-between align-items-center">
                            <span>Google Drive File Link(s) <span class="text-danger">*</span></span>
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 font-12" id="addLinkBtn">+ Add another link</button>
                        </label>
                        <div id="linksWrap" class="d-flex flex-column gap-2"></div>
                        <div class="form-text">Paste the shareable link of the evaluation file (drive.google.com or docs.google.com).</div>
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

    let table; // declared early so filter callbacks can reference it
    const GDRIVE_RE = /^https?:\/\/(drive|docs)\.google\.com\//i;

    function warn(text) {
        Swal.fire({ icon: 'warning', title: 'Required', text: text, timer: 2600, showConfirmButton: false });
    }
    function errorMessage(xhr) {
        const errs = xhr.responseJSON && xhr.responseJSON.errors;
        if (errs) { const first = Object.values(errs)[0]; if (first && first[0]) return first[0]; }
        return (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong.';
    }
    function ratingClass(rating) {
        const slug = String(rating || '').toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        const known = ['outstanding', 'very-satisfactory', 'satisfactory', 'fair', 'poor'];
        return 'rating-' + (known.includes(slug) ? slug : 'default');
    }

    // ── Filters ──────────────────────────────────────────────────────────
    const empFilterTs = new TomSelect('#filter_employee', {
        allowEmptyOption: true,
        onChange: () => table && table.ajax.reload()
    });
    empFilterTs.wrapper.classList.add('filter-ts');

    const typeFilterTs = new TomSelect('#filter_type', {
        allowEmptyOption: true,
        create: false,
        onChange: () => table && table.ajax.reload()
    });
    typeFilterTs.wrapper.classList.add('filter-ts');

    const ratingFilterTs = new TomSelect('#filter_rating', {
        allowEmptyOption: true,
        create: false,
        onChange: () => table && table.ajax.reload()
    });
    ratingFilterTs.wrapper.classList.add('filter-ts');

    const yearFilterTs = new TomSelect('#filter_year', {
        allowEmptyOption: true,
        create: false,
        onChange: () => table && table.ajax.reload()
    });
    yearFilterTs.wrapper.classList.add('filter-ts');
    // Default to the current year, if it's one of the available options.
    const currentYear = String(new Date().getFullYear());
    if (yearFilterTs.options[currentYear]) yearFilterTs.setValue(currentYear, true);

    // ── Modal controls ───────────────────────────────────────────────────
    const empTs = new TomSelect('#eval_employee_id', {
        maxItems: 1,
        placeholder: 'Search and select an employee…'
    });

    const typeTs = new TomSelect('#eval_type', {
        maxItems: 1,
        create: true,
        placeholder: 'Select or type a type…'
    });

    const ratingTs = new TomSelect('#eval_rating', {
        maxItems: 1,
        create: true,
        placeholder: 'Select or type a rating…'
    });

    const fpDate = flatpickr('#eval_date', { dateFormat: 'Y-m-d' });

    function addLinkRow(value) {
        const row = $(
            '<div class="input-group input-group-sm link-row">' +
            '<span class="input-group-text">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>' +
            '</span>' +
            '<input type="url" class="form-control eval-link" maxlength="500" placeholder="https://drive.google.com/file/d/…">' +
            '<button type="button" class="btn btn-outline-danger removeLinkBtn" title="Remove link">&times;</button>' +
            '</div>'
        );
        row.find('.eval-link').val(value || '');
        $('#linksWrap').append(row);
        syncRemoveButtons();
    }
    function syncRemoveButtons() {
        const rows = $('#linksWrap .link-row');
        rows.find('.removeLinkBtn').prop('disabled', rows.length <= 1);
    }
    $('#addLinkBtn').on('click', function () { addLinkRow(''); $('#linksWrap .eval-link').last().focus(); });
    $(document).on('click', '.removeLinkBtn', function () {
        $(this).closest('.link-row').remove();
        syncRemoveButtons();
    });

    function resetModal() {
        $('#eval_id').val('');
        $('#eval_period').val('');
        $('#eval_score').val('');
        $('#eval_remarks').val('');
        fpDate.clear();
        empTs.clear(true);
        typeTs.clear(true);
        ratingTs.clear(true);
        $('#linksWrap').empty();
        addLinkRow('');
    }

    // ── DataTable ────────────────────────────────────────────────────────
    table = $('#evalTable').DataTable({
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
            sLengthMenu: "Display: _MENU_",
            sEmptyTable: "No evaluations found for the selected filters."
        },
        stripeClasses: [],
        lengthMenu: [10, 20, 50],
        pageLength: 10,
        ordering: false,
        searching: false,
        processing: false,
        serverSide: true,
        ajax: {
            url: "{{ route('evaluations.data') }}",
            data: function (d) {
                d.year_filter     = yearFilterTs.getValue();
                d.employee_filter = empFilterTs.getValue();
                d.type_filter     = typeFilterTs.getValue();
                d.rating_filter   = ratingFilterTs.getValue();
            }
        },
        columns: [
            { data: 'evaluation_date_fmt', className: 'ps-4 text-nowrap' },
            {
                data: 'employee_info',
                render: function (d) {
                    if (!d) return '<span class="text-muted">—</span>';
                    const p = d.split('|');
                    return '<div class="fw-semibold font-13">' + p[0] + '</div>' +
                           '<div class="text-muted font-12">' + p[1] + ' &bull; ' + p[2] + '</div>';
                }
            },
            {
                data: null,
                render: function (row) {
                    return '<div class="fw-semibold font-13">' + row.evaluation_period + '</div>' +
                           '<div class="text-muted font-12">' + row.evaluation_type + '</div>';
                }
            },
            {
                data: null,
                render: function (row) {
                    return '<div class="fw-semibold font-13">' + row.score_fmt + '</div>' +
                           '<span class="rating-badge ' + ratingClass(row.overall_rating) + '">' + (row.overall_rating || '—') + '</span>';
                }
            },
            { data: 'files_html', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#evalTable').on('processing.dt', function (e, settings, processing) {
        if (processing) {
            var el = this;
            setTimeout(function () {
                $(el).find('tbody').html(
                    '<tr class="dt-loading-row"><td colspan="6" class="text-center py-4 text-muted border-0">' +
                    '<div class="d-flex align-items-center justify-content-center gap-2">' +
                    '<div class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></div>' +
                    '<span class="font-13 fw-semibold">Processing...</span>' +
                    '</div></td></tr>'
                );
            }, 0);
        }
    });

    // ── Open — Add ───────────────────────────────────────────────────────
    $('#addNewBtn').on('click', function () {
        resetModal();
        $('#evalModalTitle').text('New Evaluation');
        fpDate.setDate(new Date(), false);
    });

    // ── Open — Edit ──────────────────────────────────────────────────────
    $(document).on('click', '.editBtn', function () {
        const btn = $(this);
        resetModal();
        $('#eval_id').val(btn.data('id'));
        empTs.addItem(String(btn.data('employee_id')), true);
        $('#eval_period').val(btn.attr('data-period'));
        typeTs.addItem(btn.attr('data-type'), true);
        fpDate.setDate(btn.attr('data-date'), false);
        $('#eval_score').val(btn.attr('data-score'));
        ratingTs.addItem(btn.attr('data-rating'), true);
        $('#eval_remarks').val(btn.attr('data-remarks'));

        let files = [];
        try { files = JSON.parse(btn.attr('data-files') || '[]'); } catch (e) {}
        $('#linksWrap').empty();
        (files.length ? files : ['']).forEach(addLinkRow);

        $('#evalModalTitle').text('Edit Evaluation');
        $('#evalModal').modal('show');
    });

    // ── Save ─────────────────────────────────────────────────────────────
    $('#saveBtn').on('click', function () {
        const id       = $('#eval_id').val();
        const url      = id ? '/performance-evaluations/update/' + id : '/performance-evaluations/store';
        const empId    = empTs.getValue();
        const period    = $('#eval_period').val().trim();
        const type      = typeTs.getValue();
        const date      = $('#eval_date').val();
        const score     = $('#eval_score').val();
        const rating    = ratingTs.getValue();
        const remarks   = $('#eval_remarks').val().trim();

        if (!empId)   return warn('Please select an employee.');
        if (!period)  return warn('Evaluation period is required.');
        if (!type)    return warn('Evaluation type is required.');
        if (!date)    return warn('Evaluation date is required.');
        if (score === '' || score === null) return warn('Overall score is required.');
        if (Number(score) < 0 || Number(score) > 100) return warn('Overall score must be between 0 and 100.');
        if (!rating)  return warn('Overall rating is required.');

        const links = [];
        let bad = null;
        $('#linksWrap .eval-link').each(function () {
            const v = $(this).val().trim();
            if (!v) return;
            if (!GDRIVE_RE.test(v)) { bad = v; return false; }
            links.push(v);
        });
        if (bad)                return warn('"' + bad + '" is not a Google Drive / Google Docs link.');
        if (links.length === 0) return warn('Please add at least one Google Drive file link.');
        if (new Set(links).size !== links.length) return warn('The same file link was entered more than once.');

        const payload = {
            employee_id: empId, evaluation_period: period, evaluation_type: type,
            evaluation_date: date, overall_score: score, overall_rating: rating, remarks: remarks
        };
        links.forEach(function (l, i) { payload['file_urls[' + i + ']'] = l; });

        const btn = $('#saveBtn').prop('disabled', true);
        $.post(url, payload, function () {
            $('#evalModal').modal('hide');

            // keep the Type / Rating filters in sync with newly-used values
            if (!typeFilterTs.options[type])     typeFilterTs.addOption({ value: type, text: type });
            if (!ratingFilterTs.options[rating]) ratingFilterTs.addOption({ value: rating, text: rating });

            table.ajax.reload();
            Swal.fire({ icon: 'success', title: 'Saved', text: id ? 'Evaluation updated successfully.' : 'Evaluation saved successfully.', timer: 1800, showConfirmButton: false });
        }).fail(function (xhr) {
            Swal.fire({ icon: 'error', title: 'Error', text: errorMessage(xhr) });
        }).always(function () { btn.prop('disabled', false); });
    });

    // ── Delete ───────────────────────────────────────────────────────────
    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete this evaluation?',
            text: 'The evaluation record will be removed from the list. The file in Google Drive is not affected.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (!r.isConfirmed) return;
            $.ajax({ url: '/performance-evaluations/delete/' + id, type: 'DELETE' })
                .done(function () {
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Deleted', timer: 1500, showConfirmButton: false });
                })
                .fail(function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Error', text: errorMessage(xhr) });
                });
        });
    });
});
</script>
@endsection
