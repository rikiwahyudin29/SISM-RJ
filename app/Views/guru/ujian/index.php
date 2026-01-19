<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Manajemen Jadwal Ujian</h1>
    </div>
</div>

<div class="p-4">
    <a href="<?= base_url('guru/ujian/tambah') ?>" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-4 focus:outline-none flex w-fit items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Jadwal Baru
    </a>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Bank Soal / Mapel</th>
                    <th scope="col" class="px-6 py-3">Waktu Pelaksanaan</th>
                    <th scope="col" class="px-6 py-3">Token</th>
                    <th scope="col" class="px-6 py-3">Kelas</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jadwal as $j): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        <?= $j['judul_ujian'] ?>
                        <div class="text-xs text-gray-500"><?= $j['nama_mapel'] ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col text-xs">
                            <span class="text-green-600 font-bold">Mulai: <?= $j['waktu_mulai'] ?></span>
                            <span class="text-red-600">Selesai: <?= $j['waktu_selesai'] ?></span>
                            <span class="text-gray-500 mt-1">Durasi: <?= $j['durasi'] ?> Menit</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2.5 py-0.5 rounded border border-purple-400">
                            <?= $j['token'] ?? 'BEBAS' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            <?= $j['total_kelas'] ?> Kelas
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="<?= base_url('guru/ujian/monitoring/' . $j['id']) ?>" class="font-medium text-blue-600 hover:underline">Monitor</a>
                        <a href="<?= base_url('guru/ujian/hapus/' . $j['id']) ?>" onclick="return confirm('Hapus jadwal ini?')" class="font-medium text-red-600 hover:underline">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>