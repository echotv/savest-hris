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

    .total-days-box {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 64px; height: 36px;
        background: #f1f5f9; border: 1px solid #e2e8f0;
        border-radius: 8px; font-size: 20px; font-weight: 700;
        color: #4361ee; letter-spacing: -1px;
    }
    body.dark .total-days-box { background: #1b2e4b; border-color: #0e1726; color: #6ea8fe; }
</style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Suspended Employees</h3>
            <p class="text-muted small mb-0">Manage employee suspension records including period and duration.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Suspended Employees</li>
                </ol>
            </nav>
            @if(userCan('ADD SUSPENDED EMPLOYEES'))
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#suspModal" id="addNewBtn">
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
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Branch</label>
                        <select id="filter_branch" autocomplete="off">
                            <option value="">All Branches</option>
                            @foreach($branches as $br)
                                <option value="{{ $br->branch_code }}">{{ $br->branch_name }}</option>
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
                    <table id="suspTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Employee</th>
                                <th class="border-0 py-3">Branch</th>
                                <th class="border-0 py-3">Start Date</th>
                                <th class="border-0 py-3">End Date</th>
                                <th class="border-0 py-3 text-center">Total Days</th>
                                <th class="border-0 py-3">Reason</th>
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
<div class="modal fade" id="suspModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="suspModalTitle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    New Suspension Record
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="susp_id">

                <div class="row g-3">

                    {{-- Employee --}}
                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Employee <span class="text-danger">*</span></label>
                        <select id="susp_employee_id" class="form-select form-select-sm rounded">
                            <option value="">— Select Employee —</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">
                                    {{ trim($emp->firstname . ' ' . $emp->lastname) }} ({{ $emp->employee_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dates --}}
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-secondary small">Start Date <span class="text-danger">*</span></label>
                        <input type="text" id="susp_start_date" class="form-control form-control-sm" placeholder="Select start date…" autocomplete="off">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium text-secondary small">End Date <span class="text-danger">*</span></label>
                        <input type="text" id="susp_end_date" class="form-control form-control-sm" placeholder="Select end date…" autocomplete="off">
                    </div>

                    {{-- Total Days --}}
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-secondary small">Total Days</label>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <div class="total-days-box" id="total_days_display">—</div>
                            <span class="text-muted small">calendar day(s)</span>
                        </div>
                        <input type="hidden" id="susp_total_days" value="0">
                    </div>

                    {{-- Reason --}}
                    <div class="col-12">
                        <label class="form-label fw-medium text-secondary small">Reason / Remarks <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea id="susp_reason" class="form-control form-control-sm" rows="3"
                                  placeholder="Details of the disciplinary action or memo reference…"></textarea>
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

    // ── Date pickers ──────────────────────────────────────────────────────
    const fpStart = flatpickr('#susp_start_date', {
        dateFormat: 'Y-m-d',
        onChange(sel) { fpEnd.set('minDate', sel[0] || null); recalcDays(); }
    });
    const fpEnd = flatpickr('#susp_end_date', {
        dateFormat: 'Y-m-d',
        onChange(sel) { fpStart.set('maxDate', sel[0] || null); recalcDays(); }
    });

    function recalcDays() {
        const s = $('#susp_start_date').val();
        const e = $('#susp_end_date').val();
        if (s && e) {
            const diff = Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
            if (diff > 0) {
                $('#total_days_display').text(diff);
                $('#susp_total_days').val(diff);
                return;
            }
        }
        $('#total_days_display').text('—');
        $('#susp_total_days').val(0);
    }

    // ── TomSelect ────────────────────────────────────────────────────────
    const empTs = new TomSelect('#susp_employee_id', {
        allowEmptyOption: true,
        placeholder: 'Search employee…'
    });

    const branchFilterTs = new TomSelect('#filter_branch', {
        allowEmptyOption: true,
        placeholder: 'Search branch…'
    });

    // ── DataTable ─────────────────────────────────────────────────────────
    const table = $('#suspTable').DataTable({
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
            sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
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
            url: "{{ route('suspensions.data') }}",
            data: function (d) {
                d.branch_filter = $('#filter_branch').val();
            }
        },
        columns: [
            {
                data: 'employee_info',
                render: function (d) {
                    const parts = d.split('|');
                    return '<div><div class="fw-semibold font-13">' + parts[0] + '</div><div class="text-muted font-12">' + parts[1] + '</div></div>';
                }
            },
            { data: 'branch_name', render: d => d ?? '<span class="text-muted">—</span>' },
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
                data: 'total_days',
                className: 'text-center',
                render: function (d) { return '<span class="fw-bold text-primary font-13">' + d + '</span>'; }
            },
            {
                data: 'reason',
                render: function (d) {
                    return d
                        ? '<span class="text-muted font-12" style="max-width:220px;display:inline-block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="' + d + '">' + d + '</span>'
                        : '<span class="text-muted opacity-50 font-12">—</span>';
                }
            },
            { data: 'action', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#suspTable').on('processing.dt', function (e, settings, processing) {
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

    $('#filter_branch').on('change', function () { table.ajax.reload(); });

    // ── Reset modal ───────────────────────────────────────────────────────
    function resetModal() {
        $('#susp_id').val('');
        empTs.clear(true);
        fpStart.clear(); fpStart.set('maxDate', null);
        fpEnd.clear();   fpEnd.set('minDate', null);
        $('#susp_reason').val('');
        $('#total_days_display').text('—');
        $('#susp_total_days').val(0);
    }

    // ── Open — Add ────────────────────────────────────────────────────────
    $('#addNewBtn').on('click', function () {
        resetModal();
        $('#suspModalTitle').html(
            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> New Suspension Record'
        );
    });

    // ── Open — Edit ───────────────────────────────────────────────────────
    $(document).on('click', '.editBtn', function () {
        const btn = $(this);
        resetModal();
        $('#susp_id').val(btn.data('id'));
        empTs.setValue(String(btn.data('employee_id')), true);
        fpStart.setDate(btn.data('start'));
        fpEnd.setDate(btn.data('end'));
        fpEnd.set('minDate', btn.data('start'));
        const days = btn.data('days');
        $('#total_days_display').text(days);
        $('#susp_total_days').val(days);
        $('#susp_reason').val(btn.data('reason'));
        $('#suspModalTitle').html(
            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Suspension Record'
        );
        $('#suspModal').modal('show');
    });

    // ── Save ─────────────────────────────────────────────────────────────
    $('#saveBtn').on('click', function () {
        const id  = $('#susp_id').val();
        const url = id ? '/suspensions/update/' + id : '/suspensions/store';

        if (!$('#susp_employee_id').val()) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Please select an employee.', timer: 2000, showConfirmButton: false });
            return;
        }
        if (!$('#susp_start_date').val() || !$('#susp_end_date').val()) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Start and end dates are required.', timer: 2000, showConfirmButton: false });
            return;
        }
        if (parseInt($('#susp_total_days').val()) < 1) {
            Swal.fire({ icon: 'warning', title: 'Invalid', text: 'End date must be on or after start date.', timer: 2000, showConfirmButton: false });
            return;
        }

        $.post(url, {
            employee_id: $('#susp_employee_id').val(),
            start_date:  $('#susp_start_date').val(),
            end_date:    $('#susp_end_date').val(),
            total_days:  $('#susp_total_days').val(),
            reason:      $('#susp_reason').val(),
        }, function () {
            $('#suspModal').modal('hide');
            table.ajax.reload();
            Swal.fire({ icon: 'success', title: 'Saved', text: 'Suspension record saved successfully.', timer: 1800, showConfirmButton: false });
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
            text: 'The suspension record will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (r.isConfirmed) {
                $.ajax({ url: '/suspensions/delete/' + id, type: 'DELETE' })
                    .done(function () {
                        table.ajax.reload();
                        Swal.fire({ icon: 'success', title: 'Deleted', timer: 1500, showConfirmButton: false });
                    });
            }
        });
    });

    // ── Reset flatpickr limits when modal closes ──────────────────────────
    $('#suspModal').on('hidden.bs.modal', function () {
        fpStart.set('maxDate', null);
        fpEnd.set('minDate', null);
    });
});
</script>
@endsection
