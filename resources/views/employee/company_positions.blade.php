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
            <h3 class="fw-bold text-dark tracking-tight mb-1">Company Positions</h3>
            <p class="text-muted small mb-0">Manage and configure company job positions and roles.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Company Positions</li>
                </ol>
            </nav>
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#CreateModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Create New Position
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

    {{-- FILTER --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <form action="{{ route('positions.company_positions') }}" method="get" id="filterForm">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Status</label>
                            <select class="form-select form-select-sm" id="filter_status" name="filter_status" onchange="document.getElementById('filterForm').submit();">
                                <option value="Active" {{ ($filter_status ?? 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Archived" {{ ($filter_status ?? '') === 'Archived' ? 'selected' : '' }}>Archived</option>
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
                                <th class="border-0 ps-4 py-3">Position</th>
                                <th class="border-0 py-3">No. of Employees</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions_list as $position)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $position->position_name }}</td>
                                <td>{{ $position->num_employees }}</td>
                                <td class="pe-4 text-end">
                                    <button class="border-0 bg-transparent text-primary d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        data-bs-toggle="modal" data-bs-target="#EmployeesModal"
                                        data-id="{{ $position->position_id }}"
                                        data-name="{{ $position->position_name }}"
                                        title="View Employees">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </button>
                                    <button class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        data-bs-toggle="modal" data-bs-target="#UpdateModal"
                                        data-id="{{ $position->position_id }}"
                                        data-name="{{ $position->position_name }}"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    @if($position->is_archived)
                                    <a href="{{ route('positions.restore', $position->position_id) }}"
                                        class="border-0 bg-transparent text-warning d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        onclick="return confirm('Are you sure you want to restore this position?')"
                                        title="Restore">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                                    </a>
                                    @else
                                    <a href="{{ route('positions.archive', $position->position_id) }}"
                                        class="border-0 bg-transparent text-warning d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        onclick="return confirm('Are you sure you want to archive this position?')"
                                        title="Archive">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Position</th>
                                <th>No. of Employees</th>
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
                    Create New Position
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('positions.store') }}" method="post" id="positionForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Position Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="position_name" name="position_name" maxlength="150" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="positionForm" class="btn btn-primary px-4 shadow-sm">Create</button>
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
                    Update Position
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="post" id="updatePositionForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Position Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="update_position_name" name="position_name" value="" maxlength="150" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="updatePositionForm" class="btn btn-primary px-4 shadow-sm">Update</button>
            </div>
        </div>
    </div>
</div>

{{-- Employees Modal --}}
<div id="EmployeesModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span id="employees_modal_title">Employees</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 border-bottom">
                    <input type="text" class="form-control form-control-sm" id="employees_search" placeholder="Search name, employee no., branch...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Employee No.</th>
                                <th class="border-0 py-3">Name</th>
                                <th class="border-0 py-3">Branch</th>
                                <th class="border-0 pe-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody id="employees_tbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 justify-content-between">
                <span class="text-muted small" id="employees_count"></span>
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Close</button>
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
        var id   = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');

        var form = updateModal.querySelector('form');
        form.action = '/positions/update/' + id;

        updateModal.querySelector('#update_position_name').value = name;
    });

    var employeesModal = document.getElementById('EmployeesModal');
    var tbody  = document.getElementById('employees_tbody');
    var search = document.getElementById('employees_search');
    var countEl = document.getElementById('employees_count');

    function esc(v) {
        return $('<div>').text(v == null ? '' : v).html();
    }

    function setMessage(msg) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">' + msg + '</td></tr>';
    }

    function updateCount() {
        var rows = tbody.querySelectorAll('tr[data-search]');
        var shown = 0;
        rows.forEach(function (r) { if (r.style.display !== 'none') shown++; });
        countEl.textContent = rows.length ? ('Showing ' + shown + ' of ' + rows.length + ' employee(s)') : '';
    }

    employeesModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id   = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');

        document.getElementById('employees_modal_title').textContent = 'Employees - ' + name;
        search.value = '';
        countEl.textContent = '';
        setMessage('Loading...');

        fetch('/positions/employees/' + id, { headers: { 'Accept': 'application/json' } })
            .then(function (res) {
                if (!res.ok) throw new Error(res.status);
                return res.json();
            })
            .then(function (list) {
                if (!list.length) {
                    setMessage('No employees assigned to this position.');
                    return;
                }
                tbody.innerHTML = list.map(function (e) {
                    var fullName = [e.lastname + ',', e.firstname, e.middlename, e.suffix].filter(Boolean).join(' ');
                    var branch   = [e.branch_code, e.branch_name].filter(Boolean).join(' ');
                    var hay = [e.employee_number, fullName, branch, e.empl_status].join(' ').toLowerCase();
                    return '<tr data-search="' + esc(hay) + '">' +
                        '<td class="ps-4 font-13">' + esc(e.employee_number) + '</td>' +
                        '<td class="font-13 fw-semibold"><a href="' + esc(e.profile_url) + '" class="text-decoration-none">' + esc(fullName) + '</a></td>' +
                        '<td class="font-13">' + esc(branch) + '</td>' +
                        '<td class="pe-4 font-13">' + esc(e.empl_status) + '</td>' +
                        '</tr>';
                }).join('');
                updateCount();
            })
            .catch(function () {
                setMessage('Failed to load employees. Please try again.');
            });
    });

    search.addEventListener('input', function () {
        var q = this.value.trim().toLowerCase();
        tbody.querySelectorAll('tr[data-search]').forEach(function (r) {
            r.style.display = r.getAttribute('data-search').indexOf(q) !== -1 ? '' : 'none';
        });
        updateCount();
    });
});
</script>
@endsection
