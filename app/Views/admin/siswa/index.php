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
                    <li><div class="flex items-center"><svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg><span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2">Kesiswaan</span></div></li>
                    <li><div class="flex items-center"><svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg><span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2">Data Siswa</span></div></li>
                </ol>
            </nav>
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Data Siswa</h1>
        </div>
        
        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700">
            <div class="flex items-center mb-4 sm:mb-0">
                <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="text" id="siswa-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Ketik nama, NISN, atau kelas...">
                </div>
            </div>
            
            <div class="flex items-center justify-end flex-1 ml-auto space-x-2 sm:space-x-3">
                <a href="<?= base_url('admin/master/download_template_siswa') ?>" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Template
                </a>

                <button type="button" data-modal-target="modal-import-siswa" data-modal-toggle="modal-import-siswa" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    Import Excel
                </button>

                <button type="button" data-modal-target="modal-siswa-form" data-modal-toggle="modal-siswa-form" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Tambah Siswa
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table id="tabel-siswa" class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Nama & NISN</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Kelas & Jurusan</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Kontak Siswa</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php if (empty($siswa)) : ?>
                            <tr><td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400 py-8">Data siswa belum tersedia.</td></tr>
                        <?php else : ?>
                            <?php foreach ($siswa as $s) : ?>
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <td class="flex items-center p-4 mr-12 space-x-4 whitespace-nowrap">
                                    <?php 
                                        // LOGIC FOTO: Jika kosong/default, pakai UI Avatar. Jika ada, pakai file upload.
                                        $fotoDB = $s['foto'] ?? 'default.png';
                                        if ($fotoDB == 'default.png' || empty($fotoDB)) {
                                            $fotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($s['nama_lengkap']) . '&background=random&color=fff&size=128&bold=true';
                                        } else {
                                            $fotoUrl = base_url('uploads/siswa/' . $fotoDB);
                                        }
                                    ?>
                                    <img class="w-10 h-10 rounded-full object-cover shadow-sm" src="<?= $fotoUrl ?>" alt="Foto">
                                    <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                        <div class="text-base font-semibold text-gray-900 dark:text-white"><?= $s['nama_lengkap'] ?></div>
                                        <div class="text-xs font-normal text-gray-500 dark:text-gray-400">NISN: <?= $s['nisn'] ?></div>
                                    </div>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white"><?= $s['nama_kelas'] ?? '-' ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= $s['nama_jurusan'] ?? '-' ?></div>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white"><?= $s['no_hp_siswa'] ?? '-' ?></div>
                                    <?php if($s['telegram_chat_id']): ?>
                                        <span class="bg-blue-100 text-blue-800 text-[10px] font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Telegram ON</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <?php $statusColor = ($s['status_siswa'] == 'Aktif') ? 'green' : 'red'; ?>
                                    <span class="bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800 text-xs font-medium px-2.5 py-0.5 rounded border border-<?= $statusColor ?>-400">
                                        <?= $s['status_siswa'] ?>
                                    </span>
                                </td>
                                <td class="p-4 whitespace-nowrap space-x-2">
                                    <button id="dropdown-btn-<?= $s['id'] ?>" data-dropdown-toggle="dropdown-<?= $s['id'] ?>" class="p-2 text-sm font-medium text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 3"><path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/></svg>
                                    </button>
                                    <div id="dropdown-<?= $s['id'] ?>" class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                            <li>
                                                <button type="button" data-modal-target="modal-siswa-form" data-modal-toggle="modal-siswa-form"
                                                    data-id="<?= $s['id'] ?>"
                                                    data-nisn="<?= $s['nisn'] ?>"
                                                    data-nis="<?= $s['nis'] ?>"
                                                    data-nama="<?= $s['nama_lengkap'] ?>"
                                                    data-kelas="<?= $s['kelas_id'] ?>"
                                                    data-jurusan="<?= $s['jurusan_id'] ?>"
                                                    data-jk="<?= $s['jenis_kelamin'] ?>"
                                                    data-tempat="<?= $s['tempat_lahir'] ?>"
                                                    data-tgl="<?= $s['tanggal_lahir'] ?>"
                                                    data-agama="<?= $s['agama'] ?>"
                                                    data-alamat="<?= $s['alamat'] ?>"
                                                    data-hp="<?= $s['no_hp_siswa'] ?>"
                                                    data-email="<?= $s['email_siswa'] ?>"
                                                    data-ayah="<?= $s['nama_ayah'] ?>"
                                                    data-ibu="<?= $s['nama_ibu'] ?>"
                                                    data-hp_ortu="<?= $s['no_hp_ortu'] ?>"
                                                    data-status="<?= $s['status_siswa'] ?>"
                                                    data-telegram="<?= $s['telegram_chat_id'] ?>"
                                                    class="btn-edit-siswa block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</button>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="<?= base_url('admin/siswa/delete/' . $s['id']) ?>" onclick="return confirm('Hapus siswa & akun login?')" class="block px-4 py-2 text-sm text-red-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-500 dark:hover:text-red-400">Hapus</a>
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
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Total <span class="font-semibold text-gray-900 dark:text-white"><?= count($siswa) ?></span> siswa</span>
    </div>
    <div class="flex items-center space-x-3">
        <button class="px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Previous</button>
        <button class="px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Next</button>
    </div>
</div>

<?= view('admin/siswa/modal_form') ?>
<?= view('admin/siswa/modal_import') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('siswa-search');
        const table = document.getElementById('tabel-siswa');
        const rows = table.getElementsByTagName('tr');

        searchInput.addEventListener('input', function() {
            const filter = searchInput.value.toLowerCase();

            // Loop semua baris tabel (mulai index 1 untuk skip header)
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                // Cek semua sel dalam baris
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }

                // Sembunyikan atau tampilkan baris
                if (found) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });
    });
</script>

<?= $this->endSection() ?>