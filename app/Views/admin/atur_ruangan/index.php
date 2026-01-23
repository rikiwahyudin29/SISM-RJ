<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-1">
    <div class="flex justify-between items-center mb-6 mt-14">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Atur Peserta Ruangan</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach($ruangan as $r): ?>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= $r['nama_ruangan'] ?></h3>
                        <p class="text-xs text-gray-500">
                            Terisi: <b class="text-blue-600"><?= $r['jumlah_siswa'] ?></b> Siswa
                        </p>
                    </div>
                </div>
                
                <hr class="mb-4 dark:border-slate-700">

                <a href="<?= base_url('admin/aturruangan/kelola/' . $r['id']) ?>" class="block w-full text-center text-white bg-purple-600 hover:bg-purple-700 font-bold rounded-lg text-sm px-5 py-2.5 transition">
                    <i class="fas fa-edit mr-2"></i> KELOLA PESERTA
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>