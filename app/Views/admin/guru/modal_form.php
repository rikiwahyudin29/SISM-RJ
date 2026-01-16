<div id="modal-guru-form" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-4xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modal-guru-title">
                    Tambah Guru Baru
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-guru-form">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto custom-scrollbar">
                <form action="<?= base_url('admin/guru/simpan') ?>" method="POST" id="form-guru" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="guru-id">

                    <div class="grid grid-cols-6 gap-6">
                        
                        <div class="col-span-6">
                             <h4 class="text-base font-bold text-gray-900 dark:text-white mb-3 pb-2 border-b dark:border-gray-600">Identitas & Akun</h4>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="nip" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIP (Sebagai Username Login) <span class="text-red-500">*</span></label>
                            <input type="text" name="nip" id="nip" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: 19850101..." required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap (Tanpa Gelar) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label for="gelar_depan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gelar Depan</label>
                            <input type="text" name="gelar_depan" id="gelar_depan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: Dr., Drs.">
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label for="gelar_belakang" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" id="gelar_belakang" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: S.Pd., M.Kom.">
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                         <div class="col-span-6 mt-4">
                             <h4 class="text-base font-bold text-gray-900 dark:text-white mb-3 pb-2 border-b dark:border-gray-600">Data Pribadi & Kontak</h4>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="tempat_lahir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>
                         <div class="col-span-6 sm:col-span-3">
                            <label for="tanggal_lahir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" id="no_hp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Aktif</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="col-span-6">
                            <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                        </div>
                        <div class="col-span-6 sm:col-span-6">
    <label for="telegram_chat_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        ID Telegram (Wajib untuk OTP Login) <span class="text-red-500">*</span>
    </label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
            </svg>
        </div>
        <input type="text" name="telegram_chat_id" id="telegram_chat_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: 123456789">
    </div>
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">User bisa cek ID via bot Telegram.</p>
</div>

                        <div class="col-span-6 mt-4">
                             <h4 class="text-base font-bold text-gray-900 dark:text-white mb-3 pb-2 border-b dark:border-gray-600">Kepegawaian & Pendidikan</h4>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="status_guru" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Kepegawaian</label>
                            <select name="status_guru" id="status_guru" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="GTY">GTY (Tetap Yayasan)</option>
                                <option value="GTT">GTT (Tidak Tetap)</option>
                                <option value="PNS">PNS</option>
                                <option value="PPPK">PPPK</option>
                                <option value="HONORER">Honorer</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="pendidikan_terakhir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: S1 Pendidikan Matematika">
                        </div>
                        <div class="col-span-6">
                            <label for="sertifikasi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sertifikasi / Keahlian</label>
                            <input type="text" name="sertifikasi" id="sertifikasi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: Sertifikat Pendidik 2023">
                        </div>

                        <div class="col-span-6 mt-4">
                             <h4 class="text-base font-bold text-gray-900 dark:text-white mb-3 pb-2 border-b dark:border-gray-600">Foto Profil</h4>
                        </div>
                        <div class="col-span-6">
                            
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="foto">Upload Foto (Opsional)</label>
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400" id="foto" name="foto" type="file" accept="image/png, image/jpeg, image/jpg">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-300" id="file_input_help">Format: JPG, JPEG, PNG. Maks. 2MB.</p>
                        </div>
                    </div>
            </div>
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" id="btn-simpan-guru">Simpan Data</button>
                <button data-modal-hide="modal-guru-form" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalTitle = document.getElementById('modal-guru-title');
        const formGuru = document.getElementById('form-guru');
        const btnSimpan = document.getElementById('btn-simpan-guru');
        const inputId = document.getElementById('guru-id');

        // Helper untuk set value (biar gak error kalau null)
        const setValue = (id, val) => { document.getElementById(id).value = val ?? ''; };

        // MODE TAMBAH
        document.querySelector('[data-modal-toggle="modal-guru-form"]').addEventListener('click', function() {
            modalTitle.textContent = 'Tambah Guru Baru';
            formGuru.reset();
            inputId.value = '';
            btnSimpan.textContent = 'Simpan Data';
            document.getElementById('nip').removeAttribute('readonly'); // NIP bisa diedit saat tambah
        });

        // MODE EDIT (Mengisi semua field)
        document.querySelectorAll('.btn-edit-guru').forEach(button => {
            button.addEventListener('click', function() {
                modalTitle.textContent = 'Edit Data Guru';
                btnSimpan.textContent = 'Simpan Perubahan';
                
                const d = this.dataset; // Ambil semua data-attribute
                
                inputId.value = d.id;
                setValue('nip', d.nip);
                setValue('nama_lengkap', d.nama);
                setValue('gelar_depan', d.gelar_depan);
                setValue('gelar_belakang', d.gelar_belakang);
                setValue('jenis_kelamin', d.jenis_kelamin);
                setValue('tempat_lahir', d.tempat_lahir);
                setValue('tanggal_lahir', d.tanggal_lahir);
                setValue('no_hp', d.no_hp);
                setValue('email', d.email);
                setValue('alamat', d.alamat);
                setValue('status_guru', d.status);
                setValue('pendidikan_terakhir', d.pendidikan);
                setValue('sertifikasi', d.sertifikasi);
                setValue('telegram_chat_id', d.telegram);

                // Opsional: NIP sebaiknya tidak diubah sembarangan saat edit karena terkait username login
                // document.getElementById('nip').setAttribute('readonly', true); 
            });
        });
    });
</script>