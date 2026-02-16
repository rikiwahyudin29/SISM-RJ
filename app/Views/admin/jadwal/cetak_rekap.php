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
    <title>Rekap Beban Mengajar - <?= $sekolah['nama_sekolah'] ?></title>
    <style>
        /* RESET CSS UNTUK PRINT */
        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: #000; background: #fff; margin: 0; padding: 0; }

        /* HEADER / KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 5px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 18pt; text-transform: uppercase; letter-spacing: 2px; font-weight: 900; }
        .kop-teks p { margin: 2px 0; font-size: 10pt; }

        /* JUDUL LAPORAN */
        .report-title { text-align: center; margin-bottom: 15px; }
        .report-title h2 { margin: 0; text-transform: uppercase; font-size: 14pt; text-decoration: underline; }
        .report-title p { margin: 5px 0; font-size: 11pt; font-style: italic; }

        /* TABEL UTAMA */
        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.main-table th, table.main-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; font-size: 10pt; }
        
        /* HEADER TABEL */
        table.main-table th { background-color: #e0e0e0 !important; -webkit-print-color-adjust: exact; text-align: center; font-weight: bold; }
        
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

        /* TANDA TANGAN */
        .ttd-area { margin-top: 20px; width: 100%; display: table; page-break-inside: avoid; }
        .ttd-box { display: table-cell; width: 33%; text-align: center; vertical-align: top; }
        .ttd-space { height: 60px; }

        /* TOMBOL KEMBALI (HILANG SAAT PRINT) */
        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
        }

        .no-print { background: #f8f9fa; padding: 15px; text-align: center; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        .btn { padding: 8px 15px; border-radius: 5px; font-weight: bold; text-decoration: none; cursor: pointer; border: none; font-size: 12px; display: inline-block; margin: 0 5px; }
        .btn-back { background: #333; color: #fff; }
        .btn-print { background: #007bff; color: #fff; }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="<?= base_url('admin/jadwal/rekap') ?>" class="btn btn-back">&larr; KEMBALI</a>
        <button onclick="window.print()" class="btn btn-print">CETAK SEKARANG</button>
        <p style="margin-top: 5px; font-size: 11px; color: #555;">*Gunakan kertas A4 Landscape agar tabel muat sempurna.</p>
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
        <h2>REKAPITULASI BEBAN MENGAJAR GURU</h2>
        <p>Tahun Ajaran: <?= !empty($tahun) ? esc($tahun['tahun_ajaran']) . ' - Semester ' . esc($tahun['semester']) : '-' ?></p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="25%">NAMA GURU / NIP</th>
                <th width="30%">KELAS YANG DIAJAR</th>
                <th width="20%">TOTAL DURASI (WAKTU)</th>
                <th width="10%">TOTAL JP (40m)</th>
                <th width="10%">TOTAL JP (45m)</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($rekap)): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; font-style: italic;">
                        Belum ada data jadwal pelajaran yang diinput.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach($rekap as $r): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <strong><?= $r['nama'] ?></strong>
                        <div style="font-size: 9pt; color: #555; margin-top: 2px;">NIP: <?= $r['nip'] ?? '-' ?></div>
                    </td>
                    <td>
                        <?= implode(', ', $r['kelas_ajar']) ?>
                    </td>
                    <td class="text-center">
                        <?= $r['jam_asli'] ?>
                    </td>
                    <td class="text-center text-bold">
                        <?= $r['total_jp_40'] ?>
                    </td>
                    <td class="text-center text-bold">
                        <?= $r['total_jp_45'] ?>
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
        
        <div class="ttd-box"></div> <div class="ttd-box">
            <p><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?></p>
            <p>Waka Kurikulum</p>
            <div class="ttd-space"></div>
            <p class="text-bold" style="text-decoration: underline;">_________________________</p>
            <p>NIP. .................................</p>
        </div>
    </div>

</body>
</html>