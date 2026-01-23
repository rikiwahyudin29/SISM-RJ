<?= $this->extend('layout/template_ujian') ?>
<?= $this->section('content') ?>

<style>
    body { user-select: none; -webkit-user-select: none; -moz-user-select: none; }
    .blur-content { filter: blur(8px); pointer-events: none; }
    #warning-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95); z-index: 9999;
        display: none; flex-direction: column; justify-content: center; align-items: center;
        color: white; text-align: center;
    }
    .soal-active { display: block; }
    .soal-hidden { display: none; }
</style>

<div id="warning-overlay">
    <div class="text-6xl text-red-500 mb-6"><i class="fas fa-exclamation-triangle animate-pulse"></i></div>
    <h2 class="text-3xl font-bold mb-2 text-white">PELANGGARAN TERDETEKSI!</h2>
    <p class="text-gray-300 mb-8 max-w-lg text-lg">Anda terdeteksi meninggalkan halaman ujian.</p>
    <div class="bg-red-900/40 border-2 border-red-500 p-6 rounded-2xl mb-8 transform scale-110">
        <p class="text-sm uppercase tracking-widest text-red-300 mb-2 font-bold">Ujian Terkunci Dalam</p>
        <div class="text-7xl font-mono font-bold text-white tracking-tighter" id="countdown-timer">00</div>
        <p class="text-xs text-gray-400 mt-2">Detik</p>
    </div>
    <button onclick="kembaliKeUjian()" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg">KEMBALI MENGERJAKAN</button>
</div>

<div class="p-4 sm:ml-64 h-screen flex flex-col bg-gray-50 dark:bg-slate-900" id="exam-container">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 bg-white dark:bg-slate-800 p-4 rounded-xl shadow border border-gray-200 dark:border-slate-700 gap-4">
        <div class="w-full md:w-auto">
            <h1 class="text-lg font-bold text-gray-800 dark:text-white leading-tight"><?= $jadwal->judul_ujian ?></h1>
            <div class="flex flex-wrap items-center gap-3 mt-1">
                <span class="text-xs bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded"><?= $jadwal->nama_mapel ?></span>
                <?php if($jadwal->setting_strict == 1): ?>
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded border border-red-200">MODE KETAT</span>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded border border-yellow-200">Sisa Nyawa: <b id="sisa-nyawa"><?= $jadwal->setting_max_violation - $sesi->jml_pelanggaran ?></b></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
            <div id="main-timer" class="bg-gray-800 dark:bg-slate-950 text-white px-5 py-2.5 rounded-lg font-mono font-bold text-2xl shadow-lg border border-gray-600 tracking-widest min-w-[140px] text-center">--:--:--</div>
            <button onclick="konfirmasiSelesai()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg font-bold text-sm shadow flex items-center"><i class="fas fa-flag-checkered mr-2"></i> SELESAI</button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto bg-white dark:bg-slate-800 rounded-xl shadow p-6 border dark:border-slate-700 relative custom-scrollbar">
        <form id="form-ujian">
            <?php foreach($soal as $index => $s): 
                // --- [PERBAIKAN KUNCI UTAMA] ---
                // Kita gunakan 'id_soal' (Foreign Key) sebagai identitas, BUKAN 'id' (Primary Key tabel jawaban)
                // Ini agar sinkron dengan Controller simpanJawaban dan selesaiUjian
                $idSoal = $s['id_soal']; 
            ?>
                <div class="soal-item <?= $index == 0 ? 'soal-active' : 'soal-hidden' ?>" id="soal-index-<?= $index ?>">
                    
                    <div class="flex gap-4 mb-6">
                        <span class="bg-blue-600 text-white w-10 h-10 flex items-center justify-center rounded-lg font-bold flex-shrink-0 text-lg shadow-md"><?= $index + 1 ?></span>
                        <div class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed w-full">
                            <?= $s['pertanyaan'] ?>
                            <?php if(!empty($s['file_gambar'])): ?>
                                <div class="mt-4"><img src="<?= base_url('uploads/bank_soal/' . $s['file_gambar']) ?>" class="max-w-full md:max-w-md rounded-lg border shadow-sm" onclick="window.open(this.src)"></div>
                            <?php endif; ?>
                            <?php if(!empty($s['file_audio'])): ?>
                                <div class="mt-4 bg-gray-100 dark:bg-slate-700 p-3 rounded-full w-fit"><audio controls class="h-8 w-64"><source src="<?= base_url('uploads/bank_soal/' . $s['file_audio']) ?>" type="audio/mpeg"></audio></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pl-0 md:pl-14 space-y-4">
                        
                        <?php if ($s['tipe_soal'] == 'PG'): ?>
                            <?php foreach($s['opsi'] as $opsi): ?>
                                <label class="group flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 dark:hover:bg-slate-700 dark:hover:border-slate-500 transition-all">
                                    <input type="radio" name="jawaban_<?= $idSoal ?>" value="<?= $opsi['id'] ?>" class="peer sr-only"
                                           onchange="simpanJawaban(<?= $idSoal ?>, 'pg')"
                                           <?= (isset($s['id_opsi']) && $s['id_opsi'] == $opsi['id']) ? 'checked' : '' ?>>
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-full peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center mr-4 transition-all">
                                        <div class="w-2.5 h-2.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <span class="text-gray-700 dark:text-gray-300 font-medium"><?= $opsi['teks_opsi'] ?></span>
                                </label>
                            <?php endforeach; ?>

                        <?php elseif ($s['tipe_soal'] == 'PG_KOMPLEKS'): 
                            $jawabanSiswaArr = json_decode($s['jawaban_siswa'] ?? '[]', true);
                            if(!is_array($jawabanSiswaArr)) $jawabanSiswaArr = [];
                        ?>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg text-sm text-yellow-800 dark:text-yellow-200 mb-2 border border-yellow-200 dark:border-yellow-700">
                                <i class="fas fa-info-circle mr-1"></i> Pilih lebih dari satu jawaban.
                            </div>
                            <?php foreach($s['opsi'] as $opsi): ?>
                                <label class="group flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-blue-50 dark:hover:bg-slate-700 transition-all">
                                    <input type="checkbox" name="jawaban_<?= $idSoal ?>[]" value="<?= $opsi['id'] ?>" class="peer sr-only chk-kompleks-<?= $idSoal ?>"
                                           onchange="simpanJawaban(<?= $idSoal ?>, 'kompleks')"
                                           <?= in_array($opsi['id'], $jawabanSiswaArr) ? 'checked' : '' ?>>
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-md peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center mr-4 transition-all">
                                        <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                    <span class="text-gray-700 dark:text-gray-300 font-medium"><?= $opsi['teks_opsi'] ?></span>
                                </label>
                            <?php endforeach; ?>

                        <?php elseif ($s['tipe_soal'] == 'MENJODOHKAN'): 
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
                                                        onchange="updateMatchAnswer(<?= $idSoal ?>)">
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

                        <?php elseif ($s['tipe_soal'] == 'ISIAN_SINGKAT'): ?>
                            <div class="relative">
                                <p class="text-sm text-gray-500 mb-2 dark:text-gray-400">Jawaban Singkat:</p>
                                <input type="text" class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-lg dark:bg-slate-800 dark:text-white dark:border-slate-600 transition-all" 
                                       placeholder="Ketik jawaban singkat disini..."
                                       value="<?= $s['jawaban_siswa'] ?? '' ?>"
                                       onblur="simpanJawaban(<?= $idSoal ?>, 'essai')"
                                       id="input_essai_<?= $idSoal ?>">
                                <div class="absolute bottom-1/2 right-4 transform translate-y-1/2 text-gray-400"><i class="fas fa-pen"></i></div>
                            </div>

                        <?php elseif ($s['tipe_soal'] == 'URAIAN'): ?>
                            <div class="relative">
                                <p class="text-sm text-gray-500 mb-2 dark:text-gray-400">Jawaban Uraian:</p>
                                <textarea class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-lg dark:bg-slate-800 dark:text-white dark:border-slate-600 transition-all" 
                                          rows="6" 
                                          placeholder="Ketik jawaban lengkap disini..."
                                          onblur="simpanJawaban(<?= $idSoal ?>, 'essai')"
                                          id="input_essai_<?= $idSoal ?>"><?= $s['jawaban_siswa'] ?? '' ?></textarea>
                                <div class="absolute bottom-3 right-3 text-xs text-gray-400">Tersimpan otomatis saat kursor keluar</div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </form>
    </div>

    <div class="mt-4 bg-white dark:bg-slate-800 p-4 rounded-xl shadow border dark:border-slate-700 flex justify-between items-center">
        <button onclick="prevSoal()" id="btn-prev" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 disabled:opacity-50" disabled><i class="fas fa-arrow-left mr-2"></i> Sebelumnya</button>
        <button onclick="nextSoal()" id="btn-next" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 flex items-center">Selanjutnya <i class="fas fa-arrow-right ml-2"></i></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const CONFIG = {
        isStrict: <?= $jadwal->setting_strict ?>, 
        afkTimeout: <?= $jadwal->setting_afk_timeout ?? 10 ?>, 
        idUjianSiswa: <?= $sesi->id ?>,
        endTime: new Date("<?= $sesi->waktu_selesai_seharusnya ?>").getTime(),
        totalSoal: <?= count($soal) ?>
    };

    let currentIndex = 0;
    
    // TIMER
    const timerInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = CONFIG.endTime - now;
        if (distance < 0) { clearInterval(timerInterval); selesaiUjian(true); return; }
        const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((distance % (1000 * 60)) / 1000);
        document.getElementById("main-timer").innerHTML = `${h<10?'0'+h:h}:${m<10?'0'+m:m}:${s<10?'0'+s:s}`;
    }, 1000);

    // NAVIGASI
    function showSoal(index) {
        if (index < 0 || index >= CONFIG.totalSoal) return;
        document.querySelectorAll('.soal-item').forEach(el => { el.classList.remove('soal-active'); el.classList.add('soal-hidden'); });
        document.getElementById(`soal-index-${index}`).classList.remove('soal-hidden');
        document.getElementById(`soal-index-${index}`).classList.add('soal-active');
        currentIndex = index;
        document.getElementById('btn-prev').disabled = (index === 0);
        
        const btnNext = document.getElementById('btn-next');
        if (index === CONFIG.totalSoal - 1) {
            btnNext.innerHTML = 'Selesai <i class="fas fa-check ml-2"></i>';
            btnNext.className = "px-6 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 flex items-center";
            btnNext.onclick = konfirmasiSelesai;
        } else {
            btnNext.innerHTML = 'Selanjutnya <i class="fas fa-arrow-right ml-2"></i>';
            btnNext.className = "px-6 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 flex items-center";
            btnNext.onclick = nextSoal;
        }
    }
    function nextSoal() { showSoal(currentIndex + 1); }
    function prevSoal() { showSoal(currentIndex - 1); }

    function updateMatchAnswer(idSoal) {
        let answers = {};
        const selects = document.querySelectorAll(`.match-select-${idSoal}`);
        selects.forEach(sel => {
            const left = sel.getAttribute('data-left');
            const right = sel.value;
            if(right) { answers[left] = right; }
        });
        const jsonStr = JSON.stringify(answers);
        document.getElementById(`input_essai_${idSoal}`).value = jsonStr;
        simpanJawaban(idSoal, 'essai');
    }

    // --- FUNGSI SIMPAN (PASTIKAN PAKE site_url AGAR TIDAK 404) ---
    function simpanJawaban(idSoal, tipe) {
        const formData = new FormData();
        formData.append('id_ujian_siswa', CONFIG.idUjianSiswa);
        formData.append('id_soal', idSoal); 

        if (tipe === 'pg') {
            const val = document.querySelector(`input[name="jawaban_${idSoal}"]:checked`)?.value;
            if(val) formData.append('id_opsi', val);
        } 
        else if (tipe === 'kompleks') {
            const checked = [];
            document.querySelectorAll(`.chk-kompleks-${idSoal}:checked`).forEach(el => checked.push(el.value));
            checked.forEach(val => formData.append('id_opsi[]', val));
            if(checked.length === 0) formData.append('id_opsi', ''); 
        } 
        else if (tipe === 'essai') {
            const val = document.getElementById(`input_essai_${idSoal}`).value;
            formData.append('jawaban_isian', val);
        }

        fetch('<?= site_url('siswa/ujian/simpanJawaban') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).catch(console.error);
    }

    // STRICT MODE
    let warningTimer = null, countdownVal = CONFIG.afkTimeout, isWarningActive = false;
    if (CONFIG.isStrict) {
        document.addEventListener("visibilitychange", () => { if (document.hidden) triggerWarning(); });
        window.addEventListener("blur", () => { triggerWarning(); });
        document.addEventListener('contextmenu', e => e.preventDefault());
    }
    function triggerWarning() {
        if (isWarningActive) return;
        isWarningActive = true;
        countdownVal = CONFIG.afkTimeout;
        document.getElementById('warning-overlay').style.display = 'flex';
        document.getElementById('exam-container').classList.add('blur-content');
        warningTimer = setInterval(() => {
            countdownVal--;
            document.getElementById('countdown-timer').innerText = countdownVal < 10 ? "0" + countdownVal : countdownVal;
            if (countdownVal <= 0) { clearInterval(warningTimer); laporServer('timeout'); }
        }, 1000);
    }
    function kembaliKeUjian() {
        if (countdownVal > 0) {
            clearInterval(warningTimer);
            laporServer('violation');
            document.getElementById('warning-overlay').style.display = 'none';
            document.getElementById('exam-container').classList.remove('blur-content');
            isWarningActive = false;
        }
    }
    function laporServer(jenis) {
        const fd = new FormData();
        fd.append('id_ujian_siswa', CONFIG.idUjianSiswa);
        fd.append('jenis', jenis);
        fetch('<?= site_url('siswa/ujian/catatPelanggaran') ?>', { method: 'POST', body: fd, headers: {'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>r.json()).then(d=>{
            if(d.status === 'locked') Swal.fire('Terkunci', d.msg, 'error').then(()=>location.reload());
            else if(d.status === 'kicked') Swal.fire('Diskualifikasi', d.msg, 'error').then(()=>window.location.href='<?= base_url('siswa/ujian') ?>');
            else if(d.status === 'warning') document.getElementById('sisa-nyawa').innerText = d.sisa_nyawa;
        });
    }

    // SELESAI UJIAN
    function konfirmasiSelesai() {
        Swal.fire({ title: 'Selesai?', text: "Yakin ingin mengumpulkan jawaban?", icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Kumpulkan' })
        .then((r) => { if(r.isConfirmed) selesaiUjian(false); });
    }

    function selesaiUjian(auto) {
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
        const fd = new FormData();
        fd.append('id_ujian_siswa', CONFIG.idUjianSiswa);
        fetch('<?= site_url('siswa/ujian/selesaiUjian') ?>', { method: 'POST', body: fd, headers: {'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>r.json()).then(d=>{
            if(d.status==='success') window.location.href = d.redirect;
            else Swal.fire('Gagal', d.message, 'error');
        });
    }
</script>

<?= $this->endSection() ?>