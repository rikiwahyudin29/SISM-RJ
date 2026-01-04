<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="space-y-6">

    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700 transition-all duration-300">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="relative group">
                <?php 
                    // Ambil nama file foto dari session (pastikan saat login nama file foto disimpan ke session)
                    $fotoSession = session()->get('foto');
                    $pathFoto = 'uploads/guru/' . $fotoSession;
                    
                    // Cek apakah file fisik ada dan nama file tidak kosong
                    if (!empty($fotoSession) && file_exists(FCPATH . $pathFoto)): 
                ?>
                    <img class="w-16 h-16 rounded-full border-2 border-blue-500 p-0.5 object-cover shadow-sm" 
                         src="<?= base_url($pathFoto) ?>" 
                         alt="Foto Profil">
                <?php else: ?>
                    <img class="w-16 h-16 rounded-full border-2 border-blue-500 p-0.5 object-cover shadow-sm" 
                         src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama_lengkap')) ?>&background=0D8ABC&color=fff" 
                         alt="Avatar Default">
                <?php endif; ?>
                
                <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full" title="Sistem Aktif"></div>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">
                    Halo, <?= session()->get('nama_lengkap') ?>! 👋
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium tracking-tight">
                    Selamat mengelola sistem di <span class="text-blue-600 dark:text-blue-400">SIAKAD SISM-RJ</span>.
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="bg-red-100 text-red-800 text-[10px] font-black px-3 py-1 rounded-full dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 uppercase tracking-widest">
                <?= strtoupper(session()->get('role_active')) ?>
            </span>
            <span class="bg-blue-100 text-blue-800 text-[10px] font-black px-3 py-1 rounded-full dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800 uppercase tracking-widest">
                SUPER USER
            </span>
        </div>
    </div>
</div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-5 bg-white border border-gray-100 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Guru</p>
                    <h5 class="text-3xl font-black text-gray-900 dark:text-white mt-1"><?= $total_guru ?></h5>
                </div>
                <div class="p-3.5 bg-blue-100 rounded-xl dark:bg-blue-900/50">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        </div>

    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center gap-2 mb-6">
            <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
            <h3 class="text-lg font-black text-gray-900 dark:text-white">Log Aktivitas Terbaru</h3>
        </div>
        
        <ul class="space-y-4">
            <?php foreach($logs as $l): ?>
            <li class="flex items-start gap-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border border-transparent hover:border-gray-100 dark:hover:border-gray-600">
                <div class="p-2 bg-gray-100 rounded-lg dark:bg-gray-700">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white"><?= $l['act'] ?></p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-medium text-blue-600 dark:text-blue-400">@<?= $l['user'] ?></span>
                        <span class="text-[10px] text-gray-400">•</span>
                        <span class="text-[10px] text-gray-400 font-medium"><?= $l['time'] ?></span>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>