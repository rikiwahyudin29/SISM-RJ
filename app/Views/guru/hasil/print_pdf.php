<!DOCTYPE html>
<html>
<head>
    <title>Daftar Nilai - <?= esc($jadwal['judul_ujian']) ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .meta { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background-color: #f2f2f2; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR NILAI UJIAN</h2>
        <h3><?= esc($jadwal['nama_mapel']) ?></h3>
    </div>

    <div class="meta">
        <strong>Judul Ujian:</strong> <?= esc($jadwal['judul_ujian']) ?><br>
        <strong>Tanggal:</strong> <?= date('d F Y', strtotime($jadwal['waktu_mulai'])) ?><br>
        <strong>Durasi:</strong> <?= $jadwal['durasi'] ?> Menit
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIS</th>
                <th width="40%">Nama Peserta</th>
                <th width="20%">Kelas</th>
                <th width="20%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($siswa as $i => $s): ?>
            <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><?= esc($s['nis']) ?></td>
                <td><?= esc($s['nama_lengkap']) ?></td>
                <td class="text-center"><?= esc($s['nama_kelas']) ?></td>
                <td class="text-center"><strong><?= $s['nilai'] ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada: <?= date('d-m-Y H:i') ?></p>
    </div>
</body>
</html>