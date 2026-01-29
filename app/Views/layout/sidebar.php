<?php 
// 1. SETUP ROLE & USER DATA
$sessionRoles = session()->get('roles') ?? []; 
if (!is_array($sessionRoles)) $sessionRoles = [$sessionRoles]; 

// --- FIX PENTING: BERSIHKAN DATA ROLE ---
// Ini akan mengubah " Piket ", "PIKET", "piket " menjadi "piket" (bersih)
$roles = array_map(function($r) {
    return strtolower(trim($r)); 
}, $sessionRoles);

$roleActive = session()->get('role_active');
$uri = service('uri')->getPath(); 

// 2. SETUP STYLE CLASSES
$baseClass     = "flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm";
$activeClass   = "bg-blue-600 text-white shadow-md shadow-blue-600/20";
$inactiveClass = "text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white";
$headerClass   = "pt-4 pb-2 px-3 text-xs font-extrabold text-gray-400 dark:text-slate-600 uppercase tracking-widest";

// Helper
function is_active($url) {
    return strpos(uri_string(), $url) !== false;
}
?>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-[#0f172a] dark:border-slate-800 shadow-xl" aria-label="Sidebar">
    
    <div class="absolute top-4 right-4 sm:hidden">
        <button type="button" data-drawer-hide="logo-sidebar" aria-controls="logo-sidebar" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>

    <div class="h-full px-3 pb-4 overflow-y-auto custom-scrollbar flex flex-col justify-between" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div>
            <div class="flex items-center gap-3 mb-4 mt-2 px-3 py-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-700">
                <div class="relative shrink-0">
                    <img class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-slate-600" 
                        src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama_lengkap')) ?>&background=0f172a&color=3b82f6&bold=true" alt="User">
                    <span class="absolute bottom-0 right-0 w-2 h-2 bg-emerald-500 border-2 border-white dark:border-[#0f172a] rounded-full"></span>
                </div>
                <div class="overflow-hidden min-w-0">
                    <h2 class="text-xs font-bold text-gray-800 dark:text-white truncate leading-tight"><?= session()->get('nama_lengkap') ?></h2>
                    <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider"><?= $roleActive ?></span>
                </div>
            </div>

            <ul class="space-y-1">
                
                <li>
                    <a href="<?= base_url($roleActive . '/dashboard') ?>" 
                       class="<?= $baseClass ?> <?= (uri_string() == $roleActive.'/dashboard') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                <?php if (in_array('admin', $roles)) : ?>
                
                <li class="<?= $headerClass ?>">Master Data</li>

                <li>
                    <button type="button" class="<?= $baseClass ?> w-full <?= $inactiveClass ?>" aria-controls="dropdown-master" data-collapse-toggle="dropdown-master">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Data Sekolah</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-master" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/master/tahun_ajaran') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Tahun Ajaran</a></li>
                        <li><a href="<?= base_url('admin/jenis_ujian') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Jenis Ujian</a></li>
                        <li><a href="<?= base_url('admin/master/jurusan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Jurusan</a></li>
                        <li><a href="<?= base_url('admin/master/ruangan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Ruangan</a></li>
                        <li><a href="<?= base_url('admin/master/kelas') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Kelas</a></li>
                        <li><a href="<?= base_url('admin/master/mapel') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Mata Pelajaran</a></li>
                        <li><a href="<?= base_url('admin/jam') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Jam Belajar (Bel)</a></li>
                    </ul>
                </li>

                <li>
                    <button type="button" class="<?= $baseClass ?> w-full <?= $inactiveClass ?>" aria-controls="dropdown-keuangan-adm" data-collapse-toggle="dropdown-keuangan-adm">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Keuangan Sekolah</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-keuangan-adm" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/keuangan/pos') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Pos Bayar</a></li>
                        <li><a href="<?= base_url('admin/keuangan/jenis') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Jenis & Generate</a></li>
                        <li><a href="<?= base_url('admin/keuangan/pembayaran') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Pembayaran (Kasir)</a></li>
                        <li><a href="<?= base_url('admin/keuangan/laporan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Laporan Keuangan</a></li>
                        <li><a href="<?= base_url('admin/keuangan/pengeluaran') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Pengeluaran Operasional</a></li>
                        <li><a href="<?= base_url('admin/keuangan/log') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Log Aktivitas</a></li>
                    </ul>
                </li>

                <li class="<?= $headerClass ?>">Akademik & Ujian</li>

                <li>
                    <a href="<?= base_url('admin/jadwal') ?>" class="<?= $baseClass ?> <?= is_active('admin/jadwal') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="ml-3">Jadwal Pelajaran</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?= base_url('admin/jadwalujian') ?>" class="<?= $baseClass ?> <?= is_active('admin/jadwalujian') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3">Jadwal Ujian</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('admin/bank-soal') ?>" class="<?= $baseClass ?> <?= is_active('admin/bank-soal') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="ml-3">Monitoring Soal</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('admin/aturruangan') ?>" class="<?= $baseClass ?> <?= is_active('admin/aturruangan') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="ml-3">Atur Ruangan</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('admin/monitoring-ruang') ?>" class="<?= $baseClass ?> <?= is_active('admin/monitoring-ruang') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="ml-3">Monitoring Ruangan</span>
                    </a>
                </li>

         <?php 
                // 1. AMBIL DATA LANGSUNG DARI SESSION
                $raw_roles = session()->get('roles') ?? [];
                if (!is_array($raw_roles)) $raw_roles = [$raw_roles];

                // 2. BERSIHKAN DATA (Hapus spasi, ubah ke huruf kecil semua)
                // Ini menjamin ' Piket ' atau 'PIKET' tetap terbaca sebagai 'piket'
                $clean_roles = array_map(function($r) { 
                    return strtolower(trim($r)); 
                }, $raw_roles);

                // 3. TENTUKAN HAK AKSES
                // Admin BOLEH, Piket BOLEH
                $boleh_lihat = in_array('admin', $clean_roles) || in_array('piket', $clean_roles);
                ?>

                <?php if ($boleh_lihat) : ?>
                
                <li class="<?= $headerClass ?>">Presensi & Disiplin</li>

                <li>
                    <button type="button" class="<?= $baseClass ?> w-full <?= $inactiveClass ?>" aria-controls="dropdown-presensi-final" data-collapse-toggle="dropdown-presensi-final">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.2-2.873.571-4.244M5.753 11c.883-2.671 2.946-4.672 5.753-5.274M20.25 15.364c-.64-1.319-1-2.8-1-4.364 0-1.457-.2-2.873-.571-4.244"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Presensi Digital</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-presensi-final" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/presensi/scanner') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Scanner (QR/RFID)</a></li>
                        <li><a href="<?= base_url('admin/presensi/laporan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Laporan Kehadiran</a></li>
                        <li><a href="<?= base_url('admin/presensi/izin') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Izin/Sakit</a></li>
                        
                        <?php if (in_array('admin', $clean_roles)) : ?>
                        <li><a href="<?= base_url('admin/presensi/jam') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Setting Jam</a></li>
                        <?php endif; ?>

                        <li><a href="<?= base_url('admin/presensi_guru') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Harian Guru</a></li>
                        <li><a href="<?= base_url('admin/jurnal') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Monitoring Jurnal</a></li>
                    </ul>
                </li>

                <li>
                    <a href="<?= base_url('admin/piket') ?>" class="<?= $baseClass ?> <?= is_active('admin/piket') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="ml-3">Monitoring Piket</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="<?= $headerClass ?>">Administrasi</li>

                <li>
                    <button type="button" class="<?= $baseClass ?> w-full <?= $inactiveClass ?>" aria-controls="dropdown-users" data-collapse-toggle="dropdown-users">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Pengguna</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-users" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/guru') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Guru</a></li>
                        <li><a href="<?= base_url('admin/siswa') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Siswa</a></li>
                        <li><a href="<?= base_url('admin/ortu') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Wali</a></li>
                        <li><a href="<?= base_url('admin/users') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Hak Akses</a></li>
                    </ul>
                </li>

                <li>
                    <button type="button" class="<?= $baseClass ?> w-full <?= $inactiveClass ?>" aria-controls="dropdown-kesiswaan" data-collapse-toggle="dropdown-kesiswaan">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Kesiswaan</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-kesiswaan" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/rombel') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Manajemen Rombel</a></li>
                        <li><a href="<?= base_url('admin/rombel/alumni') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Alumni</a></li>
                        <li><a href="<?= base_url('admin/kartu') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Cetak Kartu Pelajar</a></li>
                    </ul>
                </li>

                <li>
                    <button type="button" class="<?= $baseClass ?> w-full <?= $inactiveClass ?>" aria-controls="dropdown-web" data-collapse-toggle="dropdown-web">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Konfigurasi</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-web" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/pengaturan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Identitas Sekolah</a></li>
                        <li><a href="<?= base_url('admin/backup') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Backup Database</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if (in_array('keuangan', $roles) || in_array('bendahara', $roles)) : ?>
                <li class="<?= $headerClass ?>">Keuangan</li>
                <li>
                    <a href="<?= base_url('admin/keuangan/pos') ?>" class="<?= $baseClass ?> <?= is_active('keuangan/pos') ? $activeClass : $inactiveClass ?>">
                         <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3">Pos Bayar</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/keuangan/jenis') ?>" class="<?= $baseClass ?> <?= is_active('keuangan/jenis') ? $activeClass : $inactiveClass ?>">
                         <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span class="ml-3">Jenis & Generate</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/keuangan/pembayaran') ?>" class="<?= $baseClass ?> <?= is_active('keuangan/pembayaran') ? $activeClass : $inactiveClass ?>">
                         <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="ml-3">Kasir Pembayaran</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/keuangan/laporan') ?>" class="<?= $baseClass ?> <?= is_active('keuangan/laporan') ? $activeClass : $inactiveClass ?>">
                         <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="ml-3">Laporan Keuangan</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/keuangan/pengeluaran') ?>" class="<?= $baseClass ?> <?= is_active('keuangan/pengeluaran') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3">Pengeluaran Ops</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/keuangan/log') ?>" class="<?= $baseClass ?> <?= is_active('keuangan/log') ? $activeClass : $inactiveClass ?>">
                         <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="ml-3">Log Aktivitas</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (in_array('guru', $roles)) : ?>
                <li class="<?= $headerClass ?>">Area Guru</li>
                
                <li>
                    <a href="<?= base_url('guru/jadwal') ?>" class="<?= $baseClass ?> <?= is_active('guru/jadwal') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="ml-3">Jadwal Mengajar</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/presensi') ?>" class="<?= $baseClass ?> <?= is_active('guru/presensi') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3 flex-1">Absensi Saya</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/presensi/rekap') ?>" class="<?= $baseClass ?> <?= is_active('guru/presensi/rekap') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="ml-3">Rekap Bulanan</span>
                    </a>
                </li>
                <li>
    <a href="<?= base_url('guru/presensi/izin') ?>" class="<?= $baseClass ?> <?= is_active('guru/presensi/izin') ? $activeClass : $inactiveClass ?>">
        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <span class="ml-3">Ajukan Izin/Dinas</span>
    </a>
</li>

                <li>
                    <a href="<?= base_url('guru/jurnal') ?>" class="<?= $baseClass ?> <?= is_active('guru/jurnal') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="ml-3">Jurnal KBM</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/bank_soal') ?>" class="<?= $baseClass ?> <?= is_active('guru/bank_soal') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="ml-3 flex-1">Bank Soal</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/ujian') ?>" class="<?= $baseClass ?> <?= is_active('guru/ujian') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3 flex-1">Jadwal Ujian</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/monitoring') ?>" class="<?= $baseClass ?> <?= is_active('guru/monitoring') ? $activeClass : $inactiveClass ?>">
                        <i class="fas fa-desktop w-4 h-4 flex-shrink-0 text-center transition-colors"></i>
                        <span class="ml-3 flex-1">Mengawas</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/nilai') ?>" class="<?= $baseClass ?> <?= is_active('guru/nilai') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="ml-3 flex-1">Input Nilai</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($roleActive == 'siswa') : ?>
                <li class="<?= $headerClass ?>">Area Siswa</li>
                
                <li>
                    <a href="<?= base_url('siswa/keuangan') ?>" class="<?= $baseClass ?> <?= is_active('siswa/keuangan') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 text-center transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <span class="ml-3 flex-1">Keuangan Saya</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('siswa/presensi') ?>" class="<?= $baseClass ?> <?= is_active('siswa/presensi') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="ml-3 flex-1">Riwayat Absen</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('siswa/presensi/rekap') ?>" class="<?= $baseClass ?> <?= is_active('siswa/presensi/rekap') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="ml-3">Rekap Bulanan</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('siswa/presensi/izin') ?>" class="<?= $baseClass ?> <?= is_active('siswa/presensi/izin') ? $activeClass : $inactiveClass ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span class="ml-3">Ajukan Izin/Sakit</span>
                    </a>
                </li>
                <li>
    <a href="<?= base_url('siswa/presensi/pelajaran') ?>" class="<?= $baseClass ?> <?= is_active('siswa/presensi/pelajaran') ? $activeClass : $inactiveClass ?>">
        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        <span class="ml-3 flex-1">Absen Mapel</span>
    </a>
</li>

                <li>
                    <a href="<?= base_url('siswa/ujian') ?>" class="<?= $baseClass ?> <?= is_active('siswa/ujian') ? $activeClass : $inactiveClass ?>">
                        <i class="fas fa-pen-alt w-4 h-4 text-center transition-colors"></i>
                        <span class="ml-3 flex-1">Ujian Sekolah</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('siswa/nilai') ?>" class="<?= $baseClass ?> <?= is_active('siswa/nilai') ? $activeClass : $inactiveClass ?>">
                        <i class="fas fa-chart-line w-4 h-4 text-center transition-colors"></i>
                        <span class="ml-3 flex-1">Riwayat Nilai</span>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
            <?php 
                // Kita cek manual disini biar tidak terpengaruh kodingan atas
                $my_roles = session()->get('roles') ?? [];
                if (!is_array($my_roles)) $my_roles = [$my_roles];
                
                // Cek apakah punya tiket 'piket' atau 'admin'?
                $is_piket_or_admin = false;
                foreach($my_roles as $r) {
                    if (strtolower(trim($r)) == 'piket' || strtolower(trim($r)) == 'admin') {
                        $is_piket_or_admin = true;
                        break;
                    }
                }
                ?>

                <?php if ($is_piket_or_admin) : ?>
                <li class="pt-4 pb-2 px-3 text-xs font-extrabold text-emerald-600 uppercase tracking-widest">
                    Tugas Piket
                </li>

                <li>
                    <a href="<?= base_url('admin/piket') ?>" class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-400 dark:hover:bg-slate-800">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="ml-3">Monitoring Piket</span>
                    </a>
                </li>

                <li>
                    <button type="button" class="flex items-center w-full px-3 py-2 rounded-lg transition-all group font-bold text-sm text-gray-600 hover:bg-gray-100" aria-controls="dropdown-piket-tools" data-collapse-toggle="dropdown-piket-tools">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Data Presensi</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-piket-tools" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/presensi/laporan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-emerald-600">Laporan Siswa</a></li>
                        <li><a href="<?= base_url('admin/presensi_guru') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-emerald-600">Absen Guru</a></li>
                        <li><a href="<?= base_url('admin/jurnal') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-emerald-600">Jurnal Mengajar</a></li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
        </div>

        <div class="mt-2 pt-3 border-t border-gray-100 dark:border-slate-800/50">
             <a href="<?= base_url('logout') ?>" class="flex items-center px-3 py-2 text-gray-500 rounded-lg hover:bg-red-50 hover:text-red-600 dark:text-slate-500 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-all group">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="ml-3 text-sm font-bold">Keluar Sistem</span>
            </a>
        </div>
    </div>
</aside>