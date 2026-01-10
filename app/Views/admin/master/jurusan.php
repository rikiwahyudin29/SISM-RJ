<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-6" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="p-8 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2.5rem] shadow-xl shadow-blue-500/5 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-emerald-500 shadow-lg shadow-emerald-500/30 rounded-2xl text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter leading-none">Manajemen Jurusan</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-2">Daftar Kompetensi Keahlian SISM-RJ</p>
            </div>
        </div>
        
        <button data-modal-target="modal-tambah-jurusan" data-modal-toggle="modal-tambah-jurusan" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold rounded-2xl text-[11px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
            + Tambah Jurusan
        </button>
    </div>

    <div class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2.5rem] shadow-xl shadow-blue-500/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50 text-[11px] font-extrabold uppercase text-slate-500 tracking-widest border-b dark:border-slate-700/50">
                    <tr>
                        <th class="px-8 py-6">Kode Jurusan</th>
                        <th class="px-8 py-6">Nama Lengkap Kompetensi</th>
                        <th class="px-8 py-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-700/30">
                    <?php foreach($jurusan as $j): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all group text-gray-900 dark:text-white">
                        <td class="px-8 py-6">
                            <span class="inline-block bg-blue-600 text-white px-4 py-2 rounded-xl font-extrabold text-[11px] uppercase">
                                <?= $j['kode_jurusan'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 font-extrabold text-xs uppercase tracking-tight leading-relaxed">
                            <?= $j['nama_jurusan'] ?>
                        </td>
                        <td class="px-8 py-6 text-center flex items-center justify-center gap-3">
                            <button data-modal-target="edit-jurusan-<?= $j['id'] ?>" data-modal-toggle="edit-jurusan-<?= $j['id'] ?>" class="p-3 text-amber-500 bg-amber-500/10 rounded-xl hover:bg-amber-500 hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button data-modal-target="delete-jurusan-<?= $j['id'] ?>" data-modal-toggle="delete-jurusan-<?= $j['id'] ?>" class="p-3 text-red-500 bg-red-500/10 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>

                   <div id="edit-jurusan-<?= $j['id'] ?>" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[70] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4 overflow-y-auto">
    <div class="relative w-full max-w-xl bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        
        <div class="p-10 bg-amber-500 text-white flex justify-between items-center relative overflow-hidden">
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white/10" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
            
            <div class="relative z-10">
                <h3 class="text-xl font-extrabold uppercase tracking-tighter leading-none">Update Kompetensi</h3>
                <p class="text-[10px] font-bold opacity-80 uppercase tracking-[0.2em] mt-2">Ubah Data Identitas: <?= $j['kode_jurusan'] ?></p>
            </div>
            
            <button data-modal-hide="edit-jurusan-<?= $j['id'] ?>" class="relative z-10 text-white/60 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="<?= base_url('admin/master/jurusan_update/' . $j['id']) ?>" method="POST" class="p-1 space-y-8 text-left">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 gap-8">
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] ml-1">Kode Jurusan</label>
                    <div class="relative">
                        <input type="text" name="kode_jurusan" value="<?= $j['kode_jurusan'] ?>" 
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl p-5 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all outline-none shadow-sm" required>
                    </div>
                </div>

                <div class="space y-1">
                    <label class="block text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] ml-1">Nama Lengkap Jurusan</label>
                    <div class="relative">
                        <textarea name="nama_jurusan" rows="2" 
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl p-5 text-sm font-bold text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all outline-none shadow-sm resize-none" required><?= $j['nama_jurusan'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-6 bg-amber-500 hover:bg-amber-600 text-white font-extrabold rounded-[2rem] text-[11px] uppercase shadow-2xl shadow-amber-500/30 active:scale-[0.98] transition-all tracking-[0.3em]">
                    Simpan Perubahan Data
                </button>
            </div>
        </form>
    </div>
</div>

                    <div id="delete-jurusan-<?= $j['id'] ?>" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[70] flex justify-center items-center bg-slate-900/60 backdrop-blur-sm p-4">
                        <div class="relative w-full max-w-md bg-white dark:bg-[#1e293b] rounded-[2.5rem] shadow-2xl border dark:border-slate-700 overflow-hidden p-8 text-center">
                            <div class="w-20 h-20 bg-red-100 dark:bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white uppercase mb-2 tracking-tighter">Hapus Jurusan?</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold mb-8 uppercase tracking-widest leading-relaxed px-4">Data <span class="text-red-500"><?= $j['nama_jurusan'] ?></span> akan hilang selamanya, Bos!</p>
                            <div class="flex gap-3">
                                <button data-modal-hide="delete-jurusan-<?= $j['id'] ?>" class="flex-1 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Batal</button>
                                <a href="<?= base_url('admin/master/jurusan_hapus/' . $j['id']) ?>" class="flex-1 py-4 bg-red-600 hover:bg-red-700 text-white font-extrabold rounded-2xl text-[10px] uppercase shadow-lg shadow-red-500/20 tracking-widest text-center">Ya, Hapus!</a>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-tambah-jurusan" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
        <div class="p-8 bg-emerald-600 text-white flex justify-between items-center">
            <div>
                <h3 class="font-extrabold uppercase text-xs tracking-[0.2em]">Tambah Kompetensi Baru</h3>
                <p class="text-[9px] font-bold opacity-70 uppercase tracking-widest mt-1">Master Data Kurikulum</p>
            </div>
            <button data-modal-hide="modal-tambah-jurusan" class="text-white/50 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= base_url('admin/master/jurusan_simpan') ?>" method="POST" class="p-1 space-y-6">
            <?= csrf_field() ?>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest leading-none ml-1">Kode Jurusan</label>
                <input type="text" name="kode_jurusan" placeholder="MISAL: TJKT" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest leading-none ml-1">Nama Lengkap Jurusan</label>
                <input type="text" name="nama_jurusan" placeholder="TEKNIK JARINGAN KOMPUTER..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none" required>
            </div>
            <button type="submit" class="w-full py-5 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold rounded-2xl text-[11px] uppercase shadow-xl shadow-emerald-500/20 active:scale-95 transition-all tracking-[0.3em]">
                Simpan Entitas Jurusan
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>