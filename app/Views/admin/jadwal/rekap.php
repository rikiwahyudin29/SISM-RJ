<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Rekap Beban Mengajar</h1>
        <p class="text-sm text-gray-500">
            Akumulasi jam tatap muka per minggu. Tahun Ajaran: <span class="font-bold text-blue-600"><?= !empty($tahun) ? esc($tahun['tahun_ajaran']) : '-' ?></span>
        </p>
    </div>

    <div class="flex gap-2">
        <a href="<?= base_url('admin/jadwal') ?>" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
            &larr; Kembali ke Jadwal
        </a>
        <a href="<?= base_url('admin/jadwal/rekap/cetak') ?>" target="_blank" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 shadow-lg">
    <i class="fas fa-print mr-2"></i> Cetak Laporan PDF
</a>
    </div>
</div>

<div class="p-4">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3" width="5%">No</th>
                    <th class="px-6 py-3">Nama Guru / NIP</th>
                    <th class="px-6 py-3">Kelas yang Diajar</th>
                    <th class="px-6 py-3 text-center bg-blue-50 dark:bg-blue-900/20">Total Durasi (Waktu)</th>
                    <th class="px-6 py-3 text-center bg-green-50 dark:bg-green-900/20">Total JP (40m)</th>
                    <th class="px-6 py-3 text-center bg-yellow-50 dark:bg-yellow-900/20">Total JP (45m)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($rekap)): ?>
                    <tr><td colspan="6" class="text-center p-8 italic">Belum ada data jadwal untuk dikalkulasi.</td></tr>
                <?php else: ?>
                    <?php $no = 1; foreach($rekap as $r): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                        <td class="px-6 py-4 text-center"><?= $no++ ?></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white"><?= $r['nama'] ?></div>
                            <div class="text-xs text-gray-500">NIP: <?= $r['nip'] ?? '-' ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                <?php foreach($r['kelas_ajar'] as $kls): ?>
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded border border-gray-300">
                                        <?= $kls ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-mono font-bold bg-blue-50/50 dark:bg-blue-900/10">
                            <?= $r['jam_asli'] ?>
                            <div class="text-[10px] text-gray-400">(<?= $r['total_menit'] ?> Menit)</div>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-green-600 bg-green-50/50 dark:bg-green-900/10 text-lg">
                            <?= $r['total_jp_40'] ?> <span class="text-xs text-gray-500">JP</span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-yellow-600 bg-yellow-50/50 dark:bg-yellow-900/10 text-lg">
                            <?= $r['total_jp_45'] ?> <span class="text-xs text-gray-500">JP</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="p-4 text-xs text-gray-500 italic">
            * Kolom JP (Jam Pelajaran) adalah estimasi. 
            <br>JP (40m) = Total Menit dibagi 40. 
            <br>JP (45m) = Total Menit dibagi 45.
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .p-4, .p-4 * { visibility: visible; }
        .p-4 { position: absolute; left: 0; top: 0; width: 100%; }
        /* Sembunyikan tombol saat print */
        button, a { display: none !important; }
    }
</style>

<?= $this->endSection() ?>