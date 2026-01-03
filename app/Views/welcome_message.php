<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi | <?= $web['nama_sekolah'] ?? 'Nama Sekolah' ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .nav-transparent { background-color: transparent; box-shadow: none; border-bottom: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .nav-scrolled { background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border-bottom: 1px solid #e5e7eb; padding-top: 1rem; padding-bottom: 1rem; }
        .nav-transparent .nav-link { color: white; }
        .nav-scrolled .nav-link { color: #374151; }
        .nav-scrolled .nav-link:hover { color: #1d4ed8; } 
        .nav-transparent .logo-text { color: white; }
        .nav-scrolled .logo-text { color: #1e3a8a; }
        .nav-transparent .btn-spmb { border-color: white; color: white; }
        .nav-transparent .btn-spmb:hover { background-color: white; color: #1d4ed8; }
        .nav-scrolled .btn-spmb { border-color: #1d4ed8; color: #1d4ed8; }
        .nav-scrolled .btn-spmb:hover { background-color: #1d4ed8; color: white; }
        .nav-transparent .mobile-icon { color: white; }
        .nav-scrolled .mobile-icon { color: #374151; }
        @media (max-width: 768px) {
            #navbar-sticky ul { background-color: #1f2937; border: 1px solid #374151; margin-top: 10px; }
            .nav-transparent .nav-link, .nav-scrolled .nav-link { color: white !important; padding-left: 15px; }
            .nav-link:hover { background-color: #374151; }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-700 bg-white">

    <nav id="mainNavbar" class="fixed w-full z-50 top-0 start-0 transition-all duration-300 ease-in-out nav-transparent">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="bg-blue-600 text-white p-2 rounded-lg shadow-lg">
                    <i class="fas fa-graduation-cap fa-lg"></i>
                </div>
                <span class="self-center text-xl md:text-2xl font-bold whitespace-nowrap logo-text transition-colors duration-300">
                    <?= $web['nama_sekolah'] ?? 'Sekolah Digital' ?>
                </span>
            </a>
            
            <div class="flex md:order-2 space-x-2 md:space-x-2 rtl:space-x-reverse">
                <a href="<?= base_url('spmb') ?>" class="btn-spmb hidden md:block border bg-transparent focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center transition-all duration-300">
                    <i class="fas fa-user-plus mr-1"></i> Daftar SPMB
                </a>
                <a href="<?= base_url('auth') ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs md:text-sm px-3 py-2 md:px-4 md:py-2 text-center shadow-lg transition-colors">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </a>
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center rounded-lg md:hidden hover:bg-white/10 focus:outline-none mobile-icon">
                    <span class="sr-only">Open main menu</span>
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent">
                    <li><a href="#home" class="nav-link block py-2 px-3 rounded md:p-0 transition-colors duration-300">Home</a></li>
                    <li><a href="#profile" class="nav-link block py-2 px-3 rounded md:p-0 transition-colors duration-300">Profile</a></li>
                    <li><a href="#keunggulan" class="nav-link block py-2 px-3 rounded md:p-0 transition-colors duration-300">Keunggulan</a></li>
                    <li><a href="#fitur-app" class="nav-link block py-2 px-3 rounded md:p-0 transition-colors duration-300">Aplikasi</a></li>
                    <li><a href="#kegiatan" class="nav-link block py-2 px-3 rounded md:p-0 transition-colors duration-300">Kegiatan</a></li>
                    <li><a href="#kontak" class="nav-link block py-2 px-3 rounded md:p-0 transition-colors duration-300">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        
        <div id="hero-carousel" class="absolute inset-0 z-0 w-full h-full" data-carousel="slide" data-carousel-interval="4000">
            <div class="relative h-full w-full overflow-hidden">
                <?php if(!empty($sliders)): ?>
                    <?php foreach($sliders as $slide): ?>
                        <div class="hidden duration-1000 ease-in-out" data-carousel-item>
                            <img src="<?= base_url('uploads/slider/' . $slide['gambar']) ?>" class="absolute block w-full h-full object-cover" alt="<?= $slide['judul'] ?>">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="hidden duration-1000 ease-in-out" data-carousel-item>
                        <img src="https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1986&auto=format&fit=crop" class="absolute block w-full h-full object-cover" alt="Default">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="absolute inset-0 bg-gray-900/60 z-10"></div>
        
        <div class="relative z-20 px-4 mx-auto max-w-screen-xl text-center mt-10 md:mt-0">
            <span class="bg-blue-500/20 text-blue-100 border border-blue-400 text-xs font-bold inline-flex items-center px-3 py-1 rounded-full mb-4 md:mb-6 backdrop-blur-sm">
                <i class="fas fa-rocket mr-2"></i> Penerimaan Siswa Baru
            </span>
            <h1 class="mb-4 text-3xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white drop-shadow-md">
                Mewujudkan Generasi Emas <br> 
                Berbasis <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-teal-300">Teknologi Digital</span>
            </h1>
            <p class="mb-8 text-sm md:text-xl font-normal text-gray-200 px-2 md:px-48 drop-shadow-sm">
                <?= $web['deskripsi_hero'] ?? 'Deskripsi default sekolah...' ?>
            </p>
            <div class="flex flex-col space-y-3 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                <a href="<?= base_url('auth') ?>" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-800 transition-all shadow-lg">
                    Masuk Sistem <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#profile" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg border border-white hover:bg-white hover:text-gray-900 focus:ring-4 focus:ring-gray-400 transition-all backdrop-blur-sm bg-white/10">
                    <i class="fas fa-info-circle mr-2"></i> Tentang Kami
                </a>
            </div>
        </div>
    </section>

    <section class="relative z-30 mb-12 px-4 mt-6 md:-mt-16">
        <div class="max-w-screen-xl mx-auto bg-white rounded-xl shadow-xl p-6 md:p-8 border border-gray-100">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <dt class="text-2xl md:text-3xl font-bold text-blue-600"><?= number_format($stats['siswa']) ?>+</dt>
                    <dd class="text-gray-500 text-xs md:text-sm mt-1 uppercase font-semibold">Siswa Aktif</dd>
                </div>
                <div>
                    <dt class="text-2xl md:text-3xl font-bold text-blue-600"><?= number_format($stats['guru']) ?></dt>
                    <dd class="text-gray-500 text-xs md:text-sm mt-1 uppercase font-semibold">Guru & Staff</dd>
                </div>
                <div>
                    <dt class="text-2xl md:text-3xl font-bold text-blue-600"><?= number_format($stats['ekskul']) ?>+</dt>
                    <dd class="text-gray-500 text-xs md:text-sm mt-1 uppercase font-semibold">Ekstrakurikuler</dd>
                </div>
                <div>
                    <dt class="text-2xl md:text-3xl font-bold text-blue-600">100%</dt>
                    <dd class="text-gray-500 text-xs md:text-sm mt-1 uppercase font-semibold">Digital System</dd>
                </div>
            </div>
        </div>
    </section>

    <section id="profile" class="py-12 md:py-16 bg-white">
        <div class="max-w-screen-xl px-4 mx-auto lg:grid lg:grid-cols-2 lg:gap-16 items-center">
            <div class="mb-8 lg:mb-0">
                <img class="rounded-lg shadow-lg w-full h-auto object-cover" 
                     src="<?= !empty($web['foto_kepsek']) ? base_url('uploads/profile/'.$web['foto_kepsek']) : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop' ?>" 
                     alt="Profile">
            </div>
            <div class="text-gray-500 sm:text-lg">
                <h2 class="mb-4 text-3xl md:text-4xl tracking-tight font-extrabold text-gray-900">Sambutan Kepala Sekolah</h2>
                <p class="mb-4 font-light text-sm md:text-base">
                    "<?= $web['sambutan_kepsek'] ?? 'Sambutan belum diisi di dashboard admin.' ?>"
                </p>
                <div class="border-l-4 border-blue-500 pl-4 italic text-gray-700 bg-gray-50 py-3 rounded-r-lg text-sm md:text-base">
                    "Mencerdaskan kehidupan bangsa melalui teknologi dan akhlak mulia."
                </div>
            </div>
        </div>
    </section>

    <section id="keunggulan" class="bg-gray-50 py-12 md:py-16">
         <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <div class="max-w-screen-md mb-8 lg:mb-16 mx-auto text-center">
                <h2 class="mb-4 text-3xl md:text-4xl tracking-tight font-extrabold text-gray-900">Mengapa Memilih Kami?</h2>
                <p class="text-gray-500 text-sm md:text-xl">Kombinasi kurikulum terbaik, fasilitas modern, dan lingkungan yang kondusif.</p>
            </div>
            <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Kurikulum Merdeka</h3>
                    <p class="text-gray-500 text-sm">Penerapan kurikulum terbaru yang berpusat pada pengembangan minat dan bakat.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-mosque text-xl"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Lingkungan Islami</h3>
                    <p class="text-gray-500 text-sm">Menanamkan nilai-nilai akhlak mulia dan tahfidz Al-Qur'an.</p>
                </div>
                 <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-laptop-code text-xl"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Smart Classroom</h3>
                    <p class="text-gray-500 text-sm">Setiap kelas dilengkapi proyektor dan CCTV.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur-app" class="py-16 md:py-20 bg-gradient-to-br from-slate-900 to-slate-800 text-white overflow-hidden">
        <div class="max-w-screen-xl px-4 mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="order-1 lg:order-1">
                <span class="bg-blue-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded">SMART SCHOOL SYSTEM V.2.0</span>
                <h2 class="mt-4 mb-4 text-3xl md:text-4xl font-extrabold tracking-tight">Sekolah dalam Genggaman Anda</h2>
                <p class="mb-6 text-slate-300 text-sm md:text-base">Sistem kami mengintegrasikan Akademik, Keuangan, dan Kesiswaan.</p>
                <div class="mt-8">
                     <a href="<?= base_url('login') ?>" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-800 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none transition block w-full md:w-auto text-center">
                        <i class="fas fa-desktop mr-2"></i> Akses Portal Digital
                    </a>
                </div>
            </div>
            <div class="mt-4 lg:mt-0 relative group order-2 lg:order-2 w-full">
                <div class="relative mx-auto border-gray-800 bg-gray-800 border-[8px] rounded-t-xl h-auto w-full max-w-[512px] shadow-2xl">
                    <div class="rounded-lg overflow-hidden bg-white aspect-video">
                        <img src="https://flowbite.s3.amazonaws.com/docs/device-mockups/laptop-screen.png" class="w-full h-full object-cover" alt="App Dashboard">
                    </div>
                </div>
                <div class="relative mx-auto bg-gray-900 rounded-b-xl rounded-t-sm h-[15px] md:h-[21px] w-[80%] max-w-[597px]">
                    <div class="absolute left-1/2 top-0 -translate-x-1/2 rounded-b-xl w-[20%] h-[5px] bg-gray-800"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="kegiatan" class="py-12 md:py-16 bg-white">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center mb-8 lg:mb-16">
                <h2 class="mb-4 text-3xl md:text-4xl tracking-tight font-extrabold text-gray-900">Kegiatan Terbaru</h2>
                <p class="font-light text-gray-500 text-sm md:text-xl">Berita terkini seputar kegiatan dan prestasi sekolah.</p>
            </div> 
            
            <div class="grid gap-8 mb-6 lg:mb-16 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <?php if(!empty($kegiatan)): ?>
                    <?php foreach($kegiatan as $news): ?>
                        <div class="bg-white rounded-lg border border-gray-200 shadow-md">
                            <a href="<?= base_url('berita/detail/' . $news['slug']) ?>">
                                <img class="rounded-t-lg w-full h-48 object-cover" src="<?= base_url('uploads/kegiatan/' . $news['gambar']) ?>" alt="<?= $news['judul'] ?>" />
                            </a>
                            <div class="p-5">
                                <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded">Berita</span>
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 mt-2 truncate"><?= $news['judul'] ?></h5>
                                <p class="mb-3 font-normal text-gray-700 text-sm line-clamp-3">
                                    <?= strip_tags($news['isi_berita']) // Asumsi kolom isi_berita ?>
                                </p>
                                <a href="<?= base_url('berita/detail/' . $news['slug']) ?>" class="inline-flex items-center text-sm font-medium text-blue-600 hover:underline">
                                    Baca Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center w-full col-span-3 text-gray-500">Belum ada kegiatan terbaru.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="gallery" class="py-16 bg-gray-50">
        <div class="max-w-screen-xl px-4 mx-auto">
             <h2 class="mb-8 text-3xl md:text-4xl tracking-tight font-extrabold text-center text-gray-900">Galeri Sekolah</h2>
             <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php if(!empty($gallery)): ?>
                    <?php foreach($gallery as $img): ?>
                        <div>
                            <img class="h-40 w-full object-cover rounded-lg hover:scale-105 transition duration-300" 
                                 src="<?= base_url('uploads/gallery/' . $img['gambar']) ?>" alt="<?= $img['judul'] ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="spmb" class="bg-blue-700 py-16">
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center text-white">
            <h2 class="mb-4 text-3xl md:text-4xl font-extrabold tracking-tight">Siap Bergabung Bersama Kami?</h2>
            <p class="mb-8 font-light text-gray-200 text-sm md:text-xl">Pendaftaran Peserta Didik Baru (PPDB) telah dibuka.</p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                <a href="<?= base_url('spmb/register') ?>" class="text-blue-700 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-8 py-3 mr-0 md:mr-2 focus:outline-none transition">
                    Daftar Sekarang
                </a>
                <a href="<?= base_url('spmb/info') ?>" class="text-white border border-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-8 py-3 focus:outline-none transition">
                    Info Syarat & Biaya
                </a>
            </div>
        </div>
    </section>

    <footer id="kontak" class="bg-gray-900 text-white">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0 max-w-sm">
                    <a href="#" class="flex items-center mb-4">
                        <i class="fas fa-graduation-cap text-3xl mr-3 text-blue-500"></i>
                        <span class="self-center text-2xl font-semibold whitespace-nowrap">
                            <?= $web['nama_sekolah'] ?? 'Nama Sekolah' ?>
                        </span>
                    </a>
                    <p class="text-gray-400 text-sm">
                        <?= $web['alamat'] ?? 'Alamat Sekolah' ?><br>
                        Email: <?= $web['email'] ?? 'email@sekolah.sch.id' ?><br>
                        Telp: <?= $web['no_telp'] ?? '0812...' ?>
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-4 text-sm font-semibold text-white uppercase">Menu Utama</h2>
                        <ul class="text-gray-400 font-medium text-sm">
                            <li class="mb-2"><a href="#profile" class="hover:text-blue-400">Profile</a></li>
                            <li class="mb-2"><a href="#keunggulan" class="hover:text-blue-400">Keunggulan</a></li>
                            <li class="mb-2"><a href="#kegiatan" class="hover:text-blue-400">Berita</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-4 text-sm font-semibold text-white uppercase">Aplikasi</h2>
                        <ul class="text-gray-400 font-medium text-sm">
                            <li class="mb-2"><a href="/login" class="hover:text-blue-400">Login Guru</a></li>
                            <li class="mb-2"><a href="/login" class="hover:text-blue-400">Login Siswa</a></li>
                            <li class="mb-2"><a href="/spmb" class="hover:text-blue-400">E-SPMB</a></li>
                        </ul>
                    </div>
                    <div>
                         <h2 class="mb-4 text-sm font-semibold text-white uppercase">Lokasi</h2>
                         <div class="w-full h-24 bg-gray-700 rounded overflow-hidden">
                             <iframe src="<?= $web['map_link'] ?? '' ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                         </div>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-800 sm:mx-auto lg:my-8" />
            <div class="flex flex-col md:flex-row items-center justify-between">
                <span class="text-sm text-gray-500 text-center md:text-left mb-4 md:mb-0">© <?= date('Y') ?> <?= $web['nama_sekolah'] ?? 'Sekolah' ?>. All Rights Reserved.</span>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('nav-scrolled');
                navbar.classList.remove('nav-transparent');
            } else {
                navbar.classList.remove('nav-scrolled');
                navbar.classList.add('nav-transparent');
            }
        });
    </script>
</body>
</html>