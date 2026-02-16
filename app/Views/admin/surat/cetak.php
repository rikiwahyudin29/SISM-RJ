<?php
// 1. KONEKSI LANGSUNG KE DATABASE UNTUK AMBIL IDENTITAS TERBARU
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// 2. SETUP PATH GAMBAR
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default_logo.png');
$pathKop  = 'uploads/identitas/' . ($sekolah['kop_surat'] ?? 'default_kop.png');

// Cek apakah file fisik kop surat ada di folder
$pakaiKopGambar = !empty($sekolah['kop_surat']) && file_exists(FCPATH . $pathKop) && $sekolah['kop_surat'] != 'default_kop.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat - <?= $surat['no_surat'] ?></title>
    <style>
        /* RESET & BASE STYLE SURAT DINAS */
        body { font-family: 'Times New Roman', Times, serif; padding: 30px; color: #000; background: #fff; line-height: 1.4; }
        
        /* CONTAINER KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        
        /* LAYOUT KOP MANUAL (LOGO + TEKS) */
        .kop-manual { display: table; width: 100%; border: none; }
        .kop-logo { display: table-cell; width: 15%; text-align: center; vertical-align: middle; }
        .kop-teks { display: table-cell; width: 85%; text-align: center; vertical-align: middle; }
        .kop-teks h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-teks h2 { margin: 0; font-size: 18pt; font-weight: bold; text-transform: uppercase; }
        .kop-teks p { margin: 2px 0; font-size: 10pt; font-style: italic; }

        /* KONTEN SURAT */
        .surat-title { text-align: center; margin-bottom: 30px; margin-top: 10px; }
        .surat-title h3 { text-decoration: underline; margin-bottom: 5px; text-transform: uppercase; font-size: 14pt; }
        .surat-title span { font-size: 12pt; }
        
        .content { font-size: 12pt; text-align: justify; min-height: 300px; margin-bottom: 40px; }
        
        /* AREA TANDA TANGAN */
        .ttd-area { float: right; width: 45%; text-align: center; page-break-inside: avoid; }
        .ttd-area p { margin: 0; font-size: 12pt; }

        @media print {
            @page { size: A4; margin: 1.5cm; }
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header-container">
        <?php if ($pakaiKopGambar): ?>
            <img src="<?= base_url($pathKop) ?>" style="width: 100%; height: auto; display: block;">
        <?php else: ?>
            <div class="kop-manual">
                <div class="kop-logo">
                    <img src="<?= base_url($pathLogo) ?>" style="width: 85px; height: auto;">
                </div>
                <div class="kop-teks">
                    <h3>PEMERINTAH PROVINSI <?= strtoupper($sekolah['provinsi'] ?? 'JAWA BARAT') ?></h3>
                    <h2><?= strtoupper($sekolah['nama_sekolah']) ?></h2>
                    <p>
                        <?= $sekolah['alamat'] ?> <br>
                        Email: <?= $sekolah['email'] ?> | Website: <?= $sekolah['website'] ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="surat-title">
        <h3><?= $surat['perihal'] ?></h3>
        <span>Nomor: <?= $surat['no_surat'] ?></span>
    </div>

    <div class="content">
        <?= $surat['isi_final'] ?>
    </div>

    <div class="ttd-area">
        <p>Ditetapkan di: <?= $sekolah['kabupaten'] ?></p>
        <p>Pada Tanggal: <?= date('d F Y', strtotime($surat['tgl_surat'])) ?></p>
        <br>
        <p>Kepala Sekolah,</p>
        
        <?php if(isset($qr_link)): ?>
            <img src="<?= $qr_link ?>" width="95" style="margin: 8px 0;">
            <p style="font-size: 8pt; color: #555;"><i>Dokumen ini ditandatangani secara elektronik <br> Sah sesuai database sistem.</i></p>
        <?php else: ?>
            <div style="height: 100px;"></div>
        <?php endif; ?>
        
        <p style="margin-top: 10px;">
            <strong><u><?= strtoupper($sekolah['nama_kepsek'] ?? $sekolah['kepala_sekolah']) ?></u></strong><br>
            NIP. <?= $sekolah['nip_kepsek'] ?>
        </p>
    </div>

    <div style="clear: both;"></div>

</body>
</html>