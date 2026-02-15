<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex items-center gap-4 mb-6 mt-14">
        <a href="<?= base_url('guru/tugas') ?>" class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:bg-gray-50 border border-gray-100 dark:border-slate-700">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Koreksi Tugas</h1>
            <p class="text-sm text-gray-500"><?= $tugas->nama_mapel ?> • <?= $tugas->nama_kelas ?></p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 mb-6">
        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-white"><?= $tugas->judul ?></h2>
        <p class="text-gray-500 text-sm mb-4"><?= $tugas->deskripsi ?></p>
        <?php if($tugas->file_pendukung): ?>
            <a href="<?= base_url('uploads/tugas/'.$tugas->file_pendukung) ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors border border-blue-100">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                Lihat Soal / Lampiran
            </a>
        <?php endif; ?>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-slate-700 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 w-10">No</th>
                        <th class="px-6 py-3">Nama Siswa</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">File Jawaban</th>
                        <th class="px-6 py-3 text-center w-32">Nilai</th>
                        <th class="px-6 py-3 text-center">Feedback</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    <?php $no=1; foreach($siswa as $s): ?>
                        <?php 
                            $k = $pengumpulan[$s['id']] ?? null;
                            $sudah_kumpul = !empty($k);
                            $nilai = $k['nilai'] ?? '';
                        ?>
                    <form action="<?= base_url('guru/tugas/nilai') ?>" method="POST">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="tugas_id" value="<?= $tugas->id ?>">
                        <input type="hidden" name="siswa_id" value="<?= $s['id'] ?>">
                        
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-500"><?= $no++ ?></td>
                            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                                <?= $s['nama_lengkap'] ?>
                                <div class="text-xs text-gray-400 font-normal"><?= $s['nis'] ?></div>
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                <?php if($sudah_kumpul): ?>
                                    <?php if($k['status_kumpul'] == 'Terlambat'): ?>
                                        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-600 rounded text-[10px] font-bold uppercase">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Terlambat
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-600 rounded text-[10px] font-bold uppercase">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Tepat Waktu
                                        </span>
                                    <?php endif; ?>
                                    <div class="text-[10px] text-gray-400 mt-1 font-mono">
                                        <?= date('d/m H:i', strtotime($k['tgl_kumpul'])) ?>
                                    </div>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-400 rounded text-[10px] font-bold uppercase">Belum Kumpul</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <?php if($sudah_kumpul && $k['file_jawaban']): ?>
                                    <button type="button" onclick="previewJawaban('<?= base_url('uploads/tugas_siswa/'.$k['file_jawaban']) ?>', '<?= $s['nama_lengkap'] ?>')" class="inline-flex items-center justify-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-xs font-bold transition-all border border-emerald-100">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat File
                                    </button>
                                    
                                    <?php if($k['catatan_siswa']): ?>
                                        <div class="text-[10px] text-gray-500 mt-1 italic max-w-[150px] mx-auto truncate cursor-help border-b border-dashed border-gray-300" title="<?= $k['catatan_siswa'] ?>">
                                            "<?= $k['catatan_siswa'] ?>"
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4">
                                <div class="relative">
                                    <input type="number" name="nilai" value="<?= $nilai ?>" min="0" max="100" class="w-full text-center font-bold border border-gray-200 rounded-lg p-2 bg-gray-50 dark:bg-slate-900 dark:border-slate-600 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm" placeholder="0">
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <input type="text" name="komentar_guru" value="<?= $k['komentar_guru'] ?? '' ?>" class="w-full border border-gray-200 rounded-lg p-2 text-xs bg-gray-50 dark:bg-slate-900 dark:border-slate-600 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="Beri feedback...">
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button type="submit" class="p-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all group" title="Simpan Nilai">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4h3m-3-4V5a2 2 0 012-2h1a2 2 0 012 2v2m-6 16h6m-6-4h6m-6-4h6"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </form>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalPreview" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-gray-900/95 backdrop-blur-md p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-5xl h-[90vh] rounded-3xl overflow-hidden flex flex-col scale-95 animate-zoom-in">
        <div class="p-4 border-b dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900/50">
            <div>
                <h3 class="font-bold dark:text-white text-lg">Jawaban Siswa</h3>
                <p id="previewName" class="text-xs text-blue-600 font-bold uppercase"></p>
            </div>
            <div class="flex gap-2">
                <a id="btnDownload" href="#" target="_blank" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download
                </a>
                <button onclick="closeModal()" class="p-1.5 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        
        <div class="flex-1 bg-gray-800 overflow-auto flex items-center justify-center relative" id="previewContainer">
            </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('modalPreview').classList.add('hidden');
        document.getElementById('previewContainer').innerHTML = ''; // Clear konten biar ringan
    }

    function previewJawaban(url, namaSiswa) {
        document.getElementById('previewName').innerText = namaSiswa;
        document.getElementById('btnDownload').href = url; // Set link download
        
        const container = document.getElementById('previewContainer');
        const ext = url.split('.').pop().toLowerCase();
        
        container.innerHTML = '<div class="text-white animate-pulse">Memuat...</div>'; // Loading indicator

        // Logic Preview
        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
            // Jika Gambar
            container.innerHTML = `<img src="${url}" class="max-w-full max-h-full object-contain shadow-2xl">`;
        } else if (ext === 'pdf') {
            // Jika PDF (Browser Native)
            container.innerHTML = `<embed src="${url}" type="application/pdf" class="w-full h-full border-0" />`;
        } else {
            // Jika File Lain (Word, Zip, dll)
            container.innerHTML = `
                <div class="text-center p-10 bg-white/10 rounded-2xl backdrop-blur-sm">
                    <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h4 class="text-xl font-bold text-white mb-2">Tidak dapat dipratinjau</h4>
                    <p class="text-gray-300 mb-6 text-sm">File bertipe .${ext} harus didownload untuk dibuka.</p>
                    <a href="${url}" target="_blank" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold inline-flex items-center hover:bg-blue-700 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download File
                    </a>
                </div>`;
        }
        
        document.getElementById('modalPreview').classList.remove('hidden');
    }
</script>

<?= $this->endSection(); ?>