<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Profil Saya
    </h1>
</div>

<div class="p-6 max-h-[85vh] overflow-y-auto custom-scrollbar">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm flex items-center" role="alert">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('siswa/profil/simpan') ?>" method="POST">
        <?= csrf_field() ?> <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 dark:bg-gray-700/50 dark:border-gray-600 shadow-sm">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        Informasi Akademik (Terkunci)
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Nama Lengkap</label>
                            <input type="text" value="<?= $profil['nama_lengkap'] ?>" class="bg-gray-100 border-none text-gray-600 rounded-xl w-full mt-1 font-semibold focus:ring-0" readonly>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">NISN / Username</label>
                                <input type="text" value="<?= $profil['username'] ?>" class="bg-gray-100 border-none text-gray-600 rounded-xl w-full mt-1 focus:ring-0" readonly>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Kelas</label>
                                <input type="text" value="<?= $profil['nama_kelas'] ?? '-' ?>" class="bg-gray-100 border-none text-gray-600 rounded-xl w-full mt-1 focus:ring-0" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-blue-100 shadow-xl shadow-blue-500/5 dark:bg-gray-800 dark:border-gray-700">
                    <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-6 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                        Kontak & Notifikasi
                    </h4>
                    <div class="space-y-5">
                        <div class="mt-4">
    <label class="block mb-2 text-sm font-bold text-gray-700 uppercase">Nomor WhatsApp Siswa</label>
    <input type="text" name="no_hp" 
           value="<?= $profil['no_hp_siswa'] ?? '' ?>" 
           class="bg-gray-50 border border-gray-300 rounded-xl block w-full p-3 focus:ring-blue-500 focus:border-blue-500" 
           placeholder="08xxxxxxxxxx">
</div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-blue-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-3.417 17.583l.833-4.333 8.333-7.5-6.583 3.917-3.5-1.083 8.5-3.333-1.667 9.5-.75-.583-.5.916.334 2.5z"/></svg>
                                ID Telegram (Untuk Notifikasi Ujian)
                            </label>
                            <input type="text" name="telegram_chat_id" value="<?= $profil['telegram_chat_id'] ?? '' ?>" class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 font-mono shadow-inner" placeholder="Contoh: 1080546870">
                            <p class="mt-2 text-[10px] text-gray-400 leading-relaxed italic">Dapatkan ID dengan mengirim pesan ke Bot Telegram Sekolah.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h4 class="text-xs font-black text-gray-700 uppercase tracking-widest mb-6 flex items-center dark:text-gray-300">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    Kelengkapan Data Pribadi
                </h4>
                <div class="space-y-5">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Email Aktif</label>
                        <input type="email" name="email" value="<?= $profil['email'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="email@siswa.sch.id">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="<?= $profil['tempat_lahir'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-xl block w-full p-3">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="<?= $profil['tgl_lahir'] ?? $profil['tanggal_lahir'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-xl block w-full p-3">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Alamat Lengkap Domisili</label>
                        <textarea name="alamat" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3" placeholder="Jalan, Desa, RT/RW, Kecamatan..."><?= $profil['alamat'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex justify-end">
            <button type="submit" class="group relative inline-flex items-center justify-center px-12 py-4 font-bold text-white transition-all duration-200 bg-blue-700 font-pj rounded-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 shadow-lg shadow-blue-500/30 transform hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                SIMPAN PERUBAHAN PROFIL
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>