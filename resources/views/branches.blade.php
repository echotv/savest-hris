@extends("layout")
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
            <h3 class="fw-bold text-dark tracking-tight mb-1">Branches</h3>
            <p class="text-muted small mb-0">Manage and configure company branch locations.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Branches</li>
                </ol>
            </nav>
            @if (in_array('CREATE NEW BRANCH', session('permissions', [])))
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#CreateModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Create New Branch
            </button>
            @endif
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

    {{-- FILTERS --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <form method="GET" action="{{ route('branches.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Area</label>
                        <select name="area" id="filter_area" class="form-select form-select-sm" onchange="this.form.submit();">
                            <option value="">All Areas</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area }}" @if (request('area') == $area) selected @endif>
                                    Area {{ $area }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary btn-sm">Clear Filter</a>
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
                                <th class="border-0 ps-4 py-3">Branch Code</th>
                                <th class="border-0 py-3">Branch Name</th>
                                <th class="border-0 py-3">Area</th>
                                <th class="border-0 py-3">Address</th>
                                <th class="border-0 py-3">Visible?</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $branch->branch_code }}</td>
                                <td>{{ $branch->branch_name }}</td>
                                <td>{{ $branch->area }}</td>
                                <td class="text-muted">{{ $branch->address }}</td>
                                <td>
                                    @if($branch->is_visible)
                                        <span class="badge bg-light-success">Yes</span>
                                    @else
                                        <span class="badge bg-light-danger">No</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    @if (in_array('UPDATE BRANCH', session('permissions', [])))
                                    <button class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#UpdateModal"
                                        data-code="{{ $branch->branch_code }}"
                                        data-name="{{ $branch->branch_name }}"
                                        data-area="{{ $branch->area }}"
                                        data-address="{{ $branch->address }}"
                                        data-visible="{{ $branch->is_visible }}"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    @endif
                                    @if (in_array('DELETE BRANCH', session('permissions', [])))
                                    <form action="{{ route('branches.delete', $branch->branch_code) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="border-0 bg-transparent text-danger d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                            onclick="return confirm('Delete this branch?')"
                                            title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Area</th>
                                <th>Address</th>
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
                    Create New Branch
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('branches.store') }}" method="POST" id="branchForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Branch Code <span class="text-danger">*</span></label>
                        <input type="text" name="branch_code" class="form-control form-control-sm" value="Branch #" maxlength="20" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" class="form-control form-control-sm" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Area</label>
                        <input type="number" name="area" class="form-control form-control-sm" step="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Address</label>
                        <input type="text" name="address" class="form-control form-control-sm">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_visible" class="form-check-input" id="create_visible" checked>
                        <label class="form-check-label text-dark small" for="create_visible">Visible</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="branchForm" class="btn btn-primary px-4 shadow-sm">Create</button>
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
                    Update Branch
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id="updateBranchForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Branch Code</label>
                        <input type="text" id="update_branch_code" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" id="update_branch_name" class="form-control form-control-sm" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Area</label>
                        <input type="number" name="area" id="update_branch_area" class="form-control form-control-sm" step="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Address</label>
                        <input type="text" name="address" id="update_branch_address" class="form-control form-control-sm">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_visible" id="update_is_visible" class="form-check-input">
                        <label class="form-check-label text-dark small" for="update_is_visible">Visible</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="updateBranchForm" class="btn btn-primary px-4 shadow-sm">Update</button>
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
        var button = event.relatedTarget;
        var code    = button.getAttribute('data-code');
        var name    = button.getAttribute('data-name');
        var area    = button.getAttribute('data-area');
        var address = button.getAttribute('data-address');
        var visible = button.getAttribute('data-visible');

        var form = document.getElementById('updateBranchForm');
        form.action = '/branches/update/' + code;

        document.getElementById('update_branch_code').value    = code;
        document.getElementById('update_branch_name').value    = name;
        document.getElementById('update_branch_area').value    = area;
        document.getElementById('update_branch_address').value = address;
        document.getElementById('update_is_visible').checked   = visible == 1;
    });
});
</script>
@endsection
