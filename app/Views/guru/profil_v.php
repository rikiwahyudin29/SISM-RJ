<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
        <i class="fas fa-user-circle text-blue-600 mr-3"></i> Pengaturan Profil Saya
    </h1>
</div>

<div class="p-6 max-h-[85vh] overflow-y-auto custom-scrollbar">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('guru/profil/simpan') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 dark:bg-gray-700/50 dark:border-gray-600 shadow-sm">
                <h4 class="text-sm font-bold text-gray-500 uppercase mb-5 flex items-center">
                    <i class="fas fa-lock mr-2"></i> Informasi Utama (Terkunci)
                </h4>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">Nama Lengkap</label>
                        <input type="text" value="<?= $profil['nama_lengkap'] ?>" class="bg-gray-100 border-none text-gray-500 rounded-lg w-full mt-1 font-semibold" readonly>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">NIK / Username</label>
                            <input type="text" value="<?= $profil['username'] ?>" class="bg-gray-100 border-none text-gray-500 rounded-lg w-full mt-1" readonly>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">NIP / NUPTK</label>
                            <input type="text" value="<?= $profil['nip'] ?? $profil['nuptk'] ?? '-' ?>" class="bg-gray-100 border-none text-gray-500 rounded-lg w-full mt-1" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-blue-100 shadow-md dark:bg-gray-800 dark:border-gray-700">
                <h4 class="text-sm font-bold text-blue-600 uppercase mb-5 flex items-center">
                    <i class="fas fa-envelope-open-text mr-2"></i> Kontak & Keamanan Akun
                </h4>
                <div class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-semibold">Email Aktif</label>
                        <input type="email" name="email" value="<?= $profil['email'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="contoh@sekolah.sch.id">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-blue-600 flex items-center">
                            <i class="fab fa-telegram mr-2"></i> ID Telegram (Wajib untuk OTP)
                        </label>
                        <input type="text" name="telegram_chat_id" value="<?= $profil['telegram_chat_id'] ?? '' ?>" class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-mono shadow-inner" placeholder="Contoh: 1080546870">
                        <p class="mt-1 text-[10px] text-gray-500">Kirim pesan <code class="bg-gray-100 px-1">/myid</code> ke Bot Telegram Sekolah untuk melihat ID Anda.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h4 class="text-sm font-bold text-gray-700 uppercase mb-5 border-b pb-2 flex items-center dark:text-gray-300">
                    <i class="fas fa-id-badge mr-2"></i> Kelengkapan Data Pribadi
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium">Gelar Depan</label>
                        <input type="text" name="gelar_depan" value="<?= $profil['gelar_depan'] ?? '' ?>" class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Gelar Belakang</label>
                        <input type="text" name="gelar_belakang" value="<?= $profil['gelar_belakang'] ?? '' ?>" class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Nomor WhatsApp</label>
                        <input type="text" name="no_hp" value="<?= $profil['no_hp'] ?? '' ?>" class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="<?= $profil['tempat_lahir'] ?? '' ?>" class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" value="<?= $profil['tgl_lahir'] ?? $profil['tanggal_lahir'] ?? '' ?>" class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Nama Ibu Kandung</label>
                        <input type="text" name="ibu_kandung" value="<?= $profil['ibu_kandung'] ?? '' ?>" class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5"><?= $profil['alamat'] ?? '' ?></textarea>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-xl text-sm px-10 py-3 shadow-lg transform transition hover:scale-105 active:scale-95">
                <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN PROFIL
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>