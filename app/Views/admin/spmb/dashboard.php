<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in-up">
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/30">
        <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2">
            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
        </div>
        <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Pendaftar</p>
        <h3 class="text-4xl font-bold mt-1"><?= $stats['total'] ?></h3>
        <p class="text-blue-200 text-xs mt-2">Calon Siswa Baru</p>
    </div>

    <div class="relative overflow-hidden bg-gradient-to-r from-amber-400 to-amber-500 rounded-2xl p-6 text-white shadow-lg shadow-amber-500/30">
        <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2">
            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </div>
        <p class="text-amber-100 text-sm font-medium uppercase tracking-wider">Perlu Verifikasi</p>
        <h3 class="text-4xl font-bold mt-1"><?= $stats['pending'] ?></h3>
        <p class="text-amber-100 text-xs mt-2">Berkas Belum Dicek</p>
    </div>

    <div class="relative overflow-hidden bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg shadow-emerald-500/30">
        <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2">
            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        </div>
        <p class="text-emerald-100 text-sm font-medium uppercase tracking-wider">Lolos Seleksi</p>
        <h3 class="text-4xl font-bold mt-1"><?= $stats['diterima'] ?></h3>
        <p class="text-emerald-100 text-xs mt-2">Siap Migrasi Kelas</p>
    </div>

    <div class="relative overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Tidak Lolos</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1"><?= $stats['ditolak'] ?></h3>
            </div>
            <div class="bg-red-100 p-3 rounded-full text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Minat Pendaftar</h4>
        <div class="space-y-4">
            <?php foreach($jurusan as $j): 
                $persen = ($stats['total'] > 0) ? ($j['jumlah'] / $stats['total']) * 100 : 0;
            ?>
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?= $j['jurusan_minat'] ?></span>
                    <span class="text-sm font-medium text-blue-600"><?= $j['jumlah'] ?> Siswa</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= $persen ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h4 class="text-lg font-bold text-gray-800 dark:text-white">Pendaftaran Terbaru</h4>
            <a href="<?= base_url('admin/spmb/pendaftar') ?>" class="text-sm text-blue-600 hover:underline font-medium">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Nama Lengkap</th>
                        <th class="px-6 py-3">Asal Sekolah</th>
                        <th class="px-6 py-3">Jurusan</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($terbaru as $t): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                            <?= $t['nama_lengkap'] ?>
                        </td>
                        <td class="px-6 py-4"><?= $t['asal_sekolah'] ?></td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                <?= $t['jurusan_minat'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs"><?= date('d M Y', strtotime($t['tgl_daftar'])) ?></td>
                        <td class="px-6 py-4">
                            <?php if($t['status_pendaftaran'] == 'Diterima'): ?>
                                <span class="flex w-3 h-3 bg-green-500 rounded-full"></span>
                            <?php elseif($t['status_pendaftaran'] == 'Pending'): ?>
                                <span class="flex w-3 h-3 bg-amber-500 rounded-full animate-pulse"></span>
                            <?php else: ?>
                                <span class="flex w-3 h-3 bg-red-500 rounded-full"></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>