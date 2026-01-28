<!DOCTYPE html>
<html>
<head>
    <title>Laporan Piket Harian</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        p { margin: 5px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>LAPORAN HARIAN PIKET</h1>
        <h1><?= $sekolah['nama'] ?></h1>
        <p>Tanggal: <?= date('d F Y', strtotime($tanggal)) ?></p>
    </div>

    <p>Berikut adalah daftar siswa yang tidak hadir / terlambat pada hari ini:</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Kelas</th>
                <th style="width: 30%">Nama Siswa</th>
                <th style="width: 15%">Status</th>
                <th style="width: 35%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        <i>Nihil (Semua Siswa Hadir Tepat Waktu)</i>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($data as $i => $d): ?>
                <tr>
                    <td style="text-align: center;"><?= $i + 1 ?></td>
                    <td><?= $d['nama_kelas'] ?></td>
                    <td><b><?= $d['nama_lengkap'] ?></b></td>
                    <td><?= $d['status_kehadiran'] ?></td>
                    <td>
                        <?= $d['keterangan'] ?>
                        <?php if($d['jam_masuk']) echo "(Jam Masuk: {$d['jam_masuk']})"; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d-m-Y H:i') ?></p>
        <br><br><br>
        <p>( Guru Piket )</p>
    </div>

</body>
</html>