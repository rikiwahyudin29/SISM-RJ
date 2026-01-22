<?= $this->extend('layout/template_admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:ml-64">
    <div class="flex items-center justify-between mb-6 mt-14">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Jadwal (Mode Admin)</h1>
        <a href="<?= base_url('admin/jadwalujian') ?>" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm">Kembali</a>
    </div>

    <form action="<?= base_url('admin/jadwalujian/simpan') ?>" method="post" class="space-y-6 pb-10">
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow border border-gray-200 dark:border-slate-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Jadwal</label>
                    <input type="text" name="nama_ujian" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Bank Soal (Dari Guru)</label>
                    <select name="id_bank_soal" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                        <option value="">-- Pilih Bank Soal --</option>
                        <?php foreach($bank_soal as $b): ?>
                            <option value="<?= $b['id'] ?>">
                                [<?= $b['nama_guru'] ?>] - <?= $b['judul_ujian'] ?> (<?= $b['nama_mapel'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tahun Pelajaran</label>
                    <select name="id_tahun_ajaran" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                        <?php foreach($tahun_ajaran as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= $t['tahun_ajaran'] ?? $t['tahun'] ?> (<?= $t['semester'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Ujian</label>
                    <select name="id_jenis_ujian" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                        <?php foreach($jenis_ujian as $j): ?>
                            <option value="<?= $j['id'] ?>"><?= $j['nama_jenis'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow border border-gray-200 dark:border-slate-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" class="w-full p-2 border rounded dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" class="w-full p-2 border rounded dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Durasi (Menit)</label>
                    <input type="number" name="durasi" class="w-full p-2 border rounded dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="60">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Bobot PG / Esai (%)</label>
                    <div class="flex gap-2">
                        <input type="number" name="bobot_pg" value="100" class="w-full p-2 border rounded dark:bg-slate-900 dark:text-white" placeholder="PG">
                        <input type="number" name="bobot_esai" value="100" class="w-full p-2 border rounded dark:bg-slate-900 dark:text-white" placeholder="Esai">
                    </div>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Target Kelas</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-h-40 overflow-y-auto p-3 border rounded bg-gray-50 dark:bg-slate-900 dark:border-slate-600">
                        <?php foreach($kelas as $k): ?>
                            <label class="flex items-center space-x-2 p-1 hover:bg-gray-200 dark:hover:bg-slate-700 rounded cursor-pointer">
                                <input type="checkbox" name="kelas[]" value="<?= $k['id'] ?>" class="rounded text-blue-600">
                                <span class="text-sm dark:text-white"><?= $k['nama_kelas'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow border border-gray-200 dark:border-slate-700 grid grid-cols-2 md:grid-cols-4 gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="setting_strict" value="1" class="rounded text-blue-600">
                <span class="text-sm font-bold dark:text-white">Mode Ketat</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="setting_token" value="1" class="rounded text-blue-600" id="chkToken" onchange="toggleToken()">
                <span class="text-sm font-bold dark:text-white">Pakai Token</span>
            </label>
             <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="acak_soal" value="1" checked class="rounded text-blue-600">
                <span class="text-sm font-bold dark:text-white">Acak Soal</span>
            </label>
            <input type="text" name="token" id="inpToken" class="hidden w-full p-1 text-center border rounded uppercase" placeholder="TOKEN">
        </div>

        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg">SIMPAN JADWAL</button>
    </form>
</div>

<script>
    function toggleToken() {
        const chk = document.getElementById('chkToken');
        const inp = document.getElementById('inpToken');
        if(chk.checked) inp.classList.remove('hidden'); else inp.classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>