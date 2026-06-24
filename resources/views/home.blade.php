@extends('layouts.template')

@section('page_title', 'Dashboard Distribusi Fasilitas Kesehatan')
@section('page_subtitle', 'Ringkasan statistik dan grafik informasi aplikasi')

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
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            transition: transform 0.15s;
            background: white;
            height: 100%;
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
        .chart-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
            background: white;
            padding: 20px;
            height: 100%;
        }
        .chart-container {
            position: relative;
            margin: auto;
            height: 260px;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="dashboard-header mb-4">
            <h2 class="mb-2"><i class="fa-solid fa-chart-pie me-2"></i> Dashboard GeoHealth Explorer</h2>
            <p class="mb-0">Selamat datang. Aplikasi ini menampilkan persebaran fasilitas kesehatan di Kota Yogyakarta secara interaktif.</p>
        </div>

        <div class="row g-4 mb-4 justify-content-center">
            <div class="col-12 col-md-4">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon text-primary"><i class="fa-solid fa-hospital"></i></div>
                    <div class="stat-title">Jumlah Rumah Sakit</div>
                    <div class="stat-value">{{ $points_count }}</div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon text-success"><i class="fa-solid fa-house-medical"></i></div>
                    <div class="stat-title">Jumlah Puskesmas</div>
                    <div class="stat-value" style="color: #198754;">{{ $puskesmas_count }}</div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon text-warning"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-title">Jumlah User</div>
                    <div class="stat-value" style="color: #ffc107;">{{ $users_count }}</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-5">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-chart-pie me-2"></i> Proporsi Fasilitas Kesehatan</h5>
                    <div class="chart-container">
                        <canvas id="faskesPieChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-7">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-chart-bar me-2"></i> Perbandingan Kuantitas Faskes</h5>
                    <div class="chart-container">
                        <canvas id="faskesBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4" style="border: none; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.05);">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3"><i class="fa-solid fa-info-circle me-2 text-primary"></i> Tentang Aplikasi</h5>
                <p class="mb-0 text-muted">
                    Aplikasi ini dibuat untuk memenuhi Responsi praktikum Pemrograman Geospasial Web Lanjut. Aplikasi ini menampilkan peta interaktif persebaran fasilitas kesehatan di Kota Yogyakarta,
                    yang menunjukkan objek geometri titik yang dapat ditambah, ditampilkan, diubah, dan dihapus.<br>
                    <span class="badge bg-light text-dark mt-2 p-2"><b>Teknologi:</b> Laravel, MySQL, PostGIS, Leaflet, Chart.js, Bootstrap</span>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Memuat Library Chart.js via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Ambil data langsung dari variabel Blade Laravel
        const rsData = {{ $points_count }};
        const puskData = {{ $puskesmas_count }};

        // 1. Konfigurasi Diagram Lingkaran (Pie Chart)
        const ctxPie = document.getElementById('faskesPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Rumah Sakit', 'Puskesmas'],
                datasets: [{
                    data: [rsData, puskData],
                    backgroundColor: ['#0d6efd', '#198754'],
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // 2. Konfigurasi Diagram Batang (Bar Chart)
        const ctxBar = document.getElementById('faskesBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Rumah Sakit', 'Puskesmas'],
                datasets: [{
                    label: 'Jumlah Fasilitas Kesehatan',
                    data: [rsData, puskData],
                    backgroundColor: ['rgba(13, 110, 253, 0.2)', 'rgba(25, 135, 84, 0.2)'],
                    borderColor: ['#0d6efd', '#198754'],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan label legend karena hanya 1 kategori dataset
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5 // Mengatur interval grid angka ke atas
                        }
                    }
                }
            }
        });
    </script>
@endsection
