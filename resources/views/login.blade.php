<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>SignIn - SAVEST-HRIS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/src/assets/img/savesthris_logo.svg')}}"/>

    <link href="{{ asset('assets/layouts/horizontal-light-menu/css/light/loader.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/horizontal-light-menu/css/dark/loader.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/layouts/horizontal-light-menu/loader.js')}}"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/src/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/horizontal-light-menu/css/light/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/light/authentication/auth-cover.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/horizontal-light-menu/css/dark/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/authentication/auth-cover.css')}}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Icons for the eye icon toggle -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- END GLOBAL MANDATORY STYLES -->

    <style>
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }

        .cover {
            position: fixed;
            left: 0;
            top: 0;
            width: 50%;
            height: 100vh;
            background-image: url("{{ asset('assets/src/assets/img/savest-cover-img.svg')}}");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .brand-logo-img {
            max-height: 65px;
            width: auto;
            object-fit: contain;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 480px;
            width: 100%;
        }

        .form-control {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0e6ed;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #3f71a5;
            box-shadow: 0 0 0 0.25rem rgba(63, 113, 165, 0.15);
        }

        .btn-primary-custom {
            background-color: #3f71a5;
            border-color: #3f71a5;
            padding: 0.6rem;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: background-color 0.2s ease;
        }

        .btn-primary-custom:hover {
            background-color: #315984;
            border-color: #315984;
        }

        .btn-google {
            padding: 0.6rem;
            border-radius: 8px;
            font-weight: 600;
            background: #fff;
            transition: background 0.2s ease;
        }

        .btn-google:hover {
            background: #f8f9fa;
        }

        .password-toggle {
            cursor: pointer;
            color: #6c757d;
        }

        .form-check-input:checked {
            background-color: #3f71a5;
            border-color: #3f71a5;
        }

        @media (max-width: 991px) {
            .cover {
                display: none;
            }
        }
    </style>
</head>

<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!-- END LOADER -->

    <div class="auth-container min-vh-100 d-flex align-items-center">
        <div class="container-fluid p-0">
            <div class="row w-100 m-0">

                <!-- LEFT SIDE COVER IMAGE -->
                <div class="col-lg-6 d-none d-lg-block p-0">
                    <div class="cover"></div>
                </div>

                <!-- RIGHT SIDE LOGIN FORM -->
                <div class="col-lg-6 d-flex align-items-center justify-content-center p-4">
                    <div class="card card-custom bg-white p-3 p-sm-4">
                        <div class="card-body bg-white">

                            <!-- Logo & Header -->
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <img src="{{ asset('assets/src/assets/img/savesthris_logo.svg')}}" alt="Logo" class="brand-logo-img">
                                </div>
                                <h2 class="fw-bold text-dark mb-1">Sign In</h2>
                                <p class="text-muted small">Enter your username and password to login to SAVEST-HRIS</p>
                            </div>

                            <!-- Session Errors -->
                            @if (session('error'))
                                <div class="alert alert-danger mb-4 border-0 shadow-sm d-flex align-items-center" role="alert">
                                    <div>
                                        <strong>{{ session('error') }}</strong> Please sign in again.
                                    </div>
                                </div>
                            @endif

                            <!-- Form Content -->
                            <form action="{{ route('hris.check_login') }}" method="post" autocomplete="off">
                                @csrf
                                <div class="row g-3">

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-semibold text-secondary small">Username</label>
                                            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-semibold text-secondary small">Password</label>
                                            <div class="input-group">
                                                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" style="border-right: none;" required>
                                                <span class="input-group-text bg-white password-toggle" id="togglePassword" style="border-left: none;">
                                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SIGN IN BUTTON -->
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary-custom w-100 text-white shadow-sm">SIGN IN</button>
                                    </div>

                                    <!-- OR DIVIDER -->
                                    <div class="col-12 my-3 text-center position-relative">
                                        <hr class="text-muted">
                                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">or</span>
                                    </div>

                                    <!-- GOOGLE LOGIN BUTTON -->
                                    <div class="col-12">
                                        <a href="{{ route('google.login') }}" class="btn btn-google w-100 d-flex align-items-center justify-content-center shadow-sm text-dark">
                                            <img src="https://developers.google.com/identity/images/g-logo.png" width="18" class="me-2" alt="Google Logo">
                                            Login using Google
                                        </a>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('assets/src/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            password.type = password.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
