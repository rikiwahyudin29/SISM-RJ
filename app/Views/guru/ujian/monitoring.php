<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Monitoring Ujian</h1>
            <p class="text-sm text-gray-500"><?= $jadwal['judul_ujian'] ?> | Token: <span class="font-mono font-bold text-blue-600"><?= $jadwal['token'] ?></span></p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.location.reload()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-bold flex items-center gap-2 transition-all">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
            <a href="<?= base_url('guru/ujian') ?>" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-bold flex items-center gap-2 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-xs text-gray-500 uppercase font-bold">Total Peserta</div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white"><?= $stats['total'] ?></div>
        </div>
        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl shadow-sm border border-green-100">
            <div class="text-xs text-green-600 uppercase font-bold">Selesai</div>
            <div class="text-2xl font-bold text-green-700"><?= $stats['sudah'] ?></div>
        </div>
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl shadow-sm border border-blue-100">
            <div class="text-xs text-blue-600 uppercase font-bold">Sedang Mengerjakan</div>
            <div class="text-2xl font-bold text-blue-700"><?= $stats['sedang'] ?></div>
        </div>
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl shadow-sm border border-gray-200">
            <div class="text-xs text-gray-500 uppercase font-bold">Belum Login</div>
            <div class="text-2xl font-bold text-gray-600 dark:text-gray-300"><?= $stats['belum'] ?></div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nama Siswa</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3">Waktu Mulai</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Nilai</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($siswa)): ?>
                        <tr><td colspan="7" class="px-6 py-8 text-center">Belum ada peserta di kelas ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($siswa as $i => $s): ?>
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4"><?= $i + 1 ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                <?= $s['nama_lengkap'] ?>
                                <div class="text-xs font-normal text-gray-500"><?= $s['nis'] ?></div>
                            </td>
                            <td class="px-6 py-4"><?= $s['nama_kelas'] ?></td>
                            <td class="px-6 py-4">
                                <?= $s['waktu_mulai'] ? date('H:i:s', strtotime($s['waktu_mulai'])) : '-' ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($s['is_blocked'] == 1): ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-0.5 rounded animate-pulse">TERBLOKIR</span>
                                <?php elseif($s['status'] === '1'): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded">SELESAI</span>
                                <?php elseif($s['status'] === '0'): ?>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded">MENGERJAKAN</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">BELUM LOGIN</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                <?= $s['nilai'] !== null ? number_format($s['nilai'], 0) : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($s['status'] !== null): ?>
                                    <button onclick="resetPeserta(<?= $s['id_sesi'] ?>, '<?= $s['nama_lengkap'] ?>')" class="text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-xs px-3 py-1.5 focus:outline-none" title="Reset Login jika siswa error">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Fitur Auto Refresh setiap 30 detik biar guru gak capek
    setInterval(function(){
        window.location.reload();
    }, 30000); 

    function resetPeserta(idSesi, nama) {
        Swal.fire({
            title: 'Reset Peserta?',
            text: "Siswa atas nama " + nama + " akan diizinkan login kembali. Jawaban sebelumnya TETAP AMAN (Tidak dihapus).",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset Status!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('guru/ujian/reset_peserta') ?>', { id_sesi: idSesi }, function(res) {
                    if(res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => window.location.reload());
                    }
                });
            }
        })
    }
</script>

<?= $this->endSection() ?>