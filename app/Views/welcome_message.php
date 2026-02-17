<?php
// 1. SETUP LOGO & IDENTITAS
$pathLogo = !empty($web['logo']) ? base_url('uploads/identitas/' . $web['logo']) : 'https://flowbite.com/docs/images/logo.svg';

// 2. LOGIC YOUTUBE EMBED (AUTO CONVERT)
// Fitur ini mengubah link youtube biasa menjadi embed player
$yt_embed = '';
if (!empty($web['link_yt'])) {
    $url = $web['link_yt'];
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $yt_embed = "https://www.youtube.com/embed/" . $matches[1];
    } elseif (preg_match('/v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        $yt_embed = "https://www.youtube.com/embed/" . $matches[1];
    } else {
        $yt_embed = $url; 
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $web['nama_sekolah'] ?> | Official Website</title>
    
    <link rel="icon" type="image/x-icon" href="<?= $pathLogo ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Navbar Effect */
        .nav-transparent { background-color: transparent; padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .nav-scrolled { 
            background-color: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px); 
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.1); 
            padding-top: 1rem; 
            padding-bottom: 1rem; 
        }
        .nav-transparent .nav-link, .nav-transparent .brand-text { color: white; }
        .nav-scrolled .nav-link, .nav-scrolled .brand-text { color: #1e293b; }
        
        .nav-link { position: relative; font-weight: 600; font-size: 0.95rem; }
        .nav-link::after {
            content: ''; position: absolute; width: 0; height: 2px; bottom: -5px; left: 0;
            background-color: #3b82f6; transition: width 0.3s;
        }
        .nav-link:hover::after { width: 100%; }

        /* Lightbox (Fitur Galeri) */
        .lightbox { display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease; }
        .lightbox.show { opacity: 1; }
        .lightbox img { max-width: 90%; max-height: 85%; border-radius: 8px; box-shadow: 0 0 20px rgba(0,0,0,0.5); transform: scale(0.9); transition: transform 0.3s ease; }
        .lightbox.show img { transform: scale(1); }
        .lightbox-close { position: absolute; top: 20px; right: 30px; font-size: 40px; color: white; cursor: pointer; z-index: 10000; }
    </style>
</head>
<body class="font-sans antialiased text-slate-600 bg-white">

    <nav id="mainNavbar" class="fixed w-full z-50 top-0 start-0 transition-all duration-300 ease-in-out nav-transparent">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="<?= $pathLogo ?>" class="h-10 w-10 object-contain drop-shadow-md bg-white rounded-full p-1" alt="Logo">
                <span class="self-center text-xl font-bold whitespace-nowrap brand-text transition-colors duration-300 uppercase tracking-tight">
                    <?= $web['nama_sekolah'] ?>
                </span>
            </a>
            
            <div class="flex md:order-2 space-x-2 rtl:space-x-reverse">
                <a href="<?= base_url('auth') ?>" class="text-white bg-slate-900 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center shadow-lg transition-transform hover:scale-105 hidden md:block">
                    <i class="fas fa-lock mr-2 text-slate-400"></i> Login
                </a>
                
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 bg-white/20 backdrop-blur-sm">
                    <span class="sr-only">Open main menu</span>
                    <i class="fas fa-bars text-xl text-white"></i>
                </button>
            </div>

            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent bg-slate-900/90 rounded-xl md:rounded-none">
                    <li><a href="#home" class="nav-link block py-2 px-3 rounded md:p-0">Beranda</a></li>
                    <li><a href="#spmb" class="nav-link block py-2 px-3 rounded md:p-0 text-yellow-300 hover:text-yellow-400">Info PPDB</a></li>
                    <li><a href="#profile" class="nav-link block py-2 px-3 rounded md:p-0">Profil</a></li>
                    <li><a href="#berita" class="nav-link block py-2 px-3 rounded md:p-0">Berita</a></li>
                    <li><a href="#galeri" class="nav-link block py-2 px-3 rounded md:p-0">Galeri</a></li>
                    <li><a href="#kontak" class="nav-link block py-2 px-3 rounded md:p-0">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        <div id="hero-carousel" class="absolute inset-0 z-0 w-full h-full" data-carousel="slide" data-carousel-interval="5000">
            <div class="relative h-full w-full overflow-hidden">
                <?php if(!empty($sliders)): ?>
                    <?php foreach($sliders as $index => $slide): ?>
                        <div class="hidden duration-1000 ease-in-out" data-carousel-item="<?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= base_url('uploads/slider/' . $slide['gambar']) ?>" class="absolute block w-full h-full object-cover" alt="<?= $slide['judul'] ?>">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent opacity-90"></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="hidden duration-1000 ease-in-out" data-carousel-item="active">
                        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop" class="absolute block w-full h-full object-cover">
                        <div class="absolute inset-0 bg-slate-900/70"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="relative z-20 px-4 mx-auto max-w-screen-xl text-center mt-10 md:mt-0" data-aos="fade-up" data-aos-duration="1200">
            <span class="bg-blue-600/30 text-blue-200 border border-blue-400/50 text-xs font-bold inline-flex items-center px-4 py-1.5 rounded-full mb-6 backdrop-blur-md uppercase tracking-wider">
                <i class="fas fa-school mr-2"></i> Official Website
            </span>
            <h1 class="mb-6 text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight leading-none text-white drop-shadow-xl">
                <?= $web['nama_sekolah'] ?>
            </h1>
            <p class="mb-8 text-base md:text-xl font-light text-slate-300 px-2 md:px-48 drop-shadow-md leading-relaxed">
                <?= $web['deskripsi_hero'] ?? 'Mewujudkan generasi cerdas, berkarakter, dan berdaya saing global.' ?>
            </p>
            <div class="flex flex-col sm:flex-row sm:justify-center gap-4">
                <a href="<?= base_url('spmb/register') ?>" class="inline-flex justify-center items-center py-4 px-8 text-base font-bold text-center text-white rounded-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:ring-4 focus:ring-blue-800 transition-all shadow-xl shadow-blue-500/30 transform hover:-translate-y-1">
                    Daftar PPDB Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#profile" class="inline-flex justify-center items-center py-4 px-8 text-base font-bold text-center text-white rounded-full border border-white/30 hover:bg-white/10 hover:border-white focus:ring-4 focus:ring-slate-700 transition-all backdrop-blur-sm">
                    <i class="fas fa-play-circle mr-2"></i> Profil Sekolah
                </a>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg class="relative block w-[calc(100%+1.3px)] h-[80px] md:h-[150px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-white"></path>
            </svg>
        </div>
    </section>

    <section id="spmb" class="relative py-20 bg-white">
        <div class="max-w-screen-xl px-4 mx-auto text-center" data-aos="fade-up">
            <span class="text-blue-600 font-bold uppercase tracking-widest text-xs mb-2 block">Penerimaan Peserta Didik Baru</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-8">Bergabunglah Bersama Kami</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-lg transition-all group">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4 mx-auto group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">1. Daftar Online</h3>
                    <p class="text-slate-500 text-sm">Isi formulir biodata diri dan sekolah asal melalui menu PPDB di website ini.</p>
                </div>
                <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-lg transition-all group">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-2xl mb-4 mx-auto group-hover:scale-110 transition-transform">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">2. Verifikasi</h3>
                    <p class="text-slate-500 text-sm">Panitia akan memverifikasi berkas pendaftaran Anda secara digital.</p>
                </div>
                <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-lg transition-all group">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-4 mx-auto group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">3. Pengumuman</h3>
                    <p class="text-slate-500 text-sm">Cek status kelulusan secara real-time dan cetak bukti pendaftaran.</p>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 md:p-12 text-white shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <h4 class="text-4xl md:text-5xl font-extrabold mb-1"><?= $stats['pendaftar'] ?></h4>
                        <p class="text-blue-200 text-sm uppercase font-semibold">Pendaftar</p>
                    </div>
                    <div>
                        <h4 class="text-4xl md:text-5xl font-extrabold mb-1"><?= $stats['siswa'] ?></h4>
                        <p class="text-blue-200 text-sm uppercase font-semibold">Siswa Aktif</p>
                    </div>
                    <div>
                        <h4 class="text-4xl md:text-5xl font-extrabold mb-1"><?= $stats['guru'] ?></h4>
                        <p class="text-blue-200 text-sm uppercase font-semibold">Guru</p>
                    </div>
                    <div class="col-span-2 md:col-span-1 flex flex-col justify-center">
                        <a href="<?= base_url('spmb/register') ?>" class="w-full bg-white text-blue-700 hover:bg-blue-50 font-bold py-3 px-6 rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                            Daftar Sekarang
                        </a>
                        <p class="mt-2 text-xs text-blue-200 opacity-80">*Kuota Terbatas</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="profile" class="py-20 bg-slate-50">
        <div class="max-w-screen-xl px-4 mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <div class="relative" data-aos="fade-right">
                <div class="absolute inset-0 bg-blue-600 transform rotate-3 rounded-2xl shadow-lg"></div>
                <?php 
                // Logika Cerdas Foto Kepsek
                if (!empty($web['foto_kepsek'])) {
                    if (file_exists(FCPATH . 'uploads/profil/' . $web['foto_kepsek'])) {
                        $fotoKepsek = base_url('uploads/profil/' . $web['foto_kepsek']);
                    } elseif (file_exists(FCPATH . 'uploads/identitas/' . $web['foto_kepsek'])) {
                        $fotoKepsek = base_url('uploads/identitas/' . $web['foto_kepsek']);
                    } else {
                        $fotoKepsek = 'https://ui-avatars.com/api/?name='.urlencode($web['nama_kepsek']).'&background=0D8ABC&color=fff&size=512'; 
                    }
                } else {
                    $fotoKepsek = 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=2000&auto=format&fit=crop';
                }
                ?>
                <img src="<?= $fotoKepsek ?>" alt="Kepala Sekolah" class="relative rounded-2xl shadow-xl w-full h-[400px] object-cover border-4 border-white">
                
                <div class="absolute bottom-6 left-6 bg-white/90 backdrop-blur-md p-4 rounded-xl shadow-lg max-w-xs border border-white">
                    <h4 class="font-bold text-slate-900 text-lg"><?= $web['nama_kepsek'] ?? 'Nama Kepala Sekolah' ?></h4>
                    <p class="text-slate-500 text-sm">Kepala Sekolah</p>
                </div>
            </div>
            
            <div data-aos="fade-left">
                <span class="text-blue-600 font-bold uppercase tracking-widest text-xs mb-2 block">Sambutan Pimpinan</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">Selamat Datang di <?= $web['nama_sekolah'] ?></h2>
                <div class="prose prose-lg text-slate-600 mb-6 text-justify">
                    <?= $web['sambutan_kepsek'] ?? '<p>Sambutan kepala sekolah belum diisi.</p>' ?>
                </div>
                <div class="flex items-center gap-4">
                    <a href="#galeri" class="text-blue-600 font-bold hover:text-blue-800 transition flex items-center">
                        Lihat Dokumentasi <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <?php if(!empty($yt_embed)): ?>
        <div class="max-w-screen-xl px-4 mx-auto mt-20" data-aos="fade-up">
            <div class="text-center mb-8">
                <span class="text-red-600 font-bold uppercase tracking-widest text-xs mb-2 block">Video Profil</span>
                <h2 class="text-2xl font-bold text-slate-900">Tur Virtual Sekolah</h2>
            </div>
            <div class="relative w-full max-w-4xl mx-auto rounded-2xl overflow-hidden shadow-2xl border-4 border-white">
                <div class="aspect-w-16 aspect-h-9">
                    <iframe class="w-full h-[500px]" src="<?= $yt_embed ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <section id="berita" class="py-20 bg-white">
        <div class="max-w-screen-xl px-4 mx-auto">
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="text-blue-600 font-bold uppercase tracking-widest text-xs mb-2 block">Informasi Terkini</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Berita & Artikel</h2>
            </div>

            <?php if(!empty($berita)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach($berita as $b): ?>
                <div class="bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-xl transition-all overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                    <div class="h-48 overflow-hidden relative">
                        <img src="<?= base_url('uploads/berita/'.$b['gambar']) ?>" alt="<?= $b['judul'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-xs font-bold text-slate-800 shadow-sm">
                            <?= date('d M Y', strtotime($b['created_at'])) ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                            <?= $b['judul'] ?>
                        </h3>
                        <div class="text-slate-500 text-sm line-clamp-3 mb-4">
                            <?= strip_tags($b['isi']) ?>
                        </div>
                        <a href="<?= base_url('berita/detail/'.$b['slug']) ?>" class="inline-flex items-center text-blue-600 font-semibold text-sm hover:underline">
                            Baca Selengkapnya <i class="fas fa-long-arrow-alt-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <div class="text-center p-12 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                    <p class="text-slate-500">Belum ada berita yang dipublikasikan.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section id="galeri" class="py-20 bg-slate-900 text-white">
        <div class="max-w-screen-xl px-4 mx-auto">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="text-blue-400 font-bold uppercase tracking-widest text-xs mb-2 block">Dokumentasi</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold">Galeri Kegiatan</h2>
                </div>
            </div>

            <?php if(!empty($galeri)): ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php foreach($galeri as $i => $g): ?>
                <div class="relative group overflow-hidden rounded-xl h-48 md:h-64 cursor-pointer <?= ($i==0 || $i==3) ? 'md:col-span-2' : '' ?>" 
                     data-aos="zoom-in"
                     onclick="openLightbox('<?= base_url('uploads/galeri/'.$g['gambar']) ?>')">
                    
                    <img src="<?= base_url('uploads/galeri/'.$g['gambar']) ?>" alt="<?= $g['judul'] ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        <div>
                            <span class="text-blue-400 text-xs font-bold uppercase mb-1 block"><?= $g['kategori'] ?></span>
                            <h4 class="text-white font-bold text-lg"><?= $g['judul'] ?></h4>
                            <p class="text-xs text-gray-300 mt-1"><i class="fas fa-search-plus"></i> Lihat Foto</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <div class="text-center text-slate-500 py-10">Belum ada foto galeri.</div>
            <?php endif; ?>
        </div>
    </section>

    <footer id="kontak" class="bg-white border-t border-slate-100 pt-16 pb-8">
        <div class="max-w-screen-xl px-4 mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="<?= $pathLogo ?>" class="h-12 w-12 object-contain" alt="Logo">
                        <span class="text-xl font-extrabold text-slate-900 uppercase tracking-tight">
                            <?= $web['nama_sekolah'] ?>
                        </span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">
                        <?= $web['alamat'] ?>
                    </p>
                    <div class="flex space-x-4">
                        <?php if(!empty($web['facebook'])): ?>
                            <a href="<?= $web['facebook'] ?>" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($web['instagram'])): ?>
                            <a href="<?= $web['instagram'] ?>" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-pink-600 hover:text-white transition-all"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($web['youtube'])): ?>
                            <a href="<?= $web['youtube'] ?>" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-red-600 hover:text-white transition-all"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <h4 class="text-slate-900 font-bold mb-6">Akses Cepat</h4>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li><a href="#home" class="hover:text-blue-600 transition">Beranda</a></li>
                        <li><a href="#profile" class="hover:text-blue-600 transition">Profil Sekolah</a></li>
                        <li><a href="<?= base_url('spmb/register') ?>" class="hover:text-blue-600 transition font-bold text-blue-600">Info PPDB</a></li>
                        <li><a href="<?= base_url('auth') ?>" class="hover:text-blue-600 transition">Login Guru/Siswa</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-slate-900 font-bold mb-6">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-slate-500">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 w-5 text-blue-600"></i>
                            <span><?= $web['alamat'] ?></span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope w-5 text-blue-600"></i>
                            <span><?= $web['email'] ?></span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt w-5 text-blue-600"></i>
                            <span><?= $web['no_telp'] ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-slate-400">
                <p>&copy; <?= date('Y') ?> <?= $web['nama_sekolah'] ?>. All rights reserved.</p>
                <p>Powered by Smart School System.</p>
            </div>
        </div>
    </footer>

    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <img id="lightbox-img" src="">
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init Animation
        AOS.init({
            once: true,
            offset: 100,
            duration: 800,
        });

        // Navbar Scroll Effect
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

        // Lightbox Logic (Zoom Gambar)
        function openLightbox(src) {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-img');
            img.src = src;
            lightbox.style.display = 'flex';
            setTimeout(() => { lightbox.classList.add('show'); }, 10);
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('show');
            setTimeout(() => { lightbox.style.display = 'none'; }, 300);
        }
    </script>
</body>
</html>