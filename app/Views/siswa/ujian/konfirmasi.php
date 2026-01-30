<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-1">
    <div class="min-h-[80vh] flex items-center justify-center p-4">
        
        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden dark:bg-slate-800 border border-gray-100 dark:border-slate-700">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-center text-white relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <h2 class="text-3xl font-extrabold tracking-tight relative z-10">KONFIRMASI UJIAN</h2>
                <p class="text-blue-100 text-sm mt-2 relative z-10">Mohon periksa detail di bawah ini sebelum memulai.</p>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    
                    <div class="md:col-span-1 flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-slate-700/50 rounded-2xl border-2 border-dashed border-gray-300 dark:border-slate-600 group hover:border-blue-400 transition-colors">
                        <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-white text-lg text-center leading-tight mb-1">
                            <?= $bank['judul_ujian'] ?>
                        </span>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest bg-gray-200 dark:bg-slate-600 px-2 py-1 rounded">
                            <?= $bank['nama_mapel'] ?>
                        </span>
                    </div>

                    <div class="md:col-span-2 space-y-5">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm flex items-center"><i class="far fa-clock w-5 mr-2"></i> Waktu Mulai</span>
                            <span class="font-bold text-gray-800 dark:text-white text-right">
                                <?= date('d M Y', strtotime($bank['waktu_mulai'])) ?> <br>
                                <span class="text-blue-600"><?= date('H:i', strtotime($bank['waktu_mulai'])) ?> WIB</span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm flex items-center"><i class="fas fa-hourglass-half w-5 mr-2"></i> Durasi</span>
                            <span class="font-bold text-gray-800 dark:text-white"><?= $bank['durasi'] ?> Menit</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm flex items-center"><i class="fas fa-list-ol w-5 mr-2"></i> Jumlah Soal</span>
                            <span class="font-bold text-gray-800 dark:text-white"><?= $bank['jumlah_soal'] ?? '-' ?> Butir</span>
                        </div>
                        <div class="flex items-center justify-between pb-1">
                            <span class="text-gray-500 dark:text-gray-400 text-sm flex items-center"><i class="fas fa-tags w-5 mr-2"></i> Tipe Soal</span>
                            <span class="font-bold text-gray-800 dark:text-white">Pilihan Ganda & Esai</span>
                        </div>
                    </div>
                </div>

                <div class="flex p-4 mb-8 text-sm text-yellow-800 rounded-xl bg-yellow-50 dark:bg-slate-700 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-600 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <div>
                        <span class="font-bold block mb-1">PENTING:</span>
                        <ul class="mt-1 ml-4 list-disc list-outside space-y-1 text-xs sm:text-sm">
                            <li>Waktu akan langsung berjalan saat tombol <b>MULAI</b> diklik.</li>
                            <li>Dilarang keluar dari mode layar penuh (Fullscreen) atau berpindah tab.</li>
                            <li>Sistem akan mendeteksi dan mencatat kecurangan secara otomatis.</li>
                        </ul>
                    </div>
                </div>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 dark:bg-red-900/30 dark:text-red-300 border border-red-200 text-center animate-pulse">
                        <i class="fas fa-exclamation-circle mr-1"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('siswa/ujian/mulai') ?>" method="post" id="formKonfirmasi">
                    <?= csrf_field() ?>
                    
                    <input type="hidden" name="id_jadwal" value="<?= $bank['id'] ?>"> 
                    
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="long">

                    <?php if (!empty($bank['token'])): ?>
                        <div class="mb-8 bg-gray-50 dark:bg-slate-700/50 p-6 rounded-2xl border border-gray-200 dark:border-slate-600">
                            <label for="token" class="block mb-3 text-sm font-bold text-gray-900 dark:text-white text-center uppercase tracking-widest">
                                Masukkan Token Ujian
                            </label>
                            <div class="relative max-w-xs mx-auto">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="fas fa-key text-gray-400 text-lg"></i>
                                </div>
                                <input type="text" name="token" id="token" 
                                    class="bg-white border-2 border-gray-300 text-gray-900 text-3xl rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-3 font-mono font-bold text-center uppercase tracking-[0.3em] shadow-inner dark:bg-slate-800 dark:text-white dark:border-slate-500 transition-all" 
                                    placeholder="TOKEN" required autocomplete="off" maxlength="6">
                            </div>
                            <p class="mt-3 text-xs text-center text-gray-500 dark:text-gray-400">
                                Token dapat diperoleh dari Pengawas Ruangan.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="mb-8 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-center text-green-700 dark:text-green-300 text-sm">
                            <i class="fas fa-unlock-alt mr-2"></i> Ujian ini tidak memerlukan Token.
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-2 gap-4">
                        <a href="<?= base_url('siswa/ujian') ?>" class="py-4 px-5 w-full text-sm font-bold text-gray-700 bg-white rounded-xl border border-gray-300 hover:bg-gray-50 hover:text-gray-900 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-600 dark:hover:text-white dark:hover:bg-slate-700 text-center transition-transform active:scale-95">
                            <i class="fas fa-arrow-left mr-2"></i> BATAL
                        </a>
                        
                        <button type="submit" id="btnMulai"
                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-sm px-5 py-4 text-center shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-95">
                            MULAI MENGERJAKAN <i class="fas fa-rocket ml-2"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // 1. Auto Uppercase Token
    const tokenInput = document.getElementById('token');
    if(tokenInput) {
        tokenInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // 2. Loading State pada Tombol Submit
    const form = document.getElementById('formKonfirmasi');
    const btn = document.getElementById('btnMulai');

    form.addEventListener('submit', function() {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });

    // 3. Geolocation (Opsional)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('long').value = position.coords.longitude;
        });
    }
</script>

<?= $this->endSection() ?>