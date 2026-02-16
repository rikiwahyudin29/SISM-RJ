<?php
// 1. TARIK DATA SEKOLAH (Agar Header & Kop Surat Dinamis)
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
    <title>Laporan Keuangan - <?= $sekolah['nama_sekolah'] ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; padding: 20px; color: #000; }
        
        /* HEADER / KOP SURAT STYLE */
        .header-container { width: 100%; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 22px; text-transform: uppercase; font-weight: 900; letter-spacing: 1px; }
        .kop-teks p { margin: 2px 0; font-size: 12px; }
        .kop-teks .kontak { font-size: 10px; font-style: italic; }

        /* JUDUL LAPORAN */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h2 { margin: 0; text-decoration: underline; font-size: 16px; }
        .report-title p { margin: 5px 0; font-size: 12px; }

        /* TABEL DATA */
        .section-title { font-size: 13px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; background: #eee; padding: 5px; border: 1px solid #000; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: middle; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* TOTAL ROW */
        .total-row { font-weight: bold; background: #e0e0e0; }

        /* SUMMARY BOX */
        .summary-box { margin-top: 20px; border: 2px solid #000; padding: 10px; width: 40%; float: right; page-break-inside: avoid; }
        
        /* TANDA TANGAN */
        .ttd-area { margin-top: 40px; float: right; text-align: center; page-break-inside: avoid; width: 200px; }
        
        .clear { clear: both; }

        @media print {
            @page { size: A4; margin: 1cm; }
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
                        <img src="<?= base_url($pathLogo) ?>" style="width: 80px; height: auto;">
                    </td>
                    <td style="border: none; text-align: center; padding: 0;">
                        <div class="kop-teks">
                            <h1><?= $sekolah['nama_sekolah'] ?></h1>
                            <p>
                                <?= $sekolah['alamat'] ?><br>
                                <?= $sekolah['kelurahan'] ?>, Kec. <?= $sekolah['kecamatan'] ?>, <?= $sekolah['kabupaten'] ?> - <?= $sekolah['provinsi'] ?>
                            </p>
                            <p class="kontak">
                                Telp: <?= $sekolah['no_telp'] ?> | Email: <?= $sekolah['email'] ?>
                            </p>
                        </div>
                    </td>
                    <td style="border: none; width: 15%;"></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="report-title">
        <h2>LAPORAN KEUANGAN & REALISASI</h2>
        <p>Periode: <b><?= date('d/m/Y', strtotime($start)) ?></b> s/d <b><?= date('d/m/Y', strtotime($end)) ?></b></p>
    </div>

    <div class="section-title">A. Pemasukan (Uang Masuk)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th>Siswa / Sumber</th>
                <th>Kelas</th>
                <th>Keterangan</th>
                <th width="15%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $total_masuk = 0;
            if(empty($transaksi)): ?>
                <tr><td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data pemasukan pada periode ini.</td></tr>
            <?php else: ?>
                <?php foreach($transaksi as $t): 
                    $total_masuk += $t['jumlah_bayar'];
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= date('d/m/y', strtotime($t['created_at'])) ?></td>
                    <td><?= $t['nama_lengkap'] ?></td>
                    <td class="text-center"><?= $t['nama_kelas'] ?></td>
                    <td><?= $t['nama_pos'] ?> <small style="color:gray;">(<?= $t['keterangan'] ?>)</small></td>
                    <td class="text-right">Rp <?= number_format($t['jumlah_bayar'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="5" class="text-center">TOTAL PEMASUKAN</td>
                <td class="text-right">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">B. Pengeluaran (Operasional)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th>Divisi</th>
                <th>Jenis</th>
                <th>Keperluan</th>
                <th width="15%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $total_keluar = 0;
            if(empty($pengeluaran)): ?>
                <tr><td colspan="6" class="text-center" style="padding: 20px;">Tidak ada pengeluaran pada periode ini.</td></tr>
            <?php else: ?>
                <?php foreach($pengeluaran as $p): 
                    $total_keluar += $p['nominal'];
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= date('d/m/y', strtotime($p['tanggal'])) ?></td>
                    <td><?= $p['nama_divisi'] ?></td>
                    <td><?= $p['nama_jenis'] ?></td>
                    <td><?= $p['judul_pengeluaran'] ?></td>
                    <td class="text-right">Rp <?= number_format($p['nominal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="5" class="text-center">TOTAL PENGELUARAN</td>
                <td class="text-right">Rp <?= number_format($total_keluar, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="summary-box">
        <h4 style="margin:0 0 10px 0; border-bottom:1px solid #000; padding-bottom:5px;">RINGKASAN AKHIR</h4>
        <table style="border:none; margin:0; font-size:13px;">
            <tr>
                <td style="border:none; padding:2px;">Total Pemasukan</td>
                <td style="border:none; padding:2px;" class="text-right">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border:none; padding:2px;">Total Pengeluaran</td>
                <td style="border:none; padding:2px; color:red;" class="text-right">- Rp <?= number_format($total_keluar, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="2" style="border:none; border-top:1px dashed #000; padding:5px 0;"></td>
            </tr>
            <tr style="font-weight:bold; font-size:16px;">
                <td style="border:none;">SURPLUS / DEFISIT</td>
                <td style="border:none;" class="text-right">Rp <?= number_format($total_masuk - $total_keluar, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="clear"></div>

    <div class="ttd-area">
        <p><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?></p>
        <p>Bendahara Sekolah</p>
        
        <br><br><br><br>
        
        <p style="text-decoration: underline; font-weight: bold;"><?= session()->get('nama_lengkap') ?></p>
        <p>NIP. -</p>
    </div>

</body>
</html>