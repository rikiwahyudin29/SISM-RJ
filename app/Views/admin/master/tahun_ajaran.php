<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase italic tracking-tight">Tahun Ajaran & Semester</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kelola periode aktif sistem akademik.</p>
            </div>
            <button type="button" data-modal-target="modal-tambah-ta" data-modal-toggle="modal-tambah-ta" class="text-white bg-blue-700 hover:bg-blue-800 font-black rounded-xl text-xs px-5 py-3 shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex items-center gap-2 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Periode
            </button>
        </div>
    </div>

    <div class="relative overflow-x-auto bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-[10px] text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4">Tahun Ajaran</th>
                    <th class="px-6 py-4">Semester</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ta as $t) : ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white"><?= $t['tahun_ajaran'] ?></td>
                    <td class="px-6 py-4 font-medium uppercase tracking-tighter"><?= $t['semester'] ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($t['status'] == 'Aktif') : ?>
                            <span class="bg-green-100 text-green-700 text-[9px] font-black px-3 py-1 rounded-full dark:bg-green-900/30 dark:text-green-400 border border-green-200 uppercase italic">Aktif</span>
                        <?php else : ?>
                            <span class="bg-gray-100 text-gray-500 text-[9px] font-black px-3 py-1 rounded-full dark:bg-gray-700 dark:text-gray-400 border border-gray-200 uppercase">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center items-center gap-3">
                            <?php if ($t['status'] == 'Nonaktif') : ?>
                                <a href="<?= base_url('admin/master/ta_aktif/' . $t['id']) ?>" 
                                   class="flex items-center justify-center w-9 h-9 text-gray-400 hover:text-blue-500 bg-gray-100/5 hover:bg-blue-500/10 rounded-xl transition-all group" 
                                   title="Aktifkan Periode">
                                    <svg class="w-5 h-5 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                            <?php else : ?>
                                <div class="flex items-center justify-center w-9 h-9 text-white bg-blue-600 rounded-xl shadow-lg shadow-blue-500/40" title="Periode Aktif Utama">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <button type="button" 
                               data-modal-target="popup-modal-delete-<?= $t['id'] ?>" 
                               data-modal-toggle="popup-modal-delete-<?= $t['id'] ?>"
                               class="flex items-center justify-center w-9 h-9 text-red-500 hover:text-white bg-red-500/10 hover:bg-red-500 rounded-xl transition-all group" 
                               title="Hapus Data">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                <div id="popup-modal-delete-<?= $t['id'] ?>" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <div class="relative bg-white rounded-3xl shadow dark:bg-gray-800 border dark:border-gray-700">
                            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal-delete-<?= $t['id'] ?>">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                            </button>
                            <div class="p-4 md:p-5 text-center">
                                <svg class="mx-auto mb-4 text-red-500 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                <h3 class="mb-5 text-lg font-black text-gray-900 dark:text-white uppercase italic">Yakin hapus periode ini, Bos?</h3>
                                <div class="flex justify-center gap-3">
                                    <a href="<?= base_url('admin/master/ta_hapus/' . $t['id']) ?>" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-black rounded-xl text-xs inline-flex items-center px-5 py-2.5 text-center uppercase tracking-widest">Ya, Hapus!</a>
                                    <button data-modal-hide="popup-modal-delete-<?= $t['id'] ?>" type="button" class="py-2.5 px-5 ms-3 text-xs font-black text-gray-900 focus:outline-none bg-white rounded-xl border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 uppercase tracking-widest">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modal-tambah-ta" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl dark:bg-gray-800 border dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b dark:border-gray-700 bg-blue-600 text-white font-black uppercase text-xs tracking-widest italic flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                Tambah Periode Akademik
            </div>
            <form action="<?= base_url('admin/master/ta_simpan') ?>" method="POST" class="p-6 space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" placeholder="Contoh: 2025/2026" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl p-3 text-sm font-bold text-gray-900 dark:text-white focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Semester</label>
                    <select name="semester" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl p-3 text-sm font-bold text-gray-900 dark:text-white">
                        <option value="Ganjil">GANJIL</option>
                        <option value="Genap">GENAP</option>
                    </select>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" data-modal-hide="modal-tambah-ta" class="flex-1 py-4 text-xs font-black text-gray-400 uppercase hover:text-gray-600 transition-colors tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-blue-600 text-white font-black rounded-2xl text-xs uppercase shadow-lg shadow-blue-500/20 active:scale-95 transition-all tracking-widest text-center">Simpan TA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success') || session()->getFlashdata('error')) : ?>
<div id="toast-notification" class="fixed flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-2xl shadow bottom-5 right-5 dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800 border dark:border-gray-700" role="alert">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
        </div>
        <div class="ms-3 text-xs font-black uppercase italic tracking-tight"><?= session()->getFlashdata('success') ?></div>
    <?php else : ?>
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg>
        </div>
        <div class="ms-3 text-xs font-black uppercase italic tracking-tight"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-notification" aria-label="Close">
        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
    </button>
</div>
<?php endif; ?>

<script>
    // Auto hide toast after 3 seconds
    setTimeout(() => {
        const toast = document.getElementById('toast-notification');
        if(toast) toast.remove();
    }, 3000);
</script>
<?= $this->endSection() ?>