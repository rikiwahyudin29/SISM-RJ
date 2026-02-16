<?php
// 1. TARIK DATA SEKOLAH (Sama seperti A4)
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// Cek Logo untuk Thermal
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default.png');
$showLogo = !empty($sekolah['logo']) && file_exists(FCPATH . $pathLogo);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= $trx['kode_transaksi'] ?></title>
    <style>
        /* Reset CSS untuk Printer Thermal */
        * { box-sizing: border-box; }
        body { 
            font-family: 'Courier New', monospace; 
            font-size: 12px; 
            margin: 0; 
            padding: 5px; 
            width: 72mm; /* Ukuran standar kertas thermal 80mm (dengan margin) */
            color: #000;
        }
        
        .center { text-align: center; }
        .bold { font-weight: bold; }
        
        /* HEADER */
        .header { margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px dashed #000; }
        .logo-thermal { width: 40px; height: auto; margin-bottom: 5px; filter: grayscale(100%); } /* Logo Hitam Putih */
        .nama-sekolah { font-size: 14px; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
        .alamat-sekolah { font-size: 10px; margin-top: 2px; }
        
        /* INFO TRANSAKSI */
        .info-group { margin: 5px 0; font-size: 11px; }
        .row { display: flex; justify-content: space-between; }
        
        /* ITEM & TOTAL */
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .item-name { font-weight: bold; margin-bottom: 2px; }
        .total-area { font-size: 14px; font-weight: bold; margin-top: 5px; }
        
        /* FOOTER */
        .footer { margin-top: 15px; font-size: 10px; text-align: center; }

        @media print {
            @page { margin: 0; size: auto; }
            body { margin: 0; padding: 5px; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header center">
        <?php if($showLogo): ?>
            <img src="<?= base_url($pathLogo) ?>" class="logo-thermal">
        <?php endif; ?>
        
        <div class="nama-sekolah"><?= $sekolah['nama_sekolah'] ?></div>
        <div class="alamat-sekolah">
            <?= $sekolah['alamat'] ?><br>
            <?= $sekolah['kabupaten'] ?>
        </div>
    </div>

    <div class="info-group">
        <div class="row">
            <span>No. Bukti</span> 
            <span>#<?= $trx['kode_transaksi'] ?></span>
        </div>
        <div class="row">
            <span>Tanggal</span> 
            <span><?= date('d/m/y H:i', strtotime($trx['tanggal_bayar'])) ?></span>
        </div>
        <div class="row">
            <span>Kasir</span> 
            <span><?= substr($trx['nama_petugas'] ?? 'Admin', 0, 12) ?></span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="info-group">
        <div class="bold">Siswa:</div>
        <div><?= strtoupper($trx['nama_lengkap']) ?></div>
        <div class="row">
            <span>NIS: <?= $trx['nis'] ?></span>
            <span>Kls: <?= $trx['nama_kelas'] ?></span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="info-group">
        <div class="item-name"><?= $trx['nama_pos'] ?></div>
        <div style="font-size: 10px; margin-bottom: 2px;">Tagihan: <?= $trx['ket_tagihan'] ?></div>
        
        <div class="row bold">
            <span>Nominal</span>
            <span>Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?></span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row total-area">
        <span>TOTAL BAYAR</span>
        <span>Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?></span>
    </div>
    
    <div class="center" style="margin-top: 5px; font-size: 10px;">
        (Tunai / Lunas)
    </div>

    <div class="footer">
        Terima Kasih.<br>
        Simpan struk sebagai bukti sah.<br>
        -- SIAKAD SYSTEM --
    </div>

</body>
</html>