<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System | SIAKAD</title>
    
    <link href="<?= base_url('assets/css/flowbite.min.css') ?>" rel="stylesheet" />
    
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200 font-sans">

    <section class="min-h-screen flex items-center justify-center">
        <div class="max-w-screen-xl w-full px-4 py-8 grid lg:grid-cols-2 gap-8 lg:py-16">
            
            <div class="flex flex-col justify-center">
                <div class="w-full lg:max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    
                    <div class="flex items-center gap-3 mb-2">
                        <img class="w-10 h-10" src="https://flowbite.com/docs/images/logo.svg" alt="logo">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">SIAKAD</span>
                    </div>
                    
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">Selamat Datang</h1>
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Masuk untuk mengakses dashboard sekolah.</p>
                    </div>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400 border border-red-200 dark:border-red-600" role="alert">
                            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                            </svg>
                            <div><?= session()->getFlashdata('error') ?></div>
                        </div>
                    <?php endif; ?>

                    <form class="space-y-5" action="<?= base_url('auth/login') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div>
                            <label for="username" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Username / Email</label>
                            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Contoh: 123456" required autocomplete="username">
                        </div>
                        
                        <div>
                            <label for="password" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="••••••••" required autocomplete="current-password">
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                                </div>
                                <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ingat saya</label>
                            </div>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Lupa password?</a>
                        </div>

                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow-md transition-all">
                            Masuk Sekarang
                        </button>

                        <div class="flex items-center">
                            <div class="w-full h-0.5 bg-gray-200 dark:bg-gray-700"></div>
                            <div class="px-5 text-center text-gray-500 dark:text-gray-400 text-sm">atau</div>
                            <div class="w-full h-0.5 bg-gray-200 dark:bg-gray-700"></div>
                        </div>

                        <a href="<?= base_url('auth/google') ?>" class="flex items-center justify-center w-full px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition duration-200 gap-2">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                            </svg>
                            Masuk dengan Google
                        </a>
                    </form>
                </div>
            </div>

            <div class="hidden lg:flex items-center justify-center bg-blue-50 dark:bg-gray-800 rounded-2xl">
                <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/authentication/illustration.svg" class="w-full max-w-lg p-10 transform hover:scale-105 transition duration-500" alt="Ilustrasi Sekolah">
            </div>
        </div>
    </section>

    <script src="<?= base_url('assets/js/flowbite.min.js') ?>"></script>
</body>
</html>