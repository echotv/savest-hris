<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/src/assets/img/savesthris_logo.svg')}}"/>
    <link href="{{ asset('assets/layouts/collapsible-menu/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/collapsible-menu/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/layouts/collapsible-menu/loader.js') }}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/collapsible-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/collapsible-menu/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!--
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">

    <link href="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/plugins/src/noUiSlider/nouislider.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/plugins/css/light/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    -->
    @yield('additional_head')
    <style>
        /* ── SIDEBAR SCROLL ─────────────────────────────────────────── */
        #sidebar {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }
        #sidebar .navbar-nav.theme-brand {
            flex-shrink: 0;
        }
        #sidebar .shadow-bottom {
            flex-shrink: 0;
        }
        #sidebar ul.menu-categories {
            flex: 1 1 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 24px;
        }
        /* thin custom scrollbar */
        #sidebar ul.menu-categories::-webkit-scrollbar { width: 3px; }
        #sidebar ul.menu-categories::-webkit-scrollbar-track { background: transparent; }
        #sidebar ul.menu-categories::-webkit-scrollbar-thumb {
            background: rgba(136,142,168,.25);
            border-radius: 4px;
        }
        #sidebar ul.menu-categories::-webkit-scrollbar-thumb:hover {
            background: rgba(136,142,168,.55);
        }
        body.dark #sidebar ul.menu-categories::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.1);
        }
        body.dark #sidebar ul.menu-categories::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,.22);
        }

        /* ── COMPACT TOP-LEVEL MENU ITEMS ───────────────────────────── */
        .menu-categories li.menu-heading + li.menu > a.dropdown-toggle {
            padding-top: 2px !important;
        }
        .menu-categories li.menu > a.dropdown-toggle {
            padding: 7px 12px 7px 14px !important;
            min-height: unset !important;
        }
        .menu-categories li.menu > a.dropdown-toggle > div:first-child {
            gap: 9px !important;
        }
        .menu-categories li.menu > a.dropdown-toggle svg {
            width: 16px !important;
            height: 16px !important;
            flex-shrink: 0;
        }
        .menu-categories li.menu > a.dropdown-toggle span {
            font-size: 12.5px !important;
            font-weight: 600 !important;
            letter-spacing: 0.1px;
        }
        .menu-categories li.menu > a.dropdown-toggle > div:last-child svg {
            width: 13px !important;
            height: 13px !important;
        }

        /* ── COMPACT SUBMENU LINKS ──────────────────────────────────── */
        /* Container: top/bottom padding so items breathe inside the accordion */
        #sidebar ul.menu-categories ul.submenu {
            padding: 6px 8px 10px 20px !important;
        }
        /* Reset template's margin-left on <a> and use only padding for indentation */
        #sidebar ul.menu-categories ul.submenu > li a {
            margin-left: 0 !important;
            padding: 7px 10px 7px 24px !important;
            font-size: 13px !important;
            line-height: 1.4 !important;
            font-weight: 500 !important;
            border-radius: 6px !important;
        }
        #sidebar ul.menu-categories li.menu ul.submenu > li a:hover {
            padding-left: 28px !important;
        }
        /* Vertically center the bullet dot regardless of link height */
        #sidebar ul.menu-categories li.menu ul.submenu > li a:before {
            top: 50% !important;
            transform: translateY(-50%) !important;
            left: 8px !important;
            width: 5px !important;
            height: 5px !important;
        }

        /* ── SECTION GROUP LABEL ────────────────────────────────────── */
        .submenu-group-label {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 12px 3px 28px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            color: #888ea8;
            pointer-events: none;
            line-height: 1;
        }
        .submenu-group-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #888ea8;
            opacity: 0.2;
        }
        .submenu-group-label:first-child { padding-top: 6px; }

        /* ── MENU HEADING ───────────────────────────────────────────── */
        .menu-categories li.menu-heading .heading {
            font-size: 9.5px !important;
            padding: 8px 14px 0px 30px !important;
            display: flex !important;
            align-items: center !important;
            gap: 9px !important;
        }
        .menu-categories li.menu-heading .heading svg {
            width: 16px !important;
            height: 16px !important;
            flex-shrink: 0;
        }

        /* ── COLLAPSED SIDEBAR FIXES ────────────────────────────────── */
        /* Restore template's icon-only padding when sidebar is collapsed */
        .sidebar-closed #sidebar ul.menu-categories li.menu > a.dropdown-toggle {
            padding: 10px 16px !important;
        }
        /* Restore normal padding on hover-to-expand */
        .sidebar-closed > .sidebar-wrapper:hover #sidebar ul.menu-categories li.menu > a.dropdown-toggle {
            padding: 7px 12px 7px 14px !important;
        }
        /* Restore larger icon size in collapsed mode so icons are visible */
        .sidebar-closed #sidebar ul.menu-categories li.menu > a.dropdown-toggle svg {
            width: 20px !important;
            height: 20px !important;
        }
        /* Restore compact icon size when hovering to expand */
        .sidebar-closed > .sidebar-wrapper:hover #sidebar ul.menu-categories li.menu > a.dropdown-toggle svg {
            width: 16px !important;
            height: 16px !important;
        }

        /* ── TEMPLATE BUG FIX: theme toggle only adds body.dark, it never sets
           data-bs-theme or redefines Bootstrap's CSS vars, so any page using
           var(--bs-body-bg, #fff) / var(--bs-border-color, ...) etc. as a
           "theme-aware" value was permanently pinned to the light fallback.
           Defining the vars here makes every such usage across all pages
           follow the active theme automatically. ── */
        body.dark {
            --bs-body-bg: #191e3a;
            --bs-body-color: #bfc9d4;
            --bs-border-color: #1b2e4b;
            --bs-secondary-bg: #0e1726;
            --bs-tertiary-bg: #1b2e4b;
            /* Bootstrap 5.3's own dark palette for the bg-*-subtle / text-*-emphasis /
               border-*-subtle utilities, which Bootstrap only defines under
               [data-bs-theme=dark] — never applied here — so badges like
               "bg-success-subtle text-success" were stuck showing their light
               pastel colors even with the site in dark mode. */
            --bs-primary-text-emphasis: #6ea8fe;
            --bs-secondary-text-emphasis: #a7acb1;
            --bs-success-text-emphasis: #75b798;
            --bs-info-text-emphasis: #6edff6;
            --bs-warning-text-emphasis: #ffda6a;
            --bs-danger-text-emphasis: #ea868f;
            --bs-light-text-emphasis: #f8f9fa;
            --bs-dark-text-emphasis: #dee2e6;
            --bs-primary-bg-subtle: #031633;
            --bs-secondary-bg-subtle: #161719;
            --bs-success-bg-subtle: #051b11;
            --bs-info-bg-subtle: #032830;
            --bs-warning-bg-subtle: #332701;
            --bs-danger-bg-subtle: #2c0b0e;
            --bs-light-bg-subtle: #343a40;
            --bs-dark-bg-subtle: #1a1d20;
            --bs-primary-border-subtle: #084298;
            --bs-secondary-border-subtle: #41464b;
            --bs-success-border-subtle: #0f5132;
            --bs-info-border-subtle: #087990;
            --bs-warning-border-subtle: #997404;
            --bs-danger-border-subtle: #842029;
            --bs-light-border-subtle: #495057;
            --bs-dark-border-subtle: #343a40;
        }

        /* ── TEMPLATE BUG FIX: Bootstrap's .bg-light utility is never re-themed
           by the template's dark CSS, so any page using it keeps a light-gray
           chip on an otherwise dark page. ── */
        body.dark .bg-light {
            background-color: #1b2e4b !important;
            color: #bfc9d4;
        }

        /* ── TEMPLATE BUG FIX: modal-content hardcoded to dark color in light/main.css ── */
        .modal-content {
            background: #fff !important;
            color: inherit;
        }
        body.dark .modal-content {
            background: #0e1726 !important;
            color: #bfc9d4;
        }
        body.dark .modal-header {
            border-bottom-color: #1b2e4b;
        }
        body.dark .modal-footer {
            border-top-color: #1b2e4b;
        }
        body.dark .modal-title {
            color: #e0e6ed;
        }
        body.dark .modal .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        body.dark .modal .form-control,
        body.dark .modal .form-select {
            background-color: #1b2e4b;
            border-color: #253c5a;
            color: #bfc9d4;
        }
        body.dark .modal .form-control:disabled,
        body.dark .modal .form-control[readonly] {
            background-color: #0e1726;
        }
        body.dark .modal .form-label { color: #888ea8; }
        body.dark .modal .form-check-label { color: #bfc9d4; }
        body.dark .modal .input-group .btn { border-color: #253c5a; }

        /* ── Disabled/readonly fields, everywhere (not just modals) ──────
           Bootstrap's own disabled/readonly styling washes text out to a
           low-contrast gray, which is hard to read on both the light and
           dark themes here. Match this app's actual brand color (the same
           #4361ee that .text-primary already resolves to) instead, so
           these fields read as "locked, but still clearly readable" in
           either theme. */
        .form-control:disabled, .form-control[readonly],
        .form-select:disabled,
        input:disabled, input[readonly],
        select:disabled,
        textarea:disabled, textarea[readonly] {
            color: #4361ee !important;
            opacity: 1;
        }
        body.dark .form-control:disabled, body.dark .form-control[readonly],
        body.dark .form-select:disabled,
        body.dark input:disabled, body.dark input[readonly],
        body.dark select:disabled,
        body.dark textarea:disabled, body.dark textarea[readonly] {
            color: #4361ee !important;
            opacity: 1;
        }

        /* ── NOTIFICATION BELL ───────────────────────────────────────── */
        .notif-bell-wrap { position:relative; display:inline-flex; align-items:center; }
        .notif-badge {
            position:absolute; top:-6px; right:-8px;
            min-width:18px; height:18px; padding:0 4px;
            background:#ef4444; color:#fff; font-size:10px; font-weight:700;
            border-radius:9px; display:flex; align-items:center; justify-content:center;
            border:2px solid #fff; line-height:1;
        }
        body.dark .notif-badge { border-color:#0e1726; }

        /* panel */
        .notif-dropdown-panel {
            width:360px; padding:0; border:none;
            border-radius:14px; overflow:hidden;
            box-shadow:0 8px 32px rgba(0,0,0,.14);
        }
        body.dark .notif-dropdown-panel {
            background:#0e1726;
            box-shadow:0 8px 32px rgba(0,0,0,.4);
        }

        /* header */
        .notif-panel-header {
            display:flex; align-items:center; justify-content:space-between;
            padding:14px 16px 10px;
            border-bottom:1px solid #f1f5f9;
        }
        body.dark .notif-panel-header { border-color:#1b2e4b; }
        .notif-panel-title {
            display:flex; align-items:center; gap:7px;
            font-weight:700; font-size:14px; color:#1e293b;
        }
        body.dark .notif-panel-title { color:#e0e6ed; }
        .notif-count-pill {
            background:#ef4444; color:#fff; font-size:10px; font-weight:700;
            padding:1px 6px; border-radius:8px;
        }
        .notif-markall-btn {
            background:none; border:none; color:#4361ee; font-size:12px;
            font-weight:600; cursor:pointer; padding:0;
        }
        .notif-markall-btn:hover { text-decoration:underline; }
        body.dark .notif-markall-btn { color:#7986f8; }

        /* list */
        .notif-list { max-height:380px; overflow-y:auto; }
        .notif-list::-webkit-scrollbar { width:4px; }
        .notif-list::-webkit-scrollbar-thumb { background:rgba(0,0,0,.1); border-radius:4px; }

        /* item */
        .notif-item {
            display:flex; align-items:flex-start; gap:11px;
            padding:11px 16px; cursor:pointer; transition:background .15s;
            border-bottom:1px solid #f8fafc; position:relative;
        }
        body.dark .notif-item { border-color:#1b2e4b; }
        .notif-item:last-child { border-bottom:none; }
        .notif-item:hover { background:#f8fafc; }
        body.dark .notif-item:hover { background:#1b2e4b; }
        .notif-unread { background:#eff6ff; }
        body.dark .notif-unread { background:#1b2e4b; }
        .notif-unread:hover { background:#dbeafe; }
        body.dark .notif-unread:hover { background:#243b56; }

        .notif-icon-wrap {
            flex-shrink:0; width:36px; height:36px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
        }
        .notif-body { flex:1; min-width:0; }
        .notif-title { font-size:13px; font-weight:700; color:#1e293b; line-height:1.3; }
        body.dark .notif-title { color:#e0e6ed; }
        .notif-msg { font-size:12px; color:#64748b; margin-top:2px; line-height:1.4;
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        body.dark .notif-msg { color:#888ea8; }
        .notif-meta { display:flex; align-items:center; gap:8px; margin-top:4px; }
        .notif-branch {
            font-size:10px; font-weight:700; background:#e0e7ff; color:#4338ca;
            padding:1px 6px; border-radius:4px;
        }
        body.dark .notif-branch { background:#1b2e4b; color:#7986f8; }
        .notif-time { font-size:11px; color:#94a3b8; }
        .notif-dot {
            flex-shrink:0; width:8px; height:8px; background:#4361ee;
            border-radius:50%; align-self:center; margin-left:4px;
        }

        /* empty */
        .notif-empty {
            display:flex; flex-direction:column; align-items:center;
            padding:36px 16px; color:#94a3b8; gap:10px;
        }
        .notif-empty p { margin:0; font-size:13px; }
    </style>
</head>
<body class="layout-boxed alt-menu">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm expand-header">

            <a href="javascript:void(0);" class="sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </a>



            <ul class="navbar-item flex-row ms-lg-auto ms-0">

                @php
                    $navUnread = 0;
                    $navNotifs = collect();
                @endphp

                {{-- ── Bell Notification Dropdown ── --}}
                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notifBell" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="notif-bell-wrap" style="position:relative;display:inline-flex;align-items:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                            <span id="notifBadge" class="notif-badge" style="display:{{ $navUnread > 0 ? 'flex' : 'none' }};">{{ $navUnread > 9 ? '9+' : $navUnread }}</span>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end notif-dropdown-panel" aria-labelledby="notifBell">
                        <div class="notif-panel-header">
                            <span class="notif-panel-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                Notifications
                                @if($navUnread > 0)
                                <span class="notif-count-pill">{{ $navUnread }}</span>
                                @endif
                            </span>
                            <button class="notif-markall-btn" id="markAllReadBtn" onclick="markAllNotifRead()">Mark all read</button>
                        </div>

                        <div class="notif-list" id="notifList">
                            @forelse($navNotifs as $notif)
                            <div class="notif-item {{ $notif->is_read ? 'notif-read' : 'notif-unread' }}"
                                 data-id="{{ $notif->notif_id }}"
                                 onclick="handleNotifClick(this)">
                                <div class="notif-body">
                                    <div class="notif-title">{{ $notif->title }}</div>
                                    <div class="notif-msg">{{ $notif->message }}</div>
                                    <div class="notif-meta">
                                        <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @if(!$notif->is_read)
                                <span class="notif-dot"></span>
                                @endif
                            </div>
                            @empty
                            <div class="notif-empty">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                <p>No notifications yet</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </li>

                <li class="nav-item theme-toggle-item">
                    <a href="javascript:void(0);" class="nav-link theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </a>
                </li>



                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-container">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                                @if(Auth::user()->profile_picture)
                                    <img alt="avatar" src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-circle" style="object-fit:cover;width:100%;height:100%;">
                                @else
                                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white"
                                          style="width:100%;height:100%;background:#4361ee;font-size:14px;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <div class="emoji me-2">
                                    &#x1F44B;
                                </div>
                                <div class="media-body">
                                    <h5>{{ Auth::user()->name }}</h5>
                                    <p>{{ Auth::user()->type }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="{{ route('profile.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>My Profile</span>
                            </a>
                        </div>

                        <div class="dropdown-item">
                            <a href="{{ route("hris.logout") }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                            </a>
                        </div>
                    </div>

                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">

                <div class="navbar-nav theme-brand flex-row  text-center">
                    <div class="nav-logo">
                        <div class="nav-item theme-logo">
                            <a href="./index.html">
                                <img src="{{ asset('assets/src/assets/img/savesthris_logo.svg') }}" class="" alt="hris logo">
                            </a>
                        </div>
                        <div class="nav-item theme-text">
                            <a href="./index.html" class="nav-link"> SAVEST-HR </a>
                        </div>
                    </div>
                    <div class="nav-item sidebar-toggle">
                        <div class="btn-toggle sidebarCollapse">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                        </div>
                    </div>
                </div>
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu @if($menu == 'Dashboard') active @endif">
                        <a href="{{ route('system.dashboard') }}" aria-expanded="{{ $menu == 'Dashboard' ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span>MODULES</span></div>
                    </li>
                    <li class="menu @if($menu == 'Employee') active @endif">
                        <a href="#employees" data-bs-toggle="collapse" aria-expanded="{{ $menu == 'Employee' ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                               <span>Employees</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @if($menu == 'Employee') show @endif" id="employees" data-bs-parent="#accordionExample">
                            @if (in_array('ADD NEW EMPLOYEE', session('permissions', [])))
                            <li>
                                <a href="{{ route('employee.registration_form') }}"> Employee Registration </a>
                            </li>
                            @endif
                            @if (in_array('VIEW LIST OF EMPLOYEES', session('permissions', [])))
                            <li>
                                <a href="{{ route('employee.employee_list') }}"> List of Employees </a>
                            </li>
                            @endif
                            @if (in_array('VIEW UPCOMING BIRTHDAYS', session('permissions', [])))
                            <li>
                                <a href="{{ route('employee.upcoming_birthdays') }}"> Upcoming Birthdays </a>
                            </li>
                            @endif
                            @if (in_array('VIEW UPCOMING WORK ANNIVERSARIES', session('permissions', [])))
                            <li>
                                <a href="{{ route('employee.work_anniversarries') }}"> Upcoming Work<br>Anniversaries </a>
                            </li>
                            @endif
                            @if (in_array('VIEW WEEKLY SCHEDULES', session('permissions', [])))
                            <li>
                                <a href="{{ route('ews.load_wsl') }}"> List of <br>Weekly Schedules </a>
                            </li>
                            @endif
                            @if (in_array('VIEW BIOMETRIC IDS', session('permissions', [])))
                            <li>
                                <a href="{{ route('biometric-ids.all') }}"> Biometric IDs </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @if (userCan('VIEW EMPLOYEE MEMOS'))
                    <li class="menu @if($menu == 'Memos') active @endif">
                        <a href="{{ route('memos.index') }}" aria-expanded="{{ $menu == 'Memos' ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <span>Employee Memos</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (userCan('VIEW PERFORMANCE EVALUATIONS'))
                    <li class="menu @if($menu == 'Evaluations') active @endif">
                        <a href="{{ route('evaluations.index') }}" aria-expanded="{{ $menu == 'Evaluations' ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                <span>Performance Evaluation</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if(userCan('VIEW HOLIDAYS & NON-WORKING DAYS') || userCan('VIEW ON-TRAVEL & SEMINAR REQUESTS') || userCan('VIEW BRANCH VISITS') || userCan('VIEW SUSPENDED EMPLOYEES'))
                    <li class="menu @if($menu == 'Timekeeping') active @endif">
                        <a href="#timekeeping" data-bs-toggle="collapse" aria-expanded="{{ $menu == 'Timekeeping' ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span>Attendance Management</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @if($menu == 'Timekeeping') show @endif" id="timekeeping" data-bs-parent="#accordionExample">
                            <li>
                                @if(userCan('VIEW HOLIDAYS & NON-WORKING DAYS'))<a href="{{ route('holidays.index') }}">Holidays & Non-Working Days</a>@endif
                                @if(userCan('VIEW ON-TRAVEL & SEMINAR REQUESTS'))<a href="{{ route('travel.index') }}">On-Travel / Seminar / Training</a>@endif
                                @if(userCan('VIEW BRANCH VISITS'))<a href="{{ route('branch-visits.index') }}">Branch Visits</a>@endif
                                @if(userCan('VIEW SUSPENDED EMPLOYEES'))<a href="{{ route('suspensions.index') }}">Suspended Employees</a>@endif
                                </li>
                        </ul>
                    </li>
                    @endif

                    @if (userCan('VIEW BRANCHES') || userCan('VIEW COMPANY POSITIONS') || userCan('VIEW EMPLOYMENT STATUS') || userCan('VIEW SALARY GRADE'))
                    <li class="menu @if($menu == 'Company Setup') active @endif">
                        <a href="#company_setup" data-bs-toggle="collapse" aria-expanded="{{ $menu == 'Company Setup' ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                <span>Company Setup</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @if($menu == 'Company Setup') show @endif" id="company_setup" data-bs-parent="#accordionExample">
                            @if (userCan('VIEW BRANCHES'))
                            <li>
                                <a href="{{ route('branches.index') }}"> Branches </a>
                            </li>
                            @endif
                            @if (userCan('VIEW COMPANY POSITIONS'))
                            <li>
                                <a href="{{ route('positions.company_positions') }}"> Company Positions </a>
                            </li>
                            @endif
                            @if (userCan('VIEW EMPLOYMENT STATUS'))
                            <li>
                                <a href="{{ route('employment_status.load') }}"> Employment Status </a>
                            </li>
                            @endif
                            @if (userCan('VIEW SALARY GRADE'))
                            <li>
                                <a href="{{ route('salarygrades.index') }}"> Salary Grade </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <li class="menu @if($menu == 'Settings') active @endif">
                        <a href="#system_settings" data-bs-toggle="collapse" aria-expanded="{{ $menu == 'Settings' ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                                <span>System Settings</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @if($menu == 'Settings') show @endif" id="system_settings" data-bs-parent="#accordionExample">
                            @if (in_array('VIEW USER ACCOUNTS', session('permissions', [])))
                            <li>
                                <a href="{{ route('system.user_accounts') }}"> User Accounts </a>
                            </li>
                            @endif
                            @if (in_array('VIEW USER TYPE & PERMISSIONS', session('permissions', [])))
                            <li>
                                <a href="{{ route('system.user_permissions') }}"> User Type & Permissions </a>
                            </li>
                            @endif
                            @if (in_array('VIEW SYSTEM LOGS', session('permissions', [])))
                            <li>
                                <a href="{{ route('system.logs') }}"> System Logs </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('system.user_manual') }}"> User's Manual </a>
                            </li>
                        </ul>
                    </li>

                </ul>

            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">
                        @yield('content')
                </div>

                <!--  BEGIN FOOTER  -->
                <div class="footer-wrapper">
                    <div class="footer-section f-section-1">
                        <p class="">Copyright © <span class="dynamic-year">2026</span> <a target="_blank" href="https://designreset.com/cork-admin/">MAB Business Solutions</a>, All rights reserved.</p>
                    </div>
                    <div class="footer-section f-section-2">
                        <p class="">Version 1.0 </p>
                    </div>
                </div>
                <!--  END FOOTER  -->
            </div>
        <!--  END CONTENT AREA  -->
        </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('assets/src/plugins/src/global/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/layouts/collapsible-menu/app.js') }}"></script>
    <script src="{{ asset('assets/src/assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/highlight/highlight.pack.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!--
    <script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('assets/src/plugins/src/flatpickr/flatpickr.js') }}"></script>

    <script src="{{ asset('assets/src/plugins/src/flatpickr/custom-flatpickr.js') }}"></script>

    <script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
    <script>
    function getPhilippinesDate() {
        let now = new Date();
        let utc = now.getTime() + now.getTimezoneOffset() * 60000;
        let phTime = new Date(utc + (3600000 * 8));
        return phTime;
    }

    var f4 = flatpickr(document.getElementById('date_hired'), {
        defaultDate: getPhilippinesDate()
    });
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
            "pageLength": 10
        });
    </script>
    -->
    @yield('additional_footer')

    <script>
    const _csrf = document.querySelector('meta[name="csrf-token"]')?.content
                  || '{{ csrf_token() }}';

    // ── Mark single notification read then navigate ────────────────
    function handleNotifClick(el) {
        const id   = el.dataset.id;
        const href = el.dataset.href;

        if (!href) return;

        if (el.classList.contains('notif-unread')) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': _csrf, 'Content-Type': 'application/json' }
            });
            el.classList.remove('notif-unread');
            el.classList.add('notif-read');
            const dot = el.querySelector('.notif-dot');
            if (dot) dot.remove();
            decrementBadge();
        }

        window.location.href = href;
    }

    // ── Mark all read ──────────────────────────────────────────────
    function markAllNotifRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': _csrf, 'Content-Type': 'application/json' }
        }).then(() => {
            document.querySelectorAll('.notif-unread').forEach(el => {
                el.classList.remove('notif-unread');
                el.classList.add('notif-read');
                const dot = el.querySelector('.notif-dot');
                if (dot) dot.remove();
            });
            hideBadge();
        });
    }

    // ── Badge helpers ──────────────────────────────────────────────
    const badge = document.getElementById('notifBadge');
    function hideBadge() {
        badge.style.display = 'none';
        badge.textContent = '0';
    }
    function decrementBadge() {
        let n = parseInt(badge.textContent) || 0;
        if (n <= 1) { hideBadge(); return; }
        badge.textContent = n - 1;
        badge.style.display = 'flex';
    }
    </script>
</body>
</html>
