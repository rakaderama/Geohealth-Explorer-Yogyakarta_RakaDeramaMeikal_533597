<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

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
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}"
                       href="/">
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
                    <a class="nav-link {{ Request::is('/tabel') ? 'active' : '' }}"
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

            <!-- SEARCH + LOGOUT KANAN -->
            <div class="ms-auto d-flex align-items-center">
                <div id="nav-search-container" class="me-3 custom-search" style="position:relative;">
                    <input id="point-search" class="search-input form-control" type="text" placeholder="Cari fasilitas kesehatan..." autocomplete="off" style="min-width:260px; max-width:360px;" />
                    <div id="search-suggestions" class="search-suggestions"></div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-logout">
                        <i class="fa-solid fa-right-from-bracket me-1"></i>
                        Logout
                    </button>
                </form>
            </div>

        </div>

    </div>

</nav>
