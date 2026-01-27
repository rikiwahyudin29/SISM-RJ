<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $trx['kode_transaksi'] ?></title>
    <style>
        body { font-family: sans-serif; padding: 40px; }
        .header { border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; }
        .title { font-size: 24px; font-weight: bold; color: #333; }
        .sub-title { font-size: 14px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
        .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 20px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #888; }
        @media print { @page { size: A4; margin: 0; } body { padding: 30px; } }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <div>
            <div class="title"><?= $sekolah['nama'] ?></div>
            <div class="sub-title"><?= $sekolah['alamat'] ?><br>Telp: <?= $sekolah['telp'] ?></div>
        </div>
        <div style="text-align: right;">
            <div class="title" style="color:#000;">BUKTI PEMBAYARAN</div>
            <div class="sub-title">No: <?= $trx['kode_transaksi'] ?><br>Tgl: <?= date('d/m/Y H:i', strtotime($trx['tanggal_bayar'])) ?></div>
        </div>
    </div>

    <table>
        <tr>
            <td width="150" style="background:#f9f9f9;"><b>Siswa</b></td>
            <td><?= $trx['nama_lengkap'] ?> (<?= $trx['nis'] ?>) - <?= $trx['nama_kelas'] ?></td>
        </tr>
        <tr>
            <td style="background:#f9f9f9;"><b>Penerima</b></td>
            <td><?= $trx['nama_petugas'] ?? 'Administrator' ?></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Keterangan Pembayaran</th>
                <th style="text-align:right;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <b><?= $trx['nama_pos'] ?></b><br>
                    <small><?= $trx['ket_tagihan'] ?></small>
                </td>
                <td style="text-align:right;">Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        TOTAL DIBAYAR: Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?>
    </div>

    <div style="margin-top: 40px; display: flex; justify-content: space-between;">
        <div style="text-align: center;">
            <br>Penyetor<br><br><br><br>
            ( ........................ )
        </div>
        <div style="text-align: center;">
            Jakarta, <?= date('d F Y') ?><br>Penerima<br><br><br><br>
            ( <?= $trx['nama_petugas'] ?? 'Admin' ?> )
        </div>
    </div>

    <div class="footer">
        Dicetak pada <?= date('d-m-Y H:i:s') ?> oleh Sistem Keuangan Sekolah.
    </div>

</body>
</html>