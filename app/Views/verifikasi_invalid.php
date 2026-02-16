<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white max-w-md w-full rounded-2xl shadow-xl overflow-hidden border-t-8 border-rose-500">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>

            <h1 class="text-2xl font-black text-rose-600 mb-2">DOKUMEN TIDAK VALID!</h1>
            <p class="text-sm text-gray-500 mb-6">Maaf, data surat tidak ditemukan di sistem kami atau token sudah kadaluarsa.</p>

            <a href="<?= base_url() ?>" class="inline-block px-6 py-3 bg-gray-800 text-white font-bold rounded-xl text-sm">Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>