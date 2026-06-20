@extends('layouts.template')

@section('styles')
    <style>
        .about-hero {
            background: linear-gradient(90deg,#0d6efd 0%, #6c63ff 100%);
            color: #fff;
            padding: 28px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .about-card {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(15,23,42,0.06);
            background: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="about-hero">
            <h3 class="mb-1">Tentang Aplikasi</h3>
            <p class="mb-0">Aplikasi ini menampilkan persebaran Rumah Sakit di wilayah Kota Yogyakarta. Pengguna dapat menambahkan, melihat, mengedit, dan menghapus data titik rumah sakit beserta foto dan deskripsi singkat.</p>
        </div>

        <div class="card about-card mb-4">
            <div class="card-body">
                <h5 class="card-title">Fitur Utama</h5>
                <ul>
                    <li>Peta interaktif dengan marker dan popup informasi rumah sakit.</li>
                    <li>Upload foto rumah sakit yang tersimpan dan ditampilkan pada popup dan tabel.</li>
                    <li>Cluster marker untuk memudahkan visualisasi persebaran.</li>
                    <li>Halaman tabel untuk melihat daftar lengkap rumah sakit.</li>
                </ul>

                <h5 class="card-title mt-3">Teknologi</h5>
                <p class="mb-0">Dibangun dengan Laravel, Leaflet, Bootstrap, dan PostGIS (opsional) untuk penyimpanan geometri.</p>

                <div class="mt-4">
                    <a href="{{ route('peta') }}" class="btn btn-primary me-2"><i class="fa-solid fa-map-location-dot me-1"></i> Lihat Peta</a>
                    <a href="{{ route('tabel') }}" class="btn btn-outline-primary"><i class="fa-solid fa-list me-1"></i> Daftar Rumah Sakit</a>
                </div>
            </div>
        </div>
    </div>
@endsection
