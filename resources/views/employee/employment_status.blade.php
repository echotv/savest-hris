@extends("../layout")
@section("additional_head")
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tracking-tight  { letter-spacing: -0.5px; }
        .tracking-wider  { letter-spacing: 0.5px; }
        .font-12 { font-size: 12px; }
        .font-13 { font-size: 13px; }
        .tbtn { width:24px; height:24px; }
    </style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Employment Status</h3>
            <p class="text-muted small mb-0">Manage employment status types and their visibility settings.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Employment Status</li>
                </ol>
            </nav>
            <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#CreateModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Create Employment Status
            </button>
        </div>
    </div>

    {{-- ALERTS --}}
    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="zero-config" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Employment Status</th>
                                <th class="border-0 py-3">No. of Employees</th>
                                <th class="border-0 py-3">Current Employee</th>
                                <th class="border-0 py-3">Visible?</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employment_statuses as $es)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $es->empl_status }}</td>
                                <td>0</td>
                                <td>
                                    @if ($es->is_current_employee)
                                        <span class="badge badge-light-info">YES</span>
                                    @else
                                        <span class="badge badge-light-danger">NO</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($es->is_visible)
                                        <span class="badge badge-light-success">YES</span>
                                    @else
                                        <span class="badge badge-light-danger">NO</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#UpdateModal"
                                        data-id="{{ $es->es_id }}"
                                        data-name="{{ $es->empl_status }}"
                                        data-current="{{ $es->is_current_employee }}"
                                        data-visible="{{ $es->is_visible }}"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Employment Status</th>
                                <th>No. of Employees</th>
                                <th>Current Employee</th>
                                <th>Visible?</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section("additional_footer")

{{-- Create Modal --}}
<div id="CreateModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    Create Employment Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('employment_status.store') }}" method="post" id="esForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Employment Status <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="empl_status" name="empl_status" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Is Current Employee?</label>
                        <select name="is_current_employee" id="is_current_employee" class="form-select form-select-sm">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Is Visible?</label>
                        <select name="is_visible" id="is_visible" class="form-select form-select-sm">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="esForm" class="btn btn-primary px-4 shadow-sm">Create</button>
            </div>
        </div>
    </div>
</div>

{{-- Update Modal --}}
<div id="UpdateModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Update Employment Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id="updateESForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Employment Status <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="update_empl_status" name="empl_status" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Is Current Employee?</label>
                        <select name="is_current_employee" id="update_is_current_employee" class="form-select form-select-sm">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Is Visible?</label>
                        <select name="is_visible" id="update_is_visible" class="form-select form-select-sm">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="updateESForm" class="btn btn-primary px-4 shadow-sm">Update</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script>
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

document.querySelectorAll('.btn[title], a.btn[title]').forEach(function(el) {
    new bootstrap.Tooltip(el, { trigger: 'hover' });
});

document.addEventListener("DOMContentLoaded", function () {
    var updateModal = document.getElementById('UpdateModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        var button  = event.relatedTarget;
        document.getElementById('updateESForm').action             = '/employment-status/update/' + button.getAttribute('data-id');
        document.getElementById('update_empl_status').value        = button.getAttribute('data-name');
        document.getElementById('update_is_current_employee').value = button.getAttribute('data-current');
        document.getElementById('update_is_visible').value          = button.getAttribute('data-visible');
    });
});
</script>
@endsection
