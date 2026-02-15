<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">🗂️ Master Pelanggaran</h1>
            <p class="text-sm text-gray-500">Atur jenis pelanggaran dan bobot poin.</p>
        </div>
        
        <div class="flex gap-2">
            <a href="<?= base_url('guru/bk') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-all flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI
            </a>
            <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg transition-all flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                TAMBAH DATA
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-slate-700 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 w-10">No</th>
                        <th class="px-6 py-3">Nama Pelanggaran</th>
                        <th class="px-6 py-3 text-center">Kategori</th>
                        <th class="px-6 py-3 text-center">Poin</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    <?php $no=1; foreach($data as $d): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                        <td class="px-6 py-4 text-center"><?= $no++ ?></td>
                        <td class="px-6 py-4 font-bold text-gray-800 dark:text-white"><?= $d['nama_pelanggaran'] ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase <?= $d['kategori'] == 'Berat' ? 'bg-rose-100 text-rose-600' : ($d['kategori'] == 'Sedang' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600') ?>">
                                <?= $d['kategori'] ?>
                            </span>
                        </td>
                       <td class="px-6 py-4 text-center">
    <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg font-black">
        -<?= $d['poin'] ?> Poin
    </span>
</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="editData(<?= htmlspecialchars(json_encode($d)) ?>)" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <a href="<?= base_url('guru/bk/master/delete/'.$d['id']) ?>" onclick="return confirm('Hapus master data ini?')" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalForm" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900/50">
            <h3 class="font-bold text-gray-800 dark:text-white" id="modalTitle">Tambah Jenis</h3>
            <button onclick="document.getElementById('modalForm').classList.add('hidden')" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        
        <form action="<?= base_url('guru/bk/master/save') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" id="formId">
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Pelanggaran</label>
                <input type="text" name="nama_pelanggaran" id="formNama" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border rounded-xl text-sm dark:text-white" placeholder="Contoh: Merokok">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kategori</label>
                    <select name="kategori" id="formKategori" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border rounded-xl text-sm dark:text-white">
                        <option value="Ringan">Ringan</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Berat">Berat</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Poin</label>
                    <input type="number" name="poin" id="formPoin" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border rounded-xl text-sm dark:text-white" placeholder="10">
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700">SIMPAN</button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalForm').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Tambah Jenis';
        document.getElementById('formId').value = '';
        document.getElementById('formNama').value = '';
        document.getElementById('formPoin').value = '';
    }

    function editData(data) {
        document.getElementById('modalForm').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Edit Jenis';
        document.getElementById('formId').value = data.id;
        document.getElementById('formNama').value = data.nama_pelanggaran;
        document.getElementById('formKategori').value = data.kategori;
        document.getElementById('formPoin').value = data.poin;
    }
</script>

<?= $this->endSection(); ?>