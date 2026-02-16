<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📂 Filebox Administrasi</h1>
            <p class="text-sm text-gray-500 font-medium">Upload dan validasi RPP, Silabus, dan Perangkat Ajar.</p>
        </div>
        
        <button onclick="document.getElementById('modalUpload').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 flex items-center transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            UPLOAD DOKUMEN
        </button>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-4 rounded shadow-sm font-bold">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-rose-100 border-l-4 border-rose-500 text-rose-700 p-4 mb-4 rounded shadow-sm font-bold">
            Gagal: <?= is_array(session()->getFlashdata('error')) ? implode(', ', session()->getFlashdata('error')) : session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <?php if(empty($files)): ?>
            <div class="col-span-1 md:col-span-3 py-16 flex flex-col items-center justify-center text-center border-2 border-dashed border-gray-300 rounded-3xl bg-gray-50/50">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-400 rounded-full flex items-center justify-center mb-6 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700">Belum Ada Dokumen</h3>
                <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">File RPP, Silabus, atau Bahan Ajar yang diupload akan muncul di sini.</p>
                <button onclick="document.getElementById('modalUpload').classList.remove('hidden')" class="px-6 py-3 bg-white border-2 border-indigo-100 text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 hover:border-indigo-200 shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Upload Dokumen Pertama
                </button>
            </div>
        <?php endif; ?>

        <?php foreach($files as $f): ?>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md transition-all relative group">
                
                <div class="absolute top-4 right-4">
                    <?php 
                        $badge = "bg-amber-100 text-amber-600";
                        if($f['status'] == 'Disetujui') $badge = "bg-emerald-100 text-emerald-600";
                        if($f['status'] == 'Revisi') $badge = "bg-rose-100 text-rose-600 animate-pulse border border-rose-200";
                    ?>
                    <span class="px-2 py-1 rounded text-[10px] font-black uppercase <?= $badge ?>">
                        <?= $f['status'] ?>
                    </span>
                </div>

                <div class="flex items-start mb-4 pr-16">
                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-800 dark:text-white text-sm line-clamp-2 leading-tight"><?= $f['judul'] ?></h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1"><?= $f['kategori'] ?> • <?= date('d M Y', strtotime($f['created_at'])) ?></p>
                    </div>
                </div>

                <?php if($role != 'guru'): ?>
                <div class="mb-3 pb-3 border-b border-dashed border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500 uppercase">
                            <?= substr($f['nama_lengkap'], 0, 1) ?>
                        </div>
                        <p class="text-xs text-gray-600 font-bold truncate"><?= $f['nama_lengkap'] ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($f['status'] == 'Revisi'): ?>
                    <div class="bg-rose-50 p-3 rounded-lg mb-4 border border-rose-100">
                        <p class="text-[10px] font-bold text-rose-500 uppercase mb-1 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            PERLU PERBAIKAN:
                        </p>
                        <p class="text-xs text-rose-700 italic font-medium">"<?= $f['catatan_kepsek'] ?>"</p>
                    </div>
                    
                    <button onclick="bukaModalRevisi(<?= $f['id'] ?>, '<?= addslashes($f['judul']) ?>')" class="w-full mb-3 px-3 py-2 bg-rose-600 text-white text-xs font-bold rounded-lg uppercase hover:bg-rose-700 shadow-lg shadow-rose-500/30 flex justify-center items-center gap-2 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        UPLOAD PERBAIKAN
                    </button>
                <?php endif; ?>

               <div class="flex justify-between items-center mt-auto pt-2 border-t border-gray-100 dark:border-slate-700">
    <a href="<?= base_url('admin/filebox/download/'.$f['nama_file']) ?>" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors mt-2">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12"></path></svg>
        DOWNLOAD
    </a>

    <div class="flex gap-1 mt-2">
        <?php if($role != 'guru'): ?>
            <button onclick="bukaModalNilai(<?= $f['id'] ?>, '<?= addslashes($f['judul']) ?>')" class="px-3 py-1.5 bg-gray-800 text-white text-[10px] font-bold rounded-lg uppercase hover:bg-black transition-all shadow-lg shadow-gray-500/30 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                PERIKSA
            </button>
        <?php endif; ?>
        
        <?php if($role != 'guru' || ($role == 'guru' && $f['status'] == 'Pending')): ?>
            <a href="<?= base_url('admin/filebox/hapus/'.$f['id']) ?>" onclick="return confirm('Yakin hapus dokumen ini?')" class="text-gray-300 hover:text-rose-500 p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </a>
        <?php endif; ?>
    </div>
</div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="modalUpload" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 transform transition-all">
        <h3 class="font-bold text-lg mb-4 text-gray-800 uppercase flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Upload Dokumen Baru
        </h3>
        <form action="<?= base_url('admin/filebox/upload') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field(); ?>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Judul Dokumen</label>
                <input type="text" name="judul" class="w-full p-3 border border-gray-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: RPP Matematika Kelas X" required>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Kategori</label>
                <select name="kategori" class="w-full p-3 border border-gray-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500">
                    <option value="RPP">RPP</option>
                    <option value="Silabus">Silabus</option>
                    <option value="Bahan Ajar">Bahan Ajar</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">File (PDF/Word Max 5MB)</label>
                <input type="file" name="berkas" class="w-full p-2 border border-gray-200 rounded-xl text-sm" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('modalUpload').classList.add('hidden')" class="px-4 py-2 text-gray-500 font-bold hover:bg-gray-100 rounded-lg">BATAL</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">UPLOAD SEKARANG</button>
            </div>
        </form>
    </div>
</div>

<div id="modalNilai" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6">
        <h3 class="font-bold text-lg mb-2 text-gray-800 uppercase">Pemeriksaan Dokumen</h3>
        <p id="judulDokumen" class="text-sm text-indigo-600 font-bold mb-4 bg-indigo-50 p-2 rounded border border-indigo-100">Judul Dokumen...</p>
        
        <form action="<?= base_url('admin/filebox/nilai') ?>" method="POST" class="space-y-4">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" id="idDokumen">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Keputusan</label>
                <div class="flex gap-4">
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-emerald-50 w-full transition-colors has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-200">
                        <input type="radio" name="status" value="Disetujui" class="w-4 h-4 text-emerald-600" checked>
                        <span class="ml-2 text-sm font-bold text-emerald-600">SETUJUI</span>
                    </label>
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-rose-50 w-full transition-colors has-[:checked]:bg-rose-50 has-[:checked]:border-rose-200">
                        <input type="radio" name="status" value="Revisi" class="w-4 h-4 text-rose-600">
                        <span class="ml-2 text-sm font-bold text-rose-600">MINTA REVISI</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Catatan (Jika Revisi)</label>
                <textarea name="catatan" rows="3" class="w-full p-3 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500" placeholder="Berikan catatan perbaikan..."></textarea>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('modalNilai').classList.add('hidden')" class="px-4 py-2 text-gray-500 font-bold hover:bg-gray-100 rounded-lg">BATAL</button>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-bold rounded-lg hover:bg-black shadow-lg">SIMPAN KEPUTUSAN</button>
            </div>
        </form>
    </div>
</div>

<div id="modalRevisi" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg text-rose-600 uppercase flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Upload Perbaikan
            </h3>
            <button onclick="document.getElementById('modalRevisi').classList.add('hidden')" class="text-gray-400 hover:text-rose-500">✕</button>
        </div>
        
        <p class="text-xs text-gray-500 font-bold uppercase mb-1">Dokumen yang diperbaiki:</p>
        <p id="judulRevisi" class="text-sm font-bold text-gray-800 mb-6 bg-gray-50 p-3 rounded-lg border border-gray-200">Judul Dokumen...</p>

        <form action="<?= base_url('admin/filebox/revisi') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" id="idRevisi">
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">File Baru (PDF/Word)</label>
                <input type="file" name="berkas" class="w-full p-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-rose-500" accept=".pdf,.doc,.docx" required>
            </div>
            
            <button type="submit" class="w-full py-3 bg-rose-600 text-white font-black rounded-xl hover:bg-rose-700 transition-all uppercase text-xs tracking-widest shadow-lg shadow-rose-600/30">
                KIRIM PERBAIKAN
            </button>
        </form>
    </div>
</div>

<script>
    // Buka Modal Nilai & Set ID + Judul
    function bukaModalNilai(id, judul) {
        document.getElementById('idDokumen').value = id;
        document.getElementById('judulDokumen').innerText = judul;
        document.getElementById('modalNilai').classList.remove('hidden');
    }

    // Buka Modal Revisi & Set ID + Judul
    function bukaModalRevisi(id, judul) {
        document.getElementById('idRevisi').value = id;
        document.getElementById('judulRevisi').innerText = judul;
        document.getElementById('modalRevisi').classList.remove('hidden');
    }
</script>

<?= $this->endSection(); ?>