<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Atur Anggota: <span class="text-blue-600"><?= $kelas_asal['nama_kelas'] ?></span></h1>
            <p class="text-sm text-gray-500">Centang siswa yang ingin dipindahkan atau diluluskan.</p>
        </div>
        <a href="<?= base_url('admin/rombel') ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">Kembali</a>
    </div>
</div>

<form action="<?= base_url('admin/rombel/proses_pindah') ?>" method="POST" id="form-pindah">
    <?= csrf_field() ?>
    
    <div class="p-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-center">
        <div class="flex items-center h-5">
            <input id="select-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="select-all" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Pilih Semua</label>
        </div>
        
        <div class="flex-1 w-full md:w-auto flex gap-2">
            <select name="aksi" id="aksi-select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="pindah">Pindah / Naik Kelas</option>
                <option value="lulus">Luluskan (Alumni)</option>
            </select>
            
            <select name="kelas_tujuan" id="tujuan-select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">-- Pilih Kelas Tujuan --</option>
                <?php foreach($kelas_all as $ka): ?>
                    <?php if($ka['id'] != $kelas_asal['id']): // Jangan tampilkan kelas asal ?>
                    <option value="<?= $ka['id'] ?>"><?= $ka['nama_kelas'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <button type="submit" onclick="return confirm('Yakin ingin memproses siswa terpilih?')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 whitespace-nowrap">
                Proses Data
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4 w-4">#</th>
                    <th scope="col" class="px-6 py-3">NISN</th>
                    <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                    <th scope="col" class="px-6 py-3">Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($siswa)): ?>
                    <tr><td colspan="4" class="text-center p-4">Tidak ada siswa aktif di kelas ini.</td></tr>
                <?php else: ?>
                    <?php foreach($siswa as $s): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="siswa_id[]" value="<?= $s['id'] ?>" class="siswa-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono"><?= $s['nisn'] ?></td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white"><?= $s['nama_lengkap'] ?></td>
                        <td class="px-6 py-4"><?= $s['jenis_kelamin'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</form>

<script>
    // Script Pilih Semua & Toggle Dropdown Tujuan
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.siswa-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Kalau pilih "Luluskan", sembunyikan dropdown Kelas Tujuan karena tidak butuh
    const aksiSelect = document.getElementById('aksi-select');
    const tujuanSelect = document.getElementById('tujuan-select');

    aksiSelect.addEventListener('change', function() {
        if(this.value === 'lulus') {
            tujuanSelect.style.display = 'none';
            tujuanSelect.required = false;
        } else {
            tujuanSelect.style.display = 'block';
            tujuanSelect.required = true;
        }
    });
</script>

<?= $this->endSection() ?>