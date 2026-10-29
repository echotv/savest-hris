@extends("../layout")

@section("additional_head")
<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/filepond/filepond.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImagePreview.min.css') }}">
<link href="{{ asset('assets/src/plugins/css/light/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/css/dark/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
<style>
/* ── Avatar ─────────────────────────────────────────────────── */
.avatar-wrap { position: relative; display: inline-block; }
.avatar-ring {
    width: 96px; height: 96px; border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #1b55e2, 0 6px 18px rgba(27,85,226,0.18);
}
body.dark .avatar-ring {
    border-color: #0e1726;
    box-shadow: 0 0 0 3px #1b55e2, 0 6px 18px rgba(27,85,226,0.4);
}
.avatar-edit-btn {
    position: absolute; right: -2px; bottom: 2px;
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: #1b55e2; color: #fff; border: 2px solid #fff;
    cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: background 0.15s;
}
.avatar-edit-btn:hover { background: #123f9e; }
body.dark .avatar-edit-btn { border-color: #0e1726; }

/* ── Profile sidebar card ────────────────────────────────────── */
.profile-sidebar {
    border-radius: 12px;
    border: 1px solid #e0e6ed;
    overflow: hidden;
    background: #fff;
}
body.dark .profile-sidebar {
    background: #0e1726;
    border-color: #1b2e4b;
}

.profile-sidebar-banner {
    height: 72px;
    background: linear-gradient(135deg, #1b55e2 0%, #00b5b5 100%);
}
body.dark .profile-sidebar-banner {
    background: linear-gradient(135deg, #0f2d8a 0%, #005f6f 100%);
    opacity: 0.85;
}

.profile-sidebar-body { padding: 0 1.5rem 1.5rem; text-align: center; }
.profile-sidebar-avatar { margin-top: -48px; margin-bottom: 0.75rem; }

.profile-name  { font-size: 1rem; font-weight: 700; margin-bottom: 0.15rem; color: #1a1f3a; }
body.dark .profile-name { color: #bfc9d4; }

.profile-username { font-size: 0.8rem; color: #888; margin-bottom: 0.75rem; }
body.dark .profile-username { color: #506690; }

.profile-type-pill {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
    padding: 0.3rem 0.85rem; border-radius: 20px;
    background: rgba(27,85,226,0.1); color: #1b55e2;
}
body.dark .profile-type-pill { background: rgba(27,85,226,0.25); color: #7ea8f8; }

.profile-divider {
    border: none; border-top: 1px solid #e9ecef;
    margin: 1.1rem 0 0.9rem;
}
body.dark .profile-divider { border-color: #1b2e4b; }

.profile-meta-row {
    display: flex; align-items: center; gap: 0.6rem;
    font-size: 0.8rem; color: #555;
    padding: 0.35rem 0;
}
body.dark .profile-meta-row { color: #888ea8; }
.profile-meta-row svg { flex-shrink: 0; opacity: 0.5; }

.profile-meta-label {
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.06em; color: #aaa; margin-bottom: 0.08rem;
}
body.dark .profile-meta-label { color: #506690; }

/* ── Section card ────────────────────────────────────────────── */
.section-card {
    border-radius: 12px;
    border: 1px solid #e0e6ed;
    background: #fff;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.section-card:last-child { margin-bottom: 0; }
body.dark .section-card {
    background: #0e1726;
    border-color: #1b2e4b;
}

.section-card-hdr {
    display: flex; align-items: center; gap: 0.65rem;
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid #e8edf2;
    background: rgba(27,85,226,0.025);
}
body.dark .section-card-hdr {
    background: #060d18;
    border-color: #1b2e4b;
}

.section-card-hdr-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.section-card-hdr h6 { font-size: 0.88rem; font-weight: 700; margin-bottom: 0; color: #1a1f3a; }
body.dark .section-card-hdr h6 { color: #e0e6ed; }

.section-card-hdr p  { font-size: 0.73rem; color: #888; margin-bottom: 0; }
body.dark .section-card-hdr p { color: #506690; }

.section-card-body { padding: 1.25rem; }

/* ── Info rows (read-only) ───────────────────────────────────── */
.info-row {
    display: flex; align-items: center; gap: 0.85rem;
    padding: 0.7rem 0;
    border-bottom: 1px solid #f0f3f8;
}
.info-row:last-child { border-bottom: none; padding-bottom: 0; }
.info-row:first-child { padding-top: 0; }
body.dark .info-row { border-color: #1b2e4b; }

.info-row-icon {
    width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}

.info-row-label {
    font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.07em; color: #aaa; line-height: 1; margin-bottom: 0.18rem;
}
body.dark .info-row-label { color: #506690; }

.info-row-value { font-size: 0.88rem; font-weight: 600; color: #3b3f5c; }
body.dark .info-row-value { color: #bfc9d4; }

/* Column divider in info grid */
.info-col-divider { border-left: 1px solid #f0f3f8; }
body.dark .info-col-divider { border-color: #1b2e4b; }

/* ── Form labels in dark mode ────────────────────────────────── */
body.dark .form-label { color: #888ea8; }

/* ── Password field with toggle ──────────────────────────────── */
.pw-wrap { position: relative; }
.pw-wrap .form-control { padding-right: 2.8rem; }
.pw-toggle {
    position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);
    background: none; border: none; padding: 0; cursor: pointer;
    color: #bbb; display: flex; align-items: center; transition: color 0.15s;
}
.pw-toggle:hover { color: #1b55e2; }
body.dark .pw-toggle { color: #506690; }
body.dark .pw-toggle:hover { color: #7ea8f8; }

/* ── Strength bar ────────────────────────────────────────────── */
.strength-bar-wrap { display: flex; gap: 3px; margin-top: 0.45rem; height: 4px; }
.strength-segment {
    flex: 1; border-radius: 2px;
    background: #e2e8f0;
    transition: background 0.3s;
}
body.dark .strength-segment { background: #1b2e4b; }

.strength-segment.active-weak   { background: #e7515a; }
.strength-segment.active-fair   { background: #e2a03f; }
.strength-segment.active-good   { background: #1b55e2; }
.strength-segment.active-strong { background: #00ab55; }

.strength-hint { font-size: 0.68rem; color: #aaa; margin-top: 0.28rem; min-height: 1rem; }
body.dark .strength-hint { color: #506690; }

/* ── Actions bar ─────────────────────────────────────────────── */
.actions-bar {
    display: flex; justify-content: flex-end; gap: 0.75rem; align-items: center;
    padding: 0.9rem 1.25rem;
    border-top: 1px solid #e8edf2;
    background: rgba(27,85,226,0.025);
}
body.dark .actions-bar {
    background: #060d18;
    border-color: #1b2e4b;
}
</style>
@endsection

@section("content")
<div class="row layout-top-spacing">

    {{-- ── Page header ─────────────────────────────────────── --}}
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-3">
            <div style="width:46px;height:46px;border-radius:12px;background:rgba(27,85,226,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1b55e2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
                <h4 class="mb-0 fw-bold" style="font-size:1.15rem;">My Account</h4>
                <p class="mb-0 text-muted" style="font-size:0.8rem;">View your profile and manage security settings.</p>
            </div>
        </div>
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">My Account</li>
            </ol>
        </nav>
    </div>

    {{-- ── Alerts ──────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
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

    {{-- ── Left sidebar ─────────────────────────────────────── --}}
    <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
        <div class="profile-sidebar">
            <div class="profile-sidebar-banner"></div>
            <div class="profile-sidebar-body">
                <div class="profile-sidebar-avatar">
                    <div class="avatar-wrap">
                        <img src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : asset('assets/default.png') }}"
                             class="avatar-ring" alt="{{ $user->name }}">
                        <span class="avatar-edit-btn" data-bs-toggle="modal" data-bs-target="#UpdateProfilePicModal" title="Change photo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        </span>
                    </div>
                </div>
                <div class="profile-name">{{ $user->name }}</div>
                <div class="profile-username">@_{{ $user->username }}</div>
                @if($user->type)
                <span class="profile-type-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    {{ $user->type }}
                </span>
                @endif

                <hr class="profile-divider">

                <div class="profile-meta-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <div>
                        <div class="profile-meta-label">Email</div>
                        <div style="font-size:0.8rem;word-break:break-all;">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="profile-meta-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <div>
                        <div class="profile-meta-label">Password</div>
                        <div style="font-size:0.8rem;letter-spacing:0.12em;">••••••••</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Right content ────────────────────────────────────── --}}
    <div class="col-lg-9 col-md-8">

        {{-- Account Information --}}
        <div class="section-card">
            <div class="section-card-hdr">
                <div class="section-card-hdr-icon" style="background:rgba(27,85,226,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1b55e2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <h6>Account Information</h6>
                    <p>Your account details on record.</p>
                </div>
            </div>
            <div class="section-card-body">
                <div class="row g-0">
                    <div class="col-md-6 pe-md-4">
                        <div class="info-row">
                            <div class="info-row-icon" style="background:rgba(27,85,226,0.08);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1b55e2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <div class="info-row-label">Full Name</div>
                                <div class="info-row-value">{{ $user->name }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-row-icon" style="background:rgba(0,181,181,0.08);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00a0a0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <div class="info-row-label">Username</div>
                                <div class="info-row-value">{{ $user->username }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ps-md-4 info-col-divider">
                        <div class="info-row">
                            <div class="info-row-icon" style="background:rgba(0,171,85,0.08);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00ab55" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <div class="info-row-label">Email Address</div>
                                <div class="info-row-value" style="word-break:break-all;">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-row-icon" style="background:rgba(226,160,63,0.1);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c07a10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <div class="info-row-label">Account Type</div>
                                <div class="info-row-value">{{ $user->type ?? 'Standard' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security / Change Password --}}
        <div class="section-card">
            <div class="section-card-hdr">
                <div class="section-card-hdr-icon" style="background:rgba(231,81,90,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#e7515a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <h6>Security Settings</h6>
                    <p>Change your password to keep your account secure.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                <div class="section-card-body">
                    <div class="row g-3">

                        {{-- Current password --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:0.82rem;" for="current_password">
                                Current Password
                            </label>
                            <div class="pw-wrap">
                                <input type="password" id="current_password" name="current_password"
                                    class="form-control form-control-sm @error('current_password') is-invalid @enderror"
                                    placeholder="Enter your current password" required>
                                <button type="button" class="pw-toggle" data-target="current_password" aria-label="Show/hide">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- New password --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:0.82rem;" for="password">
                                New Password
                            </label>
                            <div class="pw-wrap">
                                <input type="password" id="password" name="password"
                                    class="form-control form-control-sm @error('password') is-invalid @enderror"
                                    placeholder="Min. 8 characters" required>
                                <button type="button" class="pw-toggle" data-target="password" aria-label="Show/hide">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="strength-bar-wrap">
                                <div class="strength-segment" id="seg1"></div>
                                <div class="strength-segment" id="seg2"></div>
                                <div class="strength-segment" id="seg3"></div>
                                <div class="strength-segment" id="seg4"></div>
                            </div>
                            <div class="strength-hint" id="strength-hint">Enter a new password</div>
                        </div>

                        {{-- Confirm password --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:0.82rem;" for="password_confirmation">
                                Confirm New Password
                            </label>
                            <div class="pw-wrap">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control form-control-sm"
                                    placeholder="Re-enter new password" required>
                                <button type="button" class="pw-toggle" data-target="password_confirmation" aria-label="Show/hide">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="strength-hint" id="match-hint"></div>
                        </div>

                    </div>
                </div>

                <div class="actions-bar">
                    <button type="reset" class="btn btn-outline-secondary btn-sm px-3">Reset</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Update Password
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection

@section("additional_footer")

{{-- Update Profile Picture Modal --}}
<div id="UpdateProfilePicModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    Change Photo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="UpdateProfilePicForm" method="POST" action="{{ route('profile.picture.update') }}" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <input type="hidden" name="uploaded_profile_picture" id="uploaded_profile_picture">
                    <div class="img-uploader-content">
                        <input type="file" class="filepond" name="profile_picture" id="profile_picture" accept="image/png, image/jpeg, image/gif"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light-dark border-0" data-bs-dismiss="modal">Discard</button>
                <button type="submit" form="UpdateProfilePicForm" class="btn btn-primary px-4 shadow-sm" id="updatePicBtn" disabled>Update</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/src/plugins/src/filepond/filepond.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    /* ── Change photo (FilePond) ─── */
    $('#UpdateProfilePicModal').on('hidden.bs.modal', function () {
        $('#uploaded_profile_picture').val('');
    });

    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginImageCrop,
        FilePondPluginImageResize,
        FilePondPluginImageTransform
    );

    const pond = FilePond.create(document.querySelector('#profile_picture'), {
        allowMultiple: false,
        acceptedFileTypes: ['image/png', 'image/jpeg', 'image/gif'],
        instantUpload: true,
        server: {
            process: {
                url: '{{ route("employee.upload-temp") }}', method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                onload: (response) => {
                    $('#uploaded_profile_picture').val(response);
                    return response;
                }
            },
            revert: {
                url: '{{ route("employee.revert-temp") }}', method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                onload: () => { $('#uploaded_profile_picture').val(''); }
            }
        }
    });

    const updatePicBtn = document.getElementById('updatePicBtn');
    pond.on('processfilestart', () => { updatePicBtn.disabled = true; updatePicBtn.textContent = 'Uploading...'; });
    pond.on('processfile',      () => { updatePicBtn.disabled = false; updatePicBtn.textContent = 'Update'; });
    pond.on('processfileabort', () => { updatePicBtn.disabled = true;  updatePicBtn.textContent = 'Update'; });
    pond.on('removefile',       () => { updatePicBtn.disabled = true; });

    /* ── Show / hide password toggles ─── */
    document.querySelectorAll(".pw-toggle").forEach(function (btn) {
        btn.addEventListener("click", function () {
            var inp = document.getElementById(this.dataset.target);
            if (!inp) return;
            var isText = inp.type === "text";
            inp.type = isText ? "password" : "text";
            var svg = this.querySelector("svg");
            if (isText) {
                svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            } else {
                svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            }
        });
    });

    /* ── Password strength meter ─── */
    var pwInput    = document.getElementById("password");
    var confInput  = document.getElementById("password_confirmation");
    var segments   = [document.getElementById("seg1"), document.getElementById("seg2"), document.getElementById("seg3"), document.getElementById("seg4")];
    var hint       = document.getElementById("strength-hint");
    var matchHint  = document.getElementById("match-hint");

    function scorePassword(pw) {
        var score = 0;
        if (!pw) return 0;
        if (pw.length >= 8)  score++;
        if (pw.length >= 12) score++;
        if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return Math.min(score, 4);
    }

    var levels = [
        { label: "Too short",    cls: "active-weak"   },
        { label: "Weak",         cls: "active-weak"   },
        { label: "Fair",         cls: "active-fair"   },
        { label: "Good",         cls: "active-good"   },
        { label: "Strong",       cls: "active-strong" }
    ];

    function updateStrength() {
        var pw    = pwInput.value;
        var score = scorePassword(pw);
        segments.forEach(function (s, i) {
            s.className = "strength-segment";
            if (pw.length > 0 && i < score) s.classList.add(levels[score].cls);
        });
        hint.textContent  = pw.length === 0 ? "Enter a new password" : levels[score].label;
        hint.style.color  = pw.length === 0 ? "#aaa"
            : score <= 1 ? "#e7515a"
            : score === 2 ? "#e2a03f"
            : score === 3 ? "#1b55e2"
            : "#00ab55";
        updateMatch();
    }

    function updateMatch() {
        var pw   = pwInput.value;
        var conf = confInput.value;
        if (!conf) { matchHint.textContent = ""; return; }
        if (pw === conf) {
            matchHint.textContent  = "✓ Passwords match";
            matchHint.style.color  = "#00ab55";
        } else {
            matchHint.textContent  = "✗ Passwords do not match";
            matchHint.style.color  = "#e7515a";
        }
    }

    pwInput.addEventListener("input", updateStrength);
    confInput.addEventListener("input", updateMatch);
});
</script>
@endsection
