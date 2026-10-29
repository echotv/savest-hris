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
    <style>.tbtn { width:24px; height:24px; }</style>
@endsection
@section("content")

<div class="row layout-top-spacing">

    <!-- HEADER -->
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
        <div>
            <h4 class="mb-1"><b>BIOMETRIC IDS</b></h4>
        </div>
    </div>

    <!-- ALERTS -->
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

    <!-- EMPLOYEE INFO -->
    <div class="col-12 mb-2">
        <div class="card">
            <div class="card-body row">

                <div class="col-md-3">
                    <label>Employee No.</label>
                    <input class="form-control form-control-sm text-primary" value="{{ $employee->employee_number }}" readonly>
                </div>

                <div class="col-md-5">
                    <label>Employee</label>
                    <input class="form-control form-control-sm text-primary" value="{{ $employee->lastname }}, {{ $employee->firstname }}" readonly>
                </div>

                <div class="col-md-4">
                    <label>Assigned Branch</label>
                    <input class="form-control form-control-sm text-primary" value="{{ $employee->branch_code }} {{ $employee->branch_name }}" readonly>
                </div>

            </div>
        </div>
    </div>

    <!-- ADD BUTTON -->
    @if (in_array('ADD BIOMETRIC IDS', session('permissions', [])))
    <div class="col-12 mb-2">
        <button type="button"
            class="btn btn-primary float-end"
            data-bs-toggle="modal"
            data-bs-target="#addBiometricModal">
            + Add Biometric ID
        </button>
    </div>
    @endif

    <!-- TABLE -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h5><b>List of Records</b></h5>

                <div class="table-responsive">
                    <table id="zero-config" class="table table-striped table-bordered dt-table-hover" style="width:100%">

    <thead>
        <tr>
            <th>Branch</th>
            <th>Biometric ID</th>
            <th width="140">Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($biometric_records as $record)
        <tr>

            <td>
                {{ $record->branch_code }} {{ $record->branch_name }}
            </td>

            <td>
                <span class="badge bg-primary">{{ $record->biometric_id }}</span>
            </td>

            <td class="text-center">

        <button type="button"
            class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
            data-bs-toggle="modal"
            data-bs-target="#editModal{{ $record->id }}"
            title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
        </button>

        @if (in_array('DELETE BIOMETRIC IDS', session('permissions', [])))
        <button type="button"
            class="border-0 bg-transparent text-danger d-inline-flex align-items-center justify-content-center p-0 tbtn"
            data-bs-toggle="modal"
            data-bs-target="#deleteModal{{ $record->id }}"
            title="Delete">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
        </button>
        @endif

</td>

        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center text-muted">
                No biometric IDs on record for this employee
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

      <form method="POST" action="{{ route('biometric-ids.store', $employee->employee_id) }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Add Biometric ID</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="mb-2">
                <label>Branch</label>
                <select name="branch_code" id="addBiometricBranch" class="form-control form-control-sm" required>
                    @foreach($branches ?? [] as $branch)
                        <option value="{{ $branch->branch_code }}"
                            {{ $branch->branch_code == $employee->branch_code ? 'selected' : '' }}>
                            {{ $branch->branch_code }} - {{ $branch->branch_name }}
                        </option>
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

@foreach($biometric_records as $record)

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
                <label>Branch</label>
                <select name="branch_code" id="editBiometricBranch{{ $record->id }}" class="form-control form-control-sm">
                    @foreach($branches ?? [] as $branch)
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

        new TomSelect('#addBiometricBranch', {
            create: false,
            sortField: { field: 'text', direction: 'asc' }
        });

        @foreach($biometric_records as $record)
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
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 10,
            "ordering": false,
        });

        document.querySelectorAll('[title]').forEach(function(el) {
            if (el.classList.contains('tbtn')) {
                new bootstrap.Tooltip(el, { trigger: 'hover' });
            }
        });

    </script>
    <!-- END PAGE LEVEL SCRIPTS -->
@endsection
