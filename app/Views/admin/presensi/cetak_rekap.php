<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi - <?= $kelas->nama_kelas ?></title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 11px; -webkit-print-color-adjust: exact; }
        
        .header { text-align: center; margin-bottom: 20px; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header h2 { margin: 5px 0; font-size: 14px; }
        
        .meta { margin-bottom: 15px; font-weight: bold; display: flex; justify-content: space-between; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 3px; text-align: center; vertical-align: middle; }
        
        th { background-color: #eee; font-weight: bold; height: 30px; }
        .text-left { text-align: left; padding-left: 5px; }
        
        /* Warna Status untuk Cetak */
        .bg-a { background-color: #ffcccc !important; } /* Merah Muda */
        .bg-s { background-color: #ffebcc !important; } /* Orange Muda */
        .bg-i { background-color: #ccf2ff !important; } /* Biru Muda */
        .bg-t { background-color: #ffffcc !important; } /* Kuning Muda */
        
        .footer { margin-top: 30px; display: flex; justify-content: space-between; text-align: center; }
        .sign-box { width: 200px; }
        .sign-name { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>REKAPITULASI KEHADIRAN SISWA</h1>
        <h2><?= $sekolah['nama'] ?></h2>
    </div>

    <div class="meta">
        <span>Kelas: <?= $kelas->nama_kelas ?></span>
        <span>Bulan: <?= date('F Y', strtotime($bulan)) ?></span>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="30">No</th>
                <th rowspan="2">Nama Siswa</th>
                <th colspan="<?= $jml_hari ?>">Tanggal</th>
                <th colspan="5">Total</th>
                <th rowspan="2">%</th>
            </tr>
            <tr>
                <?php for($d=1; $d<=$jml_hari; $d++): ?>
                    <th width="18"><?= $d ?></th>
                <?php endfor; ?>
                <th width="25">H</th>
                <th width="25">S</th>
                <th width="25">I</th>
                <th width="25">A</th>
                <th width="25">T</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data_rekap as $i => $s): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td class="text-left">
                    <b><?= strtoupper($s['nama']) ?></b><br>
                    <span style="font-size: 9px; color: #555;"><?= $s['nis'] ?></span>
                </td>
                
                <?php for($d=1; $d<=$jml_hari; $d++): ?>
                    <?php 
                        $st = $s['harian'][$d];
                        $class = ''; $text = '';
                        if($st == 'Alpha') { $class = 'bg-a'; $text = 'A'; }
                        elseif($st == 'Sakit') { $class = 'bg-s'; $text = 'S'; }
                        elseif($st == 'Izin') { $class = 'bg-i'; $text = 'I'; }
                        elseif($st == 'Terlambat') { $class = 'bg-t'; $text = 'T'; }
                        elseif($st == 'Hadir') { $text = '•'; }
                        else { $text = ''; }
                    ?>
                    <td class="<?= $class ?>"><?= $text ?></td>
                <?php endfor; ?>

                <td><?= $s['total']['H'] ?></td>
                <td><?= $s['total']['S'] ?></td>
                <td><?= $s['total']['I'] ?></td>
                <td><?= $s['total']['A'] ?></td>
                <td><?= $s['total']['T'] ?></td>
                <td><b><?= $s['persen'] ?>%</b></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="font-size: 10px; margin-bottom: 20px;">
        <b>Keterangan:</b> A: Alpha | S: Sakit | I: Izin | T: Terlambat | •: Hadir
    </div>

    <div class="footer">
        <div class="sign-box">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <div class="sign-name">( ........................... )</div>
        </div>
        <div class="sign-box">
            <p><?= date('d F Y') ?><br>Wali Kelas</p>
            <div class="sign-name">( ........................... )</div>
        </div>
    </div>

</body>
</html>