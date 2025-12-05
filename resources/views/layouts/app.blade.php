<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ContaFlux')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" integrity="sha512-1f5T5nYeD1yfknhk+NCA8DmF5asJ5AZt0pOEtpJR/YWZLxE+nobVhtSGcVwqDAVBBusfvEvFpc1dV0C2Zaw2xg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('argon/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/css/argon-dashboard.min.css') }}" rel="stylesheet">
    @stack('styles')
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .navbar-main {
            background: linear-gradient(310deg,#141727,#3a416f);
        }
        .navbar-main .navbar-brand,
        .navbar-main .nav-link {
            color: #fff !important;
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-100">
<div class="min-height-300 bg-dark position-absolute w-100"></div>
<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('dashboard') }}">
            <img src="{{''}}" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">ContaFlux</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('companies.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Companii</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('accounts.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Conturi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transactions.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-app text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Tranzacții</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('tasks.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-app text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Task-uri</span>
                </a>
            </li>
        </ul>
    </div>
    </aside>

    <div class="container-fluid py-4">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-3fh7xZt+xiqf+4iZHPxemwF/6Ckxzgv2Fne5DE5pSg0=" crossorigin="anonymous"></script>
    <script src="{{ asset('argon/js/core/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('argon/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('argon/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('argon/js/argon-dashboard.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
