@extends("../layout")
@section("additional_head")
<link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<style>
    .tracking-tight { letter-spacing: -0.5px; }
    .font-12 { font-size: 12px; }

    /* ── Profile banner ────────────────────────────────────────── */
    .profile-banner {
        height: 110px; border-radius: 14px 14px 0 0;
        background: linear-gradient(135deg, #1b55e2 0%, #00b5b5 100%);
        position: relative; flex-shrink: 0;
    }
    body.dark .profile-banner {
        background: linear-gradient(135deg, #0f2d8a 0%, #005f6f 100%);
    }
    .profile-hero {
        border-radius: 14px; border: 1px solid #e0e6ed;
        background: #fff; overflow: hidden; margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    body.dark .profile-hero { background: #0e1726; border-color: #1b2e4b; }

    .profile-hero-body {
        padding: 20px 1.6rem 1.4rem;
        display: flex; flex-wrap: wrap;
        align-items: flex-end; gap: 1.2rem;
    }
    .profile-avatar-wrap {
        margin-top: -44px; flex-shrink: 0;
    }
    .profile-avatar {
        width: 88px; height: 88px; border-radius: 50%;
        border: 4px solid #fff;
        background: linear-gradient(135deg, #1b55e2, #00b5b5);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 800; color: #fff;
        text-transform: uppercase; letter-spacing: -0.05em;
        box-shadow: 0 4px 16px rgba(27,85,226,0.22);
        overflow: hidden;
    }
    body.dark .profile-avatar { border-color: #0e1726; }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }

    .profile-meta { flex: 1; min-width: 0; padding-bottom: 0.2rem; }
    .profile-name {
        font-size: 1.22rem; font-weight: 800; color: #1a1f3a;
        letter-spacing: -0.4px; line-height: 1.2; margin-bottom: 0.25rem;
    }
    body.dark .profile-name { color: #e0e6ed; }
    .profile-sub {
        font-size: 0.8rem; color: #506690; font-weight: 500;
    }
    body.dark .profile-sub { color: #506690; }

    .profile-badges { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem; }
    .profile-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.22rem 0.65rem; border-radius: 20px;
        font-size: 0.7rem; font-weight: 700; letter-spacing: 0.03em;
    }
    .badge-id   { background: #eef2ff; color: #1b55e2; }
    .badge-pos  { background: #e8fff3; color: #00ab55; }
    .badge-brnch{ background: #fff8ec; color: #e2a03f; }
    body.dark .badge-id   { background: rgba(27,85,226,0.15); color: #7ea8f8; }
    body.dark .badge-pos  { background: rgba(0,171,85,0.12);  color: #4dd68c; }
    body.dark .badge-brnch{ background: rgba(226,160,63,0.13); color: #f0c060; }

    .profile-status-wrap {
        display: flex; flex-direction: column; align-items: flex-end;
        gap: 0.4rem; margin-left: auto; padding-bottom: 0.3rem;
    }
    .status-pill {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.28rem 0.9rem; border-radius: 20px;
        font-size: 0.74rem; font-weight: 700;
    }
    .tenure-chip {
        font-size: 0.7rem; font-weight: 600; color: #506690;
    }
    body.dark .tenure-chip { color: #506690; }

    /* ── Section cards ─────────────────────────────────────────── */
    .section-card {
        border-radius: 12px; border: 1px solid #e0e6ed;
        background: #fff; margin-bottom: 1.5rem;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    body.dark .section-card { background: #0e1726; border-color: #1b2e4b; }

    .section-card-hdr {
        display: flex; align-items: center; gap: 0.7rem;
        padding: 0.75rem 1.3rem;
        background: #f4f6fa; border-bottom: 1px solid #e0e6ed;
    }
    body.dark .section-card-hdr { background: #060d18; border-color: #1b2e4b; }

    .section-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .section-title {
        font-size: 0.78rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.09em; color: #1a1f3a;
    }
    body.dark .section-title { color: #e0e6ed; }
    .section-sub {
        font-size: 0.68rem; color: #506690; margin-top: 0.05rem;
    }

    .section-body { padding: 1.1rem 1.3rem; }

    /* ── Field grid ────────────────────────────────────────────── */
    .field-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem 1.2rem;
    }
    @media (max-width: 768px) { .field-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .field-grid { grid-template-columns: 1fr; } }
    .field-grid-2 { grid-template-columns: repeat(2, 1fr); }
    .field-grid-4 { grid-template-columns: repeat(4, 1fr); }
    @media (max-width: 768px) {
        .field-grid-2, .field-grid-4 { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .field-grid-2, .field-grid-4 { grid-template-columns: 1fr; }
    }

    .field-item { display: flex; flex-direction: column; gap: 0.2rem; }
    .field-label {
        font-size: 0.67rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.08em; color: #888ea8;
    }
    body.dark .field-label { color: #506690; }
    .field-value {
        font-size: 0.85rem; font-weight: 600; color: #1a1f3a;
        word-break: break-word;
    }
    body.dark .field-value { color: #bfc9d4; }
    .field-value.monospace { font-family: 'SFMono-Regular', Consolas, monospace; font-size: 0.8rem; }
    .field-value.text-muted-val { color: #888ea8; font-weight: 500; }
    body.dark .field-value.text-muted-val { color: #506690; }

    .field-span-full { grid-column: 1 / -1; }

    /* ── Sub-section divider ───────────────────────────────────── */
    .subsection-label {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.1em; color: #1b55e2;
        margin: 1rem 0 0.6rem;
    }
    body.dark .subsection-label { color: #4d80e4; }
    .subsection-label::before {
        content: ''; width: 3px; height: 12px; border-radius: 2px;
        background: currentColor; flex-shrink: 0;
    }
    .subsection-label::after {
        content: ''; flex: 1; height: 1px; background: #e0e6ed;
    }
    body.dark .subsection-label::after { background: #1b2e4b; }

    /* ── Tables ────────────────────────────────────────────────── */
    .ev-table {
        width: 100%; border-collapse: separate; border-spacing: 0;
        font-size: 0.82rem;
    }
    .ev-table thead tr th {
        background: #f4f6fa; color: #506690;
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.09em; padding: 0.6rem 0.9rem;
        border-bottom: 1px solid #e0e6ed; border-top: none;
        white-space: nowrap;
    }
    body.dark .ev-table thead tr th { background: #060d18; border-color: #1b2e4b; color: #506690; }
    .ev-table tbody tr td {
        padding: 0.65rem 0.9rem; color: #1a1f3a;
        border-bottom: 1px solid #f0f3f8; vertical-align: middle;
    }
    body.dark .ev-table tbody tr td { color: #bfc9d4; border-color: #1b2e4b; }
    .ev-table tbody tr:last-child td { border-bottom: none; }
    .ev-table tbody tr:hover td { background: #f8faff; }
    body.dark .ev-table tbody tr:hover td { background: rgba(27,46,75,0.5); }
    .ev-table tbody .empty-row td {
        text-align: center; color: #888ea8; font-style: italic;
        padding: 1.5rem; font-size: 0.8rem;
    }

    /* ── Actions bar ───────────────────────────────────────────── */
    .actions-bar {
        display: flex; justify-content: flex-end; align-items: center;
        gap: 0.75rem; padding: 0.85rem 1.3rem;
        background: #f4f6fa; border-top: 1px solid #e0e6ed;
        border-radius: 0 0 12px 12px;
    }
    body.dark .actions-bar { background: #060d18; border-color: #1b2e4b; }

    @media (max-width: 576px) {
        .profile-status-wrap { align-items: flex-start; margin-left: 0; }
        .profile-hero-body { align-items: flex-start; }
    }
</style>
@endsection

@section("content")

@php
use Carbon\Carbon;

$fullName = trim(
    $employee->firstname . ' ' .
    ($employee->middlename ? $employee->middlename[0].'. ' : '') .
    $employee->lastname .
    ($employee->suffix ? ' '.$employee->suffix : '')
);
$initials = strtoupper(
    substr($employee->firstname ?? '', 0, 1) .
    substr($employee->lastname  ?? '', 0, 1)
);

// Current status = the "Present" (no end date) record on Employment Status Movement,
// which Employment Status Movement keeps synced onto employee.es_id. The employee
// table's own `employment_status` text column is legacy/unused and left blank, so it
// must not be read here — that's what showed up as "Unknown" on this page.
$empStatus    = $employee->empl_status ?? '';
$statusLabel  = $empStatus ?: 'Unknown';
$statusBg     = match($empStatus) {
    'Permanent','Regular' => '#00ab55',
    'Probationary'        => '#e2a03f',
    'Resigned'            => '#e7515a',
    'Contractual'         => '#00b5b5',
    default               => '#888ea8',
};

$dateEmployed = Carbon::parse($employee->date_employed, 'Asia/Manila');
$endDate = $empStatus === 'Resigned'
    ? Carbon::parse($employee->date_resigned, 'Asia/Manila')
    : Carbon::now('Asia/Manila');
$diff = $dateEmployed->diff($endDate);
$tenure = $diff->y.'y '.$diff->m.'m '.$diff->d.'d';

$hasPhoto = !empty($employee->profile_picture);
@endphp

<div class="row layout-top-spacing">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Employee Profile</h3>
            <p class="text-muted small mb-0">Viewing detailed information for this employee.</p>
        </div>
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employee.employee_list') }}" class="text-muted text-decoration-none">Employees</a></li>
                <li class="breadcrumb-item active text-primary" aria-current="page">View Profile</li>
            </ol>
        </nav>
    </div>

    <div class="col-12">

        {{-- ── PROFILE HERO ──────────────────────────────────────── --}}
        <div class="profile-hero">
            <div class="profile-banner"></div>
            <div class="profile-hero-body">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        @if($hasPhoto)
                            <img src="{{ Storage::url($employee->profile_picture) }}" alt="{{ $fullName }}">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                </div>
                <div class="profile-meta">
                    <div class="profile-name">{{ $fullName }}</div>
                    <div class="profile-sub">{{ $employee->position_name ?? '—' }}</div>
                    <div class="profile-badges">
                        <span class="profile-badge badge-id">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                            {{ $employee->employee_number }}
                        </span>
                        @if($employee->branch_name)
                        <span class="profile-badge badge-brnch">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            {{ $employee->branch_code }} — {{ $employee->branch_name }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="profile-status-wrap">
                    <span class="status-pill" style="background:{{ $statusBg }}1a;color:{{ $statusBg }};">
                        <span style="width:7px;height:7px;border-radius:50%;background:{{ $statusBg }};display:inline-block;"></span>
                        {{ $statusLabel }}
                    </span>
                    <span class="tenure-chip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;margin-right:3px;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Tenure: {{ $tenure }}
                    </span>
                    <span class="tenure-chip">
                        {{ $employee->account_status ? 'Account: Active' : 'Account: Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ── PERSONAL DATA ─────────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-hdr">
                <div class="section-icon" style="background:rgba(27,85,226,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1b55e2" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <div class="section-title">Personal Data</div>
                </div>
            </div>
            <div class="section-body">
                <div class="subsection-label" style="margin-top:0;">Basic Information</div>
                <div class="field-grid">
                    <div class="field-item">
                        <div class="field-label">Last Name</div>
                        <div class="field-value">{{ $employee->lastname }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">First Name</div>
                        <div class="field-value">{{ $employee->firstname }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Middle Name</div>
                        <div class="field-value">{{ $employee->middlename ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Suffix</div>
                        <div class="field-value">{{ $employee->suffix ?: 'N/A' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Gender</div>
                        <div class="field-value">{{ $employee->gender }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Civil Status</div>
                        <div class="field-value">{{ $employee->civil_status }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Date of Birth</div>
                        <div class="field-value">{{ $employee->date_of_birth }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Place of Birth</div>
                        <div class="field-value">{{ $employee->place_of_birth }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Religion</div>
                        <div class="field-value">{{ $employee->religion ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Citizenship</div>
                        <div class="field-value">{{ $employee->citizenship }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Height (cm)</div>
                        <div class="field-value">{{ $employee->height ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Weight (kg)</div>
                        <div class="field-value">{{ $employee->weight ?: '—' }}</div>
                    </div>
                </div>

                <div class="subsection-label">Contact Information</div>
                <div class="field-grid">
                    <div class="field-item">
                        <div class="field-label">Email Address</div>
                        <div class="field-value">{{ $employee->personal_email ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Telephone No.</div>
                        <div class="field-value">{{ $employee->telephone_no ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Cellphone No.</div>
                        <div class="field-value">{{ $employee->cellphone_no ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Emergency Contact Person</div>
                        <div class="field-value">{{ $employee->eci_name ?: '—' }}</div>
                    </div>
                    <div class="field-item" style="grid-column: span 2;">
                        <div class="field-label">Emergency Contact No. & Address</div>
                        <div class="field-value">{{ $employee->eci_cnumber ?: '—' }}</div>
                    </div>
                </div>

                <div class="subsection-label">Government IDs</div>
                <div class="field-grid">
                    <div class="field-item">
                        <div class="field-label">PAGIBIG</div>
                        <div class="field-value monospace">{{ $employee->pagibig ?? '____-____-____' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">PhilHealth</div>
                        <div class="field-value monospace">{{ $employee->philhealth ?? '__-_________-_' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">TIN</div>
                        <div class="field-value monospace">{{ $employee->tin ?? '___-___-___-___' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">National ID</div>
                        <div class="field-value monospace">{{ $employee->national_id ?? '___-___-___-___' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">SSS</div>
                        <div class="field-value monospace">{{ $employee->sss ?? '__-_______-_' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Driver's License No.</div>
                        <div class="field-value monospace">{{ $employee->drivers_license_no ?: '—' }}</div>
                    </div>
                </div>

                <div class="subsection-label">Home Address</div>
                <div class="field-grid field-grid-4">
                    <div class="field-item">
                        <div class="field-label">Street</div>
                        <div class="field-value">{{ $employee->street ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Barangay</div>
                        <div class="field-value">{{ $employee->barangay ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">City / Municipality</div>
                        <div class="field-value">{{ $employee->city ?: '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Province</div>
                        <div class="field-value">{{ $employee->province ?: '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── FAMILY BACKGROUND ─────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-hdr">
                <div class="section-icon" style="background:rgba(0,171,85,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#00ab55" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div>
                    <div class="section-title">Family Background</div>
                </div>
            </div>
            <div class="section-body">
                <div class="subsection-label" style="margin-top:0;">Spouse</div>
                <div class="field-grid">
                    <div class="field-item">
                        <div class="field-label">Spouse's Name</div>
                        <div class="field-value">{{ $employee->spouse_name ?? 'N/A' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Spouse's Occupation</div>
                        <div class="field-value">{{ $employee->spouse_occupation ?? 'N/A' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Spouse's Employer</div>
                        <div class="field-value">{{ $employee->spouse_empl_name ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="subsection-label">Parents</div>
                <div class="field-grid field-grid-2">
                    <div class="field-item">
                        <div class="field-label">Father's Name</div>
                        <div class="field-value">{{ $employee->father_name ?? '—' }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Mother's Name</div>
                        <div class="field-value">{{ $employee->mother_name ?? '—' }}</div>
                    </div>
                </div>

                <div class="subsection-label">Children</div>
                <div class="field-value" style="white-space: pre-wrap; line-height: 1.6;">{{ $employee->childrens ?? 'N/A' }}</div>
            </div>
        </div>

        {{-- ── EMPLOYMENT DETAILS ────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-hdr">
                <div class="section-icon" style="background:rgba(226,160,63,0.12);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e2a03f" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                </div>
                <div>
                    <div class="section-title">Employment Details</div>
                </div>
            </div>
            <div class="section-body">
                <div class="field-grid">
                    <div class="field-item">
                        <div class="field-label">Employee ID</div>
                        <div class="field-value monospace">{{ $employee->employee_number }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Date Employed</div>
                        <div class="field-value">{{ $employee->date_employed }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Employment Status</div>
                        <div class="field-value">
                            <span style="display:inline-flex;align-items:center;gap:5px;padding:0.2rem 0.65rem;border-radius:20px;font-size:0.73rem;font-weight:700;background:{{ $statusBg }}1a;color:{{ $statusBg }};">
                                <span style="width:6px;height:6px;border-radius:50%;background:{{ $statusBg }};display:inline-block;"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Tenure</div>
                        <div class="field-value">{{ $diff->y }} yrs, {{ $diff->m }} mos, {{ $diff->d }} days</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Branch</div>
                        <div class="field-value">{{ $employee->branch_code }} — {{ $employee->branch_name }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Position</div>
                        <div class="field-value">{{ $employee->position_name }}</div>
                    </div>
                    <div class="field-item">
                        <div class="field-label">Account Status</div>
                        <div class="field-value">
                            @if($employee->account_status)
                                <span style="display:inline-flex;align-items:center;gap:5px;padding:0.2rem 0.65rem;border-radius:20px;font-size:0.73rem;font-weight:700;background:rgba(0,171,85,0.12);color:#00ab55;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#00ab55;display:inline-block;"></span>
                                    Active
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:5px;padding:0.2rem 0.65rem;border-radius:20px;font-size:0.73rem;font-weight:700;background:rgba(231,81,90,0.12);color:#e7515a;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#e7515a;display:inline-block;"></span>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── EDUCATIONAL BACKGROUND ────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-hdr">
                <div class="section-icon" style="background:rgba(0,181,181,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#00b5b5" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
                <div>
                    <div class="section-title">Educational Background</div>
                </div>
            </div>

            <div style="padding: 1rem 1.3rem 0.5rem;">
                <div class="subsection-label" style="margin-top:0;">College</div>
            </div>
            <div class="table-responsive">
                <table class="ev-table">
                    <thead>
                        <tr>
                            <th>Name of School</th>
                            <th>Course / Major</th>
                            <th style="text-align:center;">Year Graduated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($colleges as $college)
                        <tr>
                            <td>{{ $college->name_of_school }}</td>
                            <td>{{ $college->course }}</td>
                            <td style="text-align:center;">{{ $college->year_graduated }}</td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="3">No records found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 1rem 1.3rem 0.5rem; border-top: 1px solid #f0f3f8;">
                <div class="subsection-label" style="margin-top:0;">Graduate Studies</div>
            </div>
            <div class="table-responsive">
                <table class="ev-table">
                    <thead>
                        <tr>
                            <th>Name of School</th>
                            <th>School Address</th>
                            <th>Course / Major</th>
                            <th style="text-align:center;">Year Graduated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grad_studies as $grad)
                        <tr>
                            <td>{{ $grad->gs_name_of_school }}</td>
                            <td>{{ $grad->gs_sch_address }}</td>
                            <td>{{ $grad->gs_course }}</td>
                            <td style="text-align:center;">{{ $grad->gs_year_graduated }}</td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="4">No records found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── EMPLOYMENT RECORD ─────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-hdr">
                <div class="section-icon" style="background:rgba(124,58,237,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div>
                    <div class="section-title">Employment Record</div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="ev-table">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Position</th>
                            <th style="text-align:right;">Salary (Php)</th>
                            <th style="text-align:center;">Date Started</th>
                            <th style="text-align:center;">Date Ended</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($work_experiences as $we)
                        <tr>
                            <td>{{ $we->we_company_name }}</td>
                            <td>{{ $we->we_position }}</td>
                            <td style="text-align:right; font-family:monospace;">{{ number_format($we->we_salary, 2) }}</td>
                            <td style="text-align:center;">{{ $we->we_date_started }}</td>
                            <td style="text-align:center;">{{ $we->we_date_ended }}</td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="5">No records found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── CHARACTER REFERENCES ──────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-hdr">
                <div class="section-icon" style="background:rgba(231,81,90,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e7515a" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div>
                    <div class="section-title">Character References</div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="ev-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Position</th>
                            <th style="text-align:center;">Contact No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($character_references as $cref)
                        <tr>
                            <td>{{ $cref->cr_name }}</td>
                            <td>{{ $cref->cr_company }}</td>
                            <td>{{ $cref->cr_position }}</td>
                            <td style="text-align:center; font-family:monospace;">{{ $cref->cr_cnum }}</td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="4">No records found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Actions bar --}}
            <div class="actions-bar">
                <a href="{{ route('employee.employee_list') }}" class="btn btn-sm btn-light-dark border-0 px-3 d-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to List
                </a>
                <a href="{{ route('employee.employee_edit', $employee->employee_id) }}"
                   class="btn btn-sm btn-primary px-4 shadow-sm d-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Employee
                </a>
            </div>
        </div>

    </div>
</div>

@endsection

@section("additional_footer")
<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
@endsection
