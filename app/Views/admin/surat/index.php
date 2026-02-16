<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🖨️ E-Arsip & Surat Otomatis</h1>
            <p class="text-sm text-gray-500">Buat surat dalam hitungan detik menggunakan template.</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('admin/templatesurat') ?>" class="px-4 py-2 bg-gray-800 text-white rounded-xl font-bold hover:bg-black text-xs flex items-center">
                ⚙️ ATUR TEMPLATE
            </a>
            <button onclick="document.getElementById('modalBuat').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 text-xs flex items-center">
                + BUAT SURAT BARU
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black border-b">
                <tr>
                    <th class="px-6 py-4">No. Surat</th>
                    <th class="px-6 py-4">Tujuan / Siswa</th>
                    <th class="px-6 py-4">Perihal</th>
                    <th class="px-6 py-4 text-center">Tgl Surat</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($surat as $s): ?>
                <tr class="hover:bg-gray-50 border-b">
                    <td class="px-6 py-4 font-bold text-gray-800"><?= $s['no_surat'] ?></td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-indigo-600"><?= $s['nama_siswa'] ?></span>
                    </td>
                    <td class="px-6 py-4"><?= $s['perihal'] ?></td>
                    <td class="px-6 py-4 text-center text-xs font-bold text-gray-500">
                        <?= date('d M Y', strtotime($s['tgl_surat'])) ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?= base_url('admin/surat/cetak/'.$s['id']) ?>" target="_blank" class="text-indigo-600 font-bold hover:underline text-xs mr-2">CETAK PDF</a>
                        <a href="<?= base_url('admin/surat/delete/'.$s['id']) ?>" onclick="return confirm('Hapus?')" class="text-rose-500 font-bold hover:underline text-xs">HAPUS</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalBuat" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6">
        <h3 class="font-bold text-lg mb-4 text-gray-800 uppercase">Buat Surat Otomatis</h3>
        
        <form action="<?= base_url('admin/surat/generate') ?>" method="POST" class="space-y-4">
            <?= csrf_field(); ?>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">1. Pilih Jenis Surat (Template)</label>
                <select name="template_id" class="w-full p-3 border rounded-xl font-bold bg-gray-50" required>
                    <option value="">-- Pilih Template --</option>
                    <?php foreach($templates as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= $t['nama_template'] ?> (Format: <?= $t['format_nomor'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <p class="text-[10px] text-gray-400 mt-1">Nomor surat akan digenerate otomatis sesuai format template.</p>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">2. Pilih Siswa (Tujuan)</label>
                <select name="siswa_id" class="w-full p-3 border rounded-xl font-bold" required>
                    <option value="">-- Cari Nama Siswa --</option>
                    <?php foreach($siswa as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['nama_lengkap'] ?> - <?= $s['nama_kelas'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="bg-indigo-50 p-3 rounded-xl border border-indigo-100">
                <p class="text-[10px] text-indigo-700 font-bold">INFO:</p>
                <p class="text-[10px] text-indigo-600">Sistem akan otomatis mengganti {NAMA}, {NIS}, {KELAS} di dalam surat dengan data siswa yang dipilih.</p>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('modalBuat').classList.add('hidden')" class="px-4 py-2 text-gray-500 font-bold">BATAL</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl shadow-lg">GENERATE SURAT</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>