<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
            <?php 
            $foto = 'https://ui-avatars.com/api/?name='.urlencode($siswa['nama_lengkap']).'&background=random&size=128';
            if(!empty($siswa['foto']) && $siswa['foto'] != 'default.png') {
                $foto = base_url('uploads/ppdb/'.$siswa['foto']);
            }
            ?>
            <img class="w-32 h-32 rounded-full mx-auto object-cover mb-4 ring-4 ring-blue-100 dark:ring-blue-900 shadow-md" src="<?= $foto ?>" alt="Foto Siswa">
            
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1"><?= $siswa['nama_lengkap'] ?></h2>
            <p class="text-sm text-gray-500 font-mono"><?= $siswa['no_pendaftaran'] ?></p>
            
            <div class="mt-4">
                <span class="px-4 py-1.5 rounded-full text-sm font-bold border 
                    <?php 
                    if($siswa['status_pendaftaran'] == 'Diterima') echo 'bg-green-100 text-green-700 border-green-200';
                    elseif($siswa['status_pendaftaran'] == 'Ditolak') echo 'bg-red-100 text-red-700 border-red-200';
                    else echo 'bg-amber-100 text-amber-700 border-amber-200';
                    ?>">
                    <?= strtoupper($siswa['status_pendaftaran']) ?>
                </span>
            </div>

            <div class="mt-6 flex flex-col gap-3">
                <a href="<?= base_url('admin/spmb/cetak_formulir/'.$siswa['id']) ?>" target="_blank" class="flex items-center justify-center gap-2 w-full text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 transition-all dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Formulir
                </a>
                
                <?php if($siswa['status_pendaftaran'] == 'Diterima' && $siswa['is_migrated'] == 0): ?>
                    <button data-modal-target="modal-migrasi" data-modal-toggle="modal-migrasi" class="flex items-center justify-center gap-2 w-full text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-2.5 shadow-lg shadow-blue-500/30 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Terima & Masukkan Kelas
                    </button>
                <?php elseif($siswa['is_migrated'] == 1): ?>
                    <div class="flex items-center justify-center gap-2 w-full bg-green-50 text-green-700 border border-green-200 rounded-lg p-2.5 text-sm font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Siswa Aktif
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider border-b pb-2">Berkas Lampiran</h3>
            <div class="space-y-3">
                <?php if(!empty($siswa['berkas_kk'])): ?>
                <a href="<?= base_url('uploads/ppdb/'.$siswa['berkas_kk']) ?>" target="_blank" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-blue-50 border border-gray-200 transition group">
                    <div class="p-2 bg-white rounded-md border border-gray-200 group-hover:border-blue-200">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Kartu Keluarga</p>
                        <p class="text-xs text-blue-600">Klik untuk lihat</p>
                    </div>
                </a>
                <?php endif; ?>

                <?php if(!empty($siswa['berkas_ijazah'])): ?>
                <a href="<?= base_url('uploads/ppdb/'.$siswa['berkas_ijazah']) ?>" target="_blank" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-blue-50 border border-gray-200 transition group">
                    <div class="p-2 bg-white rounded-md border border-gray-200 group-hover:border-blue-200">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Ijazah / SKL</p>
                        <p class="text-xs text-blue-600">Klik untuk lihat</p>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Biodata Lengkap
                </h3>
                <span class="text-xs font-mono text-gray-400">Terdaftar: <?= date('d M Y', strtotime($siswa['tgl_daftar'])) ?></span>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div class="md:col-span-2 pb-2 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="text-xs font-bold text-blue-600 uppercase tracking-widest">A. Data Pribadi</h4>
                </div>
                
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">NISN</label>
                    <p class="font-bold text-gray-900 dark:text-white"><?= $siswa['nisn'] ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">NIK</label>
                    <p class="font-bold text-gray-900 dark:text-white"><?= $siswa['nik'] ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Tempat, Tanggal Lahir</label>
                    <p class="font-bold text-gray-900 dark:text-white"><?= $siswa['tempat_lahir'] ?>, <?= date('d F Y', strtotime($siswa['tgl_lahir'])) ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Jenis Kelamin</label>
                    <p class="font-bold text-gray-900 dark:text-white"><?= $siswa['jk'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Agama</label>
                    <p class="font-bold text-gray-900 dark:text-white"><?= $siswa['agama'] ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">No. HP Siswa</label>
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-gray-900 dark:text-white"><?= $siswa['no_hp_siswa'] ?></p>
                        <a href="https://wa.me/<?= '62'.ltrim($siswa['no_hp_siswa'], '0') ?>" target="_blank" class="text-green-500 hover:text-green-600"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="md:col-span-2 mt-2 pb-2 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="text-xs font-bold text-orange-600 uppercase tracking-widest">B. Alamat Domisili</h4>
                </div>
                <div class="md:col-span-2">
                    <p class="font-bold text-gray-900 dark:text-white leading-relaxed">
                        <?= $siswa['alamat_jalan'] ?><br>
                        <span class="text-sm font-normal text-gray-600 dark:text-gray-300">
                            RT/RW <?= $siswa['rt_rw'] ?>, Kel. <?= $siswa['desa_kelurahan'] ?>, Kec. <?= $siswa['kecamatan'] ?><br>
                            <?= $siswa['kabupaten'] ?> - <?= $siswa['provinsi'] ?> (<?= $siswa['kode_pos'] ?>)
                        </span>
                    </p>
                </div>

                <div class="md:col-span-2 mt-2 pb-2 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="text-xs font-bold text-purple-600 uppercase tracking-widest">C. Data Orang Tua / Wali</h4>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                    <span class="text-xs font-bold text-gray-400 block mb-2">AYAH KANDUNG</span>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= $siswa['nama_ayah'] ?></p>
                    <p class="text-xs text-gray-500 mb-1"><?= $siswa['pekerjaan_ayah'] ?></p>
                    <p class="text-xs text-blue-600"><i class="fas fa-phone mr-1"></i> <?= $siswa['no_hp_ayah'] ?></p>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                    <span class="text-xs font-bold text-gray-400 block mb-2">IBU KANDUNG</span>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= $siswa['nama_ibu'] ?></p>
                    <p class="text-xs text-gray-500 mb-1"><?= $siswa['pekerjaan_ibu'] ?></p>
                    <p class="text-xs text-blue-600"><i class="fas fa-phone mr-1"></i> <?= $siswa['no_hp_ibu'] ?></p>
                </div>

                <?php if(!empty($siswa['nama_wali'])): ?>
                <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg md:col-span-2">
                    <span class="text-xs font-bold text-gray-400 block mb-2">WALI MURID</span>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= $siswa['nama_wali'] ?></p>
                            <p class="text-xs text-gray-500"><?= $siswa['pekerjaan_wali'] ?></p>
                        </div>
                        <p class="text-xs text-blue-600"><i class="fas fa-phone mr-1"></i> <?= $siswa['no_hp_wali'] ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="md:col-span-2 mt-2 pb-2 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-widest">D. Sekolah & Peminatan</h4>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Asal Sekolah</label>
                    <p class="font-bold text-gray-900 dark:text-white"><?= $siswa['asal_sekolah'] ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Jurusan Pilihan</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                        <?= $siswa['jurusan_minat'] ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Verifikasi & Keputusan Admin</h3>
            <form action="<?= base_url('admin/spmb/update_status') ?>" method="post" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $siswa['id'] ?>">
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Kelulusan</label>
                    <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="Pending" <?= $siswa['status_pendaftaran']=='Pending'?'selected':'' ?>>Pending</option>
                        <option value="Diterima" <?= $siswa['status_pendaftaran']=='Diterima'?'selected':'' ?>>Diterima</option>
                        <option value="Cadangan" <?= $siswa['status_pendaftaran']=='Cadangan'?'selected':'' ?>>Cadangan</option>
                        <option value="Ditolak" <?= $siswa['status_pendaftaran']=='Ditolak'?'selected':'' ?>>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan (Opsional)</label>
                    <input type="text" name="catatan" value="<?= $siswa['catatan_admin'] ?>" placeholder="Catatan untuk siswa..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md transition-all">
                    Simpan Keputusan
                </button>
            </form>
        </div>
    </div>
</div>

<div id="modal-migrasi" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/50 backdrop-blur-sm">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-700 border border-gray-200">
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    Pindahkan ke Kelas
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-migrasi">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-6 bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                    Siswa ini akan resmi masuk ke <b>Database Siswa Aktif</b> dan ditempatkan di kelas yang Anda pilih.
                </p>
                
                <form class="space-y-4" action="<?= base_url('admin/spmb/migrasi_siswa') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_pendaftar" value="<?= $siswa['id'] ?>">
                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tetapkan NIS Baru</label>
                        <input type="text" name="nis_baru" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold" required placeholder="Contoh: 2024001">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Kelas (Rombel)</label>
                        <select name="id_rombel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach($rombel_list as $r): ?>
                                <?php $isMatch = (stripos($r['nama_kelas'], $siswa['jurusan_minat']) !== false); ?>
                                <option value="<?= $r['id'] ?>" <?= $isMatch ? 'class="font-bold text-blue-700 bg-blue-50"' : '' ?>>
                                    <?= $r['nama_kelas'] ?> <?= $isMatch ? '★ (Rekomendasi)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3 text-center shadow-lg transition-all transform hover:-translate-y-0.5 mt-4">
                        Konfirmasi & Pindahkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>