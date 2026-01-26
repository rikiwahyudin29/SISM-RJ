<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-white flex items-center gap-3">
            <span class="p-3 bg-blue-600 rounded-xl text-white shadow-lg shadow-blue-600/30">
                <i class="fas fa-laptop-code"></i>
            </span>
            Daftar Ujian Tersedia
        </h1>
    </div>

    <?php if (empty($ujian)) : ?>
        <div class="flex flex-col items-center justify-center p-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 text-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Belum Ada Ujian</h3>
        </div>
    <?php else : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($ujian as $u) : 
                // NORMALISASI STATUS: 1=SELESAI, 0=MENGERJAKAN
                $status = (int)$u['status_ujian']; 
            ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col overflow-hidden group">
                    <div class="h-2 bg-gradient-to-r from-blue-500 to-cyan-400 group-hover:h-3 transition-all duration-300"></div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 uppercase tracking-wide">
                                    <?= esc($u['nama_mapel']) ?>
                                </span>
                                <h3 class="mt-2 text-lg font-bold text-gray-900 dark:text-white leading-tight group-hover:text-blue-600 transition-colors">
                                    <?= esc($u['judul_ujian']) ?>
                                </h3>
                            </div>
                            <?php if ($status === 1) : ?>
                                <i class="fas fa-check-circle text-green-500 text-xl" title="Selesai"></i>
                            <?php elseif ($status === 0) : ?> 
                                <i class="fas fa-hourglass-half text-yellow-500 text-xl animate-pulse" title="Sedang Mengerjakan"></i>
                            <?php else : ?>
                                <i class="fas fa-lock-open text-gray-300 text-xl" title="Belum Dikerjakan"></i>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400 mb-6 flex-1">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user-tie w-5 text-center text-gray-400"></i>
                                <span><?= esc($u['nama_guru']) ?></span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="far fa-clock w-5 text-center text-gray-400"></i>
                                <span><?= esc($u['durasi']) ?> Menit</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="far fa-file-alt w-5 text-center text-gray-400"></i>
                                <span><?= esc($u['jumlah_soal'] ?? 0) ?> Soal</span>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                            <?php if ($status === 1) : ?>
                                <button disabled class="w-full py-2.5 px-4 bg-green-100 text-green-600 font-bold rounded-xl cursor-not-allowed dark:bg-green-900/30 dark:text-green-400 flex items-center justify-center gap-2">
                                    <i class="fas fa-check-double"></i> Sudah Mengerjakan
                                </button>
                            
                            <?php elseif ($status === 0) : ?> 
                                <form action="<?= base_url('siswa/ujian/mulai') ?>" method="post">
                                    <input type="hidden" name="id_jadwal" value="<?= $u['id_jadwal'] ?>">
                                    <button type="submit" class="w-full py-2.5 px-4 bg-yellow-400 text-yellow-900 font-bold rounded-xl hover:bg-yellow-500 hover:shadow-lg hover:shadow-yellow-400/30 transition-all flex items-center justify-center gap-2">
                                        <i class="fas fa-history"></i> Lanjutkan
                                    </button>
                                </form>

                            <?php else : ?>
                                <button onclick="bukaModalToken('<?= $u['id_jadwal'] ?>', '<?= $u['token'] ? '1' : '0' ?>')" 
                                   class="w-full py-2.5 px-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-rocket"></i> Mulai Kerjakan
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div id="modal-token" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md shadow-2xl transform scale-95 transition-transform">
        <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Mulai Ujian</h3>
        <form action="<?= base_url('siswa/ujian/mulai') ?>" method="post">
            <input type="hidden" name="id_jadwal" id="input_id_jadwal">
            <div id="area-token" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Masukkan Token</label>
                <input type="text" name="token" class="w-full p-3 border rounded-xl bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="TOKEN UJIAN">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-token').classList.add('hidden')" class="px-4 py-2 text-gray-500 hover:text-gray-700">Batal</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">Mulai</button>
            </div>
        </form>
    </div>
</div>
<script>
function bukaModalToken(idJadwal, butuhToken) {
    document.getElementById('input_id_jadwal').value = idJadwal;
    const areaToken = document.getElementById('area-token');
    if(butuhToken === '1') { areaToken.classList.remove('hidden'); areaToken.querySelector('input').required = true; } 
    else { areaToken.classList.add('hidden'); areaToken.querySelector('input').required = false; }
    document.getElementById('modal-token').classList.remove('hidden');
}
</script>
<?= $this->endSection() ?>