@extends("../layout")
@section("additional_head")
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tracking-tight { letter-spacing: -0.5px; }
        .bday-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .bday-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
        }
        .bday-avatar {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e9ecef;
        }
        .bday-avatar-initials {
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
            <h3 class="fw-bold text-dark tracking-tight mb-1">Upcoming Birthdays</h3>
            <p class="text-muted small mb-0">Celebrate your team — birthdays in the next 14 days and today.</p>
        </div>
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active text-primary" aria-current="page">Upcoming Birthdays</li>
            </ol>
        </nav>
    </div>

    {{-- TODAY'S BIRTHDAYS --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Today's Birthday
                </h6>
                <div class="row g-3">
                    @if ($todays_birthday->isNotEmpty())
                        @foreach ($todays_birthday as $bday)
                        @php
                            $dob    = \Carbon\Carbon::parse($bday->date_of_birth)->timezone('Asia/Manila');
                            $age    = $dob->age;
                            $suffix = match(true) {
                                $age % 10 == 1 && $age % 100 != 11 => 'st',
                                $age % 10 == 2 && $age % 100 != 12 => 'nd',
                                $age % 10 == 3 && $age % 100 != 13 => 'rd',
                                default => 'th',
                            };
                        @endphp
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-3 bday-card text-center p-3">
                                <div class="d-flex justify-content-center mb-3">
                                    @if($bday->profile_picture)
                                        <img src="{{ asset('storage/' . $bday->profile_picture) }}" alt="avatar" class="bday-avatar">
                                    @else
                                        <div class="bday-avatar-initials">{{ strtoupper(substr($bday->firstname, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $bday->lastname }}, {{ $bday->firstname }} {{ $bday->suffix }}</h6>
                                <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($bday->date_of_birth)->format('F d, Y') }}</p>
                                <span class="badge bg-light-danger">{{ $age }}{{ $suffix }} Birthday 🎂</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center text-muted py-3">No birthdays today.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- UPCOMING BIRTHDAYS (Next 14 Days) --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Upcoming Birthdays — Next 14 Days
                </h6>
                <div class="row g-3">
                    @if ($upcoming_birthdays->isNotEmpty())
                        @foreach ($upcoming_birthdays as $bday)
                        @php
                            $dob    = \Carbon\Carbon::parse($bday->date_of_birth)->timezone('Asia/Manila');
                            $age    = $dob->age + 1;
                            $suffix = match(true) {
                                $age % 10 == 1 && $age % 100 != 11 => 'st',
                                $age % 10 == 2 && $age % 100 != 12 => 'nd',
                                $age % 10 == 3 && $age % 100 != 13 => 'rd',
                                default => 'th',
                            };
                        @endphp
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-3 bday-card text-center p-3">
                                <div class="d-flex justify-content-center mb-3">
                                    @if($bday->profile_picture)
                                        <img src="{{ asset('storage/' . $bday->profile_picture) }}" alt="avatar" class="bday-avatar">
                                    @else
                                        <div class="bday-avatar-initials">{{ strtoupper(substr($bday->firstname, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $bday->lastname }}, {{ $bday->firstname }} {{ $bday->suffix }}</h6>
                                <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($bday->date_of_birth)->format('F d, Y') }}</p>
                                <span class="badge bg-light-primary">{{ $age }}{{ $suffix }} Birthday</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center text-muted py-3">No upcoming birthdays in the next 14 days.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- PREVIOUS BIRTHDAYS (Past 7 Days) --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    Previous Birthdays — Past 7 Days
                </h6>
                <div class="row g-3">
                    @if ($previous_birthdays->isNotEmpty())
                        @foreach ($previous_birthdays as $bday)
                        @php
                            $dob    = \Carbon\Carbon::parse($bday->date_of_birth)->timezone('Asia/Manila');
                            $age    = $dob->age;
                            $suffix = match(true) {
                                $age % 10 == 1 && $age % 100 != 11 => 'st',
                                $age % 10 == 2 && $age % 100 != 12 => 'nd',
                                $age % 10 == 3 && $age % 100 != 13 => 'rd',
                                default => 'th',
                            };
                        @endphp
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-3 bday-card text-center p-3 opacity-75">
                                <div class="d-flex justify-content-center mb-3">
                                    @if($bday->profile_picture)
                                        <img src="{{ asset('storage/' . $bday->profile_picture) }}" alt="avatar" class="bday-avatar">
                                    @else
                                        <div class="bday-avatar-initials" style="background:#6c757d;">{{ strtoupper(substr($bday->firstname, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $bday->lastname }}, {{ $bday->firstname }} {{ $bday->suffix }}</h6>
                                <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($bday->date_of_birth)->format('F d, Y') }}</p>
                                <span class="badge bg-secondary">{{ $age }}{{ $suffix }} Birthday</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center text-muted py-3">No birthdays in the past 7 days.</div>
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
