<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📚 Materi Pelajaran</h1>
            <p class="text-sm text-gray-500 font-medium uppercase">KELAS : <?= $siswa->kelas_id ?> (Fokus Belajar Hari Ini!)</p>
        </div>
    </div>

    <?php if(empty($materi)): ?>
        <div class="bg-white dark:bg-slate-800 p-10 rounded-3xl text-center border-2 border-dashed border-gray-200 dark:border-slate-700">
            <i class="fas fa-book-reader text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 font-bold italic">Belum ada materi yang diupload gurumu.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($materi as $m): ?>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden group hover:ring-2 hover:ring-blue-500 transition-all">
                <div class="p-5">
                    <div class="flex justify-between items-center mb-3">
                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase rounded">
                            <?= $m['nama_mapel'] ?>
                        </span>
                        <span class="text-[10px] text-gray-400 font-bold"><?= date('d M Y', strtotime($m['created_at'])) ?></span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2"><?= $m['judul'] ?></h3>
                    <p class="text-xs text-blue-500 font-bold mb-4 flex items-center">
                        <i class="fas fa-user-tie mr-2"></i> <?= $m['nama_guru'] ?>
                    </p>
                    
                    <p class="text-sm text-gray-500 dark:text-slate-400 mb-4 line-clamp-3">
                        <?= $m['deskripsi'] ? $m['deskripsi'] : 'Klik tombol di bawah untuk mempelajari materi.' ?>
                    </p>

                    <div class="flex gap-2 border-t dark:border-slate-700 pt-4">
                        <?php if($m['file_materi']): ?>
                        <button onclick="previewFile('<?= base_url('uploads/materi/'.$m['file_materi']) ?>')" class="flex-1 py-2 bg-blue-600 text-white rounded-lg font-bold text-xs hover:bg-blue-700 transition-all shadow-md shadow-blue-500/20">
                            <i class="fas fa-file-alt mr-1"></i> BUKA MATERI
                        </button>
                        <?php endif; ?>

                        <?php if($m['link_youtube']): ?>
                        <button onclick="previewVideo('<?= $m['link_youtube'] ?>')" class="flex-1 py-2 bg-red-600 text-white rounded-lg font-bold text-xs hover:bg-red-700 transition-all shadow-md shadow-red-500/20">
                            <i class="fab fa-youtube mr-1"></i> VIDEO
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div id="modalPreview" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-gray-900/90 backdrop-blur-md p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-5xl h-[90vh] rounded-3xl overflow-hidden flex flex-col scale-95 animate-zoom-in">
        <div class="p-4 border-b dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900/50">
            <h3 id="previewTitle" class="font-bold dark:text-white">Preview Materi</h3>
            <button onclick="closeModal()" class="p-2 bg-red-100 text-red-600 rounded-full hover:rotate-90 transition-all duration-300">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="flex-1 bg-gray-200 dark:bg-slate-900 overflow-hidden" id="previewContainer"></div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('modalPreview').classList.add('hidden');
        document.getElementById('previewContainer').innerHTML = '';
    }

    function previewFile(url) {
        document.getElementById('previewTitle').innerText = '📄 Membaca Materi';
        const container = document.getElementById('previewContainer');
        const extension = url.split('.').pop().toLowerCase();
        
        if (extension === 'pdf') {
            container.innerHTML = `<embed src="${url}" type="application/pdf" class="w-full h-full" />`;
        } else {
            // Logika Localhost vs Online
            if (window.location.hostname === "localhost" || window.location.hostname === "127.0.0.1") {
                container.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-center p-10">
                    <i class="fas fa-file-word text-6xl text-blue-500 mb-4"></i>
                    <h4 class="text-xl font-bold dark:text-white">Mode Pratinjau Terbatas</h4>
                    <p class="text-gray-400 mb-6">File .${extension} harus didownload untuk dibuka di Localhost.</p>
                    <a href="${url}" download class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold">Download File</a>
                </div>`;
            } else {
                const embedUrl = `https://docs.google.com/gview?url=${url}&embedded=true`;
                container.innerHTML = `<iframe src="${embedUrl}" class="w-full h-full border-0"></iframe>`;
            }
        }
        document.getElementById('modalPreview').classList.remove('hidden');
    }

    function previewVideo(url) {
        document.getElementById('previewTitle').innerText = '🎥 Belajar via Video';
        let videoId = url.split('v=')[1] || url.split('/').pop();
        const ampersandPosition = videoId.indexOf('&');
        if(ampersandPosition != -1) videoId = videoId.substring(0, ampersandPosition);
        
        const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        document.getElementById('previewContainer').innerHTML = `<iframe src="${embedUrl}" class="w-full h-full border-0" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
        document.getElementById('modalPreview').classList.remove('hidden');
    }
</script>

<?= $this->endSection(); ?>