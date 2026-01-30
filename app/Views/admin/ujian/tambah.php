<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:ml-2">
    
    <div class="flex items-center justify-between mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Jadwal (Admin)</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Anda membuat jadwal atas nama Guru Pemilik Bank Soal.</p>
        </div>
        <a href="<?= base_url('admin/jadwalujian') ?>" class="px-4 py-2 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-all text-sm font-medium shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="<?= base_url('admin/jadwalujian/simpan') ?>" method="post" class="space-y-6 pb-10">
        
        <?= csrf_field() ?>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="w-1 h-5 bg-blue-600 rounded-full"></span> Informasi Dasar
                </h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Nama Jadwal / Ujian</label>
                    <input type="text" name="nama_ujian" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white transition-colors" placeholder="Contoh: PAS Matematika X RPL" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Tahun Pelajaran</label>
                    <select name="id_tahun_ajaran" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Tahun --</option>
                        <?php foreach($tahun_ajaran as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= $t['tahun_ajaran'] ?? $t['tahun'] ?> (<?= $t['semester'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Jenis Ujian</label>
                    <select name="id_jenis_ujian" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach($jenis_ujian as $j): ?>
                            <option value="<?= $j['id'] ?>"><?= $j['nama_jenis'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Pilih Bank Soal (Dari Guru)</label>
                    <select name="id_bank_soal" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Bank Soal --</option>
                        <?php foreach($bank_soal as $b): ?>
                            <option value="<?= $b['id'] ?>">
                                [<?= $b['nama_guru'] ?>] - <?= $b['judul_ujian'] ?> (<?= $b['nama_mapel'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="w-1 h-5 bg-purple-600 rounded-full"></span> Bobot Penilaian
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Bobot Pilihan Ganda (PG)</label>
                    <div class="relative">
                        <input type="number" name="bobot_pg" value="100" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-purple-500 text-gray-900 dark:text-white" placeholder="100">
                        <span class="absolute right-4 top-3 text-gray-400 text-sm">%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Bobot Non-PG (Esai/Kompleks/Jodoh)</label>
                    <div class="relative">
                        <input type="number" name="bobot_esai" value="100" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-purple-500 text-gray-900 dark:text-white" placeholder="100">
                        <span class="absolute right-4 top-3 text-gray-400 text-sm">%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="w-1 h-5 bg-green-600 rounded-full"></span> Waktu & Peserta
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Mulai</label>
                        <input type="datetime-local" name="waktu_mulai" class="w-full p-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg text-sm dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Selesai (Tutup)</label>
                        <input type="datetime-local" name="waktu_selesai" class="w-full p-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg text-sm dark:text-white" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Durasi (Menit)</label>
                        <input type="number" name="durasi" class="w-full p-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg text-sm dark:text-white" placeholder="60">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Min. Submit (Menit)</label>
                        <input type="number" name="min_waktu" class="w-full p-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg text-sm dark:text-white" placeholder="30">
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Target Kelas</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-h-40 overflow-y-auto p-3 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-900/50">
                        <?php foreach($kelas as $k): ?>
                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-white dark:hover:bg-slate-800 p-2 rounded transition-colors">
                                <input type="checkbox" name="kelas[]" value="<?= $k['id'] ?>" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300"><?= $k['nama_kelas'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-bold text-gray-800 dark:text-white text-sm">Mode Ketat (Strict)</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="setting_strict" value="1" class="sr-only peer" onclick="toggleStrict(this)">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>
                <div id="strictOptions" class="space-y-3 opacity-50 pointer-events-none transition-opacity">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-1">Batas Max Pelanggaran</label>
                        <input type="number" name="setting_max_violation" value="3" class="w-full p-2 text-sm bg-gray-50 dark:bg-slate-900 border rounded-lg dark:border-slate-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-1">AFK Timeout (Detik)</label>
                        <input type="number" name="setting_afk_timeout" value="0" class="w-full p-2 text-sm bg-gray-50 dark:bg-slate-900 border rounded-lg dark:border-slate-600 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-bold text-gray-800 dark:text-white text-sm">Pakai Token?</span>
                    <input type="checkbox" name="setting_token" value="1" id="chkToken" class="w-4 h-4 rounded text-blue-600" onchange="toggleToken()">
                </div>
                <input type="text" name="token" id="inpToken" class="w-full p-2 text-center uppercase tracking-widest font-mono text-lg bg-gray-50 dark:bg-slate-900 border rounded-lg dark:border-slate-600 dark:text-white hidden" placeholder="TOKEN">
                <div class="mt-4 pt-4 border-t dark:border-slate-700 flex justify-between items-center">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Tampilkan Nilai?</span>
                    <input type="checkbox" name="setting_show_score" value="1" class="w-4 h-4 rounded text-blue-600">
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-bold text-gray-800 dark:text-white text-sm">Single Device</span>
                    <input type="checkbox" name="setting_multi_login" value="1" checked class="w-4 h-4 rounded text-blue-600">
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="space-y-3">
                    <label class="flex justify-between items-center cursor-pointer">
                        <span class="text-sm font-bold text-gray-800 dark:text-white">Acak Soal</span>
                        <input type="checkbox" name="acak_soal" value="1" checked class="w-4 h-4 rounded text-blue-600">
                    </label>
                    <hr class="dark:border-slate-700">
                    <label class="flex justify-between items-center cursor-pointer">
                        <span class="text-sm font-bold text-gray-800 dark:text-white">Acak Opsi</span>
                        <input type="checkbox" name="acak_opsi" value="1" checked class="w-4 h-4 rounded text-blue-600">
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-6">
            <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-transform active:scale-95">
                <i class="fas fa-save mr-2"></i> TERBITKAN JADWAL
            </button>
        </div>

    </form>
</div>

<script>
    function toggleStrict(el) {
        const opts = document.getElementById('strictOptions');
        if (el.checked) {
            opts.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            opts.classList.add('opacity-50', 'pointer-events-none');
        }
    }

    function toggleToken() {
        const chk = document.getElementById('chkToken');
        const inp = document.getElementById('inpToken');
        if (chk.checked) {
            inp.classList.remove('hidden');
            inp.required = true;
        } else {
            inp.classList.add('hidden');
            inp.required = false;
        }
    }
</script>

<?= $this->endSection() ?>