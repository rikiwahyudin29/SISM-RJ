<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-6">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Manajemen Ujian</h1>
            <p class="text-sm text-slate-500">Kelola jadwal, monitoring, dan hasil ujian.</p>
        </div>
        <a href="<?= base_url('guru/ujian/tambah') ?>" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg hover:bg-blue-700 transition-transform active:scale-95 flex items-center gap-2">
            <i class="fas fa-plus-circle"></i> Buat Jadwal Baru
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-6 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (empty($jadwal)) : ?>
        <div class="flex flex-col items-center justify-center p-12 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 text-center">
            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4 text-slate-400">
                <i class="fas fa-clipboard-list text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 dark:text-white">Belum Ada Jadwal</h3>
            <p class="text-slate-500 text-sm mt-1">Anda belum membuat jadwal ujian apapun.</p>
        </div>
    <?php else : ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($jadwal as $j) : ?>
                
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition-shadow group">
                    
                    <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-start">
                        <div>
                            <span class="inline-block bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded border border-blue-100 uppercase mb-2">
                                <?= esc($j['nama_mapel']) ?>
                            </span>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white leading-tight group-hover:text-blue-600 transition-colors">
                                <?= esc($j['nama_ujian']) ?>
                            </h3>
                        </div>
                        <div class="text-right">
                            <span class="block text-2xl font-black text-slate-700 dark:text-slate-200"><?= $j['total_kelas'] ?></span>
                            <span class="text-[10px] text-slate-400 uppercase font-bold">Kelas</span>
                        </div>
                    </div>

                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 flex items-center gap-2"><i class="far fa-clock w-4 text-center"></i> Durasi</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300"><?= $j['durasi'] ?> Menit</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 flex items-center gap-2"><i class="far fa-calendar w-4 text-center"></i> Mulai</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300"><?= date('d M H:i', strtotime($j['waktu_mulai'])) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 flex items-center gap-2"><i class="fas fa-key w-4 text-center"></i> Token</span>
                            <span class="font-mono font-bold bg-slate-100 px-2 py-0.5 rounded text-slate-600"><?= !empty($j['token']) ? $j['token'] : '-' ?></span>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 dark:bg-slate-700/30 border-t border-slate-100 dark:border-slate-700 flex gap-2">
                        
                        <a href="<?= base_url('guru/ujian/monitoring/' . $j['id']) ?>" class="flex-1 py-2 bg-blue-600 text-white rounded-lg font-bold text-center text-xs hover:bg-blue-700 flex items-center justify-center gap-2 shadow-sm transition-colors">
                            <i class="fas fa-desktop"></i> Live
                        </a>

                        <a href="<?= base_url('guru/hasil/index/' . $j['id']) ?>" class="flex-1 py-2 bg-emerald-500 text-white rounded-lg font-bold text-center text-xs hover:bg-emerald-600 flex items-center justify-center gap-2 shadow-sm transition-colors">
                            <i class="fas fa-poll"></i> Hasil
                        </a>

                        <a href="<?= base_url('guru/ujian/hapus/' . $j['id']) ?>" onclick="return confirm('Hapus jadwal ini? Data nilai siswa juga akan terhapus!')" class="w-10 py-2 bg-white border border-rose-200 text-rose-500 rounded-lg font-bold text-center text-xs hover:bg-rose-50 flex items-center justify-center transition-colors" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </a>

                    </div>
                </div>

            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>