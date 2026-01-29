<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Riwayat Jurnal</h1>
            <p class="text-sm text-slate-500">Rekap aktivitas mengajar Anda.</p>
        </div>
        <form action="" method="get" class="flex items-center gap-2">
            <input type="month" name="bulan" value="<?= $bulan ?>" onchange="this.form.submit()" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
            
            <a href="<?= base_url('guru/jurnal/input') ?>" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Isi Jurnal
            </a>
        </form>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="p-4 mb-6 bg-rose-100 text-rose-700 rounded-xl border border-rose-200 flex items-start gap-3">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <p class="font-bold">Info Debug:</p>
                <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="space-y-4">
        <?php if(empty($jurnal)): ?>
            <div class="p-10 text-center bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-500 font-medium">Belum ada jurnal bulan ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($jurnal as $j): ?>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-all relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 group-hover:bg-blue-600 transition-colors"></div>
                
                <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                    <div class="flex gap-4 items-center">
                        <div class="text-center bg-slate-50 dark:bg-slate-700/50 p-2 rounded-lg min-w-[60px]">
                            <span class="block text-xs font-bold text-slate-400 uppercase"><?= date('M', strtotime($j['tanggal'])) ?></span>
                            <span class="block text-xl font-black text-slate-800 dark:text-white"><?= date('d', strtotime($j['tanggal'])) ?></span>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-bold text-lg text-slate-800 dark:text-white"><?= $j['nama_mapel'] ?? '<span class="text-rose-500">Mapel Hapus</span>' ?></h3>
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-full">
                                    Jam <?= $j['jam_ke'] ?>
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 font-medium mb-1">
                                🏫 <?= $j['nama_kelas'] ?? '<span class="text-rose-500">Kelas Hapus</span>' ?> 
                            </p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 italic line-clamp-1">
                                "<?= $j['materi'] ?>"
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                         <a href="<?= base_url('guru/jurnal/absen/'.$j['id']) ?>" class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-xs font-bold transition-colors">
                            Cek Absensi
                         </a>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-700 text-[10px] text-slate-300 flex gap-3 font-mono">
                    <span>ID Jurnal: <b><?= $j['id'] ?></b></span> | 
                    <span>Semester ID: <b><?= $j['id_tahun_ajaran'] ?></b></span> | 
                    <span>Kelas ID: <b><?= $j['id_kelas'] ?></b></span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>