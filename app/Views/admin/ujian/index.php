<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:ml-2">
    <div class="flex justify-between items-center mb-6 mt-14">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Monitoring Jadwal Ujian</h1>
        <a href="<?= base_url('admin/jadwalujian/tambah') ?>" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">
            <i class="fas fa-plus mr-2"></i> Buat Jadwal
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-slate-800">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-slate-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Nama Ujian</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Guru Pengampu</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Jenis</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Waktu</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Token</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Status</th>
                    <th scope="col" class="px-6 py-3 text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($jadwal)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Belum ada jadwal ujian yang dibuat.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jadwal as $j) : ?>
                        <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600">
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900 dark:text-white"><?= $j['nama_ujian'] ?></div>
                                <div class="text-xs text-gray-500"><?= $j['judul_ujian'] ?></div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded border border-purple-400">
                                    <?= $j['nama_guru'] ?? 'Tanpa Guru' ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap"><?= $j['nama_jenis'] ?? '-' ?></td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col text-xs gap-1">
                                    <span class="text-blue-600 font-mono">Mulai: <?= date('d/m H:i', strtotime($j['waktu_mulai'])) ?></span>
                                    <span class="text-red-600 font-mono">Selesai: <?= date('d/m H:i', strtotime($j['waktu_selesai'])) ?></span>
                                </div>
                            </td>

                            <td class="px-6 py-4 font-mono font-bold whitespace-nowrap">
                                <?= $j['setting_token'] == 1 ? $j['token'] : '-' ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($j['status_ujian'] == 'AKTIF'): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">AKTIF</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">NONAKTIF</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <a href="<?= base_url('admin/jadwalujian/edit/' . $j['id']) ?>" class="font-medium text-blue-600 hover:underline mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= base_url('admin/jadwalujian/hapus/' . $j['id']) ?>" onclick="return confirm('Hapus jadwal ini?')" class="font-medium text-red-600 hover:underline">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>