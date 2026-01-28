<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Jenis Tagihan & Generate</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Atur tipe pembayaran, generate tagihan, dan kelola beasiswa.</p>
        </div>
        <button onclick="openModal('tambah')" class="group flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Setting
        </button>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-100 dark:border-emerald-800 flex items-center gap-3 font-bold text-sm">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl border border-rose-100 dark:border-rose-800 flex items-center gap-3 font-bold text-sm">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-bold uppercase text-xs tracking-wider">
                <tr>
                    <th class="p-4 w-12 text-center">No</th>
                    <th class="p-4">Pos & Tahun</th>
                    <th class="p-4">Tipe Bayar</th>
                    <th class="p-4 text-right">Nominal Standar</th>
                    <th class="p-4 text-center w-48">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if(empty($jenis)): ?>
                    <tr><td colspan="5" class="p-8 text-center text-slate-400 italic">Belum ada setting jenis pembayaran.</td></tr>
                <?php else: ?>
                    <?php foreach($jenis as $i => $j): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                        <td class="p-4 text-center font-mono text-slate-400"><?= $i + 1 ?></td>
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-white"><?= esc($j['nama_pos']) ?></div>
                            <div class="text-xs text-slate-500 dark:text-slate-400"><?= esc($j['tahun_ajaran']) ?></div>
                        </td>
                        <td class="p-4">
                            <?php if($j['tipe_bayar'] == 'BULANAN'): ?>
                                <span class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 px-2.5 py-1 rounded-lg text-xs font-bold border border-indigo-200 dark:border-indigo-800">
                                    BULANAN
                                </span>
                            <?php else: ?>
                                <span class="bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400 px-2.5 py-1 rounded-lg text-xs font-bold border border-teal-200 dark:border-teal-800">
                                    BEBAS
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-right font-mono font-bold text-slate-700 dark:text-slate-200">
                            Rp <?= number_format($j['nominal_default'], 0, ',', '.') ?>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-2">
                                
                                <a href="<?= base_url('admin/keuangan/tagihan/kelola/' . $j['id']) ?>" 
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-500 hover:text-white hover:border-indigo-600 transition-all shadow-sm"
                                   title="Lihat Daftar Siswa & Edit Nominal (Beasiswa)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                <button onclick="openGenerate(<?= $j['id'] ?>, '<?= esc($j['nama_pos']) ?>', '<?= $j['tipe_bayar'] ?>')" 
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-500 hover:text-white hover:border-emerald-600 transition-all shadow-sm group"
                                        title="Generate Tagihan Massal">
                                    <svg class="w-4 h-4 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </button>

                                <button onclick="editJenis(<?= $j['id'] ?>, <?= $j['id_pos_bayar'] ?>, <?= $j['id_tahun_ajaran'] ?>, '<?= $j['tipe_bayar'] ?>', <?= $j['nominal_default'] ?>)" 
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 border border-amber-100 hover:bg-amber-500 hover:text-white hover:border-amber-600 transition-all shadow-sm"
                                        title="Edit Setting">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                
                                <button onclick="hapusJenis(<?= $j['id'] ?>)" 
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-500 hover:text-white hover:border-rose-600 transition-all shadow-sm"
                                        title="Hapus Setting">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalForm" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg border border-slate-100 dark:border-slate-700 transform scale-95 transition-transform" id="modalContent">
        <form action="<?= base_url('admin/keuangan/jenis/simpan') ?>" method="post" id="formJenis">
            
            <?= csrf_field() ?>
            
            <input type="hidden" name="id" id="inputId">
            
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50 rounded-t-2xl">
                <h3 class="font-black text-lg text-slate-800 dark:text-white flex items-center gap-2" id="modalTitle">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Setting Tagihan Baru
                </h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Pos Tagihan</label>
                        <select name="id_pos_bayar" id="inputPos" required class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold text-slate-800 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-0 transition-colors">
                            <option value="">Pilih Pos...</option>
                            <?php foreach($pos as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= esc($p['nama_pos']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Tahun Ajaran</label>
                        <select name="id_tahun_ajaran" id="inputTahun" required class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold text-slate-800 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-0 transition-colors">
                            <option value="">Pilih Tahun...</option>
                            <?php foreach($tahun as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= esc($t['tahun_ajaran']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Tipe Pembayaran</label>
                        <select name="tipe_bayar" id="inputTipe" required class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold text-slate-800 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-0 transition-colors">
                            <option value="BULANAN">Bulanan (SPP)</option>
                            <option value="BEBAS">Bebas (Sekali Bayar)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Nominal (Rp)</label>
                        <input type="text" name="nominal_default" id="inputNominal" onkeyup="formatRupiah(this)" required class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold text-right text-slate-800 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-0 transition-colors" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-transform active:scale-95">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalGenerate" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg border border-slate-100 dark:border-slate-700 transform scale-95 transition-transform" id="modalGenerateContent">
        <form action="<?= base_url('admin/keuangan/tagihan/generate') ?>" method="post">
            
            <?= csrf_field() ?>
            
            <input type="hidden" name="id_jenis_bayar" id="genId">
            
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-slate-800 dark:text-white">Generate Tagihan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tagihan: <span id="genNama" class="font-bold text-blue-600 dark:text-blue-400"></span></p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Pilih Target Kelas</label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="checkAll" onchange="toggleAll(this)" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Pilih Semua</span>
                        </label>
                    </div>

                    <div class="border-2 border-slate-200 dark:border-slate-600 rounded-xl p-4 max-h-48 overflow-y-auto bg-slate-50 dark:bg-slate-900">
                        <div class="grid grid-cols-2 gap-3">
                            <?php foreach($kelas as $k): ?>
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-white dark:hover:bg-slate-800 p-2 rounded-lg transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                                    <input type="checkbox" name="id_kelas[]" value="<?= $k['id'] ?>" class="chk-kelas w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Kelas <?= esc($k['nama_kelas']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 italic">*Centang kelas mana saja yang ingin dibuatkan tagihan.</p>
                </div>
                
                <div class="flex gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-blue-700 dark:text-blue-300 font-medium leading-relaxed">
                        Siswa yang <b>sudah memiliki tagihan ini</b> akan dilewati (tidak double). Aman untuk digenerate ulang.
                    </p>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700 flex gap-3">
                <button type="button" onclick="closeGenerate()" class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-transform active:scale-95">Proses Generate</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('.chk-kelas');
        checkboxes.forEach(chk => {
            chk.checked = source.checked;
        });
    }
</script>

<form id="formHapus" action="<?= base_url('admin/keuangan/jenis/hapus') ?>" method="post" class="hidden">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="hapusId">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- MODAL INPUT ---
    const modal = document.getElementById('modalForm');
    const modalContent = document.getElementById('modalContent');
    const form = document.getElementById('formJenis');

    function openModal(mode) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => { modal.classList.remove('opacity-0'); modalContent.classList.remove('scale-95'); modalContent.classList.add('scale-100'); }, 10);
        
        if(mode === 'tambah') {
            document.getElementById('modalTitle').innerHTML = '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Tambah Setting';
            document.getElementById('inputId').value = '';
            document.getElementById('inputPos').value = '';
            document.getElementById('inputTahun').value = '';
            document.getElementById('inputTipe').value = 'BULANAN';
            document.getElementById('inputNominal').value = '';
        }
    }

    function editJenis(id, pos, tahun, tipe, nominal) {
        openModal('edit');
        document.getElementById('modalTitle').innerHTML = '<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit Setting';
        document.getElementById('inputId').value = id;
        document.getElementById('inputPos').value = pos;
        document.getElementById('inputTahun').value = tahun;
        document.getElementById('inputTipe').value = tipe;
        document.getElementById('inputNominal').value = new Intl.NumberFormat('id-ID').format(nominal);
    }

    function closeModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
    }

    // --- MODAL GENERATE ---
    const modalGen = document.getElementById('modalGenerate');
    const modalGenContent = document.getElementById('modalGenerateContent');

    function openGenerate(id, nama, tipe) {
        modalGen.classList.remove('hidden');
        modalGen.classList.add('flex');
        setTimeout(() => { modalGen.classList.remove('opacity-0'); modalGenContent.classList.remove('scale-95'); modalGenContent.classList.add('scale-100'); }, 10);
        
        document.getElementById('genId').value = id;
        document.getElementById('genNama').innerText = nama + ' (' + tipe + ')';
    }

    function closeGenerate() {
        modalGen.classList.add('opacity-0');
        modalGenContent.classList.remove('scale-100');
        modalGenContent.classList.add('scale-95');
        setTimeout(() => { modalGen.classList.add('hidden'); modalGen.classList.remove('flex'); }, 300);
    }

    // --- UTILS ---
    function hapusJenis(id) {
        Swal.fire({
            title: 'Hapus Setting?',
            text: "Tagihan siswa yang sudah dibuat TIDAK AKAN HILANG, tapi setting master ini akan terhapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusId').value = id;
                document.getElementById('formHapus').submit();
            }
        })
    }

    function formatRupiah(input) {
        let value = input.value.replace(/[^,\d]/g, '').toString();
        let split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) { let separator = sisa ? '.' : ''; rupiah += separator + ribuan.join('.'); }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        input.value = rupiah;
    }
</script>

<?= $this->endSection() ?>