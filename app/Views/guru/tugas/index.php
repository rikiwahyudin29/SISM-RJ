<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📝 Tugas & PR</h1>
            <p class="text-sm text-gray-500">Kelola tugas harian dan pantau pengumpulan siswa.</p>
        </div>
        <button onclick="openModal('modalTambah')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 transition-all flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            BUAT TUGAS BARU
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($tugas as $t): ?>
            <?php 
                $deadline = strtotime($t['deadline']);
                $now = time();
                $isActive = $now <= $deadline;
            ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden group relative hover:shadow-md transition-all flex flex-col h-full">
            
            <div class="p-5 flex-1">
                <div class="flex justify-between items-start mb-3 gap-2">
                    
                    <span class="inline-block max-w-[65%] truncate px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase rounded border border-indigo-100 dark:border-indigo-800" title="<?= $t['nama_mapel'] ?>">
                        <?= $t['nama_mapel'] ?>
                    </span>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <?php if($isActive): ?>
                            <span class="text-[10px] font-bold text-emerald-500 flex items-center bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1 animate-pulse"></div> Aktif
                            </span>
                        <?php else: ?>
                            <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100">Tutup</span>
                        <?php endif; ?>

                        <a href="<?= base_url('guru/tugas/delete/'.$t['id']) ?>" onclick="return confirm('Hapus tugas ini? Semua jawaban siswa juga akan terhapus!')" class="text-gray-400 hover:text-rose-500 transition-colors p-1" title="Hapus Tugas">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </a>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1 line-clamp-2 leading-tight" title="<?= $t['judul'] ?>"><?= $t['judul'] ?></h3>
                
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-4">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <?= $t['nama_kelas'] ?>
                </div>

                <div class="bg-gray-50 dark:bg-slate-700/50 p-3 rounded-xl mb-4 h-16 overflow-hidden">
                    <p class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2 italic">"<?= $t['deskripsi'] ?>"</p>
                </div>

                <div class="border-t dark:border-slate-700 pt-3 flex justify-between items-center">
                    <div class="text-xs">
                        <p class="text-gray-400 mb-0.5">Deadline</p>
                        <p class="font-bold text-gray-700 dark:text-gray-300 flex items-center">
                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <?= date('d M, H:i', $deadline) ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-400 text-xs mb-0.5">Terkumpul</p>
                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-400"><?= $t['total_kumpul'] ?></span> <span class="text-xs text-gray-400">Siswa</span>
                    </div>
                </div>
            </div>

            <a href="<?= base_url('guru/tugas/hasil/'.$t['id']) ?>" class="block w-full py-3 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-bold text-xs hover:bg-indigo-600 hover:text-white transition-all text-center border-t border-indigo-100 dark:border-indigo-900">
                <i class="fas fa-check-circle mr-1"></i> PERIKSA JAWABAN
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center bg-indigo-50 dark:bg-indigo-900/20">
            <h3 class="font-bold text-indigo-800 dark:text-white">Buat Tugas Baru</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= base_url('guru/tugas/save') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
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
                <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Judul Tugas</label>
                <input type="text" name="judul" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white" placeholder="Contoh: Latihan Soal Bab 1">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Instruksi / Soal</label>
                <textarea name="deskripsi" rows="3" class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white" placeholder="Kerjakan halaman..."></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Batas Waktu (Deadline)</label>
                <input type="datetime-local" name="deadline" required class="w-full p-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white">
            </div>

            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/10 rounded-lg border border-indigo-100 dark:border-indigo-900/20">
                <label class="text-[10px] text-indigo-700 dark:text-indigo-400 font-bold block mb-1">File Pendukung (Opsional)</label>
                <input type="file" name="file_pendukung" class="text-xs text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-indigo-100 file:text-indigo-700">
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition-all">TERBITKAN TUGAS</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
</script>

<?= $this->endSection(); ?>