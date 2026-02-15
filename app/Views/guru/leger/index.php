<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">🏆 Leger & Ranking</h1>
            <p class="text-sm text-gray-500">Rekapitulasi nilai seluruh siswa.</p>
        </div>
        
        <form action="" method="GET" class="flex gap-2">
            <select name="kelas_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">- Pilih Kelas -</option>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                        <?= $k['nama_kelas'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if(isset($_GET['kelas_id']) && !empty($_GET['kelas_id'])): ?>
    <a href="<?= base_url('guru/leger/cetak?kelas_id='.$_GET['kelas_id']) ?>" target="_blank" class="px-4 py-2.5 bg-gray-800 text-white rounded-lg font-bold hover:bg-gray-900 transition-all flex items-center gap-2">
        <i class="fas fa-print"></i> CETAK LEGER
    </a>
<?php endif; ?>
        </form>
        
    </div>

    <?php if(!empty($leger)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-gray-800 text-white uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 sticky left-0 bg-gray-800 z-10 w-10">Rank</th>
                        <th class="px-4 py-3 sticky left-10 bg-gray-800 z-10 min-w-[200px]">Nama Siswa</th>
                        <th class="px-4 py-3">NIS</th>
                        
                        <?php foreach($mapel as $m): ?>
                            <th class="px-4 py-3 text-center border-l border-gray-600" title="<?= $m['nama_mapel'] ?>">
                                <?= substr($m['nama_mapel'], 0, 3) ?> </th>
                        <?php endforeach; ?>

                        <th class="px-4 py-3 text-center bg-emerald-600">Total</th>
                        <th class="px-4 py-3 text-center bg-blue-600">Rata²</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach($leger as $row): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-center font-bold sticky left-0 bg-white dark:bg-gray-800 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            <?php if($row['ranking'] == 1): ?>
                                <span class="text-yellow-500"><i class="fas fa-crown"></i> 1</span>
                            <?php elseif($row['ranking'] == 2): ?>
                                <span class="text-gray-400"><i class="fas fa-medal"></i> 2</span>
                            <?php elseif($row['ranking'] == 3): ?>
                                <span class="text-amber-700"><i class="fas fa-medal"></i> 3</span>
                            <?php else: ?>
                                <?= $row['ranking'] ?>
                            <?php endif; ?>
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white sticky left-10 bg-white dark:bg-gray-800 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            <?= $row['nama'] ?>
                        </td>
                        
                        <td class="px-4 py-3 text-gray-500"><?= $row['nis'] ?></td>

                        <?php foreach($mapel as $m): ?>
                            <?php $n = $row['nilai'][$m['id']]; ?>
                            <td class="px-4 py-3 text-center border-l border-gray-100 dark:border-gray-700 <?= ($n < 75 && $n > 0) ? 'text-red-500 font-bold' : '' ?>">
                                <?= ($n > 0) ? $n : '-' ?>
                            </td>
                        <?php endforeach; ?>

                        <td class="px-4 py-3 text-center font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/10">
                            <?= $row['total'] ?>
                        </td>
                        <td class="px-4 py-3 text-center font-black text-blue-600 bg-blue-50 dark:bg-blue-900/10">
                            <?= $row['rata'] ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if(empty($leger) && isset($_GET['kelas_id'])): ?>
            <div class="p-8 text-center text-gray-400">
                Belum ada data nilai yang masuk untuk kelas ini.
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>