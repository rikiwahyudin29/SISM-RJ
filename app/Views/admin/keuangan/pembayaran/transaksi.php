<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('admin/keuangan/pembayaran') ?>" class="w-10 h-10 flex items-center justify-center bg-slate-100 dark:bg-slate-700 rounded-full text-slate-500 hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white"><?= esc($siswa['nama_lengkap']) ?></h1>
                <p class="text-sm text-slate-500"><?= esc($siswa['nis']) ?> • Kelas <?= esc($siswa['nama_kelas']) ?></p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-xs text-slate-400 font-bold uppercase">Tanggal Hari Ini</p>
            <p class="font-bold text-slate-700 dark:text-slate-300"><?= date('d F Y') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-4">
            
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl font-bold text-sm border border-emerald-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="p-4 bg-rose-50 text-rose-600 rounded-xl font-bold text-sm border border-rose-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700 font-bold text-slate-600 dark:text-slate-300">
                    Daftar Tagihan
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php foreach($tagihan as $t): ?>
                        <?php 
                            $sisa = $t['nominal_tagihan'] - $t['nominal_terbayar'];
                            $isLunas = $t['status_bayar'] == 'LUNAS';
                        ?>
                        <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $isLunas ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' ?>">
                                        <?= $t['status_bayar'] ?>
                                    </span>
                                    <span class="text-xs font-bold text-slate-400"><?= $t['nama_pos'] ?></span>
                                </div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-lg"><?= $t['keterangan'] ?></h4>
                                <div class="text-xs text-slate-500 mt-1">
                                    Tagihan: Rp <?= number_format($t['nominal_tagihan'], 0, ',', '.') ?> 
                                    <?php if($t['nominal_terbayar'] > 0): ?>
                                        • <span class="text-emerald-600">Terbayar: Rp <?= number_format($t['nominal_terbayar'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <?php if(!$isLunas): ?>
                                    <p class="text-xs text-slate-400 mb-1">Sisa Pembayaran</p>
                                    <p class="text-xl font-black text-rose-600 mb-3">Rp <?= number_format($sisa, 0, ',', '.') ?></p>
                                    
                                    <button onclick="openBayar(<?= $t['id'] ?>, '<?= $t['nama_pos'] ?> - <?= $t['keterangan'] ?>', <?= $sisa ?>)" 
                                            class="px-5 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 hover:bg-blue-700 active:scale-95 transition-all w-full md:w-auto">
                                        Bayar Sekarang
                                    </button>
                                <?php else: ?>
                                    <div class="text-emerald-500 font-bold flex items-center gap-1 justify-end">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        LUNAS
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Pembayaran
                </h3>
                
                <div class="space-y-4">
                    <?php if(empty($riwayat)): ?>
                        <p class="text-sm text-slate-400 italic text-center py-4">Belum ada riwayat.</p>
                    <?php else: ?>
                        <?php foreach($riwayat as $r): ?>
                            <div class="relative pl-4 border-l-2 border-slate-100 dark:border-slate-700 group">
                                <div class="absolute -left-[5px] top-1 w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600 group-hover:bg-blue-500 transition-colors"></div>
                                
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs text-slate-400 font-mono mb-0.5"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></p>
                                        <p class="font-bold text-slate-800 dark:text-white text-sm"><?= $r['nama_pos'] ?> (<?= $r['keterangan'] ?>)</p>
                                        <p class="text-emerald-600 font-bold text-sm">+ Rp <?= number_format($r['jumlah_bayar'], 0, ',', '.') ?></p>
                                    </div>
                                    
                                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="openModalCetak(<?= $r['id'] ?>)" class="text-slate-400 hover:text-blue-500" title="Cetak Ulang">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </button>
                                        
                                        <button onclick="konfirmasiBatal(<?= $r['id'] ?>)" class="text-slate-400 hover:text-red-500" title="Batalkan Transaksi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="modalBayar" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md border border-slate-100 dark:border-slate-700 transform scale-95 transition-transform" id="modalContent">
        <form action="<?= base_url('admin/keuangan/pembayaran/proses') ?>" method="post">
            
            <?= csrf_field() ?>
            
            <input type="hidden" name="id_siswa" value="<?= $siswa['id'] ?>">
            <input type="hidden" name="id_tagihan" id="inputTagihanId">
            
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-t-2xl text-center">
                <h3 class="font-black text-xl text-slate-800 dark:text-white" id="modalTitle">Pembayaran</h3>
                <p class="text-sm text-slate-500 mt-1">Masukkan nominal uang yang diterima.</p>
            </div>

            <div class="p-6 space-y-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl text-center">
                    <p class="text-xs font-bold text-blue-500 uppercase">Sisa Tagihan Saat Ini</p>
                    <p class="text-2xl font-black text-blue-700 dark:text-blue-400" id="textSisa">Rp 0</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jumlah Bayar (Rp)</label>
                    <input type="text" name="jumlah_bayar" id="inputBayar" onkeyup="formatRupiah(this)" required 
                           class="w-full text-center text-2xl font-bold py-4 rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-0 outline-none text-slate-800 dark:bg-slate-900 dark:text-white dark:border-slate-600"
                           placeholder="0" autocomplete="off">
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 active:scale-95 transition-all">Proses Bayar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalCetak" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm border border-slate-100 dark:border-slate-700 transform scale-95 transition-transform" id="modalCetakContent">
        <div class="p-8 text-center">
            <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-500 animate-bounce">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Pembayaran Sukses!</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">Pilih format struk yang ingin dicetak:</p>

            <div class="grid grid-cols-1 gap-3">
                <a href="#" id="btnCetakThermal" target="_blank" class="flex items-center justify-center gap-3 px-4 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Thermal (58mm)
                </a>
                
                <a href="#" id="btnCetakA4" target="_blank" class="flex items-center justify-center gap-3 px-4 py-3 bg-white border-2 border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Cetak Invoice (A4)
                </a>
            </div>

            <button onclick="closeModalCetak()" class="mt-6 text-sm font-bold text-slate-400 hover:text-slate-600 underline">Tutup / Tidak Cetak</button>
        </div>
    </div>
</div>

<form id="formBatal" action="<?= base_url('admin/keuangan/pembayaran/batal') ?>" method="post" class="hidden">
    <?= csrf_field() ?>
    <input type="hidden" name="id_transaksi" id="batalId">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const modal = document.getElementById('modalBayar');
    const modalContent = document.getElementById('modalContent');
    const inputBayar = document.getElementById('inputBayar');

    // --- MODAL BAYAR ---
    function openBayar(id, title, sisa) {
        document.getElementById('inputTagihanId').value = id;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('textSisa').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(sisa);
        inputBayar.value = new Intl.NumberFormat('id-ID').format(sisa);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => { modal.classList.remove('opacity-0'); modalContent.classList.remove('scale-95'); modalContent.classList.add('scale-100'); }, 10);
        
        inputBayar.focus();
    }

    function closeModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
    }

    // --- MODAL CETAK (POPUP SUKSES) ---
    const modalCetak = document.getElementById('modalCetak');
    const modalCetakContent = document.getElementById('modalCetakContent');

    function openModalCetak(trxId) {
        document.getElementById('btnCetakThermal').href = '<?= base_url('admin/keuangan/pembayaran/cetak/') ?>' + trxId + '?mode=thermal';
        document.getElementById('btnCetakA4').href = '<?= base_url('admin/keuangan/pembayaran/cetak/') ?>' + trxId + '?mode=a4';

        modalCetak.classList.remove('hidden');
        modalCetak.classList.add('flex');
        setTimeout(() => { modalCetak.classList.remove('opacity-0'); modalCetakContent.classList.remove('scale-95'); modalCetakContent.classList.add('scale-100'); }, 10);
    }

    function closeModalCetak() {
        modalCetak.classList.add('opacity-0');
        modalCetakContent.classList.remove('scale-100');
        modalCetakContent.classList.add('scale-95');
        setTimeout(() => { modalCetak.classList.add('hidden'); modalCetak.classList.remove('flex'); }, 300);
    }

    // --- AUTO SHOW MODAL IF SUCCESS ---
    <?php if(session()->getFlashdata('new_trx_id')): ?>
        openModalCetak(<?= session()->getFlashdata('new_trx_id') ?>);
    <?php endif; ?>

    // --- PEMBATALAN ---
    function konfirmasiBatal(id) {
        Swal.fire({
            title: 'Batalkan Transaksi?',
            text: "Saldo tagihan siswa akan dikembalikan seperti semula. Data transaksi akan dihapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tutup'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('batalId').value = id;
                document.getElementById('formBatal').submit();
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
        input.value = rupiah;
    }
</script>

<?= $this->endSection() ?>