<?php
// Setup Path Logo
$pathLogo = 'uploads/identitas/' . ($sekolah['logo'] ?? 'default.png');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulir Pendaftaran - <?= $pendaftar['no_pendaftaran'] ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; padding: 40px; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        .box-no { float: right; border: 2px solid #000; padding: 5px 10px; font-weight: bold; font-size: 14px; }
        
        .section-title { background: #eee; padding: 5px; font-weight: bold; margin: 15px 0 10px 0; border: 1px solid #000; }
        
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px; vertical-align: top; }
        .label { width: 180px; }
        
        .foto-box { width: 3cm; height: 4cm; border: 1px solid #000; text-align: center; line-height: 4cm; float: right; margin-top: 20px; margin-right: 20px; }
        
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="box-no">NO. REG: <?= $pendaftar['no_pendaftaran'] ?></div>
    
    <div class="header">
        <h2>PANITIA PENERIMAAN PESERTA DIDIK BARU</h2>
        <h3><?= strtoupper($sekolah['nama_sekolah']) ?></h3>
        <p><?= $sekolah['alamat'] ?> | Telp: <?= $sekolah['no_telp'] ?></p>
    </div>

    <div style="text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 20px;">
        FORMULIR BIODATA SISWA BARU
    </div>

    <div class="section-title">A. DATA PRIBADI</div>
    <table>
        <tr><td class="label">Nama Lengkap</td><td>: <?= strtoupper($pendaftar['nama_lengkap']) ?></td></tr>
        <tr><td>NISN</td><td>: <?= $pendaftar['nisn'] ?></td></tr>
        <tr><td>Jenis Kelamin</td><td>: <?= $pendaftar['jk'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
        <tr><td>Tempat, Tgl Lahir</td><td>: <?= $pendaftar['tempat_lahir'] ?>, <?= date('d-m-Y', strtotime($pendaftar['tgl_lahir'])) ?></td></tr>
        <tr><td>Agama</td><td>: <?= $pendaftar['agama'] ?></td></tr>
        <tr><td>Alamat</td><td>: <?= $pendaftar['alamat_jalan'] ?></td></tr>
    </table>

    <div class="section-title">B. DATA ORANG TUA / WALI</div>
    <table>
        <tr><td class="label">Nama Ayah</td><td>: <?= $pendaftar['nama_ayah'] ?></td></tr>
        <tr><td>Pekerjaan Ayah</td><td>: <?= $pendaftar['pekerjaan_ayah'] ?></td></tr>
        <tr><td>No. HP Ayah</td><td>: <?= $pendaftar['no_hp_ayah'] ?></td></tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr><td class="label">Nama Ibu</td><td>: <?= $pendaftar['nama_ibu'] ?></td></tr>
        <tr><td>Pekerjaan Ibu</td><td>: <?= $pendaftar['pekerjaan_ibu'] ?></td></tr>
    </table>

    <div class="section-title">C. DATA AKADEMIK</div>
    <table>
        <tr><td class="label">Asal Sekolah</td><td>: <?= $pendaftar['asal_sekolah'] ?></td></tr>
        <tr><td>Jurusan Pilihan</td><td>: <b><?= $pendaftar['jurusan_minat'] ?></b></td></tr>
        <tr><td>Nilai Rata-rata</td><td>: <?= $pendaftar['nilai_rata_rata'] ?></td></tr>
    </table>

    <div style="margin-top: 40px;">
        <div class="foto-box">
            FOTO 3x4
        </div>
        
        <div style="float: left; width: 250px; text-align: center; margin-left: 20px;">
            <p>Mengetahui,<br>Orang Tua/Wali</p>
            <br><br><br>
            <p>( .................................... )</p>
        </div>

        <div style="float: right; width: 250px; text-align: center;">
            <p><?= $sekolah['kabupaten'] ?>, <?= date('d F Y') ?><br>Calon Siswa</p>
            <br><br><br>
            <p><b><?= $pendaftar['nama_lengkap'] ?></b></p>
        </div>
    </div>

</body>
</html>