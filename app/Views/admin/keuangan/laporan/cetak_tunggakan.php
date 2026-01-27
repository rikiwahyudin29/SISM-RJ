<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #eee; }
        .text-right { text-align: right; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2 style="margin:0;"><?= $sekolah['nama'] ?></h2>
        <p style="margin:5px 0;"><?= $sekolah['alamat'] ?></p>
        <h3 style="margin:10px 0;">REKAPITULASI TAGIHAN & TUNGGAKAN</h3>
        <p>Per Tanggal: <?= date('d F Y') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Pembayaran</th>
                <th>Tahun</th>
                <th>Total Siswa</th>
                <th>Sudah Lunas (Org)</th>
                <th>Belum Lunas (Org)</th>
                <th>Uang Masuk (Rp)</th>
                <th>Tunggakan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($rekap as $r): ?>
            <tr>
                <td style="text-align:center;"><?= $no++ ?></td>
                <td><?= $r['nama_pos'] ?></td>
                <td style="text-align:center;"><?= $r['tahun_ajaran'] ?></td>
                <td style="text-align:center;"><?= $r['total_siswa'] ?></td>
                <td style="text-align:center;"><?= $r['qty_lunas'] ?></td>
                <td style="text-align:center; color:red; font-weight:bold;"><?= $r['qty_belum'] ?></td>
                <td class="text-right"><?= number_format($r['total_bayar'], 0, ',', '.') ?></td>
                <td class="text-right" style="color:red;"><?= number_format($r['total_tunggakan'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada: <?= date('d F Y H:i') ?></p>
        <br><br><br>
        <p>( Bendahara Sekolah )</p>
    </div>
</body>
</html>