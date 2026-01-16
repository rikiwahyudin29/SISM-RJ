<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<div class="space-y-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-5">
            <a href="<?= base_url('admin/master/kelas') ?>" 
               class="group relative flex items-center justify-center w-14 h-14 bg-white dark:bg-[#1e293b] border border-slate-200 dark:border-slate-700 rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-none hover:scale-105 active:scale-95 transition-all duration-300">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                <div class="absolute inset-0 rounded-2xl ring-2 ring-blue-500/20 group-hover:ring-blue-500/50 scale-90 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300"></div>
            </a>
            
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight uppercase leading-none">
                    <?= $title ?>
                </h2>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest leading-none">
                        Anggota Rombongan Belajar
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="px-5 py-2.5 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800">
                <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest block mb-0.5">Total Siswa</span>
                <span class="text-xl font-black text-blue-600 dark:text-blue-400 leading-none"><?= count($siswa) ?></span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2rem] shadow-2xl shadow-slate-200/40 dark:shadow-none overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[10px] font-extrabold uppercase text-slate-500 tracking-[0.2em] border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-8 py-6 text-center w-16">No</th>
                        <th class="px-8 py-6 text-center">NISN</th>
                        <th class="px-8 py-6">Nama Lengkap</th>
                        <th class="px-8 py-6 text-center">Jenis Kelamin</th>
                        <th class="px-8 py-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <?php if (empty($siswa)) : ?>
                        <tr>
                            <td colspan="5" class="px-10 py-24 text-center">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p class="text-sm font-extrabold text-slate-500 uppercase tracking-widest">Belum Ada Siswa</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1">Silakan tambahkan siswa ke kelas ini</p>
                                </div>
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php $no = 1; foreach($siswa as $s): ?>
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors group">
                            <td class="px-8 py-6 text-center">
                                <span class="text-xs font-bold text-slate-400 group-hover:text-blue-500 transition-colors"><?= $no++ ?></span>
                            </td>
                            
                            <td class="px-8 py-6 text-center">
                                <span class="inline-block bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-3 py-1.5 rounded-lg font-bold text-[11px] border border-slate-200 dark:border-slate-600 tracking-wide font-mono">
                                    <?= $s['nisn'] ?>
                                </span>
                            </td>
                            
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-black uppercase">
                                        <?= substr($s['nama_lengkap'], 0, 2) ?>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-700 dark:text-white uppercase tracking-tight leading-none group-hover:text-blue-600 transition-colors">
                                        <?= $s['nama_lengkap'] ?>
                                    </span>
                                </div>
                            </td>
                            
                            <td class="px-8 py-6 text-center">
                                <?php if($s['jenis_kelamin'] == 'L'): ?>
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                        Laki-Laki
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 border border-pink-100 dark:border-pink-800 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                        Perempuan
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="px-8 py-6 text-center">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Aktif</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>