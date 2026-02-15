<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📝 Tugas Sekolah</h1>
            <p class="text-sm text-gray-500">Kerjakan tugas tepat waktu agar nilaimu maksimal.</p>
        </div>
        <div class="flex gap-3 mt-4 md:mt-0">
            <div class="px-4 py-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 text-center">
                <span class="block text-xs text-gray-400 font-bold uppercase">Total</span>
                <span class="text-lg font-black text-blue-600"><?= count($tugas) ?></span>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <?php foreach($tugas as $t): ?>
            <?php 
                $deadline = strtotime($t['deadline']);
                $now = time();
                $isLate = $now > $deadline;
                $sudahKumpul = !empty($t['id_kumpul']);
                $sudahDinilai = !empty($t['nilai']);
            ?>
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 relative overflow-hidden group hover:shadow-md transition-all">
            
            <div class="absolute top-0 right-0 p-4">
                <?php if($sudahDinilai): ?>
                    <div class="text-center bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded-lg border border-blue-100 dark:border-blue-800">
                        <span class="block text-[10px] text-blue-600 dark:text-blue-400 font-bold uppercase">Nilai</span>
                        <span class="text-2xl font-black text-blue-700 dark:text-blue-300"><?= $t['nilai'] ?></span>
                    </div>
                <?php elseif($sudahKumpul): ?>
                    <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-xs font-bold">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Sudah Dikirim
                    </span>
                <?php elseif($isLate): ?>
                    <span class="inline-flex items-center px-3 py-1 bg-rose-100 text-rose-600 rounded-full text-xs font-bold">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Terlewat
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tugas Baru
                    </span>
                <?php endif; ?>
            </div>

            <div class="mr-24"> <span class="inline-block px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase rounded border border-indigo-100 dark:border-indigo-800 mb-2">
                    <?= $t['nama_mapel'] ?>
                </span>
                
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1"><?= $t['judul'] ?></h3>
                <p class="text-xs text-gray-400 mb-3 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <?= $t['nama_guru'] ?>
                </p>

                <div class="bg-gray-50 dark:bg-slate-700/30 p-3 rounded-xl mb-3 text-sm text-gray-600 dark:text-slate-300">
                    <?= $t['deskripsi'] ?>
                </div>

                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <div class="flex items-center font-bold <?= (!$sudahKumpul && $isLate) ? 'text-rose-500' : 'text-gray-500' ?>">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Deadline: <?= date('d M Y, H:i', $deadline) ?>
                    </div>
                    
                    <?php if($t['file_pendukung']): ?>
                        <a href="<?= base_url('uploads/tugas/'.$t['file_pendukung']) ?>" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 font-bold">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            Lampiran Soal
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t dark:border-slate-700">
                <?php if($sudahDinilai): ?>
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-100 dark:border-blue-800">
                        <p class="text-xs font-bold text-blue-800 dark:text-blue-300 mb-1">Komentar Guru:</p>
                        <p class="text-sm text-blue-900 dark:text-blue-100 italic">"<?= $t['komentar_guru'] ?? 'Tidak ada catatan.' ?>"</p>
                    </div>

                <?php elseif($sudahKumpul): ?>
                    <div class="flex justify-between items-center bg-emerald-50 dark:bg-emerald-900/20 p-3 rounded-lg border border-emerald-100 dark:border-emerald-800">
                        <div>
                            <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Status: Menunggu Penilaian</p>
                            <p class="text-[10px] text-emerald-600 mt-1">Dikirim: <?= date('d M H:i', strtotime($t['tgl_kumpul'])) ?></p>
                        </div>
                        <button onclick="openModalUpload('<?= $t['id'] ?>')" class="text-xs font-bold text-emerald-600 underline hover:text-emerald-800">
                            Edit Jawaban
                        </button>
                    </div>

                <?php else: ?>
                    <button onclick="openModalUpload('<?= $t['id'] ?>')" class="w-full py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex justify-center items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        UPLOAD JAWABAN
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div id="modalTugas_<?= $t['id'] ?>" class="modal-upload fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900/50">
                    <h3 class="font-bold text-gray-800 dark:text-white line-clamp-1">Kirim Jawaban</h3>
                    <button onclick="closeModalUpload('<?= $t['id'] ?>')" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                
                <form action="<?= base_url('siswa/tugas/upload') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="tugas_id" value="<?= $t['id'] ?>">
                    <input type="hidden" name="siswa_id" value="<?= $siswa->id ?>">

                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg text-xs text-blue-700 dark:text-blue-300">
                        <strong><?= $t['judul'] ?></strong><br>
                        Pastikan file jawaban sudah benar sebelum dikirim.
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">File Jawaban (PDF/Foto)</label>
                        <input type="file" name="file_jawaban" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-500 uppercase">Catatan Tambahan</label>
                        <textarea name="catatan_siswa" rows="2" class="block w-full p-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="Pesan untuk guru..."><?= $t['catatan_siswa'] ?? '' ?></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all">
                        KIRIM SEKARANG
                    </button>
                </form>
            </div>
        </div>

        <?php endforeach; ?>
        
        <?php if(empty($tugas)): ?>
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 mb-4 text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Tidak Ada Tugas</h3>
                <p class="text-gray-500 text-sm">Hore! Belum ada tugas aktif untuk kelasmu.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function openModalUpload(id) {
        document.getElementById('modalTugas_' + id).classList.remove('hidden');
    }
    function closeModalUpload(id) {
        document.getElementById('modalTugas_' + id).classList.add('hidden');
    }
</script>

<?= $this->endSection(); ?>