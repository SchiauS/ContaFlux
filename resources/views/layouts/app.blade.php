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
    <link href="{{ asset('argon/css/daterangepicker.css') }}" rel="stylesheet">
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
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-chart-pie text-primary"></i>
                    </div>
                    <span class="nav-link-text ms-1">Rapoarte</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-building text-primary"></i>
                    </div>
                    <span class="nav-link-text ms-1">Companie</span>
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
                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users text-primary"></i>
                    </div>
                    <span class="nav-link-text ms-1">Angajați</span>
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
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Acasa</a></li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">@yield('title', 'ContaFlux')</li>
                </ol>
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
                <ul class="navbar-nav  justify-content-end">
                    <li class="nav-item d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">Sign In</span>
                        </a>
                    </li>
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item px-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0">
                            <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown pe-2 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell cursor-pointer"></i>
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="d-flex py-1">
                                        <div class="my-auto">
                                            <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">
                                                <span class="font-weight-bold">New message</span> from Laur
                                            </h6>
                                            <p class="text-xs text-secondary mb-0">
                                                <i class="fa fa-clock me-1"></i>
                                                13 minutes ago
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="d-flex py-1">
                                        <div class="my-auto">
                                            <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">
                                                <span class="font-weight-bold">New album</span> by Travis Scott
                                            </h6>
                                            <p class="text-xs text-secondary mb-0">
                                                <i class="fa fa-clock me-1"></i>
                                                1 day
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="d-flex py-1">
                                        <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                            <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <title>credit-card</title>
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                        <g transform="translate(1716.000000, 291.000000)">
                                                            <g transform="translate(453.000000, 454.000000)">
                                                                <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                                                <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">
                                                Payment successfully completed
                                            </h6>
                                            <p class="text-xs text-secondary mb-0">
                                                <i class="fa fa-clock me-1"></i>
                                                2 days
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
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
                                </script>. Toate drepturile sunt rezervate.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
    </div>
</main>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirmare ștergere</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
            </div>
            <div class="modal-body">
                <p id="deleteModalMessage" class="mb-2">Ești sigur că vrei să ștergi acest element?</p>
                <div class="alert alert-danger d-none" id="deleteErrorAlert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fa-solid fa-trash me-1"></i> Șterge
                </button>
            </div>
        </div>
    </div>
</div>

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

<form id="global-delete-form" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('argon/js/core/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('argon/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('argon/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('argon/js/argon-dashboard.min.js') }}"></script>
<script src="{{ asset('argon/js/moment.js') }}"></script>
<script src="{{ asset('argon/js/daterangepicker.js') }}"></script>
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

        let deleteUrl = null;
        const deleteModalEl = document.getElementById('confirmDeleteModal');
        const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

        $(document).on('click', '.js-delete-trigger', function () {
            if (!deleteModal) {
                return;
            }

            deleteUrl = $(this).data('delete-url');
            const itemName = $(this).data('item-name') || 'acest element';
            $('#deleteModalMessage').text(`Ești sigur că vrei să ștergi ${itemName}?`);
            $('#deleteErrorAlert').addClass('d-none').text('');
            $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fa-solid fa-trash me-1"></i> Șterge');
            deleteModal.show();
        });

        $('#confirmDeleteBtn').on('click', function () {
            if (!deleteUrl) {
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Se șterge');

            const form = document.getElementById('global-delete-form');
            form.setAttribute('action', deleteUrl);
            form.submit();
        });
    }
</script>
@stack('scripts')
</body>
</html>
