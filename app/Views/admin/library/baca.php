<!DOCTYPE html>
<html>
<head>
    <title>Membaca: <?= $buku->judul ?></title>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        iframe { width: 100%; height: 100%; border: none; }
        .header { background: #333; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .btn-back { color: white; text-decoration: none; font-family: sans-serif; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <span>📖 Sedang Membaca: <strong><?= $buku->judul ?></strong></span>
        <a href="<?= base_url('admin/library') ?>" class="btn-back">✕ Tutup / Kembali</a>
    </div>
    <iframe src="<?= base_url('uploads/library/'.$buku->file_ebook) ?>#toolbar=0"></iframe>
</body>
</html>