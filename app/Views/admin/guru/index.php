<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="<?= base_url('admin/dashboard') ?>" class="inline-flex items-center text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
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
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Data Guru</h1>
        </div>
        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700">
            <div class="flex items-center mb-4 sm:mb-0">
                <form class="sm:pr-3" action="" method="GET">
                    <label for="products-search" class="sr-only">Search</label>
                    <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                        <input type="text" name="search" id="products-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari NIP atau Nama Guru...">
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-end flex-1 ml-auto space-x-2 sm:space-x-3">
                 <a href="<?= base_url('admin/master/download_template_guru') ?>" class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 sm:w-auto dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Template
                </a>
                <button type="button" data-modal-target="modal-import-guru" data-modal-toggle="modal-import-guru" class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 sm:w-auto dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    Import
                </button>

                <button type="button" data-modal-target="modal-guru-form" data-modal-toggle="modal-guru-form" class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Tambah Guru
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        <span class="font-medium">Sukses!</span> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">Gagal!</span> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Nama & NIP
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Pendidikan & Sertifikasi
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Kontak
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Status
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Aksi
                            </th>
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
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="flex items-center p-4 mr-12 space-x-4 whitespace-nowrap">
                                    <img class="w-10 h-10 rounded-full object-cover" src="<?= base_url('uploads/guru/' . ($g['foto'] ?? 'default.png')) ?>" alt="Avatar">
                                    <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                        <div class="text-base font-semibold text-gray-900 dark:text-white"><?= $g['gelar_depan'] . ' ' . $g['nama_lengkap'] . ' ' . $g['gelar_belakang'] ?></div>
                                        <div class="text-xs font-normal text-gray-500 dark:text-gray-400">NIP: <?= $g['nip'] ?></div>
                                    </div>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium"><?= $g['pendidikan_terakhir'] ?? '-' ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= $g['sertifikasi'] ?? '' ?></div>
                                </td>
                                <td class="p-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <?= $g['no_hp'] ?? '-' ?><br>
                                    <span class="text-xs text-gray-500"><?= $g['email'] ?? '' ?></span>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php 
                                        $color = ($g['status_guru'] == 'PNS' || $g['status_guru'] == 'PPPK') ? 'green' : 'blue';
                                        ?>
                                        <div class="h-2.5 w-2.5 rounded-full bg-<?= $color ?>-400 mr-2"></div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white"><?= $g['status_guru'] ?></span>
                                    </div>
                                </td>
                                <td class="p-4 whitespace-nowrap space-x-2">
                                    <button id="dropdown-btn-<?= $g['id'] ?>" data-dropdown-toggle="dropdown-<?= $g['id'] ?>" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 bg-white rounded-lg hover:bg-gray-100 focus:outline-none dark:text-gray-400 focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600" type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                        </svg>
                                    </button>
                                    <div id="dropdown-<?= $g['id'] ?>" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-btn-<?= $g['id'] ?>">
                                            <li>
                                                <button type="button" data-modal-target="modal-guru-form" data-modal-toggle="modal-guru-form" 
                                                    data-id="<?= $g['id'] ?>"
                                                    data-nip="<?= $g['nip'] ?>"
                                                    data-nama="<?= $g['nama_lengkap'] ?>"
                                                    data-gelar_depan="<?= $g['gelar_depan'] ?>"
                                                    data-gelar_belakang="<?= $g['gelar_belakang'] ?>"
                                                    data-tempat_lahir="<?= $g['tempat_lahir'] ?>"
                                                    data-tanggal_lahir="<?= $g['tanggal_lahir'] ?>"
                                                    data-jenis_kelamin="<?= $g['jenis_kelamin'] ?>"
                                                    data-alamat="<?= $g['alamat'] ?>"
                                                    data-no_hp="<?= $g['no_hp'] ?>"
                                                    data-email="<?= $g['email'] ?>"
                                                    data-pendidikan="<?= $g['pendidikan_terakhir'] ?>"
                                                    data-sertifikasi="<?= $g['sertifikasi'] ?>"
                                                    data-status="<?= $g['status_guru'] ?>"
                                                    data-telegram="<?= $g['telegram_chat_id'] ?>"
                                                    class="btn-edit-guru block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Edit Data
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="py-2">
                                            <a href="<?= base_url('admin/guru/hapus/' . $g['id']) ?>" onclick="return confirm('Yakin hapus data ini? Akun login juga akan terhapus.')" class="block px-4 py-2 text-sm text-red-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-500 dark:hover:text-red-400">
                                                Hapus
                                            </a>
                                        </div>
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
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Menampilkan <span class="font-semibold text-gray-900 dark:text-white"><?= count($guru) ?></span> data</span>
    </div>
    <div class="flex items-center space-x-3">
        <button class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Previous
        </button>
        <button class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Next
        </button>
    </div>
</div>

<?= view('admin/guru/modal_import') ?>
<?= view('admin/guru/modal_form') ?>

<?= $this->endSection() ?>