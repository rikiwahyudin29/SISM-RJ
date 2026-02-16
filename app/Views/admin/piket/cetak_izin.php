<?php
// 1. TARIK DATA SEKOLAH LANGSUNG DARI DATABASE
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
    <title>Surat Izin Keluar - <?= $izin['nama_lengkap'] ?></title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            background: #eee;
        }
        .container {
            width: 148mm; /* Ukuran A5 */
            height: 210mm;
            background: white;
            padding: 20px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
            box-sizing: border-box;
        }

        /* HEADER / KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .kop-teks { text-align: center; }
        .kop-teks h2 { margin: 0; font-size: 14pt; text-transform: uppercase; font-weight: bold; }
        .kop-teks p { margin: 2px 0; font-size: 9pt; }

        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            font-size: 14pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        td {
            padding: 5px;
            vertical-align: top;
            font-size: 11pt;
        }
        .label { width: 130px; font-weight: bold; }
        .titik { width: 10px; }
        
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature {
            width: 45%;
        }
        .ttd-space {
            height: 50px;
        }

        /* Tombol Print agar tidak ikut ter-print */
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-family: sans-serif;
            font-weight: bold;
            cursor: pointer;
            border: none;
            z-index: 999;
        }

        @media print {
            body { background: white; margin: 0; padding: 0; }
            .container { box-shadow: none; width: 100%; margin: 0; border: 1px solid #eee; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print">🖨️ CETAK SURAT</button>

    <div class="container">
        <div class="header-container">
            <?php if ($pakaiKopGambar): ?>
                <img src="<?= base_url($pathKop) ?>" style="width: 100%; height: auto; object-fit: contain; max-height: 100px;">
            <?php else: ?>
                <table style="border: none; margin-bottom: 0;">
                    <tr style="border: none;">
                        <td style="border: none; width: 60px; text-align: center; padding: 0;">
                            <img src="<?= base_url($pathLogo) ?>" style="width: 50px; height: auto;">
                        </td>
                        <td style="border: none; text-align: center; padding: 0;">
                            <div class="kop-teks">
                                <h2><?= $sekolah['nama_sekolah'] ?></h2>
                                <p>
                                    <?= $sekolah['alamat'] ?><br>
                                    Telp: <?= $sekolah['no_telp'] ?> | Email: <?= $sekolah['email'] ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>

        <div class="title">SURAT IZIN KELUAR SEKOLAH</div>

        <p style="font-size: 11pt;">Diberikan izin kepada siswa:</p>

        <table>
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="titik">:</td>
                <td><strong><?= strtoupper($izin['nama_lengkap']) ?></strong></td>
            </tr>
            <tr>
                <td class="label">Nomor Induk (NIS)</td>
                <td class="titik">:</td>
                <td><?= $izin['nis'] ?></td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td class="titik">:</td>
                <td><?= $izin['nama_kelas'] ?></td>
            </tr>
            <tr>
                <td class="label">Waktu Keluar</td>
                <td class="titik">:</td>
                <td><?= date('d/m/Y, H:i', strtotime($izin['waktu_keluar'])) ?> WIB</td>
            </tr>
            <tr>
                <td class="label">Keperluan</td>
                <td class="titik">:</td>
                <td>
                    <div style="min-height: 40px; border: 1px solid #000; padding: 8px; font-style: italic;">
                        <?= $izin['alasan'] ?>
                    </div>
                </td>
            </tr>
        </table>

        <p style="font-size: 10pt; text-align: justify;">Demikian surat izin ini diberikan untuk dipergunakan sebagaimana mestinya. Siswa wajib kembali ke sekolah jika urusan telah selesai (kecuali izin pulang sakit).</p>

        <div class="footer">
            <div class="signature">
                <p style="font-size: 10pt;">Mengetahui,<br>Petugas Keamanan (Satpam)</p>
                <div class="ttd-space"></div>
                <p>( .................................... )</p>
            </div>
            
            <div class="signature">
                <p style="font-size: 10pt;"><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?><br>Guru Piket,</p>
                <div class="ttd-space"></div>
                <p><strong><?= $izin['nama_pencatat'] ?></strong></p>
            </div>
        </div>

        <div style="margin-top: 30px; border-top: 1px dashed black; padding-top: 10px; font-size: 9pt; font-style: italic;">
            * Potongan surat ini wajib ditunjukkan dan diserahkan kepada Petugas Keamanan di Gerbang Sekolah saat akan keluar area sekolah.
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>