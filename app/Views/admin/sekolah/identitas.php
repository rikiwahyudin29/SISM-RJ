<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="p-4">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-school text-blue-600 mr-2"></i> Identitas Sekolah
            </h1>
            <p class="text-sm text-gray-500 mt-1">Data profil sekolah yang terintegrasi dengan Dapodik.</p>
        </div>

        <a href="<?= base_url('admin/dapodik/tarik_sekolah') ?>" 
           onclick="return confirm('Tarik data terbaru dari Dapodik? Data lokal akan ditimpa.')"
           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 flex items-center shadow-lg transition-transform transform hover:scale-105">
            <i class="fas fa-sync-alt mr-2 animate-spin-slow"></i> Update dari Dapodik
        </a>
    </div>

    <?php if(empty($sekolah)): ?>
        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 border border-yellow-300" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 text-lg"></i>
                <span class="font-medium">Data Belum Ada!</span>
            </div>
            <div class="mt-2">
                Silakan klik tombol <span class="font-bold">Update dari Dapodik</span> di pojok kanan atas.
            </div>
        </div>
    <?php else: ?>

    <div class="w-full bg-white border border-gray-200 rounded-xl shadow-md dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
        
        <div class="flex flex-col items-center pb-6 pt-8 bg-gradient-to-b from-blue-50 to-white dark:from-gray-700 dark:to-gray-800">
            <div class="w-20 h-20 rounded-full bg-white shadow-md flex items-center justify-center mb-3 border border-gray-200">
                <i class="fas fa-university text-4xl text-blue-600"></i>
            </div>
            
            <h5 class="mb-1 text-2xl font-bold text-gray-900 dark:text-white text-center uppercase tracking-wide">
                <?= $sekolah->nama_sekolah ?>
            </h5>
            
            <div class="flex items-center space-x-2 mt-2">
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900 border border-green-200">
                    NPSN: <?= $sekolah->npsn ?>
                </span>
                <?php if(!empty($sekolah->website)): ?>
                    <a href="<?= $sekolah->website ?>" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center">
                        <i class="fas fa-globe mr-1"></i> Website
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-2 text-sm">
                        <i class="fas fa-map-marked-alt"></i>
                    </span>
                    Lokasi Sekolah
                </h3>
                
                <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
                    <div class="flex flex-col pb-3">
                        <dt class="mb-1 text-gray-500 md:text-sm dark:text-gray-400 text-xs uppercase font-semibold">Alamat Jalan</dt>
                        <dd class="text-sm font-medium"><?= $sekolah->alamat ?></dd>
                    </div>
                    <div class="flex flex-col py-3">
                        <dt class="mb-1 text-gray-500 md:text-sm dark:text-gray-400 text-xs uppercase font-semibold">Desa / Kelurahan</dt>
                        <dd class="text-sm font-medium"><?= $sekolah->desa_kelurahan ?></dd>
                    </div>
                    <div class="flex flex-col py-3">
                        <dt class="mb-1 text-gray-500 md:text-sm dark:text-gray-400 text-xs uppercase font-semibold">Kecamatan</dt>
                        <dd class="text-sm font-medium"><?= $sekolah->kecamatan ?></dd>
                    </div>
                    <div class="flex flex-col py-3">
                        <dt class="mb-1 text-gray-500 md:text-sm dark:text-gray-400 text-xs uppercase font-semibold">Kabupaten / Kota</dt>
                        <dd class="text-sm font-medium"><?= $sekolah->kabupaten ?></dd>
                    </div>
                    <div class="flex flex-col py-3">
                        <dt class="mb-1 text-gray-500 md:text-sm dark:text-gray-400 text-xs uppercase font-semibold">Provinsi</dt>
                        <dd class="text-sm font-medium"><?= $sekolah->provinsi ?> - Kode Pos: <?= $sekolah->kode_pos ?></dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mr-2 text-sm">
                        <i class="fas fa-user-tie"></i>
                    </span>
                    Pimpinan & Kontak
                </h3>

                <div class="flex items-center p-4 mb-4 text-sm text-gray-800 border border-purple-200 rounded-lg bg-purple-50 dark:bg-gray-800 dark:text-purple-400 dark:border-purple-800" role="alert">
                    <div class="flex-shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-200 text-purple-600">
                         <i class="fas fa-user"></i>
                    </div>
                    <div class="ml-3">
                        <span class="font-medium block text-xs uppercase text-purple-700">Kepala Sekolah</span>
                        <div class="text-base font-bold text-gray-900 dark:text-white"><?= $sekolah->kepala_sekolah ?></div>
                        <div class="text-xs text-gray-600">NIP: <?= $sekolah->nip_kepsek ?></div>
                    </div>
                </div>

                <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
                    <div class="flex flex-col pb-3">
                        <dt class="mb-1 text-gray-500 md:text-sm dark:text-gray-400 text-xs uppercase font-semibold">Nomor Telepon</dt>
                        <dd class="text-sm font-medium"><?= $sekolah->no_telp ?></dd>
                    </div>
                    <div class="flex flex-col py-3">
                        <dt class="mb-1 text-gray-500 md:text-sm dark:text-gray-400 text-xs uppercase font-semibold">Email Sekolah</dt>
                        <dd class="text-sm font-medium text-blue-600"><?= $sekolah->email ?></dd>
                    </div>
                </dl>
            </div>

        </div>
        
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 dark:bg-gray-700 dark:border-gray-600">
            <p class="text-xs text-gray-500 flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-1"></i> 
                Data ini tersinkronisasi otomatis dengan Web Service Dapodik.
            </p>
        </div>
    </div>

    <?php endif; ?>
</div>

<?= $this->endSection(); ?>