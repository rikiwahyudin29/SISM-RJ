<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Jurnal KBM</h1>
            <p class="text-sm text-slate-500">Riwayat aktivitas mengajar Anda.</p>
        </div>
        
        <div class="flex gap-2">
            <form action="" method="get">
                <input type="month" name="bulan" value="<?= $bulan ?>" class="px-4 py-2 border rounded-xl font-bold text-sm bg-white outline-none focus:border-blue-500" onchange="this.form.submit()">
            </form>
            <a href="<?= base_url('guru/jurnal/input') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-blue-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Isi Jurnal
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-6 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-sm flex items-center gap-2">
            ✅ <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php if(empty($data)): ?>
            <div class="col-span-2 p-10 text-center bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-400 italic">Belum ada jurnal bulan ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($data as $d): ?>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex gap-4 group hover:shadow-md transition-all">
                <div class="w-24 h-24 shrink-0 bg-slate-100 rounded-xl overflow-hidden relative cursor-pointer" onclick="window.open('<?= base_url('uploads/jurnal/'.$d['foto_kegiatan']) ?>')">
                    <?php if($d['foto_kegiatan']): ?>
                        <img src="<?= base_url('uploads/jurnal/'.$d['foto_kegiatan']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">No Img</div>
                    <?php endif; ?>
                    <div class="absolute top-0 left-0 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded-br-lg">
                        Jam <?= $d['jam_ke'] ?>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <h4 class="font-bold text-slate-800 dark:text-white truncate"><?= $d['nama_kelas'] ?></h4>
                        <span class="text-[10px] font-mono text-slate-400"><?= date('d M', strtotime($d['tanggal'])) ?></span>
                    </div>
                    <p class="text-xs font-bold text-blue-600 mb-1"><?= $d['nama_mapel'] ?></p>
                    <p class="text-xs text-slate-500 line-clamp-2 italic mb-3">"<?= esc($d['materi']) ?>"</p>
                    
                    <div class="flex justify-end gap-2 border-t border-slate-50 pt-2">
                        <a href="<?= base_url('guru/jurnal/absen/'.$d['id']) ?>" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-bold flex items-center gap-1 transition-colors">
                            <i class="fas fa-user-check"></i> Absensi
                        </a>
                        
                        <a href="<?= base_url('guru/jurnal/hapus/'.$d['id']) ?>" onclick="return confirm('Hapus jurnal ini?')" class="px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-500 rounded-lg text-xs font-bold transition-colors">
                            Hapus
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>