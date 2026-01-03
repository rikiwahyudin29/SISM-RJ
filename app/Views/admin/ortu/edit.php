<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow dark:bg-gray-800">
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Edit Data Wali Siswa</h2>

    <form action="<?= base_url('admin/ortu/update/' . $ortu['id']) ?>" method="post">
        
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Siswa</label>
            <select name="siswa_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                <?php foreach($data_siswa as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($s['id'] == $ortu['siswa_id']) ? 'selected' : '' ?>>
                        <?= $s['nama'] ?> (<?= $s['nama_kelas'] ?? '-' ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid gap-4 mb-4 sm:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ayah</label>
                <input type="text" name="nama_ayah" value="<?= $ortu['nama_ayah'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pekerjaan Ayah</label>
                <input type="text" name="pekerjaan_ayah" value="<?= $ortu['pekerjaan_ayah'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="sm:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No. HP (WhatsApp)</label>
                <input type="number" name="no_hp_ortu" value="<?= $ortu['no_hp_ortu'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>
        </div>
        
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">Update Data</button>
    </form>
</div>
<?= $this->endSection() ?>