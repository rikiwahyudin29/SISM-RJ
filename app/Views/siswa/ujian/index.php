<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Jadwal Ujian</h1>
            <p class="text-gray-500 text-sm">Daftar ujian yang tersedia untuk Anda.</p>
        </div>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if(empty($ujian)): ?>
        <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow text-center">
            <img src="https://illustrations.popsy.co/gray/surr-list-is-empty.svg" class="h-40 mx-auto mb-4 opacity-50">
            <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">Belum Ada Ujian</h3>
            <p class="text-gray-500 text-sm">Anda tidak memiliki jadwal ujian aktif saat ini.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($ujian as $u): ?>
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow hover:shadow-lg transition-shadow border border-gray-100 dark:border-slate-700 overflow-hidden group">
                    <div class="p-5 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-700 dark:to-slate-800">
                        <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded uppercase tracking-wider"><?= esc($u['nama_mapel']) ?></span>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mt-2 leading-tight group-hover:text-blue-600 transition-colors"><?= esc($u['judul_ujian']) ?></h3>
                        <p class="text-xs text-gray-500 mt-1"><i class="fas fa-chalkboard-teacher mr-1"></i> <?= esc($u['nama_guru']) ?></p>
                    </div>
                    
                    <div class="p-5 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500"><i class="far fa-clock w-5 text-center"></i> Durasi</span>
                            <span class="font-bold text-gray-700 dark:text-gray-300"><?= $u['durasi'] ?> Menit</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500"><i class="far fa-calendar w-5 text-center"></i> Mulai</span>
                            <span class="font-bold text-gray-700 dark:text-gray-300"><?= date('d M H:i', strtotime($u['waktu_mulai'])) ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500"><i class="fas fa-list-ol w-5 text-center"></i> Soal</span>
                            <span class="font-bold text-gray-700 dark:text-gray-300"><?= $u['jumlah_soal'] ?> Butir</span>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-slate-900 border-t border-gray-100 dark:border-slate-700">
                        <?php if ($u['status_ujian'] == 1): ?>
                            <div class="text-center">
                                <span class="block text-xs font-bold text-green-600 uppercase mb-1"><i class="fas fa-check-circle"></i> Selesai Mengerjakan</span>
                                
                                <?php if($u['setting_show_score'] == 1): ?>
                                    <div class="text-3xl font-black text-gray-800 dark:text-white"><?= floatval($u['nilai_saya']) ?></div>
                                    <span class="text-[10px] text-gray-400">Nilai Akhir</span>
                                <?php else: ?>
                                    <div class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-lg text-xs font-bold mt-2">
                                        <i class="fas fa-lock mr-1"></i> Menunggu Hasil
                                    </div>
                                <?php endif; ?>
                            </div>

                       <?php elseif ($u['status_ujian'] == 0): ?>
    <a href="<?= base_url('siswa/ujian/kerjakan/' . ($u['id_sesi'] ?? '')) ?>" 
       class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow transition-transform active:scale-95">
        <i class="fas fa-play mr-2"></i> Lanjutkan
    </a>
<?php else: ?>
                            <?php if ($u['status_waktu'] == 'BELUM_MULAI'): ?>
                                <button disabled class="w-full bg-gray-200 text-gray-500 font-bold py-2.5 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-clock mr-2"></i> Belum Dibuka
                                </button>
                            <?php elseif ($u['status_waktu'] == 'TERLEWAT'): ?>
                                <button disabled class="w-full bg-red-100 text-red-500 font-bold py-2.5 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-times-circle mr-2"></i> Terlewat
                                </button>
                            <?php else: ?>
                                <a href="<?= base_url('siswa/ujian/konfirmasi/' . $u['id_jadwal']) ?>" class="block w-full text-center bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-lg shadow transition-transform active:scale-95">
                                    <i class="fas fa-rocket mr-2"></i> Mulai Ujian
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>