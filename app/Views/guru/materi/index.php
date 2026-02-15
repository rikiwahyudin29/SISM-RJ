<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📚 Materi Pelajaran</h1>
            <p class="text-sm text-gray-500">Kelola dan bagikan bahan ajar kepada siswa.</p>
        </div>
        <button onclick="openModal('modalTambah')" class="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            TAMBAH MATERI
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($materi as $m): ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden group relative hover:shadow-md transition-all">
            
            <div class="absolute top-3 right-3 flex gap-2 z-10">
                <button onclick="editMateri(<?= htmlspecialchars(json_encode($m)) ?>)" class="p-2 bg-white/90 dark:bg-slate-700/90 text-amber-500 rounded-lg hover:bg-amber-500 hover:text-white transition-all shadow-sm border border-gray-100 dark:border-slate-600 group/edit" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </button>
                
                <a href="<?= base_url('guru/materi/delete/'.$m['id']) ?>" onclick="return confirm('Yakin ingin menghapus materi ini?')" class="p-2 bg-white/90 dark:bg-slate-700/90 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-gray-100 dark:border-slate-600" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </a>
            </div>

            <div class="p-5 pt-8"> <div class="flex justify-between items-start mb-3">
                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase rounded border border-blue-100 dark:border-blue-800">
                        <?= $m['nama_mapel'] ?>
                    </span>
                </div>

                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1 line-clamp-1" title="<?= $m['judul'] ?>"><?= $m['judul'] ?></h3>
                <p class="text-xs text-blue-500 font-medium mb-3 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <?= $m['nama_kelas'] ?>
                </p>
                
                <div class="bg-gray-50 dark:bg-slate-700/50 p-3 rounded-xl mb-4 h-16">
                    <p class="text-sm text-gray-500 dark:text-slate-400 line-clamp-2 italic">"<?= $m['deskripsi'] ?>"</p>
                </div>

                <div class="flex gap-2 pt-2">
                    <?php if($m['file_materi']): ?>
                    <button onclick="previewFile('<?= base_url('uploads/materi/'.$m['file_materi']) ?>')" class="flex-1 py-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg font-bold text-xs hover:bg-emerald-600 hover:text-white transition-all border border-emerald-100 dark:border-emerald-900 flex items-center justify-center group/btn">
                        <svg class="w-4 h-4 mr-1 group-hover/btn:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        LIHAT FILE
                    </button>
                    <?php endif; ?>

                    <?php if($m['link_youtube']): ?>
                    <button onclick="previewVideo('<?= $m['link_youtube'] ?>')" class="flex-1 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg font-bold text-xs hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:border-red-900 flex items-center justify-center group/btn">
                        <svg class="w-4 h-4 mr-1 group-hover/btn:animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        NONTON
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center bg-blue-50 dark:bg-blue-900/20">
            <h3 class="font-bold text-blue-800 dark:text-white">Upload Materi Baru</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= base_url('guru/materi/save') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field(); ?>
            <input type="hidden" name="guru_id" value="<?= $guru->id ?>">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Kelas</label>
                    <select name="kelas_id" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white">
                        <?php foreach($kelas as $k): ?><option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Mapel</label>
                    <select name="mapel_id" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white">
                        <?php foreach($mapel as $m): ?><option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Judul Materi</label>
                <input type="text" name="judul" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white" placeholder="Contoh: Pengenalan PHP Dasar">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white" placeholder="Jelaskan isi materi ini..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">File (PDF/Word/PPT)</label>
                    <input type="file" name="file_materi" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Link YouTube (Opsional)</label>
                    <input type="text" name="link_youtube" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white" placeholder="https://youtube.com/...">
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all mt-4">UPLOAD MATERI</button>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center bg-amber-50 dark:bg-amber-900/20">
            <h3 class="font-bold text-amber-800 dark:text-amber-400">Edit Materi Pelajaran</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= base_url('guru/materi/update') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" id="edit_id">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Kelas</label>
                    <select name="kelas_id" id="edit_kelas_id" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border rounded-xl text-sm dark:text-white">
                        <?php foreach($kelas as $k): ?><option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Mapel</label>
                    <select name="mapel_id" id="edit_mapel_id" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border rounded-xl text-sm dark:text-white">
                        <?php foreach($mapel as $m): ?><option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Judul Materi</label>
                <input type="text" name="judul" id="edit_judul" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border rounded-xl text-sm dark:text-white">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border rounded-xl text-sm dark:text-white"></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Ganti Link YouTube</label>
                <input type="text" name="link_youtube" id="edit_link_youtube" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border rounded-xl text-sm dark:text-white">
            </div>

            <div class="p-3 bg-amber-50 dark:bg-amber-900/10 rounded-lg border border-amber-100 dark:border-amber-900/20">
                <p class="text-[10px] text-amber-700 dark:text-amber-400 font-medium italic mb-1">*Biarkan kosong jika tidak ingin mengganti file materi.</p>
                <input type="file" name="file_materi" class="text-xs text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-amber-100 file:text-amber-700">
            </div>

            <button type="submit" class="w-full py-3 bg-amber-600 text-white font-bold rounded-xl shadow-lg hover:bg-amber-700 transition-all">SIMPAN PERUBAHAN</button>
        </form>
    </div>
</div>

<div id="modalPreview" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-gray-900/90 backdrop-blur-md p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-5xl h-[90vh] rounded-3xl overflow-hidden flex flex-col scale-95 animate-zoom-in">
        <div class="p-4 border-b dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900/50">
            <h3 id="previewTitle" class="font-bold dark:text-white flex items-center">
                <span id="previewIcon" class="mr-2"></span> Preview Materi
            </h3>
            <button onclick="closeModal('modalPreview')" class="p-2 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="flex-1 bg-gray-200 dark:bg-slate-900 overflow-hidden relative" id="previewContainer">
            </div>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        if(id === 'modalPreview') document.getElementById('previewContainer').innerHTML = ''; // Stop video/pdf saat ditutup
    }

    // Fungsi isi form modal edit
    function editMateri(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_kelas_id').value = data.kelas_id;
        document.getElementById('edit_mapel_id').value = data.mapel_id;
        document.getElementById('edit_judul').value = data.judul;
        document.getElementById('edit_deskripsi').value = data.deskripsi;
        document.getElementById('edit_link_youtube').value = data.link_youtube;
        openModal('modalEdit');
    }

    // Fungsi Preview File (PDF/PPT/Doc)
    function previewFile(url) {
        document.getElementById('previewTitle').innerText = 'Document Viewer';
        const container = document.getElementById('previewContainer');
        const extension = url.split('.').pop().toLowerCase();
        
        if (extension === 'pdf') {
            // Untuk PDF, browser modern bisa render langsung tanpa Google Docs
            container.innerHTML = `<embed src="${url}" type="application/pdf" class="w-full h-full" />`;
        } else {
            // Jika di Localhost, Google Docs Viewer TIDAK AKAN BISA baca file
            if (window.location.hostname === "localhost" || window.location.hostname === "127.0.0.1") {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-center p-10">
                        <svg class="w-20 h-20 text-blue-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <h4 class="text-xl font-bold dark:text-white">Pratinjau Terbatas (Localhost)</h4>
                        <p class="text-gray-500 mb-6">File .${extension} tidak dapat dipratinjau di server lokal oleh Google Viewer.</p>
                        <a href="${url}" target="_blank" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download File
                        </a>
                    </div>`;
            } else {
                // Jika sudah di Hosting Online, baru pakai Google Docs
                const embedUrl = `https://docs.google.com/gview?url=${url}&embedded=true`;
                container.innerHTML = `<iframe src="${embedUrl}" class="w-full h-full border-0"></iframe>`;
            }
        }
        openModal('modalPreview');
    }

    // Fungsi Preview Video YouTube
    function previewVideo(url) {
        document.getElementById('previewTitle').innerText = 'Video Tutorial';
        // Convert link youtube biasa ke format embed
        let videoId = url.split('v=')[1] || url.split('/').pop();
        const ampersandPosition = videoId.indexOf('&');
        if(ampersandPosition != -1) videoId = videoId.substring(0, ampersandPosition);
        
        const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        document.getElementById('previewContainer').innerHTML = `<iframe src="${embedUrl}" class="w-full h-full border-0" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
        openModal('modalPreview');
    }
</script>

<?= $this->endSection(); ?>