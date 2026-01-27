<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background: #ddd; }
        
        /* Kotak Saldo */
        .summary-box { margin-top: 30px; border: 1px solid #000; padding: 10px; width: 40%; float: right; }
        .clear { clear: both; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2 style="margin:0;"><?= $sekolah['nama'] ?></h2>
        <p style="margin:5px 0;"><?= $sekolah['alamat'] ?></p>
        <h3 style="margin:10px 0;">LAPORAN KEUANGAN (REALISASI)</h3>
        <p>Periode: <?= date('d/m/Y', strtotime($start)) ?> s/d <?= date('d/m/Y', strtotime($end)) ?></p>
    </div>

    <div class="section-title">A. Pemasukan (Uang Masuk)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Keterangan</th>
                <th width="15%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $total_masuk = 0;
            if(empty($transaksi)): ?>
                <tr><td colspan="6" class="text-center">Tidak ada pemasukan pada periode ini.</td></tr>
            <?php else: ?>
                <?php foreach($transaksi as $t): 
                    $total_masuk += $t['jumlah_bayar'];
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d/m/y', strtotime($t['created_at'])) ?></td>
                    <td><?= $t['nama_lengkap'] ?></td>
                    <td><?= $t['nama_kelas'] ?></td>
                    <td><?= $t['nama_pos'] ?> (<?= $t['keterangan'] ?>)</td>
                    <td class="text-right">Rp <?= number_format($t['jumlah_bayar'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="5" class="text-center">TOTAL PEMASUKAN</td>
                <td class="text-right">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">B. Pengeluaran (Operasional)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Divisi</th>
                <th>Jenis</th>
                <th>Keperluan</th>
                <th width="15%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $total_keluar = 0;
            if(empty($pengeluaran)): ?>
                <tr><td colspan="6" class="text-center">Tidak ada pengeluaran pada periode ini.</td></tr>
            <?php else: ?>
                <?php foreach($pengeluaran as $p): 
                    $total_keluar += $p['nominal'];
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d/m/y', strtotime($p['tanggal'])) ?></td>
                    <td><?= $p['nama_divisi'] ?></td>
                    <td><?= $p['nama_jenis'] ?></td>
                    <td><?= $p['judul_pengeluaran'] ?></td>
                    <td class="text-right">Rp <?= number_format($p['nominal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="5" class="text-center">TOTAL PENGELUARAN</td>
                <td class="text-right">Rp <?= number_format($total_keluar, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="summary-box">
        <table style="border:none; margin:0;">
            <tr>
                <td style="border:none;">Total Pemasukan</td>
                <td style="border:none;" class="text-right">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border:none;">Total Pengeluaran</td>
                <td style="border:none; color:red;" class="text-right">- Rp <?= number_format($total_keluar, 0, ',', '.') ?></td>
            </tr>
            <tr style="font-weight:bold; font-size:14px; border-top:1px solid #000;">
                <td style="border:none; padding-top:10px;">SALDO AKHIR</td>
                <td style="border:none; padding-top:10px;" class="text-right">Rp <?= number_format($total_masuk - $total_keluar, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    
    <div style="margin-top: 40px; text-align: right;">
        <p>Dicetak pada: <?= date('d F Y H:i') ?></p>
        <br><br><br>
        <p>( Bendahara Sekolah )</p>
    </div>
</body>
</html>