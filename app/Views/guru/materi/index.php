<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="p-4">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📚 E-Learning: Materi</h1>
            <p class="text-sm text-gray-500">Bagikan bahan ajar ke siswa dengan mudah.</p>
        </div>
        
        <button data-modal-target="modalUpload" data-modal-toggle="modalUpload" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow-lg" type="button">
            <i class="fas fa-plus mr-2"></i> Tambah Materi Baru
        </button>
    </div>

    <?php if(empty($materi)): ?>
        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 border border-blue-300" role="alert">
            <i class="fas fa-info-circle mr-2"></i> Belum ada materi yang diupload. Silakan klik tombol Tambah.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($materi as $m): ?>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative group">
                
                <div class="flex justify-between mb-3">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">
                        <?= $m['nama_kelas'] ?>
                    </span>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded border border-purple-400">
                        <?= $m['nama_mapel'] ?>
                    </span>
                </div>

                <a href="#">
                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white line-clamp-2">
                        <?= $m['judul'] ?>
                    </h5>
                </a>
                
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 text-sm line-clamp-3">
                    <?= $m['deskripsi'] ? $m['deskripsi'] : 'Tidak ada deskripsi.' ?>
                </p>

                <div class="flex items-center justify-between mt-4 border-t pt-3">
                    
                    <?php if($m['file_materi']): ?>
                        <a href="<?= base_url('uploads/materi/'.$m['file_materi']) ?>" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300">
                            <i class="fas fa-download mr-2"></i> File
                        </a>
                    <?php endif; ?>

                    <?php if($m['link_youtube']): ?>
                        <a href="<?= $m['link_youtube'] ?>" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300">
                            <i class="fab fa-youtube mr-2"></i> Video
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('guru/materi/delete/'.$m['id']) ?>" onclick="return confirm('Hapus materi ini?')" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
                
                <div class="absolute top-2 right-2 text-xs text-gray-400">
                    <?= date('d M Y', strtotime($m['created_at'])) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div id="modalUpload" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalUpload">
                <i class="fas fa-times"></i>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">📤 Upload Materi Baru</h3>
                
                <form class="space-y-4" action="<?= base_url('guru/materi/save') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="guru_id" value="<?= $guru->id ?>">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Untuk Kelas</label>
                            <select name="kelas_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">- Pilih Kelas -</option>
                                <?php foreach($kelas as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                            <select name="mapel_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">- Pilih Mapel -</option>
                                <?php foreach($mapel as $mp): ?>
                                    <option value="<?= $mp['id'] ?>"><?= $mp['nama_mapel'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Materi</label>
                        <input type="text" name="judul" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Modul Bab 1 - Aljabar">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi / Instruksi</label>
                        <textarea name="deskripsi" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Berikan keterangan singkat..."></textarea>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File Materi (PDF/Word/PPT)</label>
                        <input type="file" name="file_materi" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <p class="mt-1 text-xs text-gray-500">Max: 10MB.</p>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Link YouTube (Opsional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fab fa-youtube text-red-500"></i>
                            </div>
                            <input type="text" name="link_youtube" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="https://youtube.com/...">
                        </div>
                    </div>

                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-md">Simpan & Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>