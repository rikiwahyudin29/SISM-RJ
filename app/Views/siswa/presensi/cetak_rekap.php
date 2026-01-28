<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi - <?= $siswa['nama_lengkap'] ?></title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        h1 { font-size: 18px; margin: 0; text-transform: uppercase; }
        h2 { font-size: 14px; margin: 5px 0; }
        .meta { margin-bottom: 20px; }
        .meta table { width: 100%; border: none; }
        .meta td { padding: 2px; border: none; }
        
        .tabel-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabel-data th, .tabel-data td { border: 1px solid #000; padding: 6px; text-align: center; }
        .tabel-data th { background-color: #eee; }
        .bg-grey { background-color: #f9f9f9; }
        
        .footer { margin-top: 40px; text-align: right; }
        .sign { display: inline-block; width: 200px; text-align: center; }
        .sign-name { margin-top: 70px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>LAPORAN KEHADIRAN SISWA</h1>
        <h2><?= $sekolah['nama'] ?></h2>
        <p><?= $sekolah['alamat'] ?></p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="100">Nama Siswa</td>
                <td width="10">:</td>
                <td><b><?= $siswa['nama_lengkap'] ?></b></td>
                <td width="100">Bulan</td>
                <td width="10">:</td>
                <td><?= date('F Y', strtotime($bulan)) ?></td>
            </tr>
            <tr>
                <td>NIS / NISN</td>
                <td>:</td>
                <td><?= $siswa['nis'] ?></td>
                <td>Kelas</td>
                <td>:</td>
                <td><?= $siswa['nama_kelas'] ?></td>
            </tr>
        </table>
    </div>

    <table class="tabel-data" style="width: 50%; margin-bottom: 20px;">
        <tr>
            <th>Hadir</th>
            <th>Terlambat</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpha</th>
        </tr>
        <tr>
            <td><?= $total['H'] ?></td>
            <td><?= $total['T'] ?></td>
            <td><?= $total['S'] ?></td>
            <td><?= $total['I'] ?></td>
            <td><?= $total['A'] ?></td>
        </tr>
    </table>

    <table class="tabel-data">
        <thead>
            <tr>
                <th width="50">Tanggal</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            <?php for($d=1; $d<=$jml_hari; $d++): 
                $tglStr = date('Y-m-', strtotime($bulan)) . sprintf('%02d', $d);
                $hari = date('l', strtotime($tglStr));
                $status = $map[$d] ?? '-';
                $bg = ($status == '-') ? '' : 'bg-grey';
            ?>
            <tr class="<?= $bg ?>">
                <td><?= $d ?></td>
                <td><?= $hari ?></td>
                <td><?= ($status != '-' && $status != 'Alpha') ? 'Terdata' : '-' ?></td>
                <td><b><?= $status ?></b></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="sign">
            <p>Diketahui Orang Tua/Wali,</p>
            <div class="sign-name">( ........................... )</div>
        </div>
    </div>

</body>
</html>