<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-0 sm:ml-0">
    <div class="flex justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📚 E-Library Sekolah</h1>
            <p class="text-sm text-gray-500">Baca buku pelajaran dan referensi di mana saja.</p>
        </div>
        
        <?php if(isset($can_upload) && $can_upload == true): ?>
            <button onclick="document.getElementById('modalUpload').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + UPLOAD BUKU
            </button>
        <?php endif; ?>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <?php foreach($buku as $b): ?>
        <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all overflow-hidden border border-gray-100 group">
            <div class="h-48 bg-gray-200 relative overflow-hidden">
                <?php if($b['cover'] != 'default_book.png'): ?>
                    <img src="<?= base_url('uploads/library/covers/'.$b['cover']) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                <?php endif; ?>
                
                <span class="absolute top-2 right-2 bg-black/60 text-white text-[10px] font-bold px-2 py-1 rounded backdrop-blur-sm">
                    <?= $b['kategori'] ?>
                </span>
            </div>
            
            <div class="p-3">
                <h3 class="font-bold text-gray-800 text-sm line-clamp-2 h-10"><?= $b['judul'] ?></h3>
                <p class="text-[10px] text-gray-500 mt-1"><?= $b['penulis'] ?></p>
                
                <div class="mt-3 flex justify-between items-center">
                    <span class="text-[10px] text-gray-400 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <?= $b['diakses'] ?>x
                    </span>
                    
                   <button onclick="bukaBuku('<?= base_url('uploads/library/'.$b['file_ebook']) ?>', <?= $b['id'] ?>, '<?= $b['judul'] ?>')" class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 hover:shadow-lg transition-all shadow-indigo-500/30">
    BACA
</button>
                </div>
                
                <?php if(session()->get('role') == 'admin'): ?>
                <div class="mt-2 border-t pt-2 text-center">
                    <a href="<?= base_url('admin/library/delete/'.$b['id']) ?>" onclick="return confirm('Hapus buku ini?')" class="text-rose-500 text-[10px] font-bold hover:underline">Hapus Buku</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="modalUpload" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6">
        <h3 class="font-bold text-lg mb-4 text-gray-800 uppercase">Upload Buku Baru</h3>
        <form action="<?= base_url('admin/library/upload') ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
            <?= csrf_field(); ?>
            <input type="text" name="judul" class="w-full p-2 border rounded-xl text-sm font-bold" placeholder="Judul Buku" required>
            <input type="text" name="penulis" class="w-full p-2 border rounded-xl text-sm" placeholder="Nama Penulis" required>
            <select name="kategori" class="w-full p-2 border rounded-xl text-sm font-bold">
                <option value="Buku Paket">Buku Paket Pelajaran</option>
                <option value="Modul">Modul / LKS</option>
                <option value="Fiksi">Novel / Fiksi</option>
                <option value="Umum">Pengetahuan Umum</option>
            </select>
            
            <div>
                <label class="text-xs font-bold text-gray-500">File Ebook (PDF Max 10MB)</label>
                <input type="file" name="ebook" class="w-full p-2 border rounded-xl text-sm" accept=".pdf" required>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500">Cover Gambar (Opsional)</label>
                <input type="file" name="cover" class="w-full p-2 border rounded-xl text-sm" accept="image/*">
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 mt-4">UPLOAD KE RAK</button>
            <button type="button" onclick="document.getElementById('modalUpload').classList.add('hidden')" class="w-full py-2 text-gray-500 font-bold mt-2">BATAL</button>
        </form>
    </div>
</div>
<div id="modalBaca" class="fixed inset-0 z-[999] hidden bg-slate-900/95 backdrop-blur-sm flex flex-col transition-all duration-300">
    
    <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-gray-800 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                PDF
            </div>
            <div>
                <h3 id="judulBuku" class="font-bold text-white text-sm tracking-wide">Memuat Buku...</h3>
                <p class="text-[10px] text-gray-400">Mode Membaca Layar Penuh</p>
            </div>
        </div>
        
        <button onclick="tutupBuku()" class="group flex items-center gap-2 px-4 py-2 bg-rose-600/10 hover:bg-rose-600 text-rose-500 hover:text-white rounded-lg transition-all duration-200">
            <span class="text-xs font-bold">TUTUP</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div class="flex-1 w-full h-full bg-gray-800 relative p-4 sm:p-8 overflow-hidden">
        <div class="w-full h-full bg-white rounded-xl shadow-2xl overflow-hidden ring-4 ring-white/10">
            <iframe id="frameBuku" src="" class="w-full h-full border-none"></iframe>
        </div>
    </div>
</div>

<script>
    function bukaBuku(url, id, judul) {
        // 1. Update Judul di Header Modal
        document.getElementById('judulBuku').innerText = judul;
        
        // 2. Load PDF ke Iframe
        // Tambahkan #toolbar=0 agar tampilan lebih bersih
        document.getElementById('frameBuku').src = url + "#toolbar=0"; 
        
        // 3. Tampilkan Modal
        const modal = document.getElementById('modalBaca');
        modal.classList.remove('hidden');
        
        // 4. Hitung View (+1) secara diam-diam (AJAX)
        fetch('<?= base_url('admin/library/counter/') ?>' + id)
            .then(response => console.log('View counted'))
            .catch(error => console.error('Error counting view:', error));

        // 5. Matikan Scroll Body Utama
        document.body.style.overflow = 'hidden';
    }

    function tutupBuku() {
        const modal = document.getElementById('modalBaca');
        
        // Sembunyikan Modal
        modal.classList.add('hidden');
        
        // Kosongkan SRC iframe agar memori lepas (Stop loading)
        document.getElementById('frameBuku').src = "";
        
        // Nyalakan Scroll Body Utama
        document.body.style.overflow = 'auto';
        
        // Opsional: Refresh halaman agar angka view terupdate (Kalau mau)
        // location.reload(); 
    }
</script>
<?= $this->endSection(); ?>