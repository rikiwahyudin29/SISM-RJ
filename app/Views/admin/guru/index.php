<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-2 sm:ml-12">
    
    <div class="flex justify-between items-center mb-4 px-2">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Guru</h1>
            <p class="text-xs text-gray-500">Kelola data guru dan akun login.</p>
        </div>
        <a href="<?= base_url('admin/guru/tambah') ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Guru
        </a>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-gray-800 p-1">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Foto</th>
                    <th scope="col" class="px-6 py-3">NIP / NUPTK</th>
                    <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                    <th scope="col" class="px-6 py-3">Username</th>
                    <th scope="col" class="px-6 py-3 text-center" width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($guru)) : ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada data guru. Silakan tambah data baru.
                        </td>
                    </tr>
                <?php else : ?>
                    <?php $no = 1; foreach ($guru as $g) : ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 text-center"><?= $no++ ?></td>
                        <td class="px-6 py-4">
                            <img class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700" src="<?= base_url('uploads/guru/' . ($g['foto'] ?? 'default.png')) ?>" alt="Foto">
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <?= esc($g['nip']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold"><?= esc($g['nama_lengkap']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($g['gelar_depan'] . ' ' . $g['gelar_belakang']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 border border-gray-500">
                                <?= esc($g['username'] ?? '-') ?>
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <a href="<?= base_url('admin/guru/edit/' . $g['id']) ?>" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2 text-center inline-flex items-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-700" title="Edit Data">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    Edit
                                </a>

                                <button type="button" onclick="bukaModalHapus('<?= base_url('admin/guru/hapus/' . $g['id']) ?>')" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-2 text-center inline-flex items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800" title="Hapus Data">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
<div id="successModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900 bg-opacity-50">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="successModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-6 text-center">
                <div class="mx-auto mb-4 text-green-500 bg-green-100 rounded-full p-3 w-16 h-16 flex items-center justify-center dark:bg-green-900 dark:text-green-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Berhasil Disimpan!</h3>
                <div class="mb-5 text-left bg-gray-100 dark:bg-gray-600 p-4 rounded-lg text-gray-700 dark:text-gray-200 text-sm">
                    <?= str_replace('Berhasil! <br>', '', session()->getFlashdata('success')) ?>
                </div>
                <button data-modal-hide="successModal" type="button" class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Siap, Sudah Dicatat!
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div id="deleteModal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900 bg-opacity-50">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="deleteModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-6 text-center">
                <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Yakin mau menghapus guru ini?<br><span class="text-xs">Akun login juga akan terhapus.</span></h3>
                <a id="btnConfirmDelete" href="#" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Ya, Hapus
                </a>
                <button data-modal-hide="deleteModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Script Modal Sukses
    document.addEventListener("DOMContentLoaded", function(event) {
        const successModalEl = document.getElementById('successModal');
        if (successModalEl) {
            const modal = new Modal(successModalEl);
            modal.show();
            const closeBtns = successModalEl.querySelectorAll('[data-modal-hide="successModal"]');
            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.hide();
                });
            });
        }
    });

    // Script Modal Hapus
    let deleteModal;
    document.addEventListener("DOMContentLoaded", function(event) {
        const deleteModalEl = document.getElementById('deleteModal');
        if(deleteModalEl) {
            deleteModal = new Modal(deleteModalEl, { backdrop: 'static' });
        }
    });

    function bukaModalHapus(urlHapus) {
        document.getElementById('btnConfirmDelete').setAttribute('href', urlHapus);
        if(deleteModal) {
            deleteModal.show();
        }
    }
    
    const closeDeleteBtns = document.querySelectorAll('[data-modal-hide="deleteModal"]');
    closeDeleteBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if(deleteModal) deleteModal.hide();
        });
    });
</script>

<?= $this->endSection() ?>