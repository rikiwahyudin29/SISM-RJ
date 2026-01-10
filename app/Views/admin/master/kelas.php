<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-7" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white italic tracking-tighter uppercase leading-none">Manajemen Kelas</h2>
            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mt-2 leading-none">Atur entitas rombongan belajar dan wali kelas aktif.</p>
        </div>
        
        <button data-modal-target="modal-tambah-kelas" data-modal-toggle="modal-tambah-kelas" 
            class="px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-extrabold rounded-2xl text-[11px] uppercase tracking-widest shadow-xl shadow-blue-500/30 active:scale-95 transition-all flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kelas
        </button>
        <button data-modal-target="modal-import-kelas" data-modal-toggle="modal-import-kelas" 
    class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold rounded-2xl text-[11px] uppercase tracking-widest shadow-xl shadow-emerald-500/30 active:scale-95 transition-all flex items-center gap-3">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
    Import Excel
</button>

<div id="modal-import-kelas" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[80] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
        <div class="p-10 bg-emerald-600 text-white flex justify-between items-center relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-extrabold uppercase tracking-tighter italic leading-none">Import Massal Kelas</h3>
                <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest mt-2 leading-none">Gunakan Format .XLSX atau .CSV</p>
            </div>
            <button data-modal-hide="modal-import-kelas" class="text-white/60 hover:text-white transition-colors bg-white/10 p-2 rounded-full leading-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="<?= base_url('admin/import/kelas') ?>" method="POST" enctype="multipart/form-data" class="p-10 space-y-7">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Pilih File Master Kelas</label>
                <div class="relative flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:hover:border-slate-500 transition-all">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="text-xs font-bold text-slate-500 uppercase italic">Klik untuk upload file excel</p>
                        </div>
                        <input type="file" name="file_excel" class="hidden" required accept=".xlsx, .xls, .csv" />
                    </label>
                </div>
            </div>
            <button type="submit" class="w-full py-6 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold rounded-[2rem] text-[11px] uppercase shadow-2xl shadow-emerald-500/30 tracking-[0.3em] transition-all active:scale-95 leading-none">
                Eksekusi Import Data
            </button>
        </form>
    </div>
</div>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
    <div class="p-4 mb-4 text-red-800 border-2 border-red-300 rounded-[1.5rem] bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800 animate-pulse">
        <div class="flex items-center">
            <svg class="flex-shrink-0 w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <span class="text-xs font-black uppercase tracking-widest leading-none">Peringatan: <?= session()->getFlashdata('error') ?></span>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700/50 rounded-[2rem] shadow-2xl shadow-blue-500/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 dark:bg-slate-800/30 text-[10px] font-extrabold uppercase text-slate-500 tracking-[0.2em] border-b dark:border-slate-700/50">
                    <tr>
                        <th class="px-10 py-7">Nama Kelas</th>
                        <th class="px-10 py-7 text-center">Jurusan</th>
                        <th class="px-10 py-7">Wali Kelas</th>
                        <th class="px-10 py-7 text-center">Siswa</th> 
                        <th class="px-10 py-7 text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-700/30">
                    <?php foreach($kelas as $k): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all group">
                        <td class="px-10 py-7">
                            <span class="text-sm font-extrabold text-gray-900 dark:text-white italic tracking-tight uppercase leading-none"><?= $k['nama_kelas'] ?></span>
                        </td>
                        <td class="px-10 py-7 text-center leading-none">
                            <span class="bg-indigo-600/10 text-indigo-500 px-4 py-2 rounded-xl font-black text-[10px] border border-indigo-500/20 uppercase tracking-tighter leading-none">
                                <?= $k['kode_jurusan'] ?? 'UMUM' ?>
                            </span>
                        </td>
                        <td class="px-10 py-7 leading-none">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest leading-none">
                                <?= $k['nama_guru'] ?? '<span class="text-red-500 font-black italic">BELUM SET</span>' ?>
                            </span>
                        </td>
                        <td class="px-10 py-7 text-center leading-none">
                            <div class="inline-flex items-center justify-center w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-full border border-emerald-500/20">
                                <span class="text-[11px] font-black leading-none"><?= $k['jumlah_siswa'] ?? 0 ?></span>
                            </div>
                        </td>
                        <td class="px-10 py-7">
                            <div class="flex items-center justify-center gap-3">
                                <a href="<?= base_url('admin/master/kelas_detail/' . $k['id']) ?>" 
                                    class="w-10 h-10 flex items-center justify-center bg-emerald-500/10 text-emerald-500 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-lg shadow-emerald-500/10 leading-none" title="Lihat Siswa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                <button data-modal-target="edit-kelas-<?= $k['id'] ?>" data-modal-toggle="edit-kelas-<?= $k['id'] ?>" 
                                    class="w-10 h-10 flex items-center justify-center bg-blue-600/10 text-blue-500 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-lg shadow-blue-500/10 leading-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                
                                <button data-modal-target="del-kelas-<?= $k['id'] ?>" data-modal-toggle="del-kelas-<?= $k['id'] ?>" 
                                    class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-lg shadow-red-500/10 leading-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <div id="edit-kelas-<?= $k['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[70] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4">
                        <div class="relative w-full max-w-xl bg-white dark:bg-[#1e293b] rounded-[2.5rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
                            <div class="p-10 bg-amber-500 text-white flex justify-between items-center relative overflow-hidden leading-none">
                                <div class="relative z-10 leading-none">
                                    <h3 class="text-xl font-extrabold uppercase tracking-tighter italic leading-none">Update Data Kelas</h3>
                                    <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest mt-1 italic leading-none">Nama: <?= $k['nama_kelas'] ?></p>
                                </div>
                                <button data-modal-hide="edit-kelas-<?= $k['id'] ?>" class="text-white/60 hover:text-white transition-colors bg-white/10 p-2 rounded-full leading-none">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <form action="<?= base_url('admin/master/kelas_update/' . $k['id']) ?>" method="POST" class="p-10 space-y-7">
                                <?= csrf_field() ?>
                                <div class="space-y-3 text-left">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Nama Kelas</label>
                                    <input type="text" name="nama_kelas" value="<?= $k['nama_kelas'] ?>" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl p-5 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 outline-none transition-all leading-none" required>
                                </div>
                                <div class="grid grid-cols-2 gap-5">
                                    <div class="space-y-3 text-left">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Jurusan</label>
                                        <select name="id_jurusan" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl p-5 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 outline-none appearance-none leading-none" required>
                                            <option value="">-- PILIH --</option>
                                            <?php foreach($jurusan as $j): ?>
                                                <option value="<?= $j['id'] ?>" <?= ($j['id'] == $k['id_jurusan']) ? 'selected' : '' ?>><?= $j['kode_jurusan'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="space-y-3 text-left">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Wali Kelas</label>
                                        <select name="guru_id" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl p-5 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 outline-none appearance-none leading-none" required>
                                            <option value="">-- PILIH --</option>
                                            <?php foreach($guru as $g): ?>
                                                <option value="<?= $g['id'] ?>" <?= ($g['id'] == $k['guru_id']) ? 'selected' : '' ?>><?= $g['nama_lengkap'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-6 bg-amber-500 hover:bg-amber-600 text-white font-extrabold rounded-[2rem] text-[11px] uppercase shadow-2xl shadow-amber-500/30 tracking-[0.3em] transition-all active:scale-95 leading-none">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>

                    <div id="del-kelas-<?= $k['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[70] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4">
                        <div class="relative w-full max-w-md bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden p-10 text-center">
                            <div class="w-24 h-24 bg-red-100 dark:bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-7 text-red-500 leading-none">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </div>
                            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white uppercase italic tracking-tighter leading-none">Hapus Kelas?</h3>
                            <p class="text-xs text-slate-500 font-bold mb-10 uppercase tracking-widest leading-none px-2">Data <span class="text-red-500 font-black"><?= $k['nama_kelas'] ?></span> akan hilang permanen!</p>
                            <div class="flex gap-4">
                                <button data-modal-hide="del-kelas-<?= $k['id'] ?>" class="flex-1 py-4 text-xs font-extrabold text-slate-400 uppercase tracking-widest leading-none">Batal</button>
                                <a href="<?= base_url('admin/master/kelas_hapus/' . $k['id']) ?>" class="flex-1 py-4 bg-red-600 hover:bg-red-700 text-white font-extrabold rounded-2xl text-[10px] uppercase shadow-xl shadow-red-500/20 tracking-widest text-center transition-all leading-none">Ya, Hapus!</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-tambah-kelas" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4 overflow-y-auto">
    <div class="relative w-full max-w-xl bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden leading-none">
        <div class="p-10 bg-blue-600 text-white flex justify-between items-center relative overflow-hidden leading-none">
            <div class="relative z-10 leading-none">
                <h3 class="text-xl font-extrabold uppercase tracking-tighter italic leading-none">Registrasi Kelas</h3>
                <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest mt-2 leading-none">Daftarkan Rombongan Belajar Baru.</p>
            </div>
            <button data-modal-hide="modal-tambah-kelas" class="text-white/60 hover:text-white transition-colors bg-white/10 p-2 rounded-full leading-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= base_url('admin/master/kelas_simpan') ?>" method="POST" class="p-10 space-y-7">
            <?= csrf_field() ?>
            <div class="space-y-3">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Nama Kelas</label>
                <input type="text" name="nama_kelas" placeholder="X TJKT 1" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-5 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none transition-all leading-none" required>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div class="space-y-3 leading-none">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Pilih Jurusan</label>
                    <select name="id_jurusan" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-5 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none transition-all appearance-none leading-none" required>
                        <option value="">-- PILIH --</option>
                        <?php foreach($jurusan as $j): ?>
                            <option value="<?= $j['id'] ?>"><?= $j['kode_jurusan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="space-y-3 leading-none">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Pilih Wali Kelas</label>
                    <select name="guru_id" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-5 text-sm font-bold uppercase text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 outline-none transition-all appearance-none leading-none" required>
                        <option value="">-- PILIH --</option>
                        <?php foreach($guru as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= $g['nama_lengkap'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full py-6 bg-blue-600 hover:bg-blue-500 text-white font-extrabold rounded-[2rem] text-[11px] uppercase shadow-2xl shadow-blue-500/30 tracking-[0.3em] transition-all active:scale-95 leading-none">
                Simpan Entitas Kelas
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>