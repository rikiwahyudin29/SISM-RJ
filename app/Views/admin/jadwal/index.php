<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Jadwal Pelajaran</h1>
        <p class="text-sm text-gray-500">
            Tahun Ajaran: <span class="font-bold text-blue-600"><?= !empty($tahun) ? esc($tahun['tahun_ajaran']) : '-' ?></span>
        </p>
    </div>
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        
        <?php if(!empty($tahun)): ?>
        <button type="button" data-modal-target="modal-jadwal" data-modal-toggle="modal-jadwal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 shadow-lg">
            <i class="fas fa-plus mr-2"></i> Tambah Jadwal
        </button>
        <?php endif; ?>

        <form action="" method="GET" class="flex flex-col md:flex-row gap-2 w-full md:w-auto bg-gray-50 p-2 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
            
            <select name="id_jurusan" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                <option value="">- Semua Jurusan -</option>
                <?php foreach($jurusan as $j): ?>
                    <option value="<?= $j['id'] ?>" <?= ($f_jurusan == $j['id']) ? 'selected' : '' ?>><?= $j['nama_jurusan'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="id_kelas" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                <option value="">- Semua Kelas -</option>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($f_kelas == $k['id']) ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="id_guru" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                <option value="">- Semua Guru -</option>
                <?php foreach($guru as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= ($f_guru == $g['id']) ? 'selected' : '' ?>><?= $g['nama_lengkap'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-500">
                <i class="fas fa-filter"></i> Filter
            </button>
            
            <a href="<?= base_url('admin/jadwal') ?>" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                Reset
            </a>

            <a href="<?= base_url('admin/jadwal/cetak') ?>?<?= $_SERVER['QUERY_STRING'] ?? '' ?>" target="_blank" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-500 dark:hover:bg-red-600">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="<?= base_url('admin/jadwal/rekap') ?>" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 shadow-lg">
    <i class="fas fa-calculator mr-2"></i> Rekap Beban Mengajar
</a>
        </form>
    </div>
</div>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 m-4 font-bold">
        <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('success')) : ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 m-4">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="p-4">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Hari</th>
                    <th class="px-6 py-3">Jam (WIB)</th>
                    <th class="px-6 py-3">Kelas</th>
                    <th class="px-6 py-3">Mata Pelajaran</th>
                    <th class="px-6 py-3">Guru</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($jadwal)): ?>
                    <tr><td colspan="6" class="text-center p-8 italic text-gray-500">
                        Data tidak ditemukan. Coba reset filter atau tambah jadwal.
                    </td></tr>
                <?php else: ?>
                    <?php foreach($jadwal as $j): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                        <td class="px-6 py-4 font-bold"><?= $j['hari'] ?></td>
                        <td class="px-6 py-4 font-mono">
                            <?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-blue-600"><?= $j['nama_kelas'] ?></td>
                        <td class="px-6 py-4 font-semibold"><?= $j['nama_mapel'] ?></td>
                        <td class="px-6 py-4 text-xs"><?= $j['nama_guru'] ?></td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= base_url('admin/jadwal/hapus/' . $j['id']) ?>" onclick="return confirm('Hapus?')" class="text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modal-jadwal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center bg-gray-900/50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-clock text-blue-600"></i> Set Jadwal (Otomatis)
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="modal-jadwal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            
            <form action="<?= base_url('admin/jadwal/simpan') ?>" method="POST" class="p-6 space-y-6">
                <?= csrf_field() ?>
                <input type="hidden" name="id_tahun_ajaran" value="<?= $tahun['id'] ?? '' ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <h4 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-3 border-b border-blue-200 pb-2">Pengaturan Waktu</h4>
                            
                            <div class="mb-3">
                                <label class="block mb-1 text-xs font-semibold uppercase text-gray-500">Hari</label>
                                <select name="hari" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block mb-1 text-xs font-semibold uppercase text-gray-500">Mulai Jam Ke-</label>
                                    <select id="start_jp" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <option value="">- Pilih -</option>
                                        <?php if(!empty($jam_master)): ?>
                                            <?php foreach($jam_master as $jm): ?>
                                                <option value="<?= $jm['urutan'] ?>"><?= $jm['nama_jam'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block mb-1 text-xs font-semibold uppercase text-gray-500">Durasi (JP)</label>
                                    <select id="durasi_jp" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <option value="1">1 JP</option>
                                        <option value="2">2 JP</option>
                                        <option value="3">3 JP</option>
                                        <option value="4">4 JP</option>
                                        <option value="5">5 JP</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block mb-1 text-xs text-gray-500">Jam Mulai (Auto)</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed" readonly required>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs text-gray-500">Jam Selesai (Auto)</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed" readonly required>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                            <select name="id_kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                <?php foreach($kelas as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                            <select name="id_mapel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                <?php foreach($mapel as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Guru Pengampu</label>
                            <select name="id_guru" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                <option value="">-- Pilih Guru --</option>
                                <?php if(!empty($guru)): ?>
                                    <?php foreach($guru as $g): ?>
                                        <option value="<?= $g['id'] ?>"><?= $g['nama_lengkap'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full md:w-auto">
                        <i class="fas fa-save mr-2"></i> Simpan Jadwal
                    </button>
                    <button data-modal-toggle="modal-jadwal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const jamMaster = <?= $jam_all_json ?>; 
    
    const elStart = document.getElementById('start_jp');
    const elDurasi = document.getElementById('durasi_jp');
    const elJamMulai = document.getElementById('jam_mulai');
    const elJamSelesai = document.getElementById('jam_selesai');

    function hitungJam() {
        const urutanMulai = parseInt(elStart.value);
        const durasi = parseInt(elDurasi.value);

        if(!urutanMulai || !durasi) return;

        const jamAwal = jamMaster.find(j => parseInt(j.urutan) === urutanMulai);
        
        if(jamAwal) {
            elJamMulai.value = jamAwal.jam_mulai.substring(0, 5);
            let countJP = 0;
            let targetJam = null;
            const startIndex = jamMaster.findIndex(j => parseInt(j.urutan) === urutanMulai);
            for (let i = startIndex; i < jamMaster.length; i++) {
                if (jamMaster[i].is_istirahat == 0) {
                    countJP++;
                }
                if (countJP === durasi) {
                    targetJam = jamMaster[i];
                    break;
                }
            }
            if (targetJam) {
                elJamSelesai.value = targetJam.jam_selesai.substring(0, 5);
            } else {
                elJamSelesai.value = jamMaster[jamMaster.length - 1].jam_selesai.substring(0, 5);
            }
        }
    }

    elStart.addEventListener('change', hitungJam);
    elDurasi.addEventListener('change', hitungJam);
</script>

<?= $this->endSection() ?>