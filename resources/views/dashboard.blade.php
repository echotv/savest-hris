@extends("layout")

@section("additional_head")
    <link href="{{ asset('assets/src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* Modern Premium UI Variables & Framework */
        :root {
            --card-radius: 16px;
            --subtle-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dashboard Global Overrides */
        .dashboard-header {
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Realtime Clock Premium Styling */
        .premium-clock {
            background: #f8fafc;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            transition: var(--subtle-transition);
        }
        .premium-clock #clock-time {
            font-size: 1.1rem;
            letter-spacing: -0.01em;
        }
        .premium-clock #clock-date {
            font-size: 0.72rem;
            letter-spacing: 0.03em;
        }

        /* Glassmorphism Luxury Metric Cards */
        .modern-stat-card {
            border: 1px solid rgba(0, 0, 0, 0.04) !important;
            border-radius: var(--card-radius);
            background: #ffffff;
            transition: var(--subtle-transition);
            position: relative;
            overflow: hidden;
        }
        .modern-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05) !important;
        }
        .modern-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color, #3f71a5);
        }

        /* Custom Status Identity Map */
        .status-active { --accent-color: #0d6efd; --bg-light: rgba(13, 110, 253, 0.06); --color-main: #0d6efd; }
        .status-salary { --accent-color: #4361ee; --bg-light: rgba(67, 97, 238, 0.08); --color-main: #4361ee; }
        .status-position { --accent-color: #0ea5e9; --bg-light: rgba(14, 165, 233, 0.08); --color-main: #0ea5e9; }
        .status-employment { --accent-color: #10b981; --bg-light: rgba(16, 185, 129, 0.08); --color-main: #10b981; }
        .status-biometric { --accent-color: #f59e0b; --bg-light: rgba(245, 158, 11, 0.08); --color-main: #f59e0b; }

        /* Pie/donut chart cards */
        .chart-card .apexcharts-legend-text { color: inherit !important; }

        .modern-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-light);
            color: var(--color-main);
            transition: var(--subtle-transition);
        }
        .modern-stat-card:hover .modern-icon-box {
            transform: scale(1.08);
        }

        /* Container & Widget Layout Modifications */
        .modern-widget {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.04) !important;
            border-radius: var(--card-radius);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.01) !important;
        }
        .avatar-container {
            position: relative;
            display: inline-block;
        }
        .avatar-ring {
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--ring-color, #e2e8f0);
            transition: var(--subtle-transition);
        }
        .interactive-row {
            border-radius: 12px;
            transition: var(--subtle-transition);
            padding: 0.75rem;
        }
        .interactive-row:hover {
            background-color: #f8fafc;
        }

        /* Smooth System Timeline Graphics */
        .timeline-modern {
            position: relative;
            padding-left: 1.5rem;
        }
        .timeline-modern::before {
            content: '';
            position: absolute;
            left: 4px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: #f1f5f9;
        }
        .timeline-node {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .timeline-node::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 6px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        /* Dark Mode Integration Architecture */
        body.dark .modern-stat-card, body.dark .modern-widget {
            background: #0e1726 !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        body.dark .interactive-row:hover {
            background-color: #191e3a;
        }
        body.dark .timeline-modern::before {
            background: #191e3a;
        }
        body.dark .premium-clock {
            background: #141e31;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        body.dark .avatar-ring {
            border-color: #0e1726;
        }
    </style>
@endsection

@section("content")
    <div class="row layout-top-spacing g-4">

        <div class="col-12 dashboard-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-dark m-0 tracking-tight" style="font-weight: 800; letter-spacing: -0.03em;">Dashboard Overview</h3>
                <p class="text-muted small mb-0 mt-1">Real-time enterprise administrative mapping and global employment analytics.</p>
            </div>

            <div class="premium-clock d-flex align-items-center gap-3 shadow-sm">
                <div class="text-end d-flex flex-column">
                    <span id="clock-time" class="fw-bold text-primary mb-0">00:00:00 AM</span>
                    <span id="clock-date" class="text-muted text-uppercase fw-semibold">LOADING DATE...</span>
                </div>
                <div class="clock-icon text-primary d-none d-sm-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row g-3">

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="card modern-stat-card p-4 status-active">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold tracking-wide text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Active Roster Count</span>
                                <h2 class="fw-bold text-dark mt-2 mb-0 count-up" data-count-to="{{ $num_active }}" style="letter-spacing: -0.02em;">0</h2>
                            </div>
                            <div class="modern-icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="card modern-stat-card p-4 status-salary">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold tracking-wide text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Salary Grades Defined</span>
                                <h2 class="fw-bold text-dark mt-2 mb-0 count-up" data-count-to="{{ $num_salary_grades }}" style="letter-spacing: -0.02em;">0</h2>
                            </div>
                            <div class="modern-icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 21V3h6a4.5 4.5 0 0 1 0 9H7"></path><line x1="4" y1="7" x2="17" y2="7"></line><line x1="4" y1="11" x2="17" y2="11"></line></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="card modern-stat-card p-4 status-position">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold tracking-wide text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Company Positions</span>
                                <h2 class="fw-bold text-dark mt-2 mb-0 count-up" data-count-to="{{ $num_positions }}" style="letter-spacing: -0.02em;">0</h2>
                            </div>
                            <div class="modern-icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="card modern-stat-card p-4 status-employment">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold tracking-wide text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Employment Statuses</span>
                                <h2 class="fw-bold text-dark mt-2 mb-0 count-up" data-count-to="{{ $num_employment_statuses }}" style="letter-spacing: -0.02em;">0</h2>
                            </div>
                            <div class="modern-icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="card modern-stat-card p-4 status-biometric">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold tracking-wide text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Biometric IDs Assigned</span>
                                <h2 class="fw-bold text-dark mt-2 mb-0 count-up" data-count-to="{{ $num_biometric_ids }}" style="letter-spacing: -0.02em;">0</h2>
                            </div>
                            <div class="modern-icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 11V9a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2"></path><rect x="5" y="11" width="14" height="10" rx="2"></rect><circle cx="12" cy="16" r="1.5"></circle></svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="row g-3">

                <div class="col-xl-4 col-lg-6 col-md-12">
                    <div class="card modern-widget chart-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h6 class="fw-bold text-dark m-0 mb-1" style="font-weight:700;">Weekly Schedule Coverage</h6>
                                <p class="text-muted small m-0 mb-2">Who has a schedule on file, as of today.</p>
                            </div>
                            <a href="{{ route('ews.load_wsl') }}" class="btn btn-sm btn-outline-primary flex-shrink-0 ms-2">View Employees</a>
                        </div>
                        <div id="chartSchedule"></div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-12">
                    <div class="card modern-widget chart-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h6 class="fw-bold text-dark m-0 mb-1" style="font-weight:700;">Position Coverage</h6>
                                <p class="text-muted small m-0 mb-2">Who has a company position on file.</p>
                            </div>
                            <a href="{{ route('employee.employee_list') }}" class="btn btn-sm btn-outline-primary flex-shrink-0 ms-2">View Employees</a>
                        </div>
                        <div id="chartPositions"></div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-12">
                    <div class="card modern-widget chart-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h6 class="fw-bold text-dark m-0 mb-1" style="font-weight:700;">Salary Grade Coverage</h6>
                                <p class="text-muted small m-0 mb-2">Who has an active salary grade on file.</p>
                            </div>
                            <a href="{{ route('employee.employee_list', ['salary_grade' => 'all']) }}" class="btn btn-sm btn-outline-primary flex-shrink-0 ms-2">View Employees</a>
                        </div>
                        <div id="chartSalaryGrade"></div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-12">
                    <div class="card modern-widget chart-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h6 class="fw-bold text-dark m-0 mb-1" style="font-weight:700;">Employees by Employment Status</h6>
                                <p class="text-muted small m-0 mb-2">Current roster, grouped by employment status.</p>
                            </div>
                            <a href="{{ route('employee.employee_list') }}" class="btn btn-sm btn-outline-primary flex-shrink-0 ms-2">View Employees</a>
                        </div>
                        <div id="chartEmploymentStatus"></div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-12">
                    <div class="card modern-widget chart-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h6 class="fw-bold text-dark m-0 mb-1" style="font-weight:700;">Biometric ID Coverage</h6>
                                <p class="text-muted small m-0 mb-2">Who has at least one biometric ID on file.</p>
                            </div>
                            <a href="{{ route('biometric-ids.all') }}" class="btn btn-sm btn-outline-primary flex-shrink-0 ms-2">View Employees</a>
                        </div>
                        <div id="chartBiometric"></div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card modern-widget h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark m-0" style="font-weight:700;">Today’s Birthday List</h5>
                        <p class="text-muted small m-0">Active staff celebrating milestones today.</p>
                    </div>
                    <div class="dropdown">
                        <a class="text-muted p-2 d-inline-block" href="#" role="button" id="bdayDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="bdayDropdown">
                            <li><a class="dropdown-item py-2 small" href="{{ route('employee.work_anniversarries') }}">View Upcoming Birthdays</a></li>
                        </ul>
                    </div>
                </div>

                <div class="panel-body-content">
                    @if ($todays_birthdays->isNotEmpty())
                        <div class="d-flex flex-column gap-2">
                            @foreach ($todays_birthdays as $bday)
                                @php
                                    $dob = \Carbon\Carbon::parse($bday->date_of_birth)->timezone('Asia/Manila');
                                    $age = $dob->age;
                                    $suffix = match(true) {
                                        $age % 10 == 1 && $age % 100 != 11 => 'st',
                                        $age % 10 == 2 && $age % 100 != 12 => 'nd',
                                        $age % 10 == 3 && $age % 100 != 13 => 'rd',
                                        default => 'th',
                                    };
                                @endphp
                                <div class="d-flex align-items-center justify-content-between interactive-row">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-container me-3" style="--ring-color: rgba(13, 110, 253, 0.2);">
                                            <img alt="User profile" src="{{ $bday->profile_picture ? Storage::url($bday->profile_picture) : Storage::url('profile_pictures/user.png') }}" class="rounded-circle avatar-ring" width="42" height="42" style="object-fit: cover;" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $bday->lastname }}, {{ $bday->firstname }} {{ $bday->suffix }} {{ $bday->middlename }}</h6>
                                            <span class="text-muted tracking-wide" style="font-size:0.75rem;">{{ $dob->format('F d, Y') }}</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary text-white px-3 py-2 border-0 fw-bold" style="border-radius:30px; font-size:0.75rem;">
                                        {{ $age }}{{ $suffix }} Birthday
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 border border-dashed rounded-3">
                            <span class="text-muted small fw-medium">NO RECORDS FOUND FOR TODAY</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card modern-widget h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark m-0" style="font-weight:700;">Today’s Work Anniversary List</h5>
                        <p class="text-muted small m-0">Corporate career benchmarks reached today.</p>
                    </div>
                    <div class="dropdown">
                        <a class="text-muted p-2 d-inline-block" href="#" role="button" id="annivDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="annivDropdown">
                            <li><a class="dropdown-item py-2 small" href="{{ route('employee.upcoming_birthdays') }}">View Upcoming Work Anniversaries</a></li>
                        </ul>
                    </div>
                </div>

                <div class="panel-body-content">
                    @php
                        // Only celebrate real anniversaries — a full year or more of tenure.
                        $todays_work_anniv = $todays_work_anniv->filter(function ($w) {
                            return \Carbon\Carbon::parse($w->date_employed)->timezone('Asia/Manila')->age >= 1;
                        });
                    @endphp
                    @if ($todays_work_anniv->isNotEmpty())
                        <div class="d-flex flex-column gap-2">
                            @foreach ($todays_work_anniv as $wanniv)
                                @php
                                    $annivDob = \Carbon\Carbon::parse($wanniv->date_employed)->timezone('Asia/Manila');
                                    $anniv = $annivDob->age;
                                    $annivSuffix = match(true) {
                                        $anniv % 10 == 1 && $anniv % 100 != 11 => 'st',
                                        $anniv % 10 == 2 && $anniv % 100 != 12 => 'nd',
                                        $anniv % 10 == 3 && $anniv % 100 != 13 => 'rd',
                                        default => 'th',
                                    };
                                @endphp
                                <div class="d-flex align-items-center justify-content-between interactive-row">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-container me-3" style="--ring-color: rgba(25, 135, 84, 0.2);">
                                            <img alt="User profile" src="{{ $wanniv->profile_picture ? Storage::url($wanniv->profile_picture) : Storage::url('profile_pictures/user.png') }}" class="rounded-circle avatar-ring" width="42" height="42" style="object-fit: cover;" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $wanniv->lastname }}, {{ $wanniv->firstname }} {{ $wanniv->suffix }} {{ $wanniv->middlename }}</h6>
                                            <span class="text-muted tracking-wide" style="font-size:0.75rem;">Tenure since {{ $annivDob->format('F d, Y') }}</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-success text-white px-3 py-2 border-0 fw-bold" style="border-radius:30px; font-size:0.75rem;">
                                        {{ $anniv }}{{ $annivSuffix }} Anniversary
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 border border-dashed rounded-3">
                            <span class="text-muted small fw-medium">NO RECORDS FOUND FOR TODAY</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if (in_array('VIEW SYSTEM LOGS', session('permissions', [])))
        <div class="col-12 mb-4">
            <div class="card modern-widget p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark m-0" style="font-weight:700;">System Logs</h5>
                        <p class="text-muted small m-0">Live backend administrative operations logs tracking updates.</p>
                    </div>
                    <div class="dropdown">
                        <a class="text-muted p-2 d-inline-block" href="#" role="button" id="logsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="logsDropdown">
                            <li><a class="dropdown-item py-2 small" href="javascript:void(0);">View All</a></li>
                            <li><a class="dropdown-item py-2 small" href="javascript:void(0);">Mark as Read</a></li>
                        </ul>
                    </div>
                </div>

                <div class="timeline-modern">
                    @foreach ($logs as $log)
                        <div class="timeline-node">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center g-2">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size:0.92rem;">
                                        {{ $log->action }}
                                        <span class="text-muted fw-medium px-2 py-0.5 bg-light rounded border ms-1" style="font-size:0.75rem;">
                                            By: {{ $log->name }}
                                        </span>
                                    </h6>
                                </div>
                                <span class="text-muted tracking-wide fw-medium mt-1 mt-sm-0" style="font-size: 0.78rem;">
                                    {{ \Carbon\Carbon::parse($log->log_date)->format('M d, h:i A') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
@endsection

@section("additional_footer")
    <script src="{{ asset('assets/src/plugins/src/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/src/assets/js/dashboard/dash_1.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chartTheme = document.body.classList.contains('dark') ? 'dark' : 'light';
            const donutPalette = ['#4361ee', '#0ea5e9', '#10b981', '#f59e0b', '#f43f5e', '#8b5cf6', '#14b8a6', '#eab308'];

            function renderDonut(elId, labels, series, colors) {
                const el = document.querySelector(elId);
                if (!el) return;
                if (!series.length || series.every(v => v === 0)) {
                    el.innerHTML = '<div class="text-center py-5 border border-dashed rounded-3"><span class="text-muted small fw-medium">NO DATA YET</span></div>';
                    return;
                }
                new ApexCharts(el, {
                    chart: { type: 'donut', height: 300 },
                    labels: labels,
                    series: series,
                    colors: colors,
                    theme: { mode: chartTheme },
                    legend: { position: 'bottom', fontSize: '12px' },
                    dataLabels: { enabled: true, formatter: (val) => val.toFixed(1) + '%' },
                    stroke: { width: 2 },
                    tooltip: { theme: chartTheme },
                }).render();
            }

            renderDonut('#chartSchedule',
                ['With Schedule', 'No Schedule Yet'],
                [{{ $sched_with }}, {{ $sched_without }}],
                ['#10b981', '#f43f5e']
            );

            renderDonut('#chartPositions',
                ['With Position', 'No Position Yet'],
                [{{ $position_with }}, {{ $position_without }}],
                ['#0ea5e9', '#f43f5e']
            );

            renderDonut('#chartSalaryGrade',
                ['With Salary Grade', 'No Salary Grade Yet'],
                [{{ $salary_grade_with }}, {{ $salary_grade_without }}],
                ['#4361ee', '#f43f5e']
            );

            renderDonut('#chartEmploymentStatus',
                @json($status_breakdown->pluck('empl_status')),
                @json($status_breakdown->pluck('total')),
                donutPalette
            );

            renderDonut('#chartBiometric',
                ['Has Biometric ID', 'No Biometric ID'],
                [{{ $biometric_with }}, {{ $biometric_without }}],
                ['#4361ee', '#f59e0b']
            );
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const timeDisplay = document.getElementById('clock-time');
            const dateDisplay = document.getElementById('clock-date');

            const updateClock = () => {
                const now = new Date();

                // Format Time: hh:mm:ss AM/PM
                let hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';

                hours = hours % 12;
                hours = hours ? hours : 12; // conversion of '0' hour to '12'
                const formattedHours = String(hours).padStart(2, '0');

                timeDisplay.textContent = `${formattedHours}:${minutes}:${seconds} ${ampm}`;

                // Format Date: Day, Month DD, YYYY (e.g., Mon, May 25, 2026)
                const options = { weekday: 'short', month: 'short', day: '2-digit', year: 'numeric' };
                dateDisplay.textContent = now.toLocaleDateString('en-US', options);
            };

            // Run right away, then update every 1 second
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.count-up');
            const duration = 1500;

            const easeOutQuad = (t) => t * (2 - t);

            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count-to'), 10) || 0;

                if (target === 0) {
                    counter.textContent = '0';
                    return;
                }

                let startTime = null;

                const animateCount = (timestamp) => {
                    if (!startTime) startTime = timestamp;
                    const progress = timestamp - startTime;

                    let progressPercent = Math.min(progress / duration, 1);
                    progressPercent = easeOutQuad(progressPercent);

                    const currentValue = Math.floor(progressPercent * target);
                    counter.textContent = currentValue.toLocaleString();

                    if (progress < duration) {
                        requestAnimationFrame(animateCount);
                    } else {
                        counter.textContent = target.toLocaleString();
                    }
                };

                requestAnimationFrame(animateCount);
            });
        });
    </script>
@endsection
