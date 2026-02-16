<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2 mt-14">
    
    <div class="mb-6 flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">🏢 PUSAT KOMANDO SEKOLAH</h1>
            <p class="text-sm text-gray-500 font-medium">Atur identitas, legalitas, sosial media, dan integrasi API dalam satu panel.</p>
        </div>
        <button form="formSekolah" type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            SIMPAN DATA LENGKAP
        </button>
    </div>

    <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-bold text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-indigo-600 transition-all border-indigo-600 text-indigo-600" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-selected="true">1. PROFIL & LEGALITAS</button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-indigo-600 transition-all" id="media-tab" data-tabs-target="#media" type="button" role="tab" aria-selected="false">2. MEDIA & BRANDING</button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-indigo-600 transition-all" id="api-tab" data-tabs-target="#api" type="button" role="tab" aria-selected="false">3. INTEGRASI API (DEV)</button>
            </li>
        </ul>
    </div>

    <form id="formSekolah" action="<?= base_url('admin/sekolah/update') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        
        <div id="myTabContent">
            
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm space-y-8" id="profile" role="tabpanel">
                
                <div>
                    <h3 class="text-indigo-600 font-black text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2m4-2a2 2 0 110-4m-6 0h.01m-6 0H3m12 0h.01M9 21h1"></path></svg>
                        DATA UTAMA SEKOLAH
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-2">
                            <label class="label-text">Nama Sekolah (Resmi)</label>
                            <input type="text" name="nama_sekolah" value="<?= $sekolah['nama_sekolah'] ?>" class="input-form" required>
                        </div>
                        <div>
                            <label class="label-text">NPSN</label>
                            <input type="text" name="npsn" value="<?= $sekolah['npsn'] ?>" class="input-form font-mono">
                        </div>
                        <div>
                            <label class="label-text">Akreditasi</label>
                            <select name="akreditasi" class="input-form">
                                <option value="A" <?= $sekolah['akreditasi'] == 'A' ? 'selected' : '' ?>>A (Unggul)</option>
                                <option value="B" <?= $sekolah['akreditasi'] == 'B' ? 'selected' : '' ?>>B (Baik)</option>
                                <option value="C" <?= $sekolah['akreditasi'] == 'C' ? 'selected' : '' ?>>C (Cukup)</option>
                                <option value="Belum Terakreditasi" <?= $sekolah['akreditasi'] == 'Belum Terakreditasi' ? 'selected' : '' ?>>Belum Terakreditasi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-indigo-600 font-black text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        LOKASI & PETA
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-3">
                            <label class="label-text">Alamat Jalan</label>
                            <input type="text" name="alamat" value="<?= $sekolah['alamat'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text">Kelurahan / Desa</label>
                            <input type="text" name="kelurahan" value="<?= $sekolah['kelurahan'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text">Kecamatan</label>
                            <input type="text" name="kecamatan" value="<?= $sekolah['kecamatan'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text">Kabupaten / Kota</label>
                            <input type="text" name="kabupaten" value="<?= $sekolah['kabupaten'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text">Provinsi</label>
                            <input type="text" name="provinsi" value="<?= $sekolah['provinsi'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text">Kode Pos</label>
                            <input type="text" name="kode_pos" value="<?= $sekolah['kode_pos'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text">Koordinat Maps (Lat, Long)</label>
                            <input type="text" name="koordinat_longlat" value="<?= $sekolah['koordinat_longlat'] ?>" class="input-form" placeholder="-6.917464, 107.619122">
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-indigo-600 font-black text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        KONTAK & SOSIAL MEDIA
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="label-text">No. Telepon / WA</label>
                            <input type="text" name="no_telp" value="<?= $sekolah['no_telp'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text">Email Resmi</label>
                            <input type="email" name="email" value="<?= $sekolah['email'] ?>" class="input-form">
                        </div>
                        <div class="md:col-span-2">
                            <label class="label-text">Website Sekolah</label>
                            <input type="text" name="website" value="<?= $sekolah['website'] ?>" class="input-form">
                        </div>
                        
                        <div>
                            <label class="label-text text-blue-600">Facebook URL</label>
                            <input type="text" name="facebook" value="<?= $sekolah['facebook'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text text-pink-600">Instagram URL</label>
                            <input type="text" name="instagram" value="<?= $sekolah['instagram'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text text-red-600">Youtube Channel</label>
                            <input type="text" name="youtube" value="<?= $sekolah['youtube'] ?>" class="input-form">
                        </div>
                        <div>
                            <label class="label-text text-black">Tiktok</label>
                            <input type="text" name="tiktok" value="<?= $sekolah['tiktok'] ?>" class="input-form">
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden p-6 rounded-2xl bg-white border border-gray-100 shadow-sm" id="media" role="tabpanel">
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-3">
                         <h3 class="text-indigo-600 font-black text-lg mb-4">DATA KEPALA SEKOLAH</h3>
                         <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label-text">Nama Kepala Sekolah</label>
                                <input type="text" name="nama_kepsek" value="<?= $sekolah['nama_kepsek'] ?>" class="input-form" placeholder="Beserta Gelar">
                            </div>
                            <div>
                                <label class="label-text">NIP / NIY</label>
                                <input type="text" name="nip_kepsek" value="<?= $sekolah['nip_kepsek'] ?>" class="input-form">
                            </div>
                         </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 text-center">
                        <p class="font-bold mb-2">Logo Sekolah</p>
                        <?php if(!empty($sekolah['logo'])): ?>
                            <img src="<?= base_url('uploads/identitas/'.$sekolah['logo']) ?>" class="h-20 mx-auto mb-3 object-contain">
                        <?php endif; ?>
                        <input type="file" name="logo" class="file-input">
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 text-center">
                        <p class="font-bold mb-2">Kop Surat (Header)</p>
                        <?php if(!empty($sekolah['kop_surat'])): ?>
                            <img src="<?= base_url('uploads/identitas/'.$sekolah['kop_surat']) ?>" class="w-full h-20 object-cover mb-3 rounded border">
                        <?php endif; ?>
                        <input type="file" name="kop_surat" class="file-input">
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 text-center">
                        <p class="font-bold mb-2">Tanda Tangan Kepsek</p>
                        <?php if(!empty($sekolah['ttd_kepsek'])): ?>
                            <img src="<?= base_url('uploads/identitas/'.$sekolah['ttd_kepsek']) ?>" class="h-20 mx-auto mb-3 object-contain">
                        <?php endif; ?>
                        <input type="file" name="ttd_kepsek" class="file-input">
                    </div>
                </div>
            </div>

            <div class="hidden p-6 rounded-2xl bg-white border border-gray-100 shadow-sm space-y-6" id="api" role="tabpanel">
                
                <div class="border border-indigo-100 bg-indigo-50/30 p-5 rounded-xl">
                    <h3 class="font-bold text-indigo-700 flex items-center gap-2 mb-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" class="w-5"> Google Auth & Calendar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label-text">Client ID</label>
                            <input type="text" name="google_client_id" value="<?= $sekolah['google_client_id'] ?>" class="input-form font-mono">
                        </div>
                        <div>
                            <label class="label-text">Client Secret</label>
                            <input type="password" name="google_client_secret" value="<?= $sekolah['google_client_secret'] ?>" class="input-form font-mono">
                        </div>
                    </div>
                </div>

                <div class="border border-emerald-100 bg-emerald-50/30 p-5 rounded-xl">
                    <h3 class="font-bold text-emerald-700 flex items-center gap-2 mb-4">
                        📱 Chat Gateway (Notifikasi)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="label-text">WA API URL (Fonnte/Wablas)</label>
                            <input type="text" name="wa_api_url" value="<?= $sekolah['wa_api_url'] ?>" class="input-form font-mono" placeholder="https://api.fonnte.com">
                        </div>
                        <div class="md:col-span-2">
                            <label class="label-text">WhatsApp Token</label>
                            <input type="text" name="wa_api_token" value="<?= $sekolah['wa_api_token'] ?>" class="input-form font-mono" placeholder="Ex: 123456abcdef">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="label-text text-sky-600">Telegram Bot Token</label>
                            <input type="text" name="tele_bot_token" value="<?= $sekolah['tele_bot_token'] ?>" class="input-form font-mono" placeholder="Ex: 12345:AAHJS...">
                        </div>
                        <div>
                            <label class="label-text text-sky-600">Telegram Chat ID (Admin)</label>
                            <input type="text" name="tele_chat_id" value="<?= $sekolah['tele_chat_id'] ?>" class="input-form font-mono" placeholder="Ex: -99887766">
                            <p class="text-[10px] text-gray-400 mt-1">ID Grup/User untuk menerima notifikasi sistem.</p>
                        </div>
                    </div>
                </div>

                <div class="border border-orange-100 bg-orange-50/30 p-5 rounded-xl">
    <h3 class="font-bold text-orange-700 flex items-center gap-2 mb-4">
        💳 Tripay Payment Gateway
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="label-text">API Key</label>
            <input type="text" name="tripay_api_key" value="<?= $sekolah['tripay_api_key'] ?>" class="input-form font-mono">
        </div>
        <div>
            <label class="label-text">Private Key</label>
            <input type="password" name="tripay_private_key" value="<?= $sekolah['tripay_private_key'] ?>" class="input-form font-mono">
        </div>
        <div>
            <label class="label-text">Merchant Code</label>
            <input type="text" name="tripay_merchant_code" value="<?= $sekolah['tripay_merchant_code'] ?>" class="input-form font-mono">
        </div>

        <div class="md:col-span-3 border-t border-orange-200 pt-4 mt-2">
            <label class="label-text text-orange-800">Mode Transaksi</label>
            <select name="mode_transaksi" class="input-form font-bold text-orange-900">
                <option value="Sandbox" <?= $sekolah['mode_transaksi'] == 'Sandbox' ? 'selected' : '' ?>>🛠️ Sandbox (Mode Uji Coba)</option>
                <option value="Production" <?= $sekolah['mode_transaksi'] == 'Production' ? 'selected' : '' ?>>🚀 Production (Mode Live / Asli)</option>
            </select>
            <p class="text-[10px] text-orange-600 mt-1">
                *Gunakan mode <b>Sandbox</b> saat mengetes pembayaran. Pindah ke <b>Production</b> jika sudah siap menerima uang asli dari siswa.
            </p>
        </div>
        </div>
</div>

            </div>
        </div>
    </form>
</div>

<style>
    .label-text { display: block; margin-bottom: 0.5rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #6b7280; }
    .input-form { background-color: #f9fafb; border: 1px solid #d1d5db; color: #111827; font-size: 0.875rem; border-radius: 0.5rem; display: block; width: 100%; padding: 0.625rem; font-weight: 600; }
    .input-form:focus { ring: 2px solid #6366f1; border-color: #6366f1; outline: none; }
    .file-input { display: block; width: 100%; font-size: 0.75rem; color: #374151; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer; background-color: #f9fafb; }
</style>

<script>
    const tabs = document.querySelectorAll('[role="tab"]');
    const panels = document.querySelectorAll('[role="tabpanel"]');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('text-indigo-600', 'border-indigo-600');
                t.classList.add('border-transparent');
            });
            panels.forEach(p => p.classList.add('hidden'));

            tab.classList.add('text-indigo-600', 'border-indigo-600');
            tab.classList.remove('border-transparent');
            
            const target = document.querySelector(tab.dataset.tabsTarget);
            target.classList.remove('hidden');
        });
    });
</script>

<?= $this->endSection(); ?>