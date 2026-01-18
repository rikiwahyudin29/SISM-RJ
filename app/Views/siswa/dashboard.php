<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
    $fotoDb = $siswa['foto'] ?? 'default.png';
    $namaUrl = urlencode($siswa['nama_lengkap']);
    
    // Logika: Jika foto 'default.png' atau kosong, pakai UI Avatars. Jika ada file, pakai file lokal.
    if ($fotoDb == 'default.png' || empty($fotoDb)) {
        $fotoProfil = "https://ui-avatars.com/api/?name={$namaUrl}&background=random&color=fff&bold=true&size=128";
    } else {
        $fotoProfil = base_url('uploads/siswa/' . $fotoDb);
    }
?>

<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Selamat Datang, <?= esc($siswa['nama_lengkap']) ?>! 👋</h1>
        <p class="text-sm text-gray-500">Panel Siswa - Cek Data Akademik</p>
    </div>
</div>

<div class="p-4">
    <div class="max-w-4xl bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Validasi Data Siswa</h5>
            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                Status: <?= esc($siswa['status_siswa'] ?? 'Aktif') ?>
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            
            <div class="flex flex-col items-center text-center md:items-start md:text-left gap-4">
                <div class="relative group">
                    <img class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-lg" 
                         src="<?= $fotoProfil ?>" 
                         alt="Foto Siswa">
                    <span class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                </div>
                
                <div class="w-full">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Nama Lengkap</p>
                    <p class="text-xl font-black text-gray-900 dark:text-white leading-tight mb-2"><?= esc($siswa['nama_lengkap']) ?></p>
                    
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                            NISN: <?= esc($siswa['nisn']) ?>
                        </span>
                        <span class="bg-gray-100 text-gray-800 text-xs font-bold px-3 py-1 rounded-full dark:bg-gray-700 dark:text-gray-300">
                            <?= ($siswa['jenis_kelamin'] == 'L') ? 'Laki-Laki' : 'Perempuan' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-4 w-full">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600 transition hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Kelas Saat Ini</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                <?= !empty($siswa['nama_kelas']) ? esc($siswa['nama_kelas']) : '<span class="text-red-500 italic">Belum Masuk Rombel</span>' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600 transition hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                            <i class="fas fa-tools text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Kompetensi Keahlian</p>
                            <p class="text-md font-bold text-gray-900 dark:text-white leading-tight">
                                <?= !empty($siswa['nama_jurusan']) ? esc($siswa['nama_jurusan']) : '-' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
             <a href="<?= base_url('logout') ?>" class="inline-flex items-center gap-2 text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-bold rounded-lg text-sm px-6 py-3 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 transition-all shadow-lg shadow-red-600/20">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </a>
        </div>
    </div>
</div>
<div class="mt-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
        <h5 class="mb-4 text-xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fas fa-calendar-alt text-blue-600"></i> Jadwal Pelajaran Saya
        </h5>

        <?php if(empty($jadwal)): ?>
            <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 border border-yellow-300" role="alert">
                <span class="font-medium">Belum ada jadwal!</span> Hubungi admin atau wali kelas untuk info lebih lanjut.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php 
                // Grouping Jadwal per Hari
                $jadwalPerHari = [];
                foreach($jadwal as $j) {
                    $jadwalPerHari[$j['hari']][] = $j;
                }
                $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                ?>

                <?php foreach($hariList as $hari): ?>
                    <?php if(isset($jadwalPerHari[$hari])): ?>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <div class="bg-blue-600 text-white text-center py-2 font-bold uppercase tracking-wider text-sm">
                            <?= $hari ?>
                        </div>
                        
                        <div class="p-3 space-y-3">
                            <?php foreach($jadwalPerHari[$hari] as $item): ?>
                            <div class="flex items-start gap-3 p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-600">
                                <div class="text-center min-w-[60px]">
                                    <span class="block text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded px-1 py-0.5">
                                        <?= substr($item['jam_mulai'], 0, 5) ?>
                                    </span>
                                    <span class="block text-[10px] text-gray-400 mt-0.5">s/d</span>
                                    <span class="block text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded px-1 py-0.5">
                                        <?= substr($item['jam_selesai'], 0, 5) ?>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                                        <?= $item['nama_mapel'] ?>
                                    </h4>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-medium">
                                        <i class="fas fa-user-circle mr-1"></i> <?= $item['nama_guru'] ?>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div> 
<?= $this->endSection() ?>