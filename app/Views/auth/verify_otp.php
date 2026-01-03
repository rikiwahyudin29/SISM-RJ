<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP Telegram | SIAKAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <style>
        /* Menghilangkan panah naik-turun pada input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .otp-input:focus {
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.2); /* Warna Biru Langit Telegram */
            border-color: #38bdf8;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-8 border border-gray-100 dark:border-gray-700">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-sky-100 dark:bg-sky-900/20 rounded-full mb-6 ring-8 ring-sky-50 dark:ring-sky-900/10">
                <svg class="w-10 h-10 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.56 8.16l-1.99 9.41c-.15.66-.54.82-1.1.51l-3.05-2.25-1.47 1.42c-.16.16-.3.3-.61.3l.22-3.11 5.66-5.11c.25-.22-.05-.34-.38-.12l-6.99 4.4-3.02-.94c-.66-.21-.67-.66.14-.97l11.78-4.54c.55-.2 1.02.12.81.9z"/>
                </svg>
            </div>
            
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Cek Telegram Anda</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-3 text-sm leading-relaxed">
                Kode unik telah dikirim ke akun Telegram bot <br>
                <span class="font-bold text-sky-600 dark:text-sky-400 text-lg tracking-wide">
                    <?php 
                        // UBAH: Ambil ID Telegram dari session (sesuai AuthController)
                        $teleID = session()->get('temp_telegram');
                        
                        if ($teleID) {
                            // Masking ID Telegram: 123****89
                            $len = strlen($teleID);
                            if($len > 4) {
                                echo substr($teleID, 0, 3) . '****' . substr($teleID, -2); 
                            } else {
                                echo $teleID;
                            }
                        } else {
                            echo "ID Tidak Terdeteksi";
                        }
                    ?>
                </span>
            </p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="p-4 mb-6 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-800 animate-pulse" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-900/20 dark:text-green-400 border border-green-100 dark:border-green-800" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/check_otp') ?>" method="POST" id="otp-form">
            <?= csrf_field() ?>
            <input type="hidden" name="otp_code" id="otp_code_hidden">

            <div class="flex justify-between gap-2 mb-10">
                <?php for($i=1; $i<=6; $i++): ?>
                    <input type="tel" 
                           class="otp-input w-full h-14 text-center text-2xl font-black text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-white border-2 border-gray-200 dark:border-gray-600 rounded-2xl focus:border-sky-500 focus:ring-0 transition-all duration-200" 
                           maxlength="1" 
                           inputmode="numeric"
                           autocomplete="one-time-code"
                           required 
                           <?= ($i == 1) ? 'autofocus' : '' ?>>
                <?php endfor; ?>
            </div>

            <button type="submit" class="w-full py-4 bg-sky-600 hover:bg-sky-700 text-white font-bold text-lg rounded-2xl transition-all shadow-xl shadow-sky-500/30 flex items-center justify-center gap-3 active:scale-[0.98]">
                <span>Verifikasi Masuk</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </button>
        </form>

        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Belum menerima pesan? 
                <a href="<?= base_url('auth/resend_otp') ?>" 
                   id="resend-btn"
                   class="pointer-events-none opacity-50 text-sky-600 dark:text-sky-400 font-bold hover:underline transition-opacity">
                    Kirim Ulang ke Telegram
                </a>
            </p>
            
            <div id="timer-box" class="mt-4 flex items-center justify-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                <span id="timer-label">Tunggu 60 detik</span>
            </div>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('otp_code_hidden');
        const form = document.getElementById('otp-form');

        // --- Fitur Auto Focus & Input Control (Tetap Sama) ---
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                if (e.target.value.length > 1) {
                    e.target.value = e.target.value.slice(0, 1);
                }
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                combineCode();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6).split('');
                pasteData.forEach((char, i) => {
                    if (inputs[i]) inputs[i].value = char;
                });
                combineCode();
                if (pasteData.length > 0) {
                    const nextIndex = Math.min(pasteData.length, 5);
                    inputs[nextIndex].focus();
                }
            });
        });

        function combineCode() {
            let fullCode = "";
            inputs.forEach(input => fullCode += input.value);
            hiddenInput.value = fullCode;
        }

        form.addEventListener('submit', (e) => {
            combineCode();
            if(hiddenInput.value.length < 6) {
                e.preventDefault();
                alert('Harap masukkan 6 digit kode OTP!');
            }
        });

        // --- Fitur Countdown Timer ---
        let secondsLeft = 60;
        const timerLabel = document.getElementById('timer-label');
        const resendBtn = document.getElementById('resend-btn');
        const timerBox = document.getElementById('timer-box');

        const countdown = setInterval(() => {
            secondsLeft--;
            timerLabel.textContent = `Tunggu ${secondsLeft} detik`;

            if (secondsLeft <= 0) {
                clearInterval(countdown);
                resendBtn.classList.remove('pointer-events-none', 'opacity-50');
                timerBox.classList.add('opacity-0'); 
            }
        }, 1000);
    </script>
</body>
</html>