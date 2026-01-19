<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Buat Jadwal Ujian Baru</h2>

    <form action="<?= base_url('guru/ujian/simpan') ?>" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
        
        <div class="mb-6">
            <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Pilih Paket Soal (Bank Soal)</label>
            <select name="id_bank_soal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                <option value="">-- Pilih Bank Soal --</option>
                <?php foreach($bank_soal as $b): ?>
                    <option value="<?= $b['id'] ?>"><?= $b['judul_ujian'] ?> (<?= $b['nama_mapel'] ?>) - <?= $b['jumlah_soal'] ?> Soal</option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1 text-xs text-gray-500">Hanya bank soal yang sudah memiliki butir soal yang muncul disini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Batas Terakhir Masuk</label>
                <input type="datetime-local" name="waktu_selesai" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Durasi (Menit)</label>
                <input type="number" name="durasi" value="60" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Peserta (Pilih Kelas)</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700 max-h-40 overflow-y-auto">
                <?php foreach($kelas as $k): ?>
                <div class="flex items-center">
                    <input id="kelas_<?= $k['id'] ?>" type="checkbox" name="kelas[]" value="<?= $k['id'] ?>" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="kelas_<?= $k['id'] ?>" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"><?= $k['nama_kelas'] ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Token Masuk</label>
                <div class="flex">
                    <input type="text" name="token" id="token_input" class="rounded-none rounded-l-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5 uppercase font-mono font-bold" placeholder="Kosong = Bebas">
                    <button type="button" onclick="acakToken()" class="inline-flex items-center px-3 text-sm text-white bg-gray-600 border border-l-0 border-gray-600 rounded-r-md hover:bg-gray-700">
                        Acak
                    </button>
                </div>
            </div>
            
            <div class="flex flex-col gap-2 justify-center">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="acak_soal" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Acak Urutan Soal</span>
                </label>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="acak_opsi" value="1" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Acak Pilihan Jawaban (A/B/C)</span>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center">
            <i class="fas fa-save mr-2"></i> TERBITKAN JADWAL UJIAN
        </button>

    </form>
</div>

<script>
    function acakToken() {
        const chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        let token = "";
        for (let i = 0; i < 6; i++) {
            token += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('token_input').value = token;
    }
</script>

<?= $this->endSection() ?>