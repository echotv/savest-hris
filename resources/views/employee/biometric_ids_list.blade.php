@extends("../layout")
@section("additional_head")
   <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <!-- END PAGE LEVEL STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{ asset('assets/src/assets/css/light/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <!--  END CUSTOM STYLE FILE  -->
@endsection
@section("content")

<div class="row layout-top-spacing">

    <!-- HEADER -->
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
        <div>
            <h4 class="mb-1"><b>BIOMETRIC IDS</b></h4>
        </div>
        @if (in_array('ADD BIOMETRIC IDS', session('permissions', [])))
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBiometricModal">
            + Add Biometric ID
        </button>
        @endif
    </div>

    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger">
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
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- FILTERS -->
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <form method="GET" action="{{ route('biometric-ids.all') }}" class="row g-3 align-items-end">

                    <div class="col-lg-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Branch</label>
                        <select name="branch_code" id="filter_branch_code" class="form-control form-control-sm">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->branch_code }}" {{ request('branch_code') == $branch->branch_code ? 'selected' : '' }}>
                                    {{ $branch->branch_code }} - {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Employee</label>
                        <select name="employee_id" id="filter_employee_id" class="form-control form-control-sm">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}" {{ request('employee_id') == $emp->employee_id ? 'selected' : '' }}>
                                    {{ trim($emp->lastname . ', ' . $emp->firstname) }} ({{ $emp->employee_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Status</label>
                        <select name="status" id="filter_status" class="form-control form-control-sm">
                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="with" {{ request('status') == 'with' ? 'selected' : '' }}>With Biometric ID</option>
                            <option value="without" {{ request('status') == 'without' ? 'selected' : '' }}>Without Biometric ID</option>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <a href="{{ route('biometric-ids.all') }}" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h5><b>List of Records</b></h5>

                <div class="table-responsive">
                    <table id="zero-config" class="table table-striped table-bordered dt-table-hover" style="width:100%">

    <thead>
        <tr>
            <th>Employee No.</th>
            <th>Employee</th>
            <th>Assigned Branch</th>
            <th>Status</th>
            <th>Branch - Biometric ID</th>
            <th width="80">Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($employee_rows as $emp)
        <tr>
            <td>{{ $emp->employee_number }}</td>
            <td>
                <a href="{{ route('biometric-ids.index', $emp->employee_id) }}">
                    {{ $emp->lastname }}, {{ $emp->firstname }}
                </a>
            </td>
            <td class="font-13">{{ $emp->branch_code }} {{ $emp->branch_name }}</td>
            <td>
                @if($emp->biometric_records->isNotEmpty())
                    <span class="badge bg-success-subtle text-success-emphasis">With Biometric ID</span>
                @else
                    <span class="badge bg-warning-subtle text-warning-emphasis">Without Biometric ID Yet</span>
                @endif
            </td>
            <td>
                @forelse($emp->biometric_records as $record)
                <div class="d-flex align-items-center gap-1 mb-1">
                    <span class="badge bg-primary">{{ $record->branch_code }} {{ $record->biometric_branch_name }} - {{ $record->biometric_id }}</span>

                    <button type="button"
                        class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                        style="width:20px;height:20px;"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $record->id }}"
                        title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>

                    @if (in_array('DELETE BIOMETRIC IDS', session('permissions', [])))
                    <button type="button"
                        class="border-0 bg-transparent text-danger d-inline-flex align-items-center justify-content-center p-0 tbtn"
                        style="width:20px;height:20px;"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{ $record->id }}"
                        title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                    @endif
                </div>
                @empty
                    <span class="text-muted font-13">—</span>
                @endforelse
            </td>
            <td class="text-center">
                @if (in_array('ADD BIOMETRIC IDS', session('permissions', [])))
                <button type="button"
                    class="border-0 bg-transparent text-success d-inline-flex align-items-center justify-content-center p-0 tbtn"
                    style="width:24px;height:24px;"
                    data-bs-toggle="modal"
                    data-bs-target="#addBiometricModal"
                    onclick="prefillAddBiometric({{ $emp->employee_id }})"
                    title="Add Biometric ID">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </button>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">
                No employees found for the selected filters
            </td>
        </tr>
        @endforelse
    </tbody>

</table>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- ========================= -->
<!-- ADD MODAL -->
<!-- ========================= -->
<div class="modal fade" id="addBiometricModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" id="addBiometricForm" action="{{ url('/biometric-ids') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Add Biometric ID</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="mb-2">
                <label>Employee</label>
                <select id="addBiometricEmployee" class="form-control form-control-sm" required>
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}" data-branch="{{ $emp->branch_code }} {{ $emp->branch_name }}">
                            {{ trim($emp->lastname . ', ' . $emp->firstname) }} ({{ $emp->employee_number }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Assigned Branch</label>
                <input type="text" id="addBiometricAssignedBranch" class="form-control form-control-sm" readonly>
            </div>

            <div class="mb-2">
                <label>Branch</label>
                <select name="branch_code" id="addBiometricBranch" class="form-control form-control-sm" required>
                    <option value="">-- Select Branch --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->branch_code }}">{{ $branch->branch_code }} - {{ $branch->branch_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Biometric ID</label>
                <input type="text" name="biometric_id" class="form-control form-control-sm" required>
            </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- ========================= -->
<!-- EDIT + DELETE MODALS -->
<!-- ========================= -->

@foreach($all_biometric_records as $record)

<!-- EDIT -->
<div class="modal fade" id="editModal{{ $record->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('biometric-ids.update', $record->id) }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Edit Biometric ID</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="mb-2">
                <label>Employee</label>
                <input class="form-control form-control-sm" value="{{ $record->lastname }}, {{ $record->firstname }} ({{ $record->employee_number }})" disabled>
            </div>

            <div class="mb-2">
                <label>Branch</label>
                <select name="branch_code" id="editBiometricBranch{{ $record->id }}" class="form-control form-control-sm">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->branch_code }}"
                            {{ $record->branch_code == $branch->branch_code ? 'selected' : '' }}>
                            {{ $branch->branch_code }} - {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Biometric ID</label>
                <input type="text" name="biometric_id"
                    value="{{ $record->biometric_id }}"
                    class="form-control form-control-sm">
            </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- DELETE -->
<div class="modal fade" id="deleteModal{{ $record->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('biometric-ids.delete', $record->id) }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Delete Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            Are you sure you want to delete this biometric ID record?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endforeach

@endsection
@section("additional_footer")
<!--  BEGIN CUSTOM STYLE FILE  -->
    <script src="{{ asset('assets/src/assets/js/elements/tooltip.js') }}"></script>
    <!--  END CUSTOM STYLE FILE  -->

    <script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
    <script>

        new TomSelect('#filter_branch_code', {
            create: false,
            sortField: { field: 'text', direction: 'asc' },
            placeholder: 'All Branches',
            onChange: function () { this.input.form.submit(); }
        });

        new TomSelect('#filter_employee_id', {
            create: false,
            sortField: { field: 'text', direction: 'asc' },
            placeholder: 'All Employees',
            onChange: function () { this.input.form.submit(); }
        });

        new TomSelect('#filter_status', {
            create: false,
            sortField: { field: 'text', direction: 'asc' },
            onChange: function () { this.input.form.submit(); }
        });

        const addBiometricEmployeeTs = new TomSelect('#addBiometricEmployee', {
            create: false,
            sortField: { field: 'text', direction: 'asc' },
            placeholder: '-- Select Employee --',
            onChange: function (value) {
                document.getElementById('addBiometricForm').action = '{{ url('/biometric-ids') }}/' + value;
                const opt = document.querySelector('#addBiometricEmployee option[value="' + value + '"]');
                document.getElementById('addBiometricAssignedBranch').value = opt ? opt.dataset.branch : '';
            }
        });

        window.prefillAddBiometric = function (employeeId) {
            addBiometricEmployeeTs.setValue(employeeId);
        };

        new TomSelect('#addBiometricBranch', {
            create: false,
            sortField: { field: 'text', direction: 'asc' },
            placeholder: '-- Select Branch --'
        });

        @foreach($all_biometric_records as $record)
        new TomSelect('#editBiometricBranch{{ $record->id }}', {
            create: false,
            sortField: { field: 'text', direction: 'asc' }
        });
        @endforeach

        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 20, 50, 100],
            "pageLength": 20,
            "ordering": true,
        });

        document.querySelectorAll('[title]').forEach(function(el) {
            if (el.classList.contains('tbtn')) {
                new bootstrap.Tooltip(el, { trigger: 'hover' });
            }
        });

    </script>
    <!-- END PAGE LEVEL SCRIPTS -->
@endsection
