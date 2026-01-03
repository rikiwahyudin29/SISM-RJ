<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="p-4 sm:ml-64 mt-14">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Ekskul</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            
            <form action="<?= base_url('admin/ekskul/simpan') ?>" method="post">
                
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    
                    <div class="col-span-2 md:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ekskul</label>
                        <input type="text" name="nama_ekskul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Pramuka, Futsal" required>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pembina</label>
                        <select name="guru_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">-- Pilih Guru Pembina --</option>
                            <?php foreach($guru as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= $g['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hari Latihan</label>
                        <select name="hari" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Latihan</label>
                        <input type="text" name="jam" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: 15:00 - 17:00">
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Simpan Data
                    </button>
                    <a href="<?= base_url('admin/ekskul') ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>