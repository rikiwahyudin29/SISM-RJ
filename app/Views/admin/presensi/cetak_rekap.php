<?php
// 1. TARIK DATA SEKOLAH (Agar Header & TTD Dinamis)
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// Setup Path Gambar
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default.png');
$pathKop  = 'uploads/identitas/' . ($sekolah['kop_surat'] ?? 'default.png');
$pakaiKopGambar = !empty($sekolah['kop_surat']) && file_exists(FCPATH . $pathKop);

// Hitung Jumlah Hari dalam Bulan
$jml_hari = date('t', strtotime($bulan));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi - <?= $kelas->nama_kelas ?></title>
    <style>
        /* RESET CSS UNTUK PRINT */
        @page { size: A4 landscape; margin: 10mm; }
        body { font-family: 'Arial', sans-serif; font-size: 10pt; color: #000; background: #fff; margin: 0; padding: 0; }

        /* HEADER / KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 15px; padding-bottom: 5px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 18pt; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; }
        .kop-teks p { margin: 2px 0; font-size: 10pt; }

        /* META DATA */
        .meta-data { margin-bottom: 15px; display: flex; justify-content: space-between; font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 5px; }

        /* TABEL UTAMA */
        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 9pt; }
        table.main-table th, table.main-table td { border: 1px solid #444; padding: 2px; text-align: center; vertical-align: middle; }
        
        /* HEADER TABEL */
        table.main-table th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; color: #000; font-weight: bold; height: 25px; }
        
        .text-left { text-align: left; padding-left: 5px; }
        .nama-siswa { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }

        /* WARNA STATUS (Pastel Colors untuk Print) */
        .bg-a { background-color: #ffcccc !important; -webkit-print-color-adjust: exact; } /* Merah Muda - Alpha */
        .bg-s { background-color: #ccf2ff !important; -webkit-print-color-adjust: exact; } /* Biru Muda - Sakit */
        .bg-i { background-color: #fff2cc !important; -webkit-print-color-adjust: exact; } /* Kuning Muda - Izin */
        .bg-t { background-color: #e2e8f0 !important; -webkit-print-color-adjust: exact; } /* Abu Muda - Terlambat */
        .bg-libur { background-color: #555 !important; -webkit-print-color-adjust: exact; } /* Abu Gelap - Minggu */

        /* TANDA TANGAN */
        .ttd-area { margin-top: 15px; width: 100%; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .ttd-box { text-align: center; width: 250px; }
        .ttd-space { height: 60px; }
        
        /* TOMBOL (HILANG SAAT PRINT) */
        @media print {
            .no-print { display: none !important; }
        }
        .no-print { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #ccc; margin-bottom: 10px; }
        .btn { padding: 5px 10px; border-radius: 4px; font-weight: bold; cursor: pointer; border: none; font-size: 12px; background: #333; color: white; }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn">CETAK PDF</button>
        <span style="font-size: 11px; margin-left: 10px;">*Gunakan kertas A4 Landscape</span>
    </div>

    <div class="header-container">
        <?php if ($pakaiKopGambar): ?>
            <img src="<?= base_url($pathKop) ?>" style="width: 100%; height: auto; object-fit: contain; max-height: 100px;">
        <?php else: ?>
            <table style="width: 100%; border: none; margin-bottom: 0;">
                <tr style="border: none;">
                    <td style="border: none; width: 10%; text-align: center; padding: 0;">
                        <img src="<?= base_url($pathLogo) ?>" style="width: 60px; height: auto;">
                    </td>
                    <td style="border: none; text-align: center; padding: 0;">
                        <div class="kop-teks">
                            <h1><?= $sekolah['nama_sekolah'] ?></h1>
                            <p>
                                <?= $sekolah['alamat'] ?><br>
                                <?= $sekolah['kelurahan'] ?>, Kec. <?= $sekolah['kecamatan'] ?>, <?= $sekolah['kabupaten'] ?>
                            </p>
                        </div>
                    </td>
                    <td style="border: none; width: 10%;"></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="meta-data">
        <span>LAPORAN REKAPITULASI ABSENSI SISWA</span>
        <span>KELAS: <?= strtoupper($kelas->nama_kelas) ?> | PERIODE: <?= strtoupper(date('F Y', strtotime($bulan))) ?></span>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" width="25">NO</th>
                <th rowspan="2">NAMA SISWA</th>
                <th colspan="<?= $jml_hari ?>">TANGGAL</th>
                <th colspan="5">TOTAL</th>
                <th rowspan="2" width="35">%</th>
            </tr>
            <tr>
                <?php for($d=1; $d<=$jml_hari; $d++): 
                    $dateStr = date('Y-m', strtotime($bulan)) . '-' . sprintf('%02d', $d);
                    $isSunday = (date('N', strtotime($dateStr)) == 7);
                    $thClass = $isSunday ? 'bg-libur' : '';
                ?>
                    <th width="18" class="<?= $thClass ?>"><?= $d ?></th>
                <?php endfor; ?>
                <th width="22" class="bg-h">H</th> <th width="22" class="bg-s">S</th>
                <th width="22" class="bg-i">I</th>
                <th width="22" class="bg-a">A</th>
                <th width="22" class="bg-t">T</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data_rekap as $i => $s): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td class="text-left">
                    <div class="nama-siswa"><b><?= strtoupper($s['nama']) ?></b></div>
                </td>
                
                <?php for($d=1; $d<=$jml_hari; $d++): 
                    $dateStr = date('Y-m', strtotime($bulan)) . '-' . sprintf('%02d', $d);
                    $isSunday = (date('N', strtotime($dateStr)) == 7);

                    $st = $s['harian'][$d] ?? '';
                    $class = ''; $text = '';

                    // Logika Warna & Simbol
                    if($st == 'Alpha') { $class = 'bg-a'; $text = 'A'; }
                    elseif($st == 'Sakit') { $class = 'bg-s'; $text = 'S'; }
                    elseif($st == 'Izin') { $class = 'bg-i'; $text = 'I'; }
                    elseif($st == 'Terlambat') { $class = 'bg-t'; $text = 'T'; }
                    elseif($st == 'Hadir') { $text = '•'; }
                    
                    // Jika Hari Minggu, Timpa Warna jadi Abu
                    if($isSunday) { $class = 'bg-libur'; $text = ''; }
                ?>
                    <td class="<?= $class ?>"><?= $text ?></td>
                <?php endfor; ?>

                <td style="font-weight:bold;"><?= $s['total']['H'] ?></td>
                <td style="font-weight:bold;"><?= $s['total']['S'] ?></td>
                <td style="font-weight:bold;"><?= $s['total']['I'] ?></td>
                <td style="font-weight:bold; color:red;"><?= $s['total']['A'] ?></td>
                <td style="font-weight:bold;"><?= $s['total']['T'] ?></td>
                
                <?php 
                    // Rumus: (Hadir / Jumlah Hari) * 100
                    // Kita gunakan $jml_hari sebagai pembagi
                    $persen = ($jml_hari > 0) ? round(($s['total']['H'] / $jml_hari) * 100, 0) : 0;
                ?>
                <td><b><?= $persen ?>%</b></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="font-size: 9pt; margin-bottom: 10px; border: 1px solid #ccc; padding: 5px; display: inline-block;">
        <b>Keterangan Kode:</b> A: Alpha (Merah) | S: Sakit (Biru) | I: Izin (Kuning) | T: Terlambat (Abu) | •: Hadir
    </div>

    <div class="ttd-area">
        <div class="ttd-box">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            
            <?php if(!empty($sekolah['ttd_kepsek']) && file_exists(FCPATH . 'uploads/identitas/' . $sekolah['ttd_kepsek'])): ?>
                <img src="<?= base_url('uploads/identitas/'.$sekolah['ttd_kepsek']) ?>" height="50" style="margin: 5px 0;">
            <?php else: ?>
                <div class="ttd-space"></div>
            <?php endif; ?>

            <p style="font-weight:bold; text-decoration: underline;"><?= strtoupper($sekolah['nama_kepsek'] ?? '..........................') ?></p>
            <p>NIP. <?= $sekolah['nip_kepsek'] ?? '-' ?></p>
        </div>

        <div class="ttd-box">
            <p><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?><br>Wali Kelas</p>
            <div class="ttd-space"></div>
            <p style="font-weight:bold; text-decoration: underline;">( ..................................... )</p>
            <p>NIP. -</p>
        </div>
    </div>

</body>
</html>