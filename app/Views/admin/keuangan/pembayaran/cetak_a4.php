<?php
// 1. TARIK DATA SEKOLAH LANGSUNG (Biar Header Dinamis)
$db = \Config\Database::connect();
$sekolah = $db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

// Path Gambar
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default.png');
$pathKop  = 'uploads/identitas/' . ($sekolah['kop_surat'] ?? 'default.png');
$pakaiKopGambar = !empty($sekolah['kop_surat']) && file_exists(FCPATH . $pathKop);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $trx['kode_transaksi'] ?> - <?= $sekolah['nama_sekolah'] ?></title>
    <style>
        body { font-family: 'Times New Roman', sans-serif; padding: 40px; color: #000; }
        
        /* HEADER STYLE */
        .header-container { width: 100%; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 24px; text-transform: uppercase; font-weight: 900; letter-spacing: 1px; }
        .kop-teks p { margin: 2px 0; font-size: 13px; }
        .kop-teks .kontak { font-size: 11px; font-style: italic; }
        
        /* INVOICE DETAILS */
        .invoice-info { display: flex; justify-content: space-between; margin-top: 20px; margin-bottom: 20px; }
        .invoice-title { font-size: 18px; font-weight: bold; border: 2px solid #000; padding: 5px 15px; display: inline-block; }
        
        /* TABLE STYLE */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th, td { border: 1px solid #000; padding: 10px; text-align: left; }
        th { background-color: #eee; font-weight: bold; text-align: center; }
        
        /* TOTAL & FOOTER */
        .total-box { margin-top: 20px; text-align: right; font-size: 16px; font-weight: bold; }
        .ttd-area { margin-top: 50px; display: flex; justify-content: space-between; text-align: center; }
        .footer-print { margin-top: 40px; font-size: 10px; color: #555; text-align: center; border-top: 1px dashed #ccc; padding-top: 10px; }

        @media print { 
            @page { size: A4; margin: 1cm; } 
            body { padding: 0; }
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
                    <td style="border: none; width: 15%; text-align: center;">
                        <img src="<?= base_url($pathLogo) ?>" style="width: 90px; height: auto;">
                    </td>
                    <td style="border: none; text-align: center;">
                        <div class="kop-teks">
                            <h1><?= $sekolah['nama_sekolah'] ?></h1>
                            
                            <p>
                                <?= $sekolah['alamat'] ?><br>
                                <?= $sekolah['kelurahan'] ?>, Kec. <?= $sekolah['kecamatan'] ?>, <?= $sekolah['kabupaten'] ?> - <?= $sekolah['provinsi'] ?>
                            </p>
                            
                            <p class="kontak">
                                Telp: <?= $sekolah['no_telp'] ?> | Email: <?= $sekolah['email'] ?> | Web: <?= $sekolah['website'] ?>
                            </p>
                        </div>
                    </td>
                    <td style="border: none; width: 15%;"></td> </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="invoice-info">
        <div>
            <b>DITERIMA DARI:</b><br>
            <span style="font-size: 16px;"><?= strtoupper($trx['nama_lengkap']) ?></span><br>
            NIS/NISN: <?= $trx['nis'] ?><br>
            Kelas: <?= $trx['nama_kelas'] ?>
        </div>
        <div style="text-align: right;">
            <div class="invoice-title">BUKTI PEMBAYARAN</div><br><br>
            No. Transaksi: <b>#<?= $trx['kode_transaksi'] ?></b><br>
            Tanggal: <?= date('d/m/Y H:i', strtotime($trx['tanggal_bayar'])) ?> WIB
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Keterangan Pembayaran</th>
                <th width="25%">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">1</td>
                <td>
                    <b><?= $trx['nama_pos'] ?> (T.A <?= $trx['tahun_ajaran'] ?? date('Y') ?>)</b><br>
                    <small>Pembayaran untuk tagihan: <?= $trx['ket_tagihan'] ?></small>
                </td>
                <td align="right" style="font-family: 'Courier New', monospace; font-weight: bold;">
                    Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?>
                </td>
            </tr>
            <tr>
                <td style="height: 20px;"></td><td></td><td></td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        TOTAL DIBAYAR: Rp <?= number_format($trx['jumlah_bayar'], 0, ',', '.') ?>
    </div>

    <div style="font-style: italic; font-size: 12px; margin-top: 10px;">
        Terbilang: # <?= ucwords($terbilang ?? 'Nominal Rupiah') ?> #
    </div>

    <div class="ttd-area">
        <div>
            <br>Penyetor<br><br><br><br>
            ( <?= ucwords(strtolower($trx['nama_lengkap'])) ?> )
        </div>
        <div>
            <?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?><br>
            Petugas Tata Usaha<br>
            
            <?php if(!empty($trx['ttd_petugas'])): ?>
                <img src="<?= base_url('uploads/ttd/'.$trx['ttd_petugas']) ?>" height="60"><br>
            <?php else: ?>
                <br><br><br>
            <?php endif; ?>
            
            ( <b><?= $trx['nama_petugas'] ?? 'Administrator' ?></b> )
        </div>
    </div>

    <div class="footer-print">
        Bukti pembayaran ini sah dan diterbitkan otomatis oleh Sistem Informasi Akademik (SIAKAD).<br>
        Simpan struk ini sebagai bukti pembayaran yang sah.
    </div>

</body>
</html>