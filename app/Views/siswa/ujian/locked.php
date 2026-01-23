<?= $this->extend('layout/template_ujian') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-center h-screen bg-gray-100 dark:bg-slate-900">
    <div class="text-center bg-white dark:bg-slate-800 p-10 rounded-2xl shadow-xl max-w-md w-full border border-red-200 dark:border-red-900">
        <div class="text-6xl text-red-500 mb-6">
            <i class="fas fa-lock"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">UJIAN TERKUNCI!</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-6">
            Anda terdeteksi meninggalkan halaman ujian melebihi batas waktu toleransi.
        </p>
        
        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-100 dark:border-red-800 text-sm text-red-800 dark:text-red-300 mb-6">
            Silakan hubungi <b>Pengawas / Proktor</b> untuk membuka kunci akun Anda agar bisa melanjutkan ujian.
        </div>

        <a href="<?= current_url() ?>" class="inline-block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition-transform active:scale-95">
            <i class="fas fa-sync-alt mr-2"></i> COBA REFRESH (JIKA SUDAH DIBUKA)
        </a>
        
        <a href="<?= base_url('siswa/ujian') ?>" class="block mt-4 text-sm text-gray-400 hover:underline">Kembali ke Dashboard</a>
    </div>
</div>

<?= $this->endSection() ?>