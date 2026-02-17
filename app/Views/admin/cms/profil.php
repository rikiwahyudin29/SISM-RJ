<?= $this->extend('layout/template') ?>

<?= $this->section('header') ?>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700 rounded-t-lg">
    <div class="w-full mb-1">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Konfigurasi Tampilan Website</h1>
        <p class="text-sm text-gray-500">Atur sambutan kepala sekolah, sosial media, dan teks banner depan di sini.</p>
    </div>
</div>

<div class="p-4">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/cms/save_profil') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b pb-2">Sambutan Kepala Sekolah</h3>
                    
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kepala Sekolah (Tampilan Web)</label>
                        <input type="text" name="nama_kepsek" value="<?= $web['nama_kepsek'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Foto Profil</label>
                        <?php if(!empty($web['foto_kepsek'])): ?>
                            <img src="<?= base_url('uploads/profil/'.$web['foto_kepsek']) ?>" class="h-32 w-auto mb-3 rounded-lg border">
                        <?php endif; ?>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" type="file" name="foto_kepsek" accept="image/*">
                        <p class="mt-1 text-xs text-gray-500">Format JPG/PNG. Disarankan rasio 1:1 atau 3:4.</p>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi Sambutan</label>
                        <textarea name="sambutan_kepsek" id="editor"><?= $web['sambutan_kepsek'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b pb-2">Banner Utama (Hero)</h3>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teks Deskripsi Singkat</label>
                        <textarea name="deskripsi_hero" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"><?= $web['deskripsi_hero'] ?></textarea>
                        <p class="mt-1 text-xs text-gray-500">Teks ini muncul di bawah judul besar di halaman depan.</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b pb-2">Kontak & Media Sosial</h3>
                    
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><i class="fab fa-facebook text-blue-600"></i> Facebook Link</label>
                        <input type="text" name="link_fb" value="<?= $web['link_fb'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><i class="fab fa-instagram text-pink-600"></i> Instagram Link</label>
                        <input type="text" name="link_ig" value="<?= $web['link_ig'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><i class="fab fa-youtube text-red-600"></i> Youtube Link</label>
                        <input type="text" name="link_yt" value="<?= $web['link_yt'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><i class="fas fa-map-marked-alt text-green-600"></i> Google Maps Embed</label>
                        <input type="text" name="link_map" value="<?= htmlspecialchars($web['link_map']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder='<iframe src="https://google.com/maps..."></iframe>'>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    tinymce.init({
        selector: '#editor',
        height: 300,
        menubar: false,
        plugins: 'lists link code',
        toolbar: 'undo redo | bold italic | alignleft aligncenter | bullist numlist'
    });
</script>
<?= $this->endSection() ?>