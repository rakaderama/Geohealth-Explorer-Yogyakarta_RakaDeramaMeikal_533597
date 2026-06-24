@extends('layouts.template')

@section('page_title', 'Peta Fasilitas Kesehatan')
@section('page_subtitle', 'Temukan rumah sakit dan fasilitas kesehatan di Kota Yogyakarta')
@section('hero_image')
    <img src="https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?q=80&w=600&auto=format&fit=crop" alt="Map hero">
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Sinkronisasi Google Fonts tema Faskes --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            overflow: hidden;
            letter-spacing: -0.01em;
        }

        h1, h2, h3, h4, h5, h6, .popup-title, .faskes-item-card h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Full Height Split Screen Layout */
        .dashboard-container {
            display: flex;
            height: calc(100vh - var(--header-height, 64px));
            width: 100%;
            position: relative;
        }

        /* Left Panel - Sidebar List & Stats */
        .sidebar-panel {
            width: 420px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            z-index: 10;
            box-shadow: 4px 0 24px rgba(15, 23, 42, 0.02);
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 16px;
        }

        .stat-card {
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }

        .list-container {
            flex: 1;
            overflow-y: auto;
            padding: 16px 24px;
        }

        /* Faskes Card Item in Sidebar */
        .faskes-item-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            gap: 12px;
        }

        .faskes-item-card:hover {
            border-color: #0d6efd;
            box-shadow: 0 10px 15px -3px rgba(13, 110, 253, 0.08);
            transform: translateY(-2px);
        }

        .faskes-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            background: #f1f5f9;
        }

        /* Right Panel - Map Full Width */
        .map-panel {
            flex: 1;
            position: relative;
            height: 100%;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        /* Modernized Leaflet UI */
        .leaflet-bar {
            border: none !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
            border-radius: 10px !important;
            overflow: hidden;
        }

        .leaflet-bar a {
            border-bottom: 1px solid #f1f5f9 !important;
            color: #475569 !important;
        }

        /* Premium Glassmorphism Marker Base */
        .marker-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            border: 3px solid #ffffff;
            transition: transform 0.2s ease;
        }

        /* Pembeda Warna Marker */
        .marker-rs {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            box-shadow: 0 8px 16px rgba(13, 110, 253, 0.3);
        }
        .marker-pkm {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            box-shadow: 0 8px 16px rgba(25, 135, 84, 0.3);
        }

        /* Modern Map Popup Card */
        .leaflet-popup-content-wrapper {
            border-radius: 16px !important;
            padding: 0 !important;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15) !important;
        }
        .leaflet-popup-content { margin: 0 !important; width: 300px !important; }
        .popup-card { display: flex; flex-direction: column; }
        .popup-image { width: 100%; height: 140px; object-fit: cover; }
        .popup-body { padding: 16px; }
        .popup-title { font-weight: 700; font-size: 1rem; color: #0f172a; margin-bottom: 4px; letter-spacing: -0.01em; }
        .popup-desc { color: #64748b; font-size: 0.85rem; margin-bottom: 12px; line-height: 1.4; }
        .popup-actions { display: flex; gap: 8px; }
        .popup-actions .btn { flex: 1; padding: 6px 10px; font-size: 0.8rem; border-radius: 8px; font-weight: 600; }

        /* Modal Custom Style */
        .modal-content { border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        .form-control, .form-select { border-radius: 10px; padding: 10px 14px; }
        .form-control:focus, .form-select:focus { box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15); border-color: #0d6efd; }

        /* Responsive Mobile Drawer */
        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column-reverse; }
            .sidebar-panel { width: 100%; height: 40vh; }
            .map-panel { height: 60vh; }
        }
    </style>
@endsection

@section('content')
<div class="dashboard-container">

    <div class="sidebar-panel">
        <div class="sidebar-header">
            <h4 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Eksplorasi Faskes</h4>
            <p class="text-muted small mb-3">Gunakan alat digitasi di peta untuk menambah titik baru.</p>

            <div class="input-group border rounded-3 p-1 bg-light">
                <span class="input-group-text bg-transparent border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="searchFaskes" class="form-control border-0 bg-transparent" placeholder="Cari rumah sakit atau klinik...">
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <span class="text-muted d-block small fw-medium">Total Terdata</span>
                    <span class="fs-4 fw-bold text-primary" id="totalFaskes">0</span>
                </div>
                <div class="stat-card">
                    <span class="text-muted d-block small fw-medium">Kota</span>
                    <span class="fs-6 fw-bold text-dark">Yogyakarta</span>
                </div>
            </div>
        </div>

        <div class="list-container" id="faskesList">
            <div class="text-center py-5 text-muted small" id="loadingList">
                <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                <p>Memuat data fasilitas kesehatan...</p>
            </div>
        </div>
    </div>

    <div class="map-panel">
        <div id="map"></div>
    </div>

</div>

{{-- Modal Form Input Point Modern --}}
<div class="modal fade" tabindex="-1" id="modalInputPoint" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header px-4 pt-4 border-0">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-plus text-primary"></i> Tambah Titik Faskes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('points.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Fasilitas Kesehatan</label>
                        <input type="text" class="form-control" name="name" required placeholder="Contoh: RSUD Kota Yogyakarta">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Fasilitas Kesehatan</label>
                        <select class="form-select" name="type" required>
                            <option value="" disabled selected>-- Pilih Jenis Faskes --</option>
                            <option value="rumah_sakit">Rumah Sakit</option>
                            <option value="puskesmas">Puskesmas</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi & Layanan</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Tulis rincian fasilitas atau info operasional..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Geometris (WKT)</label>
                        <input type="text" class="form-control bg-light" id="geometry_point" name="geometry_point" readonly style="font-family: monospace; font-size: 0.85rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto / Gambar Lokasi</label>
                        <input class="form-control" type="file" id="imageInput" name="image" accept="image/*"
                            onchange="document.getElementById('previewImg').src = window.URL.createObjectURL(this.files[0]); document.getElementById('previewWrapper').classList.remove('d-none')">
                    </div>
                    <div class="mb-2 d-none" id="previewWrapper">
                        <img src="" id="previewImg" class="w-100 rounded-3 border" style="max-height: 160px; object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer px-4 pb-4 border-0 gap-2">
                    <button type="button" class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster-src.js"></script>

    <script>
        // Inisialisasi Peta & Matikan Zoom bawaan agar UI bersih
        var map = L.map('map', { zoomControl: false }).setView([-7.8000, 110.3755], 14);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');

        /* Fitur Digitasi */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        var drawControl = new L.Control.Draw({
            draw: { position: 'topright', marker: true, polyline: false, polygon: false, circle: false, rectangle: false, circlemarker: false },
            edit: false
        });
        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var layer = e.layer;
            var objectGeometry = Terraformer.geojsonToWKT(layer.toGeoJSON().geometry);

            $('#geometry_point').val(objectGeometry);
            var modal = new bootstrap.Modal(document.getElementById('modalInputPoint'));
            modal.show();

            document.getElementById('modalInputPoint').addEventListener('hidden.bs.modal', function() {
                location.reload();
            });
            drawnItems.addLayer(layer);
        });

        /* Setup Marker & Cluster Groups */
        var markers = L.markerClusterGroup();
        var markerById = {};
        var allFeatures = [];

        // Request GeoJSON dan Render Objek ke dalam Peta
        $.getJSON("{{ route('geojson.points') }}", function(data) {
            $('#loadingList').remove();

            if(data && data.features) {
                allFeatures = data.features;
                $('#totalFaskes').text(allFeatures.length);
                renderSidebarList(allFeatures);
            }

            var geojsonLayer = L.geoJSON(data, {
                pointToLayer: function(feature, latlng) {
                    // Deteksi jenis data secara dinamis berdasarkan nama (atau kolom type jika ada)
                    var isPuskesmas = feature.properties.name.toLowerCase().includes('puskesmas') ||
                                      feature.properties.name.toLowerCase().includes('pkm');

                    // Membuat struktur divIcon dengan warna kelas CSS yang berbeda
                    var customIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: isPuskesmas
                            ? '<div class="marker-circle marker-pkm"><i class="fa-solid fa-house-medical"></i></div>'
                            : '<div class="marker-circle marker-rs"><i class="fa-solid fa-hospital-user"></i></div>',
                        iconSize: [44, 44],
                        iconAnchor: [22, 44],
                        popupAnchor: [0, -44]
                    });

                    return L.marker(latlng, { icon: customIcon });
                },
                onEachFeature: function(feature, layer) {
                    var routedelete = "{{ route('points.delete', ':id') }}".replace(':id', feature.properties.id);
                    var routeedit = "{{ route('points.edit', ':id') }}".replace(':id', feature.properties.id);
                    var imageSrc = feature.properties.image ? "{{ asset('storage/images') }}/" + feature.properties.image : 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=400&auto=format&fit=crop';

                    var popup_content = `
                        <div class="popup-card">
                            <img src="${imageSrc}" class="popup-image"/>
                            <div class="popup-body">
                                <div class="popup-title">${feature.properties.name}</div>
                                <div class="popup-desc">${feature.properties.description || 'Tidak ada deskripsi.'}</div>
                                <div class="popup-actions">
                                    <a href="${routeedit}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                    <form action="${routedelete}" method="post" style="flex:1" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger w-100"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>`;

                    layer.bindPopup(popup_content);
                    markerById[feature.properties.id] = layer;
                }
            });

            markers.addLayer(geojsonLayer);
            map.addLayer(markers);

            /* Fokus otomatis dari halaman Tabel Data */
            const urlParams = new URLSearchParams(window.location.search);
            const focusId = urlParams.get('focus');

            if (focusId && markerById[focusId]) {
                var targetMarker = markerById[focusId];
                markers.zoomToShowLayer(targetMarker, function() {
                    map.setView(targetMarker.getLatLng(), 17, { animate: true, duration: 1.2 });
                    setTimeout(function() {
                        targetMarker.openPopup();
                    }, 350);
                });
            }
        });

        // Fungsi menampilkan list faskes ke sidebar kiri
        function renderSidebarList(features) {
            var listHtml = '';
            if(features.length === 0) {
                $('#faskesList').html('<p class="text-center text-muted py-4 small">Fasilitas kesehatan tidak ditemukan.</p>');
                return;
            }

            features.forEach(function(f) {
                var img = f.properties.image ? "{{ asset('storage/images') }}/" + f.properties.image : 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=200&auto=format&fit=crop';
                listHtml += `
                    <div class="faskes-item-card" onclick="focusToMarker('${f.properties.id}')">
                        <img src="${img}" class="faskes-thumb">
                        <div style="flex:1; min-width:0;">
                            <h6 class="fw-bold text-dark text-truncate mb-1">${f.properties.name}</h6>
                            <p class="text-muted small mb-0 text-truncate-2" style="font-size:0.8rem; line-height:1.4;">${f.properties.description || 'Tidak ada deskripsi.'}</p>
                        </div>
                    </div>`;
            });
            $('#faskesList').html(listHtml);
        }

        // Fungsi interaksi klik list sidebar otomatis zoom ke marker peta
        window.focusToMarker = function(id) {
            var marker = markerById[id];
            if (marker) {
                markers.zoomToShowLayer(marker, function() {
                    map.setView(marker.getLatLng(), 17, { animate: true, duration: 0.8 });
                    setTimeout(function() { marker.openPopup(); }, 300);
                });
            }
        };

        // Pencarian Realtime lokal di sidebar
        $('#searchFaskes').on('input', function() {
            var val = $(this).val().toLowerCase();
            var filtered = allFeatures.filter(function(f) {
                return f.properties.name.toLowerCase().includes(val) ||
                       (f.properties.description && f.properties.description.toLowerCase().includes(val));
            });
            renderSidebarList(filtered);
        });

        var baseMaps = { "Peta Jalan": osm, "Satelit": satellite };
        L.control.layers(baseMaps, { "Fasilitas Kesehatan": markers }, { position: 'bottomleft' }).addTo(map);
    </script>
@endsection
