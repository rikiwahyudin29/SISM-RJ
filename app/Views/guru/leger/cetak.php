<?php
// 1. TARIK DATA SEKOLAH (Agar Header & TTD Dinamis)
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// Setup Path Gambar
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default.png');
$pathKop  = 'uploads/identitas/' . ($sekolah['kop_surat'] ?? 'default.png');
$pakaiKopGambar = !empty($sekolah['kop_surat']) && file_exists(FCPATH . $pathKop);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Leger Nilai Kelas <?= $kelas->nama_kelas ?></title>
    <style>
        /* RESET CSS UNTUK PRINT */
        @page { size: A4 landscape; margin: 10mm; }
        body { font-family: 'Arial', sans-serif; font-size: 10pt; color: #000; background: #fff; margin: 0; padding: 0; }

        /* HEADER / KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 15px; padding-bottom: 5px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 18pt; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; }
        .kop-teks p { margin: 2px 0; font-size: 10pt; }

        /* JUDUL LAPORAN */
        .report-title { text-align: center; margin-bottom: 15px; }
        .report-title h2 { margin: 0; text-transform: uppercase; font-size: 14pt; text-decoration: underline; }
        .report-title p { margin: 2px 0; font-size: 10pt; }

        /* TABEL UTAMA */
        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 9pt; }
        table.main-table th, table.main-table td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; }
        
        /* HEADER TABEL */
        table.main-table th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; color: #000; font-weight: bold; height: 30px; }
        
        .text-left { text-align: left; padding-left: 5px; }
        .font-bold { font-weight: bold; }
        .text-red { color: red !important; -webkit-print-color-adjust: exact; }

        /* TANDA TANGAN */
        .ttd-area { margin-top: 15px; width: 100%; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .ttd-box { text-align: center; width: 250px; }
        .ttd-space { height: 60px; }
        
        /* TOMBOL (HILANG SAAT PRINT) */
        @media print {
            .no-print { display: none !important; }
        }
        .no-print { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #ccc; margin-bottom: 10px; }
        .btn { padding: 5px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; border: none; font-size: 12px; background: #333; color: white; }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ CETAK LEGER PDF</button>
        <span style="font-size: 11px; margin-left: 10px;">*Gunakan kertas A4 Landscape</span>
    </div>

    <div class="header-container">
        <?php if ($pakaiKopGambar): ?>
            <img src="<?= base_url($pathKop) ?>" style="width: 100%; height: auto; object-fit: contain; max-height: 100px;">
        <?php else: ?>
            <table style="width: 100%; border: none; margin-bottom: 0;">
                <tr style="border: none;">
                    <td style="border: none; width: 10%; text-align: center; padding: 0;">
                        <img src="<?= base_url($pathLogo) ?>" style="width: 70px; height: auto;">
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
        <h2>LEGER NILAI SISWA</h2>
        <p>Kelas: <strong><?= $kelas->nama_kelas ?></strong> | Tahun Ajaran: <?= date('Y') ?></p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th>Nama Siswa</th>
                <th width="8%">NIS</th>
                <?php foreach($mapel as $m): ?>
                    <th width="5%" title="<?= $m['nama_mapel'] ?>">
                        <?= !empty($m['kode_mapel']) ? $m['kode_mapel'] : substr($m['nama_mapel'],0,3) ?>
                    </th>
                <?php endforeach; ?>
                <th width="6%">Total</th>
                <th width="6%">Rata²</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($leger as $row): ?>
            <tr>
                <td class="font-bold"><?= $row['ranking'] ?></td>
                <td class="text-left font-bold" style="white-space: nowrap;"><?= $row['nama'] ?></td>
                <td><?= $row['nis'] ?></td>
                
                <?php foreach($mapel as $m): ?>
                    <?php $n = $row['nilai'][$m['id']]; ?>
                    <td class="<?= ($n < 75 && $n > 0) ? 'font-bold text-red' : '' ?>">
                        <?= ($n > 0) ? $n : '-' ?>
                    </td>
                <?php endforeach; ?>

                <td class="font-bold"><?= $row['total'] ?></td>
                <td class="font-bold"><?= $row['rata'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="ttd-area">
        <div class="ttd-box">
            <p>Mengetahui,<br>Wali Kelas</p>
            <div class="ttd-space"></div>
            <p class="font-bold underline"><?= $wali ?></p>
            <p>NIP. -</p>
        </div>

        <div class="ttd-box">
            <p><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?><br>Kepala Sekolah</p>
            
            <?php if(!empty($sekolah['ttd_kepsek']) && file_exists(FCPATH . 'uploads/identitas/' . $sekolah['ttd_kepsek'])): ?>
                <img src="<?= base_url('uploads/identitas/'.$sekolah['ttd_kepsek']) ?>" height="50" style="margin: 5px 0;">
            <?php else: ?>
                <div class="ttd-space"></div>
            <?php endif; ?>

            <p class="font-bold underline"><?= strtoupper($sekolah['nama_kepsek'] ?? '..........................') ?></p>
            <p>NIP. <?= $sekolah['nip_kepsek'] ?? '-' ?></p>
        </div>
    </div>

</body>
</html>