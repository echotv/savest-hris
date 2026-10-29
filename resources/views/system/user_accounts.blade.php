@extends("../layout")
@section("additional_head")
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .tracking-tight  { letter-spacing: -0.5px; }
        .tracking-wider  { letter-spacing: 0.5px; }
        .font-12 { font-size: 12px; }
        .font-13 { font-size: 13px; }
        .tbtn { width:24px; height:24px; }

        /* ── Editing-user identity strip ─────────────────────── */
        .edit-user-strip {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0e6ed;
            background: #f7f9ff;
            margin-bottom: 1.1rem;
        }
        body.dark .edit-user-strip {
            background: #1b2e4b;
            border-color: #253c5a;
        }
        .edit-user-avatar {
            width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #1b55e2, #00b5b5);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; font-weight: 800; color: #fff;
            letter-spacing: -0.03em; text-transform: uppercase;
        }
        .edit-user-label {
            font-size: 0.62rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #1b55e2; margin-bottom: 0.1rem;
        }
        body.dark .edit-user-label { color: #7ea8f8; }
        .edit-user-name {
            font-size: 0.92rem; font-weight: 700; color: #1a1f3a; line-height: 1.2;
        }
        body.dark .edit-user-name { color: #bfc9d4; }
    </style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">User Accounts</h3>
            <p class="text-muted small mb-0">Manage system user accounts, roles, and access status.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#registerModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                Register New User
            </button>
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">User Accounts</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- FILTER --}}
    <div class="col-12 mb-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <form id="filterForm" method="post" action="{{ route('system.filter_acc') }}">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">User Type</label>
                            <select id="filter_user" name="ut_id"
                                    onchange="document.getElementById('filterForm').submit();"
                                    class="form-select form-select-sm">
                                <option value="All" {{ ($filtered_data['type'] ?? '') == 'All' ? 'selected' : '' }}>All</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->ut_id }}" {{ ($filtered_data['ut_id'] ?? '') == $type->ut_id ? 'selected' : '' }}>
                                        {{ $type->user_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Account Status</label>
                            <select id="filter_status" name="is_active"
                                    onchange="document.getElementById('filterForm').submit();"
                                    class="form-select form-select-sm">
                                <option value="All" {{ ($filtered_data['is_active'] ?? '') == 'All' ? 'selected' : '' }}>All</option>
                                <option value="1" {{ ($filtered_data['is_active'] ?? '') == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ ($filtered_data['is_active'] ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
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
                                <th class="border-0 ps-4 py-3">Name</th>
                                <th class="border-0 py-3">Username</th>
                                <th class="border-0 py-3">Email</th>
                                <th class="border-0 py-3">User Type</th>
                                <th class="border-0 py-3">Status</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user_accounts as $user)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>{{ $user->userType->user_type ?? '' }}</td>
                                <td>
                                    @if ($user->is_active == 1)
                                        <span class="badge badge-light-success">Active</span>
                                    @else
                                        <span class="badge badge-light-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn editBtn"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-username="{{ $user->username }}"
                                        data-email="{{ $user->email }}"
                                        data-ut_id="{{ $user->ut_id }}"
                                        data-status="{{ $user->is_active }}"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Status</th>
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

{{-- Register New User Modal --}}
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    Register New User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('system.register_user') }}" id="registerUserForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="reg_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="reg_username" class="form-control form-control-sm" maxlength="20" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Email</label>
                        <input type="email" name="email" id="reg_email" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">User Type <span class="text-danger">*</span></label>
                        <select name="ut_id" id="reg_ut_id" class="form-select form-select-sm" required>
                            <option value="" disabled selected>Select user type</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->ut_id }}">{{ $type->user_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Account Status</label>
                        <select name="is_active" id="reg_status" class="form-select form-select-sm">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Password <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="password" id="reg_password"
                                   class="form-control form-control-sm" minlength="6" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleRegPassword()">
                                <i id="regToggleIcon" class="fa fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="registerUserForm" class="btn btn-primary px-4 shadow-sm">Register</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Update User Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('system.update_user') }}" id="editUserForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">

                    {{-- User identity strip --}}
                    <div class="edit-user-strip">
                        <div class="edit-user-avatar" id="edit_avatar_initial">?</div>
                        <div>
                            <div class="edit-user-label">Editing account</div>
                            <div class="edit-user-name" id="edit_name_display">—</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name"
                               class="form-control form-control-sm" maxlength="255"
                               placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Username</label>
                        <input type="text" name="username" id="edit_username"
                               class="form-control form-control-sm" maxlength="20"
                               placeholder="Enter username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Email</label>
                        <input type="email" name="email" id="edit_email"
                               class="form-control form-control-sm"
                               placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">User Type</label>
                        <select name="ut_id" id="edit_ut_id" class="form-select form-select-sm">
                            @foreach ($types as $type)
                                <option value="{{ $type->ut_id }}">{{ $type->user_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">Account Status</label>
                        <select name="is_active" id="edit_status" class="form-select form-select-sm">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">New Password</label>
                        <div class="input-group input-group-sm">
                            <input type="password" name="password" id="edit_password"
                                   class="form-control form-control-sm"
                                   placeholder="Leave blank if no change">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                <i id="toggleIcon" class="fa fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Leave blank to keep current password</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="editUserForm" class="btn btn-primary px-4 shadow-sm">Update</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script>
document.querySelectorAll('.btn[title], a.btn[title]').forEach(function(el) {
    new bootstrap.Tooltip(el, { trigger: 'hover' });
});

var editUtTomSelect, regUtTomSelect;

document.addEventListener('DOMContentLoaded', function () {
    editUtTomSelect = new TomSelect('#edit_ut_id', {
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        placeholder: 'Select user type'
    });

    regUtTomSelect = new TomSelect('#reg_ut_id', {
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        placeholder: 'Select user type'
    });

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
});

$(document).on('click', '.editBtn', function () {
    var name = $(this).data('name') || '';
    $('#edit_id').val($(this).data('id'));
    $('#edit_name_display').text(name);
    $('#edit_avatar_initial').text(name.trim().charAt(0) || '?');
    $('#edit_name').val(name);
    $('#edit_username').val($(this).data('username'));
    $('#edit_email').val($(this).data('email'));
    editUtTomSelect.setValue(String($(this).data('ut_id')));
    $('#edit_status').val($(this).data('status'));
    $('#edit_password').val('');
    $('#editModal').modal('show');
});

$(document).on('input', '#edit_name', function () {
    var name = $(this).val() || '';
    $('#edit_name_display').text(name || '—');
    $('#edit_avatar_initial').text(name.trim().charAt(0) || '?');
});

function togglePassword() {
    const field = document.getElementById("edit_password");
    const icon  = document.getElementById("toggleIcon");
    if (field.type === "password") {
        field.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        field.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

/* ── Register New User ─── */
var registerHasOldData = @json($errors->any() && old('name') ? true : false);

document.getElementById('registerModal').addEventListener('show.bs.modal', function () {
    if (registerHasOldData) {
        registerHasOldData = false;
        return;
    }
    document.getElementById('registerUserForm').reset();
    regUtTomSelect.clear();
});

@if($errors->any() && old('name'))
document.addEventListener('DOMContentLoaded', function () {
    $('#reg_name').val(@json(old('name')));
    $('#reg_username').val(@json(old('username')));
    $('#reg_email').val(@json(old('email')));
    regUtTomSelect.setValue(@json(old('ut_id')) || '');
    $('#reg_status').val(@json(old('is_active')));
    new bootstrap.Modal(document.getElementById('registerModal')).show();
});
@endif

function toggleRegPassword() {
    const field = document.getElementById("reg_password");
    const icon  = document.getElementById("regToggleIcon");
    if (field.type === "password") {
        field.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        field.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}
</script>
@endsection
