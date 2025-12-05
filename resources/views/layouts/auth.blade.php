<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ContaFlux')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('argon/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/css/argon-dashboard.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-100">
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-100 d-flex align-items-center" style="background-image: url('{{ asset('argon/img/curved-images/curved14.jpg') }}');">
            <span class="mask bg-gradient-primary opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-5 col-md-7 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom shadow-lg border-0">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h4 class="font-weight-bolder text-primary">@yield('card-title', 'ContaFlux')</h4>
                                <p class="mb-0 text-sm">@yield('card-subtitle', 'Introduceți datele contului pentru a continua.')</p>
                            </div>
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                        <p class="text-white text-center mt-4 mb-0 text-sm">
                            O platformă de analiză contabilă și productivitate asistată de AI.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('argon/js/core/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
