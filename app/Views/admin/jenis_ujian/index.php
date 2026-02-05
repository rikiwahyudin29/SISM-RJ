<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:ml-2">
    <div class="flex items-center justify-between mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Master Jenis Ujian</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola kategori ujian seperti PAS, PAT, UH, dll.</p>
        </div>
        <button onclick="openModal('modalTambah')" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/30 transition-all font-bold text-sm flex items-center gap-2">
            <i class="fas fa-plus"></i> TAMBAH JENIS
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-slate-700/50 text-gray-500 dark:text-gray-400 uppercase text-xs font-bold">
                <tr>
                    <th class="px-6 py-4 w-16 text-center">No</th>
                    <th class="px-6 py-4">Nama Jenis Ujian</th>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
    <?php if(empty($jenis)): ?>
        <tr>
            <td colspan="4" class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada data jenis ujian.</td>
        </tr>
    <?php else: $no=1; foreach($jenis as $j): ?>
        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
            <td class="px-6 py-4 text-center font-mono text-xs text-gray-500 dark:text-gray-400"><?= $no++ ?></td>
            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white uppercase tracking-tight text-sm"><?= esc($j['nama_jenis']) ?></td>
            <td class="px-6 py-4">
                <span class="bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 text-[10px] px-2 py-1 rounded-md font-black border border-blue-100 dark:border-blue-800 uppercase tracking-widest">
                    <?= esc($j['kode_jenis']) ?>
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex justify-center items-center gap-2">
                    <button onclick="editJenis(<?= htmlspecialchars(json_encode($j)) ?>)" 
                            class="group p-2 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white rounded-xl transition-all duration-200 border border-amber-100 dark:border-amber-800"
                            title="Edit Data">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                    
                    <a href="<?= base_url('admin/jenis_ujian/hapus/' . $j['id']) ?>" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" 
                       class="group p-2 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-200 border border-rose-100 dark:border-rose-800"
                       title="Hapus Data">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; endif; ?>
</tbody>
        </table>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 dark:text-white">Tambah Jenis Ujian</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= base_url('admin/jenis_ujian/simpan') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?> <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Jenis Ujian</label>
                <input type="text" name="nama_jenis" required class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 dark:text-white" placeholder="Contoh: Penilaian Akhir Semester">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kode (Singkatan)</label>
                <input type="text" name="kode_jenis" required class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 dark:text-white" placeholder="Contoh: PAS">
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all">SIMPAN DATA</button>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 dark:text-white">Edit Jenis Ujian</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="formEdit" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?> <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Jenis Ujian</label>
                <input type="text" name="nama_jenis" id="edit_nama" required class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kode (Singkatan)</label>
                <input type="text" name="kode_jenis" id="edit_kode" required class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus:ring-blue-500 focus:border-blue-500 dark:text-white">
            </div>
            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all">UPDATE DATA</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function editJenis(data) {
        document.getElementById('edit_nama').value = data.nama_jenis;
        document.getElementById('edit_kode').value = data.kode_jenis;
        document.getElementById('formEdit').action = "<?= base_url('admin/jenis_ujian/update') ?>/" + data.id;
        openModal('modalEdit');
    }
</script>

<?= $this->endSection() ?>