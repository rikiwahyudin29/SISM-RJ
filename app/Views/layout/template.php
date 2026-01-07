<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIAKAD SISM-RJ' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..0,800;1,200..0,800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>
    
    <script>
        // Cek Tema saat loading awal
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }

        tailwind.config = {
          darkMode: 'class', 
          theme: {
            extend: {
              colors: {
                'neutral-primary-soft': '#1f2937',
              }
            }
          }
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar" aria-controls="top-bar-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
                     </button>
                    <a href="<?= base_url(session()->get('role_active') . '/dashboard') ?>" class="flex ms-2 md:me-24 items-center gap-2">
                        <img src="<?= base_url('assets/img/logo.svg') ?>" class="h-9 w-9" alt="Logo" />
                        <span class="self-center text-xl font-bold whitespace-nowrap dark:text-white uppercase tracking-tighter italic">SIAKAD</span>
                    </a>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-4">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                        <div id="ping-dot" class="w-2.5 h-2.5 rounded-full bg-gray-400"></div>
                        <span id="ping-text" class="text-[11px] font-mono font-bold dark:text-gray-300">0 ms</span>
                    </div>

                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    </button>

                    <div class="flex items-center ms-3">
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <?php 
                                $foto = session()->get('foto');
                                $path = 'uploads/guru/' . $foto;
                                if (!empty($foto) && file_exists(FCPATH . $path)): 
                            ?>
                                <img class="w-8 h-8 rounded-full object-cover" src="<?= base_url($path) ?>" alt="user photo">
                            <?php else: ?>
                                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama_lengkap')) ?>&background=0D8ABC&color=fff" alt="user photo">
                            <?php endif; ?>
                        </button>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                            <div class="px-4 py-3">
                                <p class="text-sm font-bold text-gray-900 dark:text-white"><?= session()->get('nama_lengkap') ?></p>
                                <p class="text-xs font-bold text-blue-600 truncate dark:text-blue-400 uppercase tracking-widest"><?= session()->get('role_active') ?></p>
                            </div>
                            <ul class="py-1">
                                <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 italic">Edit Profil</a></li>
                                <li><a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 font-bold">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <?= $this->include('layout/sidebar') ?>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script>
        // Logika Ping Sensor
        function checkPing() {
            const start = Date.now();
            const text = document.getElementById('ping-text');
            const dot = document.getElementById('ping-dot');
            fetch('<?= base_url('favicon.ico') ?>', { mode: 'no-cors', cache: 'no-store' })
                .then(() => {
                    const diff = Date.now() - start;
                    text.innerText = diff + ' ms';
                    if (diff < 150) dot.className = 'w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse';
                    else if (diff < 400) dot.className = 'w-2.5 h-2.5 rounded-full bg-yellow-400';
                    else dot.className = 'w-2.5 h-2.5 rounded-full bg-red-500';
                })
                .catch(() => {
                    text.innerText = 'Offline';
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-red-700';
                });
        }
        setInterval(checkPing, 5000);
        checkPing();

        // Dark Mode Logic
        var themeToggleBtn = document.getElementById('theme-toggle');
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }
        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    </script>
    <?php if (session()->getFlashdata('access_denied')) : ?>
<div id="denied-popup" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 max-w-sm w-full border-t-4 border-red-500 transform transition-all animate-bounce-short">
        <div class="flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-black text-gray-900 dark:text-white mb-2">Akses Terlarang!</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed font-medium">
                <?= session()->getFlashdata('access_denied') ?>
            </p>
            <button onclick="document.getElementById('denied-popup').remove()" class="mt-6 w-full py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold rounded-2xl transition-colors">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    // Popup denied juga hilang otomatis dalam 5 detik jika tidak diklik
    setTimeout(function() {
        const popup = document.getElementById('denied-popup');
        if (popup) {
            popup.classList.add('opacity-0');
            setTimeout(() => popup.remove(), 300);
        }
    }, 5000);
</script>
<?php endif; ?>
</body>
</html>