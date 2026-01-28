<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi Guru</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        h1, h2 { margin: 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; }
        th { background: #eee; }
        .text-left { text-align: left; padding-left: 5px; }
        .bg-s { background: #ffebee; } .bg-i { background: #e3f2fd; } .bg-a { background: #ffebee; }
        .footer { margin-top: 40px; display: flex; justify-content: flex-end; text-align: center; }
        .sign { width: 200px; float: right; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>REKAPITULASI KEHADIRAN GURU & PEGAWAI</h1>
        <h2><?= $sekolah['nama'] ?></h2>
        <p>Bulan: <?= date('F Y', strtotime($bulan)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="30">No</th>
                <th rowspan="2">Nama Guru / Pegawai</th>
                <th colspan="<?= $jml_hari ?>">Tanggal</th>
                <th colspan="3">Total</th>
            </tr>
            <tr>
                <?php for($d=1; $d<=$jml_hari; $d++) echo "<th>$d</th>"; ?>
                <th width="30">H</th>
                <th width="30">S</th>
                <th width="30">I</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($guru as $i => $g): 
                 $h=0; $s=0; $iz=0;
            ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td class="text-left"><b><?= $g['nama_guru'] ?></b><br><span style="font-size:9px"><?= $g['nip'] ?></span></td>
                <?php for($d=1; $d<=$jml_hari; $d++): 
                    $st = $map[$g['id']][$d] ?? '';
                    $cls = '';
                    if($st=='Hadir') { $t='•'; $h++; }
                    elseif($st=='Sakit') { $t='S'; $s++; $cls='bg-s'; }
                    elseif($st=='Izin') { $t='I'; $iz++; $cls='bg-i'; }
                    elseif($st=='Terlambat') { $t='T'; $h++; } // Terlambat dihitung hadir
                    else { $t=''; }
                ?>
                <td class="<?= $cls ?>"><?= $t ?></td>
                <?php endfor; ?>
                <td><?= $h ?></td>
                <td><?= $s ?></td>
                <td><?= $iz ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="sign">
            <p><?= date('d F Y') ?><br>Kepala Sekolah</p>
            <br><br><br>
            <p style="text-decoration: underline; font-weight: bold;"><?= $sekolah['kepsek'] ?></p>
        </div>
    </div>
</body>
</html>