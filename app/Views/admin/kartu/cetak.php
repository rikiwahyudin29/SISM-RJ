<!DOCTYPE html>
<html>
<head>
    <title>Cetak Kartu Pelajar - Kelas <?= $kelas->nama_kelas ?></title>
    <style>
        body { font-family: sans-serif; background: #e2e8f0; -webkit-print-color-adjust: exact; }
        .page-container { display: flex; flex-wrap: wrap; gap: 20px; padding: 20px; justify-content: center; }
        
        /* Ukuran Kartu ID Standar (CR80) */
        .card {
            width: 323px; height: 204px; /* Pixel approx for 85.6mm x 54mm */
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            color: white;
            border: 1px solid #ccc;
        }

        /* Desain Background Abstrak */
        .card::before {
            content: ''; position: absolute; top: -50px; right: -50px;
            width: 200px; height: 200px; background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .card::after {
            content: ''; position: absolute; bottom: -30px; left: -30px;
            width: 150px; height: 150px; background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .header { padding: 15px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.2); }
        .logo-placeholder { width: 35px; height: 35px; background: white; border-radius: 50%; }
        .school-name { font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .card-title { font-size: 9px; opacity: 0.8; }

        .content { display: flex; padding: 15px; gap: 15px; align-items: center; }
        .photo-box { width: 70px; height: 85px; background: #fff; border-radius: 6px; border: 2px solid rgba(255,255,255,0.5); object-fit: cover;}
        
        .info { flex: 1; }
        .name { font-size: 16px; font-weight: bold; margin-bottom: 4px; text-transform: uppercase; line-height: 1.1; }
        .nis { font-size: 12px; font-family: monospace; background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 4px; display: inline-block; margin-bottom: 8px; }
        .detail { font-size: 10px; opacity: 0.9; margin-bottom: 2px; }

        .qr-area { position: absolute; bottom: 15px; right: 15px; background: white; padding: 4px; border-radius: 6px; }
        .qr-img { width: 50px; height: 50px; display: block; }

        @media print {
            body { background: white; margin: 0; }
            .page-container { padding: 0; gap: 10px; }
            .card { break-inside: avoid; page-break-inside: avoid; border: 1px solid #ddd; box-shadow: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="page-container">
        <?php foreach($siswa as $s): ?>
            <div class="card">
                <div class="header">
                    <div class="logo-placeholder"></div> 
                    <div>
                        <div class="school-name"><?= $sekolah['nama'] ?></div>
                        <div class="card-title">KARTU TANDA PELAJAR</div>
                    </div>
                </div>
                
                <div class="content">
                    <?php $foto = $s['foto'] ?? 'https://ui-avatars.com/api/?name='.urlencode($s['nama_lengkap']).'&background=random'; ?>
                    <img src="<?= $foto ?>" class="photo-box">
                    
                    <div class="info">
                        <div class="name"><?= substr($s['nama_lengkap'], 0, 20) ?></div>
                        <div class="nis"><?= $s['nis'] ?></div>
                        <div class="detail">Kelas: <?= $kelas->nama_kelas ?></div>
                        <div class="detail">L/P: <?= $s['jenis_kelamin'] ?? '-' ?></div>
                        <div class="detail">Berlaku s/d: <?= date('Y') + 3 ?></div>
                    </div>
                </div>

                <div class="qr-area">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $s['nis'] ?>" class="qr-img">
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>