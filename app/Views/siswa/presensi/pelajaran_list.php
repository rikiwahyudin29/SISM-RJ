<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Absensi Pelajaran</h1>
            <p class="text-sm text-slate-500">Rekap kehadiran per mata pelajaran.</p>
        </div>

        <form action="" method="get" class="w-full md:w-auto">
            <div class="relative">
                <select name="id_ta" onchange="this.form.submit()" class="w-full md:w-64 pl-4 pr-10 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer">
                    <?php foreach($ta_list as $ta): ?>
                        <option value="<?= $ta['id'] ?>" <?= $ta['id'] == $selected_ta ? 'selected' : '' ?>>
                            <?= $ta['tahun_ajaran'] ?> - <?= ucfirst($ta['semester']) ?> 
                            <?= ($ta['status'] == 'Aktif') ? '(Aktif)' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </form>
    </div>

    <?php if(empty($mapel)): ?>
        <div class="p-10 text-center bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <p class="text-slate-500 font-medium">Tidak ada jadwal pelajaran di semester ini.</p>
            <p class="text-xs text-slate-400 mt-1">Coba pilih semester lain.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach($mapel as $m): ?>
            <a href="<?= base_url('siswa/presensi/pelajaran/'.$m['id_mapel']) ?>?id_ta=<?= $selected_ta ?>" class="group block bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md hover:border-blue-200 transition-all">
                
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white group-hover:text-blue-600 transition-colors">
                            <?= $m['nama_mapel'] ?>
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">👨‍🏫 <?= $m['nama_guru'] ?></p>
                    </div>
                    <div class="text-center">
                        <div class="text-xs font-bold text-slate-400 mb-1">Kehadiran</div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-xs border-4 
                            <?= $m['persentase'] >= 80 ? 'border-emerald-500 text-emerald-600' : ($m['persentase'] >= 50 ? 'border-yellow-500 text-yellow-600' : 'border-rose-500 text-rose-600') ?>">
                            <?= $m['persentase'] ?>%
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2 text-center bg-slate-50 dark:bg-slate-700/30 rounded-xl p-2">
                    <div>
                        <span class="block text-[10px] font-bold text-emerald-600 uppercase">Hadir</span>
                        <span class="font-black text-slate-700 dark:text-white"><?= $m['stats']['hadir'] ?></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-blue-600 uppercase">Sakit</span>
                        <span class="font-black text-slate-700 dark:text-white"><?= $m['stats']['sakit'] ?></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-yellow-600 uppercase">Izin</span>
                        <span class="font-black text-slate-700 dark:text-white"><?= $m['stats']['izin'] ?></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-rose-600 uppercase">Alpha</span>
                        <span class="font-black text-slate-700 dark:text-white"><?= $m['stats']['alpha'] ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>