<?= $this->extend('layout/template_ujian') ?>
<?= $this->section('content') ?>

<style>
    /* Cegah seleksi teks (Anti Copy-Paste) */
    body { user-select: none; -webkit-user-select: none; -moz-user-select: none; }
    
    /* Efek Blur saat pelanggaran */
    .blur-content { filter: blur(8px); pointer-events: none; }
    
    /* Overlay Peringatan Pelanggaran */
    #warning-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95); z-index: 9999;
        display: none; flex-direction: column; justify-content: center; align-items: center;
        color: white; text-align: center;
    }

    /* Navigasi Nomor Soal (Opsional jika mau ditambahkan sidebar nanti) */
    .soal-active { display: block; }
    .soal-hidden { display: none; }
</style>

<div id="warning-overlay">
    <div class="text-6xl text-red-500 mb-6"><i class="fas fa-exclamation-triangle animate-pulse"></i></div>
    <h2 class="text-3xl font-bold mb-2 text-white">PELANGGARAN TERDETEKSI!</h2>
    <p class="text-gray-300 mb-8 max-w-lg text-lg">
        Anda terdeteksi meninggalkan halaman ujian (Split Screen / Pindah Tab / Minimize).
    </p>
    
    <div class="bg-red-900/40 border-2 border-red-500 p-6 rounded-2xl mb-8 transform scale-110">
        <p class="text-sm uppercase tracking-widest text-red-300 mb-2 font-bold">Ujian Terkunci Dalam</p>
        <div class="text-7xl font-mono font-bold text-white tracking-tighter" id="countdown-timer">00</div>
        <p class="text-xs text-gray-400 mt-2">Detik</p>
    </div>

    <button onclick="kembaliKeUjian()" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-transform hover:scale-105 active:scale-95 text-lg">
        SAYA MENGERTI, KEMBALI MENGERJAKAN
    </button>
</div>

<div class="p-4 sm:ml-64 h-screen flex flex-col bg-gray-50 dark:bg-slate-900" id="exam-container">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 bg-white dark:bg-slate-800 p-4 rounded-xl shadow border border-gray-200 dark:border-slate-700 gap-4">
        <div class="w-full md:w-auto">
            <h1 class="text-lg font-bold text-gray-800 dark:text-white leading-tight"><?= $jadwal->judul_ujian ?></h1>
            <div class="flex flex-wrap items-center gap-3 mt-1">
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded">
                    <?= $jadwal->nama_mapel ?>
                </span>
                <?php if($jadwal->setting_strict == 1): ?>
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded border border-red-200">
                        <i class="fas fa-shield-alt mr-1"></i> MODE KETAT
                    </span>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded border border-yellow-200">
                        Sisa Pelanggaran: <b id="sisa-nyawa"><?= $jadwal->setting_max_violation - $sesi->jml_pelanggaran ?></b>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
            <div class="text-right mr-2 hidden md:block">
                <p class="text-[10px] text-gray-500 uppercase font-bold">Sisa Waktu</p>
            </div>
            <div id="main-timer" class="bg-gray-800 dark:bg-slate-950 text-white px-5 py-2.5 rounded-lg font-mono font-bold text-2xl shadow-lg border border-gray-600 tracking-widest min-w-[140px] text-center">
                --:--:--
            </div>
            
            <button onclick="konfirmasiSelesai()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg font-bold text-sm shadow transition-colors flex items-center">
                <i class="fas fa-flag-checkered mr-2"></i> SELESAI
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto bg-white dark:bg-slate-800 rounded-xl shadow p-6 border dark:border-slate-700 relative custom-scrollbar">
        <form id="form-ujian">
            <?php foreach($soal as $index => $s): ?>
                <div class="soal-item <?= $index == 0 ? 'soal-active' : 'soal-hidden' ?>" id="soal-index-<?= $index ?>" data-nomor="<?= $index + 1 ?>">
                    
                    <div class="flex gap-4 mb-6">
                        <span class="bg-blue-600 text-white w-10 h-10 flex items-center justify-center rounded-lg font-bold flex-shrink-0 text-lg shadow-md">
                            <?= $index + 1 ?>
                        </span>
                        <div class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed w-full">
                            <?= $s['pertanyaan'] ?>
                            
                            <?php if(!empty($s['file_gambar'])): ?>
                                <div class="mt-4">
                                    <img src="<?= base_url('uploads/bank_soal/' . $s['file_gambar']) ?>" class="max-w-full md:max-w-md rounded-lg border shadow-sm hover:scale-105 transition-transform cursor-zoom-in" onclick="window.open(this.src)">
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($s['file_audio'])): ?>
                                <div class="mt-4 bg-gray-100 dark:bg-slate-700 p-3 rounded-full w-fit">
                                    <audio controls class="h-8 w-64">
                                        <source src="<?= base_url('uploads/bank_soal/' . $s['file_audio']) ?>" type="audio/mpeg">
                                    </audio>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="space-y-3 pl-0 md:pl-14">
                        <?php foreach($s['opsi'] as $opsi): ?>
                            <label class="group flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 dark:hover:bg-slate-700 dark:hover:border-slate-500 transition-all relative overflow-hidden">
                                <input type="radio" name="jawaban_<?= $s['id'] ?>" value="<?= $opsi['id'] ?>" 
                                       class="peer sr-only"
                                       onchange="simpanJawaban(<?= $s['id'] ?>, '<?= $opsi['id'] ?>')"
                                       <?= ($s['id_opsi'] == $opsi['id']) ? 'checked' : '' ?>>
                                
                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center mr-4 transition-all">
                                    <div class="w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                
                                <span class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-gray-900 dark:group-hover:text-white">
                                    <?= $opsi['teks_opsi'] ?>
                                </span>

                                <div class="absolute inset-0 border-2 border-blue-600 rounded-xl opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
    </div>

    <div class="mt-4 bg-white dark:bg-slate-800 p-4 rounded-xl shadow border dark:border-slate-700">
        <div class="flex justify-between items-center">
            <button onclick="prevSoal()" id="btn-prev" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center" disabled>
                <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
            </button>
            
            <div class="flex gap-2">
                <label class="flex items-center gap-2 cursor-pointer bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-4 py-3 rounded-lg font-bold transition select-none">
                    <input type="checkbox" id="chk-ragu" onchange="toggleRagu()" class="w-5 h-5 text-yellow-500 rounded focus:ring-yellow-500">
                    <span>Ragu-ragu</span>
                </label>
            </div>

            <button onclick="nextSoal()" id="btn-next" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition flex items-center">
                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- 1. KONFIGURASI DARI PHP ---
    const CONFIG = {
        isStrict: <?= $jadwal->setting_strict ?>, // 1 atau 0
        afkTimeout: <?= $jadwal->setting_afk_timeout ?? 10 ?>, // Detik toleransi
        idUjianSiswa: <?= $sesi->id ?>,
        endTime: new Date("<?= $sesi->waktu_selesai_seharusnya ?>").getTime(),
        totalSoal: <?= count($soal) ?>
    };

    let currentIndex = 0;
    
    // Variabel Mode Ketat
    let warningTimer = null;
    let countdownVal = CONFIG.afkTimeout;
    let isWarningActive = false;

    // --- 2. LOGIKA TIMER UTAMA ---
    const timerInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = CONFIG.endTime - now;

        if (distance < 0) {
            clearInterval(timerInterval);
            document.getElementById("main-timer").innerHTML = "00:00:00";
            selesaiUjian(true); // Auto Submit
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("main-timer").innerHTML = 
            (hours < 10 ? "0" + hours : hours) + ":" +
            (minutes < 10 ? "0" + minutes : minutes) + ":" +
            (seconds < 10 ? "0" + seconds : seconds);
    }, 1000);


    // --- 3. NAVIGASI SOAL ---
    function showSoal(index) {
        // Validasi Index
        if (index < 0 || index >= CONFIG.totalSoal) return;

        // Hide Semua
        document.querySelectorAll('.soal-item').forEach(el => {
            el.classList.remove('soal-active');
            el.classList.add('soal-hidden');
        });

        // Show Target
        const target = document.getElementById(`soal-index-${index}`);
        target.classList.remove('soal-hidden');
        target.classList.add('soal-active');

        // Update State
        currentIndex = index;
        
        // Update Button State
        document.getElementById('btn-prev').disabled = (index === 0);
        
        const btnNext = document.getElementById('btn-next');
        if (index === CONFIG.totalSoal - 1) {
            btnNext.innerHTML = 'Selesai <i class="fas fa-check ml-2"></i>';
            btnNext.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            btnNext.classList.add('bg-green-600', 'hover:bg-green-700');
            btnNext.setAttribute('onclick', 'konfirmasiSelesai()');
        } else {
            btnNext.innerHTML = 'Selanjutnya <i class="fas fa-arrow-right ml-2"></i>';
            btnNext.classList.remove('bg-green-600', 'hover:bg-green-700');
            btnNext.classList.add('bg-blue-600', 'hover:bg-blue-700');
            btnNext.setAttribute('onclick', 'nextSoal()');
        }

        // Cek Status Ragu (Disini bisa ambil dari database kalau mau persist)
        // document.getElementById('chk-ragu').checked = ... 
    }

    function nextSoal() { showSoal(currentIndex + 1); }
    function prevSoal() { showSoal(currentIndex - 1); }

    
    // --- 4. SIMPAN JAWABAN (AJAX) ---
    function simpanJawaban(idJawabanTabel, idOpsi) {
        // Kirim ke Server
        const formData = new FormData();
        formData.append('id_jawaban_siswa', idJawabanTabel);
        formData.append('id_opsi', idOpsi);

        fetch('<?= base_url('siswa/ujian/simpanJawaban') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).catch(err => console.error(err));
    }

    function toggleRagu() {
        // Implementasi logika ragu-ragu (bisa kirim ke server juga)
        const isRagu = document.getElementById('chk-ragu').checked ? 1 : 0;
        // fetch...
    }


    // --- 5. LOGIKA MODE KETAT (ANTI-CHEAT) ---
    if (CONFIG.isStrict) {
        // A. Deteksi Pindah Tab
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) triggerWarning();
        });

        // B. Deteksi Blur (Klik luar / Split Screen)
        window.addEventListener("blur", () => {
            triggerWarning();
        });

        // C. Cegah Tombol Terlarang
        document.addEventListener('contextmenu', e => e.preventDefault()); // Klik Kanan
        document.addEventListener('keydown', e => {
            // F12, Ctrl+Shift+I, Alt+Tab, PrintScreen
            if(e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I') || e.altKey) {
                e.preventDefault();
            }
        });
    }

    function triggerWarning() {
        if (isWarningActive) return; // Jangan tumpuk
        isWarningActive = true;
        countdownVal = CONFIG.afkTimeout;

        // Tampilkan Overlay
        const overlay = document.getElementById('warning-overlay');
        overlay.style.display = 'flex';
        document.getElementById('exam-container').classList.add('blur-content');
        document.getElementById('countdown-timer').innerText = countdownVal;

        // Hitung Mundur
        warningTimer = setInterval(() => {
            countdownVal--;
            document.getElementById('countdown-timer').innerText = countdownVal < 10 ? "0" + countdownVal : countdownVal;

            if (countdownVal <= 0) {
                clearInterval(warningTimer);
                laporServer('timeout'); // Waktu Habis -> LOCK
            }
        }, 1000);
    }

    function kembaliKeUjian() {
        if (countdownVal > 0) {
            clearInterval(warningTimer);
            laporServer('violation'); // Lapor Pelanggaran Biasa
            
            // Reset Tampilan
            document.getElementById('warning-overlay').style.display = 'none';
            document.getElementById('exam-container').classList.remove('blur-content');
            isWarningActive = false;
            
            // Paksa Fullscreen
            document.documentElement.requestFullscreen().catch(()=>{});
        }
    }

    function laporServer(jenis) {
        const formData = new FormData();
        formData.append('id_ujian_siswa', CONFIG.idUjianSiswa);
        formData.append('jenis', jenis);

        fetch('<?= base_url('siswa/ujian/catatPelanggaran') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'locked') {
                Swal.fire({
                    icon: 'error',
                    title: 'UJIAN TERKUNCI',
                    text: data.msg,
                    allowOutsideClick: false,
                    confirmButtonText: 'Hubungi Pengawas'
                }).then(() => location.reload()); // Reload ke halaman Locked
            } 
            else if (data.status === 'kicked') {
                Swal.fire({
                    icon: 'error',
                    title: 'DISKUALIFIKASI',
                    text: data.msg,
                    allowOutsideClick: false,
                    confirmButtonText: 'Keluar'
                }).then(() => window.location.href = '<?= base_url('siswa/ujian') ?>');
            }
            else if (data.status === 'warning') {
                // Update sisa nyawa di UI
                const el = document.getElementById('sisa-nyawa');
                if(el) el.innerText = data.sisa_nyawa;
            }
        });
    }


    // --- 6. SELESAI UJIAN ---
    function konfirmasiSelesai() {
        Swal.fire({
            title: 'Akhiri Ujian?',
            text: "Pastikan semua jawaban sudah terisi. Anda tidak bisa kembali setelah ini.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                selesaiUjian(false);
            }
        });
    }

    function selesaiUjian(auto = false) {
        if(auto) {
            Swal.fire({
                title: 'Waktu Habis!',
                text: 'Jawaban Anda akan disimpan otomatis.',
                icon: 'warning',
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }

        const formData = new FormData();
        formData.append('id_ujian_siswa', CONFIG.idUjianSiswa);

        fetch('<?= base_url('siswa/ujian/selesaiUjian') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = data.redirect;
            } else {
                Swal.fire('Gagal', data.message, 'error');
            }
        })
        .catch(err => Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error'));
    }

</script>

<?= $this->endSection() ?>