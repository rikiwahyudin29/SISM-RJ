<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">⚙️ Master Template Surat</h1>
            <p class="text-sm text-gray-500">Atur format nomor dan isi surat di sini.</p>
        </div>
        <button onclick="document.getElementById('modalTemplate').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg text-xs">
            + TAMBAH TEMPLATE
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach($templates as $t): ?>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative group">
            <h3 class="font-bold text-lg text-gray-800"><?= $t['nama_template'] ?></h3>
            <p class="text-xs text-gray-500 font-mono bg-gray-100 p-1 rounded inline-block mt-1"><?= $t['format_nomor'] ?></p>
            
            <div class="mt-3 text-xs text-gray-400 border-t pt-2 line-clamp-3">
                <?= strip_tags($t['isi_html']) ?>...
            </div>

            <a href="<?= base_url('admin/templatesurat/delete/'.$t['id']) ?>" onclick="return confirm('Hapus template ini?')" class="absolute top-4 right-4 text-rose-300 hover:text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="modalTemplate" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl p-6 overflow-y-auto max-h-[90vh]">
        <h3 class="font-bold text-lg mb-4 text-gray-800 uppercase">Tambah Template Baru</h3>
        <form action="<?= base_url('admin/templatesurat/save') ?>" method="POST" class="space-y-4">
            <?= csrf_field(); ?>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nama Surat</label>
                <input type="text" name="nama_template" class="w-full p-2 border rounded-xl" placeholder="Contoh: Surat Mutasi Keluar" required>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Format Nomor Otomatis</label>
                <input type="text" name="format_nomor" class="w-full p-2 border rounded-xl font-mono text-indigo-600" placeholder="422/{NO}/SMK/{THN}" value="421.5/{NO}/SMK/{THN}" required>
                <p class="text-[10px] text-gray-400 mt-1">Gunakan kode {NO} untuk nomor urut, {THN} untuk tahun, {BLN} untuk bulan.</p>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Isi Surat (HTML)</label>
                <textarea name="isi_html" rows="10" class="w-full p-2 border rounded-xl text-sm font-mono" placeholder="Gunakan tag HTML <p>, <br>. Gunakan variabel {NAMA}, {NIS}, {KELAS} untuk data siswa otomatis."></textarea>
                <div class="flex gap-2 mt-1">
                    <span class="text-[10px] bg-gray-100 px-2 rounded cursor-pointer select-all">{NAMA}</span>
                    <span class="text-[10px] bg-gray-100 px-2 rounded cursor-pointer select-all">{NIS}</span>
                    <span class="text-[10px] bg-gray-100 px-2 rounded cursor-pointer select-all">{KELAS}</span>
                    <span class="text-[10px] bg-gray-100 px-2 rounded cursor-pointer select-all">{ALAMAT}</span>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('modalTemplate').classList.add('hidden')" class="px-4 py-2 text-gray-500 font-bold">BATAL</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl">SIMPAN TEMPLATE</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>