<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="p-4 sm:ml-64 mt-14">
    <h2 class="text-2xl font-bold mb-4 dark:text-white">Edit Kelas</h2>
    
    <form action="<?= base_url('admin/kelas/update/'.$kelas['id']) ?>" method="post">
        <div class="mb-6">
            <label for="nama_kelas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
            <input type="text" id="nama_kelas" name="nama_kelas" value="<?= $kelas['nama_kelas'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
        </div>
        
        <div class="mb-6">
    <label for="guru_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Wali Kelas</label>
    <select id="guru_id" name="guru_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
        <option value="">-- Pilih Wali Kelas --</option>
        <?php foreach($gurus as $g): ?>
            <?php 
                // Format nama lengkap dengan gelar
                $namaLengkap = ($g['gelar_depan'] ? $g['gelar_depan'] . ' ' : '') . 
                               $g['nama_lengkap'] . 
                               ($g['gelar_belakang'] ? ', ' . $g['gelar_belakang'] : '');
            ?>
            <option value="<?= $g['id'] ?>" <?= ($g['id'] == $kelas['guru_id']) ? 'selected' : '' ?>>
                <?= $namaLengkap ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Update</button>
        <a href="<?= base_url('admin/kelas') ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 ml-2">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>