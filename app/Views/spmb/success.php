<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil | <?= $web['nama_sekolah'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('uploads/identitas/'.$web['logo']) ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .success-checkmark { width: 80px; height: 80px; margin: 0 auto; }
        .check-icon { width: 80px; height: 80px; position: relative; border-radius: 50%; box-sizing: content-box; border: 4px solid #4CAF50; }
        .check-icon::before { top: 3px; left: -2px; width: 30px; transform-origin: 100% 50%; border-radius: 100px 0 0 100px; }
        .check-icon::after { top: 0; left: 30px; width: 60px; transform-origin: 0 50%; border-radius: 0 100px 100px 0; animation: rotate-circle 4.25s ease-in; }
        .check-icon::before, .check-icon::after { content: ''; height: 100px; position: absolute; background: #FFFFFF; transform: rotate(-45deg); }
        .icon-line { height: 5px; background-color: #4CAF50; display: block; border-radius: 2px; position: absolute; z-index: 10; }
        .icon-line.line-tip { top: 46px; left: 14px; width: 25px; transform: rotate(45deg); animation: icon-line-tip 0.75s; }
        .icon-line.line-long { top: 38px; right: 8px; width: 47px; transform: rotate(-45deg); animation: icon-line-long 0.75s; }
        .icon-circle { top: -4px; left: -4px; z-index: 10; width: 80px; height: 80px; border-radius: 50%; position: absolute; box-sizing: content-box; border: 4px solid rgba(76, 175, 80, .5); }
        .icon-fix { top: 8px; width: 5px; left: 26px; z-index: 1; height: 85px; position: absolute; transform: rotate(-45deg); background-color: #FFFFFF; }
        
        @keyframes rotate-circle { 0% { transform: rotate(-45deg); } 5% { transform: rotate(-45deg); } 12% { transform: rotate(-405deg); } 100% { transform: rotate(-405deg); } }
        @keyframes icon-line-tip { 0% { width: 0; left: 1px; top: 19px; } 54% { width: 0; left: 1px; top: 19px; } 70% { width: 50px; left: -8px; top: 37px; } 84% { width: 17px; left: 21px; top: 48px; } 100% { width: 25px; left: 14px; top: 46px; } }
        @keyframes icon-line-long { 0% { width: 0; right: 46px; top: 54px; } 65% { width: 0; right: 46px; top: 54px; } 84% { width: 55px; right: 0px; top: 35px; } 100% { width: 47px; right: 8px; top: 38px; } }
        
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .card { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="max-w-xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden card border border-slate-100 relative">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-green-400 to-blue-500"></div>

        <div class="p-8 text-center">
            <div class="success-checkmark mb-6">
                <div class="check-icon">
                    <span class="icon-line line-tip"></span>
                    <span class="icon-line line-long"></span>
                    <div class="icon-circle"></div>
                    <div class="icon-fix"></div>
                </div>
            </div>

            <h1 class="text-3xl font-extrabold text-slate-800 mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-slate-500 mb-8">Data Anda telah tersimpan di sistem kami.</p>

            <div class="bg-slate-50 rounded-xl p-6 border border-slate-200 text-left mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-2 -mr-2 bg-blue-100 text-blue-600 px-4 py-2 rounded-bl-xl font-bold text-xs">
                    BUKTI REGISTRASI
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                        <span class="text-slate-500 text-sm">No. Pendaftaran</span>
                        <span class="font-mono font-bold text-lg text-blue-600"><?= $pendaftar['no_pendaftaran'] ?></span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                        <span class="text-slate-500 text-sm">Nama Lengkap</span>
                        <span class="font-bold text-slate-800 text-right"><?= $pendaftar['nama_lengkap'] ?></span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                        <span class="text-slate-500 text-sm">Jurusan</span>
                        <span class="font-bold text-slate-800 text-right"><?= $pendaftar['jurusan_minat'] ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 text-sm">Tanggal Daftar</span>
                        <span class="font-medium text-slate-800"><?= date('d M Y - H:i', strtotime($pendaftar['tgl_daftar'])) ?></span>
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-3 p-4 bg-green-50 rounded-lg border border-green-100 mb-8 text-left">
                <i class="fab fa-whatsapp text-2xl text-green-500 mt-1"></i>
                <div>
                    <h4 class="font-bold text-green-800 text-sm">Notifikasi Terkirim</h4>
                    <p class="text-xs text-green-700 mt-1">Detail pendaftaran juga telah dikirim ke nomor WhatsApp Anda. Silakan cek inbox Anda.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 no-print">
                <a href="<?= base_url() ?>" class="py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-home"></i> Beranda
                </a>
<a href="<?= base_url('spmb/cetak/'.$pendaftar['id']) ?>" target="_blank" class="py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition text-sm flex items-center justify-center gap-2">
    <i class="fas fa-print"></i> Cetak Formulir
</a>
            </div>
        </div>
        
        <div class="bg-slate-50 px-8 py-4 text-center text-xs text-slate-400 border-t border-slate-100">
            Simpan halaman ini sebagai bukti pendaftaran yang sah.
        </div>
    </div>

</body>
</html>