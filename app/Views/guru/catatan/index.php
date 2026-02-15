<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">✍️ Catatan Wali Kelas</h1>
            <p class="text-sm text-gray-500">Input absensi rapor, catatan, dan kenaikan kelas.</p>
        </div>
        
        <form action="" method="GET" class="flex gap-2">
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
        
        <div class="mb-4">
            <a href="<?= base_url('guru/catatan/generate/'. $_GET['kelas_id']) ?>" onclick="return confirm('Sistem akan menghitung ulang jumlah S/I/A dari modul Presensi. Lanjutkan?')" class="px-4 py-2 bg-purple-600 text-white rounded-lg font-bold text-sm hover:bg-purple-700 shadow-lg shadow-purple-600/30 transition-all">
                <i class="fas fa-sync-alt mr-2"></i> TARIK DATA ABSENSI OTOMATIS
            </a>
        </div>

        <form action="<?= base_url('guru/catatan/save') ?>" method="POST">
            <?= csrf_field(); ?>
            <input type="hidden" name="kelas_id" value="<?= $_GET['kelas_id'] ?>">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-800 text-white uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 w-10">No</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3 text-center w-20 bg-amber-600">Sakit</th>
                            <th class="px-4 py-3 text-center w-20 bg-blue-600">Izin</th>
                            <th class="px-4 py-3 text-center w-20 bg-red-600">Alpha</th>
                            <th class="px-4 py-3 w-1/3">Catatan / Motivasi</th>
                            <th class="px-4 py-3">Keputusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php $no=1; foreach($siswa as $s): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 text-center"><?= $no++ ?></td>
                            <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">
                                <?= $s['nama_lengkap'] ?>
                                <div class="text-[10px] text-gray-400 font-normal"><?= $s['nis'] ?></div>
                            </td>
                            
                            <td class="px-2 py-3"><input type="number" readonly value="<?= $s['sakit'] ?? 0 ?>" class="w-full text-center bg-gray-100 border-0 rounded text-amber-600 font-bold"></td>
                            <td class="px-2 py-3"><input type="number" readonly value="<?= $s['izin'] ?? 0 ?>" class="w-full text-center bg-gray-100 border-0 rounded text-blue-600 font-bold"></td>
                            <td class="px-2 py-3"><input type="number" readonly value="<?= $s['alpha'] ?? 0 ?>" class="w-full text-center bg-gray-100 border-0 rounded text-red-600 font-bold"></td>

                            <td class="px-2 py-3">
                                <textarea name="catatan[<?= $s['id'] ?>]" rows="2" class="w-full text-xs p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500" placeholder="Tulis catatan..."><?= $s['catatan'] ?></textarea>
                            </td>

                            <td class="px-2 py-3">
                                <select name="status[<?= $s['id'] ?>]" class="w-full text-xs p-2 bg-gray-50 border border-gray-300 rounded-lg">
                                    <option value="Naik Kelas" <?= ($s['status_naik'] == 'Naik Kelas') ? 'selected' : '' ?>>Naik Kelas</option>
                                    <option value="Tinggal Kelas" <?= ($s['status_naik'] == 'Tinggal Kelas') ? 'selected' : '' ?>>Tinggal Kelas</option>
                                    <option value="Lulus" <?= ($s['status_naik'] == 'Lulus') ? 'selected' : '' ?>>Lulus</option>
                                    <option value="Tidak Lulus" <?= ($s['status_naik'] == 'Tidak Lulus') ? 'selected' : '' ?>>Tidak Lulus</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 text-right">
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow-lg">
                        <i class="fas fa-save mr-2"></i> SIMPAN CATATAN
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>