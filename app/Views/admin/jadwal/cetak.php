<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Jadwal Pelajaran</title>
    <style>
        /* RESET CSS UNTUK PRINT */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        /* PAKSA UKURAN KERTAS A4 LANDSCAPE */
        @page {
            size: A4 landscape;
            margin: 10mm 15mm 10mm 15mm; /* Atas Kanan Bawah Kiri */
        }

        /* HEADER / KOP SURAT */
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h2 { margin: 0; font-size: 16pt; text-transform: uppercase; letter-spacing: 1px; }
        .header h3 { margin: 5px 0; font-size: 12pt; font-weight: normal; }
        .header p { margin: 0; font-size: 10pt; font-style: italic; }

        /* INFO FILTER */
        .info-section {
            margin-bottom: 15px;
            width: 100%;
        }
        .info-table {
            width: 100%;
            border: none;
        }
        .info-table td {
            padding: 2px 5px;
            border: none;
            vertical-align: top;
        }

        /* TABEL UTAMA */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        
        /* AGAR BACKGROUND WARNA TERCETAK (PENTING!) */
        .main-table th {
            background-color: #e0e0e0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10pt;
        }
        
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

        /* TANDA TANGAN */
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
        .sign-space {
            height: 70px;
        }

        /* TOMBOL KEMBALI (HILANG SAAT PRINT) */
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
        }

        .no-print {
            background: #f0f0f0;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }
        .btn-back {
            background: #333;
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-print {
            background: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            margin-left: 10px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="<?= base_url('admin/jadwal') ?>" class="btn-back">&larr; Kembali</a>
        <button onclick="window.print()" class="btn-print">Cetak PDF</button>
        <p style="margin-top: 10px; font-size: 12px; color: #555;">
            *Pastikan Layout printer di-set ke <strong>LANDSCAPE</strong> dan centang <strong>"Background Graphics"</strong>.
        </p>
    </div>

    <div class="header">
        <h2>SMK RIYADHUL JANNAH</h2>
        <h3>Jalan Cagak, Subang, Jawa Barat</h3>
        <p>Laporan Jadwal Pelajaran Tahun Ajaran <?= !empty($tahun) ? esc($tahun['tahun_ajaran']) . ' - Semester ' . esc($tahun['semester']) : '-' ?></p>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td width="15%"><strong>Laporan</strong></td>
                <td width="2%">:</td>
                <td><?= $judul ?></td>
                
                <td width="15%" align="right"><strong>Tanggal Cetak</strong></td>
                <td width="2%">:</td>
                <td width="15%"><?= date('d-m-Y') ?></td>
            </tr>
            <tr>
                <td><strong>Detail</strong></td>
                <td>:</td>
                <td><?= $subJudul ?></td>
                
                <td align="right"><strong>User Cetak</strong></td>
                <td>:</td>
                <td>Administrator</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="8%">HARI</th>
                <th width="12%">WAKTU</th>
                <th width="15%">KELAS</th>
                <th width="25%">MATA PELAJARAN</th>
                <th width="35%">GURU PENGAMPU</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($jadwal)): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; font-style: italic;">
                        Data Jadwal Tidak Ditemukan.
                    </td>
                </tr>
            <?php else: ?>
                <?php 
                    $no = 1; 
                    $lastHari = '';
                ?>
                <?php foreach($jadwal as $j): ?>
                    <?php 
                        // Logic untuk menebalkan batas antar hari
                        $borderStyle = ($lastHari != '' && $lastHari != $j['hari']) ? 'border-top: 2px solid #000;' : '';
                    ?>
                <tr style="<?= $borderStyle ?>">
                    <td class="text-center"><?= $no++ ?></td>
                    
                    <td class="text-center">
                        <?php if($lastHari != $j['hari']): ?>
                            <strong><?= $j['hari'] ?></strong>
                        <?php endif; ?>
                    </td>

                    <td class="text-center"><?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?></td>
                    <td class="text-center font-bold"><?= $j['nama_kelas'] ?></td>
                    <td><?= $j['nama_mapel'] ?></td>
                    <td><?= $j['nama_guru'] ?></td>
                </tr>
                <?php $lastHari = $j['hari']; endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-sign">
        <div class="sign-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            <div class="sign-space"></div>
            <p class="text-bold">_________________________</p>
            <p>NIP. .................................</p>
        </div>
        
        <div class="sign-box"></div>

        <div class="sign-box">
            <p>Subang, <?= date('d F Y') ?></p>
            <p>Waka Kurikulum</p>
            <div class="sign-space"></div>
            <p class="text-bold">_________________________</p>
            <p>NIP. .................................</p>
        </div>
    </div>

</body>
</html>