<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="mb-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Jadwal Mengajar</h1>
        <p class="text-sm text-gray-500">
            Halo, <span class="font-bold text-blue-600"><?= esc($guru['nama_lengkap']) ?></span>. Berikut adalah jadwal mengajar Anda semester ini.
        </p>
    </div>
</div>

<div class="p-4">
    <?php if(empty($jadwal)): ?>
        <div class="flex flex-col items-center justify-center p-8 bg-white border border-dashed border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700">
            <div class="p-3 bg-blue-50 rounded-full dark:bg-blue-900/20 mb-4">
                <i class="fas fa-calendar-times text-2xl text-blue-600 dark:text-blue-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum Ada Jadwal</h3>
            <p class="text-gray-500 text-sm mt-1 text-center">Admin belum mengatur jadwal mengajar untuk Anda.</p>
        </div>
    <?php else: ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php 
            // Grouping Jadwal per Hari
            $jadwalPerHari = [];
            foreach($jadwal as $j) {
                $jadwalPerHari[$j['hari']][] = $j;
            }
            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            ?>

            <?php foreach($hariList as $hari): ?>
                <?php if(isset($jadwalPerHari[$hari])): ?>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="font-bold text-gray-800 dark:text-white uppercase tracking-wide text-sm">
                            <?= $hari ?>
                        </h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                            <?= count($jadwalPerHari[$hari]) ?> Kelas
                        </span>
                    </div>
                    
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php foreach($jadwalPerHari[$hari] as $item): ?>
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <span class="bg-gray-100 text-gray-800 text-xs font-mono font-bold px-2 py-1 rounded border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                    <?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?>
                                </span>
                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                    <?= $item['nama_kelas'] ?>
                                </span>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                <?= $item['nama_mapel'] ?>
                            </h4>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>