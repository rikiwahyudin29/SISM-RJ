<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📦 Inventaris Sarpras</h1>
            <p class="text-sm text-gray-500 font-medium">Pendataan aset dan barang milik sekolah.</p>
        </div>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            TAMBAH BARANG
        </button>
    </div>

    <div class="mb-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700">
        <form action="" method="GET" class="relative">
            <input type="text" name="q" value="<?= $keyword ?>" placeholder="Cari nama barang, kode, atau lokasi..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 uppercase text-[10px] font-black border-b dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Kode / Nama Barang</th>
                        <th class="px-6 py-4">Lokasi</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-center">Kondisi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    <?php if(empty($barang)): ?>
                        <tr><td colspan="5" class="py-10 text-center text-gray-400 font-bold">Data inventaris tidak ditemukan.</td></tr>
                    <?php endif; ?>

                    <?php foreach($barang as $b): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800 dark:text-white"><?= $b['nama_barang'] ?></div>
                            <div class="text-[10px] text-gray-400 font-black uppercase tracking-wider bg-gray-100 inline-block px-1 rounded mt-1"><?= $b['kode_barang'] ?></div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-600 dark:text-gray-300"><?= $b['lokasi'] ?></td>
                        <td class="px-6 py-4 text-center font-bold text-lg"><?= $b['jumlah'] ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php 
                                $badge = "bg-gray-100 text-gray-600";
                                if($b['kondisi'] == 'Baik') $badge = "bg-emerald-100 text-emerald-600 border border-emerald-200";
                                if($b['kondisi'] == 'Rusak Ringan') $badge = "bg-amber-100 text-amber-600 border border-amber-200";
                                if($b['kondisi'] == 'Rusak Berat') $badge = "bg-rose-100 text-rose-600 border border-rose-200";
                            ?>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?= $badge ?>"><?= $b['kondisi'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="editBarang(<?= htmlspecialchars(json_encode($b)) ?>)" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 border border-amber-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <a href="<?= base_url('admin/sarpras/delete/'.$b['id']) ?>" onclick="return confirm('Hapus barang ini?')" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 border border-rose-200">
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

<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg text-gray-800 uppercase" id="modalTitle">Tambah Barang Baru</h3>
            <button onclick="tutupModal()" class="text-gray-400 hover:text-rose-500">✕</button>
        </div>

        <form action="<?= base_url('admin/sarpras/save') ?>" method="POST" id="formBarang">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" id="inputId">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Kode Barang</label>
                    <input type="text" name="kode_barang" id="inputKode" class="w-full p-2 border rounded-xl text-sm font-bold bg-gray-50" placeholder="INV-001" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Tanggal Masuk</label>
                    <input type="date" name="tgl_masuk" id="inputTgl" value="<?= date('Y-m-d') ?>" class="w-full p-2 border rounded-xl text-sm font-bold">
                </div>
            </div>

            <div class="mb-4">
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" id="inputNama" class="w-full p-3 border rounded-xl text-sm font-bold" placeholder="Contoh: Kursi Guru Kayu Jati" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Kategori</label>
                    <select name="kategori" id="inputKategori" class="w-full p-2 border rounded-xl text-sm font-bold">
                        <option value="Mebel">Mebel</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Alat Tulis">Alat Tulis</option>
                        <option value="Kebersihan">Kebersihan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Lokasi Ruangan</label>
                    <input type="text" name="lokasi" id="inputLokasi" class="w-full p-2 border rounded-xl text-sm font-bold" placeholder="R. Guru / Lab">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Jumlah</label>
                    <input type="number" name="jumlah" id="inputJumlah" class="w-full p-2 border rounded-xl text-sm font-bold" value="1" min="1">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Kondisi</label>
                    <select name="kondisi" id="inputKondisi" class="w-full p-2 border rounded-xl text-sm font-bold">
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 transition-all uppercase text-xs tracking-widest shadow-lg">SIMPAN DATA</button>
        </form>
    </div>
</div>

<script>
    function tutupModal() {
        document.getElementById('modalTambah').classList.add('hidden');
        document.getElementById('formBarang').reset();
        document.getElementById('formBarang').action = "<?= base_url('admin/sarpras/save') ?>";
        document.getElementById('modalTitle').innerText = "Tambah Barang Baru";
        document.getElementById('inputKode').readOnly = false;
    }

    function editBarang(data) {
        document.getElementById('modalTambah').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = "Edit Barang: " + data.nama_barang;
        document.getElementById('formBarang').action = "<?= base_url('admin/sarpras/update') ?>";
        
        document.getElementById('inputId').value = data.id;
        document.getElementById('inputKode').value = data.kode_barang;
        document.getElementById('inputKode').readOnly = true; // Kode barang biasanya tidak boleh ganti
        document.getElementById('inputNama').value = data.nama_barang;
        document.getElementById('inputKategori').value = data.kategori;
        document.getElementById('inputLokasi').value = data.lokasi;
        document.getElementById('inputJumlah').value = data.jumlah;
        document.getElementById('inputKondisi').value = data.kondisi;
        document.getElementById('inputTgl').value = data.tgl_masuk;
    }
</script>

<?= $this->endSection(); ?>