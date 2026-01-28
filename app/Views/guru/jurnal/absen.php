<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-3xl">
    
    <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-lg mb-6">
        <h2 class="text-xl font-black uppercase tracking-tight">Presensi Kelas</h2>
        <div class="mt-2 text-blue-100 text-sm space-y-1">
            <p><i class="fas fa-users mr-2"></i> <?= esc($jurnal['nama_kelas']) ?></p>
            <p><i class="fas fa-book mr-2"></i> <?= esc($jurnal['nama_mapel']) ?></p>
            <p><i class="fas fa-calendar mr-2"></i> <?= date('d M Y', strtotime($jurnal['tanggal'])) ?></p>
        </div>
    </div>

    <form action="<?= base_url('guru/jurnal/simpan_absen') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id_jurnal" value="<?= $jurnal['id'] ?>">

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach($siswa as $s): 
                    // Default H jika belum ada data
                    $val = $existing[$s['id']] ?? 'H';
                ?>
                <div class="p-4 flex flex-col sm:flex-row items-center justify-between gap-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-500">
                            <?= substr($s['nama_lengkap'], 0, 1) ?>
                        </div>
                        <span class="font-bold text-slate-700 dark:text-slate-200 text-sm"><?= esc($s['nama_lengkap']) ?></span>
                    </div>

                    <div class="flex bg-slate-100 dark:bg-slate-900 rounded-lg p-1">
                        <label class="cursor-pointer">
                            <input type="radio" name="status[<?= $s['id'] ?>]" value="H" class="peer sr-only" <?= $val=='H'?'checked':'' ?>>
                            <div class="px-4 py-1.5 rounded-md text-xs font-bold transition-all peer-checked:bg-emerald-500 peer-checked:text-white text-slate-500 hover:bg-white dark:hover:bg-slate-700">H</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status[<?= $s['id'] ?>]" value="S" class="peer sr-only" <?= $val=='S'?'checked':'' ?>>
                            <div class="px-4 py-1.5 rounded-md text-xs font-bold transition-all peer-checked:bg-blue-500 peer-checked:text-white text-slate-500 hover:bg-white dark:hover:bg-slate-700">S</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status[<?= $s['id'] ?>]" value="I" class="peer sr-only" <?= $val=='I'?'checked':'' ?>>
                            <div class="px-4 py-1.5 rounded-md text-xs font-bold transition-all peer-checked:bg-yellow-500 peer-checked:text-white text-slate-500 hover:bg-white dark:hover:bg-slate-700">I</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status[<?= $s['id'] ?>]" value="A" class="peer sr-only" <?= $val=='A'?'checked':'' ?>>
                            <div class="px-4 py-1.5 rounded-md text-xs font-bold transition-all peer-checked:bg-rose-500 peer-checked:text-white text-slate-500 hover:bg-white dark:hover:bg-slate-700">A</div>
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-transform active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Absensi
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>