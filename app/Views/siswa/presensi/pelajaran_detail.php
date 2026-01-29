<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('siswa/presensi/pelajaran') ?>?id_ta=<?= $ta['id'] ?? '' ?>" class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white"><?= $mapel['nama_mapel'] ?></h1>
            <p class="text-xs text-slate-500">
                Riwayat Pertemuan • 
                <span class="font-bold text-blue-600">
                    <?= $ta['tahun_ajaran'] ?? '-' ?> (<?= ucfirst($ta['semester'] ?? '-') ?>)
                </span>
            </p>
        </div>
    </div>

    <div class="space-y-4">
        <?php if(empty($riwayat)): ?>
            <div class="p-10 text-center bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-400 italic">Belum ada riwayat pertemuan untuk mapel ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($riwayat as $d): ?>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex gap-4 items-start relative overflow-hidden">
                
                <?php 
                    $color = 'bg-slate-400';
                    $text  = 'Hadir';
                    if($d['status'] == 'H') { $color = 'bg-emerald-500'; $text = 'HADIR'; }
                    if($d['status'] == 'S') { $color = 'bg-blue-500'; $text = 'SAKIT'; }
                    if($d['status'] == 'I') { $color = 'bg-yellow-500'; $text = 'IZIN'; }
                    if($d['status'] == 'A') { $color = 'bg-rose-500'; $text = 'ALPHA'; }
                ?>
                <div class="absolute left-0 top-0 bottom-0 w-1 <?= $color ?>"></div>

                <div class="w-16 h-16 shrink-0 bg-slate-100 rounded-lg overflow-hidden relative cursor-pointer" onclick="window.open('<?= base_url('uploads/jurnal/'.$d['foto_kegiatan']) ?>')">
                    <?php if($d['foto_kegiatan']): ?>
                        <img src="<?= base_url('uploads/jurnal/'.$d['foto_kegiatan']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-slate-300 text-[10px]">No Img</div>
                    <?php endif; ?>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-xs font-bold text-slate-400 mb-0.5">
                                <?= date('d M Y', strtotime($d['tanggal'])) ?> • Jam <?= $d['jam_ke'] ?>
                            </div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm line-clamp-1">
                                <?= $d['nama_guru'] ?>
                            </h4>
                        </div>
                        <span class="px-2 py-1 rounded text-[10px] font-black text-white <?= $color ?>">
                            <?= $text ?>
                        </span>
                    </div>

                    <div class="mt-2 p-2 bg-slate-50 dark:bg-slate-700/30 rounded-lg border border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-600 dark:text-slate-300 italic line-clamp-2">
                            "<?= esc($d['materi']) ?>"
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>