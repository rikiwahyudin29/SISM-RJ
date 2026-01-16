<div id="modal-siswa-form" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-4xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modal-siswa-title">Tambah Siswa Baru</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600" data-modal-hide="modal-siswa-form">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            
            <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto custom-scrollbar">
                <form action="<?= base_url('admin/siswa/simpan') ?>" method="POST" id="form-siswa" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="siswa-id">

                    <h4 class="text-base font-bold text-gray-900 dark:text-white mb-3 border-b pb-2 border-gray-200 dark:border-gray-600">Akun & Identitas</h4>
                    <div class="grid grid-cols-6 gap-6 mb-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NISN (Username Login) <span class="text-red-500">*</span></label>
                            <input type="text" name="nisn" id="nisn" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIS Lokal</label>
                            <input type="text" name="nis" id="nis" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telegram ID (Untuk OTP)</label>
                            <input type="text" name="telegram_chat_id" id="telegram_chat_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Contoh: 123456789">
                        </div>
                    </div>

                    <h4 class="text-base font-bold text-gray-900 dark:text-white mb-3 border-b pb-2 border-gray-200 dark:border-gray-600">Akademik</h4>
                    <div class="grid grid-cols-6 gap-6 mb-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Pilih Kelas</option>
                                <?php foreach($kelas as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jurusan</label>
                            <select name="jurusan_id" id="jurusan_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Pilih Jurusan</option>
                                <?php foreach($jurusan as $j): ?>
                                    <option value="<?= $j['id'] ?>"><?= $j['nama_jurusan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Siswa</label>
                            <select name="status_siswa" id="status_siswa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="Aktif">Aktif</option>
                                <option value="Lulus">Lulus</option>
                                <option value="Keluar">Keluar</option>
                                <option value="Skorsing">Skorsing</option>
                            </select>
                        </div>
                    </div>

                    <h4 class="text-base font-bold text-gray-900 dark:text-white mb-3 border-b pb-2 border-gray-200 dark:border-gray-600">Data Pribadi & Kontak</h4>
                    <div class="grid grid-cols-6 gap-6 mb-6">
                        <div class="col-span-6 sm:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No HP Siswa</label>
                            <input type="text" name="no_hp_siswa" id="no_hp_siswa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" name="email_siswa" id="email_siswa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                         <div class="col-span-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                        </div>
                    </div>

                    <h4 class="text-base font-bold text-gray-900 dark:text-white mb-3 border-b pb-2 border-gray-200 dark:border-gray-600">Data Orang Tua / Wali</h4>
                    <div class="grid grid-cols-6 gap-6 mb-6">
                        <div class="col-span-6 sm:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ayah</label>
                            <input type="text" name="nama_ayah" id="nama_ayah" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ibu</label>
                            <input type="text" name="nama_ibu" id="nama_ibu" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No HP Ortu</label>
                            <input type="text" name="no_hp_ortu" id="no_hp_ortu" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>

                    <div class="col-span-6">
                         <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Foto Siswa</label>
                         <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="foto" name="foto" type="file">
                    </div>

                    <div class="flex items-center space-x-2 border-t pt-4 dark:border-gray-600">
                        <button type="submit" id="btn-simpan-siswa" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan Data</button>
                        <button data-modal-hide="modal-siswa-form" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalTitle = document.getElementById('modal-siswa-title');
    const formSiswa = document.getElementById('form-siswa');
    const inputId = document.getElementById('siswa-id');
    const btnSimpan = document.getElementById('btn-simpan-siswa');
    const setValue = (id, val) => { document.getElementById(id).value = val ?? ''; };

    // MODE TAMBAH
    document.querySelector('[data-modal-toggle="modal-siswa-form"]').addEventListener('click', function() {
        modalTitle.textContent = 'Tambah Siswa Baru';
        formSiswa.reset();
        inputId.value = '';
        btnSimpan.textContent = 'Simpan Data';
        document.getElementById('nisn').removeAttribute('readonly');
    });

    // MODE EDIT
    document.querySelectorAll('.btn-edit-siswa').forEach(btn => {
        btn.addEventListener('click', function() {
            modalTitle.textContent = 'Edit Data Siswa';
            btnSimpan.textContent = 'Simpan Perubahan';
            const d = this.dataset;
            
            inputId.value = d.id;
            setValue('nisn', d.nisn);
            setValue('nis', d.nis);
            setValue('nama_lengkap', d.nama);
            setValue('kelas_id', d.kelas);
            setValue('jurusan_id', d.jurusan);
            setValue('jenis_kelamin', d.jk);
            setValue('tempat_lahir', d.tempat);
            setValue('tanggal_lahir', d.tgl);
            setValue('no_hp_siswa', d.hp);
            setValue('email_siswa', d.email);
            setValue('alamat', d.alamat);
            setValue('nama_ayah', d.ayah);
            setValue('nama_ibu', d.ibu);
            setValue('no_hp_ortu', d.hp_ortu);
            setValue('status_siswa', d.status);
            setValue('telegram_chat_id', d.telegram);
        });
    });
});
</script>