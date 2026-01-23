<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-2">
    
    <div class="flex items-center justify-between mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Jadwal Ujian</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui data jadwal ujian yang sudah ada.</p>
        </div>
        <a href="<?= base_url('admin/jadwalujian') ?>" class="px-4 py-2 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-all text-sm font-medium shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="<?= base_url('admin/jadwalujian/update/' . $j['id']) ?>" method="post" class="space-y-6 pb-10">
        
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="w-1 h-5 bg-blue-600 rounded-full"></span> Informasi Dasar
                </h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Nama Jadwal / Ujian</label>
                    <input type="text" name="nama_ujian" value="<?= $j['nama_ujian'] ?>" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-gray-900 dark:text-white" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Tahun Pelajaran</label>
                    <select name="id_tahun_ajaran" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Tahun --</option>
                        <?php foreach($tahun_ajaran as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($t['id'] == $j['id_tahun_ajaran']) ? 'selected' : '' ?>>
                                <?= $t['tahun_ajaran'] ?? $t['tahun'] ?> (<?= $t['semester'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Jenis Ujian</label>
                    <select name="id_jenis_ujian" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach($jenis_ujian as $ju): ?>
                            <option value="<?= $ju['id'] ?>" <?= ($ju['id'] == $j['id_jenis_ujian']) ? 'selected' : '' ?>>
                                <?= $ju['nama_jenis'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Pilih Bank Soal (Dari Guru)</label>
                    <select name="id_bank_soal" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Bank Soal --</option>
                        <?php foreach($bank_soal as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= ($b['id'] == $j['id_bank_soal']) ? 'selected' : '' ?>>
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
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Bobot PG (%)</label>
                    <input type="number" name="bobot_pg" value="<?= $j['bobot_pg'] ?>" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Bobot Esai (%)</label>
                    <input type="number" name="bobot_esai" value="<?= $j['bobot_esai'] ?>" class="w-full p-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white">
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
                        <input type="datetime-local" name="waktu_mulai" value="<?= $j['waktu_mulai'] ?>" class="w-full p-2 bg-white dark:bg-slate-900 border rounded-lg text-sm dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Selesai</label>
                        <input type="datetime-local" name="waktu_selesai" value="<?= $j['waktu_selesai'] ?>" class="w-full p-2 bg-white dark:bg-slate-900 border rounded-lg text-sm dark:text-white" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Durasi (Menit)</label>
                        <input type="number" name="durasi" value="<?= $j['durasi'] ?>" class="w-full p-2 bg-white dark:bg-slate-900 border rounded-lg text-sm dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Min. Submit (Menit)</label>
                        <input type="number" name="min_waktu" value="<?= $j['min_waktu_selesai'] ?>" class="w-full p-2 bg-white dark:bg-slate-900 border rounded-lg text-sm dark:text-white">
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Target Kelas</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-h-40 overflow-y-auto p-3 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-900/50">
                        <?php foreach($kelas as $k): ?>
                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-white dark:hover:bg-slate-800 p-2 rounded transition-colors">
                                <input type="checkbox" name="kelas[]" value="<?= $k['id'] ?>" 
                                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                    <?= in_array($k['id'], $kelas_terpilih) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-700 dark:text-gray-300"><?= $k['nama_kelas'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-bold text-gray-800 dark:text-white text-sm">Mode Ketat</span>
                    <input type="checkbox" name="setting_strict" value="1" <?= $j['setting_strict'] ? 'checked' : '' ?> onclick="toggleStrict(this)" class="w-4 h-4 rounded text-blue-600">
                </div>
                <div id="strictOptions" class="<?= $j['setting_strict'] ? '' : 'opacity-50 pointer-events-none' ?> space-y-3">
                    <input type="number" name="setting_max_violation" value="<?= $j['setting_max_violation'] ?>" class="w-full p-2 text-sm border rounded dark:bg-slate-900 dark:text-white" placeholder="Max Pelanggaran">
                    <input type="number" name="setting_afk_timeout" value="<?= $j['setting_afk_timeout'] ?>" class="w-full p-2 text-sm border rounded dark:bg-slate-900 dark:text-white" placeholder="AFK (Detik)">
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-bold text-gray-800 dark:text-white text-sm">Pakai Token</span>
                    <input type="checkbox" name="setting_token" value="1" id="chkToken" <?= $j['setting_token'] ? 'checked' : '' ?> onchange="toggleToken()" class="w-4 h-4 rounded text-blue-600">
                </div>
                <input type="text" name="token" value="<?= $j['token'] ?>" id="inpToken" class="<?= $j['setting_token'] ? '' : 'hidden' ?> w-full p-2 text-center uppercase font-mono bg-gray-50 border rounded dark:bg-slate-900 dark:text-white">
                <div class="mt-2 pt-2 border-t flex justify-between">
                    <span class="text-xs">Show Nilai?</span>
                    <input type="checkbox" name="setting_show_score" value="1" <?= $j['setting_show_score'] ? 'checked' : '' ?> class="w-4 h-4 rounded text-blue-600">
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border flex items-center justify-between">
                <div>
                    <span class="font-bold text-gray-800 dark:text-white text-sm block">Single Device</span>
                    <span class="text-[10px] text-gray-500">1 Akun 1 Perangkat</span>
                </div>
                <input type="checkbox" name="setting_multi_login" value="1" <?= $j['setting_multi_login'] ? 'checked' : '' ?> class="w-4 h-4 rounded text-blue-600">
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border space-y-3">
                <label class="flex justify-between items-center cursor-pointer">
                    <span class="text-sm font-bold dark:text-white">Acak Soal</span>
                    <input type="checkbox" name="acak_soal" value="1" <?= $j['acak_soal'] ? 'checked' : '' ?> class="w-4 h-4 rounded text-blue-600">
                </label>
                <label class="flex justify-between items-center cursor-pointer">
                    <span class="text-sm font-bold dark:text-white">Acak Opsi</span>
                    <input type="checkbox" name="acak_opsi" value="1" <?= $j['acak_opsi'] ? 'checked' : '' ?> class="w-4 h-4 rounded text-blue-600">
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-6">
            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg">SIMPAN PERUBAHAN</button>
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
        } else {
            inp.classList.add('hidden');
        }
    }
</script>

<?= $this->endSection() ?>