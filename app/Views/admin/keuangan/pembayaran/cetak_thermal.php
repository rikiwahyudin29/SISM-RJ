<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= $trx['kode_transaksi'] ?></title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; margin: 0; padding: 10px; width: 300px; }
        .header { text-align: center; margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 5px; }
        .judul { font-size: 14px; font-weight: bold; }
        .sub-judul { font-size: 10px; }
        .info { margin-bottom: 5px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .total { border-top: 1px dashed #000; margin-top: 10px; padding-top: 5px; font-weight: bold; font-size: 14px; }
        .footer { text-align: center; margin-top: 15px; font-size: 10px; }
        
        @media print {
            @page { margin: 0; }
            body { margin: 0; padding: 10px; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <div class="judul"><?= $sekolah['nama'] ?></div>
        <div class="sub-judul"><?= $sekolah['alamat'] ?></div>
    </div>

    <div class="info">
        <div class="row"><span>No. Bukti</span> <span><?= $trx['kode_transaksi'] ?></span></div>
        <div class="row"><span>Tanggal</span> <span><?= date('d/m/y H:i', strtotime($trx['tanggal_bayar'])) ?></span></div>
        <div class="row"><span>Kasir</span> <span><?= substr($trx['nama_petugas'] ?? 'Admin', 0, 10) ?></span></div>
    </div>

    <div style="border-bottom: 1px dashed #000; margin: 5px 0;"></div>

    <div class="info">
        <div class="row"><span>Siswa</span> <span><?= substr($trx['nama_lengkap'], 0, 15) ?></span></div>
        <div class="row"><span>NIS/Kls</span> <span><?= $trx['nis'] ?> / <?= $trx['nama_kelas'] ?></span></div>
    </div>

    <div style="border-bottom: 1px dashed #000; margin: 5px 0;"></div>

    <div class="item">
        <div style="font-weight:bold;"><?= $trx['nama_pos'] ?> - <?= $trx['ket_tagihan'] ?></div>
        <div class="row">
            <span>Bayar Tunai</span>
            <span>Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?></span>
        </div>
    </div>

    <div class="total row">
        <span>TOTAL</span>
        <span>Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?></span>
    </div>

    <div class="footer">
        <p>Terima Kasih.<br>Simpan struk ini sebagai bukti pembayaran yang sah.</p>
    </div>

</body>
</html>