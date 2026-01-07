<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="p-8 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2.5rem] shadow-xl h-fit">
        <div class="flex items-center gap-4 mb-8">
            <div class="p-3 bg-indigo-600 shadow-lg shadow-indigo-500/30 rounded-2xl text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-xl font-[800] text-gray-900 dark:text-white uppercase tracking-tighter">Input Kelas</h3>
        </div>

        <form action="<?= base_url('admin/master/kelas_simpan') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Nama Kelas</label>
                <input type="text" name="nama_kelas" placeholder="X TJKT 1" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2 tracking-widest leading-none">Wali Kelas</label>
                <select name="guru_id" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none shadow-inner">
                    <option value="">-- Pilih Wali Kelas --</option>
                    <?php foreach($guru as $g): ?>
                        <option value="<?= $g['id'] ?>"><?= $g['nama_lengkap'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-[11px] uppercase shadow-lg shadow-indigo-500/20 active:scale-95 transition-all tracking-widest">Simpan Kelas</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2.5rem] shadow-xl overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50 dark:bg-slate-800/50 text-[11px] font-[800] uppercase text-slate-500 tracking-widest border-b dark:border-slate-700/50">
                <tr>
                    <th class="px-8 py-6">Nama Kelas</th>
                    <th class="px-8 py-6">Wali Kelas</th>
                    <th class="px-8 py-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-slate-700/30 text-gray-900 dark:text-white">
                <?php foreach($kelas as $k): ?>
                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all">
                    <td class="px-8 py-6 font-bold text-xs uppercase italic"><?= $k['nama_kelas'] ?></td>
                    <td class="px-8 py-6 font-bold text-xs uppercase"><?= $k['nama_guru'] ?? '<span class="text-red-400">Belum Set</span>' ?></td>
                    <td class="px-8 py-6 text-center flex items-center justify-center gap-3">
                        
                        <button data-modal-target="edit-kelas-<?= $k['id'] ?>" data-modal-toggle="edit-kelas-<?= $k['id'] ?>" class="p-3 text-amber-500 bg-amber-500/10 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>

                        <button data-modal-target="del-kelas-<?= $k['id'] ?>" data-modal-toggle="del-kelas-<?= $k['id'] ?>" class="p-3 text-red-500 bg-red-500/10 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>

                <div id="edit-kelas-<?= $k['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[70] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4 animate-fade-in">
                    <div class="relative w-full max-w-md bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
                        <div class="p-8 border-b dark:border-slate-700/50 bg-amber-500 text-white flex justify-between items-center">
                            <h3 class="font-bold uppercase text-xs tracking-[0.2em]">Update Data Kelas</h3>
                            <button data-modal-hide="edit-kelas-<?= $k['id'] ?>" class="text-white/50 hover:text-white transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        <form action="<?= base_url('admin/master/kelas_update/' . $k['id']) ?>" method="POST" class="p-10 space-y-6 text-left">
                            <?= csrf_field() ?>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest leading-none">Nama Kelas</label>
                                <input type="text" name="nama_kelas" value="<?= $k['nama_kelas'] ?>" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none shadow-inner" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest leading-none">Wali Kelas</label>
                                <select name="guru_id" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none shadow-inner">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    <?php foreach($guru as $g): ?>
                                        <option value="<?= $g['id'] ?>" <?= ($g['id'] == $k['guru_id']) ? 'selected' : '' ?>><?= $g['nama_lengkap'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="w-full py-5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl text-[11px] uppercase shadow-xl shadow-amber-500/20 active:scale-95 transition-all tracking-[0.3em]">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>

                <div id="del-kelas-<?= $k['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[70] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4">
                    <div class="relative w-full max-w-md bg-white dark:bg-[#1e293b] rounded-[2.5rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
                        <div class="p-8 text-center">
                            <div class="w-20 h-20 bg-red-100 dark:bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase mb-2 tracking-tighter">Hapus Kelas?</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mb-8 uppercase tracking-widest">Data <span class="text-red-500 font-bold"><?= $k['nama_kelas'] ?></span> akan hilang permanen, Bos!</p>
                            <div class="flex gap-3">
                                <button data-modal-hide="del-kelas-<?= $k['id'] ?>" class="flex-1 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Batal</button>
                                <a href="<?= base_url('admin/master/kelas_hapus/' . $k['id']) ?>" class="flex-1 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl text-[10px] uppercase shadow-lg shadow-red-500/20 tracking-widest text-center">Ya, Hapus!</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
<div id="toast-success" class="fixed bottom-10 right-10 flex items-center w-full max-w-xs p-5 space-x-4 text-gray-500 bg-white rounded-3xl shadow-2xl dark:text-gray-400 dark:bg-slate-800 border-l-4 border-emerald-500 animate-slide-up" role="alert">
    <div class="text-emerald-500 bg-emerald-100 dark:bg-emerald-500/10 p-2 rounded-xl">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
    </div>
    <div class="text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white">
        <?= session()->getFlashdata('success') ?>
    </div>
</div>
<script>setTimeout(() => { document.getElementById('toast-success')?.remove(); }, 3500);</script>
<?php endif; ?>

<style>
    @keyframes slide-up { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .animate-slide-up { animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
</style>

<?= $this->endSection() ?>