<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<div class="space-y-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="p-8 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700 rounded-[2.5rem] shadow-xl shadow-blue-500/5 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-[800] text-gray-900 dark:text-white uppercase tracking-tighter leading-none">Manajemen Ruangan</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-2">Daftar Inventaris Ruang Kelas & Lab</p>
        </div>
        <button data-modal-target="modal-tambah-ruangan" data-modal-toggle="modal-tambah-ruangan" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-lg shadow-indigo-500/30 active:scale-95 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Ruangan
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <?php foreach($ruangan as $r): ?>
        <div class="group p-8 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700 rounded-[2.5rem] shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-500 relative overflow-hidden text-center">
            <div class="w-14 h-14 bg-indigo-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 text-indigo-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h4 class="font-extrabold text-gray-900 dark:text-white uppercase text-xs tracking-widest leading-tight"><?= $r['nama_ruangan'] ?></h4>
            <button data-modal-target="popup-delete-<?= $r['id'] ?>" data-modal-toggle="popup-delete-<?= $r['id'] ?>" class="mt-4 text-[9px] font-bold text-red-400 hover:text-red-600 uppercase tracking-widest transition-colors underline underline-offset-4">Hapus</button>
        </div>

        <div id="popup-delete-<?= $r['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[70] justify-center items-center w-full h-full bg-slate-900/60 backdrop-blur-sm">
            <div class="relative p-4 w-full max-w-md">
                <div class="relative bg-white rounded-[2.5rem] shadow dark:bg-[#1e293b] border dark:border-slate-700">
                    <div class="p-8 text-center">
                        <svg class="mx-auto mb-4 text-red-500 w-12 h-12 shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <h3 class="mb-5 text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tighter">Hapus Ruangan <?= $r['nama_ruangan'] ?>?</h3>
                        <div class="flex justify-center gap-3">
                            <a href="<?= base_url('admin/master/ruangan_hapus/' . $r['id']) ?>" class="text-white bg-red-600 hover:bg-red-800 font-bold rounded-xl text-[10px] px-6 py-3 uppercase tracking-widest transition-all shadow-lg shadow-red-500/30">Ya, Hapus!</a>
                            <button data-modal-hide="popup-delete-<?= $r['id'] ?>" type="button" class="text-slate-500 bg-white border border-slate-200 hover:bg-slate-100 font-bold rounded-xl text-[10px] px-6 py-3 uppercase tracking-widest dark:bg-slate-800 dark:text-slate-400 dark:border-slate-600 dark:hover:bg-slate-700 transition-all">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="modal-tambah-ruangan" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-slate-900/80 backdrop-blur-md p-4">
    <div class="relative w-full max-w-md bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
        <div class="p-8 border-b dark:border-slate-700/50 bg-indigo-600 text-white flex justify-between items-center">
            <h3 class="font-bold uppercase text-xs tracking-[0.2em]">Input Ruangan Baru</h3>
            <button data-modal-hide="modal-tambah-ruangan" class="text-white/50 hover:text-white transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <form action="<?= base_url('admin/master/ruangan_simpan') ?>" method="POST" class="p-10 space-y-6">
            <?= csrf_field() ?>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Nama Ruangan</label>
                <input type="text" name="nama_ruangan" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none" required>
            </div>
            <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-[11px] uppercase shadow-xl shadow-indigo-500/20 active:scale-95 transition-all tracking-[0.3em]">Simpan Ruangan</button>
        </form>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
<div id="toast-ruangan" class="fixed bottom-10 right-10 flex items-center w-full max-w-xs p-5 space-x-4 text-gray-500 bg-white rounded-3xl shadow-2xl dark:text-gray-400 dark:bg-[#1e293b] border dark:border-slate-700 animate-slide-in-right" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-emerald-500 bg-emerald-100 rounded-2xl dark:bg-emerald-500/10 dark:text-emerald-400">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
    </div>
    <div class="text-[11px] font-bold uppercase tracking-widest text-gray-900 dark:text-white"><?= session()->getFlashdata('success') ?></div>
    <button type="button" class="ms-auto bg-transparent text-gray-400 hover:text-gray-900 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white" data-dismiss-target="#toast-ruangan" aria-label="Close">
        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
    </button>
</div>
<script>setTimeout(() => { document.getElementById('toast-ruangan')?.remove(); }, 3500);</script>
<?php endif; ?>

<style>
    @keyframes slide-in-right { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    .animate-slide-in-right { animation: slide-in-right 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
</style>
<?= $this->endSection() ?>