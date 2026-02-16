<?php
// 1. TARIK DATA SEKOLAH
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
    <title>Rekap Tunggakan - <?= $sekolah['nama_sekolah'] ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11px; padding: 20px; color: #000; }
        
        /* KOP SURAT */
        .header-container { width: 100%; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 20px; text-transform: uppercase; font-weight: 900; letter-spacing: 1px; }
        .kop-teks p { margin: 2px 0; font-size: 11px; }
        
        /* JUDUL */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h2 { margin: 0; text-decoration: underline; font-size: 14px; text-transform: uppercase; }
        
        /* TABEL */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px 4px; vertical-align: middle; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; font-size: 11px; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-red { color: red; }
        
        /* TOTAL ROW */
        .total-row { background-color: #ddd; font-weight: bold; }
        
        /* TANDA TANGAN */
        .ttd-area { margin-top: 30px; float: right; text-align: center; width: 200px; page-break-inside: avoid; }
        
        @media print {
            @page { size: A4 portrait; margin: 1cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header-container">
        <?php if ($pakaiKopGambar): ?>
            <img src="<?= base_url($pathKop) ?>" style="width: 100%; height: auto; object-fit: contain;">
        <?php else: ?>
            <table style="border: none; margin-bottom: 0;">
                <tr style="border: none;">
                    <td style="border: none; width: 15%; text-align: center; padding: 0;">
                        <img src="<?= base_url($pathLogo) ?>" style="width: 70px; height: auto;">
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

    <div class="report-title">
        <h2>REKAPITULASI TAGIHAN & TUNGGAKAN SISWA</h2>
        <p>Kondisi Per Tanggal: <b><?= date('d F Y') ?></b></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Jenis Pembayaran (POS)</th>
                <th width="10%">Tahun</th>
                <th width="10%">Total Siswa</th>
                <th width="10%">Lunas (Org)</th>
                <th width="10%">Belum (Org)</th>
                <th width="15%">Uang Masuk (Rp)</th>
                <th width="15%">Tunggakan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $no = 1; 
                // Variabel untuk Grand Total
                $g_siswa = 0; $g_lunas = 0; $g_belum = 0; 
                $g_uang = 0; $g_tunggakan = 0;

                foreach($rekap as $r): 
                    // Hitung Grand Total
                    $g_siswa += $r['total_siswa'];
                    $g_lunas += $r['qty_lunas'];
                    $g_belum += $r['qty_belum'];
                    $g_uang  += $r['total_bayar'];
                    $g_tunggakan += $r['total_tunggakan'];
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-bold"><?= $r['nama_pos'] ?></td>
                <td class="text-center"><?= $r['tahun_ajaran'] ?></td>
                <td class="text-center"><?= $r['total_siswa'] ?></td>
                <td class="text-center" style="color:green;"><?= $r['qty_lunas'] ?></td>
                <td class="text-center text-red text-bold"><?= $r['qty_belum'] ?></td>
                <td class="text-right"><?= number_format($r['total_bayar'], 0, ',', '.') ?></td>
                <td class="text-right text-red text-bold"><?= number_format($r['total_tunggakan'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            
            <tr class="total-row">
                <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-center"><?= number_format($g_siswa,0,',','.') ?></td>
                <td class="text-center"><?= number_format($g_lunas,0,',','.') ?></td>
                <td class="text-center"><?= number_format($g_belum,0,',','.') ?></td>
                <td class="text-right"><?= number_format($g_uang, 0, ',', '.') ?></td>
                <td class="text-right text-red"><?= number_format($g_tunggakan, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="ttd-area">
        <p><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?></p>
        <p>Bendahara Sekolah</p>
        <br><br><br><br>
        <p style="text-decoration: underline; font-weight: bold;">
            <?= session()->get('nama_lengkap') ?? '..........................' ?>
        </p>
    </div>

</body>
</html>