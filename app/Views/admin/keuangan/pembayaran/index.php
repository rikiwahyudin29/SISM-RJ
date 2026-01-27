<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="max-w-2xl mx-auto text-center mb-10">
        <h1 class="text-3xl font-black text-slate-800 dark:text-white mb-2">Kasir Sekolah</h1>
        <p class="text-slate-500">Cari nama atau NIS siswa untuk melakukan pembayaran.</p>
    </div>

    <div class="max-w-xl mx-auto mb-10">
        <form action="" method="get" class="relative">
            <input type="text" name="q" value="<?= esc($keyword) ?>" 
                   class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-lg font-bold shadow-lg shadow-blue-500/10 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                   placeholder="Ketik Nama atau NIS..." autocomplete="off" autofocus>
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition-all">Cari</button>
        </form>
    </div>

    <?php if ($keyword): ?>
        <div class="max-w-4xl mx-auto">
            <h3 class="font-bold text-slate-500 uppercase text-xs mb-4 tracking-wider">Hasil Pencarian</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php if(empty($siswa)): ?>
                    <div class="col-span-2 text-center py-10 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300">
                        <p class="text-slate-400 font-medium">Siswa tidak ditemukan.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($siswa as $s): ?>
                        <a href="<?= base_url('admin/keuangan/pembayaran/siswa/' . $s['id']) ?>" 
                           class="flex items-center gap-4 p-4 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-md transition-all group">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg group-hover:scale-110 transition-transform">
                                <?= substr($s['nama_lengkap'], 0, 1) ?>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-blue-600 transition-colors"><?= esc($s['nama_lengkap']) ?></h4>
                                <p class="text-xs text-slate-500">NIS: <?= esc($s['nis']) ?> • Kelas <?= esc($s['nama_kelas']) ?></p>
                            </div>
                            <div class="ml-auto text-slate-300 group-hover:text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>