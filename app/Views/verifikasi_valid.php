<?php
// 1. TARIK DATA SEKOLAH DINAMIS
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// Setup Logo (Fallback ke default jika kosong)
$pathLogo = !empty($sekolah['logo']) ? base_url('uploads/identitas/' . $sekolah['logo']) : base_url('assets/img/logo.svg');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen Resmi - <?= $sekolah['nama_sekolah'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="mb-6 text-center animate-fade-in">
        <img src="<?= $pathLogo ?>" class="h-16 w-16 mx-auto mb-3 object-contain drop-shadow-md" alt="Logo Sekolah">
        <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight"><?= $sekolah['nama_sekolah'] ?></h2>
        <p class="text-[10px] text-slate-500 font-bold tracking-widest uppercase opacity-70"><?= $sekolah['kabupaten'] ?> • <?= $sekolah['provinsi'] ?></p>
    </div>

    <div class="bg-white max-w-lg w-full rounded-3xl shadow-2xl overflow-hidden border border-gray-200">
        <div class="bg-emerald-500 p-6 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="w-16 h-16 bg-white text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg animate-bounce">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h1 class="text-2xl font-black tracking-tight uppercase">Dokumen Valid</h1>
            <p class="text-emerald-100 text-sm font-medium">Terverifikasi di Database SIAKAD</p>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Jenis Dokumen</p>
                    <h2 class="text-lg font-bold text-gray-800 leading-tight"><?= $surat->perihal ?></h2>
                    <p class="text-xs text-gray-500 mt-1 font-mono bg-gray-100 inline-block px-2 py-0.5 rounded">No: <?= $surat->no_surat ?></p>
                </div>

                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-[10px] uppercase font-bold text-blue-400 tracking-wider mb-2">Diberikan Kepada:</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                            <?= substr($surat->nama_siswa ?? $surat->tujuan ?? 'U', 0, 1) ?>
                        </div>
                        <div>
                            <?php if(!empty($surat->nama_siswa)): ?>
                                <p class="font-bold text-gray-800"><?= $surat->nama_siswa ?></p>
                                <p class="text-xs text-gray-500"><?= $surat->nama_kelas ?> • NIS: <?= $surat->nis ?></p>
                            <?php else: ?>
                                <p class="font-bold text-gray-800"><?= $surat->tujuan ?></p>
                                <p class="text-xs text-gray-500">Pihak Eksternal / Instansi</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Tanggal Terbit</p>
                        <p class="font-bold text-sm text-gray-700"><?= date('d F Y', strtotime($surat->tgl_surat)) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Penanda Tangan</p>
                        <p class="font-bold text-sm text-emerald-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <?= $surat->nama_kepsek ?>
                        </p>
                        <p class="text-[10px] text-gray-400">NIP: <?= $surat->nip ?></p>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <a href="<?= base_url('verifikasi/download/' . $surat->token_validasi) ?>" target="_blank" class="flex items-center justify-center w-full py-4 bg-gray-900 text-white font-bold rounded-2xl shadow-xl hover:bg-black hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12"></path></svg>
                    DOWNLOAD FILE ASLI (PDF)
                </a>
                <p class="text-center text-[10px] text-gray-400 mt-4 leading-relaxed font-medium">
                    © <?= date('Y') ?> <?= $sekolah['nama_sekolah'] ?>. <br> Dokumen ini dihasilkan secara otomatis oleh sistem informasi sekolah.
                </p>
            </div>
        </div>
    </div>

</body>
</html>