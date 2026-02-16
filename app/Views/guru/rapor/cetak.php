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
    <title>Rapor - <?= $siswa->nama_lengkap ?></title>
    <style>
        @media print {
            @page { size: A4; margin: 15mm; }
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
            .page-break { page-break-after: always; }
        }
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.3; color: #000; }
        
        /* HEADER / KOP SURAT */
        .header-container { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { margin: 0; font-size: 16pt; text-transform: uppercase; font-weight: bold; }
        .kop-teks p { margin: 2px 0; font-size: 10pt; }
        
        /* INFO SISWA */
        .info-table { width: 100%; margin-bottom: 20px; font-size: 11pt; }
        .info-table td { padding: 3px; vertical-align: top; }
        
        /* TABEL NILAI */
        .nilai-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
        .nilai-table th, .nilai-table td { border: 1px solid #000; padding: 6px; vertical-align: middle; }
        .nilai-table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        
        /* KOTAK KEPUTUSAN */
        .box-container { border: 1px solid #000; padding: 10px; margin-bottom: 20px; }
        
        /* TANDA TANGAN */
        .ttd-table { width: 100%; margin-top: 40px; page-break-inside: avoid; }
        .ttd-table td { text-align: center; vertical-align: top; }
        .ttd-space { height: 70px; }
        .ttd-img { height: 60px; display: block; margin: 0 auto; }
    </style>
</head>
<body>

    <div class="no-print" style="position: fixed; top: 10px; right: 10px; background: white; padding: 10px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1); z-index: 999;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; cursor: pointer; font-weight: bold; border-radius: 5px;">🖨️ Cetak Rapor</button>
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
                                <?= $sekolah['kelurahan'] ?>, Kec. <?= $sekolah['kecamatan'] ?>, <?= $sekolah['kabupaten'] ?>
                            </p>
                            <p>Telp: <?= $sekolah['no_telp'] ?> | Email: <?= $sekolah['email'] ?></p>
                        </div>
                    </td>
                    <td style="border: none; width: 15%;"></td>
                </tr>
            </table>
        <?php endif; ?>
        <div style="text-align: center; margin-top: 10px; font-weight: bold; font-size: 12pt;">
            LAPORAN PENCAPAIAN KOMPETENSI PESERTA DIDIK
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td width="18%">Nama Peserta Didik</td><td width="2%">:</td><td width="40%"><b><?= strtoupper($siswa->nama_lengkap) ?></b></td>
            <td width="15%">Kelas</td><td width="2%">:</td><td width="23%"><?= $siswa->nama_kelas ?></td>
        </tr>
        <tr>
            <td>NIS / NISN</td><td>:</td><td><?= $siswa->nis ?> / <?= $siswa->nisn ?? '-' ?></td>
            <td>Semester</td><td>:</td><td><?= $semester ?></td>
        </tr>
        <tr>
            <td>Nama Sekolah</td><td>:</td><td><?= $sekolah['nama_sekolah'] ?></td>
            <td>Tahun Ajaran</td><td>:</td><td><?= $tahun ?></td>
        </tr>
    </table>

    <hr style="border-top: 2px solid #000; margin-bottom: 20px;">

    <h3 style="margin-bottom: 10px; font-size: 12pt;">A. Capaian Hasil Belajar</h3>
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
            <?php 
            if(empty($nilai)): ?>
                <tr><td colspan="6" class="text-center">Belum ada nilai yang diinput.</td></tr>
            <?php else: 
                $no=1; foreach($nilai as $n): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $n['nama_mapel'] ?></td>
                    <td class="text-center"><?= $n['kkm'] ?? 75 ?></td>
                    <td class="text-center text-bold"><?= $n['akhir'] ?></td>
                    <td class="text-center text-bold"><?= $n['predikat'] ?></td>
                    <td style="font-size: 9pt; text-align: justify;">
                        <?php 
                            // Deskripsi Otomatis (Bisa diganti dari database jika ada kolom deskripsi)
                            if(!empty($n['deskripsi'])) {
                                echo $n['deskripsi'];
                            } else {
                                if($n['predikat'] == 'A') echo "Sangat baik dalam memahami materi.";
                                elseif($n['predikat'] == 'B') echo "Baik dalam memahami materi.";
                                elseif($n['predikat'] == 'C') echo "Cukup baik, perlu ditingkatkan.";
                                else echo "Perlu bimbingan lebih lanjut.";
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h3 style="margin-bottom: 10px; font-size: 12pt;">B. Ekstrakurikuler</h3>
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
                <td class="text-center">2</td>
                <td>-</td>
                <td class="text-center">-</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>

    <div style="display: flex; justify-content: space-between; gap: 20px;">
        <div style="width: 48%;">
            <h3 style="margin-bottom: 10px; font-size: 12pt;">C. Ketidakhadiran</h3>
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

        <div style="width: 48%;">
            <?php if($semester == 'Genap' || $semester == '2'): // Hanya muncul di semester genap ?>
            <h3 style="margin-bottom: 10px; font-size: 12pt;">D. Keputusan</h3>
            <div class="box-container text-center">
                <p>Berdasarkan hasil pencapaian kompetensi, peserta didik ditetapkan:</p>
                <h2 style="margin: 10px 0;">
                    <?php if(isset($catatan->status_naik)): ?>
                        <?= strtoupper($catatan->status_naik) ?>
                    <?php else: ?>
                        NAIK KELAS
                    <?php endif; ?>
                </h2>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <h3 style="margin-bottom: 10px; font-size: 12pt;">E. Catatan Wali Kelas</h3>
    <div class="box-container" style="min-height: 60px;">
        <i><?= $catatan->catatan ?? "Tingkatkan terus semangat belajarmu!" ?></i>
    </div>

    <table class="ttd-table">
        <tr>
            <td width="33%">
                Mengetahui,<br>Orang Tua/Wali
                <div class="ttd-space"></div>
                ( ..................................... )
            </td>
            <td width="33%">
                <?= $sekolah['kabupaten'] ?>, <?= $tanggal ?><br>
                Wali Kelas
                <div class="ttd-space"></div>
                <b><u><?= $wali ?></u></b><br>
                NIP. -
            </td>
            <td width="33%">
                Mengetahui,<br>Kepala Sekolah
                
                <?php if(!empty($sekolah['ttd_kepsek']) && file_exists(FCPATH . 'uploads/identitas/' . $sekolah['ttd_kepsek'])): ?>
                    <img src="<?= base_url('uploads/identitas/'.$sekolah['ttd_kepsek']) ?>" class="ttd-img">
                <?php else: ?>
                    <div class="ttd-space"></div>
                <?php endif; ?>

                <b><u><?= strtoupper($sekolah['nama_kepsek'] ?? '..........................') ?></u></b><br>
                NIP. <?= $sekolah['nip_kepsek'] ?? '-' ?>
            </td>
        </tr>
    </table>

</body>
</html>