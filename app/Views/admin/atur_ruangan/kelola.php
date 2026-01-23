<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-1">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $ruang['nama_ruangan'] ?></h1>
            <p class="text-sm text-gray-500">Manajemen plotting peserta ujian.</p>
        </div>
        <a href="<?= base_url('admin/aturruangan') ?>" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm font-bold">Kembali</a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-3 mb-4 text-sm text-green-800 bg-green-100 rounded-lg"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow border border-gray-200 dark:border-slate-700 flex flex-col h-[500px]">
            <div class="p-4 border-b dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50">
                <h3 class="font-bold text-gray-700 dark:text-gray-200 mb-2"><i class="fas fa-user-plus mr-2"></i> Pilih Siswa (Sumber)</h3>
                
                <form action="" method="get" class="flex gap-2">
                    <input type="hidden" name="id_sesi" value="<?= $sesi_aktif ?>"> <select name="id_kelas" class="w-full text-sm p-2 rounded border bg-white dark:bg-slate-900 dark:text-white" onchange="this.form.submit()">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= ($kelas_aktif == $k['id']) ? 'selected' : '' ?>>
                                <?= $k['nama_kelas'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <form action="<?= base_url('admin/aturruangan/tambah') ?>" method="post" class="flex-1 flex flex-col overflow-hidden">
                <input type="hidden" name="id_ruangan" value="<?= $ruang['id'] ?>">
                <input type="hidden" name="id_sesi" value="<?= $sesi_aktif ?>">

                <div class="overflow-y-auto flex-1 p-2">
                    <?php if(empty($siswa_tersedia)): ?>
                        <div class="text-center py-10 text-gray-400 text-sm">
                            <?= $kelas_aktif ? 'Semua siswa di kelas ini sudah punya ruangan.' : 'Pilih kelas dulu di atas.' ?>
                        </div>
                    <?php else: ?>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="p-2 w-8">
                                        <input type="checkbox" onclick="toggleAll(this)" class="rounded text-blue-600">
                                    </th>
                                    <th scope="col" class="p-2">Nama Siswa</th>
                                    <th scope="col" class="p-2">Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($siswa_tersedia as $s): ?>
                                    <tr class="bg-white border-b hover:bg-gray-50 dark:bg-slate-800 dark:border-slate-700">
                                        <td class="p-2">
                                            <input type="checkbox" name="siswa_ids[]" value="<?= $s['id'] ?>" class="chk-siswa rounded text-blue-600">
                                        </td>
                                        <td class="p-2 font-medium text-gray-900 dark:text-white"><?= $s['nama_siswa'] ?></td>
                                        <td class="p-2"><?= $s['nama_kelas'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="p-4 border-t dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 text-right">
                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow">
                        MASUKKAN KE RUANGAN <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>


        <div class="bg-white dark:bg-slate-800 rounded-xl shadow border border-gray-200 dark:border-slate-700 flex flex-col h-[500px]">
            <div class="p-4 border-b dark:border-slate-700 bg-blue-50 dark:bg-slate-900">
                <h3 class="font-bold text-blue-800 dark:text-blue-300 mb-2"><i class="fas fa-desktop mr-2"></i> Isi Ruangan</h3>
                
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-gray-500">Pilih Sesi:</span>
                    <div class="flex gap-1 overflow-x-auto">
                        <?php foreach($sesi as $s): ?>
                            <a href="?id_kelas=<?= $kelas_aktif ?>&id_sesi=<?= $s['id'] ?>" 
                               class="px-3 py-1 text-xs font-bold rounded border <?= ($sesi_aktif == $s['id']) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-100' ?>">
                                <?= $s['nama_sesi'] ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="overflow-y-auto flex-1 p-2">
                <?php if(empty($siswa_terdaftar)): ?>
                    <div class="text-center py-10 text-gray-400 text-sm">
                        Ruangan ini masih kosong pada Sesi ini.
                    </div>
                <?php else: ?>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700">
                            <tr>
                                <th class="p-2">No PC</th>
                                <th class="p-2">Nama Siswa</th>
                                <th class="p-2">Kelas</th>
                                <th class="p-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($siswa_terdaftar as $st): ?>
                                <tr class="bg-white border-b hover:bg-gray-50 dark:bg-slate-800 dark:border-slate-700">
                                    <td class="p-2 font-mono font-bold text-blue-600"><?= $st['no_komputer'] ?></td>
                                    <td class="p-2 font-medium text-gray-900 dark:text-white"><?= $st['nama_siswa'] ?></td>
                                    <td class="p-2 text-xs"><?= $st['nama_kelas'] ?></td>
                                    <td class="p-2 text-center">
                                        <a href="<?= base_url("admin/aturruangan/hapus/{$st['id']}/{$ruang['id']}/{$sesi_aktif}") ?>" 
                                           class="text-red-600 hover:text-red-800" onclick="return confirm('Keluarkan siswa ini?')">
                                            <i class="fas fa-times-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <div class="p-3 border-t dark:border-slate-700 text-center text-xs text-gray-500">
                Total: <b><?= count($siswa_terdaftar) ?></b> Peserta
            </div>
        </div>

    </div>
</div>

<script>
    function toggleAll(source) {
        checkboxes = document.getElementsByClassName('chk-siswa');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>

<?= $this->endSection() ?>