<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-1">
    <div class="flex justify-between items-center mb-6 mt-14">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Monitoring Ruangan (Admin)</h1>
    </div>

    <?php if(empty($ruangan)): ?>
        <div class="flex flex-col items-center justify-center p-12 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum Ada Ruangan</h3>
            <p class="text-gray-500 dark:text-gray-400">Silakan hubungi admin untuk pembuatan ruangan.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach($ruangan as $r): ?>
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 p-6 hover:scale-105 transition-transform duration-200 cursor-pointer relative group">
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-0.5 rounded border border-green-200">
                            <?= $r['jumlah_siswa'] ?> PC
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1 truncate" title="<?= $r['nama_ruangan'] ?>"><?= $r['nama_ruangan'] ?></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">Klik tombol di bawah untuk masuk.</p>

                    <a href="<?= base_url('guru/monitoring/lihat/' . $r['id']) ?>" class="flex items-center justify-center w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-lg text-sm px-5 py-3 shadow-md hover:shadow-lg transition-all">
                        MASUK RUANGAN 
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>