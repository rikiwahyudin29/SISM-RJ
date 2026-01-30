<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="container mx-auto px-4 py-6 max-w-lg">
    <h1 class="text-xl font-black text-slate-800 mb-1">Absensi Digital 📸</h1>
    <p class="text-sm text-slate-500 mb-4">Pastikan GPS aktif & Izinkan Kamera.</p>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-rose-100 text-rose-700 p-3 rounded-xl mb-4 text-sm font-bold border border-rose-200">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if($sudah_absen): ?>
        <div class="bg-emerald-100 text-emerald-800 p-6 rounded-2xl text-center border border-emerald-200">
            <h2 class="text-2xl font-bold mb-2">✅ Sudah Absen</h2>
            <p>Anda sudah melakukan absensi hari ini pada jam <b><?= $sudah_absen->jam_masuk ?></b></p>
            <a href="<?= base_url('siswa/presensi') ?>" class="inline-block mt-4 px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold">Lihat Riwayat</a>
        </div>
    <?php else: ?>

        <div class="bg-black rounded-2xl overflow-hidden shadow-lg mb-4 relative">
            <video id="video" autoplay playsinline class="w-full h-64 object-cover transform scale-x-[-1]"></video>
            <canvas id="canvas" class="hidden"></canvas>
            <div id="loading-cam" class="absolute inset-0 flex items-center justify-center text-white text-xs">
                Memuat Kamera...
            </div>
        </div>

        <div class="bg-white p-1 rounded-xl shadow-sm border border-slate-200 mb-4">
            <div id="map" class="w-full h-40 rounded-lg z-0"></div>
        </div>

        <div class="text-center mb-6">
            <div class="inline-block px-4 py-2 rounded-full bg-slate-100 border border-slate-200">
                <p class="text-xs text-slate-500">Jarak ke Sekolah</p>
                <p id="info-jarak" class="text-xl font-black text-slate-800">Mencari...</p>
            </div>
        </div>

        <form id="form-absen" action="<?= base_url('siswa/presensi/submit_absen') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="long">
            <input type="hidden" name="foto_selfie" id="foto_selfie">

            <button type="button" onclick="ambilFotoDanAbsen()" id="btn-absen" disabled class="w-full py-4 bg-slate-300 text-slate-500 rounded-2xl font-black text-lg shadow-sm transition-all cursor-not-allowed">
                Tunggu GPS & Kamera...
            </button>
        </form>

    <?php endif; ?>
</div>

<?php if(!$sudah_absen): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const latSekolah  = <?= $lokasi->latitude ?>;
    const longSekolah = <?= $lokasi->longitude ?>;
    const radiusIzin  = <?= $lokasi->radius ?>; 

    var map = L.map('map').setView([latSekolah, longSekolah], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

    L.circle([latSekolah, longSekolah], {
        color: 'green', fillColor: '#2ecc71', fillOpacity: 0.2, radius: radiusIzin
    }).addTo(map);
    
    L.marker([latSekolah, longSekolah]).addTo(map).bindPopup("Lokasi Sekolah");
    var userMarker = L.marker([0,0]); 

    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false });
            video.srcObject = stream;
            document.getElementById('loading-cam').classList.add('hidden');
        } catch (err) {
            alert("Gagal akses kamera: " + err);
        }
    }
    startCamera();

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(updatePosisi, errorPosisi, { enableHighAccuracy: true });
    } else {
        alert("Browser tidak mendukung GPS.");
    }

    function updatePosisi(position) {
        const latUser = position.coords.latitude;
        const longUser = position.coords.longitude;

        document.getElementById('lat').value = latUser;
        document.getElementById('long').value = longUser;

        userMarker.setLatLng([latUser, longUser]).addTo(map);

        const jarak = hitungJarakJS(latSekolah, longSekolah, latUser, longUser);
        document.getElementById('info-jarak').innerText = jarak + " Meter";

        const btn = document.getElementById('btn-absen');
        if (jarak <= radiusIzin) {
            btn.disabled = false;
            btn.classList.remove('bg-slate-300', 'text-slate-500', 'cursor-not-allowed');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
            btn.innerHTML = "📸 AMBIL FOTO & ABSEN";
        } else {
            btn.disabled = true;
            btn.classList.add('bg-slate-300', 'text-slate-500', 'cursor-not-allowed');
            btn.innerHTML = "⛔ DILUAR JANGKAUAN";
        }
    }

    function errorPosisi(err) {
        document.getElementById('info-jarak').innerText = "GPS Error";
    }

    function ambilFotoDanAbsen() {
        const btn = document.getElementById('btn-absen');
        btn.innerHTML = "Mengirim...";
        btn.disabled = true;

        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const fotoBase64 = canvas.toDataURL('image/jpeg', 0.8);
        document.getElementById('foto_selfie').value = fotoBase64;

        document.getElementById('form-absen').submit();
    }

    function hitungJarakJS(lat1, lon1, lat2, lon2) {
        var R = 6371e3; 
        var φ1 = lat1 * Math.PI/180;
        var φ2 = lat2 * Math.PI/180;
        var Δφ = (lat2-lat1) * Math.PI/180;
        var Δλ = (lon2-lon1) * Math.PI/180;
        var a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c);
    }
</script>
<?php endif; ?>
<?= $this->endSection() ?>