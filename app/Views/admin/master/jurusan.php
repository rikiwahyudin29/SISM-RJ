<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="p-8 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2.5rem] shadow-xl shadow-blue-500/5 h-fit relative overflow-hidden group">
        <div class="flex items-center gap-4 mb-8 relative z-10">
            <div class="p-3 bg-emerald-500 shadow-lg shadow-emerald-500/30 rounded-2xl text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tight">Input Jurusan</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Sistem Informasi Akademik</p>
            </div>
        </div>

        <form action="<?= base_url('admin/master/jurusan_simpan') ?>" method="POST" class="space-y-6 relative z-10">
            <?= csrf_field() ?>
            <div>
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 ml-1 tracking-wider">Kode Jurusan</label>
                <input type="text" name="kode_jurusan" placeholder="CONTOH: TJKT" 
                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all outline-none" required>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 ml-1 tracking-wider">Nama Lengkap Jurusan</label>
                <input type="text" name="nama_jurusan" placeholder="NAMA JURUSAN LENGKAP..." 
                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all outline-none" required>
            </div>
            <button type="submit" class="w-full py-5 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold rounded-2xl text-[11px] uppercase shadow-lg shadow-emerald-500/20 active:scale-95 transition-all tracking-widest">
                Simpan Data
            </button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2.5rem] shadow-xl shadow-blue-500/5 overflow-hidden">
        <div class="p-8 border-b dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30 flex items-center justify-between">
            <h3 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Database Jurusan</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[11px] font-extrabold uppercase text-slate-400 tracking-wider border-b dark:border-slate-700/50">
                        <th class="px-8 py-6">Kode</th>
                        <th class="px-8 py-6">Nama Jurusan</th>
                        <th class="px-8 py-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-700/30">
                    <?php foreach($jurusan as $j): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <span class="inline-block bg-blue-600 text-white px-4 py-2 rounded-xl font-extrabold text-[11px] uppercase italic">
                                <?= $j['kode_jurusan'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-extrabold text-gray-900 dark:text-white uppercase text-xs tracking-tight leading-relaxed"><?= $j['nama_jurusan'] ?></p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <button type="button" data-modal-target="delete-<?= $j['id'] ?>" data-modal-toggle="delete-<?= $j['id'] ?>"
                                class="inline-flex items-center justify-center w-11 h-11 text-red-500 hover:text-white bg-red-500/5 hover:bg-red-500 rounded-2xl transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>