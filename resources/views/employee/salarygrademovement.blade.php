@extends("../layout")
@section("additional_head")
   <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <!-- END PAGE LEVEL STYLES -->

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/plugins/src/noUiSlider/nouislider.min.css') }}" rel="stylesheet" type="text/css">
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ asset('assets/src/plugins/src/animate/animate.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <!--  END CUSTOM STYLE FILE  -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{ asset('assets/src/assets/css/light/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->
    <style>.tbtn { width:24px; height:24px; }</style>
@endsection
@section("content")

<div class="row layout-top-spacing">

    <!-- HEADER -->
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
        <div>
            <h4 class="mb-1"><b>SALARY GRADE MOVEMENT</b></h4>
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
                    <label>Branch</label>
                    <input class="form-control form-control-sm text-primary" value="{{ $employee->branch_code }} {{ $employee->branch_name }}" readonly>
                </div>

            </div>
        </div>
    </div>

    <!-- ADD BUTTON -->
    <div class="col-12 mb-2">
        <button type="button"
            class="btn btn-primary float-end"
            data-bs-toggle="modal"
            data-bs-target="#addSgmModal">
            + Add Salary Grade Movement
        </button>
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
            <th>Grade Level</th>
            <th>Step</th>
            <th>Basic Salary</th>
            <th>Effective Date</th>
            <th>End Date</th>
            <th>Remarks</th>
            <th width="140">Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($sgm_records as $record)
        <tr>

            <td>
                <span class="badge bg-primary">
                    SG {{ $record->grade_level ?? '-' }}
                </span>
            </td>

            <td>
                <span class="badge bg-secondary">
                    Step {{ $record->step ?? '-' }}
                </span>
            </td>

            <td>
                ₱ {{ number_format($record->basic_salary ?? 0, 2) }}
            </td>

            <td>
                {{ \Carbon\Carbon::parse($record->effective_date)->format('M d, Y') }}
            </td>

            <td>
                @if($record->end_date)
                    {{ \Carbon\Carbon::parse($record->end_date)->format('M d, Y') }}
                @else
                    <span class="badge bg-success">Present</span>
                @endif
            </td>

            <td>
                {{ $record->remarks ?? '—' }}
            </td>

            <td class="text-center">



        <!-- ONLY CURRENT RECORD CAN BE MODIFIED -->
        <button type="button" @if($record->sgm_id != $current_record_id) @disabled(true) @endif
            class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
            data-bs-toggle="modal"
            data-bs-target="#editModal{{ $record->sgm_id }}"
            title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
        </button>

        <button type="button" @if($record->sgm_id != $current_record_id || $record->sgm_id == $oldest_record_id) @disabled(true) @endif
            class="border-0 bg-transparent text-danger d-inline-flex align-items-center justify-content-center p-0 tbtn"
            data-bs-toggle="modal"
            data-bs-target="#deleteModal{{ $record->sgm_id }}"
            title="Delete">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
        </button>



</td>

        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted">
                No salary grade movement records found
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
<div class="modal fade" id="addSgmModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form method="POST" action="{{ route('salary-grade-movement.store', $employee->employee_id) }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Add Salary Grade Movement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="row">

                <div class="col-md-6 mb-2">
                    <label>Salary Grade</label>
                    <select id="addSgmSalaryGrade" name="salary_grade_id" autocomplete="off" required>
                        <option value="">-- Select --</option>
                        @foreach($salary_grades ?? [] as $grade)
                            <option value="{{ $grade->salary_grade_id }}">
                                SG {{ $grade->grade_level }} - Step {{ $grade->step }} (₱ {{ number_format($grade->basic_salary, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-2">
                    <label>Effective Date</label>
                    <input type="date" name="effective_date" class="form-control form-control-sm" required>
                </div>

                <div class="col-md-6 mb-2">
                    <label>End Date <span class="text-muted small">(leave blank = Present)</span></label>
                    <input type="date" name="end_date" class="form-control form-control-sm">
                </div>

                <div class="col-12 mb-2">
                    <label>Remarks</label>
                    <input type="text" name="remarks" class="form-control form-control-sm">
                </div>

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

@foreach($sgm_records as $record)

<!-- EDIT -->
<div class="modal fade" id="editModal{{ $record->sgm_id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form method="POST" action="{{ route('salary-grade-movement.update', $record->sgm_id) }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Edit Movement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="mb-2">
                <label>Salary Grade</label>
                <select id="editSgmSalaryGrade{{ $record->sgm_id }}" name="salary_grade_id" autocomplete="off">
                    @foreach($salary_grades ?? [] as $grade)
                        <option value="{{ $grade->salary_grade_id }}"
                            {{ $record->salary_grade_id == $grade->salary_grade_id ? 'selected' : '' }}>
                            SG {{ $grade->grade_level }} - Step {{ $grade->step }} (₱ {{ number_format($grade->basic_salary, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Effective Date</label>
                <input type="date" name="effective_date"
                    value="{{ $record->effective_date ? \Carbon\Carbon::parse($record->effective_date)->format('Y-m-d') : '' }}"
                    class="form-control form-control-sm">
            </div>

            <div class="mb-2">
                <label>End Date <span class="text-muted small">(leave blank = Present)</span></label>
                <input type="date" name="end_date"
                    value="{{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('Y-m-d') : '' }}"
                    class="form-control form-control-sm">
            </div>

            <div class="mb-2">
                <label>Remarks</label>
                <input type="text" name="remarks"
                    value="{{ $record->remarks }}"
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
<div class="modal fade" id="deleteModal{{ $record->sgm_id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('salary-grade-movement.delete', $record->sgm_id) }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Delete Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            Are you sure you want to delete this record?
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
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.js') }}"></script>

    <script src="{{ asset('assets/src/plugins/src/flatpickr/custom-flatpickr.js') }}"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
    <script>

        new TomSelect('#addSgmSalaryGrade', {
            create: false,
            sortField: { field: 'text', direction: 'asc' },
            placeholder: '-- Select Salary Grade --'
        });

        @foreach($sgm_records as $record)
        new TomSelect('#editSgmSalaryGrade{{ $record->sgm_id }}', {
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
