<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor - <?= $siswa->nama_lengkap ?></title>
    <style>
        @media print {
            @page { size: A4; margin: 15mm; }
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
            .page-break { page-break-after: always; }
        }
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.3; color: #000; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10pt; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px; vertical-align: top; }
        
        .nilai-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .nilai-table th, .nilai-table td { border: 1px solid #000; padding: 6px; }
        .nilai-table th { background-color: #f0f0f0; text-align: center; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        
        .box-container { border: 1px solid #000; padding: 10px; margin-bottom: 20px; }
        
        .ttd-table { width: 100%; margin-top: 40px; }
        .ttd-table td { text-align: center; vertical-align: bottom; height: 80px; }
    </style>
</head>
<body>

    <div class="no-print" style="position: fixed; top: 10px; right: 10px; background: white; padding: 10px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; cursor: pointer; font-weight: bold; border-radius: 5px;">🖨️ Cetak Rapor</button>
    </div>

    <div class="header">
        <h1><?= $sekolah['nama'] ?></h1>
        <p><?= $sekolah['alamat'] ?></p>
        <p>Laporan Pencapaian Kompetensi Peserta Didik</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama Peserta Didik</td><td width="2%">:</td><td width="40%"><b><?= strtoupper($siswa->nama_lengkap) ?></b></td>
            <td width="15%">Kelas</td><td width="2%">:</td><td width="26%"><?= $siswa->nama_kelas ?></td>
        </tr>
        <tr>
            <td>NIS / NISN</td><td>:</td><td><?= $siswa->nis ?> / <?= $siswa->nisn ?? '-' ?></td>
            <td>Semester</td><td>:</td><td><?= $semester ?></td>
        </tr>
        <tr>
            <td>Nama Sekolah</td><td>:</td><td><?= $sekolah['nama'] ?></td>
            <td>Tahun Ajaran</td><td>:</td><td><?= $tahun ?></td>
        </tr>
    </table>

    <hr style="margin-bottom: 20px;">

    <h3 style="margin-bottom: 5px;">A. Capaian Hasil Belajar</h3>
    <table class="nilai-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Mata Pelajaran</th>
                <th width="10%">KKM</th>
                <th width="10%">Nilai</th>
                <th width="10%">Predikat</th>
                <th width="30%">Deskripsi Capaian</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($nilai as $n): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $n['nama_mapel'] ?></td>
                <td class="text-center"><?= $n['kkm'] ?? 75 ?></td>
                <td class="text-center text-bold"><?= $n['akhir'] ?></td>
                <td class="text-center text-bold"><?= $n['predikat'] ?></td>
                <td style="font-size: 9pt;">
                    <?php 
                        // Generate Deskripsi Otomatis jika kosong
                        if($n['predikat'] == 'A') echo "Sangat baik dalam memahami materi.";
                        elseif($n['predikat'] == 'B') echo "Baik dalam memahami materi.";
                        elseif($n['predikat'] == 'C') echo "Cukup baik, perlu ditingkatkan.";
                        else echo "Perlu bimbingan lebih lanjut.";
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($nilai)): ?>
                <tr><td colspan="6" class="text-center">Belum ada nilai yang diinput.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3 style="margin-bottom: 5px;">B. Ekstrakurikuler</h3>
    <table class="nilai-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="40%">Kegiatan Ekstrakurikuler</th>
                <th width="15%">Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>Pramuka</td>
                <td class="text-center">B</td>
                <td>Melaksanakan kegiatan dengan baik</td>
            </tr>
            <tr>
                <td class="text-center">-</td>
                <td>-</td>
                <td class="text-center">-</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>

    <div style="float: left; width: 45%;">
        <h3 style="margin-bottom: 5px;">C. Ketidakhadiran</h3>
        <table class="nilai-table">
            <tr>
                <td width="60%">Sakit</td>
                <td class="text-center"><?= $catatan->sakit ?? 0 ?> hari</td>
            </tr>
            <tr>
                <td>Izin</td>
                <td class="text-center"><?= $catatan->izin ?? 0 ?> hari</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan</td>
                <td class="text-center"><?= $catatan->alpha ?? 0 ?> hari</td>
            </tr>
        </table>
    </div>

    <div style="float: right; width: 50%;">
        <?php if($semester == 'Genap'): // Hanya muncul di semester genap ?>
        <h3 style="margin-bottom: 5px;">D. Keputusan</h3>
        <div class="box-container">
            <p>Berdasarkan hasil pencapaian kompetensi, peserta didik ditetapkan:</p>
            <h2 class="text-center" style="margin: 10px 0;">
                <?php if(isset($catatan->status_naik)): ?>
                    <?= strtoupper($catatan->status_naik) ?>
                <?php else: ?>
                    NAIK KELAS
                <?php endif; ?>
            </h2>
        </div>
        <?php endif; ?>
    </div>

    <div style="clear: both;"></div>

    <h3 style="margin-bottom: 5px;">E. Catatan Wali Kelas</h3>
    <div class="box-container" style="min-height: 60px;">
        <i><?= $catatan->catatan ?? "Tingkatkan terus prestasimu!" ?></i>
    </div>

    <table class="ttd-table">
        <tr>
            <td width="33%">
                Mengetahui,<br>Orang Tua/Wali
                <br><br><br><br>
                (.....................................)
            </td>
            <td width="33%">
                Jakarta, <?= $tanggal ?><br>
                Wali Kelas
                <br><br><br><br>
                <b><u><?= $wali ?></u></b><br>
                NIP. -
            </td>
            <td width="33%">
                Mengetahui,<br>Kepala Sekolah
                <br><br><br><br>
                <b><u><?= $sekolah['kepsek'] ?></u></b><br>
                NIP. <?= $sekolah['nip'] ?>
            </td>
        </tr>
    </table>

</body>
</html>