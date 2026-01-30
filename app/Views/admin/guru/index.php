<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="<?= base_url('admin/dashboard') ?>" class="inline-flex items-center text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2">Personel</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2">Data Guru</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl dark:text-white">Data Guru</h1>
        </div>
        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700">
            <div class="flex items-center mb-4 sm:mb-0">
                <form class="sm:pr-3" action="" method="GET">
                    <label for="products-search" class="sr-only">Search</label>
                    <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                        <input type="text" name="search" id="products-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari NIP, NIK atau Nama...">
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-end flex-1 ml-auto space-x-2 sm:space-x-3">
                 <a href="<?= base_url('admin/master/download_template_guru') ?>" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Template
                </a>
                <button type="button" data-modal-target="modal-import-guru" data-modal-toggle="modal-import-guru" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 shadow-sm transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    Import
                </button>

                <button type="button" data-modal-target="modal-guru-form" data-modal-toggle="modal-guru-form" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 shadow-sm transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Tambah Guru
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200" role="alert">
        <span class="font-bold"><i class="fas fa-check-circle mr-1"></i> Sukses!</span> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200" role="alert">
        <span class="font-bold"><i class="fas fa-times-circle mr-1"></i> Gagal!</span> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow bg-white rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-bold text-left text-gray-500 uppercase dark:text-gray-400">Identitas</th>
                            <th scope="col" class="p-4 text-xs font-bold text-left text-gray-500 uppercase dark:text-gray-400">Data Pribadi</th>
                            <th scope="col" class="p-4 text-xs font-bold text-left text-gray-500 uppercase dark:text-gray-400">Kontak & Akun</th>
                            <th scope="col" class="p-4 text-xs font-bold text-left text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th scope="col" class="p-4 text-xs font-bold text-center text-gray-500 uppercase dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php if (empty($guru)) : ?>
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400 py-8">
                                    Data guru belum tersedia.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($guru as $g) : ?>
                            <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="flex items-start p-4 space-x-4">
                                    <img class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" src="<?= base_url('uploads/guru/' . ($g['foto'] ?? 'default.png')) ?>" alt="Avatar">
                                    <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                        <div class="text-base font-bold text-gray-900 dark:text-white">
                                            <?= $g['gelar_depan'] . ' ' . $g['nama_lengkap'] . ' ' . $g['gelar_belakang'] ?>
                                        </div>
                                        <div class="text-xs text-blue-600 font-mono mt-1">NIP: <?= !empty($g['nip']) ? $g['nip'] : '-' ?></div>
                                        <div class="text-xs text-gray-500 font-mono">NIK: <?= !empty($g['nik']) ? $g['nik'] : '-' ?></div>
                                        <?php if(!empty($g['nuptk'])): ?>
                                            <div class="text-xs text-purple-500 font-mono">NUPTK: <?= $g['nuptk'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="p-4 align-top whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <?= $g['tempat_lahir'] ?>, <?= date('d-m-Y', strtotime($g['tgl_lahir'] ?? $g['tanggal_lahir'])) ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Ibu: <?= $g['ibu_kandung'] ?? '-' ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Gender: <?= ($g['jk'] == 'L' || $g['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan' ?>
                                    </div>
                                </td>

                                <td class="p-4 align-top text-sm text-gray-900 dark:text-white">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs flex items-center gap-1">
                                            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                                            <?= $g['no_hp'] ?? '-' ?>
                                        </span>
                                        <span class="text-xs flex items-center gap-1">
                                            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                                            <?= $g['email'] ?? '-' ?>
                                        </span>
                                        <?php if(!empty($g['telegram_chat_id'])): ?>
                                        <span class="text-xs flex items-center gap-1 font-mono text-blue-600 bg-blue-50 px-1 rounded w-fit">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-3.417 17.583l.833-4.333 8.333-7.5-6.583 3.917-3.5-1.083 8.5-3.333-1.667 9.5-.75-.583-.5.916.334 2.5z"/></svg>
                                            <?= $g['telegram_chat_id'] ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="p-4 align-top whitespace-nowrap">
                                    <?php 
                                    $status = $g['status_kepegawaian'] ?? $g['status_guru'] ?? 'Honorer';
                                    $bg_color = 'bg-gray-100 text-gray-800';
                                    if(stripos($status, 'PNS') !== false) $bg_color = 'bg-green-100 text-green-800';
                                    if(stripos($status, 'GTY') !== false) $bg_color = 'bg-blue-100 text-blue-800';
                                    if(stripos($status, 'Honor') !== false) $bg_color = 'bg-yellow-100 text-yellow-800';
                                    ?>
                                    <span class="<?= $bg_color ?> text-xs font-bold px-2.5 py-0.5 rounded border border-gray-200 shadow-sm">
                                        <?= $status ?>
                                    </span>
                                    
                                    <?php if(!empty($g['rfid_uid'])): ?>
                                        <div class="mt-2">
                                            <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-0.5 rounded border border-purple-200">
                                                RFID ON
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="p-4 align-top whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" 
                                            data-modal-target="modal-guru-form" 
                                            data-modal-toggle="modal-guru-form" 
                                            /* ATRIBUT DATA LENGKAP */
                                            data-id="<?= $g['id'] ?>"
                                            data-nik="<?= $g['nik'] ?>"
                                            data-nip="<?= $g['nip'] ?>"  
                                            data-nuptk="<?= $g['nuptk'] ?>"
                                            data-nama="<?= $g['nama_lengkap'] ?>"
                                            data-gelar-depan="<?= $g['gelar_depan'] ?>"
                                            data-gelar-belakang="<?= $g['gelar_belakang'] ?>"
                                            data-tempat-lahir="<?= $g['tempat_lahir'] ?>"
                                            data-tgl-lahir="<?= $g['tgl_lahir'] ?? $g['tanggal_lahir'] ?>"
                                            data-jk="<?= $g['jk'] ?? $g['jenis_kelamin'] ?>"
                                            data-ibu="<?= $g['ibu_kandung'] ?>"
                                            data-alamat="<?= $g['alamat'] ?>"
                                            data-hp="<?= $g['no_hp'] ?>"
                                            data-email="<?= $g['email'] ?>"
                                            data-telegram="<?= $g['telegram_chat_id'] ?? '' ?>"  
                                            data-pendidikan="<?= $g['pendidikan_terakhir'] ?>"
                                            data-status="<?= $g['status_kepegawaian'] ?? $g['status_guru'] ?>"
                                            data-rfid="<?= $g['rfid_uid'] ?>"
                                            class="btn-edit-guru flex items-center justify-center w-8 h-8 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-transform transform hover:scale-110 focus:ring-2 focus:ring-blue-400"
                                            title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        <a href="<?= base_url('admin/guru/hapus/' . $g['id']) ?>" 
                                           onclick="return confirm('Yakin hapus data ini? Akun login juga akan terhapus.')" 
                                           class="flex items-center justify-center w-8 h-8 text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-md transition-transform transform hover:scale-110 focus:ring-2 focus:ring-red-400"
                                           title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center mb-4 sm:mb-0">
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Total Data: <span class="font-semibold text-gray-900 dark:text-white"><?= count($guru) ?></span> Guru</span>
    </div>
</div>

<?= view('admin/guru/modal_import') ?>
<?= view('admin/guru/modal_form') ?>

<?= $this->endSection() ?>