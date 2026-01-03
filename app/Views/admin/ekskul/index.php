<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="p-4 sm:ml-12">

    <div class="flex flex-col md:flex-row items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Ekstrakurikuler</h1>
            <p class="text-gray-500 text-sm">Kelola kegiatan dan anggota ekskul.</p>
        </div>
        <a href="<?= base_url('admin/ekskul/tambah') ?>" class="mt-4 md:mt-0 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Ekskul
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">No</th>
                        <th class="px-6 py-4">Nama Ekskul</th>
                        <th class="px-6 py-4">Pembina</th>
                        <th class="px-6 py-4 text-center">Jadwal</th>
                        <th class="px-6 py-4 text-center">Peserta</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($ekskul)): ?>
                        <tr><td colspan="6" class="px-6 py-8 text-center">Belum ada data ekskul.</td></tr>
                    <?php else: ?>
                        <?php $i = 1; foreach ($ekskul as $e): ?>
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 text-center font-bold"><?= $i++ ?></td>
                            
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-base">
                                <?= $e['nama_ekskul'] ?>
                            </td>
                            
                            <td class="px-6 py-4">
                                <?php if($e['nama_pembina']): ?>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                            <?= substr($e['nama_pembina'], 0, 1) ?>
                                        </div>
                                        <span class="text-gray-900 dark:text-white"><?= $e['nama_pembina'] ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <?php if($e['hari']): ?>
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded border border-purple-200">
                                        <?= $e['hari'] ?>, <?= $e['jam'] ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="<?= base_url('admin/ekskul/detail/'.$e['id']) ?>" class="inline-flex items-center text-blue-600 hover:underline font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <?= $e['jumlah_peserta'] ?> Anggota
                                </a>
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('admin/ekskul/edit/'.$e['id']) ?>" class="text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded px-2.5 py-1.5 text-xs">Edit</a>
                                    <a href="<?= base_url('admin/ekskul/hapus/'.$e['id']) ?>" onclick="return confirm('Yakin hapus ekskul ini? Data anggota juga akan terhapus.')" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded px-2.5 py-1.5 text-xs">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>