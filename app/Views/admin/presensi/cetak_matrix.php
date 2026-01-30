<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi <?= $kelas['nama_kelas'] ?> - <?= $bulan ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h3 { margin: 2px 0; font-size: 14px; font-weight: normal; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #444; padding: 4px 2px; text-align: center; font-size: 10px; }
        
        /* Header Table */
        th { background-color: #f3f4f6; color: #111; font-weight: bold; }
        
        .text-left { text-align: left; padding-left: 5px; }
        .nama-siswa { white-space: nowrap; overflow: hidden; max-width: 150px; font-weight: 500; }
        
        /* --- WARNA STATUS (PASTEL COLORS BIAR TIDAK SAKIT MATA) --- */
        
        /* Hadir: Hijau */
        .bg-h { background-color: #d1fae5; color: #065f46; font-weight: bold; }
        
        /* Terlambat: Kuning/Emas */
        .bg-t { background-color: #fef08a; color: #854d0e; font-weight: bold; }
        
        /* Sakit: Biru Langit */
        .bg-s { background-color: #bae6fd; color: #075985; font-weight: bold; }
        
        /* Izin: Ungu Muda */
        .bg-i { background-color: #e9d5ff; color: #6b21a8; font-weight: bold; }
        
        /* Alpha: Merah Muda */
        .bg-a { background-color: #fda4af; color: #9f1239; font-weight: bold; }
        
        /* Libur/Minggu: Abu-abu Gelap */
        .bg-libur { background-color: #e5e7eb; color: #9ca3af; }

        /* Legend Box */
        .legend-box { display: inline-block; width: 12px; height: 12px; margin-right: 5px; border: 1px solid #ccc; vertical-align: middle; }

        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            /* Pastikan browser mencetak background color */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>LAPORAN KEHADIRAN SISWA BULANAN</h2>
        <h3><?= $sekolah['nama'] ?></h3>
        <p>Kelas: <b><?= $kelas['nama_kelas'] ?></b> | Periode: <b><?= date('F Y', strtotime($bulan)) ?></b></p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="30px">No</th>
                <th rowspan="2" width="200px">Nama Siswa</th>
                <th colspan="<?= $jml_hari ?>">Tanggal</th>
                <th colspan="4">Total</th>
            </tr>
            <tr>
                <?php for ($d = 1; $d <= $jml_hari; $d++): 
                    $dateStr = $bulan . '-' . sprintf('%02d', $d);
                    $isSunday = (date('N', strtotime($dateStr)) == 7);
                    $thClass = $isSunday ? 'bg-libur' : '';
                ?>
                    <th width="22px" class="<?= $thClass ?>"><?= $d ?></th>
                <?php endfor; ?>
                
                <th width="30px" class="bg-h">H</th>
                <th width="30px" class="bg-s">S</th>
                <th width="30px" class="bg-i">I</th>
                <th width="30px" class="bg-a">A</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($siswa as $s): 
                $h = 0; $sa = 0; $iz = 0; $al = 0;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="text-left nama-siswa"><?= strtoupper($s['nama_lengkap']) ?></td>
                
                <?php for ($d = 1; $d <= $jml_hari; $d++): 
                    // Cek hari minggu
                    $dateStr = $bulan . '-' . sprintf('%02d', $d);
                    $isSunday = (date('N', strtotime($dateStr)) == 7);
                    
                    // Ambil status
                    $status = $matrix[$s['id']][$d] ?? '-';

                    // Hitung Total (T dihitung H di total, tapi warnanya beda)
                    if ($status == 'H' || $status == 'T') $h++; 
                    if ($status == 'S') $sa++;
                    if ($status == 'I') $iz++;
                    if ($status == 'A') $al++;

                    // Tentukan Warna Cell
                    $bgClass = '';
                    if ($status == 'H') $bgClass = 'bg-h';
                    if ($status == 'T') $bgClass = 'bg-t'; // Kuning
                    if ($status == 'S') $bgClass = 'bg-s';
                    if ($status == 'I') $bgClass = 'bg-i';
                    if ($status == 'A') $bgClass = 'bg-a';
                    
                    // Jika minggu dan tidak ada absen, warna abu
                    if ($isSunday && $status == '-') $bgClass = 'bg-libur';
                ?>
                    <td class="<?= $bgClass ?>">
                        <?= ($isSunday && $status == '-') ? '' : $status ?>
                    </td>
                <?php endfor; ?>

                <td style="font-weight:bold;"><?= $h ?></td>
                <td style="font-weight:bold;"><?= $sa ?></td>
                <td style="font-weight:bold;"><?= $iz ?></td>
                <td style="font-weight:bold; color: red;"><?= $al ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: flex-start; padding: 0 20px;">
        
        <div style="font-size: 10px; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
            <strong style="display:block; margin-bottom:5px;">Keterangan Kode:</strong>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px 15px;">
                <div><span class="legend-box bg-h"></span> H : Hadir</div>
                <div><span class="legend-box bg-t"></span> T : Terlambat</div>
                <div><span class="legend-box bg-s"></span> S : Sakit</div>
                <div><span class="legend-box bg-i"></span> I : Izin</div>
                <div><span class="legend-box bg-a"></span> A : Alpha</div>
            </div>
        </div>

        <div style="text-align: center; margin-right: 50px;">
            <p>Jakarta, <?= date('d F Y') ?></p>
            <p>Wali Kelas</p>
            <br><br><br>
            <p style="border-bottom: 1px solid #000; min-width: 150px;"></p>
        </div>
    </div>

</body>
</html>