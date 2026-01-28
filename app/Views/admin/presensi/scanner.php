<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-slate-800 dark:text-white">Scanner Presensi</h1>
            <p class="text-slate-500">Scan QR Code atau Tempel Kartu (NFC)</p>
        </div>

        <div id="nfcControl" class="hidden mb-6 text-center">
            <button onclick="startNFC()" id="btnNfc" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-purple-500/30 flex items-center justify-center gap-2 mx-auto transition-all">
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>Aktifkan Baca NFC</span>
            </button>
            <p class="text-xs text-purple-600 mt-2 font-bold animate-pulse hidden" id="nfcStatus">
                📡 NFC AKTIF! Tempelkan Kartu ke Belakang HP...
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700">
                <div id="reader" class="w-full rounded-xl overflow-hidden bg-black"></div>
                <p class="text-center text-xs text-slate-400 mt-4 italic">Arahkan kamera ke QR Code Siswa</p>
            </div>

            <div class="space-y-6">
                <div id="resultBox" class="hidden bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-blue-500" id="statusLine"></div>
                    
                    <div class="w-24 h-24 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4 text-4xl" id="iconResult">
                        👋
                    </div>

                    <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-1" id="namaSiswa">...</h2>
                    <p class="text-lg font-bold text-slate-500" id="tipeAbsen">...</p>
                    
                    <div class="mt-6 py-3 px-6 bg-slate-50 dark:bg-slate-900 rounded-xl inline-block">
                        <span class="text-3xl font-mono font-bold text-slate-800 dark:text-white" id="jamAbsen">00:00:00</span>
                    </div>

                    <div class="mt-4">
                         <span id="badgeStatus" class="px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-600">...</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-700 dark:text-white mb-2">Input Manual (NIS/NIP)</h3>
                    <div class="flex gap-2">
                        <input type="text" id="manualInput" class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl font-bold focus:border-blue-500 outline-none dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="Ketik nomor..." onkeypress="handleEnter(event)">
                        <button onclick="kirimPresensi(document.getElementById('manualInput').value)" class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold hover:bg-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. CONFIG CSRF & AUDIO
    let csrfToken = '<?= csrf_hash() ?>';
    const csrfName = '<?= csrf_token() ?>';
    
    // 2. CEK DUKUNGAN NFC
    if ('NDEFReader' in window) {
        document.getElementById('nfcControl').classList.remove('hidden');
    }

    // 3. FUNGSI NFC READER (WEB NFC API)
    async function startNFC() {
        try {
            const ndef = new NDEFReader();
            await ndef.scan();
            
            // Ubah tampilan tombol
            document.getElementById('btnNfc').classList.add('hidden');
            document.getElementById('nfcStatus').classList.remove('hidden');

            ndef.onreading = event => {
                const serialNumber = event.serialNumber; // Format: xx:xx:xx:xx
                if(serialNumber) {
                    // Format ulang jadi Uppercase tanpa titik dua (biar sama kayak input manual/RC522)
                    const uidClean = serialNumber.replaceAll(":", "").toUpperCase();
                    
                    // Mainkan suara beep kecil (opsional feedback)
                    // beep(); 
                    
                    // Kirim ke server
                    kirimPresensi(uidClean, 'NFC-HP');
                }
            };
        } catch (error) {
            Swal.fire('Ups!', 'NFC Gagal diaktifkan: ' + error, 'error');
        }
    }

    // 4. CONFIG SCANNER QR
    const html5QrcodeScanner = new Html5Qrcode("reader");
    function onScanSuccess(decodedText, decodedResult) {
        html5QrcodeScanner.pause();
        kirimPresensi(decodedText, 'QR');
    }
    html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess, (e)=>{});

    // 5. KIRIM DATA KE SERVER
    function kirimPresensi(kode, tipeInput = 'MANUAL') {
        if(!kode) return;

        Swal.fire({
            title: 'Memproses...',
            text: 'ID: ' + kode,
            timer: 1000,
            showConfirmButton: false,
            didOpen: () => { Swal.showLoading() }
        });

        const formData = new FormData();
        formData.append('kode', kode);
        formData.append('tipe', tipeInput);
        formData.append(csrfName, csrfToken);

        fetch('<?= base_url('admin/presensi/proses_scan') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.token) csrfToken = data.token; // Update Token

            if(data.status === 'success') {
                suksesScan(data);
            } else {
                gagalScan(data.message);
            }
            
            // Resume QR Scanner jika dipakai
            if(html5QrcodeScanner.isScanning) {
                 setTimeout(() => html5QrcodeScanner.resume(), 2000);
            }
        })
        .catch(error => {
            console.error(error);
            gagalScan('Gagal koneksi server');
        });

        document.getElementById('manualInput').value = '';
    }

    // 6. UI HELPER
    function suksesScan(data) {
        const box = document.getElementById('resultBox');
        box.classList.remove('hidden');
        
        document.getElementById('namaSiswa').innerText = data.nama;
        document.getElementById('tipeAbsen').innerText = 'Absen ' + data.tipe;
        document.getElementById('jamAbsen').innerText = data.jam;
        document.getElementById('badgeStatus').innerText = data.ket;

        const line = document.getElementById('statusLine');
        const badge = document.getElementById('badgeStatus');
        const icon = document.getElementById('iconResult');

        if(data.ket === 'Terlambat') {
            line.className = 'absolute top-0 left-0 w-full h-2 bg-rose-500';
            badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-600';
            icon.innerHTML = '⚠️';
        } else {
            line.className = 'absolute top-0 left-0 w-full h-2 bg-emerald-500';
            badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-600';
            icon.innerHTML = '✅';
        }
    }

    function gagalScan(msg) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: msg, timer: 1500, showConfirmButton: false });
    }

    function handleEnter(e) {
        if(e.key === 'Enter') kirimPresensi(e.target.value);
    }
</script>

<?= $this->endSection() ?>