<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-8" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="p-8 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700 rounded-[2.5rem] shadow-xl flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-[800] text-gray-900 dark:text-white uppercase tracking-tighter leading-none">Mata Pelajaran</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-2 text-center md:text-left">Manajemen Kurikulum & Kelompok Belajar</p>
        </div>
        <button onclick="document.getElementById('modal-mapel').classList.remove('hidden')" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl text-[11px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
            + Tambah Mapel
        </button>
    </div>

    <div class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700 rounded-[2.5rem] shadow-xl overflow-hidden text-center md:text-left">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50 text-[11px] font-[800] uppercase text-slate-500 tracking-widest border-b dark:border-slate-700/50">
                    <tr>
                        <th class="px-8 py-6">Kode</th>
                        <th class="px-8 py-6">Nama Mata Pelajaran</th>
                        <th class="px-8 py-6 text-center">Kelompok</th>
                        <th class="px-8 py-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-700/30 text-gray-900 dark:text-white">
                    <?php if(empty($mapel)): ?>
                        <tr>
                            <td colspan="4" class="p-10 text-center text-xs font-bold text-slate-400 uppercase tracking-widest italic">Belum ada data mapel, Bos!</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach($mapel as $m): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all">
                        <td class="px-8 py-6 text-center md:text-left">
                            <span class="bg-blue-600/10 text-blue-500 px-3 py-1.5 rounded-xl font-bold text-[11px] border border-blue-500/20"><?= $m['kode_mapel'] ?></span>
                        </td>
                        <td class="px-8 py-6 font-bold text-xs uppercase leading-relaxed"><?= $m['nama_mapel'] ?></td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-500 rounded-lg border border-amber-500/20 text-[10px] font-[800] uppercase">Kelompok <?= $m['kelompok'] ?></span>
                        </td>
                        <td class="px-8 py-6 text-center flex items-center justify-center gap-3">
                            <button data-modal-target="edit-mapel-<?= $m['id'] ?>" data-modal-toggle="edit-mapel-<?= $m['id'] ?>" class="p-2.5 text-amber-500 bg-amber-500/5 hover:bg-amber-500 hover:text-white rounded-xl transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button data-modal-target="del-mapel-<?= $m['id'] ?>" data-modal-toggle="del-mapel-<?= $m['id'] ?>" class="p-2.5 text-red-500 bg-red-500/5 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>

                    <div id="edit-mapel-<?= $m['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[70] flex justify-center items-center bg-slate-900/60 backdrop-blur-md p-4 overflow-y-auto">
                        <div class="relative w-full max-w-2xl bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
                            <div class="p-8 bg-amber-500 text-white flex justify-between items-center text-left">
                                <h3 class="font-bold uppercase text-xs tracking-widest leading-none">Update Mata Pelajaran</h3>
                                <button data-modal-hide="edit-mapel-<?= $m['id'] ?>" class="text-white/50 hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <form action="<?= base_url('admin/master/mapel_update/' . $m['id']) ?>" method="POST" class="p-10 space-y-6 text-left">
                                <?= csrf_field() ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Kode Mapel</label>
                                        <input type="text" name="kode_mapel" value="<?= $m['kode_mapel'] ?>" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Kelompok Mapel</label>
                                        <select name="kelompok" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none shadow-inner">
                                            <option value="A" <?= ($m['kelompok'] == 'A') ? 'selected' : '' ?>>KELOMPOK A (UMUM)</option>
                                            <option value="B" <?= ($m['kelompok'] == 'B') ? 'selected' : '' ?>>KELOMPOK B (WAJIB)</option>
                                            <option value="C" <?= ($m['kelompok'] == 'C') ? 'selected' : '' ?>>KELOMPOK C (PRODUKTIF)</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Nama Lengkap Mapel</label>
                                    <input type="text" name="nama_mapel" value="<?= $m['nama_mapel'] ?>" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none" required>
                                </div>
                                <button type="submit" class="w-full py-5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl text-[11px] uppercase shadow-xl shadow-amber-500/20 active:scale-95 transition-all tracking-[0.3em]">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>

                    <div id="del-mapel-<?= $m['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[70] flex justify-center items-center bg-slate-900/60 backdrop-blur-sm p-4">
                        <div class="relative w-full max-w-md bg-white dark:bg-[#1e293b] rounded-[2.5rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
                            <div class="p-8 text-center text-left">
                                <div class="w-20 h-20 bg-red-100 dark:bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500 shadow-sm">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase mb-2 tracking-tighter leading-none">Hapus Mapel?</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mb-8 uppercase tracking-widest leading-relaxed px-4">Entitas <span class="text-red-500 font-bold"><?= $m['nama_mapel'] ?></span> akan dihapus dari kurikulum selamanya, Bos!</p>
                                <div class="flex gap-3">
                                    <button data-modal-hide="del-mapel-<?= $m['id'] ?>" class="flex-1 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Batal</button>
                                    <a href="<?= base_url('admin/master/mapel_hapus/' . $m['id']) ?>" class="flex-1 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl text-[10px] uppercase shadow-lg shadow-red-500/20 tracking-widest text-center transition-all">Ya, Hapus!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-mapel" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-slate-900/80 backdrop-blur-md p-4 overflow-y-auto">
    <div class="relative w-full max-w-2xl bg-white dark:bg-[#1e293b] rounded-[3rem] shadow-2xl border dark:border-slate-700 overflow-hidden">
        <div class="p-8 bg-emerald-600 text-white flex justify-between items-center">
            <h3 class="font-bold uppercase text-xs tracking-widest leading-none">Konfigurasi Mata Pelajaran Baru</h3>
            <button onclick="document.getElementById('modal-mapel').classList.add('hidden')" class="text-white/50 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= base_url('admin/master/mapel_simpan') ?>" method="POST" class="p-10 space-y-6">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest leading-none">Kode Mapel</label>
                    <input type="text" name="kode_mapel" placeholder="MTK01" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest leading-none">Kelompok Mapel</label>
                    <select name="kelompok" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none shadow-inner">
                        <option value="A">KELOMPOK A (UMUM)</option>
                        <option value="B">KELOMPOK B (WAJIB)</option>
                        <option value="C">KELOMPOK C (PRODUKTIF)</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest leading-none">Nama Lengkap Mapel</label>
                <input type="text" name="nama_mapel" placeholder="Matematika Diskrit..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none shadow-inner" required>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-4 ml-1 tracking-widest text-center leading-none">Pilih Relevansi Jurusan</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-[2rem] border border-dashed border-slate-300 dark:border-slate-700 shadow-inner">
                    <?php foreach($jurusan as $j): ?>
                    <label class="flex items-center gap-3 cursor-pointer group hover:bg-white dark:hover:bg-slate-700 p-2 rounded-xl transition-all">
                        <input type="checkbox" name="id_jurusan[]" value="<?= $j['id'] ?>" class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 bg-white dark:bg-slate-800 transition-all">
                        <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase group-hover:text-emerald-500"><?= $j['kode_jurusan'] ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl text-[11px] uppercase shadow-xl shadow-emerald-500/20 active:scale-95 transition-all tracking-[0.3em]">Konfigurasi Mapel</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>