<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Leger Nilai Kelas <?= $kelas->nama_kelas ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: landscape; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
        body { font-family: 'Times New Roman', serif; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { border: 1px solid black; padding: 4px; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
    </style>
</head>
<body class="p-8 bg-white text-black">

    <div class="fixed top-4 right-4 no-print">
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded font-bold shadow hover:bg-blue-700">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <div class="text-center mb-6 border-b-2 border-black pb-4">
        <h2 class="text-xl font-bold uppercase"><?= $sekolah['nama_sekolah'] ?></h2>
        <p class="text-sm"><?= $sekolah['alamat'] ?></p>
        <h3 class="text-lg font-bold mt-4 uppercase">LEGER NILAI SISWA</h3>
        <p class="text-sm">Kelas: <strong><?= $kelas->nama_kelas ?></strong> | Semester: Ganjil/Genap | Tahun Ajaran: <?= date('Y') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">Rank</th>
                <th width="15%">Nama Siswa</th>
                <th width="5%">NIS</th>
                <?php foreach($mapel as $m): ?>
                    <th class="w-8"><?= !empty($m['kode_mapel']) ? $m['kode_mapel'] : substr($m['nama_mapel'],0,3) ?></th>
                <?php endforeach; ?>
                <th width="5%">Total</th>
                <th width="5%">Rata²</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($leger as $row): ?>
            <tr>
                <td class="text-center font-bold"><?= $row['ranking'] ?></td>
                <td class="text-left font-medium whitespace-nowrap px-2"><?= $row['nama'] ?></td>
                <td class="text-center"><?= $row['nis'] ?></td>
                
                <?php foreach($mapel as $m): ?>
                    <?php $n = $row['nilai'][$m['id']]; ?>
                    <td class="text-center <?= ($n < 75 && $n > 0) ? 'font-bold text-red-600' : '' ?>">
                        <?= ($n > 0) ? $n : '-' ?>
                    </td>
                <?php endforeach; ?>

                <td class="text-center font-bold"><?= $row['total'] ?></td>
                <td class="text-center font-bold"><?= $row['rata'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="flex justify-between mt-10 px-10 text-sm">
        <div class="text-center">
            <p>Mengetahui,</p>
            <p>Wali Kelas</p>
            <br><br><br>
            <p class="font-bold underline"><?= $wali ?></p>
            <p>NIP. -</p>
        </div>

        <div class="text-center">
            <p>Jakarta, <?= $tanggal ?></p>
            <p>Kepala Sekolah</p>
            <br><br><br>
            <p class="font-bold underline"><?= $sekolah['kepsek'] ?></p>
            <p>NIP. <?= $sekolah['nip_kepsek'] ?></p>
        </div>
    </div>

</body>
</html>