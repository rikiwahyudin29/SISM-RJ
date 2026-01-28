<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 p-8 text-center">
        
        <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-6 text-blue-600">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.56 1.6-1.223 2.002-2.7 1.636-3.777 4.862-3.777 4.862m0 0c-.261.094-.525.18-.791.257A7.97 7.97 0 0112 14a7.97 7.97 0 00-2.21-.321"></path></svg>
        </div>

        <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Cetak Kartu Pelajar</h2>
        <p class="text-sm text-slate-500 mb-6">Pilih kelas untuk mencetak kartu masal beserta QR Code.</p>

        <form action="<?= base_url('admin/kartu/cetak') ?>" method="get" target="_blank">
            <div class="mb-4">
                <select name="id_kelas" required class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 font-bold text-slate-600 focus:border-blue-500 outline-none">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all">
                Generate Kartu (PDF)
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>