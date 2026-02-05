<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">
    <?php if(session()->getFlashdata('error')): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-900/50" role="alert">
            <span class="font-bold">Akses Ditolak:</span> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <?php 
                        $fotoSession = session()->get('foto');
                        $pathFoto = 'uploads/guru/' . $fotoSession;
                        $imgSrc = (!empty($fotoSession) && file_exists(FCPATH . $pathFoto)) 
                            ? base_url($pathFoto) 
                            : "https://ui-avatars.com/api/?name=" . urlencode(session()->get('nama_lengkap')) . "&background=0D8ABC&color=fff";
                    ?>
                    <img class="w-16 h-16 rounded-full border-2 border-slate-100 dark:border-slate-700 object-cover" src="<?= $imgSrc ?>" alt="Profil">
                    <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-gray-800 rounded-full" title="Online"></div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Halo, <?= session()->get('nama_lengkap') ?>! 👋
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                        Selamat datang di Master Control Panel <span class="text-blue-600 dark:text-blue-400 font-bold">SIAKAD</span>
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg border border-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800">
                    <?= strtoupper(session()->get('role_active')) ?>
                </span>
                <span class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1.5 rounded-lg border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600">
                    TA: <?= date('Y') ?>/<?= date('Y')+1 ?>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        <?php 
        function cardItem($label, $count, $color, $iconPath) {
            $colors = [
                'blue' => 'text-blue-600 bg-blue-50 dark:bg-blue-900/20',
                'green' => 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20',
                'purple' => 'text-purple-600 bg-purple-50 dark:bg-purple-900/20',
                'orange' => 'text-orange-600 bg-orange-50 dark:bg-orange-900/20',
                'pink' => 'text-pink-600 bg-pink-50 dark:bg-pink-900/20',
                'gray' => 'text-slate-600 bg-slate-50 dark:bg-slate-800',
                'indigo' => 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20',
            ];
            $theme = $colors[$color] ?? $colors['gray'];
            
            echo "
            <div class='p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-sm text-center group hover:shadow-md transition-all'>
                <div class='mb-2 inline-flex p-2 rounded-xl {$theme}'>
                    <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'>{$iconPath}</svg>
                </div>
                <p class='text-xs font-bold text-gray-400 uppercase mb-0.5'>{$label}</p>
                <h4 class='text-xl font-bold text-gray-800 dark:text-white'>{$count}</h4>
            </div>";
        }

        cardItem('Guru/Staff', $total_guru, 'blue', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>');
        cardItem('Siswa', $total_siswa, 'green', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>');
        cardItem('Kelas', $total_kelas, 'purple', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>');
        cardItem('Mapel', $total_mapel, 'orange', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>');
        cardItem('Wali Kelas', $total_walikelas, 'pink', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>');
        cardItem('Piket', $total_piket, 'gray', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>');
        cardItem('BK', $total_bk, 'indigo', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>');
        ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Statistik Kehadiran</h3>
                    <p class="text-sm text-gray-500">Tren kehadiran siswa 7 hari terakhir.</p>
                </div>
                <div class="text-xs bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full font-bold border border-emerald-100">
                    Live Data
                </div>
            </div>
            <div class="h-[280px]">
                <canvas id="absensiChart"></canvas>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-800 dark:text-white text-lg mb-4">Keuangan Sekolah</h3>
                
                <div class="space-y-3 mb-6">
                    <div class="p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-100 dark:border-slate-700">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Pemasukan</p>
                        <p class="text-xl font-bold text-emerald-600">Rp <?= number_format($total_bayar ?? 0, 0, ',', '.') ?></p>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-100 dark:border-slate-700">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-1">Estimasi Tunggakan</p>
                        <p class="text-xl font-bold text-rose-600">Rp <?= number_format($total_tunggak ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="h-[180px] relative">
                <canvas id="keuanganChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
            <h3 class="font-bold text-gray-800 dark:text-white text-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Log Login Terakhir
            </h3>
            <div class="overflow-hidden">
                <table class="w-full text-sm text-left">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php if(!empty($last_login)): foreach($last_login as $u): ?>
                        <tr>
                            <td class="py-3">
                                <p class="font-bold text-gray-800 dark:text-white"><?= esc($u['nama_lengkap']) ?></p>
                                <p class="text-xs text-gray-400 capitalize"><?= esc($u['role'] ?? $u['level'] ?? 'User') ?></p>
                            </td>
                            <td class="py-3 text-right">
                                <span class="text-xs font-mono text-slate-500 bg-slate-100 px-2 py-1 rounded dark:bg-slate-700 dark:text-slate-300">
                                    <?= isset($u['last_login']) && $u['last_login'] ? date('d/m H:i', strtotime($u['last_login'])) : '-' ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td class="py-4 text-center text-gray-400 italic">Belum ada data login.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
    <h3 class="font-bold text-gray-800 dark:text-white text-lg mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Transaksi Terakhir
    </h3>
    <div class="overflow-hidden">
        <table class="w-full text-sm text-left">
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(!empty($log_keuangan)): foreach($log_keuangan as $k): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="py-3">
                        <p class="font-bold text-gray-800 dark:text-white uppercase text-[11px]">
                            <?= esc($k['nama_lengkap']) ?>
                        </p>
                        <p class="text-[10px] text-gray-400 font-medium">
                            <?= esc($k['keterangan'] ?? 'Pembayaran Sekolah') ?>
                        </p>
                    </td>
                    <td class="py-3 text-right">
                        <p class="font-black text-emerald-600 text-xs">
                            + Rp <?= number_format($k['nominal'] ?? $k['jumlah'] ?? 0, 0, ',', '.') ?>
                        </p>
                        <p class="text-[9px] text-gray-400 font-bold">
                            <?= date('d M Y', strtotime($k['created_at'] ?? $k['tanggal'] ?? 'now')) ?>
                        </p>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="2" class="py-16 text-center text-gray-400 italic text-xs">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Belum ada transaksi terbaru.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
    </div>
</div>

<script>
    const isDark = document.documentElement.classList.contains('dark');
    const colorLabel = isDark ? '#9ca3af' : '#64748b';
    const gridColor = isDark ? '#374151' : '#f1f5f9';

    // Grafik Presensi
    const ctx = document.getElementById('absensiChart').getContext('2d');
    
    // Gradient Warna
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)'); // Biru pudar atas
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');   // Transparan bawah

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Kehadiran (%)',
                data: <?= json_encode($chart_presensi) ?>,
                borderColor: '#2563eb', // Blue 600
                backgroundColor: gradient,
                fill: true,
                tension: 0.4, // Melengkung halus
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#2563eb',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + '% Hadir';
                        }
                    }
                }
            },
            scales: {
                y: { 
                    min: 0, 
                    max: 100, 
                    grid: { color: gridColor, borderDash: [5, 5] },
                    ticks: { color: colorLabel, font: { size: 11, family: 'sans-serif' } } 
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: colorLabel, font: { size: 11, family: 'sans-serif' } } 
                }
            }
        }
    });

    // Grafik Keuangan (Donat)
    new Chart(document.getElementById('keuanganChart'), {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Tunggakan'],
            datasets: [{
                data: <?= json_encode($chart_keuangan) ?>,
                backgroundColor: ['#10b981', '#f43f5e'], // Emerald 500 & Rose 500
                hoverOffset: 4,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%', // Bolong tengah
            plugins: { 
                legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 11 } } } 
            }
        }
    });
</script>

<?= $this->endSection() ?>