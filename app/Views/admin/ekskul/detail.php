<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="p-4 sm:ml-12">

    <div class="mb-4">
        <a href="<?= base_url('admin/ekskul') ?>" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-gray-400">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Ekskul
        </a>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><?= $ekskul['nama_ekskul'] ?></h1>
            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Pembina: <b><?= $ekskul['nama_pembina'] ?? '-' ?></b>
                </span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal: <b><?= $ekskul['hari'] ?>, <?= $ekskul['jam'] ?></b>
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <h2 class="font-semibold text-gray-900 dark:text-white">Daftar Anggota</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">No</th>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4 text-center">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($anggota)): ?>
                        <tr><td colspan="4" class="px-6 py-8 text-center">Belum ada siswa yang mendaftar ekskul ini.</td></tr>
                    <?php else: ?>
                        <?php $i=1; foreach($anggota as $siswa): ?>
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 text-center"><?= $i++ ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white"><?= $siswa['nama'] ?></td>
                            <td class="px-6 py-4"><?= $siswa['nis'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-200">
                                    <?= $siswa['nama_kelas'] ?? '-' ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t dark:border-gray-700 text-sm text-gray-500">
            Total Anggota: <b><?= count($anggota) ?></b> Siswa
        </div>
    </div>

</div>
<?= $this->endSection() ?>