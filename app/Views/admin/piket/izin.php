<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">⛔ Izin Keluar Siswa</h1>
            <p class="text-sm text-gray-500 font-medium">Catat dan cetak surat izin siswa yang meninggalkan lingkungan sekolah.</p>
        </div>
        
        <button onclick="document.getElementById('modalIzin').classList.remove('hidden')" class="mt-4 md:mt-0 px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition-all flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            BUAT SURAT IZIN
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-t-2xl border-t border-x border-gray-100 dark:border-slate-700 p-4">
        <form action="" method="GET" class="flex items-center gap-2">
            <span class="text-sm font-bold text-gray-500 uppercase">Filter Kelas:</span>
            <select name="kelas" onchange="this.form.submit()" class="p-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 font-bold w-full md:w-64">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach($list_kelas as $kls): ?>
                    <option value="<?= $kls['id'] ?>" <?= (isset($_GET['kelas']) && $_GET['kelas'] == $kls['id']) ? 'selected' : '' ?>>
                        <?= $kls['nama_kelas'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 overflow-hidden shadow-sm rounded-b-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 uppercase text-[10px] font-black border-b dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Waktu Keluar</th>
                        <th class="px-6 py-4">Identitas Siswa</th>
                        <th class="px-6 py-4">Alasan / Keperluan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    <?php if(empty($izin)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 font-bold uppercase tracking-widest">
                                Belum ada data izin keluar hari ini.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach($izin as $i): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-800 dark:text-white"><?= date('H:i', strtotime($i['waktu_keluar'])) ?> WIB</div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase"><?= date('d M Y', strtotime($i['waktu_keluar'])) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-600 dark:text-indigo-400"><?= $i['nama_lengkap'] ?></div>
                            <div class="text-[10px] text-gray-500 font-black uppercase tracking-wide mt-1"><?= $i['nama_kelas'] ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 dark:text-gray-300 font-medium bg-gray-50 dark:bg-slate-900 p-2 rounded border border-gray-100 dark:border-slate-700">
                                "<?= $i['alasan'] ?>"
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= base_url('admin/piket/cetakIzin/' . $i['id']) ?>" target="_blank" class="inline-flex items-center px-3 py-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all font-bold text-xs border border-rose-100 shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                CETAK SURAT
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalIzin" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up border border-gray-100 dark:border-slate-700">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center bg-indigo-50 dark:bg-indigo-900/20">
            <h3 class="font-bold text-indigo-800 dark:text-white uppercase tracking-tight text-sm">Form Izin Keluar Siswa</h3>
            <button onclick="document.getElementById('modalIzin').classList.add('hidden')" class="text-gray-400 hover:text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="<?= base_url('admin/piket/saveIzin') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field(); ?>
            
            <?php if(empty($siswa)): ?>
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs font-bold text-amber-700 uppercase">
                                PERHATIAN: Silakan pilih filter kelas di dashboard terlebih dahulu untuk memunculkan daftar nama siswa.
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block">Nama Siswa</label>
                    <select name="siswa_id" required class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Siswa --</option>
                        <?php foreach($siswa as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['nama_lengkap'] ?> (<?= $s['nis'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block">Alasan / Keperluan</label>
                    <textarea name="alasan" rows="3" required class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: Mengambil barang tertinggal, sakit, urusan keluarga..."></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-black rounded-xl shadow-lg hover:bg-indigo-700 transition-all uppercase text-xs tracking-widest">
                    Simpan & Cetak Surat
                </button>
            <?php endif; ?>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>