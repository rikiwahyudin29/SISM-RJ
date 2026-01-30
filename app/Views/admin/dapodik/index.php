<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-black text-slate-800 mb-6">🔗 Integrasi Dapodik (Web Service)</h1>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-emerald-100 text-emerald-700 p-4 rounded-lg mb-6 font-bold border border-emerald-200">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-rose-100 text-rose-700 p-4 rounded-lg mb-6 font-bold border border-rose-200">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
        <h2 class="font-bold text-lg mb-4">Konfigurasi Koneksi</h2>
        
        <div class="mb-4">
            Status: 
            <?php if($setting->status_koneksi == 'Terhubung'): ?>
                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-bold">✅ Terhubung</span>
            <?php else: ?>
                <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-full text-sm font-bold">❌ Terputus</span>
            <?php endif; ?>
        </div>

        <form action="<?= base_url('admin/dapodik/update_setting') ?>" method="post" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?= csrf_field() ?> <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">IP Address Dapodik (Laptop Operator)</label>
                <input type="text" name="ip_dapodik" value="<?= $setting->ip_dapodik ?>" class="w-full border p-2 rounded" placeholder="http://192.168.1.XX:5774" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Key Integrasi (Dari Dapodik)</label>
                <input type="text" name="key_integrasi" value="<?= $setting->key_integrasi ?>" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">NPSN</label>
                <input type="text" name="npsn" value="<?= $setting->npsn ?>" class="w-full border p-2 rounded" required>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition">Simpan Pengaturan</button>
                <a href="<?= base_url('admin/dapodik/cek_koneksi') ?>" class="px-4 py-2 bg-slate-800 text-white rounded font-bold hover:bg-slate-900 transition">Tes Koneksi 🔄</a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="font-bold text-lg mb-2 text-blue-600">⬇️ Tarik Data (Dapodik -> SIAKAD)</h3>
            <p class="text-sm text-slate-500 mb-4">Mengambil data Siswa dan Guru terbaru dari Dapodik.</p>
            
            <div class="flex flex-col gap-3">
                <a href="<?= base_url('admin/dapodik/tarik_sekolah') ?>" class="flex justify-between items-center p-3 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition-all mb-3 group" onclick="return confirm('Update Profil Sekolah?')">
    <div>
        <span class="font-bold text-indigo-800">Update Profil Sekolah</span>
        <p class="text-xs text-indigo-600">Nama Sekolah, Alamat, & Kepala Sekolah (Utk Raport)</p>
    </div>
    <span class="text-xs bg-indigo-200 text-indigo-800 px-2 py-1 rounded">Identitas</span>
</a>
                <a href="<?= base_url('admin/dapodik/tarik_rombel') ?>" class="flex justify-between items-center p-3 bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-lg transition-all group" onclick="return confirm('Tarik Data Rombongan Belajar (Kelas)?')">
        <div>
            <span class="font-bold text-orange-800">1. Tarik Data Kelas (Rombel)</span>
            <p class="text-xs text-orange-600">Lakukan ini sebelum tarik siswa!</p>
        </div>
        <span class="text-xs bg-orange-200 text-orange-800 px-2 py-1 rounded">Prioritas</span>
    </a>
                <a href="<?= base_url('admin/dapodik/tarik_siswa') ?>" class="flex justify-between items-center p-3 bg-slate-50 hover:bg-blue-50 border rounded-lg transition-all group" onclick="return confirm('Yakin ingin menarik data siswa? Proses mungkin agak lama.')">
                    <span class="font-bold text-slate-700">Tarik Data Siswa</span>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Proses</span>
                </a>
                <a href="<?= base_url('admin/dapodik/tarik_guru') ?>" class="flex justify-between items-center p-3 bg-slate-50 hover:bg-blue-50 border rounded-lg transition-all group" onclick="return confirm('Yakin ingin menarik data Guru?')">
                    <span class="font-bold text-slate-700">Tarik Data Guru (PTK)</span>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Proses</span>
                </a>
                <a href="<?= base_url('admin/dapodik/tarik_jurusan') ?>" class="flex justify-between items-center p-3 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-all mb-3 group" onclick="return confirm('Tarik Data Jurusan?')">
    <div>
        <span class="font-bold text-purple-800">Tarik Data Jurusan</span>
        <p class="text-xs text-purple-600">Wajib dilakukan agar Kelas punya Jurusan.</p>
    </div>
    <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded">Master Data</span>
</a>
<a href="<?= base_url('admin/dapodik/tarik_mapel') ?>" class="flex justify-between items-center p-3 bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-lg transition-all group" onclick="return confirm('Tarik Data Mata Pelajaran & Relasi Jurusan?')">
    <div>
        <span class="font-bold text-teal-800">3. Tarik Mata Pelajaran</span>
        <p class="text-xs text-teal-600">Otomatis mapping Mapel Umum & Jurusan.</p>
    </div>
    <span class="text-xs bg-teal-200 text-teal-800 px-2 py-1 rounded">Kurikulum</span>
</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="font-bold text-lg mb-2 text-rose-600">⬆️ Kirim Data (SIAKAD -> Dapodik)</h3>
            <p class="text-sm text-slate-500 mb-4">Mengirim nilai Rapor ke Dapodik. <br><span class="text-rose-500 font-bold">*Wajib Mapping Mapel dulu!</span></p>

            <form action="<?= base_url('admin/dapodik/kirim_raport') ?>" method="post">
                <?= csrf_field() ?> <select name="id_tahun_ajaran" class="w-full border p-2 rounded mb-3">
                    <option value="4">2025/2026 Genap</option>
                    </select>
                <button type="submit" class="w-full py-2 bg-rose-600 text-white font-bold rounded hover:bg-rose-700 transition" onclick="return confirm('Pastikan ID Mapel dan Siswa sudah sinkron dengan Dapodik!')">
                    🚀 Kirim Nilai Rapor
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>