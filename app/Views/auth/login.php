<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIAKAD</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-200">
    <section class="min-h-screen flex items-center justify-center">
        <div class="max-w-screen-xl w-full px-4 py-8 grid lg:grid-cols-2 gap-8 lg:py-16">
            <div class="flex flex-col justify-center">
                <div class="w-full lg:max-w-md p-8 space-y-8 bg-white rounded-2xl shadow-xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-4">
                        <img class="w-10 h-10" src="https://flowbite.com/docs/images/logo.svg" alt="logo">
                        <span class="text-2xl font-bold dark:text-white">SIAKAD</span>
                    </div>
                    
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Welcome back</h1>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Silakan masuk menggunakan akun SIAKAD Anda.</p>
                    </div>

                    <?php if (session()->getFlashdata('error')) : ?>
                    <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-900 dark:text-red-400" role="alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                    <?php endif; ?>

                    <form class="mt-8 space-y-6" action="<?= base_url('auth/login') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Username / Email</label>
                                <input type="text" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm" placeholder="username anda" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Password</label>
                                <input type="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500 dark:text-gray-300">Ingat saya</label>
                                </div>
                            </div>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Lupa password?</a>
                        </div>

                        <button type="submit" class="w-full px-5 py-3 text-base font-bold text-center text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 shadow-lg shadow-blue-500/30">
                            Sign in to your account
                        </button>
                        <div class="space-y-3">
    <a href="<?= base_url('login/google') ?>" class="flex items-center justify-center w-full px-5 py-3 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition duration-200">
        <img src="https://www.gstatic.com/images/branding/product/2x/googleg_48dp.png" class="w-5 h-5 mr-3" alt="Google Logo">
        
        Sign in with Google
    </a>
</div>
                    </form>
                </div>
            </div>

            <div class="hidden lg:flex items-center justify-center">
                <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/authentication/illustration.svg" class="w-full max-w-lg" alt="Illustration">
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>