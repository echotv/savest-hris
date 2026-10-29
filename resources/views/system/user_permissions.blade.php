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
        .tracking-tight { letter-spacing: -0.5px; }
        .tracking-wider { letter-spacing: 0.5px; }
        .font-12 { font-size: 12px; }
        .tbtn { width:24px; height:24px; }

        /* ── User count pill ───────────────────────────────────── */
        .user-count-pill {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 0.2rem 0.6rem; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700;
            background: #eef2ff; color: #1b55e2;
        }
        body.dark .user-count-pill { background: rgba(27,85,226,0.15); color: #7ea8f8; }

        /* ── Type badge in table ───────────────────────────────── */
        .type-badge {
            display: inline-flex; align-items: center;
            padding: 0.22rem 0.75rem; border-radius: 20px;
            font-size: 0.74rem; font-weight: 700; letter-spacing: 0.03em;
        }

        /* ── Color picker ──────────────────────────────────────── */
        .color-picker-wrap { display: flex; flex-direction: column; gap: 0.6rem; }
        .color-swatches { display: flex; flex-wrap: wrap; gap: 6px; }
        .color-swatch {
            width: 26px; height: 26px; border-radius: 6px;
            cursor: pointer; border: 2px solid transparent;
            transition: transform 0.12s, border-color 0.12s; flex-shrink: 0;
        }
        .color-swatch:hover { transform: scale(1.18); }
        .color-swatch.selected {
            border-color: #fff;
            box-shadow: 0 0 0 2px currentColor, 0 2px 6px rgba(0,0,0,0.18);
            transform: scale(1.12);
        }
        body.dark .color-swatch.selected { border-color: #0e1726; }
        .color-custom-row { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.2rem; }
        .color-custom-row label {
            font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #888; white-space: nowrap;
        }
        input[type="color"].color-native {
            width: 30px; height: 26px; padding: 1px 2px;
            border-radius: 5px; border: 1px solid #dee2e6;
            cursor: pointer; background: none;
        }
        body.dark input[type="color"].color-native { border-color: #253c5a; background: #1b2e4b; }
        .color-hex-display {
            font-size: 0.75rem; font-weight: 700; font-family: monospace;
            color: #555; padding: 0.18rem 0.5rem; border-radius: 4px;
            background: #f4f6fa; border: 1px solid #e0e6ed;
        }
        body.dark .color-hex-display { background: #1b2e4b; border-color: #253c5a; color: #bfc9d4; }

        /* ── Badge preview ─────────────────────────────────────── */
        .badge-preview-wrap { display: flex; align-items: center; gap: 0.55rem; }
        .badge-preview-label {
            font-size: 0.63rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #aaa;
        }
        .badge-preview {
            display: inline-flex; align-items: center;
            padding: 0.22rem 0.75rem; border-radius: 20px;
            font-size: 0.74rem; font-weight: 700; letter-spacing: 0.03em;
            transition: background 0.2s, color 0.2s;
        }

        /* ── Privileges panel ──────────────────────────────────── */
        .priv-panel {
            border: 1px solid #e0e6ed; border-radius: 10px; overflow: hidden;
        }
        body.dark .priv-panel { border-color: #1b2e4b; }

        .priv-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.55rem 0.9rem;
            background: #f4f6fa; border-bottom: 1px solid #e0e6ed;
        }
        body.dark .priv-panel-header { background: #060d18; border-color: #1b2e4b; }
        .priv-panel-header span {
            font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.09em; color: #506690;
        }
        .priv-select-all-btn {
            font-size: 0.67rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.06em; color: #1b55e2; background: none;
            border: none; padding: 0; cursor: pointer;
            text-decoration: underline; text-underline-offset: 2px;
        }
        body.dark .priv-select-all-btn { color: #7ea8f8; }

        .priv-body {
            max-height: 60vh; overflow-y: auto;
            padding: 0.5rem 0; background: #fff;
        }
        body.dark .priv-body { background: #0e1726; }

        .priv-group { padding: 0.4rem 0.9rem 0.6rem; }
        .priv-group + .priv-group { border-top: 1px solid #f0f3f8; }
        body.dark .priv-group + .priv-group { border-color: #1b2e4b; }

        .priv-group-label {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.1em; color: #1b55e2;
            margin-bottom: 0.45rem; margin-top: 0.1rem;
        }
        body.dark .priv-group-label { color: #4d80e4; }
        .priv-group-label::before {
            content: ''; display: inline-block;
            width: 3px; height: 12px; border-radius: 2px;
            background: currentColor; flex-shrink: 0;
        }

        .priv-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0.1rem 0.5rem;
        }
        @media (max-width: 480px) { .priv-grid { grid-template-columns: 1fr; } }

        .priv-item {
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.28rem 0.35rem; border-radius: 5px;
            cursor: pointer; transition: background 0.1s;
        }
        .priv-item:hover { background: #f0f4ff; }
        body.dark .priv-item:hover { background: #1b2e4b; }
        .priv-item input[type="checkbox"] {
            width: 14px; height: 14px; flex-shrink: 0;
            accent-color: #1b55e2; cursor: pointer; margin: 0;
        }
        .priv-item span {
            font-size: 0.76rem; color: #3b3f5c; line-height: 1.3;
        }
        body.dark .priv-item span { color: #bfc9d4; }

        .priv-group.branch-group .priv-group-label { color: #00ab55; }
        body.dark .priv-group.branch-group .priv-group-label { color: #4dd68c; }
        .priv-group.branch-group .priv-item input { accent-color: #00ab55; }

        .priv-group.system-group .priv-group-label { color: #e7515a; }
        body.dark .priv-group.system-group .priv-group-label { color: #ff7a84; }
        .priv-group.system-group .priv-item input { accent-color: #e7515a; }

        .priv-group.assigned-group .priv-group-label { color: #e2a03f; }
        body.dark .priv-group.assigned-group .priv-group-label { color: #f0c060; }
        .priv-group.assigned-group .priv-item input { accent-color: #e2a03f; }
        .priv-group.assigned-group .priv-grid { grid-template-columns: 1fr; }

        .priv-group.timekeeping-group .priv-group-label { color: #7c3aed; }
        body.dark .priv-group.timekeeping-group .priv-group-label { color: #a78bfa; }
        .priv-group.timekeeping-group .priv-item input { accent-color: #7c3aed; }
    </style>
@endsection

@section("content")

@php
$palette = [
    '#1b55e2','#00ab55','#00b5b5','#7c3aed',
    '#e2a03f','#e7515a','#e91e8c','#888ea8',
    '#4361ee','#2196f3','#6dc52d','#3b3f5c',
];

$privGroups = [
    ['key'=>'branches',  'label'=>'Branches',          'class'=>'branch-group',
     'items'=>['VIEW BRANCHES','CREATE NEW BRANCH','UPDATE BRANCH','DELETE BRANCH']],
    ['key'=>'employees', 'label'=>'Employee Management','class'=>'',
     'items'=>['VIEW LIST OF EMPLOYEES','ADD NEW EMPLOYEE','UPDATE EMPLOYEE INFO','UPDATE EMPLOYMENT STATUS',
               'UPDATE PROFILE PICTURE','VIEW EMPLOYEE WEEKLY SCHEDULES','UPDATE EMPLOYEE WEEKLY SCHEDULES',
               'UPDATE EMPLOYEE ID','VIEW UPCOMING BIRTHDAYS','VIEW UPCOMING WORK ANNIVERSARIES',
               'VIEW BIOMETRIC IDS','ADD BIOMETRIC IDS','UPDATE BIOMETRIC IDS','DELETE BIOMETRIC IDS','DELETE EMPLOYEE']],
    ['key'=>'memos',     'label'=>'Employee Memos',     'class'=>'',
     'items'=>['VIEW EMPLOYEE MEMOS','ADD EMPLOYEE MEMOS','UPDATE EMPLOYEE MEMOS','DELETE EMPLOYEE MEMOS']],
    ['key'=>'evaluations','label'=>'Performance Evaluation', 'class'=>'',
     'items'=>['VIEW PERFORMANCE EVALUATIONS','ADD PERFORMANCE EVALUATIONS','UPDATE PERFORMANCE EVALUATIONS','DELETE PERFORMANCE EVALUATIONS']],
    ['key'=>'schedules', 'label'=>'Weekly Schedules',   'class'=>'',
     'items'=>['VIEW WEEKLY SCHEDULES','UPDATE WEEKLY SCHEDULES']],
    ['key'=>'salary',    'label'=>'Salary Grade',       'class'=>'',
     'items'=>['VIEW SALARY GRADE','ADD NEW SALARY GRADE','UPDATE SALARY GRADE']],
    ['key'=>'positions', 'label'=>'Company Positions',  'class'=>'',
     'items'=>['VIEW COMPANY POSITIONS','EDIT COMPANY POSITIONS','RESTORE AND ARCHIVE COMPANY POSITION']],
    ['key'=>'empstatus', 'label'=>'Employment Status',  'class'=>'',
     'items'=>['VIEW EMPLOYMENT STATUS','ADD NEW EMPLOYMENT STATUS','UPDATE EMPLOYMENT STATUS']],
    ['key'=>'system',    'label'=>'System Settings',    'class'=>'system-group',
     'items'=>['VIEW USER ACCOUNTS','VIEW USER TYPE & PERMISSIONS','VIEW SYSTEM LOGS']],
    ['key'=>'holidays',  'label'=>'Holidays & Non-Working Days', 'class'=>'timekeeping-group',
     'items'=>['VIEW HOLIDAYS & NON-WORKING DAYS','ADD HOLIDAYS & NON-WORKING DAYS',
               'UPDATE HOLIDAYS & NON-WORKING DAYS','DELETE HOLIDAYS & NON-WORKING DAYS']],
    ['key'=>'suspensions','label'=>'Suspended Employees', 'class'=>'timekeeping-group',
     'items'=>['VIEW SUSPENDED EMPLOYEES','ADD SUSPENDED EMPLOYEES',
               'UPDATE SUSPENDED EMPLOYEES','DELETE SUSPENDED EMPLOYEES']],
    ['key'=>'travel',    'label'=>'On-Travel & Seminar Requests','class'=>'timekeeping-group',
     'items'=>['VIEW ON-TRAVEL & SEMINAR REQUESTS','ADD ON-TRAVEL & SEMINAR REQUESTS',
               'UPDATE ON-TRAVEL & SEMINAR REQUESTS','DELETE ON-TRAVEL & SEMINAR REQUESTS']],
    ['key'=>'branchvisits','label'=>'Branch Visits',    'class'=>'timekeeping-group',
     'items'=>['VIEW BRANCH VISITS','ADD BRANCH VISITS',
               'UPDATE BRANCH VISITS','DELETE BRANCH VISITS']],
];
@endphp

<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">User Types & Privileges</h3>
            <p class="text-muted small mb-0">Manage user types and their system access privileges.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">User Permissions</li>
                </ol>
            </nav>
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#CreateModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Create User Type
            </button>
        </div>
    </div>

    {{-- ALERTS --}}
    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
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
                    <table id="zero-config" class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">User Type</th>
                                <th class="border-0 py-3">User Accounts</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($types as $type)
                            @php
                                $userPrivileges   = \App\Models\UserPrivilegeModel::where('ut_id', $type->ut_id)->pluck('privilege')->toArray();
                                $assignedBranches = \App\Models\AssignedBranchModel::where('ut_id', $type->ut_id)->pluck('branch_code')->toArray();
                                $badgeColor       = $type->color_code ?? '#1b55e2';
                                $userCount        = \App\Models\User::withoutDeletedEmployees()->where('ut_id', $type->ut_id)->count();
                            @endphp
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    <span class="type-badge" style="background:{{ $badgeColor }};color:#fff;">
                                        {{ $type->user_type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="user-count-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        {{ $userCount }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="border-0 bg-transparent text-primary d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        data-bs-toggle="modal" data-bs-target="#AccountsModal"
                                        data-id="{{ $type->ut_id }}"
                                        data-name="{{ $type->user_type }}"
                                        title="View Accounts">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </button>
                                    <button class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $type->ut_id }}" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <form method="POST" action="/user-type/delete/{{ $type->ut_id }}" class="d-inline">
                                        @csrf
                                        <button class="border-0 bg-transparent text-danger d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                            onclick="return confirm('Delete this user type?')" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- ── Edit Modal ──────────────────────────────── --}}
                            <div class="modal fade" id="editModal{{ $type->ut_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header py-3">
                                            <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                Edit User Type
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="/user-type/update/{{ $type->ut_id }}">
                                            @csrf
                                            <div class="modal-body p-4">

                                                {{-- Name --}}
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-secondary small">User Type Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="user_type"
                                                           value="{{ $type->user_type }}"
                                                           maxlength="50" required
                                                           class="form-control form-control-sm edit-type-name-input"
                                                           data-modal="{{ $type->ut_id }}"
                                                           oninput="updateBadgePreview('{{ $type->ut_id }}')"
                                                           required>
                                                </div>

                                                {{-- Badge color --}}
                                                <div class="mb-4">
                                                    <label class="form-label fw-medium text-secondary small">Badge Color</label>
                                                    <div class="color-picker-wrap">
                                                        <div class="color-swatches" id="swatches_{{ $type->ut_id }}">
                                                            @foreach($palette as $hex)
                                                            <div class="color-swatch {{ $badgeColor === $hex ? 'selected' : '' }}"
                                                                 style="background:{{ $hex }};" data-color="{{ $hex }}"
                                                                 onclick="pickColor('{{ $type->ut_id }}','{{ $hex }}')"></div>
                                                            @endforeach
                                                        </div>
                                                        <div class="color-custom-row">
                                                            <label>Custom</label>
                                                            <input type="color" class="color-native"
                                                                   id="colorNative_{{ $type->ut_id }}"
                                                                   value="{{ $badgeColor }}"
                                                                   oninput="pickColor('{{ $type->ut_id }}', this.value, true)">
                                                            <span class="color-hex-display" id="hexDisplay_{{ $type->ut_id }}">{{ strtoupper($badgeColor) }}</span>
                                                        </div>
                                                        <div class="badge-preview-wrap">
                                                            <span class="badge-preview-label">Preview</span>
                                                            <span class="badge-preview" id="badgePreview_{{ $type->ut_id }}">{{ $type->user_type }}</span>
                                                        </div>
                                                        <input type="hidden" name="badge_color" id="badgeColorInput_{{ $type->ut_id }}" value="{{ $badgeColor }}">
                                                    </div>
                                                </div>

                                                {{-- Privileges --}}
                                                <div class="mb-1">
                                                    <label class="form-label fw-medium text-secondary small text-uppercase mb-2">Privileges & Access</label>
                                                    <div class="priv-panel">
                                                        <div class="priv-panel-header">
                                                            <span>Assign permissions for this user type</span>
                                                            <button type="button" class="priv-select-all-btn" onclick="toggleAllPrivileges('e{{ $type->ut_id }}', this)">Select All</button>
                                                        </div>
                                                        <div class="priv-body">
                                                            @foreach($privGroups as $group)
                                                            <div class="priv-group {{ $group['class'] }}">
                                                                <div class="priv-group-label">{{ $group['label'] }}</div>
                                                                <div class="priv-grid">
                                                                    @foreach($group['items'] as $priv)
                                                                    @php $eid = 'e'.$type->ut_id.'_'.Str::slug($priv); @endphp
                                                                    <label class="priv-item" for="{{ $eid }}">
                                                                        <input type="checkbox" class="priv-chk-e{{ $type->ut_id }}" name="privileges[]" value="{{ $priv }}" id="{{ $eid }}" {{ in_array($priv, $userPrivileges) ? 'checked' : '' }}>
                                                                        <span>{{ ucwords(strtolower($priv)) }}</span>
                                                                    </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            {{-- Assigned Branches --}}
                                                            <div class="priv-group assigned-group">
                                                                <div class="priv-group-label">Assigned Branches</div>
                                                                <div class="priv-grid">
                                                                    @foreach($branches as $branch)
                                                                    @php $bid = 'e'.$type->ut_id.'_br_'.$branch->branch_code; @endphp
                                                                    <label class="priv-item" for="{{ $bid }}">
                                                                        <input type="checkbox" name="assigned_branches[]" value="{{ $branch->branch_code }}" id="{{ $bid }}" {{ in_array($branch->branch_code, $assignedBranches) ? 'checked' : '' }}>
                                                                        <span>{{ $branch->branch_name }} <span class="text-muted" style="font-size:0.68rem;">({{ $branch->branch_code }})</span></span>
                                                                    </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer py-2">
                                                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                                                <button type="submit" class="btn btn-primary px-4 shadow-sm">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Create Modal ──────────────────────────────────────────── --}}
<div class="modal fade" id="CreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    Create User Type
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user_type.store') }}">
                @csrf
                <div class="modal-body p-4">

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small">User Type Name <span class="text-danger">*</span></label>
                        <input type="text" name="user_type" id="createTypeName"
                               class="form-control form-control-sm"
                               placeholder="e.g. HR Admin"
                               maxlength="50" required
                               oninput="updateBadgePreview('create')"
                               required>
                    </div>

                    {{-- Badge color --}}
                    <div class="mb-4">
                        <label class="form-label fw-medium text-secondary small">Badge Color</label>
                        <div class="color-picker-wrap">
                            <div class="color-swatches" id="swatches_create">
                                @foreach($palette as $i => $hex)
                                <div class="color-swatch {{ $i === 0 ? 'selected' : '' }}"
                                     style="background:{{ $hex }};" data-color="{{ $hex }}"
                                     onclick="pickColor('create','{{ $hex }}')"></div>
                                @endforeach
                            </div>
                            <div class="color-custom-row">
                                <label>Custom</label>
                                <input type="color" class="color-native" id="colorNative_create" value="#1b55e2"
                                       oninput="pickColor('create', this.value, true)">
                                <span class="color-hex-display" id="hexDisplay_create">#1B55E2</span>
                            </div>
                            <div class="badge-preview-wrap">
                                <span class="badge-preview-label">Preview</span>
                                <span class="badge-preview" id="badgePreview_create">New Type</span>
                            </div>
                            <input type="hidden" name="badge_color" id="badgeColorInput_create" value="#1b55e2">
                        </div>
                    </div>

                    {{-- Privileges --}}
                    <div class="mb-1">
                        <label class="form-label fw-medium text-secondary small text-uppercase mb-2">Privileges & Access</label>
                        <div class="priv-panel">
                            <div class="priv-panel-header">
                                <span>Assign permissions for this user type</span>
                                <button type="button" class="priv-select-all-btn" onclick="toggleAllPrivileges('c', this)">Select All</button>
                            </div>
                            <div class="priv-body">
                                @foreach($privGroups as $group)
                                <div class="priv-group {{ $group['class'] }}">
                                    <div class="priv-group-label">{{ $group['label'] }}</div>
                                    <div class="priv-grid">
                                        @foreach($group['items'] as $priv)
                                        @php $cid = 'c_'.Str::slug($priv); @endphp
                                        <label class="priv-item" for="{{ $cid }}">
                                            <input type="checkbox" class="priv-chk-c" name="privileges[]" value="{{ $priv }}" id="{{ $cid }}">
                                            <span>{{ ucwords(strtolower($priv)) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                                {{-- Assigned Branches --}}
                                <div class="priv-group assigned-group">
                                    <div class="priv-group-label">Assigned Branches</div>
                                    <div class="priv-grid">
                                        @foreach($branches as $branch)
                                        <label class="priv-item" for="c_br_{{ $branch->branch_code }}">
                                            <input type="checkbox" name="assigned_branches[]" value="{{ $branch->branch_code }}" id="c_br_{{ $branch->branch_code }}">
                                            <span>{{ $branch->branch_name }} <span class="text-muted" style="font-size:0.68rem;">({{ $branch->branch_code }})</span></span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section("additional_footer")

{{-- Accounts Modal --}}
<div id="AccountsModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span id="accounts_modal_title">User Accounts</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 border-bottom">
                    <input type="text" class="form-control form-control-sm" id="accounts_search" placeholder="Search name, username, email...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Name</th>
                                <th class="border-0 py-3">Username</th>
                                <th class="border-0 py-3">Email</th>
                                <th class="border-0 pe-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody id="accounts_tbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 justify-content-between">
                <span class="text-muted small" id="accounts_count"></span>
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal   = document.getElementById('AccountsModal');
    var tbody   = document.getElementById('accounts_tbody');
    var search  = document.getElementById('accounts_search');
    var countEl = document.getElementById('accounts_count');

    function esc(v) {
        var d = document.createElement('div');
        d.textContent = v == null ? '' : v;
        return d.innerHTML;
    }

    function setMessage(msg) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">' + msg + '</td></tr>';
    }

    function updateCount() {
        var rows = tbody.querySelectorAll('tr[data-search]');
        var shown = 0;
        rows.forEach(function (r) { if (r.style.display !== 'none') shown++; });
        countEl.textContent = rows.length ? ('Showing ' + shown + ' of ' + rows.length + ' account(s)') : '';
    }

    modal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('accounts_modal_title').textContent = 'User Accounts - ' + button.getAttribute('data-name');
        search.value = '';
        countEl.textContent = '';
        setMessage('Loading...');

        fetch('/user-type/accounts/' + button.getAttribute('data-id'), { headers: { 'Accept': 'application/json' } })
            .then(function (res) {
                if (!res.ok) throw new Error(res.status);
                return res.json();
            })
            .then(function (list) {
                if (!list.length) {
                    setMessage('No accounts assigned to this user type.');
                    return;
                }
                tbody.innerHTML = list.map(function (u) {
                    var active = Number(u.is_active) === 1;
                    var status = active
                        ? '<span class="badge badge-light-success">Active</span>'
                        : '<span class="badge badge-light-danger">Inactive</span>';
                    var hay = [u.name, u.username, u.email, active ? 'active' : 'inactive'].join(' ').toLowerCase();
                    return '<tr data-search="' + esc(hay) + '">' +
                        '<td class="ps-4 fw-semibold" style="font-size:13px">' + esc(u.name) + '</td>' +
                        '<td style="font-size:13px">' + esc(u.username) + '</td>' +
                        '<td style="font-size:13px">' + esc(u.email) + '</td>' +
                        '<td class="pe-4">' + status + '</td>' +
                        '</tr>';
                }).join('');
                updateCount();
            })
            .catch(function () {
                setMessage('Failed to load accounts. Please try again.');
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

<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
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
            sLengthMenu: "Display: _MENU_"
        },
        stripeClasses: [],
        lengthMenu: [10, 20, 50],
        pageLength: 10
    });

    /* Init badge previews for all edit modals */
    document.querySelectorAll('[id^="badgeColorInput_"]').forEach(function (inp) {
        var id = inp.id.replace('badgeColorInput_', '');
        if (id !== 'create') applyColor(id, inp.value);
    });
    applyColor('create', '#1b55e2');
});

/* ── Color picker ────────────────────────────────────────────── */
function applyColor(modalId, hex) {
    var safeHex = hex;
    if (!/^#[0-9a-fA-F]{6}$/.test(hex) && /^#[0-9a-fA-F]{3}$/.test(hex)) {
        safeHex = '#' + hex[1]+hex[1]+hex[2]+hex[2]+hex[3]+hex[3];
    }
    var badge = document.getElementById('badgePreview_' + modalId);
    var input = document.getElementById('badgeColorInput_' + modalId);
    var disp  = document.getElementById('hexDisplay_' + modalId);
    var nat   = document.getElementById('colorNative_' + modalId);
    if (badge) { badge.style.background = safeHex; badge.style.color = '#fff'; }
    if (input) input.value = safeHex;
    if (disp)  disp.textContent = safeHex.toUpperCase();
    if (nat && nat.value !== safeHex) nat.value = safeHex;
    var container = document.getElementById('swatches_' + modalId);
    if (!container) return;
    container.querySelectorAll('.color-swatch').forEach(function (sw) {
        sw.classList.toggle('selected', sw.dataset.color.toLowerCase() === safeHex.toLowerCase());
    });
}

function pickColor(modalId, hex, fromNative) {
    if (fromNative) {
        var c = document.getElementById('swatches_' + modalId);
        if (c) c.querySelectorAll('.color-swatch').forEach(function(sw){ sw.classList.remove('selected'); });
    }
    applyColor(modalId, hex);
}

function updateBadgePreview(modalId) {
    var nameEl = modalId === 'create'
        ? document.getElementById('createTypeName')
        : document.querySelector('#editModal' + modalId + ' .edit-type-name-input');
    var badge = document.getElementById('badgePreview_' + modalId);
    if (badge && nameEl) {
        badge.textContent = nameEl.value.trim() || (modalId === 'create' ? 'New Type' : 'User Type');
    }
}

/* ── Select All / Deselect All toggle ────────────────────────── */
function toggleAllPrivileges(prefix, btn) {
    var boxes = document.querySelectorAll('.priv-chk-' + prefix);
    var allChecked = Array.from(boxes).every(function(b){ return b.checked; });
    boxes.forEach(function(b){ b.checked = !allChecked; });
    btn.textContent = allChecked ? 'Select All' : 'Deselect All';
}
</script>
@endsection
