<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">🖨️ Cetak Rapor Akhir</h1>
            <p class="text-sm text-gray-500">Pilih siswa untuk mencetak Laporan Hasil Belajar.</p>
        </div>
        
        <form action="" method="GET">
            <select name="kelas_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 block p-2.5">
                <option value="">- Pilih Kelas -</option>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                        <?= $k['nama_kelas'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if(!empty($siswa)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach($siswa as $s): ?>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div>
                <h5 class="font-bold text-gray-800 dark:text-white"><?= $s['nama_lengkap'] ?></h5>
                <p class="text-xs text-gray-400">NIS: <?= $s['nis'] ?></p>
            </div>
            <a href="<?= base_url('guru/rapor/cetak/' . $s['id']) ?>" target="_blank" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 shadow-lg shadow-blue-600/30">
                <i class="fas fa-print mr-1"></i> CETAK
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>