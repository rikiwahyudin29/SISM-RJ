<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-6">
    
    <div class="bg-white p-6 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hasil Ujian: <?= esc($jadwal['judul_ujian']) ?></h1>
            <p class="text-gray-500 text-sm"><?= esc($jadwal['nama_mapel']) ?> • <?= date('d M Y', strtotime($jadwal['waktu_mulai'])) ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('guru/hasil/pdf/' . $jadwal['id']) ?>" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 shadow flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="<?= base_url('guru/hasil/excel/' . $jadwal['id']) ?>" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 shadow flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="<?= base_url('guru/ujian') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">Kembali</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs">
                <tr>
                    <th class="p-4 w-10 text-center">No</th>
                    <th class="p-4">NIS</th>
                    <th class="p-4">Nama Siswa</th>
                    <th class="p-4">Kelas</th>
                    <th class="p-4 text-center">Benar</th>
                    <th class="p-4 text-center">Salah</th>
                    <th class="p-4 text-center">Nilai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($nilai)): ?>
                    <tr><td colspan="7" class="p-8 text-center text-gray-400">Belum ada data nilai masuk.</td></tr>
                <?php else: ?>
                    <?php foreach($nilai as $i => $n): ?>
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="p-4 text-center"><?= $i + 1 ?></td>
                        <td class="p-4 font-mono"><?= esc($n['nis']) ?></td>
                        <td class="p-4 font-bold text-gray-700"><?= esc($n['nama_lengkap']) ?></td>
                        <td class="p-4 text-gray-600"><?= esc($n['nama_kelas']) ?></td>
                        <td class="p-4 text-center text-green-600 font-bold"><?= $n['jml_benar'] ?></td>
                        <td class="p-4 text-center text-red-600 font-bold"><?= $n['jml_salah'] ?></td>
                        <td class="p-4 text-center">
                            <span class="text-lg font-black text-blue-700"><?= $n['nilai'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>