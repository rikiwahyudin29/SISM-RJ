<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Absensi Pelajaran</h1>
            <p class="text-sm text-slate-500">Rekap kehadiranmu di setiap mata pelajaran.</p>
        </div>
        
        <form action="" method="get">
            <input type="month" name="bulan" value="<?= $bulan ?>" class="px-4 py-2 border rounded-xl font-bold text-sm bg-white" onchange="this.form.submit()">
        </form>
    </div>

    <div class="space-y-4">
        <?php if(empty($data)): ?>
            <div class="p-10 text-center bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-400 italic">Belum ada data absensi pelajaran bulan ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($data as $d): ?>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex gap-4 items-start group hover:shadow-md transition-all">
                    
                    <div class="w-20 h-20 shrink-0 bg-slate-100 rounded-xl overflow-hidden relative cursor-pointer" onclick="window.open('<?= base_url('uploads/jurnal/'.$d['foto_kegiatan']) ?>')">
                        <?php if($d['foto_kegiatan']): ?>
                            <img src="<?= base_url('uploads/jurnal/'.$d['foto_kegiatan']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300 text-[10px]">No Foto</div>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-slate-800 dark:text-white truncate text-sm sm:text-base">
                                <?= $d['nama_mapel'] ?>
                            </h4>
                            
                            <?php 
                                $bg = 'bg-slate-100 text-slate-500';
                                if($d['status'] == 'H') $bg = 'bg-emerald-100 text-emerald-600';
                                if($d['status'] == 'S') $bg = 'bg-blue-100 text-blue-600';
                                if($d['status'] == 'I') $bg = 'bg-yellow-100 text-yellow-600';
                                if($d['status'] == 'A') $bg = 'bg-rose-100 text-rose-600';
                            ?>
                            <span class="px-2 py-1 rounded-lg text-xs font-black <?= $bg ?>">
                                <?= $d['status'] == 'H' ? 'HADIR' : ($d['status'] == 'S' ? 'SAKIT' : ($d['status'] == 'I' ? 'IZIN' : 'ALPHA')) ?>
                            </span>
                        </div>
                        
                        <div class="text-xs text-slate-500 mb-2">
                            <span class="font-bold text-blue-600"><?= $d['nama_guru'] ?></span> • 
                            <?= date('d M Y', strtotime($d['tanggal'])) ?> • Jam <?= $d['jam_ke'] ?>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-300 italic bg-slate-50 dark:bg-slate-700/50 p-2 rounded-lg line-clamp-2">
                            "<?= esc($d['materi']) ?>"
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>