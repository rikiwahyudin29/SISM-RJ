// View: user/profile.php
<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto space-y-6">
    <div class="p-8 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border dark:border-gray-700">
        <h2 class="text-xl font-black text-gray-900 dark:text-white uppercase italic mb-6">Pengaturan Akun</h2>
        
        <form action="<?= base_url('user/profile/update') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            
            <div class="flex flex-col items-center mb-6">
                <img class="w-24 h-24 rounded-full border-4 border-blue-500 p-1 object-cover mb-4" 
                     src="<?= base_url('uploads/guru/'.session()->get('foto')) ?>" alt="Profil">
                <label class="cursor-pointer bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest text-gray-600 dark:text-gray-300">
                    Ganti Foto
                    <input type="file" name="foto" class="hidden">
                </label>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" value="<?= session()->get('nama_lengkap') ?>" readonly class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm text-gray-400">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Username</label>
                    <input type="text" value="<?= session()->get('username') ?>" readonly class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm text-gray-400">
                </div>
                <div class="pt-4 border-t dark:border-gray-700">
                    <p class="text-[10px] font-black text-red-500 uppercase mb-3">Ganti Password (Kosongkan jika tidak diganti)</p>
                    <input type="password" name="password" placeholder="Password Baru" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm focus:ring-blue-500 mb-2">
                    <input type="password" name="confirm_password" placeholder="Ulangi Password Baru" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm focus:ring-blue-500">
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-blue-600 text-white font-black rounded-2xl text-xs uppercase shadow-lg shadow-blue-500/20 active:scale-95 transition-all mt-4">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>