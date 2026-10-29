@extends("../layout")
@section("additional_head")
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tracking-tight { letter-spacing: -0.5px; }
        .anniv-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .anniv-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
        }
        .anniv-avatar {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e9ecef;
        }
        .anniv-avatar-initials {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #4361ee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            border: 3px solid #e9ecef;
            flex-shrink: 0;
        }
    </style>
@endsection

@section("content")
<div class="row layout-top-spacing gap-y-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Work Anniversaries</h3>
            <p class="text-muted small mb-0">Celebrate your team — work anniversaries in the next 14 days and today.</p>
        </div>
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active text-primary" aria-current="page">Work Anniversaries</li>
            </ol>
        </nav>
    </div>

    {{-- TODAY'S ANNIVERSARIES --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Today's Work Anniversary
                </h6>
                <div class="row g-3">
                    @if ($todays_work_anniv->isNotEmpty())
                        @foreach ($todays_work_anniv as $w_anniv)
                        @php
                            $dob    = \Carbon\Carbon::parse($w_anniv->date_employed)->timezone('Asia/Manila');
                            $anniv  = $dob->age;
                            $suffix = match(true) {
                                $anniv % 10 == 1 && $anniv % 100 != 11 => 'st',
                                $anniv % 10 == 2 && $anniv % 100 != 12 => 'nd',
                                $anniv % 10 == 3 && $anniv % 100 != 13 => 'rd',
                                default => 'th',
                            };
                        @endphp
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-3 anniv-card text-center p-3">
                                <div class="d-flex justify-content-center mb-3">
                                    @if($w_anniv->profile_picture)
                                        <img src="{{ asset('storage/' . $w_anniv->profile_picture) }}" alt="avatar" class="anniv-avatar">
                                    @else
                                        <div class="anniv-avatar-initials">{{ strtoupper(substr($w_anniv->firstname, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $w_anniv->lastname }}, {{ $w_anniv->firstname }} {{ $w_anniv->suffix }}</h6>
                                <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($w_anniv->date_employed)->format('F d, Y') }}</p>
                                <span class="badge bg-light-danger">{{ $anniv }}{{ $suffix }} Work Anniversary 🎉</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center text-muted py-3">No work anniversaries today.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- UPCOMING ANNIVERSARIES (Next 14 Days) --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Upcoming Work Anniversaries — Next 14 Days
                </h6>
                <div class="row g-3">
                    @if ($upcoming_work_anniv->isNotEmpty())
                        @foreach ($upcoming_work_anniv as $w_anniv)
                        @php
                            $dob    = \Carbon\Carbon::parse($w_anniv->date_employed)->timezone('Asia/Manila');
                            $anniv  = $dob->age + 1;
                            $suffix = match(true) {
                                $anniv % 10 == 1 && $anniv % 100 != 11 => 'st',
                                $anniv % 10 == 2 && $anniv % 100 != 12 => 'nd',
                                $anniv % 10 == 3 && $anniv % 100 != 13 => 'rd',
                                default => 'th',
                            };
                        @endphp
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-3 anniv-card text-center p-3">
                                <div class="d-flex justify-content-center mb-3">
                                    @if($w_anniv->profile_picture)
                                        <img src="{{ asset('storage/' . $w_anniv->profile_picture) }}" alt="avatar" class="anniv-avatar">
                                    @else
                                        <div class="anniv-avatar-initials">{{ strtoupper(substr($w_anniv->firstname, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $w_anniv->lastname }}, {{ $w_anniv->firstname }} {{ $w_anniv->suffix }}</h6>
                                <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($w_anniv->date_employed)->format('F d, Y') }}</p>
                                <span class="badge bg-light-primary">{{ $anniv }}{{ $suffix }} Work Anniversary</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center text-muted py-3">No upcoming work anniversaries in the next 14 days.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- PREVIOUS ANNIVERSARIES (Past 7 Days) --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    Previous Work Anniversaries — Past 7 Days
                </h6>
                <div class="row g-3">
                    @if ($previous_work_anniv->isNotEmpty())
                        @foreach ($previous_work_anniv as $w_anniv)
                        @php
                            $dob    = \Carbon\Carbon::parse($w_anniv->date_employed)->timezone('Asia/Manila');
                            $anniv  = $dob->age;
                            $suffix = match(true) {
                                $anniv % 10 == 1 && $anniv % 100 != 11 => 'st',
                                $anniv % 10 == 2 && $anniv % 100 != 12 => 'nd',
                                $anniv % 10 == 3 && $anniv % 100 != 13 => 'rd',
                                default => 'th',
                            };
                        @endphp
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-3 anniv-card text-center p-3 opacity-75">
                                <div class="d-flex justify-content-center mb-3">
                                    @if($w_anniv->profile_picture)
                                        <img src="{{ asset('storage/' . $w_anniv->profile_picture) }}" alt="avatar" class="anniv-avatar">
                                    @else
                                        <div class="anniv-avatar-initials" style="background:#6c757d;">{{ strtoupper(substr($w_anniv->firstname, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $w_anniv->lastname }}, {{ $w_anniv->firstname }} {{ $w_anniv->suffix }}</h6>
                                <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($w_anniv->date_employed)->format('F d, Y') }}</p>
                                <span class="badge bg-secondary">{{ $anniv }}{{ $suffix }} Work Anniversary</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center text-muted py-3">No work anniversaries in the past 7 days.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section("additional_footer")
<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
@endsection
