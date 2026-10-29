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
            <h3 class="fw-bold text-dark tracking-tight mb-1">Salary Grades</h3>
            <p class="text-muted small mb-0">Manage salary grade levels, steps, and corresponding pay rates.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Salary Grades</li>
                </ol>
            </nav>
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#CreateModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Create Salary Grade
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
                                <th class="border-0 ps-4 py-3">Grade Level</th>
                                <th class="border-0 py-3">Step</th>
                                <th class="border-0 py-3">Basic Salary</th>
                                <th class="border-0 py-3">Daily Rate</th>
                                <th class="border-0 py-3">Hourly Rate</th>
                                <th class="border-0 py-3">Status</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryGrades as $sg)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $sg->grade_level }}</td>
                                <td>{{ $sg->step }}</td>
                                <td>₱ {{ number_format($sg->basic_salary, 2) }}</td>
                                <td>₱ {{ number_format($sg->daily_rate, 2) }}</td>
                                <td>₱ {{ number_format($sg->hourly_rate, 2) }}</td>
                                <td>
                                    @if($sg->is_active)
                                        <span class="badge bg-light-success">Active</span>
                                    @else
                                        <span class="badge bg-light-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#UpdateModal"
                                        data-id="{{ $sg->salary_grade_id }}"
                                        data-grade="{{ $sg->grade_level }}"
                                        data-step="{{ $sg->step }}"
                                        data-salary="{{ $sg->basic_salary }}"
                                        data-active="{{ $sg->is_active }}"
                                        data-hourly="{{ $sg->hourly_rate }}"
                                        data-daily="{{ $sg->daily_rate }}"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <form action="{{ route('salarygrades.delete', $sg->salary_grade_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="border-0 bg-transparent text-danger d-inline-flex align-items-center justify-content-center p-0 tbtn" onclick="return confirm('Delete this salary grade?')" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
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
                    Create Salary Grade
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('salarygrades.store') }}" method="POST" id="salaryForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Grade Level <span class="text-danger">*</span></label>
                            <input type="number" name="grade_level" class="form-control form-control-sm" min="0" max="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Step <span class="text-danger">*</span></label>
                            <input type="number" name="step" class="form-control form-control-sm" min="0" max="100" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-secondary small">Basic Salary <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="basic_salary" id="c_salary" class="form-control form-control-sm" min="0" max="9999999999.99" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Daily Rate</label>
                            <input type="text" id="c_daily" class="form-control form-control-sm bg-light" readonly placeholder="0.00">
                            <div class="form-text">Basic Salary ÷ 26</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Hourly Rate</label>
                            <input type="text" id="c_hourly" class="form-control form-control-sm bg-light" readonly placeholder="0.00">
                            <div class="form-text">Daily Rate ÷ 8</div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="create_active" checked>
                                <label class="form-check-label text-dark small" for="create_active">Active</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="salaryForm" class="btn btn-primary px-4 shadow-sm">Create</button>
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
                    Update Salary Grade
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id="updateSalaryForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Grade Level <span class="text-danger">*</span></label>
                            <input type="number" name="grade_level" id="u_grade" class="form-control form-control-sm" min="0" max="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Step <span class="text-danger">*</span></label>
                            <input type="number" name="step" id="u_step" class="form-control form-control-sm" min="0" max="100" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-secondary small">Basic Salary <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="basic_salary" id="u_salary" class="form-control form-control-sm" min="0" max="9999999999.99" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Daily Rate</label>
                            <input type="text" id="u_daily" class="form-control form-control-sm bg-light" readonly placeholder="0.00">
                            <div class="form-text">Basic Salary ÷ 26</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Hourly Rate</label>
                            <input type="text" id="u_hourly" class="form-control form-control-sm bg-light" readonly placeholder="0.00">
                            <div class="form-text">Daily Rate ÷ 8</div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="u_active" class="form-check-input">
                                <label class="form-check-label text-dark small" for="u_active">Active</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="updateSalaryForm" class="btn btn-primary px-4 shadow-sm">Update</button>
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

// Daily Rate = Basic Salary ÷ 26, Hourly Rate = Daily Rate ÷ 8 — both rounded to 2 decimals.
function computeRates(basicInput, dailyInput, hourlyInput) {
    const basic = parseFloat(basicInput.value);
    if (isNaN(basic) || basic < 0) {
        dailyInput.value = '';
        hourlyInput.value = '';
        return;
    }
    const daily  = Math.round((basic / 26) * 100) / 100;
    const hourly = Math.round((daily / 8) * 100) / 100;
    dailyInput.value  = daily.toFixed(2);
    hourlyInput.value = hourly.toFixed(2);
}

document.addEventListener("DOMContentLoaded", function () {
    var cSalary = document.getElementById('c_salary');
    var cDaily  = document.getElementById('c_daily');
    var cHourly = document.getElementById('c_hourly');
    cSalary.addEventListener('input', function () { computeRates(cSalary, cDaily, cHourly); });

    var uSalary = document.getElementById('u_salary');
    var uDaily  = document.getElementById('u_daily');
    var uHourly = document.getElementById('u_hourly');
    uSalary.addEventListener('input', function () { computeRates(uSalary, uDaily, uHourly); });

    var createModal = document.getElementById('CreateModal');
    createModal.addEventListener('show.bs.modal', function () {
        document.getElementById('salaryForm').reset();
        cDaily.value = '';
        cHourly.value = '';
    });

    var updateModal = document.getElementById('UpdateModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('updateSalaryForm').action = '/salary-grades/update/' + button.getAttribute('data-id');
        document.getElementById('u_grade').value  = button.getAttribute('data-grade');
        document.getElementById('u_step').value   = button.getAttribute('data-step');
        uSalary.value = button.getAttribute('data-salary');
        document.getElementById('u_active').checked = button.getAttribute('data-active') == 1;
        computeRates(uSalary, uDaily, uHourly);
    });
});
</script>
@endsection
