<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow dark:bg-gray-800">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Data Siswa</h2>
        <a href="<?= base_url('admin/siswa') ?>" class="text-sm text-blue-600 hover:underline">Kembali</a>
    </div>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="p-4 mb-4 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-gray-700 dark:text-red-400">
            <ul class="list-disc pl-5">
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/siswa/update/' . $siswa['id']) ?>" method="post">
        
        <h3 class="mb-4 text-lg font-medium text-gray-900 border-b pb-2 dark:text-white">1. Data Akademik</h3>
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIS</label>
                <input type="number" name="nis" value="<?= $siswa['nis'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NISN</label>
                <input type="number" name="nisn" value="<?= $siswa['nisn'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Kelas</label>
                <select name="kelas_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($data_kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($siswa['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                            <?= $k['nama_kelas'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ekstrakurikuler</label>
                <select name="ekskul_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    <option value="">-- Tidak Ada --</option>
                    <?php foreach($data_ekskul as $e): ?>
                        <option value="<?= $e['id'] ?>" <?= ($siswa['ekskul_id'] == $e['id']) ? 'selected' : '' ?>>
                            <?= $e['nama_ekskul'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <h3 class="mb-4 text-lg font-medium text-gray-900 border-b pb-2 dark:text-white">2. Data Pribadi</h3>
        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap</label>
            <input type="text" name="nama" value="<?= $siswa['nama'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
        </div>

        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="<?= $siswa['tempat_lahir'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="<?= $siswa['tanggal_lahir'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                <select name="jk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    <option value="L" <?= ($siswa['jk'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= ($siswa['jk'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIK</label>
                <input type="number" name="nik" value="<?= $siswa['nik'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            </div>
        </div>

        <h3 class="mb-4 text-lg font-medium text-gray-900 border-b pb-2 dark:text-white">3. Data Orang Tua</h3>
        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ibu Kandung</label>
            <input type="text" name="nama_ibu" value="<?= $siswa['nama_ibu'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
        </div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Update Data Siswa
        </button>
    </form>
</div>
<?= $this->endSection() ?>