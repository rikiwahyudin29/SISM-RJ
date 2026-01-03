<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP | SIAKAD</title>
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
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-8 border border-gray-100 dark:border-gray-700">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 dark:bg-green-900/20 rounded-full mb-6 ring-8 ring-green-50 dark:ring-green-900/10">
                <svg class="w-10 h-10 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Verifikasi Akun</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-3 text-sm leading-relaxed">
                Kode unik telah dikirim ke WhatsApp Anda <br>
                <span class="font-bold text-blue-600 dark:text-blue-400 text-lg tracking-wide">
                    <?php 
                        $phone = session()->get('temp_phone');
                        // Masking nomor: 0812****789
                        echo substr($phone, 0, 4) . '****' . substr($phone, -3); 
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
                           class="otp-input w-full h-14 text-center text-2xl font-black text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-white border-2 border-gray-200 dark:border-gray-600 rounded-2xl focus:border-blue-500 focus:ring-0 transition-all duration-200" 
                           maxlength="1" 
                           inputmode="numeric"
                           autocomplete="one-time-code"
                           required 
                           <?= ($i == 1) ? 'autofocus' : '' ?>>
                <?php endfor; ?>
            </div>

            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg rounded-2xl transition-all shadow-xl shadow-blue-500/30 flex items-center justify-center gap-3 active:scale-[0.98]">
                <span>Verifikasi Sekarang</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </button>
        </form>

        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tidak menerima kode? 
                <a href="<?= base_url('auth/resend_otp') ?>" 
                   id="resend-btn"
                   class="pointer-events-none opacity-50 text-blue-600 dark:text-blue-400 font-bold hover:underline transition-opacity">
                    Kirim Ulang
                </a>
            </p>
            
            <div id="timer-box" class="mt-4 flex items-center justify-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span id="timer-label">Tunggu 60 detik</span>
            </div>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('otp_code_hidden');
        const form = document.getElementById('otp-form');

        // --- Fitur Auto Focus & Input Control ---
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Hanya angka
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                if (e.target.value.length > 1) {
                    e.target.value = e.target.value.slice(0, 1);
                }
                
                // Auto focus next
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                combineCode();
            });

            input.addEventListener('keydown', (e) => {
                // Backspace auto focus prev
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

        // --- Submit Handler ---
        form.addEventListener('submit', (e) => {
            combineCode(); // Pastikan value terisi sebelum submit
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
                // Aktifkan tombol Kirim Ulang
                resendBtn.classList.remove('pointer-events-none', 'opacity-50');
                timerBox.classList.add('opacity-0'); 
            }
        }, 1000);
    </script>
</body>
</html>