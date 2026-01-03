<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =========================================================================
// 1. PUBLIC ROUTES (Landing Page & Test)
// =========================================================================
$routes->get('/', 'Home::index');
$routes->get('test-ui', function() {
    return view('test_ui');
});

// =========================================================================
// 2. AUTHENTICATION ROUTES
// =========================================================================
// Mengelompokkan semua yang berhubungan dengan login/auth
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('login', 'Auth::auth'); // Proses Login via Form
    $routes->get('logout', 'Auth::logout');
    
    // Google OAuth
    $routes->get('google', 'Auth::google_login');
    $routes->get('google_callback', 'Auth::google_callback');
    
    // OTP System
    $routes->get('verify_otp', 'Auth::verify_otp');
    $routes->post('check_otp', 'Auth::check_otp');
    $routes->get('resend_otp', 'Auth::resend_otp');
});

// Alias URL pendek untuk kemudahan akses
$routes->get('login', 'Auth::login'); // Menampilkan Halaman Login
$routes->get('logout', 'Auth::logout');

// =========================================================================
// 3. ADMIN ROUTES
// =========================================================================
// Semua URL diawali dengan /admin/
$routes->group('admin', function($routes) {
    
    // Dashboard Admin
    $routes->get('dashboard', 'Admin::index');

    // --- Manajemen User (Pengguna Sistem) ---
    $routes->group('users', function($routes) {
        $routes->get('/', 'Admin::users');
        $routes->get('tambah', 'Admin::tambah_user');
        $routes->post('simpan', 'Admin::simpan_user');
    });

    // --- Manajemen Guru ---
    $routes->get('guru', 'Admin::guru');                 // Ke fungsi guru()
    $routes->get('guru/tambah', 'Admin::guru_tambah');   // Ke fungsi guru_tambah()
    $routes->post('guru/simpan', 'Admin::guru_simpan');  // Ke fungsi guru_simpan()
    $routes->get('guru/edit/(:num)', 'Admin::guru_edit/$1');
    $routes->post('guru/update/(:num)', 'Admin::guru_update/$1');
    $routes->get('guru/hapus/(:num)', 'Admin::guru_hapus/$1');

    // --- Manajemen Siswa ---
    $routes->group('siswa', function($routes) {
        $routes->get('/', 'Admin::data_siswa');
        $routes->get('tambah', 'Admin::tambah_siswa');
        $routes->post('simpan', 'Admin::simpan_siswa'); // Proses Simpan
        
        // --- TAMBAHAN BARU ---
        $routes->get('edit/(:num)', 'Admin::edit_siswa/$1');     // Form Edit
        $routes->post('update/(:num)', 'Admin::update_siswa/$1'); // Proses Update
        $routes->get('delete/(:num)', 'Admin::delete_siswa/$1');  // Proses Hapus
        // Tambahkan route edit/delete siswa disini nanti
    // Proses hapus// Method hapus_kelas()
    });
           // --- CRUD KELAS (Pastikan ada di DALAM group admin) ---
    $routes->get('kelas', 'Admin::kelas');                  // Menampilkan tabel
    $routes->get('kelas/tambah', 'Admin::tambah_kelas');    // Form tambah
    $routes->post('kelas/simpan', 'Admin::simpan_kelas');   // Proses simpan
    $routes->get('kelas/edit/(:num)', 'Admin::edit_kelas/$1');      // Form edit
    $routes->post('kelas/update/(:num)', 'Admin::update_kelas/$1'); // Proses update
    $routes->get('kelas/hapus/(:num)', 'Admin::hapus_kelas/$1'); 

    // --- Manajemen Ekskul ---
    $routes->get('ekskul', 'Admin::ekskul');
    $routes->get('ekskul/tambah', 'Admin::ekskul_tambah');
    $routes->post('ekskul/simpan', 'Admin::ekskul_simpan');
    $routes->get('ekskul/detail/(:num)', 'Admin::ekskul_detail/$1');
    $routes->get('ekskul/edit/(:num)', 'Admin::ekskul_edit/$1');
    $routes->post('ekskul/update/(:num)', 'Admin::ekskul_update/$1');
    $routes->get('ekskul/hapus/(:num)', 'Admin::ekskul_hapus/$1');


    // --- Manajemen Orang Tua ---
    $routes->group('ortu', function($routes) {
        $routes->get('/', 'Admin::data_ortu');
        $routes->get('tambah', 'Admin::tambah_ortu');
        $routes->post('simpan', 'Admin::simpan_ortu');
        $routes->get('edit/(:num)', 'Admin::edit_ortu/$1');
        $routes->post('update/(:num)', 'Admin::update_ortu/$1');
        $routes->get('delete/(:num)', 'Admin::delete_ortu/$1');
    });
    $routes->get('ortu', 'Admin::data_ortu');
});
// app/Config/Routes.php


// =========================================================================
// 4. GURU ROUTES
// =========================================================================
$routes->group('guru', function($routes) {
    $routes->get('dashboard', 'Guru::index'); 
});

// =========================================================================
// 5. SISWA ROUTES
// =========================================================================
$routes->group('siswa', function($routes) {
    $routes->get('dashboard', 'Siswa::index');
});