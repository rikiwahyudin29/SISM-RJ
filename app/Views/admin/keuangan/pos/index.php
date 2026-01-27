<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Master Pos Bayar</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Kelola nama-nama tagihan sekolah (SPP, Gedung, dll).</p>
        </div>
        <button onclick="openModal('tambah')" class="group flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pos
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
                    <th class="p-4">Nama Pos Tagihan</th>
                    <th class="p-4">Keterangan</th>
                    <th class="p-4 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if(empty($pos)): ?>
                    <tr><td colspan="4" class="p-8 text-center text-slate-400 italic">Belum ada data pos bayar.</td></tr>
                <?php else: ?>
                    <?php foreach($pos as $i => $p): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="p-4 text-center font-mono text-slate-400"><?= $i + 1 ?></td>
                        <td class="p-4 font-bold text-slate-800 dark:text-white"><?= esc($p['nama_pos']) ?></td>
                        <td class="p-4 text-slate-500 dark:text-slate-400"><?= esc($p['keterangan']) ?></td>
                        <td class="p-4 text-center flex justify-center gap-2">
                            
                            <button onclick="editPos(<?= $p['id'] ?>, '<?= esc($p['nama_pos']) ?>', '<?= esc($p['keterangan']) ?>')" 
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 border border-amber-100 hover:bg-amber-500 hover:text-white hover:border-amber-600 transition-all shadow-sm"
                                    title="Edit Data">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            
                            <button onclick="hapusPos(<?= $p['id'] ?>)" 
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-500 hover:text-white hover:border-rose-600 transition-all shadow-sm"
                                    title="Hapus Data">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalForm" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md border border-slate-100 dark:border-slate-700 transform scale-95 transition-transform" id="modalContent">
        <form action="<?= base_url('admin/keuangan/pos/simpan') ?>" method="post" id="formPos">
            <input type="hidden" name="id" id="inputId">
            
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50 rounded-t-2xl">
                <h3 class="font-black text-lg text-slate-800 dark:text-white flex items-center gap-2" id="modalTitle">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Pos
                </h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Nama Pos</label>
                    <input type="text" name="nama_pos" id="inputNama" required class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold text-slate-800 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-0 transition-colors" placeholder="Contoh: SPP, Uang Gedung">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Keterangan</label>
                    <textarea name="keterangan" id="inputKet" rows="2" class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-medium text-slate-800 dark:bg-slate-900 dark:text-white focus:border-blue-500 focus:ring-0 transition-colors" placeholder="Opsional..."></textarea>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-transform active:scale-95">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<form id="formHapus" action="<?= base_url('admin/keuangan/pos/hapus') ?>" method="post" class="hidden">
    <input type="hidden" name="id" id="hapusId">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const modal = document.getElementById('modalForm');
    const modalContent = document.getElementById('modalContent');
    const form = document.getElementById('formPos');

    function openModal(mode) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Animasi Masuk
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        
        if(mode === 'tambah') {
            document.getElementById('modalTitle').innerHTML = '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Tambah Pos Bayar';
            form.action = '<?= base_url('admin/keuangan/pos/simpan') ?>';
            document.getElementById('inputId').value = '';
            document.getElementById('inputNama').value = '';
            document.getElementById('inputKet').value = '';
        }
    }

    function editPos(id, nama, ket) {
        openModal('edit');
        document.getElementById('modalTitle').innerHTML = '<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit Pos Bayar';
        form.action = '<?= base_url('admin/keuangan/pos/update') ?>';
        document.getElementById('inputId').value = id;
        document.getElementById('inputNama').value = nama;
        document.getElementById('inputKet').value = ket;
    }

    function closeModal() {
        // Animasi Keluar
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function hapusPos(id) {
        Swal.fire({
            title: 'Hapus Pos?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-4 py-2 font-bold',
                cancelButton: 'rounded-xl px-4 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusId').value = id;
                document.getElementById('formHapus').submit();
            }
        })
    }
</script>

<?= $this->endSection() ?>