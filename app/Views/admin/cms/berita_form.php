<?= $this->extend('layout/template') ?>

<?= $this->section('header') ?>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white"><?= $title ?></h3>
    </div>
    
    <form action="<?= base_url('admin/cms/berita/save') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $berita['id'] ?? '' ?>">

        <div class="grid gap-6 mb-6">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Berita</label>
                <input type="text" name="judul" value="<?= $berita['judul'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required placeholder="Tulis judul berita yang menarik...">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gambar Utama (Thumbnail)</label>
                <?php if(!empty($berita['gambar'])): ?>
                    <img src="<?= base_url('uploads/berita/'.$berita['gambar']) ?>" class="h-32 w-auto rounded-lg mb-2 object-cover">
                <?php endif; ?>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="gambar" accept="image/*">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi Berita</label>
                <textarea name="isi" id="editor"><?= $berita['isi'] ?? '' ?></textarea>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Terbitkan Berita
            </button>
            <a href="<?= base_url('admin/cms/berita') ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    tinymce.init({
        selector: '#editor',
        height: 400,
        menubar: false,
        plugins: 'lists link image code table wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | image code',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>
<?= $this->endSection() ?>