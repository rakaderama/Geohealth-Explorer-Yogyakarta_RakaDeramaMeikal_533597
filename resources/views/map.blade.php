@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet Draw CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <!-- MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Map height = full screen - navbar */
        #map {
            height: calc(100vh - 60px);
            width: 100%;
        }
        /* Popup card styles */
        .popup-card { display: flex; gap: 12px; align-items: flex-start; }
        .popup-image { width: 120px; height: 84px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
        .popup-body { flex: 1; min-width: 0; }
        .popup-title { font-weight: 700; font-size: 1rem; margin-bottom: 4px; }
        .popup-desc { color: #6c757d; font-size: 0.875rem; margin-bottom: 6px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }
        .popup-meta { font-size: 0.78rem; color: #868e96; }
        .popup-actions { margin-top: 8px; display: flex; gap: 8px; }
        .leaflet-popup-content .btn { font-size: .78rem; padding: .25rem .5rem; }
        /* High-visibility marker styles */
        .marker-circle { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(180deg,#ff7a59 0%,#ff4d4f 100%); color: #fff; display:flex; align-items:center; justify-content:center; font-size:18px; box-shadow: 0 6px 14px rgba(0,0,0,0.28); border: 3px solid rgba(255,255,255,0.85); }
        .marker-circle i { line-height: 1; }
        /* Search control styles */
        .custom-search { background: #fff; padding: 6px; border-radius: 8px; min-width: 280px; }
        .custom-search .search-input { width: 100%; padding: 6px 8px; border: 1px solid #e6e9ef; border-radius: 6px; outline: none; }
        .custom-search .search-suggestions { position: absolute; top: 46px; left: 6px; right: 6px; background: #fff; border: 1px solid #e6e9ef; box-shadow: 0 6px 18px rgba(16,24,40,0.08); max-height: 300px; overflow-y: auto; z-index: 1000; display: none; border-radius: 6px; }
        .custom-search .suggestion-item { padding: 8px 10px; cursor: pointer; border-bottom: 1px solid #f3f4f6; }
        .custom-search .suggestion-item:hover, .custom-search .suggestion-item.active { background: #f1f7ff; }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    {{-- Modal form Input Point --}}
    <div class="modal" tabindex="-1" id="modalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill name here...">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_point" name="geometry_point" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])"
                                rows="3">

                        </div>
                        <div class="mb-3">
                            <img src="" alt="" id="preview-image" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Draw JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <!-- Terraformer -->
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster-src.js"></script>

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-7.8000, 110.3755], 14);

        // Basemap OSM
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                marker: true,
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            console.log(drawnJSONObject);
            console.log(objectGeometry);


            if (type === 'marker') {
                // set value geometry to geometry_input textarea
                $('#geometry_point').val(objectGeometry);

                // show modal input point (Bootstrap 5)
                var modalEl = document.getElementById('modalInputPoint');
                var modal = new bootstrap.Modal(modalEl);
                modal.show();

                // modal dismiss reload page
                modalEl.addEventListener('hidden.bs.modal', function() {
                    location.reload();
                });
            } else {
                console.log('undefined');
            }

            drawnItems.addLayer(layer);
        });

        // Layer control contoh
        var satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
        );

        var baseMaps = {
            "OpenStreetMap": osm,
            "Satellite": satellite
        };

        // Marker cluster group
        var markers = L.markerClusterGroup();
        // map of point id -> marker layer for quick lookup
        var markerById = {};

        // custom hospital DivIcon (high visibility)
        var hospitalDivIcon = L.divIcon({
            className: 'custom-div-icon',
            html: '<div class="marker-circle"><i class="fa-solid fa-hospital"></i></div>',
            iconSize: [44, 44],
            iconAnchor: [22, 44],
            popupAnchor: [0, -44]
        });

        $.getJSON("{{ route('geojson.points') }}", function(data) {
            var geojsonLayer = L.geoJSON(data, {
                pointToLayer: function(feature, latlng) {
                    return L.marker(latlng, { icon: hospitalDivIcon });
                },
                onEachFeature: function(feature, layer) {
                    var routedelete = "{{ route('points.delete', ':id') }}";
                    routedelete = routedelete.replace(':id', feature.properties.id);

                    var routeedit = "{{ route('points.edit', ':id') }}";
                    routeedit = routeedit.replace(':id', feature.properties.id);

                    var imageSrc = feature.properties.image ? "{{ asset('storage/images') }}/" + feature.properties.image : 'https://via.placeholder.com/320x180?text=No+Image';

                    var popup_content = '' +
                        '<div class="popup-card" style="max-width:360px">' +
                            '<img src="' + imageSrc + '" alt="' + feature.properties.name + '" class="popup-image"/>' +
                            '<div class="popup-body">' +
                                '<div class="popup-title">' + feature.properties.name + '</div>' +
                                '<div class="popup-desc">' + (feature.properties.description || '') + '</div>' +
                                '<div class="popup-meta">Dibuat: ' + (feature.properties.created_at || '') + '</div>' +
                                '<div class="popup-actions">' +
                                    '<a href="' + routeedit + '" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen-to-square me-1"></i> Edit</a>' +
                                    '<form action="' + routedelete + '" method="post" style="display:inline">' +
                                        '@csrf' +
                                        '@method('delete')' +
                                        '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data point ini?\')"><i class="fa-solid fa-trash-can me-1"></i> Hapus</button>' +
                                    '</form>' +
                                '</div>' +
                            '</div>' +
                        '</div>';

                    layer.bindPopup(popup_content, { maxWidth: 360 });
                    // store marker reference by its id
                    if (feature && feature.properties && feature.properties.id) {
                        markerById[feature.properties.id] = layer;
                    }
                }
            });

            markers.addLayer(geojsonLayer);
            map.addLayer(markers);
            // Build a lightweight index of points for search (id, name, latlng)
            var pointsList = [];
            if (data && data.features && data.features.length) {
                data.features.forEach(function(f) {
                    var id = f.properties && f.properties.id ? f.properties.id : null;
                    var name = f.properties && f.properties.name ? f.properties.name : '';
                    var latlng = null;
                    if (f.geometry && Array.isArray(f.geometry.coordinates)) {
                        latlng = L.latLng(f.geometry.coordinates[1], f.geometry.coordinates[0]);
                    }
                    pointsList.push({ id: id, name: name, latlng: latlng });
                });
                pointsList.sort(function(a,b){ return a.name.localeCompare(b.name); });
            }

            // If URL contains ?focus=<id>, zoom to that marker and open its popup
            (function() {
                try {
                    var params = new URLSearchParams(window.location.search);
                    var focusId = params.get('focus');
                    if (focusId) {
                        var m = markerById[focusId];
                        if (m) {
                            markers.zoomToShowLayer(m, function() {
                                map.setView(m.getLatLng(), 18);
                                var opened = false;
                                function openPopupOnce() {
                                    if (opened) return;
                                    opened = true;
                                    m.openPopup();
                                    // remove focus param so reload/back doesn't re-trigger focus
                                    params.delete('focus');
                                    var newSearch = params.toString();
                                    var newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '');
                                    history.replaceState(null, '', newUrl);
                                }
                                map.once('moveend', openPopupOnce);
                                setTimeout(openPopupOnce, 500);
                            });
                        } else {
                            // focus id not found in markers; remove param anyway
                            params.delete('focus');
                            var newSearch = params.toString();
                            var newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '');
                            history.replaceState(null, '', newUrl);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing focus param', e);
                }
            })();

            // Search UI lives in the navbar now (input#point-search and #search-suggestions)

            // Helpers for suggestions
            function escapeHtml(str){ if(!str) return ''; return String(str).replace(/[&<>"]/, function(s){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'})[s]; }); }

            var inputEl = document.getElementById('point-search');
            var sugEl = document.getElementById('search-suggestions');
            var selectedIndex = -1;

            function clearSuggestions(){ sugEl.style.display = 'none'; sugEl.innerHTML = ''; selectedIndex = -1; }

            function renderSuggestions(items){
                if(!items || !items.length){ clearSuggestions(); return; }
                sugEl.innerHTML = items.map(function(it, idx){
                    return '<div class="suggestion-item" data-id="'+it.id+'" data-index="'+idx+'">'+escapeHtml(it.name)+'</div>';
                }).join('');
                sugEl.style.display = 'block';
                selectedIndex = -1;
            }

            function focusPointById(id){
                if(!id) return;
                var m = markerById[id];
                if(m){
                    markers.zoomToShowLayer(m, function(){
                        map.setView(m.getLatLng(), 18);
                        var opened = false;
                        function openPopupOnce() {
                            if (opened) return;
                            opened = true;
                            m.openPopup();
                        }
                        map.once('moveend', openPopupOnce);
                        setTimeout(openPopupOnce, 500);
                    });
                    return;
                }
                // fallback: pan to latlng from index
                var p = pointsList.find(function(x){ return String(x.id) === String(id); });
                if(p && p.latlng){ map.setView(p.latlng, 18); }
            }

            function debounce(fn, delay){ var t; return function(){ var args = arguments; clearTimeout(t); t = setTimeout(function(){ fn.apply(null, args); }, delay); }; }

            var doSearch = debounce(function(){
                var q = inputEl.value.trim().toLowerCase();
                if(!q){ clearSuggestions(); return; }
                var results = pointsList.filter(function(p){ return p.name && p.name.toLowerCase().indexOf(q) !== -1; });
                results.sort(function(a,b){ return a.name.localeCompare(b.name); });
                results = results.slice(0, 12);
                renderSuggestions(results);
            }, 120);

            inputEl.addEventListener('input', doSearch);

            // click on suggestion
            sugEl.addEventListener('click', function(e){
                var it = e.target.closest('.suggestion-item');
                if(!it) return;
                var id = it.getAttribute('data-id');
                focusPointById(id);
                clearSuggestions();
                inputEl.value = it.textContent;
            });

            // keyboard navigation
            inputEl.addEventListener('keydown', function(e){
                var items = sugEl.querySelectorAll('.suggestion-item');
                if(e.key === 'ArrowDown'){
                    e.preventDefault(); if(selectedIndex < items.length - 1) selectedIndex++; else selectedIndex = 0;
                    items.forEach(function(it,i){ it.classList.toggle('active', i===selectedIndex); });
                } else if(e.key === 'ArrowUp'){
                    e.preventDefault(); if(selectedIndex > 0) selectedIndex--; else selectedIndex = items.length - 1;
                    items.forEach(function(it,i){ it.classList.toggle('active', i===selectedIndex); });
                } else if(e.key === 'Enter'){
                    e.preventDefault(); if(selectedIndex >= 0 && items[selectedIndex]){ focusPointById(items[selectedIndex].getAttribute('data-id')); clearSuggestions(); }
                    else { var first = sugEl.querySelector('.suggestion-item'); if(first){ focusPointById(first.getAttribute('data-id')); clearSuggestions(); } }
                } else if(e.key === 'Escape'){
                    clearSuggestions();
                }
            });

            // close when clicking outside
            document.addEventListener('click', function(ev){ if(!ev.target.closest('.custom-search')) clearSuggestions(); });
        });


        var overlayMaps = {
            "Rumah Sakit": markers
        };

        var controllayer = L.control.layers(baseMaps, overlayMaps);
        controllayer.addTo(map);
    </script>
@endsection
