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

    #memoTable .memo-subject { max-width: 340px; white-space: normal; word-break: break-word; }
</style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Employee Memos</h3>
            <p class="text-muted small mb-0">Record memos issued to employees and keep the link to each memo file in Google Drive.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Employee Memos</li>
                </ol>
            </nav>
            @if(userCan('ADD EMPLOYEE MEMOS'))
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#memoModal" id="addNewBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Memo
            </button>
            @endif
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">

                    <div class="col-lg-5">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-0">Date Issued</label>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="filter_all_dates">
                                <label class="form-check-label small text-muted" for="filter_all_dates">All dates</label>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="text" id="filter_date_from" class="form-control form-control-sm rounded" placeholder="From…" autocomplete="off">
                            </div>
                            <div class="col-6">
                                <input type="text" id="filter_date_to" class="form-control form-control-sm rounded" placeholder="To…" autocomplete="off">
                            </div>
                        </div>
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
                        <label class="form-label text-muted small fw-bold text-uppercase">Subject</label>
                        <select id="filter_subject">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
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
                    <table id="memoTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Date Issued</th>
                                <th class="border-0 py-3">Subject</th>
                                <th class="border-0 py-3">Employee(s)</th>
                                <th class="border-0 py-3">File(s)</th>
                                <th class="border-0 pe-4 py-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="dt-loading-row">
                                <td colspan="5" class="text-center py-4 text-muted border-0">
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
<div class="modal fade" id="memoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="memoModalTitle">New Memo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="memo_id">

                <div class="row g-3">

                    <div class="col-md-8">
                        <label class="form-label fw-medium text-secondary small">Subject <span class="text-danger">*</span></label>
                        <input type="text" id="memo_subject" class="form-control form-control-sm" maxlength="255" placeholder="e.g. Notice on Attendance Policy">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium text-secondary small">Date Issued <span class="text-danger">*</span></label>
                        <input type="text" id="memo_date_issued" class="form-control form-control-sm" placeholder="Select date…" autocomplete="off">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Employee(s) <span class="text-danger">*</span></label>
                        <select id="memo_employee_id" class="form-select form-select-sm rounded" multiple>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">
                                    {{ trim($emp->firstname . ' ' . $emp->lastname) }} ({{ $emp->employee_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small d-flex justify-content-between align-items-center">
                            <span>Google Drive File Link(s) <span class="text-danger">*</span></span>
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 font-12" id="addLinkBtn">+ Add another link</button>
                        </label>
                        <div id="linksWrap" class="d-flex flex-column gap-2"></div>
                        <div class="form-text">Paste the shareable link of the memo file (drive.google.com or docs.google.com).</div>
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

    // ── Filters ──────────────────────────────────────────────────────────
    const empFilterTs = new TomSelect('#filter_employee', {
        allowEmptyOption: true,
        onChange: () => table && table.ajax.reload()
    });
    empFilterTs.wrapper.classList.add('filter-ts');

    const subjectFilterTs = new TomSelect('#filter_subject', {
        allowEmptyOption: true,
        create: false,
        onChange: () => table && table.ajax.reload()
    });
    subjectFilterTs.wrapper.classList.add('filter-ts');

    // Date issued range — defaults to the last 2 weeks (14 days ago → today)
    const today    = new Date();
    const twoWeeks = new Date(today.getTime() - 14 * 86400000);

    const fpFrom = flatpickr('#filter_date_from', {
        dateFormat: 'Y-m-d',
        defaultDate: twoWeeks,
        onChange(sel) {
            fpTo.set('minDate', sel[0] || null);
            if (table) table.ajax.reload();
        }
    });
    const fpTo = flatpickr('#filter_date_to', {
        dateFormat: 'Y-m-d',
        defaultDate: today,
        onChange(sel) {
            fpFrom.set('maxDate', sel[0] || null);
            if (table) table.ajax.reload();
        }
    });

    // "All dates" checkbox — disables the range fields while ticked
    $('#filter_all_dates').on('change', function () {
        const all = this.checked;
        $('#filter_date_from, #filter_date_to').prop('disabled', all);
        if (all) { fpFrom.close(); fpTo.close(); }
        table && table.ajax.reload();
    });

    // ── Modal controls ───────────────────────────────────────────────────
    const empTs = new TomSelect('#memo_employee_id', {
        maxItems: null,
        placeholder: 'Search and select employee(s)…'
    });

    const fpIssued = flatpickr('#memo_date_issued', { dateFormat: 'Y-m-d' });

    function addLinkRow(value) {
        const row = $(
            '<div class="input-group input-group-sm link-row">' +
            '<span class="input-group-text">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>' +
            '</span>' +
            '<input type="url" class="form-control memo-link" maxlength="500" placeholder="https://drive.google.com/file/d/…">' +
            '<button type="button" class="btn btn-outline-danger removeLinkBtn" title="Remove link">&times;</button>' +
            '</div>'
        );
        row.find('.memo-link').val(value || '');
        $('#linksWrap').append(row);
        syncRemoveButtons();
    }
    function syncRemoveButtons() {
        const rows = $('#linksWrap .link-row');
        rows.find('.removeLinkBtn').prop('disabled', rows.length <= 1);
    }
    $('#addLinkBtn').on('click', function () { addLinkRow(''); $('#linksWrap .memo-link').last().focus(); });
    $(document).on('click', '.removeLinkBtn', function () {
        $(this).closest('.link-row').remove();
        syncRemoveButtons();
    });

    function resetModal() {
        $('#memo_id').val('');
        $('#memo_subject').val('');
        fpIssued.clear();
        empTs.clear(true);
        $('#linksWrap').empty();
        addLinkRow('');
    }

    // ── DataTable ────────────────────────────────────────────────────────
    table = $('#memoTable').DataTable({
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
            sEmptyTable: "No memos found for the selected filters."
        },
        stripeClasses: [],
        lengthMenu: [10, 20, 50],
        pageLength: 10,
        ordering: false,
        searching: false,
        processing: false,
        serverSide: true,
        ajax: {
            url: "{{ route('memos.data') }}",
            data: function (d) {
                d.all_dates       = $('#filter_all_dates').is(':checked') ? 1 : 0;
                d.date_from       = $('#filter_date_from').val();
                d.date_to         = $('#filter_date_to').val();
                d.employee_filter = empFilterTs.getValue();
                d.subject_filter  = subjectFilterTs.getValue();
            }
        },
        columns: [
            { data: 'date_issued_fmt', className: 'ps-4 text-nowrap' },
            {
                data: 'subject',
                render: function (d) { return '<div class="fw-semibold font-13 memo-subject">' + d + '</div>'; }
            },
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
            { data: 'files_html', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#memoTable').on('processing.dt', function (e, settings, processing) {
        if (processing) {
            var el = this;
            setTimeout(function () {
                $(el).find('tbody').html(
                    '<tr class="dt-loading-row"><td colspan="5" class="text-center py-4 text-muted border-0">' +
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
        $('#memoModalTitle').text('New Memo');
        fpIssued.setDate(new Date(), false);
    });

    // ── Open — Edit ──────────────────────────────────────────────────────
    $(document).on('click', '.editBtn', function () {
        const btn = $(this);
        resetModal();
        $('#memo_id').val(btn.data('id'));
        $('#memo_subject').val(btn.attr('data-subject'));
        fpIssued.setDate(btn.attr('data-date'), false);

        let empIds = [];
        try { empIds = JSON.parse(btn.attr('data-employees') || '[]'); } catch (e) {}
        empIds.forEach(function (eid) { empTs.addItem(String(eid), true); });

        let files = [];
        try { files = JSON.parse(btn.attr('data-files') || '[]'); } catch (e) {}
        $('#linksWrap').empty();
        (files.length ? files : ['']).forEach(addLinkRow);

        $('#memoModalTitle').text('Edit Memo');
        $('#memoModal').modal('show');
    });

    // ── Save ─────────────────────────────────────────────────────────────
    $('#saveBtn').on('click', function () {
        const id      = $('#memo_id').val();
        const url     = id ? '/employee-memos/update/' + id : '/employee-memos/store';
        const subject = $('#memo_subject').val().trim();
        const issued  = $('#memo_date_issued').val();
        const empIds  = empTs.items;

        if (!subject) return warn('Subject is required.');
        if (!issued)  return warn('Date issued is required.');
        if (!empIds || empIds.length === 0) return warn('Please select at least one employee.');

        const links = [];
        let bad = null;
        $('#linksWrap .memo-link').each(function () {
            const v = $(this).val().trim();
            if (!v) return;
            if (!GDRIVE_RE.test(v)) { bad = v; return false; }
            links.push(v);
        });
        if (bad)               return warn('"' + bad + '" is not a Google Drive / Google Docs link.');
        if (links.length === 0) return warn('Please add at least one Google Drive file link.');
        if (new Set(links).size !== links.length) return warn('The same file link was entered more than once.');

        const payload = { subject: subject, date_issued: issued };
        empIds.forEach(function (eid, i) { payload['employee_id[' + i + ']'] = eid; });
        links.forEach(function (l, i)   { payload['file_urls[' + i + ']']   = l; });

        const btn = $('#saveBtn').prop('disabled', true);
        $.post(url, payload, function (res) {
            $('#memoModal').modal('hide');

            // keep the Subject filter in sync with newly-saved subjects
            if (res && res.subject && !subjectFilterTs.options[res.subject]) {
                subjectFilterTs.addOption({ value: res.subject, text: res.subject });
            }

            table.ajax.reload();
            Swal.fire({ icon: 'success', title: 'Saved', text: id ? 'Memo updated successfully.' : 'Memo saved successfully.', timer: 1800, showConfirmButton: false });
        }).fail(function (xhr) {
            Swal.fire({ icon: 'error', title: 'Error', text: errorMessage(xhr) });
        }).always(function () { btn.prop('disabled', false); });
    });

    // ── Delete ───────────────────────────────────────────────────────────
    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete this memo?',
            text: 'The memo record will be removed from the list. The file in Google Drive is not affected.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (!r.isConfirmed) return;
            $.ajax({ url: '/employee-memos/delete/' + id, type: 'DELETE' })
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
