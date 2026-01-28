<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
    /* PAKSA UKURAN PETA */
    #map { 
        height: 400px !important; 
        width: 100% !important; 
        border-radius: 12px; 
        z-index: 1;
        background: #eee; /* Warna background kalau peta belum loading */
    }

    /* --- ANTI-PECAH LEVEL MAX --- */
    /* Kita paksa semua gambar di dalam widget leaflet untuk reset ukurannya */
    .leaflet-pane img, 
    .leaflet-tile, 
    .leaflet-marker-icon, 
    .leaflet-marker-shadow,
    .leaflet-image-layer {
        max-width: none !important;
        max-height: none !important;
        width: auto !important;
        height: auto !important;
        padding: 0 !important;
        margin: 0 !important;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Setting Presensi</h1>
            <p class="text-sm text-slate-500">Atur jadwal jam kerja dan lokasi validasi (Geofencing).</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-6 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-sm flex items-center gap-2">
            ✅ <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/jam-presensi/update') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 h-fit">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    ⏱️ Aturan Jam
                </h3>
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jam Buka Scan Masuk</label>
                        <input type="time" name="jam_masuk_mulai" value="<?= $jam['jam_masuk_mulai'] ?>" class="w-full px-4 py-2 border rounded-lg font-bold bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-rose-500 uppercase mb-2">Batas Terlambat</label>
                        <input type="time" name="jam_masuk_akhir" value="<?= $jam['jam_masuk_akhir'] ?>" class="w-full px-4 py-2 border border-rose-200 rounded-lg font-bold text-rose-600 bg-rose-50 dark:bg-rose-900/10 focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-blue-600 uppercase mb-2">Jam Buka Scan Pulang</label>
                        <input type="time" name="jam_pulang_mulai" value="<?= $jam['jam_pulang_mulai'] ?>" class="w-full px-4 py-2 border border-blue-200 rounded-lg font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/10 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 h-fit">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    📍 Lokasi Sekolah (Radius)
                </h3>
                
                <div id="map" class="mb-4 border border-slate-300"></div>
                
                <div class="text-xs text-slate-500 mb-4 text-center italic">
                    *Geser pin atau klik pada peta untuk menentukan titik pusat sekolah.
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Latitude</label>
                        <input type="text" id="latitude" name="latitude" value="<?= $jam['latitude'] ?>" class="w-full px-4 py-2 border rounded-lg font-mono text-sm bg-slate-50" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Longitude</label>
                        <input type="text" id="longitude" name="longitude" value="<?= $jam['longitude'] ?>" class="w-full px-4 py-2 border rounded-lg font-mono text-sm bg-slate-50" readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-2">Jarak Toleransi (Meter)</label>
                    <div class="relative">
                        <input type="number" id="radius" name="radius" value="<?= $jam['radius'] ?>" class="w-full px-4 py-2 border border-emerald-200 rounded-lg font-bold text-emerald-600 focus:ring-2 focus:ring-emerald-500" oninput="updateRadius()">
                        <span class="absolute right-4 top-2.5 text-sm text-emerald-600 font-bold">Meter</span>
                    </div>
                </div>

                <button type="button" onclick="getLocation()" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg mb-4 transition-colors border border-slate-300">
                    📍 Deteksi Lokasi Saya Saat Ini
                </button>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
    // Ambil Data dari PHP
    var curLat = '<?= $jam['latitude'] ?: -6.200000 ?>';
    var curLng = '<?= $jam['longitude'] ?: 106.816666 ?>';
    var curRad = '<?= $jam['radius'] ?: 100 ?>';

    // Inisialisasi Peta
    var map = L.map('map').setView([curLat, curLng], 18);

    // Tambahkan Tile Layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Marker & Circle
    var marker = L.marker([curLat, curLng], {draggable: true}).addTo(map);
    var circle = L.circle([curLat, curLng], {
        color: '#10b981',
        fillColor: '#34d399',
        fillOpacity: 0.3,
        radius: curRad
    }).addTo(map);

    // Event Listeners
    marker.on('dragend', function(e) {
        var pos = marker.getLatLng();
        updateInputs(pos.lat, pos.lng);
        map.panTo(pos);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng.lat, e.latlng.lng);
        map.panTo(e.latlng);
    });

    function updateRadius() {
        var newRad = document.getElementById('radius').value;
        circle.setRadius(newRad);
    }

    function updateInputs(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        circle.setLatLng([lat, lng]);
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 18);
                updateInputs(lat, lng);
                alert("Lokasi ditemukan!");
            }, function() {
                alert("Gagal ambil lokasi. Cek izin GPS.");
            });
        } else {
            alert("Browser tidak support GPS.");
        }
    }

    // FIX TAMBAHAN: Refresh Peta setelah loading agar tile tidak abu-abu/berantakan
    setTimeout(function(){ map.invalidateSize(); }, 500);
</script>

<?= $this->endSection() ?>