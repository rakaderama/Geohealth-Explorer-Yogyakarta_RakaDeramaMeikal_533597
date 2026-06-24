@extends('layouts.template')

@section('page_title', 'Edit Data Titik')
@section('page_subtitle', 'Ubah informasi dan geometri titik fasilitas kesehatan')
@section('hero_image')
    <img src="https://source.unsplash.com/600x400/?clinic,doctors,medical" alt="Edit point hero">
@endsection

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet Draw CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Map height = full screen - header (uses CSS var defined in layout) */
        #map {
            height: calc(100vh - var(--header-height));
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    {{-- Modal form Edit --}}
    <div class="modal" tabindex="-1" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.update', $id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
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
                            <label for="geometry" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry" name="geometry" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])"
                                rows="3">

                        </div>
                        <div class="mb-3">
                            <img src="" alt="" id="preview-image" class="img-thumbnail" width="400">
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

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-7.7956, 110.3695], 12);

        // Basemap OSM
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });
        map.addControl(drawControl);

        map.on('draw:edited', function(e) {
            var layers = e.layers;

            layers.eachLayer(function(layer) {
                var drawnJSONObject = layer.toGeoJSON();
                console.log(drawnJSONObject);

                var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
                console.log(objectGeometry);

                // layer properties
                var properties = drawnJSONObject.properties;
                console.log(properties);

                drawnItems.addLayer(layer);

                //mengisi form edit denga data dari layer yang diedit
                $('#name').val(properties.name);
                $('#description').val(properties.description);
                $('#geometry').val(objectGeometry);
                $('#preview-image').attr('src', "{{ asset('storage/images') }}/" + properties.image);


                //menampilkan modal edit (Bootstrap 5)
                var modalEl = document.getElementById('modalEdit');
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            });
        });

        // Layer control contoh
        var satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
        );

        var baseMaps = {
            "OpenStreetMap": osm,
            "Satellite": satellite
        };

        // GeoJSON Point
        var points = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {

                //memasukkan layer ke dalam drawnItems agar bisa diedit
                drawnItems.addLayer(layer);

                var properties = feature.properties;
                var objectGeometry = Terraformer.geojsonToWKT(feature.geometry);


                layer.on(
                    'click',
                    function(e) {
                        //mengisi form edit denga data dari layer yang diedit
                        $('#name').val(properties.name);
                        $('#description').val(properties.description);
                        $('#geometry').val(objectGeometry);
                        $('#preview-image').attr('src', "{{ asset('storage/images') }}/" + properties
                        .image);


                        //menampilkan modal edit (Bootstrap 5)
                        var modalEl2 = document.getElementById('modalEdit');
                        var modal2 = new bootstrap.Modal(modalEl2);
                        modal2.show();
                    }
                );
            }
        });

        // Ambil satu data GeoJSON point yang diedit dari server dan tambahkan ke layer
        $.getJSON("{{ route('geojson.point', $id) }}", function(data) {
            points.addData(data);
            map.addLayer(points);
        });
    </script>
@endsection
