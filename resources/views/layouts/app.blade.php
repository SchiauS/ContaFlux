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
    <nav class="navbar navbar-expand-lg navbar-main py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">ContaFlux</a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
                <span class="navbar-toggler-icon text-white"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('companies.index') }}">Companii</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('accounts.index') }}">Conturi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.index') }}">Tranzacții</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tasks.index') }}">Task-uri</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
