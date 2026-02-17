<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700 rounded-t-lg">
    <div class="w-full mb-1">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Data Pendaftar PPDB</h1>
    </div>
</div>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">No. Daftar</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Nama & Sekolah</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Kontak</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php foreach($pendaftar as $p): ?>
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="p-4 text-base font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                <?= $p['no_pendaftaran'] ?><br>
                                <span class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($p['tgl_daftar'])) ?></span>
                            </td>
                            <td class="p-4 text-base font-medium text-gray-900 dark:text-white">
                                <div class="font-bold"><?= $p['nama_lengkap'] ?> (<?= $p['jk'] ?>)</div>
                                <div class="text-sm text-gray-500">Asal: <?= $p['asal_sekolah'] ?></div>
                            </td>
                            <td class="p-4 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-1"><i class="fas fa-phone text-xs"></i> <?= $p['no_hp_siswa'] ?? '-' ?></div>
                            </td>
                            <td class="p-4 text-base font-medium text-gray-900 dark:text-white">
                                <?php if($p['status_pendaftaran'] == 'Pending'): ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-300">Pending</span>
                                <?php elseif($p['status_pendaftaran'] == 'Diterima'): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-300">Diterima</span>
                                <?php else: ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-300">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 space-x-2 whitespace-nowrap">
                                <a href="<?= base_url('admin/spmb/detail/'.$p['id']) ?>" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Cek
                                </a>
                                <form action="<?= base_url('admin/spmb/delete/'.$p['id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Hapus data pendaftar ini permanen?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>