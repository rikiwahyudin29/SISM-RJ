<?= $this->extend('layout/template_ujian') ?>
<?= $this->section('content') ?>

<?php
// HELPER DETEKSI TIPE SOAL (SESUAI DATABASE BOS)
function deteksiTipe($tipeDb) {
    $t = strtoupper($tipeDb);
    // Tambahkan ISIAN_SINGKAT biar kedeteksi
    if (strpos($t, 'ISIAN') !== false || strpos($t, 'SINGKAT') !== false) return 'ISIAN';
    if (strpos($t, 'JODOH') !== false || strpos($t, 'MENJODOHKAN') !== false) return 'JODOH';
    if (strpos($t, 'KOMPLEKS') !== false) return 'PG_KOMPLEKS';
    if (strpos($t, 'ESSAY') !== false || strpos($t, 'URAIAN') !== false) return 'ESSAY';
    return 'PG';
}

// AMBIL SETTINGAN JADWAL (DEFAULT VALUE JIKA NULL)
$limitPelanggaran = $jadwal->limit_pelanggaran ?? 3; 
$minWaktuMenit = $jadwal->min_waktu ?? 0;
?>

<div id="violationOverlay" class="fixed inset-0 z-[99999] bg-red-900/95 flex flex-col items-center justify-center text-white text-center hidden p-6">
    <i class="fas fa-exclamation-triangle text-6xl mb-4 animate-bounce text-yellow-400"></i>
    <h2 class="text-3xl font-bold mb-2">PELANGGARAN TERDETEKSI!</h2>
    <p class="text-lg mb-2">Aktivitas mencurigakan terdeteksi (Keluar App/Split Screen/Notifikasi).</p>
    
    <div class="text-2xl font-bold mb-6">
        Sisa Kesempatan: <span id="sisaNyawa" class="text-yellow-300 text-4xl"><?= $limitPelanggaran ?></span>
    </div>

    <button onclick="resumeUjian()" class="px-8 py-3 bg-white text-red-900 font-bold rounded-xl shadow-lg hover:bg-gray-200 transition-transform active:scale-95">
        KEMBALI MENGERJAKAN
    </button>
</div>

<div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900 select-none overflow-hidden" id="examApp">

    <div class="flex-none h-16 bg-white dark:bg-gray-800 shadow-sm border-b dark:border-gray-700 flex justify-between items-center px-4 z-40">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-blue-600/20 shadow-lg">
                <span id="no_soal">1</span>
            </div>
            <div>
                <div class="text-[10px] text-gray-400 font-bold uppercase">Sisa Waktu</div>
                <div id="timerDisplay" class="font-mono text-xl font-bold text-red-600 leading-none">--:--</div>
            </div>
        </div>
        
        <div id="minWaktuIndikator" class="hidden text-xs font-bold text-orange-500 bg-orange-100 px-2 py-1 rounded">
            <i class="fas fa-lock"></i> Submit terkunci <span id="sisaMinWaktu"></span> menit
        </div>

        <button onclick="forceFullscreen()" class="p-2 text-gray-500 hover:text-blue-600 transition-colors">
            <i class="fas fa-compress-arrows-alt text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 pb-32 scroll-smooth" id="soalScroll">
        <div class="max-w-3xl mx-auto">
            
            <?php foreach($soal as $index => $s): 
                $tipeReal = deteksiTipe($s['tipe_soal']); 
                
                // Decode jawaban isian jika array (untuk PG Kompleks & Jodoh)
                $jawabanSiswaArr = []; // Default Array kosong
                $jawabanSiswaStr = $s['jawaban_isian'] ?? '';

                if(($tipeReal == 'PG_KOMPLEKS' || $tipeReal == 'JODOH') && !empty($jawabanSiswaStr)) {
                    $decoded = json_decode($jawabanSiswaStr, true);
                    if(is_array($decoded)) $jawabanSiswaArr = $decoded;
                }
            ?>

            <div class="soal-wrapper hidden" id="soal_<?= $index ?>" data-index="<?= $index ?>">
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-4">
                    <div class="flex justify-between items-center mb-3">
                        <span class="px-2 py-1 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider">
                            <?= str_replace('_', ' ', $tipeReal) ?>
                        </span>
                        <span class="text-xs text-gray-400">Bobot: <?= $s['bobot'] ?? 1 ?></span>
                    </div>
                    <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-100 text-lg leading-relaxed">
                        <?= $s['pertanyaan'] ?>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    
                    <?php if($tipeReal == 'ISIAN'): ?>
                        <div class="mb-2 text-sm text-blue-600 font-bold"><i class="fas fa-pen mr-1"></i> Jawaban Singkat</div>
                        <input type="text" 
                            class="w-full p-4 text-lg border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all uppercase" 
                            placeholder="Ketik jawaban singkat..." 
                            value="<?= $s['jawaban_isian'] ?>"
                            autocomplete="off"
                            onblur="simpanIsian(<?= $s['id'] ?>, this.value)">

                    <?php elseif($tipeReal == 'PG_KOMPLEKS'): ?>
                        <div class="mb-3 text-sm bg-blue-50 text-blue-800 p-2 rounded border border-blue-100"><i class="fas fa-check-double mr-1"></i> Pilih lebih dari satu jawaban.</div>
                        <div class="space-y-3">
                            <?php foreach($s['opsi'] as $o): ?>
                                <label class="flex items-center p-4 rounded-xl border-2 border-transparent bg-gray-50 hover:bg-gray-100 cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="checkbox" name="chk_<?= $index ?>[]" value="<?= $o['id'] ?>" 
                                        class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                        onchange="simpanKompleks(<?= $s['id'] ?>, <?= $index ?>)"
                                        <?= in_array($o['id'], $jawabanSiswaArr) ? 'checked' : '' ?>>
                                    <span class="ml-3 text-gray-800 font-medium"><?= $o['teks_opsi'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif($tipeReal == 'JODOH'): ?>
                        <div class="mb-3 text-sm bg-purple-50 text-purple-800 p-2 rounded border border-purple-100">
                            <i class="fas fa-random mr-1"></i> Pasangkan pernyataan kiri dengan kanan.
                        </div>
                        <div class="space-y-4">
                            <?php foreach($s['opsi'] as $o): ?>
                            <div class="flex flex-col gap-2 bg-gray-50 p-4 rounded-xl border border-gray-200">
                                
                                <div class="font-bold text-gray-800 text-sm mb-1">
                                    <?= !empty($o['kode_opsi']) ? $o['kode_opsi'] : 'Pernyataan '.($index+1) ?>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                    
                                    <select name="jodoh_<?= $index ?>[<?= htmlspecialchars($o['kode_opsi']) ?>]" 
                                            class="w-full p-2.5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                            onchange="simpanJodoh(<?= $s['id'] ?>, <?= $index ?>)">
                                        
                                        <option value="">-- Pilih Pasangan --</option>
                                        
                                        <?php foreach($s['opsi'] as $subOpsi): ?>
                                            <option value="<?= $subOpsi['teks_opsi'] ?>">
                                                <?= $subOpsi['teks_opsi'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                        
                                    </select>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif($tipeReal == 'ESSAY'): ?>
                         <div class="mb-2 text-sm text-blue-600 font-bold"><i class="fas fa-align-left mr-1"></i> Jawaban Uraian</div>
                        <textarea class="w-full p-4 text-base border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 min-h-[150px]" 
                            placeholder="Jelaskan jawaban Anda..." 
                            onblur="simpanIsian(<?= $s['id'] ?>, this.value)"><?= $s['jawaban_isian'] ?></textarea>

                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach($s['opsi'] as $o): ?>
                                <label class="flex items-center p-4 rounded-xl border-2 border-transparent bg-gray-50 hover:bg-gray-100 cursor-pointer transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600 has-[:checked]:text-white group">
                                    <input type="radio" name="rad_<?= $index ?>" value="<?= $o['id'] ?>" 
                                        class="peer sr-only"
                                        onchange="simpanPG(<?= $s['id'] ?>, this.value)"
                                        <?= ($s['id_opsi'] == $o['id']) ? 'checked' : '' ?>>
                                    
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-400 peer-checked:border-white peer-checked:bg-white flex items-center justify-center mr-3">
                                        <div class="w-2.5 h-2.5 rounded-full bg-blue-600 opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    
                                    <span class="font-medium text-gray-700 group-has-[:checked]:text-white"><?= $o['teks_opsi'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <?php endforeach; ?>
            <div class="h-24"></div>
        </div>
    </div>

    <div class="flex-none bg-white dark:bg-gray-800 border-t dark:border-gray-700 p-4 z-50 shadow-[0_-5px_10px_rgba(0,0,0,0.05)]">
        <div class="max-w-3xl mx-auto flex gap-4">
            <button onclick="nav(-1)" id="btnPrev" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-arrow-left"></i>
            </button>
            
            <button onclick="nav(1)" id="btnNext" class="flex-1 py-3 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                Selanjutnya <i class="fas fa-arrow-right"></i>
            </button>
            
            <button onclick="selesai()" id="btnDone" class="flex-1 py-3 rounded-xl bg-green-600 text-white font-bold shadow-lg shadow-green-600/30 hover:bg-green-700 transition-all hidden items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                SELESAI UJIAN <i class="fas fa-check-double"></i>
            </button>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- VARIABLES ---
    let curr = 0;
    const total = <?= count($soal) ?>;
    const idSesi = <?= $sesi->id ?>;
    const startTime = new Date("<?= $sesi->waktu_mulai ?>").getTime();
    const endTime = new Date("<?= $sesi->waktu_selesai_seharusnya ?>").getTime();
    
    // SETTINGAN
    const minWaktuMenit = <?= $minWaktuMenit ?>; 
    let maxViolations = <?= $limitPelanggaran ?>;
    let violations = 0;
    let isBlocked = false;

    // --- STARTUP ---
    $(document).ready(() => {
        show(0);
        setInterval(tick, 1000);
        setInterval(checkMinTime, 1000); 
        forceFullscreen();
    });

    // --- NAVIGASI ---
    function show(idx) {
        $('.soal-wrapper').hide();
        $('#soal_' + idx).fadeIn(200);
        curr = idx;
        $('#no_soal').text(curr + 1);

        $('#btnPrev').prop('disabled', curr === 0);
        
        if (curr === total - 1) {
            $('#btnNext').hide();
            $('#btnDone').removeClass('hidden').addClass('flex');
            checkMinTime();
        } else {
            $('#btnNext').show();
            $('#btnDone').removeClass('flex').addClass('hidden');
        }
    }

    function nav(dir) {
        if (dir === 1 && curr < total - 1) show(curr + 1);
        if (dir === -1 && curr > 0) show(curr - 1);
    }

    // --- CEK MINIMAL WAKTU ---
    function checkMinTime() {
        const now = new Date().getTime();
        const elapsed = (now - startTime) / 1000 / 60; // Menit

        if (elapsed < minWaktuMenit) {
            $('#btnDone').prop('disabled', true);
            $('#btnDone').html(`<i class="fas fa-lock"></i> Tunggu ${Math.ceil(minWaktuMenit - elapsed)} menit`);
            $('#minWaktuIndikator').removeClass('hidden');
            $('#sisaMinWaktu').text(Math.ceil(minWaktuMenit - elapsed));
        } else {
            $('#btnDone').prop('disabled', false);
            $('#btnDone').html(`SELESAI UJIAN <i class="fas fa-check-double"></i>`);
            $('#minWaktuIndikator').addClass('hidden');
        }
    }

    // --- SIMPAN JAWABAN (API) ---
    const api = '<?= base_url('siswa/ujian/simpan_jawaban') ?>';

    function simpanPG(idSoal, val) {
        $.post(api, { id_jawaban_siswa: idSesi, id_soal: idSoal, id_opsi: val });
    }
    function simpanIsian(idSoal, val) {
        $.post(api, { id_jawaban_siswa: idSesi, id_soal: idSoal, jawaban_isian: val });
    }
    function simpanKompleks(idSoal, idx) {
        let vals = [];
        $(`input[name="chk_${idx}[]"]:checked`).each(function() { vals.push($(this).val()); });
        $.post(api, { id_jawaban_siswa: idSesi, id_soal: idSoal, id_opsi: vals });
    }
    
    // LOGIC MENJODOHKAN (SIMPAN JSON: Key=KodeOpsi, Val=Jawaban)
    function simpanJodoh(idSoal, idx) {
        let pasangan = {};
        // Cari semua select dalam soal ini (select name="jodoh_INDEX[KODE]")
        $(`select[name^="jodoh_${idx}"]`).each(function() {
            // Ambil nama key dari attribute name (misal: Jawa Barat)
            let rawName = $(this).attr('name'); 
            // Regex untuk ambil teks dalam kurung siku
            let key = rawName.match(/\[(.*?)\]/)[1];
            let val = $(this).val();
            
            if(val !== "") {
                pasangan[key] = val;
            }
        });

        // Kirim sebagai JSON ke controller (disimpan di jawaban_isian)
        // Note: Controller harusnya sudah handle id_opsi sebagai array/json di update sebelumnya
        $.post(api, { id_jawaban_siswa: idSesi, id_soal: idSoal, id_opsi: pasangan }); 
    }

    // --- KEAMANAN ---
    function triggerViolation(reason) {
        if(isBlocked) return;

        violations++;
        let sisa = maxViolations - violations;
        $('#sisaNyawa').text(sisa);
        
        $('#violationOverlay').removeClass('hidden').addClass('flex');

        if (violations >= maxViolations) {
            isBlocked = true;
            $.post('<?= base_url('siswa/ujian/blokirSiswa') ?>', {
                id_ujian_siswa: idSesi,
                alasan: "Blokir Otomatis: " + reason
            }, function() {
                window.location.href = '<?= base_url('siswa/ujian') ?>'; 
            });
        }
    }

    function resumeUjian() {
        if(isBlocked) return;
        forceFullscreen();
        $('#violationOverlay').addClass('hidden').removeClass('flex');
    }

    function forceFullscreen() {
        const el = document.documentElement;
        if (el.requestFullscreen) el.requestFullscreen();
        else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
    }

    document.addEventListener("visibilitychange", function() {
        if (document.hidden) triggerViolation("Keluar Tab");
    });
    window.addEventListener("blur", function() {
        triggerViolation("Membuka Aplikasi Lain");
    });
    window.addEventListener("resize", function() {
        const isInput = document.activeElement.tagName === 'INPUT';
        if (!isInput && window.innerHeight < screen.height * 0.75) {
            triggerViolation("Split Screen");
        }
    });

    // --- TIMER ---
    function tick() {
        const now = new Date().getTime();
        const diff = endTime - now;
        if (diff < 0) { selesai(true); return; }
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        $('#timerDisplay').text(`${m<10?'0':''}${m}:${s<10?'0':''}${s}`);
    }

    function selesai(auto = false) {
        Swal.fire({
            title: auto ? 'WAKTU HABIS!' : 'Selesai Ujian?',
            text: auto ? 'Jawaban otomatis dikumpulkan.' : 'Apakah Anda yakin ingin mengakhiri ujian ini?',
            icon: 'question',
            showCancelButton: !auto,
            confirmButtonText: 'Ya, Kumpulkan',
            confirmButtonColor: '#16a34a',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            showLoaderOnConfirm: true, // Tampilkan loading spinner
            preConfirm: () => {
                // Gunakan Promise agar loading tidak hilang sampai selesai
                return $.post('<?= base_url('siswa/ujian/selesai') ?>', { id_ujian_siswa: idSesi })
                    .then(response => {
                        if (response.status === 'success') {
                            return response;
                        } else {
                            throw new Error(response.message || 'Gagal menyimpan data.');
                        }
                    })
                    .catch(error => {
                        // Tampilkan Error Jika Ada (Supaya tidak diam saja)
                        Swal.showValidationMessage(
                            `Gagal Mengumpulkan: ${error.responseText || error.message}`
                        )
                    })
            }
        }).then((result) => {
            if (result.isConfirmed && result.value && result.value.status === 'success') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Ujian telah dikumpulkan.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = result.value.redirect;
                });
            }
        });
    }
</script>

<?= $this->endSection() ?>