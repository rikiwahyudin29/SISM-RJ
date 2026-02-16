<?php
// 1. TARIK DATA SEKOLAH (Agar Header Dinamis)
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// Setup Path Gambar
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default.png');
$pathKop  = 'uploads/identitas/' . ($sekolah['kop_surat'] ?? 'default.png');
$pakaiKopGambar = !empty($sekolah['kop_surat']) && file_exists(FCPATH . $pathKop);

// Hitung Jumlah Hari dalam Bulan Terpilih
$jml_hari = date('t', strtotime($bulan));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi <?= $kelas['nama_kelas'] ?> - <?= date('F Y', strtotime($bulan)) ?></title>
    <style>
        /* RESET CSS UNTUK PRINT */
        body { font-family: 'Arial', sans-serif; font-size: 10pt; color: #000; background: #fff; margin: 0; padding: 0; }

        /* HEADER / KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 10px; padding-bottom: 5px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 16pt; text-transform: uppercase; letter-spacing: 1px; font-weight: 900; }
        .kop-teks p { margin: 2px 0; font-size: 9pt; }

        /* JUDUL LAPORAN */
        .report-title { text-align: center; margin-bottom: 15px; }
        .report-title h2 { margin: 0; text-transform: uppercase; font-size: 12pt; text-decoration: underline; }
        .report-title p { margin: 2px 0; font-size: 10pt; }

        /* TABEL UTAMA */
        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 9pt; }
        table.main-table th, table.main-table td { border: 1px solid #444; padding: 2px; text-align: center; vertical-align: middle; }
        
        /* Header Table */
        table.main-table th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; color: #000; font-weight: bold; font-size: 8pt; }

        .text-left { text-align: left; padding-left: 5px; }
        .nama-siswa { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; font-weight: normal; font-size: 9pt; }

        /* --- WARNA STATUS (PASTEL COLORS) --- */
        .bg-h { background-color: #d1fae5 !important; -webkit-print-color-adjust: exact; color: #000; font-weight: bold; } /* Hadir */
        .bg-t { background-color: #fef08a !important; -webkit-print-color-adjust: exact; color: #000; font-weight: bold; } /* Terlambat */
        .bg-s { background-color: #bae6fd !important; -webkit-print-color-adjust: exact; color: #000; font-weight: bold; } /* Sakit */
        .bg-i { background-color: #e9d5ff !important; -webkit-print-color-adjust: exact; color: #000; font-weight: bold; } /* Izin */
        .bg-a { background-color: #fda4af !important; -webkit-print-color-adjust: exact; color: #000; font-weight: bold; } /* Alpha */
        .bg-libur { background-color: #e5e7eb !important; -webkit-print-color-adjust: exact; color: #aaa; } /* Minggu */

        /* LEGEND BOX */
        .legend-box { display: inline-block; width: 10px; height: 10px; margin-right: 5px; border: 1px solid #ccc; vertical-align: middle; }

        /* TANDA TANGAN */
        .ttd-area { margin-top: 10px; width: 100%; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .ttd-box { text-align: center; width: 200px; font-size: 10pt; }
        .ttd-space { height: 60px; }

        /* TOMBOL PRINT */
        @media print {
            @page { size: A4 landscape; margin: 5mm; }
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
        }
        .no-print { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #ccc; margin-bottom: 10px; }
        .btn { padding: 5px 10px; border-radius: 4px; font-weight: bold; cursor: pointer; border: none; font-size: 12px; background: #333; color: white; }
    </style>
</head>
<body>

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

    <div class="report-title">
        <h2>REKAPITULASI KEHADIRAN SISWA</h2>
        <p>Kelas: <b><?= $kelas['nama_kelas'] ?></b> | Periode: <b><?= date('F Y', strtotime($bulan)) ?></b></p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" width="25px">NO</th>
                <th rowspan="2" width="180px">NAMA SISWA</th>
                <th colspan="<?= $jml_hari ?>">TANGGAL</th>
                <th colspan="4">TOTAL</th>
            </tr>
            <tr>
                <?php for ($d = 1; $d <= $jml_hari; $d++): 
                    $dateStr = date('Y-m', strtotime($bulan)) . '-' . sprintf('%02d', $d);
                    $isSunday = (date('N', strtotime($dateStr)) == 7);
                    $thClass = $isSunday ? 'bg-libur' : '';
                ?>
                    <th width="18px" class="<?= $thClass ?>"><?= $d ?></th>
                <?php endfor; ?>
                
                <th width="25px" class="bg-h">H</th>
                <th width="25px" class="bg-s">S</th>
                <th width="25px" class="bg-i">I</th>
                <th width="25px" class="bg-a">A</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($siswa as $s): 
                $h_count = 0; $s_count = 0; $i_count = 0; $a_count = 0;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="text-left nama-siswa"><?= strtoupper($s['nama_lengkap']) ?></td>
                
                <?php for ($d = 1; $d <= $jml_hari; $d++): 
                    // Cek hari minggu
                    $dateStr = date('Y-m', strtotime($bulan)) . '-' . sprintf('%02d', $d);
                    $isSunday = (date('N', strtotime($dateStr)) == 7);
                    
                    // Ambil status dari data controller (pastikan variabel $matrix dikirim)
                    // Jika data belum ada, anggap kosong
                    $status = $matrix[$s['id']][$d] ?? '-';

                    // Hitung Total
                    if ($status == 'H' || $status == 'T') $h_count++; 
                    if ($status == 'S') $s_count++;
                    if ($status == 'I') $i_count++;
                    if ($status == 'A') $a_count++;

                    // Tentukan Warna Cell
                    $bgClass = '';
                    if ($status == 'H') $bgClass = 'bg-h';
                    if ($status == 'T') $bgClass = 'bg-t'; // Terlambat warnanya kuning
                    if ($status == 'S') $bgClass = 'bg-s';
                    if ($status == 'I') $bgClass = 'bg-i';
                    if ($status == 'A') $bgClass = 'bg-a';
                    
                    // Jika minggu dan tidak ada absen, warna abu
                    if ($isSunday && $status == '-') {
                        $bgClass = 'bg-libur';
                        $status = ''; // Kosongkan teks kalau minggu
                    }
                ?>
                    <td class="<?= $bgClass ?>">
                        <?= $status ?>
                    </td>
                <?php endfor; ?>

                <td style="font-weight:bold;"><?= $h_count ?></td>
                <td style="font-weight:bold;"><?= $s_count ?></td>
                <td style="font-weight:bold;"><?= $i_count ?></td>
                <td style="font-weight:bold; color: red;"><?= $a_count ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="ttd-area">
        
        <div style="font-size: 9pt; border: 1px solid #ddd; padding: 5px; border-radius: 4px; align-self: flex-start;">
            <strong style="display:block; margin-bottom:3px;">Legenda:</strong>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2px 10px;">
                <div><span class="legend-box bg-h"></span> H : Hadir</div>
                <div><span class="legend-box bg-t"></span> T : Terlambat</div>
                <div><span class="legend-box bg-s"></span> S : Sakit</div>
                <div><span class="legend-box bg-i"></span> I : Izin</div>
                <div><span class="legend-box bg-a"></span> A : Alpha</div>
            </div>
        </div>

        <div class="ttd-box">
            <p><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?></p>
            <p>Wali Kelas</p>
            <div class="ttd-space"></div>
            <p style="border-bottom: 1px solid #000; display: inline-block; min-width: 150px;"></p>
            <p>NIP. -</p>
        </div>
    </div>

</body>
</html>