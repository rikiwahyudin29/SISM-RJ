<?php
// 1. TARIK DATA SEKOLAH (Agar Logo & Nama Sekolah Dinamis)
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// Setup Path Logo
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default.png');
// Gunakan default jika file tidak ada
if (!file_exists(FCPATH . $pathLogo) || empty($sekolah['logo'])) {
    $pathLogo = 'assets/img/logo.svg'; // Pastikan ada logo default
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Kartu Pelajar - <?= $kelas->nama_kelas ?></title>
    <style>
        /* RESET & BASE STYLE */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f1f5f9; -webkit-print-color-adjust: exact; margin: 0; padding: 20px; }
        
        .page-container { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 15px; 
            justify-content: center; 
        }
        
        /* TOMBOL PRINT (HANYA TAMPIL DI LAYAR) */
        .no-print { text-align: center; margin-bottom: 20px; width: 100%; }
        .btn-print { padding: 10px 20px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }

        /* --- DESAIN KARTU PELAJAR (Standar CR80: 85.6mm x 54mm) --- */
        /* Kita pakai pixel pendekatan: 324px x 204px */
        .card {
            width: 324px; 
            height: 204px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); /* Biru Keren */
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            color: white;
            border: 1px solid #ccc;
            page-break-inside: avoid; /* Mencegah kartu terpotong saat print */
        }

        /* Elemen Dekoratif Background (Lingkaran Transparan) */
        .card::before {
            content: ''; position: absolute; top: -60px; right: -60px;
            width: 180px; height: 180px; background: rgba(255,255,255,0.1);
            border-radius: 50%; pointer-events: none;
        }
        .card::after {
            content: ''; position: absolute; bottom: -40px; left: -40px;
            width: 140px; height: 140px; background: rgba(255,255,255,0.05);
            border-radius: 50%; pointer-events: none;
        }

        /* HEADER KARTU */
        .header { 
            padding: 10px 15px; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            border-bottom: 1px solid rgba(255,255,255,0.2); 
            position: relative; 
            z-index: 2; 
            background: rgba(0,0,0,0.1);
        }
        
        .logo-img { 
            width: 35px; height: 35px; 
            object-fit: contain; 
            background: #fff; 
            border-radius: 50%; 
            padding: 2px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .school-info { flex: 1; }
        .school-name { 
            font-size: 11px; font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            line-height: 1.1; 
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3); 
        }
        .card-type { 
            font-size: 8px; opacity: 0.9; 
            letter-spacing: 2px; margin-top: 2px; 
            font-weight: 600; text-transform: uppercase; 
        }

        /* CONTENT UTAMA */
        .content { 
            display: flex; 
            padding: 12px 15px; 
            gap: 12px; 
            align-items: flex-start; 
            position: relative; 
            z-index: 2; 
        }
        
        /* FOTO SISWA */
        .photo-frame {
            width: 70px; height: 90px; 
            background: #fff; 
            border-radius: 6px; 
            padding: 3px;
            box-shadow: 0 3px 5px rgba(0,0,0,0.2);
        }
        .photo-img { 
            width: 100%; height: 100%; 
            object-fit: cover; 
            border-radius: 4px; 
        }
        
        /* DATA SISWA */
        .student-info { flex: 1; }
        .s-name { 
            font-size: 13px; font-weight: 800; 
            text-transform: uppercase; 
            margin-bottom: 5px; 
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3); 
            line-height: 1.2;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px;
        }
        .s-nis { 
            font-family: 'Courier New', monospace; 
            background: rgba(0,0,0,0.25); 
            padding: 1px 6px; 
            border-radius: 4px; 
            font-size: 10px; 
            font-weight: bold; 
            display: inline-block; 
            margin-bottom: 6px; 
            border: 1px solid rgba(255,255,255,0.1); 
        }
        
        .meta-row { font-size: 9px; margin-bottom: 2px; display: flex; opacity: 0.95; }
        .meta-label { width: 45px; font-weight: 600; opacity: 0.8; }
        .meta-val { font-weight: 600; }

        /* QR CODE */
        .qr-box { 
            position: absolute; 
            bottom: 10px; right: 10px; 
            background: white; 
            padding: 2px; 
            border-radius: 4px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.2); 
            z-index: 2; 
        }
        .qr-code { width: 40px; height: 40px; display: block; }
        
        /* STRIP BAWAH */
        .footer-stripe {
            position: absolute; bottom: 0; left: 0; right: 0;
            height: 6px; background: rgba(252, 211, 77, 1); /* Warna Emas */
        }

        /* MODE PRINT */
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .no-print { display: none; }
            .page-container { gap: 10px; padding: 10px; }
            .card { border: 1px solid #ccc; box-shadow: none; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ CETAK KARTU</button>
        <div style="font-size: 12px; margin-top: 5px; color: #666;">*Pastikan setting "Background Graphics" dicentang saat print.</div>
    </div>

    <div class="page-container">
        <?php foreach($siswa as $s): ?>
            <div class="card">
                <div class="header">
                    <img src="<?= base_url($pathLogo) ?>" class="logo-img">
                    <div class="school-info">
                        <div class="school-name"><?= $sekolah['nama_sekolah'] ?></div>
                        <div class="card-type">KARTU TANDA PELAJAR</div>
                    </div>
                </div>
                
                <div class="content">
                    <div class="photo-frame">
                        <?php 
                            // Cek foto, kalau kosong pakai Avatar API
                            $foto = !empty($s['foto']) && file_exists(FCPATH . 'uploads/siswa/' . $s['foto']) 
                                    ? base_url('uploads/siswa/' . $s['foto']) 
                                    : 'https://ui-avatars.com/api/?name='.urlencode($s['nama_lengkap']).'&background=random&size=128'; 
                        ?>
                        <img src="<?= $foto ?>" class="photo-img">
                    </div>
                    
                    <div class="student-info">
                        <div class="s-name"><?= $s['nama_lengkap'] ?></div>
                        <div class="s-nis"><?= $s['nis'] ?></div>
                        
                        <div class="meta-row">
                            <span class="meta-label">Kelas</span>
                            <span class="meta-val">: <?= $kelas->nama_kelas ?></span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">L/P</span>
                            <span class="meta-val">: <?= $s['jenis_kelamin'] ?? '-' ?></span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Berlaku</span>
                            <span class="meta-val">: <?= date('Y') + 3 ?></span>
                        </div>
                    </div>
                </div>

                <div class="qr-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $s['nis'] ?>" class="qr-code">
                </div>

                <div class="footer-stripe"></div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>