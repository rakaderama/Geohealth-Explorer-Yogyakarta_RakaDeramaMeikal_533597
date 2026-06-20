<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoHealth Explorer Yogyakarta</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6fb;
            margin: 0;
            padding: 0;
        }

        /* HEADER */
        .app-header {
            background: linear-gradient(90deg, #0d6efd 0%, #6c63ff 100%);
            color: white;
            padding: 22px 0;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
        }

        .app-title {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 4px;
        }

        /* NAVBAR */
        .navbar {
            padding-top: 10px;
            padding-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .navbar-nav .nav-link {
            font-size: 18px;
            font-weight: 600;
            padding: 10px 18px !important;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #ffd43b !important;
        }

        .navbar-nav .nav-link.active {
            border-bottom: 3px solid white;
        }

        .btn-logout {
            font-size: 16px;
            font-weight: 600;
            padding: 8px 18px;
        }

        @media (max-width: 768px) {
            .app-title {
                font-size: 1.4rem;
            }

            .brand-sub {
                font-size: 0.9rem;
            }

            .navbar-nav .nav-link {
                font-size: 16px;
            }
        }
    </style>

    @yield('styles')
</head>

<body>

    <!-- HEADER -->
    <header class="app-header">
        <div class="container-fluid px-4">
            <div class="app-title">
                GeoHealth Explorer Yogyakarta
            </div>

            <div class="brand-sub">
                Peta interaktif persebaran rumah sakit di Kota Yogyakarta
            </div>
        </div>
    </header>

    <!-- NAVBAR -->
    @include('components.navbar')

    <!-- CONTENT -->
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

    @yield('scripts')

    @include('components.toast')

</body>
</html>
