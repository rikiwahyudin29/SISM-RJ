<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Riwayat Absensi</h1>
        <form action="" method="get">
            <input type="month" name="bulan" value="<?= $bulan ?>" class="px-4 py-2 border rounded-xl font-bold text-sm" onchange="this.form.submit()">
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <?php if(empty($data)): ?>
            <div class="p-8 text-center text-slate-400 italic">Belum ada data absensi bulan ini.</div>
        <?php else: ?>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach($data as $d): ?>
                <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl
                            <?= ($d['status_kehadiran']=='Hadir') ? 'bg-emerald-100 text-emerald-600' : 
                               (($d['status_kehadiran']=='Terlambat') ? 'bg-yellow-100 text-yellow-600' : 
                               (($d['status_kehadiran']=='Sakit') ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600')) ?>">
                            <?= ($d['status_kehadiran']=='Hadir') ? '👋' : (($d['status_kehadiran']=='Sakit') ? '😷' : '📅') ?>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800 dark:text-white text-lg">
                                <?= date('d F Y', strtotime($d['tanggal'])) ?>
                            </div>
                            <div class="text-xs text-slate-500">
                                <?= $d['metode'] ?> • <?= $d['status_verifikasi'] ?? 'Auto' ?>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-mono font-bold text-slate-700 dark:text-slate-300">
                            <?= $d['jam_masuk'] ? substr($d['jam_masuk'], 0, 5) : '--:--' ?>
                            <span class="text-slate-300 mx-1">-</span>
                            <?= $d['jam_pulang'] ? substr($d['jam_pulang'], 0, 5) : '--:--' ?>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider 
                            <?= ($d['status_kehadiran']=='Hadir') ? 'text-emerald-600' : 'text-slate-500' ?>">
                            <?= $d['status_kehadiran'] ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>