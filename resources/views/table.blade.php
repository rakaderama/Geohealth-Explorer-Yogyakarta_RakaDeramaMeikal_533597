@extends('layouts.template')

@section('page_title', 'Daftar Fasilitas Kesehatan')
@section('page_subtitle', 'Tabel lengkap rumah sakit dan puskesmas yang terdaftar')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Sinkronisasi Google Fonts ke Plus Jakarta Sans & Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            letter-spacing: -0.01em;
        }

        h1, h2, h3, h4, h5, h6, .nav-tabs-custom .nav-link, table.dataTable thead th, .faskes-link {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .container-fluid {
            padding: 2rem 3vw !important;
        }

        /* Modern Tab Styling */
        .nav-tabs-custom {
            border-bottom: 2px solid #e2e8f0;
            gap: 8px;
        }
        .nav-tabs-custom .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            position: relative;
            transition: all 0.2s ease;
        }
        .nav-tabs-custom .nav-link:hover {
            color: #0d6efd;
            background-color: #f1f5f9;
        }
        .nav-tabs-custom .nav-link.active {
            color: #0d6efd;
            background: transparent;
        }
        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #0d6efd;
        }

        /* Card Workspace */
        .dt-card-workspace {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
            border: 1px solid #e2e8f0;
            padding: 24px;
        }

        /* Table Design */
        table.dataTable {
            border-collapse: collapse !important;
            margin-top: 16px !important;
        }
        table.dataTable thead th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 14px 10px !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }
        table.dataTable tbody td {
            padding: 14px 10px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 0.9rem;
        }
        table.dataTable tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* Link & Utilities styling */
        .faskes-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.15s;
        }
        .faskes-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }
        .faskes-thumb {
            width: 100px;
            height: 64px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #f1f5f9;
        }
        .badge-date {
            background-color: #f1f5f9;
            color: #64748b;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* DataTables Controls Modernization Override */
        .dt-search input {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            font-size: 0.9rem !important;
        }
        .dt-paging-button {
            border-radius: 6px !important;
            border: 1px solid #e2e8f0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="faskesTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="rs-tab" data-bs-toggle="tab" data-bs-target="#rs-panel" type="button" role="tab">
                    <i class="fa-solid fa-hospital me-2"></i> Rumah Sakit
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="puskesmas-tab" data-bs-toggle="tab" data-bs-target="#puskesmas-panel" type="button" role="tab">
                    <i class="fa-solid fa-house-medical-flag me-2"></i> Puskesmas
                </button>
            </li>
        </ul>

        <div class="tab-content" id="faskesTabContent">

            <div class="tab-pane fade show active" id="rs-panel" role="tabpanel" aria-labelledby="rs-tab">
                <div class="dt-card-workspace">
                    <table class="table align-middle" id="tableRumahSakit">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Rumah Sakit</th>
                                <th>Deskripsi / Fasilitas</th>
                                <th style="width: 120px;">Foto</th>
                                <th style="width: 160px;">Tanggal Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $noRS = 1; @endphp
                            @foreach ($points as $p)
                                @if(!Str::contains(Str::lower($p['name']), ['puskesmas', 'pkm']))
                                    <tr>
                                        <td class="text-center text-muted font-monospace">{{ $noRS++ }}</td>
                                        <td>
                                            <a href="{{ route('peta') }}?focus={{ $p['id'] }}" class="faskes-link d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-location-dot small text-muted"></i> {{ $p['name'] }}
                                            </a>
                                        </td>
                                        <td class="text-secondary">{{ $p['description'] ?? 'Tidak ada keterangan tambahan.' }}</td>
                                        <td>
                                            <img src="{{ $p['image'] ? asset('storage/images/' . $p['image']) : 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=200&auto=format&fit=crop' }}" alt="Faskes image" class="faskes-thumb border" />
                                        </td>
                                        <td>
                                            <span class="badge-date"><i class="fa-regular fa-calendar"></i> {{ $p['created_at'] }}</span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="puskesmas-panel" role="tabpanel" aria-labelledby="puskesmas-tab">
                <div class="dt-card-workspace">
                    <table class="table align-middle" id="tablePuskesmas">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Puskesmas</th>
                                <th>Deskripsi / Wilayah Kerja</th>
                                <th style="width: 120px;">Foto</th>
                                <th style="width: 160px;">Tanggal Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $noPuskes = 1; @endphp
                            @foreach ($points as $p)
                                @if(Str::contains(Str::lower($p['name']), ['puskesmas', 'pkm']))
                                    <tr>
                                        <td class="text-center text-muted font-monospace">{{ $noPuskes++ }}</td>
                                        <td>
                                            <a href="{{ route('peta') }}?focus={{ $p['id'] }}" class="faskes-link d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-location-dot small text-muted"></i> {{ $p['name'] }}
                                            </a>
                                        </td>
                                        <td class="text-secondary">{{ $p['description'] ?? 'Tidak ada keterangan tambahan.' }}</td>
                                        <td>
                                            <img src="{{ $p['image'] ? asset('storage/images/' . $p['image']) : 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?q=80&w=200&auto=format&fit=crop' }}" alt="Faskes image" class="faskes-thumb border" />
                                        </td>
                                        <td>
                                            <span class="badge-date"><i class="fa-regular fa-calendar"></i> {{ $p['created_at'] }}</span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#tableRumahSakit').DataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari Rumah Sakit...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                }
            });

            $('#tablePuskesmas').DataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari Puskesmas...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                }
            });
        });
    </script>
@endsection
