<?= $this->extend('layout/template'); ?> 

<?= $this->section('content'); ?>

<div class="space-y-6">
    
    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700 transition-all duration-300">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <?php 
                        // Ambil data foto dari session
                        $foto = session()->get('foto');
                        $path = 'uploads/guru/' . $foto;
                        
                        // Cek apakah file fisik ada di folder uploads/guru/
                        if (!empty($foto) && file_exists(FCPATH . $path)): 
                    ?>
                        <img class="w-16 h-16 rounded-full border-2 border-blue-500 p-0.5 object-cover shadow-sm" 
                             src="<?= base_url($path) ?>" 
                             alt="Foto Profil">
                    <?php else: ?>
                        <img class="w-16 h-16 rounded-full border-2 border-blue-500 p-0.5 object-cover shadow-sm" 
                             src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama_lengkap')) ?>&background=0D8ABC&color=fff" 
                             alt="Avatar Default">
                    <?php endif; ?>

                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full" title="Online"></div>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white">
                        Halo, <?= session()->get('nama_lengkap'); ?>! 👋
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                        Selamat bertugas di <span class="text-blue-600 dark:text-blue-400">SIAKAD SISM-RJ</span>.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-3 py-1 rounded-full dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800 uppercase tracking-wider">
                    <?= session()->get('role_active') ?>
                </span>
                <span class="bg-green-100 text-green-800 text-[10px] font-bold px-3 py-1 rounded-full dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800 uppercase tracking-wider">
                    WhatsApp Active
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        </div>
</div>

<?= $this->endSection(); ?>