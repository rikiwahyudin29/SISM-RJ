<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.MathJax = {
        tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
        svg: { fontCache: 'global' },
        startup: {
            ready: () => {
                MathJax.startup.defaultReady();
            }
        }
    };
</script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<style>
    /* Dark Mode Support */
    .dark .note-editor .note-editing-area .note-editable { color: #fff !important; background-color: #374151 !important; }
    .dark .note-btn { color: #e5e7eb !important; background-color: #4b5563 !important; border-color: #374151 !important; }
    .dark .note-toolbar { background-color: #1f2937 !important; border-bottom-color: #374151 !important; }
    .note-modal-footer { height: 60px; padding: 10px; text-align: right; } /* Fix modal footer height */
    
    /* Tombol Rumus Custom */
    .math-btn { cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; background: #f8f9fa; margin: 2px; border-radius: 4px; display: inline-block; }
    .math-btn:hover { background: #e2e6ea; }
</style>

<div class="p-4 bg-white border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700 sticky top-16 z-30 shadow-sm">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white"><?= esc($bank['judul_ujian']) ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded"><?= $bank['nama_mapel'] ?></span>
                Kode: <b><?= $bank['kode_bank'] ?></b>
            </p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="bukaModalImport()" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-4 py-2 flex items-center shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Import Excel
            </button>
            <a href="<?= base_url('guru/bank_soal') ?>" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-4 py-2">Kembali</a>
        </div>
    </div>
</div>

<div class="flex flex-col lg:flex-row p-4 gap-6 h-[calc(100vh-140px)]">
    
    <div class="w-full lg:w-1/3 order-2 lg:order-1 flex flex-col h-full">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-gray-900 dark:text-white">Daftar Soal (<span id="totalSoal"><?= count($soal) ?></span>)</h3>
            <button onclick="resetForm()" class="text-xs text-blue-600 hover:underline font-bold dark:text-blue-400">+ Soal Baru</button>
        </div>
        
        <div id="listSoalContainer" class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-3 pb-20">
            <?php foreach($soal as $index => $s): ?>
            <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 flex flex-col gap-2 hover:border-blue-500 transition-colors cursor-pointer group" onclick="editSoal(<?= $s['id'] ?>)">
                
                <div class="flex justify-between items-start">
                    <div class="flex items-center">
                        <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2 py-0.5 rounded border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                            No. <?= $index + 1 ?>
                        </span>
                        <span class="text-[10px] font-mono text-gray-500 ml-2 uppercase dark:text-gray-400">
                            [<?= $s['tipe_soal'] ?>]
                        </span>
                    </div>
                </div>

                <div class="text-sm text-gray-800 dark:text-gray-300 line-clamp-2 leading-relaxed">
                    <?= strip_tags($s['pertanyaan']) ?>
                </div>

                <div class="flex justify-end pt-2 border-t border-dashed border-gray-200 dark:border-gray-700 mt-1">
                    <button type="button" onclick="hapusSoal(event, <?= $s['id'] ?>)" class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-600 hover:text-white transition-colors flex items-center font-bold">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>

            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="w-full lg:w-2/3 order-1 lg:order-2 h-full flex flex-col">
        <div class="bg-white border border-gray-200 rounded-lg shadow-xl dark:bg-gray-800 dark:border-gray-700 flex-1 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-edit text-blue-600"></i> <span id="formTitle">Input Soal Baru</span>
                </h3>
                <div class="text-xs font-mono text-gray-500" id="autoSaveStatus">Siap Input</div>
            </div>
            
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800">
                <form id="formSoal">
                    <input type="hidden" name="id_bank_soal" value="<?= $bank['id'] ?>">
                    <input type="hidden" name="id_soal" id="id_soal" value="">

                    <div id="mathHelper" class="hidden mb-4 p-3 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300">
                        <p class="text-xs font-bold mb-2 text-gray-700 dark:text-gray-300">Klik untuk menyisipkan rumus:</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="math-btn" onclick="insertMath('\\frac{a}{b}')">Pecahan $\frac{a}{b}$</span>
                            <span class="math-btn" onclick="insertMath('\\sqrt{x}')">Akar $\sqrt{x}$</span>
                            <span class="math-btn" onclick="insertMath('x^2')">Pangkat $x^2$</span>
                            <span class="math-btn" onclick="insertMath('\\sum_{i=1}^{n}')">Sigma $\sum$</span>
                            <span class="math-btn" onclick="insertMath('\\int_{a}^{b}')">Integral $\int$</span>
                            <span class="math-btn" onclick="insertMath('\\alpha')">Alpha $\alpha$</span>
                            <span class="math-btn" onclick="insertMath('\\beta')">Beta $\beta$</span>
                            <span class="math-btn" onclick="insertMath('\\pi')">Pi $\pi$</span>
                            <span class="math-btn" onclick="insertMath('\\times')">Kali $\times$</span>
                            <span class="math-btn" onclick="insertMath('\\div')">Bagi $\div$</span>
                        </div>
                        <p class="text-[10px] mt-2 text-gray-500">Tips: Rumus akan tampil sebagai kode LaTeX (contoh: <code>\( \frac{a}{b} \)</code>) saat diedit, tapi akan berubah jadi rumus cantik setelah disimpan.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block mb-2 text-xs font-bold uppercase text-gray-500">Tipe Soal</label>
                            <select name="tipe_soal" id="tipe_soal" onchange="gantiTipe()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="PG">Pilihan Ganda (PG)</option>
                                <option value="PG_KOMPLEKS">PG Kompleks</option>
                                <option value="BENAR_SALAH">Benar - Salah</option>
                                <option value="MENJODOHKAN">Menjodohkan</option>
                                <option value="ISIAN_SINGKAT">Isian Singkat</option>
                                <option value="ESSAY">Essay</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-bold uppercase text-gray-500">Bobot Nilai</label>
                            <input type="number" name="bobot" id="bobot" value="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-xs font-bold uppercase text-gray-500">Pertanyaan</label>
                        <textarea id="summernote" name="pertanyaan"></textarea>
                    </div>

                    <div id="area_opsi" class="p-5 bg-gray-50 rounded-xl border border-gray-200 dark:bg-gray-700/50">
                        <div id="form_PG" class="tipe-form">
                            <?php $abjad = ['A','B','C','D','E']; ?>
                            <?php foreach($abjad as $idx => $huruf): ?>
                            <div class="flex items-center mb-3 gap-3">
                                <span class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 font-bold text-gray-700"><?= $huruf ?></span>
                                <input type="text" name="pg_opsi[]" class="flex-1 bg-white border border-gray-300 text-sm rounded-lg p-2.5" placeholder="Jawaban...">
                                <input type="radio" name="pg_kunci" value="<?= $idx ?>" class="w-5 h-5 cursor-pointer" <?= $idx==0?'checked':'' ?>>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div id="form_PG_KOMPLEKS" class="tipe-form hidden">
                             <?php foreach($abjad as $idx => $huruf): ?>
                            <div class="flex items-center mb-3 gap-3">
                                <span class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 font-bold text-gray-700"><?= $huruf ?></span>
                                <input type="text" name="pgk_opsi[]" class="flex-1 bg-white border border-gray-300 text-sm rounded-lg p-2.5" placeholder="Jawaban...">
                                <input type="checkbox" name="pgk_kunci[]" value="<?= $idx ?>" class="w-5 h-5 cursor-pointer">
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div id="form_BENAR_SALAH" class="tipe-form hidden">
                            <div class="flex gap-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg bg-white w-full cursor-pointer hover:bg-green-50">
                                    <input type="radio" name="bs_kunci" value="Benar" class="w-5 h-5 text-green-600" checked>
                                    <span class="ml-3 font-bold text-green-700">BENAR</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg bg-white w-full cursor-pointer hover:bg-red-50">
                                    <input type="radio" name="bs_kunci" value="Salah" class="w-5 h-5 text-red-600">
                                    <span class="ml-3 font-bold text-red-700">SALAH</span>
                                </label>
                            </div>
                        </div>

                        <div id="form_MENJODOHKAN" class="tipe-form hidden">
                            <div id="containerJodoh">
                                <div class="flex gap-3 mb-3 row-jodoh">
                                    <input type="text" name="jodoh_kiri[]" class="w-1/2 bg-white border border-gray-300 text-sm rounded-lg p-2.5" placeholder="Premis">
                                    <i class="fas fa-arrow-right text-gray-400 self-center"></i>
                                    <input type="text" name="jodoh_kanan[]" class="w-1/2 bg-white border border-gray-300 text-sm rounded-lg p-2.5" placeholder="Jawaban">
                                </div>
                            </div>
                            <button type="button" onclick="tambahJodoh()" class="mt-2 text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>
                        </div>
                        
                        <div id="form_ISIAN_SINGKAT" class="tipe-form hidden">
                            <input type="text" name="isian_kunci" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Kunci Jawaban Singkat">
                        </div>
                        <div id="form_ESSAY" class="tipe-form hidden">
                            <p class="text-sm italic text-gray-500">Essay dikoreksi manual.</p>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-4 border-t border-gray-200 bg-gray-50 dark:bg-gray-900 flex justify-between items-center z-10">
                <button type="button" onclick="resetForm()" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Reset</button>
                <button type="button" onclick="simpanSoal()" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-8 py-2.5 shadow-lg">
                    <i class="fas fa-save mr-2"></i> Simpan Soal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- TOMBOL CUSTOM SUMMERNOTE UNTUK MATH ---
    var MathButton = function (context) {
        var ui = $.summernote.ui;
        var button = ui.button({
            contents: '<i class="fas fa-square-root-alt"></i> Σ Rumus',
            tooltip: 'Sisipkan Rumus Matematika',
            click: function () {
                // Toggle Helper Rumus
                $('#mathHelper').toggleClass('hidden');
            }
        });
        return button.render();
    }

    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Tulis soal... Klik tombol "Σ Rumus" untuk matematika.',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'table']],
                ['mybutton', ['math']], // Tombol Custom Kita
                ['view', ['fullscreen', 'codeview']]
            ],
            buttons: {
                math: MathButton
            },
            callbacks: {
                onChange: function(contents, $editable) {
                    autoSaveDraft();
                }
            }
        });
        
        // Render MathJax awal untuk list soal
        if (window.MathJax) {
            MathJax.typesetPromise();
        }
    });

    // FUNGSI INSERT RUMUS KE SUMMERNOTE
    function insertMath(latex) {
        // Format MathJax: \( rumus \)
        var rumusFormatted = '\\( ' + latex + ' \\) '; 
        $('#summernote').summernote('editor.insertText', rumusFormatted);
        $('#mathHelper').addClass('hidden'); // Sembunyikan helper setelah klik
    }
    
    // --- SISA FUNCTION SAMA SEPERTI SEBELUMNYA ---
    // (Copy Function simpanSoal, autoSaveDraft, dll dari jawaban sebelumnya)
    let timeoutId;
    function autoSaveDraft() { /* ... */ }
    
    function simpanSoal() {
        const formData = new FormData(document.getElementById('formSoal'));
        formData.append('pertanyaan', $('#summernote').summernote('code'));

        const btn = document.querySelector('button[onclick="simpanSoal()"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Proses...'; btn.disabled = true;

        fetch('<?= base_url('guru/bank_soal/simpanSoalAjax') ?>', {
            method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({icon: 'success', title: 'Berhasil', timer: 1000, showConfirmButton: false});
                resetForm();
                localStorage.removeItem('draft_soal_<?= $bank['id'] ?>');
                location.reload(); 
            } else { Swal.fire('Error', data.message, 'error'); }
        })
        .finally(() => { btn.innerHTML = originalText; btn.disabled = false; });
    }

    function resetForm() {
        document.getElementById('formSoal').reset();
        $('#summernote').summernote('code', '');
        document.getElementById('id_soal').value = '';
        gantiTipe();
    }
    
    function gantiTipe() {
        const tipe = document.getElementById('tipe_soal').value;
        document.querySelectorAll('.tipe-form').forEach(el => el.classList.add('hidden'));
        document.getElementById('form_' + tipe).classList.remove('hidden');
    }
    
    function tambahJodoh() {
        // ... (Sama seperti sebelumnya)
        const container = document.getElementById('containerJodoh');
        const div = document.createElement('div');
        div.className = 'flex gap-3 mb-3 row-jodoh';
        div.innerHTML = `<input type="text" name="jodoh_kiri[]" class="w-1/2 bg-white border border-gray-300 text-sm rounded p-2.5" placeholder="Premis"><input type="text" name="jodoh_kanan[]" class="w-1/2 bg-white border border-gray-300 text-sm rounded p-2.5" placeholder="Jawaban">`;
        container.appendChild(div);
    }
    function editSoal(idSoal) {
        // 1. Tampilkan Loading di Form
        $('#formTitle').text('Sedang memuat data...');
        $('#summernote').summernote('disable'); // Kunci dulu biar gak diacak-acak

        // 2. Ambil Data via AJAX
        $.ajax({
            url: '<?= base_url('guru/bank_soal/getDetailSoal') ?>/' + idSoal,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                const s = data.soal;
                const o = data.opsi;

                // 3. Isi Data Header Soal
                $('#id_soal').val(s.id);
                $('#tipe_soal').val(s.tipe_soal).trigger('change'); // Trigger biar form opsi berubah
                $('#bobot').val(s.bobot);
                
                // Isi Summernote
                $('#summernote').summernote('code', s.pertanyaan);
                
                // Ubah Judul Form
                $('#formTitle').html('<i class="fas fa-edit text-yellow-600"></i> Edit Soal No. ' + s.id);
                
                // 4. Isi Opsi Jawaban Sesuai Tipe
                
                // --- JIKA PILIHAN GANDA (PG) ---
                if(s.tipe_soal === 'PG') {
                    // Reset dulu inputan
                    $('input[name="pg_opsi[]"]').val('');
                    $('input[name="pg_kunci"]').prop('checked', false);

                    // Loop data opsi dari database
                    o.forEach((item, index) => {
                        // Isi kolom teks (Index 0=A, 1=B, dst)
                        $('input[name="pg_opsi[]"]').eq(index).val(item.teks_opsi);
                        
                        // Ceklis jika ini kunci jawaban
                        if(item.is_benar == 1) {
                            $('input[name="pg_kunci"][value="'+index+'"]').prop('checked', true);
                        }
                    });
                }

                // --- JIKA PG KOMPLEKS ---
                else if(s.tipe_soal === 'PG_KOMPLEKS') {
                    $('input[name="pgk_opsi[]"]').val('');
                    $('input[name="pgk_kunci[]"]').prop('checked', false);

                    o.forEach((item, index) => {
                        $('input[name="pgk_opsi[]"]').eq(index).val(item.teks_opsi);
                        if(item.is_benar == 1) {
                            $('input[name="pgk_kunci[]"]').eq(index).prop('checked', true);
                        }
                    });
                }

                // --- JIKA BENAR SALAH ---
                else if(s.tipe_soal === 'BENAR_SALAH') {
                    // Cari opsi yang is_benar = 1
                    const kunci = o.find(item => item.is_benar == 1);
                    if(kunci) {
                        $('input[name="bs_kunci"][value="'+kunci.teks_opsi+'"]').prop('checked', true);
                    }
                }

                // --- JIKA ISIAN SINGKAT ---
                else if(s.tipe_soal === 'ISIAN_SINGKAT') {
                    if(o.length > 0) {
                        $('input[name="isian_kunci"]').val(o[0].teks_opsi);
                    }
                }

                // --- JIKA MENJODOHKAN ---
                else if(s.tipe_soal === 'MENJODOHKAN') {
                    // Kosongkan container dulu
                    $('#containerJodoh').html('');
                    
                    // Buat inputan sesuai jumlah data
                    o.forEach(item => {
                        const html = `
                            <div class="flex gap-3 mb-3 row-jodoh">
                                <input type="text" name="jodoh_kiri[]" value="${item.kode_opsi}" class="w-1/2 bg-white border border-gray-300 text-sm rounded-lg p-2.5" placeholder="Premis">
                                <i class="fas fa-arrow-right text-gray-400 self-center"></i>
                                <input type="text" name="jodoh_kanan[]" value="${item.teks_opsi}" class="w-1/2 bg-white border border-gray-300 text-sm rounded-lg p-2.5" placeholder="Jawaban">
                            </div>`;
                        $('#containerJodoh').append(html);
                    });
                }

                // 5. Scroll ke Form & Enable Editor
                $('#summernote').summernote('enable');
                // Auto scroll ke form input di sebelah kanan (mobile friendly)
                /* Untuk Desktop tidak perlu scroll karena sticky */
            },
            error: function() {
                Swal.fire('Error', 'Gagal mengambil data soal.', 'error');
                $('#summernote').summernote('enable');
            }
        });
    }

    // --- PASTIKAN FUNGSI RESET JUGA MEMBERSIHKAN ID_SOAL ---
    function resetForm() {
        document.getElementById('formSoal').reset();
        $('#summernote').summernote('code', ''); // Bersihkan editor
        $('#id_soal').val(''); // PENTING: ID KOSONG ARTINYA INSERT BARU
        $('#formTitle').html('<i class="fas fa-plus text-blue-600"></i> Input Soal Baru');
        
        // Kembalikan Tipe ke Default
        $('#tipe_soal').val('PG').trigger('change');
        
        // Reset Menjodohkan ke 1 baris default
        $('#containerJodoh').html(`
            <div class="flex gap-3 mb-3 row-jodoh">
                <input type="text" name="jodoh_kiri[]" class="w-1/2 bg-white border border-gray-300 text-sm rounded-lg p-2.5" placeholder="Premis">
                <i class="fas fa-arrow-right text-gray-400 self-center"></i>
                <input type="text" name="jodoh_kanan[]" class="w-1/2 bg-white border border-gray-300 text-sm rounded-lg p-2.5" placeholder="Jawaban">
            </div>
        `);
    }
    function hapusSoal(e, idSoal) {
        // Stop event bubbling (PENTING AGAR TIDAK MEMBUKA EDIT SAAT DIKLIK)
        if (e) e.stopPropagation(); 
        
        Swal.fire({
            title: 'Hapus Soal ini?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('guru/bank_soal/hapusSoal') ?>/' + idSoal + '/<?= $bank['id'] ?>';
            }
        });
    }
</script>

<?= $this->endSection() ?>