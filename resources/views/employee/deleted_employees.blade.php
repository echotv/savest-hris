@extends("../layout")
@section("additional_head")
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <style>
        .tracking-tight  { letter-spacing: -0.5px; }
        .tracking-wider  { letter-spacing: 0.5px; }
        .font-12 { font-size: 12px; }
        .font-13 { font-size: 13px; }
        .emp-avatar {
            width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
            border: 2px solid #e9ecef; filter: grayscale(1); opacity: .7;
        }
        .emp-avatar-initials {
            width: 36px; height: 36px; border-radius: 50%;
            background: #888ea8; display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
            border: 2px solid #e9ecef; flex-shrink: 0;
        }
        .tbtn { width:24px; height:24px; }
    </style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Deleted Employees</h3>
            <p class="text-muted small mb-0">Employees that have been deleted. Their user accounts have been disabled.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee.employee_list') }}" class="text-muted text-decoration-none">Employees</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Deleted Employees</li>
                </ol>
            </nav>
            <a href="{{ route('employee.employee_list') }}" class="btn btn-sm btn-light-dark border d-flex align-items-center gap-1 px-3 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to List of Employees
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
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
                                <th class="border-0 ps-4 py-3">Employee</th>
                                <th class="border-0 py-3">Branch</th>
                                <th class="border-0 py-3">Position</th>
                                <th class="border-0 py-3">Employment Status</th>
                                <th class="border-0 py-3">Date Employed</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($employee->profile_picture)
                                            <img alt="avatar" class="emp-avatar"
                                                 src="{{ asset('storage/' . $employee->profile_picture) }}">
                                        @else
                                            <span class="emp-avatar-initials">{{ strtoupper(substr($employee->firstname, 0, 1)) }}</span>
                                        @endif
                                        <div>
                                            <p class="mb-0 fw-semibold font-13">{{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->suffix }} {{ $employee->middlename ? strtoupper(substr($employee->middlename, 0, 1)) . '.' : '' }}</p>
                                            <small class="text-muted">{{ $employee->employee_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-13">{{ $employee->branch_code }} {{ $employee->branch_name }}</td>
                                <td class="font-13">{{ $employee->position_name }}</td>
                                <td class="font-13">{{ $employee->empl_status }}</td>
                                <td class="font-13">{{ \Carbon\Carbon::parse($employee->date_employed)->format('M d, Y') }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('employee.employee_profile', $employee->employee_id) }}" class="border-0 bg-transparent text-secondary d-inline-flex align-items-center justify-content-center p-0 tbtn" target="_blank" title="View Employee Info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted border-0">No deleted employees.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section("additional_footer")
<script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
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
            sLengthMenu: "Display: _MENU_",
            sEmptyTable: "No deleted employees."
        },
        stripeClasses: [],
        lengthMenu: [10, 20, 50],
        pageLength: 10
    });
});
</script>
@endsection
