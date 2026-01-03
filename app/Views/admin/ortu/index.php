<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Data Orang Tua / Wali</h1>
        </div>
        <div class="flex justify-between">
            <div class="flex items-center">
                </div>
            <a href="<?= base_url('admin/ortu/tambah') ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                Tambah Data Wali
            </a>
        </div>
    </div>
</div>

<?php if(session()->getFlashdata('message')): ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">#</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Siswa & Kelas</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Nama Ayah</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Nama Ibu</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Kontak (HP)</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Pekerjaan Ayah</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php if(empty($ortu)): ?>
                            <tr><td colspan="7" class="p-4 text-center text-gray-500">Belum ada data orang tua.</td></tr>
                        <?php else: ?>
                            <?php $no=1; foreach($ortu as $o): ?>
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="p-4 text-base font-medium text-gray-900 dark:text-white"><?= $no++ ?></td>
                                <td class="p-4">
                                    <div class="text-base font-semibold text-gray-900 dark:text-white"><?= $o['nama_siswa'] ?></div>
                                    <div class="text-xs text-gray-500">Kelas: <?= $o['nama_kelas'] ?? '-' ?></div>
                                </td>
                                <td class="p-4 text-base font-medium text-gray-900 dark:text-white"><?= $o['nama_ayah'] ?></td>
                                <td class="p-4 text-base font-medium text-gray-900 dark:text-white"><?= $o['nama_ibu'] ?? '-' ?></td>
                                <td class="p-4 text-base font-medium text-green-600 dark:text-green-400">
                                    <i class="fab fa-whatsapp mr-1"></i> <?= $o['no_hp_ortu'] ?>
                                </td>
                                <td class="p-4 text-base text-gray-500 dark:text-gray-400"><?= $o['pekerjaan_ayah'] ?></td>
                                <td class="p-4 space-x-2 whitespace-nowrap">
                                    <a href="<?= base_url('admin/ortu/edit/' . $o['id']) ?>" class="text-blue-600 hover:underline">Edit</a>
                                    <a href="<?= base_url('admin/ortu/delete/' . $o['id']) ?>" onclick="return confirm('Hapus data ini?')" class="text-red-600 hover:underline">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>