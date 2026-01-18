<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekap Beban Mengajar</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            background: #fff;
            margin: 0; padding: 0;
        }
        @page {
            size: A4 landscape;
            margin: 10mm 15mm 10mm 15mm;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h2 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header h3 { margin: 5px 0; font-size: 12pt; font-weight: normal; }
        .header p { margin: 0; font-size: 10pt; font-style: italic; }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
        }
        .main-table th {
            background-color: #e0e0e0 !important;
            -webkit-print-color-adjust: exact;
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .footer-sign {
            margin-top: 30px;
            width: 100%;
            display: table;
        }
        .sign-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: top;
        }
        .sign-space { height: 70px; }

        @media print {
            .no-print { display: none !important; }
        }
        .no-print {
            background: #f0f0f0; padding: 15px; text-align: center; border-bottom: 1px solid #ccc; margin-bottom: 20px;
        }
        .btn {
            background: #333; color: white; text-decoration: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; border: none; font-size: 14px;
        }
        .btn-blue { background: #007bff; }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="<?= base_url('admin/jadwal/rekap') ?>" class="btn">&larr; Kembali</a>
        <button onclick="window.print()" class="btn btn-blue" style="margin-left: 10px;">Cetak PDF</button>
    </div>

    <div class="header">
        <h2>SMK RIYADHUL JANNAH</h2>
        <h3>REKAPITULASI BEBAN MENGAJAR GURU</h3>
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
                    <td colspan="6" style="text-align: center; padding: 20px;">Belum ada data jadwal.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach($rekap as $r): ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td>
                        <strong><?= $r['nama'] ?></strong>
                        <br><span style="font-size: 9pt; color: #555;">NIP: <?= $r['nip'] ?? '-' ?></span>
                    </td>
                    <td>
                        <?= implode(', ', $r['kelas_ajar']) ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $r['jam_asli'] ?>
                    </td>
                    <td style="text-align: center; font-weight: bold;">
                        <?= $r['total_jp_40'] ?>
                    </td>
                    <td style="text-align: center; font-weight: bold;">
                        <?= $r['total_jp_45'] ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-sign">
        <div class="sign-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            <div class="sign-space"></div>
            <p><strong>_________________________</strong></p>
            <p>NIP. .................................</p>
        </div>
        <div class="sign-box"></div>
        <div class="sign-box">
            <p>Subang, <?= date('d F Y') ?></p>
            <p>Waka Kurikulum</p>
            <div class="sign-space"></div>
            <p><strong>_________________________</strong></p>
            <p>NIP. .................................</p>
        </div>
    </div>

</body>
</html>