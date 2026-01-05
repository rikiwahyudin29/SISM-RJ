<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">
    <?php if(session()->getFlashdata('error')): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-900/50" role="alert">
            <span class="font-black">Akses Ditolak:</span> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700 transition-all duration-300">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <?php 
                        $fotoSession = session()->get('foto');
                        $pathFoto = 'uploads/guru/' . $fotoSession;
                        if (!empty($fotoSession) && file_exists(FCPATH . $pathFoto)): 
                    ?>
                        <img class="w-16 h-16 rounded-full border-2 border-blue-500 p-0.5 object-cover shadow-sm" 
                             src="<?= base_url($pathFoto) ?>" alt="Foto Profil">
                    <?php else: ?>
                        <img class="w-16 h-16 rounded-full border-2 border-blue-500 p-0.5 object-cover shadow-sm" 
                             src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama_lengkap')) ?>&background=0D8ABC&color=fff" alt="Avatar Default">
                    <?php endif; ?>
                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full" title="Sistem Aktif"></div>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white uppercase italic">
                        Halo, <?= session()->get('nama_lengkap') ?>! 👋
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold tracking-widest uppercase">
                        Master Control Panel <span class="text-blue-600 dark:text-blue-400">SIAKAD SISM-RJ</span>
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="bg-red-100 text-red-800 text-[10px] font-black px-3 py-1 rounded-full dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 uppercase tracking-widest">
                    <?= strtoupper(session()->get('role_active')) ?>
                </span>
                <span class="bg-blue-100 text-blue-800 text-[10px] font-black px-3 py-1 rounded-full dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-red-800 uppercase tracking-widest">
                    SUPER USER
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        <div class="p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-sm text-center relative overflow-hidden group">
            <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-blue-500/10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1 relative z-10">Guru/Staff</p>
            <h4 class="text-xl font-black text-blue-600 relative z-10"><?= $total_guru ?? 0 ?></h4>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-sm text-center relative overflow-hidden group">
            <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-green-500/10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1 relative z-10">Siswa</p>
            <h4 class="text-xl font-black text-green-600 relative z-10"><?= $total_siswa ?? 0 ?></h4>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-sm text-center relative overflow-hidden group">
            <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-purple-500/10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1 relative z-10">Kelas</p>
            <h4 class="text-xl font-black text-purple-600 relative z-10"><?= $total_kelas ?? 0 ?></h4>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-sm text-center relative overflow-hidden group">
            <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-orange-500/10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.993 7.993 0 002 12a8 8 0 008 8 8 8 0 008-8 7.993 7.993 0 00-7-7.196V4a1 1 0 10-2 0v.804z"></path></svg>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1 relative z-10">Mapel</p>
            <h4 class="text-xl font-black text-orange-600 relative z-10"><?= $total_mapel ?? 0 ?></h4>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-sm text-center relative overflow-hidden group">
            <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-pink-500/10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z"></path></svg>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1 relative z-10">Wali Kelas</p>
            <h4 class="text-xl font-black text-pink-600 relative z-10"><?= $total_walikelas ?? 0 ?></h4>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-sm text-center relative overflow-hidden group">
            <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-gray-500/10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1 relative z-10">Piket</p>
            <h4 class="text-xl font-black text-gray-700 dark:text-white relative z-10"><?= $total_piket ?? 0 ?></h4>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-sm text-center relative overflow-hidden group">
            <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-indigo-500/10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1 relative z-10">Petugas BK</p>
            <h4 class="text-xl font-black text-gray-700 dark:text-white relative z-10"><?= $total_bk ?? 0 ?></h4>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-black text-gray-900 dark:text-white uppercase text-xs tracking-widest">Tren Kehadiran Mingguan (%)</h3>
                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded-lg font-bold">Live Data</span>
            </div>
            <div class="h-[250px]">
                <canvas id="absensiChart"></canvas>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-sm text-center">
            <h3 class="font-black text-gray-900 dark:text-white uppercase text-xs tracking-widest mb-6">Rasio Pembayaran</h3>
            <div class="h-[200px] flex justify-center">
                <canvas id="keuanganChart"></canvas>
            </div>
            <div class="mt-6 flex justify-center gap-4 text-[10px] font-black">
                <div class="flex items-center gap-1 text-green-500"><div class="w-2 h-2 bg-green-500 rounded-full"></div> LUNAS</div>
                <div class="flex items-center gap-1 text-red-500"><div class="w-2 h-2 bg-red-500 rounded-full"></div> TUNGGAKAN</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-sm">
            <h3 class="font-black text-gray-900 dark:text-white uppercase text-xs mb-4 flex items-center gap-2">
                <div class="w-1 h-4 bg-green-500 rounded-full"></div> Keuangan Sistem
            </h3>
            <div class="space-y-4">
                <div class="p-4 bg-green-50 dark:bg-green-900/10 rounded-2xl border border-green-100 dark:border-green-900/20 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-green-600 uppercase">Total Bayar</p>
                        <p class="text-xl font-black text-green-700 dark:text-green-400">Rp <?= number_format($total_bayar ?? 0, 0, ',', '.') ?></p>
                    </div>
                    <svg class="w-8 h-8 text-green-600/20" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="p-4 bg-red-50 dark:bg-red-900/10 rounded-2xl border border-red-100 dark:border-red-900/20 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-red-600 uppercase">Total Tunggakan</p>
                        <p class="text-xl font-black text-red-700 dark:text-red-400">Rp <?= number_format($total_tunggak ?? 0, 0, ',', '.') ?></p>
                    </div>
                    <svg class="w-8 h-8 text-red-600/20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-sm">
             <div class="flex items-center gap-2 mb-6">
                <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Aktivitas & Histori</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest italic">Login Terakhir</p>
                    <ul class="space-y-3">
                        <?php if(!empty($last_login)): foreach($last_login as $u): ?>
                        <li class="flex justify-between items-center text-[11px] border-b dark:border-gray-700 pb-2">
                            <span class="font-bold text-gray-700 dark:text-gray-300"><?= $u['nama_lengkap'] ?></span>
                            <span class="text-gray-400 italic"><?= $u['last_login'] ?></span>
                        </li>
                        <?php endforeach; else: ?>
                        <li class="text-[11px] text-gray-400">Belum ada data login.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest italic">Log Sistem</p>
                    <ul class="space-y-4">
                        <?php foreach($logs as $l): ?>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                            <div>
                                <p class="text-[11px] font-bold text-gray-900 dark:text-white leading-none"><?= $l['act'] ?></p>
                                <p class="text-[9px] text-gray-400 mt-1">@<?= $l['user'] ?> • <?= $l['time'] ?></p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const isDark = document.documentElement.classList.contains('dark');
    const colorLabel = isDark ? '#9ca3af' : '#4b5563';

    // Grafik Presensi
    new Chart(document.getElementById('absensiChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Kehadiran (%)',
                data: <?= json_encode($chart_presensi) ?>,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { min: 0, max: 100, ticks: { color: colorLabel, font: { size: 10, weight: 'bold' } } },
                x: { ticks: { color: colorLabel, font: { size: 10, weight: 'bold' } } }
            }
        }
    });

    // Grafik Keuangan
    new Chart(document.getElementById('keuanganChart'), {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Tunggakan'],
            datasets: [{
                data: <?= json_encode($chart_keuangan) ?>,
                backgroundColor: ['#10b981', '#ef4444'],
                hoverOffset: 10,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '75%'
        }
    });
</script>

<?= $this->endSection() ?>