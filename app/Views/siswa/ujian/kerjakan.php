<?= $this->extend('layout/template_ujian') ?>
<?= $this->section('content') ?>

<style>
    body { user-select: none; -webkit-user-select: none; -moz-user-select: none; }
    .blur-content { filter: blur(8px); pointer-events: none; }
    
    /* Scrollbar Custom */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #555; }

    /* Navigasi Soal Grid */
    .nav-grid-btn {
        width: 40px; height: 40px; 
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px; font-weight: bold; font-size: 14px;
        cursor: pointer; transition: all 0.2s;
        border: 2px solid #e5e7eb;
    }
    .nav-active { border-color: #2563eb; transform: scale(1.1); box-shadow: 0 0 10px rgba(37,99,235,0.3); }
    .nav-filled { background-color: #22c55e; color: white; border-color: #22c55e; } /* Hijau */
    .nav-ragu { background-color: #eab308; color: white; border-color: #eab308; }   /* Kuning */
    .nav-empty { background-color: white; color: #374151; }

    /* Overlay Warning */
    #warning-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95); z-index: 9999;
        display: none; flex-direction: column; justify-content: center; align-items: center;
        color: white; text-align: center;
    }
</style>

<div id="warning-overlay">
    <div class="text-6xl text-red-500 mb-6"><i class="fas fa-exclamation-triangle animate-pulse"></i></div>
    <h2 class="text-3xl font-bold mb-2 text-white">PELANGGARAN TERDETEKSI!</h2>
    <p class="text-gray-300 mb-8 max-w-lg text-lg">Anda terdeteksi meninggalkan halaman ujian.</p>
    
    <div class="bg-red-900/40 border-2 border-red-500 p-6 rounded-2xl mb-8 transform scale-110">
        <p class="text-sm uppercase tracking-widest text-red-300 mb-2 font-bold">Ujian Terkunci Dalam</p>
        <div class="text-7xl font-mono font-bold text-white tracking-tighter" id="countdown-timer">--</div>
        <p class="text-xs text-gray-400 mt-2">Detik</p>
    </div>

    <button onclick="kembaliKeUjian()" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-transform active:scale-95">
        KEMBALI MENGERJAKAN
    </button>
</div>

<div class="flex h-screen bg-gray-50 dark:bg-slate-900 overflow-hidden" id="exam-container">

    <div class="flex-1 flex flex-col h-full relative">
        
        <div class="bg-white dark:bg-slate-800 p-4 shadow-md flex justify-between items-center z-20 border-b dark:border-slate-700">
            <div>
                <h1 class="font-bold text-gray-800 dark:text-white truncate max-w-[200px] md:max-w-md"><?= esc($jadwal->judul_ujian) ?></h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-500 bg-gray-100 dark:bg-slate-700 px-2 py-0.5 rounded"><?= esc($jadwal->nama_mapel) ?></span>
                    <?php if($jadwal->setting_strict == 1): ?>
                        <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded font-bold border border-red-200">STRICT MODE</span>
                        <span class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded font-bold border border-yellow-200">Nyawa: <b id="sisa-nyawa"><?= $jadwal->setting_max_violation - $sesi->jml_pelanggaran ?></b></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div id="main-timer" class="bg-gray-900 text-white px-4 py-2 rounded-lg font-mono font-bold text-xl shadow">--:--:--</div>
                <button onclick="toggleNav()" class="lg:hidden bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-200">
                    <i class="fas fa-th"></i>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-6 custom-scrollbar relative" id="soal-container">
            <form id="form-ujian">
                <?php foreach($soal as $index => $s): 
                    $idSoal = $s['id_soal_real']; 
                    // Inisialisasi status untuk JS
                    $isRagu = (isset($s['ragu']) && $s['ragu'] == 1) ? 1 : 0;
                    $isIsi  = (!empty($s['jawaban_siswa']) || !empty($s['id_opsi'])) ? 1 : 0;
                ?>
                    <div class="soal-item <?= $index == 0 ? '' : 'hidden' ?>" id="soal-index-<?= $index ?>" 
                         data-index="<?= $index ?>" data-id="<?= $idSoal ?>" 
                         data-ragu="<?= $isRagu ?>" data-isi="<?= $isIsi ?>">
                        
                        <div class="flex justify-between items-start mb-6 border-b pb-4 dark:border-slate-700">
                            <span class="bg-blue-600 text-white w-10 h-10 flex items-center justify-center rounded-lg font-bold text-lg shadow">
                                <?= $index + 1 ?>
                            </span>
                            <label class="flex items-center gap-2 cursor-pointer bg-yellow-50 dark:bg-yellow-900/20 px-3 py-1.5 rounded-lg border border-yellow-200 hover:bg-yellow-100 transition-colors">
                                <input type="checkbox" class="w-5 h-5 text-yellow-500 rounded focus:ring-yellow-500" 
                                       id="check-ragu-<?= $index ?>" 
                                       onchange="setRagu(<?= $index ?>, <?= $idSoal ?>)"
                                       <?= $isRagu ? 'checked' : '' ?>>
                                <span class="text-sm font-bold text-yellow-700 dark:text-yellow-400">Ragu-ragu</span>
                            </label>
                        </div>

                        <div class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed mb-6">
                            <?= $s['pertanyaan'] ?>
                            <?php if(!empty($s['file_gambar'])): ?>
                                <img src="<?= base_url('uploads/bank_soal/' . $s['file_gambar']) ?>" class="mt-4 max-w-full md:max-w-md rounded-lg shadow-sm" onclick="window.open(this.src)">
                            <?php endif; ?>
                            <?php if(!empty($s['file_audio'])): ?>
                                <audio controls class="mt-4 w-full max-w-md"><source src="<?= base_url('uploads/bank_soal/' . $s['file_audio']) ?>"></audio>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-3">
                            <?php if ($s['tipe_soal'] == 'PG'): ?>
                                <?php foreach($s['opsi'] as $opsi): ?>
                                    <label class="group flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-blue-50 dark:hover:bg-slate-800 transition-all 
                                        <?= (isset($s['id_opsi']) && $s['id_opsi'] == $opsi['id']) ? 'border-blue-500 bg-blue-50 dark:bg-slate-800' : 'border-gray-200 dark:border-slate-600' ?>">
                                        <input type="radio" name="jawaban_<?= $idSoal ?>" value="<?= $opsi['id'] ?>" class="peer sr-only"
                                               onchange="simpanJawaban(<?= $idSoal ?>, 'pg', <?= $index ?>)"
                                               <?= (isset($s['id_opsi']) && $s['id_opsi'] == $opsi['id']) ? 'checked' : '' ?>>
                                        <div class="w-6 h-6 border-2 border-gray-400 rounded-full peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center mr-4">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300 font-medium"><?= $opsi['teks_opsi'] ?></span>
                                    </label>
                                <?php endforeach; ?>

                            <?php elseif ($s['tipe_soal'] == 'PG_KOMPLEKS'): 
                                $jawabanSiswaArr = json_decode($s['jawaban_siswa'] ?? '[]', true); 
                            ?>
                                <?php foreach($s['opsi'] as $opsi): ?>
                                    <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-blue-50 transition-all border-gray-200">
                                        <input type="checkbox" name="jawaban_<?= $idSoal ?>[]" value="<?= $opsi['id'] ?>" class="peer sr-only chk-kompleks-<?= $idSoal ?>"
                                               onchange="simpanJawaban(<?= $idSoal ?>, 'kompleks', <?= $index ?>)"
                                               <?= in_array($opsi['id'], is_array($jawabanSiswaArr)?$jawabanSiswaArr:[]) ? 'checked' : '' ?>>
                                        <div class="w-6 h-6 border-2 border-gray-400 rounded-md peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center mr-4">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                        </div>
                                        <span class="text-gray-700"><?= $opsi['teks_opsi'] ?></span>
                                    </label>
                                <?php endforeach; ?>

                            <?php elseif ($s['tipe_soal'] == 'MENJODOHKAN'): 
                                // --- FITUR MENJODOHKAN (RESTORED) ---
                                $savedMatch = json_decode($s['jawaban_siswa'] ?? '{}', true);
                                $pilihanKanan = [];
                                foreach($s['opsi'] as $o) { $pilihanKanan[] = $o['teks_opsi']; }
                                shuffle($pilihanKanan); 
                            ?>
                                <div class="bg-blue-50 dark:bg-slate-700/50 p-5 rounded-xl border border-blue-200 dark:border-slate-600">
                                    <p class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-4 flex items-center">
                                        <i class="fas fa-link mr-2"></i> Pasangkan pernyataan kiri dengan jawaban kanan:
                                    </p>
                                    <div class="grid gap-3">
                                        <?php foreach($s['opsi'] as $pair): ?>
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white dark:bg-slate-800 p-3 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm">
                                                <div class="w-full sm:w-1/2 font-medium text-gray-700 dark:text-gray-300 mb-2 sm:mb-0 pr-2">
                                                    <?= $pair['kode_opsi'] ?>
                                                </div>
                                                <div class="w-full sm:w-1/2 relative">
                                                    <select class="w-full p-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-500 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block match-select-<?= $idSoal ?>"
                                                            data-left="<?= $pair['kode_opsi'] ?>"
                                                            onchange="updateMatchAnswer(<?= $idSoal ?>, <?= $index ?>)">
                                                        <option value="">-- Pilih Pasangan --</option>
                                                        <?php foreach($pilihanKanan as $pk): ?>
                                                            <option value="<?= $pk ?>" 
                                                                <?= (isset($savedMatch[$pair['kode_opsi']]) && $savedMatch[$pair['kode_opsi']] == $pk) ? 'selected' : '' ?>>
                                                                <?= $pk ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <input type="hidden" id="input_essai_<?= $idSoal ?>" value='<?= $s['jawaban_siswa'] ?? '{}' ?>'>
                                </div>
                            
                            <?php elseif ($s['tipe_soal'] == 'ISIAN_SINGKAT' || $s['tipe_soal'] == 'URAIAN'): ?>
                                <textarea class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-lg dark:bg-slate-800 dark:text-white" 
                                          rows="5" placeholder="Ketik jawaban..." 
                                          onblur="simpanJawaban(<?= $idSoal ?>, 'essai', <?= $index ?>)"
                                          id="input_essai_<?= $idSoal ?>"><?= $s['jawaban_siswa'] ?? '' ?></textarea>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 border-t dark:border-slate-700 flex justify-between items-center z-20">
            <button onclick="prevSoal()" id="btn-prev" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold disabled:opacity-50" disabled>
                <i class="fas fa-arrow-left mr-2"></i> Prev
            </button>
            
            <button onclick="nextSoal()" id="btn-next" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold flex items-center">
                Next <i class="fas fa-arrow-right ml-2"></i>
            </button>
            
            <button onclick="cekSelesai()" id="btn-selesai" class="hidden px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold flex items-center">
                <i class="fas fa-check-circle mr-2"></i> Selesai
            </button>
        </div>
    </div>

    <div id="nav-sidebar" class="fixed inset-y-0 right-0 w-72 bg-white dark:bg-slate-800 shadow-2xl transform translate-x-full lg:translate-x-0 lg:static lg:w-80 transition-transform duration-300 z-30 flex flex-col border-l dark:border-slate-700">
        <div class="p-4 border-b dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900">
            <h3 class="font-bold text-gray-700 dark:text-gray-200"><i class="fas fa-th mr-2"></i> Daftar Soal</h3>
            <button onclick="toggleNav()" class="lg:hidden text-gray-500 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
            <div class="grid grid-cols-5 gap-2" id="grid-nomor">
                </div>
        </div>

        <div class="p-4 border-t dark:border-slate-700 bg-gray-50 dark:bg-slate-900 text-xs text-gray-500 space-y-2">
            <div class="flex items-center gap-2"><div class="w-3 h-3 bg-green-500 rounded"></div> Terjawab</div>
            <div class="flex items-center gap-2"><div class="w-3 h-3 bg-yellow-500 rounded"></div> Ragu-ragu</div>
            <div class="flex items-center gap-2"><div class="w-3 h-3 border border-gray-400 bg-white rounded"></div> Belum Dijawab</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const CONFIG = {
        totalSoal: <?= count($soal) ?>,
        idUjianSiswa: <?= $sesi->id ?>,
        endTime: new Date("<?= $sesi->waktu_selesai_seharusnya ?>").getTime(),
        // Ambil setting dari PHP (Default 15 detik jika null)
        afkTimeout: <?= ($jadwal->setting_afk_timeout && $jadwal->setting_afk_timeout > 0) ? $jadwal->setting_afk_timeout : 15 ?>,
        isStrict: <?= $jadwal->setting_strict ?? 0 ?>
    };

    // State Lokal Soal (Ragu/Isi)
    let stateSoal = [];
    let currentIndex = 0;

    // INIT DATA SAAT LOAD
    document.addEventListener('DOMContentLoaded', () => {
        // Ambil data status awal dari atribut HTML
        for(let i=0; i<CONFIG.totalSoal; i++) {
            const el = document.getElementById(`soal-index-${i}`);
            stateSoal.push({
                index: i,
                id: el.dataset.id,
                ragu: el.dataset.ragu == '1',
                isi: el.dataset.isi == '1'
            });
        }
        renderGrid();
        updateNavButtons();
    });

    // --- LOGIC SIDEBAR & NAVIGASI ---
    function renderGrid() {
        const container = document.getElementById('grid-nomor');
        container.innerHTML = '';
        stateSoal.forEach((s, i) => {
            const btn = document.createElement('div');
            // Tentukan Warna Box
            let cls = 'nav-empty';
            if (s.ragu) cls = 'nav-ragu';
            else if (s.isi) cls = 'nav-filled';
            
            if (i === currentIndex) cls += ' nav-active'; // Border Biru jika sedang aktif

            btn.className = `nav-grid-btn ${cls} dark:text-gray-800`;
            btn.innerText = i + 1;
            btn.onclick = () => showSoal(i);
            container.appendChild(btn);
        });
    }

    function showSoal(idx) {
        // Hide Current
        document.getElementById(`soal-index-${currentIndex}`).classList.add('hidden');
        // Show Next
        document.getElementById(`soal-index-${idx}`).classList.remove('hidden');
        
        currentIndex = idx;
        renderGrid(); 
        updateNavButtons();

        // Di mobile, tutup sidebar setelah klik nomor
        if(window.innerWidth < 1024) document.getElementById('nav-sidebar').classList.add('translate-x-full');
    }

    function nextSoal() { if(currentIndex < CONFIG.totalSoal-1) showSoal(currentIndex+1); }
    function prevSoal() { if(currentIndex > 0) showSoal(currentIndex-1); }
    
    function updateNavButtons() {
        document.getElementById('btn-prev').disabled = (currentIndex === 0);
        
        const isLast = (currentIndex === CONFIG.totalSoal - 1);
        const btnNext = document.getElementById('btn-next');
        const btnSelesai = document.getElementById('btn-selesai');

        if(isLast) {
            btnNext.classList.add('hidden');
            btnSelesai.classList.remove('hidden');
        } else {
            btnNext.classList.remove('hidden');
            btnSelesai.classList.add('hidden');
        }
    }

    function toggleNav() {
        document.getElementById('nav-sidebar').classList.toggle('translate-x-full');
    }

    // --- LOGIC RAGU-RAGU ---
    function setRagu(idx, idSoal) {
        const isChecked = document.getElementById(`check-ragu-${idx}`).checked;
        stateSoal[idx].ragu = isChecked;
        renderGrid();
        
        // Simpan ke server
        const fd = new FormData();
        fd.append('id_ujian_siswa', CONFIG.idUjianSiswa);
        fd.append('id_soal', idSoal);
        fd.append('ragu', isChecked ? 1 : 0);
        
        fetch('<?= site_url('siswa/ujian/simpanJawaban') ?>', { method: 'POST', body: fd });
    }

    // --- LOGIC MENJODOHKAN (DIPANGGIL SAAT PILIHAN BERUBAH) ---
    function updateMatchAnswer(idSoal, idx) {
        let answers = {};
        const selects = document.querySelectorAll(`.match-select-${idSoal}`);
        selects.forEach(sel => {
            const left = sel.getAttribute('data-left');
            const right = sel.value;
            if(right) { answers[left] = right; }
        });
        const jsonStr = JSON.stringify(answers);
        document.getElementById(`input_essai_${idSoal}`).value = jsonStr;
        
        // Simpan sebagai essai
        simpanJawaban(idSoal, 'essai', idx);
    }

    // --- LOGIC SIMPAN JAWABAN (AJAX) ---
    function simpanJawaban(idSoal, tipe, idx) {
        // Update State Lokal
        stateSoal[idx].isi = true;
        
        if (tipe === 'kompleks') {
            const checked = document.querySelectorAll(`.chk-kompleks-${idSoal}:checked`).length;
            stateSoal[idx].isi = (checked > 0);
        } else if (tipe === 'essai') {
            const val = document.getElementById(`input_essai_${idSoal}`).value.trim();
            stateSoal[idx].isi = (val !== '' && val !== '{}');
        }
        
        renderGrid(); // Update warna grid jadi hijau

        // Kirim Data
        const fd = new FormData();
        fd.append('id_ujian_siswa', CONFIG.idUjianSiswa);
        fd.append('id_soal', idSoal);

        if (tipe === 'pg') {
            const val = document.querySelector(`input[name="jawaban_${idSoal}"]:checked`)?.value;
            if(val) fd.append('id_opsi', val);
        } else if (tipe === 'kompleks') {
            const checked = [];
            document.querySelectorAll(`.chk-kompleks-${idSoal}:checked`).forEach(el => checked.push(el.value));
            checked.forEach(val => fd.append('id_opsi[]', val));
            if(checked.length === 0) fd.append('id_opsi', '');
        } else if (tipe === 'essai') {
            const val = document.getElementById(`input_essai_${idSoal}`).value;
            fd.append('jawaban_isian', val);
        }

        fetch('<?= site_url('siswa/ujian/simpanJawaban') ?>', {
            method: 'POST',
            body: fd,
            headers: {'X-Requested-With':'XMLHttpRequest'}
        });
    }

    // --- CEK SELESAI (VALIDASI RAGU) ---
    function cekSelesai() {
        // Cek Ragu
        const adaRagu = stateSoal.some(s => s.ragu === true);
        if (adaRagu) {
            Swal.fire({
                title: 'Belum Bisa Selesai!',
                text: 'Masih ada jawaban Ragu-ragu (Kuning). Silakan cek kembali.',
                icon: 'warning',
                confirmButtonText: 'Baik'
            });
            // Buka sidebar biar kelihatan
            if(window.innerWidth < 1024) document.getElementById('nav-sidebar').classList.remove('translate-x-full');
            return;
        }

        const adaKosong = stateSoal.some(s => !s.isi);
        let msg = "Yakin ingin mengumpulkan jawaban?";
        if (adaKosong) msg = "Masih ada soal yang BELUM DIJAWAB (Putih). Yakin ingin menyelesaikan?";

        Swal.fire({
            title: 'Konfirmasi Selesai',
            text: msg,
            icon: adaKosong ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kumpulkan',
            confirmButtonColor: '#16a34a',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                selesaiUjian(false);
            }
        });
    }

    function selesaiUjian(isAuto) {
        if (!isAuto) Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
        
        const fd = new FormData();
        fd.append('id_ujian_siswa', CONFIG.idUjianSiswa);
        if(isAuto) fd.append('is_auto', '1');

        fetch('<?= site_url('siswa/ujian/selesaiUjian') ?>', { method: 'POST', body: fd })
        .then(r=>r.json()).then(d=>{
            if(d.status==='success') window.location.href = d.redirect;
            else Swal.fire('Gagal', d.message, 'error');
        });
    }

    // --- TIMER MUNDUR ---
    const timerInterval = setInterval(() => {
        const now = new Date().getTime();
        const dist = CONFIG.endTime - now;
        if (dist < 0) { 
            clearInterval(timerInterval); 
            selesaiUjian(true); // Waktu Habis -> Auto Submit
            return; 
        }
        
        const h = Math.floor((dist % (86400000)) / (3600000));
        const m = Math.floor((dist % (3600000)) / 60000);
        const s = Math.floor((dist % 60000) / 1000);
        document.getElementById("main-timer").innerHTML = `${h<10?'0'+h:h}:${m<10?'0'+m:m}:${s<10?'0'+s:s}`;
    }, 1000);

    // --- STRICT MODE & ANTI-REFRESH BUG FIX ---
    let isRefreshing = false;
    window.addEventListener('beforeunload', () => { isRefreshing = true; });

    let warningTimer = null, countdownVal = CONFIG.afkTimeout, isWarningActive = false;

    if (CONFIG.isStrict) {
        document.addEventListener("visibilitychange", () => { if (document.hidden && !isRefreshing) triggerWarning(); });
        window.addEventListener("blur", () => { if (!isRefreshing) triggerWarning(); });
        document.addEventListener('contextmenu', e => e.preventDefault());
    }

    function triggerWarning() {
        if (isWarningActive) return;
        isWarningActive = true;
        countdownVal = CONFIG.afkTimeout;
        document.getElementById('countdown-timer').innerText = countdownVal < 10 ? "0" + countdownVal : countdownVal;
        
        document.getElementById('warning-overlay').style.display = 'flex';
        document.getElementById('exam-container').classList.add('blur-content');

        // Countdown Auto Lock
        warningTimer = setInterval(() => {
            countdownVal--;
            document.getElementById('countdown-timer').innerText = countdownVal < 10 ? "0" + countdownVal : countdownVal;
            if (countdownVal <= 0) { clearInterval(warningTimer); laporServer('timeout'); }
        }, 1000);
    }

    function kembaliKeUjian() {
        if (countdownVal > 0) {
            clearInterval(warningTimer);
            laporServer('violation'); // Cuma lapor, tidak kunci
            document.getElementById('warning-overlay').style.display = 'none';
            document.getElementById('exam-container').classList.remove('blur-content');
            isWarningActive = false;
        }
    }

    function laporServer(jenis) {
        const fd = new FormData();
        fd.append('id_ujian_siswa', CONFIG.idUjianSiswa);
        fd.append('jenis', jenis);
        fetch('<?= site_url('siswa/ujian/catatPelanggaran') ?>', {method:'POST', body:fd})
        .then(r=>r.json()).then(d=>{
            if(d.status === 'locked') {
                Swal.fire({title:'Terkunci!', text:d.msg, icon:'error'}).then(()=>location.reload());
            } else if(d.status === 'kicked') {
                Swal.fire('Diskualifikasi', d.msg, 'error').then(()=>window.location.href='<?= base_url('siswa/ujian') ?>');
            } else if(d.status === 'warning') {
                if(document.getElementById('sisa-nyawa')) document.getElementById('sisa-nyawa').innerText = d.sisa_nyawa;
            }
        });
    }

</script>
<?= $this->endSection() ?>