<nav class="navbar navbar-expand-lg navbar-light">

    <div class="container-fluid px-4">

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- MENU KIRI -->
            <ul class="navbar-nav">

                <li class="nav-item">
                      <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}"
                          href="{{ route('home') }}">
                        <i class="fa-solid fa-house me-1"></i>
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('peta') ? 'active' : '' }}"
                       href="/peta">
                        <i class="fa-solid fa-map-location-dot me-1"></i>
                        Peta
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('tabel') ? 'active' : '' }}"
                       href="{{ route('tabel') }}">
                        <i class="fa-solid fa-hospital me-1"></i>
                        Data RS
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('tentang') ? 'active' : '' }}"
                       href="{{ route('tentang') }}">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Tentang
                    </a>
                </li>

            </ul>

            @auth
                <!-- TOMBOL LOGOUT (Ditambahkan 'ms-auto' agar menempel ke ujung kanan) -->
                <form method="POST" action="{{ route('logout') }}" class="m-0 ms-auto">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-logout">
                        <i class="fa-solid fa-right-from-bracket me-1"></i>
                        Logout
                    </button>
                </form>
            @endauth

        </div>

    </div>

</nav>
