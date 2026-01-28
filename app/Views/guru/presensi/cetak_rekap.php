<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Guru</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        h1 { font-size: 18px; margin: 0; }
        
        .meta table { width: 100%; margin-bottom: 10px; }
        .tabel-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabel-data th, .tabel-data td { border: 1px solid #000; padding: 5px; text-align: center; }
        .tabel-data th { background-color: #eee; }
        
        .footer { margin-top: 40px; text-align: right; }
        .sign-name { margin-top: 70px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>LAPORAN KEHADIRAN GURU</h1>
        <p><?= $sekolah['nama'] ?></p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="100">Nama Guru</td>
                <td width="10">:</td>
                <td><b><?= $nama_guru ?></b></td>
            </tr>
            <tr>
                <td>NIP / NUPTK</td>
                <td>:</td>
                <td><?= $guru['nip'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Bulan</td>
                <td>:</td>
                <td><?= date('F Y', strtotime($bulan)) ?></td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 15px;">
        <b>Ringkasan:</b> 
        Hadir: <?= $total['H'] ?> | Sakit: <?= $total['S'] ?> | Izin: <?= $total['I'] ?> | Alpha: <?= $total['A'] ?>
    </div>

    <table class="tabel-data">
        <thead>
            <tr>
                <th width="30">Tgl</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php for($d=1; $d<=$jml_hari; $d++): 
                $tglStr = date('Y-m-', strtotime($bulan)) . sprintf('%02d', $d);
                $hari = date('l', strtotime($tglStr));
                $st = $map[$d] ?? '-';
            ?>
            <tr>
                <td><?= $d ?></td>
                <td><?= $hari ?></td>
                <td><?= ($st!='-') ? '✔' : '-' ?></td>
                <td><?= ($st!='-') ? '✔' : '-' ?></td>
                <td><b><?= $st ?></b></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <div class="footer">
        <div style="display:inline-block; width:200px; text-align:center;">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <div class="sign-name"><?= $sekolah['kepsek'] ?></div>
        </div>
    </div>
</body>
</html>