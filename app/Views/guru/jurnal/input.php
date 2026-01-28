<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('guru/jurnal') ?>" class="p-2 bg-slate-100 rounded-lg hover:bg-slate-200">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Isi Jurnal Mengajar</h1>
            <p class="text-sm text-slate-500">Silakan pilih jadwal mengajar Anda hari ini.</p>
        </div>
    </div>

    <form action="<?= base_url('guru/jurnal/simpan') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            
            <input type="hidden" name="tanggal" value="<?= date('Y-m-d') ?>">

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pilih Jadwal Mengajar Hari Ini</label>
                <?php if(empty($jadwal)): ?>
                    <div class="p-4 bg-yellow-50 text-yellow-700 rounded-xl text-sm border border-yellow-200">
                        Tidak ada jadwal mengajar untuk Anda hari ini.
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach($jadwal as $j): ?>
                        <label class="relative flex items-center p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-all">
                            <input type="radio" name="pilih_jadwal" value="<?= $j['id'] ?>" class="peer w-5 h-5 text-blue-600" required onchange="setJadwalValues('<?= $j['id_kelas'] ?>', '<?= $j['id_mapel'] ?>', '<?= $j['jam_mulai'] ?>-<?= $j['jam_selesai'] ?>')">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-slate-800"><?= $j['nama_kelas'] ?> - <?= $j['nama_mapel'] ?></span>
                                <span class="block text-xs text-slate-500 font-mono">Jam: <?= substr($j['jam_mulai'],0,5) ?> - <?= substr($j['jam_selesai'],0,5) ?></span>
                            </div>
                            <div class="absolute right-4 hidden peer-checked:block text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <input type="hidden" name="id_kelas" id="id_kelas">
            <input type="hidden" name="id_mapel" id="id_mapel">
            <input type="hidden" name="jam_ke" id="jam_ke">

            <div class="pt-4 border-t border-dashed border-slate-200">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Materi / Topik Bahasan</label>
                <textarea name="materi" rows="3" class="w-full px-4 py-3 rounded-xl border bg-slate-50 dark:bg-slate-900 font-bold outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cth: Aljabar Linear..." required></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Bukti Foto (Wajib)</label>
                <input type="file" name="foto" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan (Opsional)</label>
                <input type="text" name="keterangan" class="w-full px-4 py-3 rounded-xl border bg-slate-50 dark:bg-slate-900 font-bold outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cth: Siswa izin 2 orang...">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all mt-4">
                Simpan Laporan
            </button>
        </div>
    </form>
</div>

<script>
    // Script untuk mengisi hidden input otomatis saat radio button jadwal dipilih
    function setJadwalValues(kelas, mapel, jam) {
        document.getElementById('id_kelas').value = kelas;
        document.getElementById('id_mapel').value = mapel;
        document.getElementById('jam_ke').value = jam;
    }
</script>

<?= $this->endSection() ?>