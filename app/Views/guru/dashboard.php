<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="space-y-6 container mx-auto px-4 py-8">
    
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-2xl font-black">Halo, <?= session()->get('nama_lengkap') ?>! 👋</h1>
            <p class="text-blue-100 mt-1 text-sm">Selamat bertugas mencerdaskan kehidupan bangsa.</p>
            <p class="mt-4 text-xs font-mono bg-white/20 inline-block px-3 py-1 rounded-lg">
                📅 <?= $hari_ini ?>, <?= date('d M Y', strtotime($tanggal)) ?>
            </p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-10 translate-y-10">
            <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-orange-100 text-orange-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold">Jadwal Hari Ini</p>
                    <h4 class="text-xl font-black text-slate-800 dark:text-white"><?= $total_jadwal ?> <span class="text-xs font-normal text-slate-400">Kelas</span></h4>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold">Jurnal Bulan Ini</p>
                    <h4 class="text-xl font-black text-slate-800 dark:text-white"><?= $total_jurnal ?> <span class="text-xs font-normal text-slate-400">Laporan</span></h4>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            📚 Jadwal Mengajar Hari Ini
        </h3>

        <?php if(empty($jadwal)): ?>
            <div class="text-center p-8 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-400 italic">Tidak ada jadwal mengajar hari ini. Happy Rest! ☕</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach($jadwal as $j): ?>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:shadow-md transition-all">
                    
                    <div class="flex items-start gap-4">
                        <div class="text-center min-w-[60px]">
                            <span class="block text-sm font-black text-slate-800 dark:text-white"><?= substr($j['jam_mulai'],0,5) ?></span>
                            <span class="block text-xs text-slate-400">s/d</span>
                            <span class="block text-sm font-black text-slate-800 dark:text-white"><?= substr($j['jam_selesai'],0,5) ?></span>
                        </div>
                        
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-lg"><?= $j['nama_kelas'] ?></h4>
                            <p class="text-sm text-blue-600 font-medium"><?= $j['nama_mapel'] ?></p>
                        </div>
                    </div>

                    <div>
                        <?php if($j['sudah_jurnal']): ?>
                            <span class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-xs font-bold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Sudah Lapor
                            </span>
                        <?php else: ?>
                            <a href="<?= base_url('guru/jurnal/input') ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg shadow-blue-500/30 transition-transform active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Isi Jurnal
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>