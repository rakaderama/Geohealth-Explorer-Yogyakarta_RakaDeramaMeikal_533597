<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoHealth Explorer Yogyakarta</title>

    {{-- Google Fonts - Plus Jakarta Sans & Inter untuk Tema Kesehatan Modern --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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
        :root {
            --header-height: 240px;
        }

        body {
            /* Menggunakan Inter sebagai base body font, memberikan kesan bersih khas platform medis */
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #0f172a;
            letter-spacing: -0.01em; /* Mengoptimalkan keterbacaan teks halus */
        }

        /* Menggunakan Plus Jakarta Sans untuk elemen-elemen judul/heading agar lebih menarik */
        h1, h2, h3, h4, h5, h6, .app-title, .navbar-nav .nav-link, .widget-time, .stat-value {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* HEADER / HERO */
        .app-header {
            position: relative;
            width: 100%;
            min-height: var(--header-height);
            background-image: url('https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            padding: 40px 0;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .app-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.75) 0%, rgba(108, 99, 255, 0.85) 100%);
            z-index: 1;
        }

        .header-container {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .app-title {
            font-size: 2.5rem;
            font-weight: 800; /* Extra bold untuk menonjolkan nama aplikasi */
            line-height: 1.2;
            color: #ffffff;
            letter-spacing: -0.02em;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .brand-sub {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.95);
            margin-top: 8px;
            font-weight: 400;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
        }

        /* DIGITAL CLOCK COMPONENT */
        .header-widget {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            padding: 14px 20px;
            color: #ffffff;
            display: inline-block;
            text-align: right;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .widget-time {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: 0.5px;
        }

        .widget-date {
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 6px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* NAVBAR */
        .navbar {
            padding: .7rem 2rem;
            border-radius: 0px;
            background: rgba(255, 255, 255, 0.95);
            margin-top: 0px;
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(2, 6, 23, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-left: none;
            border-right: none;
            width: 100%;
        }

        .navbar-nav .nav-link {
            font-size: 15px;
            font-weight: 600;
            padding: 8px 16px !important;
            color: #475569 !important;
            transition: all .2s ease;
            letter-spacing: -0.01em;
        }

        .navbar-nav .nav-link:hover {
            color: #0d6efd !important;
            background-color: #f1f5f9;
            border-radius: 8px;
        }

        .navbar-nav .nav-link.active {
            color: #0d6efd !important;
            background-color: #edf4ff;
            border-radius: 8px;
        }

        .btn-logout {
            font-size: 14px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .app-title { font-size: 1.9rem; }
            .brand-sub { font-size: 0.95rem; }
            :root { var(--header-height): 180px; }
            .navbar { padding: .7rem 1rem; }
            .header-widget { text-align: left; margin-top: 15px; width: 100%; }
        }

        /* Search Bar Controls Styling */
        .custom-search { background: #fff; padding: 4px; border-radius: 8px; min-width: 280px; }
        .custom-search .search-input { width: 100%; padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; font-size: 0.9rem; }
        .custom-search .search-input:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15); }
        .custom-search .search-suggestions { position: absolute; top: 48px; left: 0; right: 0; background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 12px 30px rgba(15,23,42,0.1); max-height: 280px; overflow-y: auto; z-index: 1050; display: none; border-radius: 8px; }
        .custom-search .suggestion-item { padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; color: #334155; }
        .custom-search .suggestion-item:hover, .custom-search .suggestion-item.active { background: #edf4ff; color: #0d6efd; }
    </style>

    @yield('styles')
</head>

<body>

    <header class="app-header">
        <div class="container-fluid px-5 header-container">
            <div class="row align-items-center">
                <div class="col-md-8 col-12">
                    <div class="app-title">
                        @yield('page_title', 'GeoHealth Explorer Yogyakarta')
                    </div>
                    <div class="brand-sub">
                        @yield('page_subtitle', 'Peta interaktif persebaran fasilitas kesehatan di Kota Yogyakarta')
                    </div>
                </div>

                <div class="col-md-4 col-12 text-md-end mt-3 mt-md-0">
                    <div class="header-widget">
                        <div class="widget-time" id="live-clock">00:00:00</div>
                        <div class="widget-date" id="live-date">Loading date...</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @include('components.navbar')

    <main class="app-main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

    <script>
        function updateClock() {
            const now = new Date();

            // Format Jam (HH:MM:SS)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds}`;

            // Format Tanggal Terjemahan Indonesia
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', options);
            document.getElementById('live-date').textContent = dateString;
        }

        // Jalankan fungsi setiap detik sekali
        setInterval(updateClock, 1000);
        window.addEventListener('DOMContentLoaded', updateClock);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var inputEl = document.getElementById('point-search');
            var sugEl = document.getElementById('search-suggestions');
            if(!inputEl || !sugEl) return;

            var pointsList = [];
            fetch("{{ route('geojson.points') }}").then(function(r){ if(!r.ok) return []; return r.json(); }).then(function(data){
                if(data && data.features){
                    data.features.forEach(function(f){ if(f && f.properties){ pointsList.push({ id: f.properties.id || null, name: f.properties.name || '' }); } });
                    pointsList.sort(function(a,b){ return a.name.localeCompare(b.name); });
                }
            }).catch(function(err){ console.error('Failed loading points for search', err); });

            function escapeHtml(str){ if(!str) return ''; return String(str).replace(/[&<>\"]/g, function(s){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'})[s]; }); }
            var selectedIndex = -1;
            function clearSuggestions(){ sugEl.style.display = 'none'; sugEl.innerHTML = ''; selectedIndex = -1; }
            function renderSuggestions(items){ if(!items || !items.length){ clearSuggestions(); return; } sugEl.innerHTML = items.map(function(it, idx){ return '<div class="suggestion-item" data-id="'+it.id+'" data-index="'+idx+'">'+escapeHtml(it.name)+'</div>'; }).join(''); sugEl.style.display = 'block'; selectedIndex = -1; }
            function debounce(fn, delay){ var t; return function(){ var args = arguments; clearTimeout(t); t = setTimeout(function(){ fn.apply(null, args); }, delay); }; }

            var doSearch = debounce(function(){ var q = inputEl.value.trim().toLowerCase(); if(!q){ clearSuggestions(); return; } var results = pointsList.filter(function(p){ return p.name && p.name.toLowerCase().indexOf(q) !== -1; }); results.sort(function(a,b){ return a.name.localeCompare(b.name); }); results = results.slice(0,12); renderSuggestions(results); }, 120);

            inputEl.addEventListener('input', doSearch);
            sugEl.addEventListener('click', function(e){ var it = e.target.closest('.suggestion-item'); if(!it) return; var id = it.getAttribute('data-id'); if(id){ window.location = '{{ route('peta') }}?focus=' + encodeURIComponent(id); } });

            inputEl.addEventListener('keydown', function(e){ var items = sugEl.querySelectorAll('.suggestion-item'); if(e.key === 'ArrowDown'){ e.preventDefault(); if(selectedIndex < items.length - 1) selectedIndex++; else selectedIndex = 0; items.forEach(function(it,i){ it.classList.toggle('active', i===selectedIndex); }); } else if(e.key === 'ArrowUp'){ e.preventDefault(); if(selectedIndex > 0) selectedIndex--; else selectedIndex = items.length - 1; items.forEach(function(it,i){ it.classList.toggle('active', i===selectedIndex); }); } else if(e.key === 'Enter'){ e.preventDefault(); if(selectedIndex >= 0 && items[selectedIndex]){ var id = items[selectedIndex].getAttribute('data-id'); if(id) window.location = '{{ route('peta') }}?focus=' + encodeURIComponent(id); } else { var first = sugEl.querySelector('.suggestion-item'); if(first){ var id = first.getAttribute('data-id'); if(id) window.location = '{{ route('peta') }}?focus=' + encodeURIComponent(id); } } } else if(e.key === 'Escape'){ clearSuggestions(); } });
            document.addEventListener('click', function(ev){ if(!ev.target.closest('.custom-search')) clearSuggestions(); });
        });
    </script>

    @yield('scripts')
    @include('components.toast')

</body>
</html>
