<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="p-2 sm:ml-12">

    <div class="mb-6 px-2 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tambah Guru Baru</h1>
            <p class="text-sm text-gray-500">Lengkapi form di bawah untuk mendaftarkan guru.</p>
        </div>
        <a href="<?= base_url('admin/guru') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-500 hover:underline">← Kembali ke Daftar</a>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200" role="alert">
            <span class="font-medium">Gagal!</span> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-gray-800 relative shadow-xl sm:rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
        
        <form action="<?= base_url('admin/guru/simpan') ?>" method="post" enctype="multipart/form-data" class="p-8">
            <?= csrf_field() ?>

            <div class="flex flex-col items-center justify-center mb-10 pb-10 border-b border-gray-100 dark:border-gray-700">
                <div class="relative group">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl dark:border-gray-700 bg-gray-50 flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                        <img id="imgPreview" 
                             src="<?= base_url('uploads/guru/default.png') ?>" 
                             alt="Preview" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <label for="fotoInput" class="absolute bottom-1 right-1 bg-blue-600 text-white p-2.5 rounded-full cursor-pointer hover:bg-blue-700 transition-all shadow-lg hover:scale-110 active:scale-90" title="Pilih Foto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </label>
                </div>
                
                <input type="file" id="fotoInput" name="foto" class="hidden" accept="image/*" onchange="previewGambar()">
                <p class="mt-4 text-sm font-bold text-gray-900 dark:text-white">Foto Profil</p>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Format: JPG, PNG (Maks 2MB)</p>
            </div>

            <div class="grid gap-10 md:grid-cols-2">
                
                <div class="space-y-6">
                    <h3 class="flex items-center text-lg font-bold text-gray-900 dark:text-white">
                        <span class="flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-[10px] font-black mr-3 rounded-full">1</span>
                        Identitas & Akun
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">NIP / NUPTK <span class="text-red-500">*</span></label>
                            <input type="text" name="nip" value="<?= old('nip') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Masukkan NIP" required>
                            <p class="mt-1.5 text-[11px] text-gray-500 italic">NIP ini akan otomatis menjadi Username login.</p>
                        </div>
                        
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Nama tanpa gelar" required>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="<?= old('email') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="nama@sekolah.sch.id" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">WhatsApp</label>
                                <input type="number" name="nomor_wa" value="<?= old('nomor_wa') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="08xxx">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">ID Telegram</label>
                                <input type="text" name="telegram_chat_id" value="<?= old('telegram_chat_id') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Contoh: 12345678">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="flex items-center text-lg font-bold text-gray-900 dark:text-white">
                        <span class="flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-[10px] font-black mr-3 rounded-full">2</span>
                        Detail Lainnya
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">Gelar Depan</label>
                                <input type="text" name="gelar_depan" value="<?= old('gelar_depan') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Drs. / H.">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">Gelar Belakang</label>
                                <input type="text" name="gelar_belakang" value="<?= old('gelar_belakang') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="S.Pd. / M.T.">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="L" <?= old('jenis_kelamin') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= old('jenis_kelamin') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">Alamat Lengkap</label>
                            <textarea name="alamat" rows="4" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Alamat domisili saat ini..."><?= old('alamat') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-12 pt-8 border-t border-gray-100 dark:border-gray-700">
                <a href="<?= base_url('admin/guru') ?>" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-bold rounded-xl text-sm px-6 py-3 dark:bg-transparent dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 transition-all">
                    Batal
                </a>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-sm px-10 py-3 dark:bg-blue-600 dark:hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Simpan Data Guru
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function previewGambar() {
        const fotoInput = document.querySelector('#fotoInput');
        const imgPreview = document.querySelector('#imgPreview');

        if (fotoInput.files && fotoInput.files[0]) {
            const fileReader = new FileReader();
            fileReader.onload = function(e) {
                imgPreview.src = e.target.result;
            }
            fileReader.readAsDataURL(fotoInput.files[0]);
        }
    }
</script>
<?= $this->endSection() ?>