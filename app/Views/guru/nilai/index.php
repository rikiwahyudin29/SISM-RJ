<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📝 Input Nilai Siswa</h1>
            <p class="text-sm text-gray-500">Kelola nilai harian, PTS, dan PAS per kelas.</p>
        </div>
        <div class="flex gap-2 mt-4 md:mt-0">
            <button onclick="openModal('modalSetting')" class="px-4 py-2 bg-amber-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-amber-500/30 hover:bg-amber-600 transition-all">
                <i class="fas fa-cog mr-2"></i> SETTING BOBOT & KKTP
            </button>
            <button onclick="openModal('modalImport')" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 transition-all">
                <i class="fas fa-file-excel mr-2"></i> IMPORT EXCEL
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
        <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Pilih Kelas</label>
                <select name="kelas_id" required class="w-full p-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-blue-500 dark:text-white text-sm">
                    <option value="">- Pilih Kelas -</option>
                    <?php foreach($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Mata Pelajaran</label>
                <select name="mapel_id" required class="w-full p-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-blue-500 dark:text-white text-sm">
                    <option value="">- Pilih Mapel -</option>
                    <?php foreach($mapel as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= (isset($_GET['mapel_id']) && $_GET['mapel_id'] == $m['id']) ? 'selected' : '' ?>><?= $m['nama_mapel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="p-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/30">
                <i class="fas fa-search mr-2"></i> TAMPILKAN
            </button>
        </form>
    </div>

    <?php if(!empty($siswa)): ?>
    <form action="<?= base_url('guru/nilai/save_batch') ?>" method="POST">
        <?= csrf_field(); ?>
        <input type="hidden" name="kelas_id" value="<?= $_GET['kelas_id'] ?>">
        <input type="hidden" name="mapel_id" value="<?= $_GET['mapel_id'] ?>">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-4 py-4 text-center">Tugas (%)</th>
                        <th class="px-4 py-4 text-center">UH (%)</th>
                        <th class="px-4 py-4 text-center">PTS (%)</th>
                        <th class="px-4 py-4 text-center">PAS (%)</th>
                        <th class="px-4 py-4 text-center bg-blue-50 dark:bg-blue-900/20">Akhir</th>
                        <th class="px-4 py-4 text-center bg-gray-100 dark:bg-gray-800">Predikat</th> 
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach($siswa as $s): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800 dark:text-white"><?= $s['nama_lengkap'] ?></p>
                            <p class="text-[10px] text-gray-400">NIS: <?= $s['id'] ?></p>
                        </td>
                        <td class="px-4 py-4"><input type="number" name="nilai[<?= $s['id'] ?>][tugas]" value="<?= $s['tugas'] ?? 0 ?>" class="w-20 mx-auto block p-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-center text-sm dark:text-white"></td>
                        <td class="px-4 py-4"><input type="number" name="nilai[<?= $s['id'] ?>][uh]" value="<?= $s['uh'] ?? 0 ?>" class="w-20 mx-auto block p-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-center text-sm dark:text-white"></td>
                        <td class="px-4 py-4"><input type="number" name="nilai[<?= $s['id'] ?>][pts]" value="<?= $s['pts'] ?? 0 ?>" class="w-20 mx-auto block p-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-center text-sm dark:text-white"></td>
                        <td class="px-4 py-4"><input type="number" name="nilai[<?= $s['id'] ?>][pas]" value="<?= $s['pas'] ?? 0 ?>" class="w-20 mx-auto block p-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-center text-sm dark:text-white"></td>
                        <td class="px-4 py-4 text-center font-black text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10">
                            <?= $s['akhir'] ?? '-' ?>
                        </td>
                        <td class="px-4 py-4 text-center font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">
            <?php 
                $p = $s['predikat'] ?? '-';
                // Opsional: Warna-warni predikat
                if($p == 'A') echo '<span class="text-emerald-600">A</span>';
                elseif($p == 'B') echo '<span class="text-blue-600">B</span>';
                elseif($p == 'C') echo '<span class="text-yellow-600">C</span>';
                elseif($p == 'D') echo '<span class="text-red-600">D</span>';
                else echo '-';
            ?>
        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="p-6 bg-gray-50 dark:bg-gray-700/30 border-t dark:border-gray-700 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition-all">
                    <i class="fas fa-save mr-2"></i> SIMPAN SEMUA NILAI
                </button>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<div id="modalSetting" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
            <h3 class="font-bold text-gray-800 dark:text-white uppercase tracking-tight">Setting Komponen Nilai</h3>
            <button onclick="closeModal('modalSetting')" class="text-gray-400 hover:text-rose-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="<?= base_url('guru/nilai/save_setting') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field(); ?>
            <input type="hidden" name="kelas_id" value="<?= $_GET['kelas_id'] ?? '' ?>">
            <input type="hidden" name="mapel_id" value="<?= $_GET['mapel_id'] ?? '' ?>">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Bobot Tugas (%)</label><input type="number" name="p_tugas" value="<?= $setting->p_tugas ?? 20 ?>" class="w-full p-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl dark:text-white"></div>
                <div><label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Bobot UH (%)</label><input type="number" name="p_uh" value="<?= $setting->p_uh ?? 30 ?>" class="w-full p-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl dark:text-white"></div>
                <div><label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Bobot PTS (%)</label><input type="number" name="p_pts" value="<?= $setting->p_pts ?? 25 ?>" class="w-full p-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl dark:text-white"></div>
                <div><label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Bobot PAS (%)</label><input type="number" name="p_pas" value="<?= $setting->p_pas ?? 25 ?>" class="w-full p-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl dark:text-white"></div>
            </div>
            <div><label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">KKTP / KKM</label><input type="number" name="kkm" value="<?= $setting->kkm ?? 75 ?>" class="w-full p-2.5 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl dark:text-white font-bold text-blue-600"></div>
            <button type="submit" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 hover:bg-amber-600 transition-all">SIMPAN SETTING</button>
        </form>
    </div>
</div>
<div id="modalImport" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden relative">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-emerald-50 dark:bg-emerald-900/20">
            <h3 class="font-bold text-gray-800 dark:text-white uppercase tracking-tight">Import Nilai (Excel/CSV)</h3>
            <button onclick="closeModal('modalImport')" class="text-gray-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="<?= base_url('guru/nilai/import') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            <?= csrf_field(); ?>
            <input type="hidden" name="kelas_id" value="<?= $_GET['kelas_id'] ?? '' ?>">
            <input type="hidden" name="mapel_id" value="<?= $_GET['mapel_id'] ?? '' ?>">

            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                <p class="text-xs font-bold text-blue-600 dark:text-blue-400 mb-2 uppercase">Langkah 1: Download Template</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Download template kelas ini, lalu isi kolom Tugas, UH, PTS, dan PAS. Jangan ubah ID Siswa!</p>
                <a href="<?= base_url('guru/nilai/download_template?kelas_id='.($_GET['kelas_id']??'').'&mapel_id='.($_GET['mapel_id']??'')) ?>" target="_blank" class="block w-full text-center py-2 bg-white dark:bg-gray-800 border border-blue-300 text-blue-600 rounded-lg text-sm font-bold hover:bg-blue-50 transition-colors">
                    <i class="fas fa-download mr-2"></i> DOWNLOAD TEMPLATE .CSV
                </a>
            </div>

            <div>
                <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">Langkah 2: Upload File (CSV)</label>
                <input type="file" name="file_csv" required accept=".csv" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 focus:outline-none">
                <p class="mt-1 text-[10px] text-gray-400">*Pastikan format file adalah .csv (Comma Delimited)</p>
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 transition-all">
                <i class="fas fa-upload mr-2"></i> UPLOAD & PROSES NILAI
            </button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
</script>

<?= $this->endSection(); ?>