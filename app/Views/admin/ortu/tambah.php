<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow dark:bg-gray-800">
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Tambah Data Wali Siswa</h2>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="p-4 mb-4 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-gray-700 dark:text-red-400">
            <ul><?php foreach(session()->getFlashdata('errors') as $e) echo "<li>$e</li>"; ?></ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/ortu/simpan') ?>" method="post">
        
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Siswa</label>
            <select name="siswa_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                <option value="">-- Cari Nama Siswa --</option>
                <?php foreach($data_siswa as $s): ?>
                    <option value="<?= $s['id'] ?>">
                        <?= $s['nama'] ?> (<?= $s['nama_kelas'] ?? 'Belum ada kelas' ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1 text-xs text-gray-500">Hanya siswa yang belum memiliki data Ayah yang bisa dipilih (validasi sistem).</p>
        </div>

        <div class="grid gap-4 mb-4 sm:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ayah</label>
                <input type="text" name="nama_ayah" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pekerjaan Ayah</label>
                <input type="text" name="pekerjaan_ayah" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="sm:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No. HP (WhatsApp)</label>
                <input type="number" name="no_hp_ortu" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="0812..." required>
            </div>
        </div>
        
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">Simpan Data</button>
        <a href="<?= base_url('admin/ortu') ?>" class="text-gray-900 bg-white border border-gray-300 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 ms-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>