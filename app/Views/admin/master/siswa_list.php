<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-7" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="<?= base_url('admin/master/kelas') ?>" class="p-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-500 hover:text-blue-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white italic tracking-tighter uppercase leading-none"><?= $title ?></h2>
            </div>
            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mt-4 ml-11 uppercase tracking-widest">Manajemen Anggota Rombongan Belajar Kelas <?= $nama_kelas ?></p>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2rem] shadow-2xl shadow-blue-500/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 dark:bg-slate-800/30 text-[10px] font-extrabold uppercase text-slate-500 tracking-[0.2em] border-b dark:border-slate-700/50">
                    <tr>
                        <th class="px-10 py-7">No</th>
                        <th class="px-10 py-7 text-center">NISN</th>
                        <th class="px-10 py-7">Nama Siswa</th>
                        <th class="px-10 py-7 text-center">L/P</th>
                        <th class="px-10 py-7 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-700/30">
                    <?php if (empty($siswa)) : ?>
                    <tr>
                        <td colspan="5" class="px-10 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <p class="text-sm font-black uppercase tracking-[0.3em]">Belum Ada Siswa di Kelas Ini</p>
                            </div>
                        </td>
                    </tr>
                    <?php else : ?>
                        <?php $no = 1; foreach($siswa as $s): ?>
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all group">
                            <td class="px-10 py-7 text-xs font-bold text-slate-400"><?= $no++ ?></td>
                            <td class="px-10 py-7 text-center font-black text-xs text-blue-600 dark:text-blue-400 italic leading-none uppercase"><?= $s['nisn'] ?></td>
                            <td class="px-10 py-7 text-sm font-extrabold text-gray-900 dark:text-white uppercase leading-none"><?= $s['nama'] ?></td>
                            <td class="px-10 py-7 text-center leading-none">
                                <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-[10px] font-black"><?= $s['jk'] ?></span>
                            </td>
                            <td class="px-10 py-7 text-center leading-none">
                                <span class="text-[10px] font-black text-emerald-500 uppercase">Aktif</span>
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