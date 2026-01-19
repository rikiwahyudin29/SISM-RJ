<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="min-h-[80vh] flex items-center justify-center p-4">
    
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-center text-white">
            <h2 class="text-2xl font-extrabold tracking-tight">KONFIRMASI DATA UJIAN</h2>
            <p class="text-blue-100 text-sm mt-1">Pastikan data di bawah ini benar sebelum memulai.</p>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">
                    <i class="fas fa-file-signature text-6xl text-blue-500 mb-3"></i>
                    <span class="font-bold text-gray-700 dark:text-gray-200 text-lg text-center"><?= $bank['judul_ujian'] ?></span>
                    <span class="text-xs text-gray-500 uppercase tracking-widest mt-1"><?= $bank['nama_mapel'] ?></span>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Waktu Mulai</span>
                        <span class="font-bold text-gray-800 dark:text-white"><?= $bank['waktu_mulai'] ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Durasi Pengerjaan</span>
                        <span class="font-bold text-gray-800 dark:text-white"><?= $bank['durasi'] ?> Menit</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Jumlah Soal</span>
                        <span class="font-bold text-gray-800 dark:text-white"><?= $bank['jumlah_soal'] ?? '-' ?> Butir</span>
                    </div>
                    <div class="flex justify-between pb-2">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Jenis Soal</span>
                        <span class="font-bold text-gray-800 dark:text-white">Pilihan Ganda</span>
                    </div>
                </div>
            </div>

            <div class="flex p-4 mb-6 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-700 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-600" role="alert">
                <svg class="flex-shrink-0 inline w-5 h-5 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <div>
                    <span class="font-medium">Perhatian!</span>
                    <ul class="mt-1.5 ml-4 list-disc list-inside">
                        <li>Waktu akan langsung berjalan saat tombol <b>MULAI</b> diklik.</li>
                        <li>Jangan keluar dari mode layar penuh (Fullscreen) atau berpindah tab.</li>
                        <li>Sistem akan mendeteksi kecurangan secara otomatis.</li>
                    </ul>
                </div>
            </div>

            <form action="<?= base_url('siswa/ujian/mulai') ?>" method="post" id="formKonfirmasi">
                <input type="hidden" name="id_bank_soal" value="<?= $bank['id'] ?>">
                
                <input type="hidden" name="latitude" id="lat">
                <input type="hidden" name="longitude" id="long">

                <?php if (!empty($bank['token'])): ?>
                    <div class="mb-8">
                        <label for="token" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white text-center">MASUKKAN TOKEN UJIAN</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="text" name="token" id="token" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-4 font-mono font-bold text-center uppercase tracking-[0.5em]" 
                                placeholder="TOKEN" required autocomplete="off" maxlength="6">
                        </div>
                        <p class="mt-2 text-xs text-center text-gray-500">Silakan minta Token kepada Pengawas Ruangan.</p>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-2 gap-4">
                    <a href="<?= base_url('siswa/ujian') ?>" class="py-3.5 px-5 w-full text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-xl border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 text-center shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Batal
                    </a>
                    
                    <button type="submit" onclick="this.disabled=true; this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Memuat...'; this.form.submit();" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-sm px-5 py-3.5 text-center shadow-lg shadow-blue-700/30 transition-all hover:scale-[1.02]">
                        MULAI MENGERJAKAN <i class="fas fa-rocket ml-2"></i>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    const tokenInput = document.getElementById('token');
    if(tokenInput) {
        tokenInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Optional: Auto Geolocation (Jika nanti diaktifkan di controller)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('long').value = position.coords.longitude;
        });
    }
</script>

<?= $this->endSection() ?>