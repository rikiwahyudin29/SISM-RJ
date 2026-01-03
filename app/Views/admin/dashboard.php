<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Selamat Datang, <?= session()->get('nama_lengkap') ?? session()->get('username') ?? 'Admin'; ?>! 👋
        </h1>
        <p class="text-gray-600 dark:text-gray-400">Berikut adalah ringkasan data sistem SIAKAD hari ini.</p>
    </div>
    
    <div class="text-right hidden md:block">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
            <?= date('D, d M Y'); ?>
        </p>
        <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">
            Status: Administrator Utama
        </p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="p-4 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 col-span-1 md:col-span-2">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white">Info Hari Ini</h3>
            <span class="flex items-center text-sm font-medium text-green-500">
                <div class="flex items-center text-sm font-medium">
    <span id="signal-ping" class="flex w-2 h-2 bg-gray-400 rounded-full me-2"></span>
    <span id="signal-text" class="text-gray-500 dark:text-gray-400">Menghubungkan...</span>
</div>
            </span>
        </div>
        <div class="grid grid-cols-2 gap-4 border-t pt-4 dark:border-gray-700">
            <div>
                <p class="text-xs text-gray-500 uppercase">Petugas Piket</p>
                <p class="text-sm font-bold dark:text-gray-200">Bpk. Heru & Ibu Siti</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Total Kelas/Mapel</p>
                <p class="text-sm font-bold dark:text-gray-200">12 Kelas / 18 Mapel</p>
            </div>
        </div>
    </div>

    <div class="p-4 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <p class="text-xs text-gray-500 uppercase">Total Tunggakan</p>
        <p class="text-xl font-bold text-red-600">Rp 4.500.000</p>
        <p class="text-xs text-green-500 mt-1">↑ 2% dari bulan lalu</p>
    </div>
    
    <div class="p-4 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 text-center">
        <p class="text-xs text-gray-500 uppercase">Pelanggaran Siswa</p>
        <p class="text-3xl font-bold text-orange-500">3</p>
        <p class="text-xs text-gray-400 mt-1">Kasus minggu ini</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="p-4 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-lg font-bold mb-4 dark:text-white text-center">Persentase Kehadiran</h3>
        <div id="attendance-chart"></div>
    </div>

    <div class="p-4 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-lg font-bold mb-4 dark:text-white">Trend Keuangan Sekolah</h3>
        <div id="finance-chart"></div>
    </div>
</div>

<div class="bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-4">
    <h3 class="text-lg font-bold mb-4 dark:text-white">Log Aktivitas Terbaru</h3>
    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
        <?php foreach($logs as $log): ?>
        <li class="py-3 flex justify-between">
            <div class="flex gap-3">
                <span class="text-blue-500">●</span>
                <span class="dark:text-gray-300"><?= $log['act'] ?></span>
            </div>
            <span class="text-xs text-gray-500"><?= $log['time'] ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // 📊 Grafik Kehadiran (Radial)
    var optionsAttendance = {
        series: [95, 88], // Siswa, Guru
        chart: { height: 280, type: 'radialBar' },
        plotOptions: { radialBar: { dataLabels: { name: { fontSize: '22px' }, value: { fontSize: '16px' }, total: { show: true, label: 'Rata-rata', formatter: function() { return '91%' } } } } },
        labels: ['Siswa', 'Guru'],
        colors: ['#1C64F2', '#10B981'],
    };
    new ApexCharts(document.querySelector("#attendance-chart"), optionsAttendance).render();

    // 📈 Grafik Keuangan (Area)
    var optionsFinance = {
        series: [{ name: 'Pemasukan', data: [31, 40, 28, 51, 42, 109, 100] }, { name: 'Pengeluaran', data: [11, 32, 45, 32, 34, 52, 41] }],
        chart: { height: 250, type: 'area', toolbar: {show: false} },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        xaxis: { categories: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"] },
        colors: ['#10B981', '#EF4444'],
    };
    new ApexCharts(document.querySelector("#finance-chart"), optionsFinance).render();
</script>
<script>
    function checkSignal() {
        const start = Date.now();
        const signalPing = document.getElementById('signal-ping');
        const signalText = document.getElementById('signal-text');

        // Melakukan fetch ringan ke endpoint favicon atau halaman yang sama
        fetch('<?= base_url('favicon.ico') ?>', { mode: 'no-cors', cache: 'no-cache' })
            .then(() => {
                const latency = Date.now() - start;
                
                // Update teks Latency
                signalText.innerText = `Sistem Online (${latency}ms)`;

                // Update warna indikator berdasarkan latency
                if (latency < 150) {
                    signalPing.className = "flex w-2 h-2 bg-green-500 rounded-full me-2 animate-pulse";
                    signalText.className = "text-green-500 font-medium";
                } else if (latency < 400) {
                    signalPing.className = "flex w-2 h-2 bg-yellow-400 rounded-full me-2";
                    signalText.className = "text-yellow-500 font-medium";
                } else {
                    signalPing.className = "flex w-2 h-2 bg-red-500 rounded-full me-2";
                    signalText.className = "text-red-500 font-medium";
                }
            })
            .catch(() => {
                signalPing.className = "flex w-2 h-2 bg-red-600 rounded-full me-2";
                signalText.innerText = "Offline / Gangguan";
                signalText.className = "text-red-600 font-bold";
            });
    }

    // Jalankan setiap 5 detik untuk monitoring real-time
    setInterval(checkSignal, 5000);
    checkSignal(); // Jalankan sekali saat load
</script>

<?= $this->endSection() ?>