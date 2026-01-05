<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-2 sm:ml-12">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Manajemen Website & Sistem</h1>
            <p class="text-sm text-gray-500 font-medium">Kelola identitas, konten visual, dan data akademik sekolah.</p>
        </div>
        <span class="bg-blue-100 text-blue-800 text-xs font-black px-4 py-1.5 rounded-full dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 uppercase tracking-widest">
            Configuration Mode
        </span>
    </div>

    <form action="<?= base_url('admin/pengaturan/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <div class="space-y-6">
                <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-5 text-blue-600 flex items-center">Identitas Sekolah</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" value="<?= $config['nama_sekolah'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-sm rounded-xl block w-full p-3 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">Email & Telp</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="email" name="email" value="<?= $config['email'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-xs rounded-xl p-3 dark:bg-gray-700 dark:text-white">
                                <input type="text" name="no_telp" value="<?= $config['no_telp'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-xs rounded-xl p-3 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-5 text-red-600 flex items-center">System & Security</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">Telegram Bot Token</label>
                            <input type="password" name="telegram_token" value="<?= $config['telegram_token'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-sm rounded-xl block w-full p-3 dark:bg-gray-700 dark:text-white font-mono">
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">Tahun Ajaran Aktif</label>
                            <select name="tahun_ajaran" class="bg-gray-50 border border-gray-300 text-sm rounded-xl block w-full p-3 dark:bg-gray-700 dark:text-white font-bold">
                                <option value="2025/2026" <?= ($config['tahun_ajaran'] ?? '') == '2025/2026' ? 'selected' : '' ?>>2025/2026</option>
                                <option value="2026/2027" <?= ($config['tahun_ajaran'] ?? '') == '2026/2027' ? 'selected' : '' ?>>2026/2027</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-2 space-y-6">
                <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-5 text-purple-600 flex items-center">🖼️ Galeri & Konten Visual</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">Tagline Hero (Halaman Depan)</label>
                            <textarea name="deskripsi_hero" rows="2" class="bg-gray-50 border border-gray-300 text-sm rounded-xl block w-full p-3 dark:bg-gray-700 dark:text-white"><?= $config['deskripsi_hero'] ?? '' ?></textarea>
                        </div>
                        
                        <div class="p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-2xl">
                            <label class="block mb-3 text-xs font-bold text-gray-500 uppercase">Foto Kepala Sekolah</label>
                            <div class="flex items-center gap-4">
                                <img src="<?= base_url('uploads/web/' . ($config['foto_kepsek'] ?? 'default.jpg')) ?>" class="w-24 h-24 rounded-2xl object-cover shadow-md" alt="Kepsek">
                                <input type="file" name="foto_kepsek" class="text-xs text-gray-500">
                            </div>
                        </div>

                        <div class="p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-2xl">
                            <label class="block mb-3 text-xs font-bold text-gray-500 uppercase">Foto Gedung Utama (Galeri)</label>
                            <div class="flex items-center gap-4">
                                <img src="<?= base_url('uploads/web/' . ($config['foto_galeri_1'] ?? 'default.jpg')) ?>" class="w-24 h-24 rounded-2xl object-cover shadow-md" alt="Gedung">
                                <input type="file" name="foto_galeri_1" class="text-xs text-gray-500">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">Sambutan Kepala Sekolah</label>
                            <textarea name="sambutan_kepsek" rows="5" class="bg-gray-50 border border-gray-300 text-sm rounded-xl block w-full p-3 dark:bg-gray-700 dark:text-white"><?= $config['sambutan_kepsek'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-bold rounded-2xl text-base px-12 py-4 shadow-xl shadow-blue-500/20 flex items-center transition-all hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Semua Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if (session()->getFlashdata('success')) : ?>
<div id="success-popup" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 max-w-sm w-full border border-gray-100 dark:border-gray-700 flex flex-col items-center text-center">
        <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-4">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-xl font-black text-gray-900 dark:text-white mb-2">Update Berhasil!</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            <?= session()->getFlashdata('success') ?>
        </p>
    </div>
</div>

<script>
    setTimeout(function() {
        const popup = document.getElementById('success-popup');
        if (popup) {
            popup.classList.add('opacity-0');
            setTimeout(() => popup.remove(), 300);
        }
    }, 3000);
</script>
<?php endif; ?>

<?= $this->endSection() ?>