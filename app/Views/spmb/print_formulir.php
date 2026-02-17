<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pendaftaran - <?= $pendaftar['nama_lengkap'] ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; margin: 0; padding: 20px; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; }
        
        /* KOP SURAT IMAGE FULL WIDTH */
        .kop-header {
            width: 100%;
            height: auto;
            display: block;
            margin-bottom: 20px;
            border-bottom: 2px solid #000; /* Garis bawah tambahan agar rapi */
        }

        .judul { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 5px; font-size: 12pt; }
        .no-reg { text-align: center; font-weight: bold; margin-bottom: 20px; border: 1px solid #000; display: inline-block; padding: 3px 10px; position: relative; left: 50%; transform: translateX(-50%); }

        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td { padding: 3px 5px; vertical-align: top; }
        .label { width: 180px; }
        .section-title { font-weight: bold; background: #eee; padding: 5px; margin-top: 10px; border-bottom: 1px solid #000; font-size: 10pt; }

        .footer { margin-top: 30px; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .foto { width: 3cm; height: 4cm; border: 1px solid #000; display: flex; align-items: center; justify-content: center; }
        .foto img { width: 100%; height: 100%; object-fit: cover; }
        .ttd { text-align: center; width: 200px; }

        @media print { .no-print { display: none; } }
        .btn-print { background: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-family: sans-serif; }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <a href="javascript:window.print()" class="btn-print">Cetak Formulir</a>
    </div>

    <div class="container">
        <?php if(!empty($web['kop_surat'])): ?>
            <img src="<?= base_url('uploads/identitas/'.$web['kop_surat']) ?>" class="kop-header" alt="Kop Surat">
        <?php else: ?>
            <div style="text-align: center; padding: 20px; border-bottom: 3px double black; margin-bottom: 20px;">
                <h2 style="margin:0;"><?= strtoupper($web['nama_sekolah']) ?></h2>
                <p style="margin:5px 0; font-style: italic; color: red;">(Kop Surat Belum Diupload di Menu Identitas)</p>
                <p><?= $web['alamat'] ?></p>
            </div>
        <?php endif; ?>

        <div class="judul">FORMULIR PENDAFTARAN SISWA BARU</div>
        <div class="no-reg">NO. REG: <?= $pendaftar['no_pendaftaran'] ?></div>

        <div class="section-title">A. DATA PRIBADI</div>
        <table>
            <tr><td class="label">Nama Lengkap</td><td>: <?= $pendaftar['nama_lengkap'] ?></td></tr>
            <tr><td class="label">NISN</td><td>: <?= $pendaftar['nisn'] ?></td></tr>
            <tr><td class="label">NIK</td><td>: <?= $pendaftar['nik'] ?></td></tr>
            <tr><td class="label">Tempat, Tgl Lahir</td><td>: <?= $pendaftar['tempat_lahir'] ?>, <?= date('d-m-Y', strtotime($pendaftar['tgl_lahir'])) ?></td></tr>
            <tr><td class="label">Jenis Kelamin</td><td>: <?= $pendaftar['jk'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
            <tr><td class="label">Agama</td><td>: <?= $pendaftar['agama'] ?></td></tr>
            <tr><td class="label">No. HP Siswa</td><td>: <?= $pendaftar['no_hp_siswa'] ?></td></tr>
        </table>

        <div class="section-title">B. ALAMAT DOMISILI</div>
        <table>
            <tr><td class="label">Alamat Jalan</td><td>: <?= $pendaftar['alamat_jalan'] ?></td></tr>
            <tr><td class="label">RT / RW</td><td>: <?= $pendaftar['rt_rw'] ?></td></tr>
            <tr><td class="label">Desa / Kelurahan</td><td>: <?= $pendaftar['desa_kelurahan'] ?></td></tr>
            <tr><td class="label">Kecamatan</td><td>: <?= $pendaftar['kecamatan'] ?></td></tr>
            <tr><td class="label">Kab/Kota - Prov</td><td>: <?= $pendaftar['kabupaten'] ?> - <?= $pendaftar['provinsi'] ?></td></tr>
            <tr><td class="label">Kode Pos</td><td>: <?= $pendaftar['kode_pos'] ?></td></tr>
        </table>

        <div class="section-title">C. DATA ORANG TUA / WALI</div>
        <table>
            <tr><td class="label">Nama Ayah</td><td>: <?= $pendaftar['nama_ayah'] ?></td></tr>
            <tr><td class="label">Pekerjaan Ayah</td><td>: <?= $pendaftar['pekerjaan_ayah'] ?></td></tr>
            <tr><td class="label">No. HP Ayah</td><td>: <?= $pendaftar['no_hp_ayah'] ?></td></tr>
            <tr><td colspan="2" style="border-bottom: 1px dashed #ccc;"></td></tr>
            <tr><td class="label">Nama Ibu</td><td>: <?= $pendaftar['nama_ibu'] ?></td></tr>
            <tr><td class="label">Pekerjaan Ibu</td><td>: <?= $pendaftar['pekerjaan_ibu'] ?></td></tr>
            <tr><td class="label">No. HP Ibu</td><td>: <?= $pendaftar['no_hp_ibu'] ?></td></tr>
            <?php if(!empty($pendaftar['nama_wali'])): ?>
            <tr><td colspan="2" style="border-bottom: 1px dashed #ccc;"></td></tr>
            <tr><td class="label">Nama Wali</td><td>: <?= $pendaftar['nama_wali'] ?></td></tr>
            <tr><td class="label">Pekerjaan Wali</td><td>: <?= $pendaftar['pekerjaan_wali'] ?></td></tr>
            <tr><td class="label">No. HP Wali</td><td>: <?= $pendaftar['no_hp_wali'] ?></td></tr>
            <?php endif; ?>
        </table>

        <div class="section-title">D. SEKOLAH & JURUSAN</div>
        <table>
            <tr><td class="label">Asal Sekolah</td><td>: <?= $pendaftar['asal_sekolah'] ?></td></tr>
            <tr><td class="label">Jurusan Pilihan</td><td>: <b><?= $pendaftar['jurusan_minat'] ?></b></td></tr>
        </table>

        <div class="footer">
            <div class="foto">
                <?php if($pendaftar['foto'] && $pendaftar['foto'] != 'default.png'): ?>
                    <img src="<?= base_url('uploads/ppdb/'.$pendaftar['foto']) ?>">
                <?php else: ?>
                    FOTO 3x4
                <?php endif; ?>
            </div>
            <div class="ttd">
                <p><?= $pendaftar['kabupaten'] ?? 'Tempat' ?>, <?= date('d F Y') ?></p>
                <p>Calon Siswa,</p>
                <br><br><br>
                <p style="font-weight: bold; text-decoration: underline;"><?= $pendaftar['nama_lengkap'] ?></p>
            </div>
        </div>
        
        <div style="font-size: 8pt; font-style: italic; margin-top: 10px;">
            Dicetak otomatis oleh Sistem PPDB <?= $web['nama_sekolah'] ?> pada <?= date('d-m-Y H:i:s') ?>
        </div>
    </div>

</body>
</html>