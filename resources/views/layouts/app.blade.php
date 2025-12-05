<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ContaFlux')</title>
    <link rel="icon" type="image/svg+xml" href="{{asset('favicon.svg')}}">
    <link rel="alternate icon" type="image/png" href="{{asset('favicon.svg')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" referrerpolicy="no-referrer" />
    <link href="{{ asset('argon/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/css/argon-dashboard.min.css') }}" rel="stylesheet">
    <style>
        .modal-backdrop {
            z-index: 1400 !important;
        }
        .modal {
            z-index: 1450 !important;
        }
        .sidenav {
            z-index: 999!important;
        }
    </style>
    @stack('styles')
</head>
<body class="g-sidenav-show bg-gray-100">
<div class="min-height-300 bg-primary position-absolute w-100"></div>
<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 shadow-lg" id="sidenav-main">
    <div class="sidenav-header">
        <a class="navbar-brand m-0 d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{asset('favicon.svg')}}" class="mr-2" height="32">
            <h5 class="font-weight-bolder text-dark mb-0" style="margin-left: 11px;">Conta</h5>
            <h5 class="font-weight-bolder text-primary mb-0">Flux</h5>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-chart-line text-primary"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-building text-primary"></i>
                    </div>
                    <span class="nav-link-text ms-1">Companii</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-coins text-primary"></i>
                    </div>
                    <span class="nav-link-text ms-1">Conturi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-file-invoice-dollar text-primary"></i>
                    </div>
                    <span class="nav-link-text ms-1">Tranzacții</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-list-check text-primary"></i>
                    </div>
                    <span class="nav-link-text ms-1">Task-uri</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <h6 class="font-weight-bolder text-white mb-0">@yield('title', 'ContaFlux')</h6>
            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 justify-content-end" id="navbar">
                <button class="btn btn-sm btn-outline-light mb-0" data-bs-toggle="modal" data-bs-target="#aiAssistantModal">
                    <i class="fa-solid fa-robot me-1"></i> Asistent AI
                </button>
                <div class="ms-3 d-flex align-items-center gap-3">
                    <div class="text-white text-sm d-none d-md-block">{{ auth()->user()->name }}</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light text-primary m-0">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Delogare
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
            <footer class="footer pt-3">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © Schiau Sebastian-Adrian - <script>
                                    document.write(new Date().getFullYear())
                                </script>. All rights reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
    </div>
</main>

<div class="modal fade" id="aiAssistantModal" tabindex="-1" aria-labelledby="aiAssistantLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aiAssistantLabel">Asistent AI - Sugestii tranzacții</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
            </div>
            <div class="modal-body">
                <form id="ai-chat-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Descrie analiza dorită</label>
                        <textarea class="form-control" id="aiPrompt" name="prompt" rows="4" placeholder="Ex: Sugerează tranzacții pentru cheltuieli de marketing luna aceasta."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" id="aiSubmitBtn">
                        <i class="fa-solid fa-paper-plane me-1"></i> Trimite
                    </button>
                </form>
                <div class="mt-4 d-none" id="aiResponseWrapper">
                    <h6>Răspuns Asistent</h6>
                    <div class="alert alert-secondary" id="aiResponse"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('argon/js/core/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('argon/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('argon/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('argon/js/argon-dashboard.min.js') }}"></script>
<script>
    if (window.$) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        $('#ai-chat-form').on('submit', function (e) {
            e.preventDefault();
            const prompt = $('#aiPrompt').val().trim();
            if (!prompt.length) {
                return;
            }
            const $btn = $('#aiSubmitBtn');
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Se procesează');

            $.post("{{ route('ai.chat') }}", { prompt })
                .done(function (response) {
                    $('#aiResponseWrapper').removeClass('d-none');
                    $('#aiResponse').text(response.reply ?? JSON.stringify(response, null, 2));
                })
                .fail(function () {
                    $('#aiResponseWrapper').removeClass('d-none');
                    $('#aiResponse').text('A apărut o eroare. Încearcă din nou.');
                })
                .always(function () {
                    $btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-1"></i> Trimite');
                });
        });
    }
</script>
@stack('scripts')
</body>
</html>
