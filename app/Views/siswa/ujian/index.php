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
        <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm">
            Silakan pilih ujian yang ingin dikerjakan sesuai jadwal.
        </p>
    </div>

    <?php if (empty($ujian)) : ?>
        
        <div class="flex flex-col items-center justify-center p-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 text-center">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-400">
                <i class="fas fa-clipboard-check text-4xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Belum Ada Ujian</h3>
            <p class="text-gray-500 text-sm mt-1">Saat ini belum ada ujian yang dijadwalkan untuk kelas Anda.</p>
        </div>

    <?php else : ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($ujian as $u) : ?>
                
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
                            <?php if ($u['status_ujian'] == '1') : ?>
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            <?php elseif ($u['status_ujian'] == '0') : ?> <i class="fas fa-hourglass-half text-yellow-500 text-xl animate-pulse"></i>
                            <?php else : ?>
                                <i class="fas fa-lock-open text-gray-300 text-xl"></i>
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
                                <span><?= esc($u['jumlah_soal']) ?> Soal</span>
                            </div>
                            
                            <?php if ($u['wajib_lokasi'] == 1): ?>
                            <div class="flex items-center gap-3 text-red-500 font-semibold bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                <i class="fas fa-map-marker-alt w-5 text-center animate-bounce"></i>
                                <span class="text-xs">Wajib di Sekolah</span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                            <?php if ($u['status_ujian'] == '1') : ?>
                                <button disabled class="w-full py-2.5 px-4 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed dark:bg-gray-700 dark:text-gray-500 flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> Selesai Dikerjakan
                                </button>
                            
                            <?php elseif ($u['status_ujian'] == '0') : ?> <a href="<?= base_url('siswa/ujian/konfirmasi/' . $u['id']) ?>" 
                                   class="w-full py-2.5 px-4 bg-yellow-400 text-yellow-900 font-bold rounded-xl hover:bg-yellow-500 hover:shadow-lg hover:shadow-yellow-400/30 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-play"></i> Lanjutkan
                                </a>

                            <?php else : ?>
                                <a href="<?= base_url('siswa/ujian/konfirmasi/' . $u['id']) ?>" 
                                   class="w-full py-2.5 px-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-rocket"></i> Mulai Kerjakan
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>