<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:ml-64">
    <div class="flex items-center justify-between mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Riwayat Hasil Ujian</h1>
            <p class="text-sm text-gray-500">Daftar ujian yang telah Anda selesaikan.</p>
        </div>
        <div class="hidden sm:block">
            <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full">
                Total Selesai: <?= count($riwayat) ?>
            </span>
        </div>
    </div>

    <?php if(empty($riwayat)): ?>
        <div class="flex flex-col items-center justify-center p-12 bg-white dark:bg-slate-800 rounded-xl border border-dashed border-gray-300 dark:border-slate-700 text-center">
            <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded-full mb-4">
                <i class="fas fa-clipboard-list text-4xl text-gray-300 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">Belum Ada Riwayat</h3>
            <p class="text-gray-500 text-sm mt-1">Anda belum menyelesaikan ujian apapun.</p>
            <a href="<?= base_url('siswa/ujian') ?>" class="mt-4 text-blue-600 hover:underline text-sm font-bold">Cek Jadwal Ujian &rarr;</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($riwayat as $r): ?>
                <?php
                    // Logika Tampilan Nilai
                    $nilaiAkhir = $r['nilai'];
                    $tampilNilai = $r['setting_tampil_nilai'];
                    
                    // Cek kelulusan (Misal KKM 75 - Opsional)
                    $kk = 75; 
                    $lulus = ($nilaiAkhir >= $kk);
                    
                    // Format Tanggal
                    $tanggal = date('d M Y', strtotime($r['waktu_submit']));
                    $jam     = date('H:i', strtotime($r['waktu_submit']));
                ?>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden hover:shadow-md transition-all">
                    
                    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500"><?= $r['nama_mapel'] ?></span>
                            <h3 class="font-bold text-lg text-gray-800 dark:text-white leading-tight mt-1"><?= $r['judul_ujian'] ?></h3>
                            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-user-tie mr-1"></i> <?= $r['nama_guru'] ?></p>
                        </div>
                        
                        <?php if($r['jumlah_soal_esai'] > 0): ?>
                            <span class="bg-yellow-50 text-yellow-700 text-[10px] px-2 py-1 rounded border border-yellow-100" title="Termasuk Soal Esai">
                                +Esai
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="p-5 text-center">
                        <?php if($tampilNilai == 1): ?>
                            <div class="relative inline-block">
                                <span class="text-5xl font-extrabold <?= $lulus ? 'text-green-500' : 'text-red-500' ?>">
                                    <?= number_format($nilaiAkhir, 2) ?>
                                </span>
                                <?php if($r['is_blocked']): ?>
                                    <span class="absolute -top-3 -right-6 bg-red-600 text-white text-[10px] px-1.5 py-0.5 rounded">BLOKIR</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Nilai Akhir</p>
                            
                            <div class="flex justify-center gap-4 mt-4 text-xs font-bold">
                                <div class="text-green-600 bg-green-50 px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-check mr-1"></i> <?= $r['jml_benar'] ?> Benar
                                </div>
                                <div class="text-red-600 bg-red-50 px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-times mr-1"></i> <?= $r['jml_salah'] ?> Salah
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="py-4">
                                <i class="fas fa-shield-alt text-4xl text-gray-300 mb-3"></i>
                                <h4 class="font-bold text-gray-600 dark:text-gray-300">Nilai Disembunyikan</h4>
                                <p class="text-xs text-gray-400 mt-1">Hubungi guru untuk info hasil.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="bg-gray-50 dark:bg-slate-700/30 p-3 flex justify-between items-center text-xs text-gray-500 border-t border-gray-100 dark:border-slate-700">
                        <span><i class="far fa-calendar-check mr-1"></i> <?= $tanggal ?></span>
                        <span><i class="far fa-clock mr-1"></i> <?= $jam ?> WIB</span>
                    </div>

                    <?php if($r['is_blocked']): ?>
                        <div class="bg-red-100 text-red-800 text-xs p-2 text-center font-bold">
                            <i class="fas fa-ban mr-1"></i> Diskualifikasi: <?= $r['alasan_blokir'] ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>