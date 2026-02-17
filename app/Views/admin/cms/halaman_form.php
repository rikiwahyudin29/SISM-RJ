<?= $this->extend('layout/template') ?>

<?= $this->section('header') ?>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
    <div class="mb-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Halaman: <?= $page['judul'] ?></h3>
    </div>
    
    <form action="<?= base_url('admin/cms/halaman/save') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $page['id'] ?>">

        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Halaman</label>
            <input type="text" name="judul" value="<?= $page['judul'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
        </div>

        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Konten Halaman</label>
            <textarea name="isi" id="editor"><?= $page['isi'] ?></textarea>
        </div>

        <div class="flex items-center space-x-2">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Simpan Perubahan
            </button>
            <a href="<?= base_url('admin/cms/halaman') ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
    tinymce.init({
        selector: '#editor',
        height: 500,
        menubar: false,
        plugins: 'lists link image code table wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | image code',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>
<?= $this->endSection() ?>