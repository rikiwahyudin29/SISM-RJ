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
                    <li><div class="flex items-center"><svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg><span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2">Manajemen Kelas</span></div></li>
                    <li><div class="flex items-center"><svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg><span class="ml-1 text-gray-400 dark:text-gray-500 md:ml-2">Detail Siswa</span></div></li>
                </ol>
            </nav>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="<?= base_url('admin/master/kelas') ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        &larr; Kembali
                    </a>
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                        Siswa Kelas: <?= $nama_kelas ?>
                    </h1>
                </div>
            </div>
        </div>
        
        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700">
            <div class="flex items-center mb-4 sm:mb-0">
                <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="text" id="siswa-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Cari Siswa...">
                </div>
            </div>
            
            <div class="flex items-center justify-end flex-1 ml-auto space-x-2 sm:space-x-3">
                <button type="button" data-modal-target="modal-import-siswa" data-modal-toggle="modal-import-siswa" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    Import Excel
                </button>

                <button type="button" data-modal-target="modal-tambah-siswa" data-modal-toggle="modal-tambah-siswa" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Tambah Siswa
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table id="tabel-siswa" class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">No</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">NISN</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Nama Siswa</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">L/P</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php if (empty($siswa)) : ?>
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400">Belum ada siswa di kelas ini.</td>
                            </tr>
                        <?php else : ?>
                            <?php $no = 1; foreach($siswa as $s): ?>
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400"><?= $no++ ?></td>
                                <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400"><?= $s['nisn'] ?></td>
                                <td class="p-4 text-sm font-bold text-gray-900 dark:text-white"><?= $s['nama_lengkap'] ?></td>
                                <td class="p-4 text-sm text-gray-900 dark:text-white">
                                    <?php if($s['jenis_kelamin'] == 'L'): ?>
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">L</span>
                                    <?php else: ?>
                                        <span class="bg-pink-100 text-pink-800 text-xs font-medium px-2.5 py-0.5 rounded border border-pink-400">P</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 space-x-2 whitespace-nowrap">
                                    <button type="button" 
                                        data-modal-target="modal-edit-siswa" 
                                        data-modal-toggle="modal-edit-siswa" 
                                        data-id="<?= $s['id'] ?>"
                                        data-nisn="<?= $s['nisn'] ?>"
                                        data-nama="<?= $s['nama_lengkap'] ?>"
                                        data-jk="<?= $s['jenis_kelamin'] ?>"
                                        class="btn-edit-siswa inline-flex items-center p-2 text-sm font-medium text-center text-blue-600 bg-white rounded-lg hover:bg-gray-100 focus:outline-none dark:text-blue-500 focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    
                                    <a href="<?= base_url('admin/master/siswa_hapus/' . $s['id']) ?>" onclick="return confirm('Hapus siswa ini?')" 
                                        class="inline-flex items-center p-2 text-sm font-medium text-center text-red-600 bg-white rounded-lg hover:bg-gray-100 focus:outline-none dark:text-red-500 focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>
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

<div id="modal-tambah-siswa" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-gray-900/50 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-lg bg-white rounded-lg shadow dark:bg-gray-700">
        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Siswa</h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600" data-modal-hide="modal-tambah-siswa">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <form action="<?= base_url('admin/master/siswa_simpan') ?>" method="POST" class="p-6 space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">

            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NISN</label>
                    <input type="number" name="nisn" placeholder="00xxxxxx" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" placeholder="Nama Siswa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</div>

<div id="modal-edit-siswa" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-gray-900/50 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-lg bg-white rounded-lg shadow dark:bg-gray-700">
        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Siswa</h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600" data-modal-hide="modal-edit-siswa">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <form id="form-edit-siswa" method="POST" class="p-6 space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_id_siswa">
            <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>"> <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NISN</label>
                    <input type="number" name="nisn" id="edit_nisn" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="edit_nama_lengkap" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="edit_jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">Simpan Perubahan</button>
        </form>
    </div>
</div>

<div id="modal-import-siswa" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-gray-900/50 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-lg bg-white rounded-lg shadow dark:bg-gray-700">
        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Import Siswa ke Kelas Ini</h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600" data-modal-hide="modal-import-siswa">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <form action="<?= base_url('admin/import/siswa_kelas') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="kelas_id" value="<?= $id_kelas ?>">

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload file .xlsx</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" name="file_excel" type="file" accept=".xlsx" required>
            </div>
            <button type="submit" class="w-full text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700">Upload & Import</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Live Search
        const searchInput = document.getElementById('siswa-search');
        const table = document.getElementById('tabel-siswa');
        const rows = table.getElementsByTagName('tr');

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const filter = searchInput.value.toLowerCase();
                for (let i = 1; i < rows.length; i++) { 
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let found = false;
                    // Cek NISN (index 1) dan Nama (index 2)
                    if ((cells[1] && cells[1].textContent.toLowerCase().indexOf(filter) > -1) || 
                        (cells[2] && cells[2].textContent.toLowerCase().indexOf(filter) > -1)) {
                        found = true;
                    }
                    row.style.display = found ? "" : "none";
                }
            });
        }

        // 2. Logic Tombol Edit (Shared Modal)
        const editButtons = document.querySelectorAll('.btn-edit-siswa');
        const formEdit = document.getElementById('form-edit-siswa');
        const baseUrl = '<?= base_url('admin/master/siswa_update/') ?>';

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nisn = this.dataset.nisn;
                const nama = this.dataset.nama;
                const jk = this.dataset.jk;

                document.getElementById('edit_id_siswa').value = id;
                document.getElementById('edit_nisn').value = nisn;
                document.getElementById('edit_nama_lengkap').value = nama;
                document.getElementById('edit_jenis_kelamin').value = jk;

                formEdit.action = baseUrl + id;
            });
        });
    });
</script>

<?= $this->endSection() ?>