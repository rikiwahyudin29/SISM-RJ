<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-1">
    <div class="flex justify-between items-center mb-6 mt-14">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Monitoring Ruangan (CBT)</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach($ruangan as $r): ?>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 p-6 hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-0.5 rounded border border-green-200">ONLINE</span>
                </div>
                
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1"><?= $r['nama_ruangan'] ?></h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">Klik untuk melihat aktivitas siswa.</p>

                <a href="<?= base_url('admin/monitoringruang/lihat/' . $r['id']) ?>" class="block w-full text-center text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-lg text-sm px-5 py-3 shadow-md hover:shadow-lg transition-all">
                    MASUK RUANGAN <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>