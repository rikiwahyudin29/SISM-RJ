<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Registrasi Kartu</h1>
            <p class="text-sm text-slate-500">Hubungkan Kartu RFID/QR ke Siswa.</p>
        </div>
        
        <form action="" method="get" class="flex gap-2">
            <input type="text" name="q" value="<?= $keyword ?>" placeholder="Cari Siswa..." class="px-4 py-2 border rounded-lg text-sm font-bold outline-none focus:border-blue-500">
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg font-bold text-sm">Cari</button>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="p-4">Siswa</th>
                    <th class="p-4">Kelas</th>
                    <th class="p-4">Status Kartu</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach($siswa as $s): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                    <td class="p-4 font-bold text-slate-700 dark:text-white">
                        <?= esc($s['nama_lengkap']) ?>
                        <div class="text-xs text-slate-400 font-normal"><?= esc($s['nis']) ?></div>
                    </td>
                    <td class="p-4"><?= esc($s['nama_kelas']) ?></td>
                    <td class="p-4" id="status-<?= $s['id'] ?>">
                        <?php if(!empty($s['rfid_uid'])): ?>
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-600 rounded text-xs font-bold flex items-center gap-1 w-fit">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 
                                Terdaftar
                            </span>
                            <div class="text-[10px] font-mono mt-1 text-slate-400"><?= esc($s['rfid_uid']) ?></div>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-xs font-bold">Belum Ada</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-center">
                        <button onclick="bukaModalScan(<?= $s['id'] ?>, '<?= esc($s['nama_lengkap']) ?>')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1 mx-auto transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Scan / Input
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalScan" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md border border-slate-200 dark:border-slate-700">
        <div class="p-6 text-center">
            <h3 class="text-lg font-black text-slate-800 dark:text-white mb-1" id="modalNama">Nama Siswa</h3>
            <p class="text-sm text-slate-500 mb-6">Tempelkan Kartu (NFC) atau Scan QR</p>
            
            <div id="reader" class="w-full h-48 bg-black rounded-xl overflow-hidden mb-4"></div>

            <input type="text" id="inputManual" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl font-bold text-center mb-4 focus:border-blue-500 outline-none" placeholder="Klik disini & Scan Alat IoT..." autocomplete="off">

            <div class="flex gap-2 justify-center">
                <button onclick="tutupModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg font-bold text-sm">Batal</button>
                <button onclick="prosesSimpan()" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm">Simpan Manual</button>
            </div>
        </div>
    </div>
</div>

<script>
    let activeSiswaId = 0;
    let html5QrcodeScanner = null;
    let csrfToken = '<?= csrf_hash() ?>'; // Token Awal

    // 1. BUKA MODAL
    function bukaModalScan(id, nama) {
        activeSiswaId = id;
        document.getElementById('modalNama').innerText = "Daftarkan: " + nama;
        document.getElementById('modalScan').classList.remove('hidden');
        document.getElementById('modalScan').classList.add('flex');
        
        // Focus ke input manual (biar kalau pake alat IoT langsung masuk)
        setTimeout(() => document.getElementById('inputManual').focus(), 500);

        // Aktifkan Kamera QR
        startQR();

        // Aktifkan NFC (Jika HP)
        startNFC(); 
    }

    function tutupModal() {
        document.getElementById('modalScan').classList.add('hidden');
        document.getElementById('modalScan').classList.remove('flex');
        if(html5QrcodeScanner) html5QrcodeScanner.clear();
    }

    // 2. LOGIKA SIMPAN
    function prosesSimpan(kode = null) {
        const uid = kode || document.getElementById('inputManual').value;
        if(!uid) return Swal.fire('Error', 'Kode kosong!', 'warning');

        // Loading
        Swal.showLoading();

        const formData = new FormData();
        formData.append('id_siswa', activeSiswaId);
        formData.append('uid', uid);
        formData.append('<?= csrf_token() ?>', csrfToken); // Kirim Token

        fetch('<?= base_url('admin/kartu/simpan_uid') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            // Update Token (PENTING BIAR GAK ERROR CSRF)
            // Note: Di CI4 otomatis refresh token, sebaiknya ambil token baru dari response jika diset
            // Tapi untuk simplenya kita reload halaman setelah sukses
            
            if(data.status === 'success') {
                Swal.fire({icon:'success', title:'Berhasil', text: data.message, timer: 1000, showConfirmButton:false})
                .then(() => location.reload()); // Reload biar data update
            } else {
                Swal.fire('Gagal', data.message, 'error');
            }
        });
    }

    // 3. SCANNER QR
    function startQR() {
        html5QrcodeScanner = new Html5Qrcode("reader");
        html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 200, height: 200 } }, 
        (decodedText) => {
            prosesSimpan(decodedText);
            html5QrcodeScanner.stop();
        });
    }

    // 4. SCANNER NFC (HP)
    async function startNFC() {
        if ('NDEFReader' in window) {
            try {
                const ndef = new NDEFReader();
                await ndef.scan();
                ndef.onreading = event => {
                    const uid = event.serialNumber.replaceAll(":", "").toUpperCase();
                    prosesSimpan(uid);
                };
            } catch (e) { console.log("NFC Error: " + e); }
        }
    }

    // Handle Enter di Input Manual (Buat Alat IoT)
    document.getElementById('inputManual').addEventListener("keypress", function(event) {
        if (event.key === "Enter") prosesSimpan();
    });
</script>

<?= $this->endSection() ?>