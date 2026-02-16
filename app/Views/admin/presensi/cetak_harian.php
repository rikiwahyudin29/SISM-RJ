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
    <title>Laporan Piket Harian - <?= date('d-m-Y', strtotime($tanggal)) ?></title>
    <style>
        /* RESET CSS UNTUK PRINT */
        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: #000; background: #fff; margin: 0; padding: 20px; }
        
        @page { size: A4 portrait; margin: 2cm; }

        /* HEADER / KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 18pt; text-transform: uppercase; letter-spacing: 1px; font-weight: 900; }
        .kop-teks p { margin: 2px 0; font-size: 10pt; }

        /* JUDUL LAPORAN */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h2 { margin: 0; text-transform: uppercase; font-size: 14pt; text-decoration: underline; }
        .report-title p { margin: 5px 0; font-size: 11pt; }

        /* TABEL UTAMA */
        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.main-table th, table.main-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; font-size: 10pt; }
        
        /* HEADER TABEL */
        table.main-table th { background-color: #e0e0e0 !important; -webkit-print-color-adjust: exact; text-align: center; font-weight: bold; }
        
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-red { color: red; }

        /* TANDA TANGAN */
        .ttd-area { margin-top: 30px; width: 100%; display: table; page-break-inside: avoid; }
        .ttd-box { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
        .ttd-space { height: 70px; }

        /* TOMBOL (HILANG SAAT PRINT) */
        @media print {
            .no-print { display: none !important; }
        }
        .no-print { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        .btn { background: #333; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; border: none; font-size: 12px; }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn">CETAK PDF</button>
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
                                <?= $sekolah['kelurahan'] ?>, Kec. <?= $sekolah['kecamatan'] ?>, <?= $sekolah['kabupaten'] ?> - <?= $sekolah['provinsi'] ?>
                            </p>
                            <p>Telp: <?= $sekolah['no_telp'] ?> | Email: <?= $sekolah['email'] ?></p>
                        </div>
                    </td>
                    <td style="border: none; width: 15%;"></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="report-title">
        <h2>LAPORAN HARIAN PIKET</h2>
        <p>Hari/Tanggal: <b><?= date('d F Y', strtotime($tanggal)) ?></b></p>
    </div>

    <p style="font-size: 10pt; margin-bottom: 10px;">Rekapitulasi ketidakhadiran dan keterlambatan siswa:</p>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kelas</th>
                <th width="30%">Nama Siswa</th>
                <th width="15%">Status</th>
                <th width="35%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data)): ?>
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; font-style: italic;">
                        Nihil (Semua Siswa Hadir Tepat Waktu)
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($data as $i => $d): ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td class="text-center"><?= $d['nama_kelas'] ?></td>
                    <td class="text-bold"><?= $d['nama_lengkap'] ?></td>
                    <td class="text-center <?= ($d['status_kehadiran'] == 'Alpa' || $d['status_kehadiran'] == 'Terlambat') ? 'text-red' : '' ?>">
                        <?= $d['status_kehadiran'] ?>
                    </td>
                    <td>
                        <?= $d['keterangan'] ?>
                        <?php if(!empty($d['jam_masuk'])): ?>
                            <br><small style="color: #555;">(Masuk: <?= substr($d['jam_masuk'], 0, 5) ?> WIB)</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="ttd-area">
        <div class="ttd-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            
            <?php if(!empty($sekolah['ttd_kepsek']) && file_exists(FCPATH . 'uploads/identitas/' . $sekolah['ttd_kepsek'])): ?>
                <img src="<?= base_url('uploads/identitas/'.$sekolah['ttd_kepsek']) ?>" height="60" style="margin: 5px 0;">
            <?php else: ?>
                <div class="ttd-space"></div>
            <?php endif; ?>

            <p class="text-bold" style="text-decoration: underline;"><?= strtoupper($sekolah['nama_kepsek'] ?? '..........................') ?></p>
            <p>NIP. <?= $sekolah['nip_kepsek'] ?? '-' ?></p>
        </div>

        <div class="ttd-box">
            <p><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?></p>
            <p>Guru Piket</p>
            <div class="ttd-space"></div>
            <p class="text-bold" style="text-decoration: underline;">( ..................................... )</p>
            <p>NIP. -</p>
        </div>
    </div>

</body>
</html>