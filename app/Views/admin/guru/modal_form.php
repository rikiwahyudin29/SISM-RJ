<div id="modal-guru-form" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-4xl max-h-full">
        <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-700 ring-1 ring-gray-200 dark:ring-gray-600">
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center" id="modal-title">
                    <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                    <span>Tambah Data Guru</span>
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors" data-modal-hide="modal-guru-form">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto custom-scrollbar">
                
                <form id="formGuru" action="<?= base_url('admin/guru/simpan') ?>" method="POST" enctype="multipart/form-data">
                    
                    <?= csrf_field() ?>

                    <input type="hidden" name="id" id="id_guru">
                    
                    <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800 mb-6">
                        <h4 class="text-sm font-bold text-blue-700 uppercase mb-4 flex items-center dark:text-blue-400">
                            <i class="fas fa-id-card mr-2"></i> Identitas & Akun Login
                        </h4>
                        
                        <div class="grid gap-6 mb-4 md:grid-cols-3">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">NIK (KTP) <span class="text-red-500">*</span></label>
                                <input type="text" name="nik" id="nik" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm" required placeholder="16 digit NIK">
                                <p class="mt-1 text-[11px] text-gray-500"><i class="fas fa-key mr-1"></i>Username Login</p>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">NIP (Opsional)</label>
                                <input type="text" name="nip" id="nip" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm" placeholder="-">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">NUPTK</label>
                                <input type="text" name="nuptk" id="nuptk" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm" placeholder="-">
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-4">
                            <div class="col-span-1">
                                <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Gelar Depan</label>
                                <input type="text" name="gelar_depan" id="gelar_depan" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm" placeholder="Dr.">
                            </div>
                            <div class="col-span-2">
                                <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm" required placeholder="Nama Tanpa Gelar">
                            </div>
                            <div class="col-span-1">
                                <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Gelar Belakang</label>
                                <input type="text" name="gelar_belakang" id="gelar_belakang" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm" placeholder="S.Pd">
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-gray-700 uppercase mb-4 border-b border-gray-200 pb-2 flex items-center dark:text-gray-300">
                            <i class="fas fa-user mr-2"></i> Data Pribadi & Kontak
                        </h4>

                        <div class="grid gap-6 mb-4 md:grid-cols-3">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" id="tgl_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                                <select name="jk" id="jk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-6 mb-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ibu Kandung</label>
                                <input type="text" name="ibu_kandung" id="ibu_kandung" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No. HP / WhatsApp</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fab fa-whatsapp text-green-500"></i>
                                    </div>
                                    <input type="text" name="no_hp" id="no_hp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Jalan, RT/RW, Kecamatan..."></textarea>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Aktif</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="email@sekolah.sch.id">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    ID Telegram (Wajib OTP) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fab fa-telegram text-blue-500"></i>
                                    </div>
                                    <input type="text" name="telegram_chat_id" id="telegram_chat_id" class="bg-blue-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Contoh: 108546xxxx">
                                </div>
                                <p class="mt-1 text-[10px] text-gray-500">Dapatkan ID via bot Telegram sekolah.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-sm font-bold text-gray-700 uppercase mb-4 border-b border-gray-200 pb-2 flex items-center dark:text-gray-300">
                            <i class="fas fa-briefcase mr-2"></i> Kepegawaian & Fasilitas
                        </h4>
                        
                        <div class="grid gap-6 md:grid-cols-3">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Kepegawaian</label>
                                <select name="status_kepegawaian" id="status_kepegawaian" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="GTY">GTY (Tetap Yayasan)</option>
                                    <option value="GTT">GTT (Tidak Tetap)</option>
                                    <option value="PNS">PNS / PPPK</option>
                                    <option value="Honorer">Honorer Sekolah</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pendidikan Terakhir</label>
                                <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="S1 Pendidikan...">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">RFID UID (Absen)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-wifi text-purple-500"></i>
                                    </div>
                                    <input type="text" name="rfid_uid" id="rfid_uid" class="bg-purple-50 border border-purple-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 p-2.5" placeholder="Tempel Kartu...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end p-4 space-x-3 border-t border-gray-200 rounded-b dark:border-gray-600 bg-gray-50 dark:bg-gray-800 -mx-6 -mb-6 mt-6 sticky bottom-0">
                        <button data-modal-hide="modal-guru-form" type="button" class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-red-600 focus:z-10 shadow-sm transition-all">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editButtons = document.querySelectorAll('.btn-edit-guru');
        const modalTitle = document.getElementById('modal-title');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // 1. Ubah Judul Modal jadi Edit
                modalTitle.innerHTML = '<i class="fas fa-edit text-yellow-500 mr-3"></i><span>Edit Data Guru</span>';
                
                // 2. Ambil data dari atribut tombol (sesuai index.php)
                const id = this.getAttribute('data-id');
                const nik = this.getAttribute('data-nik');
                const nip = this.getAttribute('data-nip');
                const nuptk = this.getAttribute('data-nuptk');
                const nama = this.getAttribute('data-nama');
                const depan = this.getAttribute('data-gelar-depan');
                const belakang = this.getAttribute('data-gelar-belakang');
                
                // Data Pribadi (Perhatikan nama atributnya, sesuaikan dengan index.php)
                const tempat = this.getAttribute('data-tempat-lahir'); 
                const tgl = this.getAttribute('data-tgl-lahir');
                const jk = this.getAttribute('data-jk');
                const ibu = this.getAttribute('data-ibu');
                const alamat = this.getAttribute('data-alamat');
                
                // Kontak & Akun
                const hp = this.getAttribute('data-hp');
                const email = this.getAttribute('data-email');
                const telegram = this.getAttribute('data-telegram');
                
                // Kepegawaian
                const pend = this.getAttribute('data-pendidikan');
                const status = this.getAttribute('data-status');
                const rfid = this.getAttribute('data-rfid');

                // 3. Masukkan ke Input Form
                document.getElementById('id_guru').value = id;
                document.getElementById('nik').value = nik;
                document.getElementById('nip').value = nip;
                document.getElementById('nuptk').value = nuptk;
                document.getElementById('nama_lengkap').value = nama;
                document.getElementById('gelar_depan').value = depan;
                document.getElementById('gelar_belakang').value = belakang;
                
                document.getElementById('tempat_lahir').value = tempat;
                document.getElementById('tgl_lahir').value = tgl;
                
                // Handle Select Option (JK)
                if(jk) document.getElementById('jk').value = jk;
                
                document.getElementById('ibu_kandung').value = ibu;
                document.getElementById('alamat').value = alamat;
                
                document.getElementById('no_hp').value = hp;
                document.getElementById('email').value = email;
                document.getElementById('telegram_chat_id').value = telegram; // Ini Field Telegram
                
                document.getElementById('pendidikan_terakhir').value = pend;
                
                // Handle Select Option (Status)
                if(status) {
                    const statusSelect = document.getElementById('status_kepegawaian');
                    // Cek jika value ada di option, kalau tidak ada default ke Honorer atau biarkan
                    let found = false;
                    for(let i=0; i<statusSelect.options.length; i++){
                        if(statusSelect.options[i].value == status){
                            statusSelect.selectedIndex = i;
                            found = true;
                            break;
                        }
                    }
                    // Jika status dari DB tidak ada di list option (misal 'Guru Mapel'), bisa ditambahkan logic lain
                    // Tapi biasanya cocok.
                }
                
                document.getElementById('rfid_uid').value = rfid;
            });
        });

        // Reset form saat tombol Tambah diklik
        const addBtns = document.querySelectorAll('[data-modal-toggle="modal-guru-form"]');
        addBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if(!this.classList.contains('btn-edit-guru')) {
                    modalTitle.innerHTML = '<i class="fas fa-user-plus text-blue-600 mr-3"></i><span>Tambah Data Guru</span>';
                    document.getElementById('formGuru').reset();
                    document.getElementById('id_guru').value = '';
                }
            });
        });
    });
</script>