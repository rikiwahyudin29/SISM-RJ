<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online | <?= $web['nama_sekolah'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('uploads/identitas/'.$web['logo']) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0f4f8; }
        .g-group { position: relative; margin-bottom: 1.5rem; }
        .g-input { width: 100%; padding: 10px 0; font-size: 1rem; color: #1e293b; border: none; border-bottom: 2px solid #cbd5e1; outline: none; background: transparent; transition: all 0.3s ease; }
        .g-input:focus { border-bottom-color: #2563eb; }
        .g-label { font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 5px; display: block; }
        .form-section { background: white; padding: 2.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 2rem; border-top: 5px solid #2563eb; }
        .section-header { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
    </style>
</head>
<body>

    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="text-center mb-10">
            <img src="<?= base_url('uploads/identitas/'.$web['logo']) ?>" class="h-24 mx-auto mb-4 drop-shadow-md">
            <h1 class="text-3xl font-extrabold text-slate-800">Formulir Pendaftaran Siswa Baru</h1>
            <p class="text-slate-500 font-medium">Tahun Ajaran <?= date('Y') ?>/<?= date('Y')+1 ?> - <?= $web['nama_sekolah'] ?></p>
        </div>

        <form action="<?= base_url('spmb/save') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-section">
                <div class="section-header text-blue-600">
                    <i class="fas fa-user-circle text-2xl"></i> A. Data Pribadi Calon Siswa
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                    <div class="g-group md:col-span-2">
                        <label class="g-label">Nama Lengkap (Sesuai Ijazah) <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" class="g-input uppercase font-bold" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label">NISN <span class="text-red-500">*</span></label>
                        <input type="number" name="nisn" class="g-input" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label">NIK / No. KTP <span class="text-red-500">*</span></label>
                        <input type="number" name="nik" class="g-input" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" class="g-input uppercase" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_lahir" class="g-input" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jk" class="g-input cursor-pointer" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="g-group">
                        <label class="g-label">Agama</label>
                        <select name="agama" class="g-input cursor-pointer">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                        </select>
                    </div>
                    <div class="g-group md:col-span-2">
                        <label class="g-label text-blue-600 font-bold">No. HP Siswa (WhatsApp) <span class="text-red-500">*</span></label>
                        <input type="number" name="no_hp_siswa" class="g-input font-bold" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>
            </div>

            <div class="form-section !border-orange-500">
                <div class="section-header text-orange-600">
                    <i class="fas fa-map-marked-alt text-2xl"></i> B. Alamat Domisili
                </div>
                <div class="g-group">
                    <label class="g-label">Alamat Jalan (Nama Jalan, Gg, Blok) <span class="text-red-500">*</span></label>
                    <input type="text" name="alamat_jalan" class="g-input uppercase" required>
                </div>
                <div class="grid grid-cols-2 gap-x-8 gap-y-2">
                    <div class="g-group">
                        <label class="g-label">RT / RW</label>
                        <input type="text" name="rt_rw" class="g-input" placeholder="001/002">
                    </div>
                    <div class="g-group">
                        <label class="g-label">Kode Pos</label>
                        <input type="number" name="kode_pos" class="g-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                    <div class="g-group">
                        <label class="g-label">Desa / Kelurahan</label>
                        <input type="text" name="desa_kelurahan" class="g-input uppercase" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label">Kecamatan</label>
                        <input type="text" name="kecamatan" class="g-input uppercase" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label">Kabupaten / Kota</label>
                        <input type="text" name="kabupaten" class="g-input uppercase" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label">Provinsi</label>
                        <input type="text" name="provinsi" class="g-input uppercase" required>
                    </div>
                </div>
            </div>

            <div class="form-section !border-purple-500">
                <div class="section-header text-purple-600">
                    <i class="fas fa-users text-2xl"></i> C. Data Orang Tua / Wali
                </div>
                
                <div class="mb-6 border-b pb-4">
                    <h3 class="font-bold text-gray-700 mb-3">1. Identitas Ayah</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="g-group">
                            <label class="g-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="g-input uppercase">
                        </div>
                        <div class="g-group">
                            <label class="g-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan_ayah" class="g-input uppercase">
                        </div>
                        <div class="g-group">
                            <label class="g-label">No. HP Ayah</label>
                            <input type="number" name="no_hp_ayah" class="g-input">
                        </div>
                    </div>
                </div>

                <div class="mb-6 border-b pb-4">
                    <h3 class="font-bold text-gray-700 mb-3">2. Identitas Ibu</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="g-group">
                            <label class="g-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="g-input uppercase">
                        </div>
                        <div class="g-group">
                            <label class="g-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan_ibu" class="g-input uppercase">
                        </div>
                        <div class="g-group">
                            <label class="g-label">No. HP Ibu</label>
                            <input type="number" name="no_hp_ibu" class="g-input">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-gray-700 mb-3">3. Identitas Wali (Jika ada)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="g-group">
                            <label class="g-label">Nama Wali</label>
                            <input type="text" name="nama_wali" class="g-input uppercase">
                        </div>
                        <div class="g-group">
                            <label class="g-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan_wali" class="g-input uppercase">
                        </div>
                        <div class="g-group">
                            <label class="g-label">No. HP Wali</label>
                            <input type="number" name="no_hp_wali" class="g-input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section !border-emerald-500">
                <div class="section-header text-emerald-600">
                    <i class="fas fa-graduation-cap text-2xl"></i> D. Asal Sekolah & Peminatan
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                    <div class="g-group">
                        <label class="g-label">Asal Sekolah (SMP/MTs) <span class="text-red-500">*</span></label>
                        <input type="text" name="asal_sekolah" class="g-input uppercase" required>
                    </div>
                    <div class="g-group">
                        <label class="g-label text-emerald-700 font-bold">Pilih Jurusan <span class="text-red-500">*</span></label>
                        <select name="jurusan_minat" class="g-input font-bold cursor-pointer" required>
                            <option value="" disabled selected>-- Pilih --</option>
                            <?php foreach($jurusan as $j): ?>
                                <option value="<?= $j['nama_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section !border-gray-500">
                <div class="section-header text-gray-700">
                    <i class="fas fa-file-upload text-2xl"></i> E. Upload Berkas
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 border border-dashed border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <label class="g-label mb-2">Pas Foto (3x4) <span class="text-red-500">*</span></label>
                        <input type="file" name="foto" class="text-sm w-full" accept="image/*" required>
                        <p class="text-xs text-gray-400 mt-1">Format: JPG/PNG, Max 2MB</p>
                    </div>
                    <div class="text-center p-4 border border-dashed border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <label class="g-label mb-2">Kartu Keluarga (Scan)</label>
                        <input type="file" name="berkas_kk" class="text-sm w-full" accept="image/*,application/pdf">
                        <p class="text-xs text-gray-400 mt-1">JPG/PDF</p>
                    </div>
                    <div class="text-center p-4 border border-dashed border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <label class="g-label mb-2">Ijazah / SKL (Scan)</label>
                        <input type="file" name="berkas_ijazah" class="text-sm w-full" accept="image/*,application/pdf">
                        <p class="text-xs text-gray-400 mt-1">JPG/PDF</p>
                    </div>
                </div>
                
                <div class="mt-8 flex items-start gap-2 bg-blue-50 p-3 rounded">
                    <input type="checkbox" required class="mt-1 w-4 h-4">
                    <p class="text-sm text-blue-800">Saya menyatakan data di atas benar dan siap diverifikasi.</p>
                </div>
            </div>

            <div class="text-center mb-10">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-full shadow-xl transition-all transform hover:-translate-y-1">
                    <i class="fas fa-paper-plane mr-2"></i> KIRIM FORMULIR
                </button>
                <div class="mt-4">
                    <a href="<?= base_url() ?>" class="text-slate-500 text-sm hover:underline">Batal & Kembali</a>
                </div>
            </div>

        </form>
    </div>
</body>
</html>