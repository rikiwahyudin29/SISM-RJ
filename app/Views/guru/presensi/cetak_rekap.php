<?php
// 1. TARIK DATA SEKOLAH
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// Setup Path Gambar
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default.png');
$pathKop  = 'uploads/identitas/' . ($sekolah['kop_surat'] ?? 'default.png');
$pakaiKopGambar = !empty($sekolah['kop_surat']) && file_exists(FCPATH . $pathKop);

// Helper Hari Indonesia
$hariIndo = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Guru - <?= date('F Y', strtotime($bulan)) ?></title>
    <style>
        /* RESET CSS UNTUK PRINT */
        @page { size: A4 portrait; margin: 2cm; }
        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: #000; background: #fff; margin: 0; padding: 0; }

        /* HEADER / KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 18pt; text-transform: uppercase; letter-spacing: 1px; font-weight: 900; }
        .kop-teks p { margin: 2px 0; font-size: 10pt; }

        /* JUDUL & META DATA */
        .report-title { text-align: center; margin-bottom: 20px; text-transform: uppercase; font-weight: bold; text-decoration: underline; font-size: 14pt; }
        .meta-table { width: 100%; margin-bottom: 15px; font-size: 11pt; }
        .meta-table td { padding: 3px; vertical-align: top; }

        /* RINGKASAN */
        .summary-box { border: 1px solid #000; padding: 10px; margin-bottom: 15px; background: #f9f9f9; -webkit-print-color-adjust: exact; }

        /* TABEL UTAMA */
        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
        table.main-table th, table.main-table td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; }
        table.main-table th { background-color: #e0e0e0 !important; -webkit-print-color-adjust: exact; font-weight: bold; }
        
        .bg-libur { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; color: #555; }

        /* TANDA TANGAN */
        .ttd-area { margin-top: 30px; width: 100%; display: flex; justify-content: flex-end; page-break-inside: avoid; }
        .ttd-box { text-align: center; width: 250px; }
        .ttd-space { height: 70px; }

        /* TOMBOL PRINT */
        @media print { .no-print { display: none !important; } }
        .no-print { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        .btn { padding: 8px 15px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ CETAK LAPORAN</button>
    </div>

    <div class="header-container">
        <?php if ($pakaiKopGambar): ?>
            <img src="<?= base_url($pathKop) ?>" style="width: 100%; height: auto; object-fit: contain; max-height: 120px;">
        <?php else: ?>
            <table style="width: 100%; border: none; margin-bottom: 0;">
                <tr style="border: none;">
                    <td style="border: none; width: 15%; text-align: center; padding: 0;">
                        <img src="<?= base_url($pathLogo) ?>" style="width: 80px; height: auto;">
                    </td>
                    <td style="border: none; text-align: center; padding: 0;">
                        <div class="kop-teks">
                            <h1><?= $sekolah['nama_sekolah'] ?></h1>
                            <p>
                                <?= $sekolah['alamat'] ?><br>
                                <?= $sekolah['kelurahan'] ?>, Kec. <?= $sekolah['kecamatan'] ?>, <?= $sekolah['kabupaten'] ?>
                            </p>
                            <p>Telp: <?= $sekolah['no_telp'] ?> | Email: <?= $sekolah['email'] ?></p>
                        </div>
                    </td>
                    <td style="border: none; width: 15%;"></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="report-title">LAPORAN KEHADIRAN GURU</div>

    <table class="meta-table">
        <tr>
            <td width="150">Nama Guru / PTK</td>
            <td width="10">:</td>
            <td><b><?= strtoupper($nama_guru) ?></b></td>
        </tr>
        <tr>
            <td>NIP / NUPTK</td>
            <td>:</td>
            <td><?= $guru['nip'] ?? '-' ?></td>
        </tr>
        <tr>
            <td>Periode Bulan</td>
            <td>:</td>
            <td><?= date('F Y', strtotime($bulan)) ?></td>
        </tr>
    </table>

    <div class="summary-box">
        <strong>Ringkasan Kehadiran:</strong><br>
        Hadir: <b><?= $total['H'] ?></b> &nbsp;|&nbsp; 
        Sakit: <b><?= $total['S'] ?></b> &nbsp;|&nbsp; 
        Izin: <b><?= $total['I'] ?></b> &nbsp;|&nbsp; 
        Alpha: <b><?= $total['A'] ?></b> &nbsp;|&nbsp; 
        Terlambat: <b><?= $total['T'] ?? 0 ?></b>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="40">Tgl</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            <?php for($d=1; $d<=$jml_hari; $d++): 
                $tglStr = date('Y-m-', strtotime($bulan)) . sprintf('%02d', $d);
                $hariEn = date('l', strtotime($tglStr));
                $hariId = $hariIndo[$hariEn] ?? $hariEn; // Translate Hari
                $isSunday = ($hariEn == 'Sunday');
                
                $st = $map[$d] ?? '-';
                
                // Style Baris
                $rowClass = $isSunday ? 'bg-libur' : '';
                $statusStyle = ($st == 'Alpha') ? 'color: red; font-weight: bold;' : (($st == 'Hadir') ? 'font-weight: bold;' : '');
            ?>
            <tr class="<?= $rowClass ?>">
                <td><?= $d ?></td>
                <td><?= $hariId ?></td>
                <td><?= ($st != '-' && $st != 'Alpha' && !$isSunday) ? '07:00' : '-' ?></td> <td><?= ($st != '-' && $st != 'Alpha' && !$isSunday) ? '14:00' : '-' ?></td>
                <td style="<?= $statusStyle ?>">
                    <?php 
                        if ($isSunday) echo '<i>Libur Akhir Pekan</i>';
                        elseif ($st == '-') echo '-';
                        else echo $st;
                    ?>
                </td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <div class="ttd-area">
        <div class="ttd-box">
            <p><?= $sekolah['kabupaten'] ?>, <?= date('t F Y', strtotime($bulan)) ?></p>
            <p>Mengetahui,<br>Kepala Sekolah</p>
            
            <?php if(!empty($sekolah['ttd_kepsek']) && file_exists(FCPATH . 'uploads/identitas/' . $sekolah['ttd_kepsek'])): ?>
                <img src="<?= base_url('uploads/identitas/'.$sekolah['ttd_kepsek']) ?>" height="60" style="margin: 5px 0;">
            <?php else: ?>
                <div class="ttd-space"></div>
            <?php endif; ?>

            <p style="font-weight:bold; text-decoration: underline;"><?= strtoupper($sekolah['nama_kepsek'] ?? '..........................') ?></p>
            <p>NIP. <?= $sekolah['nip_kepsek'] ?? '-' ?></p>
        </div>
    </div>

</body>
</html>