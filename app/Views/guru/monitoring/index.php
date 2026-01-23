<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-1">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mengawas Ujian</h1>
            <p class="text-sm text-gray-500">Pilih ruangan yang ingin Anda pantau secara realtime.</p>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                <i class="fas fa-clock mr-1"></i> <?= date('d M Y') ?>
            </span>
        </div>
    </div>

    <?php if(empty($ruangan)): ?>
        <div class="flex flex-col items-center justify-center p-10 bg-white dark:bg-slate-800 rounded-xl border border-dashed border-gray-300 dark:border-slate-700">
            <div class="p-4 bg-gray-100 dark:bg-slate-700 rounded-full mb-3">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum Ada Ruangan</h3>
            <p class="text-gray-500 text-sm mt-1">Hubungi Admin untuk membuat data ruangan ujian.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($ruangan as $r): ?>
                <div class="group relative bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 hover:shadow-lg hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-300 overflow-hidden">
                    
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>

                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <?php if($r['jumlah_siswa'] > 0): ?>
                                <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded-full border border-green-200 dark:bg-green-900 dark:text-green-300 dark:border-green-800 flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> LIVE
                                </span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded-full border border-gray-200 dark:bg-slate-700 dark:text-gray-400 dark:border-slate-600">
                                    KOSONG
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1 group-hover:text-blue-600 transition-colors">
                            <?= $r['nama_ruangan'] ?>
                        </h3>
                        
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Total Peserta: <b class="ml-1 text-gray-800 dark:text-gray-200"><?= $r['jumlah_siswa'] ?></b>
                        </div>

                        <a href="<?= base_url('guru/monitoring/lihat/' . $r['id']) ?>" class="flex items-center justify-center w-full text-white bg-gray-900 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all group-hover:shadow-lg group-hover:shadow-blue-500/30">
                            Masuk Ruangan <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>