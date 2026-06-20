@extends('layouts.template')

@section('styles')
    <style>
        body {
            background-color: #f5f7fb;
        }
        .dashboard-header {
            background: linear-gradient(90deg, #0d6efd 60%, #6c63ff 100%);
            color: white;
            border-radius: 16px;
            padding: 32px 24px 24px 24px;
            box-shadow: 0 4px 24px rgba(13,110,253,0.12);
            margin-bottom: 32px;
        }
        .dashboard-header h2 {
            font-weight: 700;
            font-size: 2.2rem;
        }
        .dashboard-header p {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            transition: transform 0.15s;
            background: white;
        }
        .stat-card:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 6px 24px rgba(13,110,253,0.13);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .stat-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #6c757d;
        }
        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #0d6efd;
        }
        @media (max-width: 767px) {
            .dashboard-header {
                padding: 18px 10px 14px 10px;
                font-size: 1.1rem;
            }
            .stat-card {
                margin-bottom: 18px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="dashboard-header mb-4">
            <h2 class="mb-2"><i class="fa-solid fa-hospital me-2"></i> Dashboard Distribusi Rumah Sakit</h2>
            <p class="mb-0">Selamat datang. Aplikasi ini menampilkan persebaran rumah sakit di Kota Yogyakarta secara interaktif.</p>
        </div>

        <div class="row g-4 mb-4 justify-content-center">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon text-primary"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="stat-title">Jumlah Rumah Sakit</div>
                    <div class="stat-value">{{ $points_count }}</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon text-warning"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-title">Jumlah User</div>
                    <div class="stat-value">1</div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fa-solid fa-info-circle me-2"></i> Tentang Aplikasi</h5>
                <p class="mb-0">
                    Aplikasi ini dibuat untuk memenuhi Responsi praktikum Pemrograman Geospasial Web Lanjut. Aplikasi ini menampilkan peta interaktif persebaran rumah sakit di Kota Yogyakarta.
                yang menunjukkan objek geometri titik yang dapat ditambah, ditampilkan, diubah, dan dihapus.<br>
                    <b>Teknologi:</b> Laravel, MySQL, PostgreSQL - PostGIS, Leaflet, Bootstrap.
                </p>
            </div>
        </div>
    </div>
@endsection
