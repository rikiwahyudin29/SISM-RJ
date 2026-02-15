<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📊 Transkrip Nilai Akademik</h1>
            <p class="text-sm text-gray-500">Laporan hasil belajar (Rapor) semester ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 border-blue-500">
            <p class="text-xs text-gray-400 uppercase font-bold">Total Mapel</p>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= count($nilai) ?></h3>
        </div>
        
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 border-emerald-500">
            <p class="text-xs text-gray-400 uppercase font-bold">Rata-rata</p>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                <?php 
                    $total_nilai = 0; 
                    foreach($nilai as $n) $total_nilai += $n['akhir'];
                    echo (count($nilai) > 0) ? round($total_nilai / count($nilai), 2) : 0;
                ?>
            </h3>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 border-purple-500">
            <p class="text-xs text-gray-400 uppercase font-bold">Predikat Umum</p>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                <?php 
                    $avg = (count($nilai) > 0) ? ($total_nilai / count($nilai)) : 0;
                    if($avg >= 90) echo 'A';
                    elseif($avg >= 80) echo 'B';
                    elseif($avg >= 75) echo 'C';
                    else echo 'D';
                ?>
            </h3>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 border-amber-500">
            <p class="text-xs text-gray-400 uppercase font-bold">Kehadiran (S/I/A)</p>
            <div class="flex gap-2 mt-1">
                <span class="text-xs font-bold px-2 py-1 bg-amber-100 text-amber-700 rounded">S: <?= $catatan->sakit ?? 0 ?></span>
                <span class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-700 rounded">I: <?= $catatan->izin ?? 0 ?></span>
                <span class="text-xs font-bold px-2 py-1 bg-red-100 text-red-700 rounded">A: <?= $catatan->alpha ?? 0 ?></span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
        <div class="p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 dark:text-white">Rincian Nilai Mata Pelajaran</h3>
            <span class="text-xs text-gray-500">*KKM: Kriteria Ketuntasan Minimal</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Mata Pelajaran</th>
                        <th class="px-4 py-3 text-center">KKM</th>
                        <th class="px-4 py-3 text-center">Tugas</th>
                        <th class="px-4 py-3 text-center">UH</th>
                        <th class="px-4 py-3 text-center">PTS</th>
                        <th class="px-4 py-3 text-center">PAS</th>
                        <th class="px-4 py-3 text-center bg-blue-50 dark:bg-blue-900/20 text-blue-600">Akhir</th>
                        <th class="px-4 py-3 text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach($nilai as $n): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            <?= $n['nama_mapel'] ?>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-400"><?= $n['kkm'] ?? 75 ?></td>
                        <td class="px-4 py-3 text-center"><?= $n['tugas'] ?></td>
                        <td class="px-4 py-3 text-center"><?= $n['uh'] ?></td>
                        <td class="px-4 py-3 text-center"><?= $n['pts'] ?></td>
                        <td class="px-4 py-3 text-center"><?= $n['pas'] ?></td>
                        <td class="px-4 py-3 text-center font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/10">
                            <?= $n['akhir'] ?>
                        </td>
                        <td class="px-4 py-3 text-center font-bold">
                            <?php 
                                $p = $n['predikat'];
                                if($p == 'A') echo '<span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs">A</span>';
                                elseif($p == 'B') echo '<span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs">B</span>';
                                elseif($p == 'C') echo '<span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-xs">C</span>';
                                else echo '<span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs">D</span>';
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($nilai)): ?>
                        <tr><td colspan="8" class="text-center py-6 text-gray-500">Belum ada nilai akademik yang masuk.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($catatan): ?>
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg text-white p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
        <div class="relative z-10">
            <h3 class="text-lg font-bold mb-3"><i class="fas fa-comment-dots mr-2"></i> Catatan Wali Kelas</h3>
            <div class="bg-white/10 p-4 rounded-xl border border-white/20 mb-4 backdrop-blur-sm">
                <p class="italic text-lg">"<?= $catatan->catatan ?? 'Tetap semangat!' ?>"</p>
            </div>
            <?php if(isset($catatan->status_naik)): ?>
            <div class="flex items-center gap-3">
                <span class="text-sm opacity-80">Keputusan:</span>
                <span class="px-4 py-1 bg-white text-indigo-700 rounded-full text-sm font-extrabold shadow-md uppercase">
                    <?= $catatan->status_naik ?>
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
<?= $this->endSection(); ?>