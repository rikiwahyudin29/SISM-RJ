<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Rekap Bulanan</h1>
        <form action="" method="get" class="flex gap-2">
            <input type="month" name="bulan" value="<?= $bulan ?>" class="px-4 py-2 border rounded-xl font-bold text-sm" onchange="this.form.submit()">
            
            <a href="<?= base_url('siswa/presensi/cetak_rekap?bulan='.$bulan) ?>" target="_blank" class="bg-rose-600 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print
            </a>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-emerald-100 p-4 rounded-2xl text-center">
            <div class="text-2xl font-black text-emerald-600"><?= $total['H'] ?></div>
            <div class="text-xs font-bold text-emerald-700 uppercase">Hadir</div>
        </div>
        <div class="bg-yellow-100 p-4 rounded-2xl text-center">
            <div class="text-2xl font-black text-yellow-600"><?= $total['T'] ?></div>
            <div class="text-xs font-bold text-yellow-700 uppercase">Telat</div>
        </div>
        <div class="bg-rose-100 p-4 rounded-2xl text-center">
            <div class="text-2xl font-black text-rose-600"><?= $total['S'] ?></div>
            <div class="text-xs font-bold text-rose-700 uppercase">Sakit</div>
        </div>
        <div class="bg-blue-100 p-4 rounded-2xl text-center">
            <div class="text-2xl font-black text-blue-600"><?= $total['I'] ?></div>
            <div class="text-xs font-bold text-blue-700 uppercase">Izin</div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6">
        <h3 class="font-bold text-slate-500 mb-4 text-sm uppercase tracking-wide">Detail Harian - <?= date('F Y', strtotime($bulan)) ?></h3>
        
        <div class="grid grid-cols-7 gap-2 text-center">
            <?php for($d=1; $d<=$jml_hari; $d++): 
                $st = $map[$d] ?? null;
                $bg = 'bg-slate-50 text-slate-300'; // Default Kosong
                
                if($st == 'Hadir') $bg = 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30';
                if($st == 'Terlambat') $bg = 'bg-yellow-400 text-white';
                if($st == 'Sakit') $bg = 'bg-rose-500 text-white';
                if($st == 'Izin') $bg = 'bg-blue-500 text-white';
                if($st == 'Alpha') $bg = 'bg-red-600 text-white';
            ?>
            <div class="aspect-square flex flex-col items-center justify-center rounded-xl font-bold text-sm <?= $bg ?>">
                <?= $d ?>
                <?php if($st): ?>
                    <span class="text-[8px] uppercase mt-1 opacity-80"><?= substr($st,0,1) ?></span>
                <?php endif; ?>
            </div>
            <?php endfor; ?>
        </div>
        
        <div class="mt-6 flex gap-4 justify-center text-xs text-slate-500">
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-emerald-500"></span> Hadir</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-yellow-400"></span> Telat</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-rose-500"></span> Sakit</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-blue-500"></span> Izin</span>
        </div>
    </div>
</div>
<?= $this->endSection() ?>