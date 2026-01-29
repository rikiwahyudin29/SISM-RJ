<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Isi Jurnal Mengajar</h1>
        <p class="text-sm text-slate-500">
            Tahun Ajaran: <span class="font-bold text-blue-600"><?= $ta_aktif->tahun_ajaran ?> (<?= $ta_aktif->semester ?>)</span>
        </p>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="p-4 mb-4 text-sm text-rose-700 bg-rose-100 rounded-xl">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="space-y-4">
        <?php if(empty($jadwal)): ?>
            <div class="p-10 text-center bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-500 font-medium">Tidak ada jadwal mengajar untuk hari ini.</p>
                <p class="text-xs text-slate-400 mt-1">Pastikan jadwal sudah diatur untuk semester ini.</p>
            </div>
        <?php else: ?>
            
            <?php foreach($jadwal as $j): ?>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg">
                        <?= substr($j['nama_kelas'], 0, 2) ?>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white"><?= $j['nama_mapel'] ?></h3>
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <span class="font-semibold text-slate-700 dark:text-slate-300"><?= $j['nama_kelas'] ?></span>
                            <span>•</span>
                            <span><?= substr($j['jam_mulai'],0,5) ?> - <?= substr($j['jam_selesai'],0,5) ?></span>
                        </div>
                    </div>
                </div>

                <div>
                    <?php if($j['sudah_isi']): ?>
                        <button disabled class="px-5 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-sm font-bold flex items-center gap-2 cursor-not-allowed opacity-70">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Sudah Diisi
                        </button>
                    <?php else: ?>
                        <button onclick="bukaModalJurnal(<?= htmlspecialchars(json_encode($j)) ?>)" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition-transform active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Isi Jurnal
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<div id="modalJurnal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0" id="modalContent">
        
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Laporan KBM</h3>
            <button onclick="tutupModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="<?= base_url('guru/jurnal/simpan') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            
            <input type="hidden" name="id_kelas" id="input_id_kelas">
            <input type="hidden" name="id_mapel" id="input_id_mapel">
            
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800 mb-2">
                <p class="text-xs text-blue-500 uppercase font-bold tracking-wider mb-1">Mata Pelajaran</p>
                <p class="font-bold text-slate-800 dark:text-white text-lg" id="label_mapel">-</p>
                <p class="text-sm text-slate-600 dark:text-slate-400" id="label_kelas">-</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Jam Ke-</label>
                    <input type="number" name="jam_ke" required placeholder="1" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Foto Bukti (Opsional)</label>
                    <input type="file" name="foto_kegiatan" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Materi Pembelajaran</label>
                <textarea name="materi" rows="3" required placeholder="Contoh: Pembahasan Bab 3 Trigonometri" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Catatan Tambahan (Opsional)</label>
                <textarea name="keterangan" rows="2" placeholder="Catatan khusus..." class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30 transition-all active:scale-95">
                    Simpan & Lanjut Absen 🚀
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalJurnal');
    const modalContent = document.getElementById('modalContent');

    function bukaModalJurnal(data) {
        // Isi Form dengan Data dari Tombol
        document.getElementById('input_id_kelas').value = data.id_kelas; // <--- INI KUNCINYA
        document.getElementById('input_id_mapel').value = data.id_mapel; // <--- INI KUNCINYA
        
        document.getElementById('label_mapel').textContent = data.nama_mapel;
        document.getElementById('label_kelas').textContent = data.nama_kelas;

        // Tampilkan Modal dengan Animasi
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function tutupModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Tutup jika klik di luar modal
    modal.addEventListener('click', (e) => {
        if (e.target === modal) tutupModal();
    });
</script>

<?= $this->endSection() ?>