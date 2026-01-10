<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =========================================================================
// 1. PUBLIC & AUTH ROUTES (Tanpa Filter)
// =========================================================================

// Landing Page & Test
$routes->get('/', 'Home::index');
$routes->get('test-ui', function() {
    return view('test_ui');
});

// Authentication System
$routes->group('auth', function($routes) {
    // Login Manual
    $routes->get('/', 'Auth::index');           // Form Login
    $routes->get('login', 'Auth::login');       // Alias Form Login
    $routes->post('login', 'Auth::auth');       // Proses Submit Login
    $routes->get('logout', 'Auth::logout');     // Logout

    // Google OAuth
    $routes->get('google', 'Auth::google_login');
    $routes->get('google_callback', 'Auth::google_callback');

    // OTP Verification (Telegram/WA)
    $routes->get('verify_otp', 'Auth::verify_otp');
    $routes->post('check_otp', 'Auth::check_otp');
    $routes->get('resend_otp', 'Auth::resend_otp');
});

// Alias URL Pendek
$routes->get('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// ROUTE PENYELAMAT (Traffic Cop)
// Mengarahkan user ke dashboard sesuai role masing-masing
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'role']);


// =========================================================================
// 2. ADMIN ROUTES (Protected: Admin Only)
// =========================================================================
$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    
    // Dashboard Admin
    $routes->get('dashboard', 'Admin::index');

    // --- Manajemen User System ---
    $routes->group('users', function($routes) {
        $routes->get('/', 'Admin::users');
        $routes->get('tambah', 'Admin::tambah_user');
        $routes->post('simpan', 'Admin::simpan_user');
        $routes->post('simpan_role', 'Admin::simpan_user_role');
        // Tambahkan edit/delete user disini nanti
        // Route untuk memproses update data user (Nama & Password)
$routes->post('update/(:num)', 'Admin::users_update/$1');
$routes->post('tambah', 'Admin::users_tambah');
    });
    // Grouping Master Data
$routes->group('master', ['filter' => 'role:admin'], function($routes) {
    // Tahun Ajaran
    $routes->get('tahun_ajaran', 'Admin\Master::tahun_ajaran');
    $routes->post('ta_simpan', 'Admin\Master::ta_simpan');
    $routes->get('ta_aktif/(:num)', 'Admin\Master::ta_aktif/$1');
    $routes->get('ta_hapus/(:num)', 'Admin\Master::ta_hapus/$1');

    // Jurusan
    $routes->get('jurusan', 'Admin\Master::jurusan');
    $routes->post('jurusan_simpan', 'Admin\Master::jurusan_simpan');
    $routes->post('jurusan_update/(:num)', 'Admin\Master::jurusan_update/$1');

    // Ruangan
   $routes->get('ruangan', 'Admin\Master::ruangan');
$routes->post('ruangan_simpan', 'Admin\Master::ruangan_simpan');
$routes->get('ruangan_hapus/(:num)', 'Admin\Master::ruangan_hapus/$1');

$routes->get('kelas', 'Admin\Master::kelas'); 
$routes->post('kelas_simpan', 'Admin\Master::kelas_simpan');
$routes->post('kelas_update/(:num)', 'Admin\Master::kelas_update/$1');
$routes->get('kelas_detail/(:num)', 'Admin\Master::kelas_detail/$1');
$routes->get('kelas_hapus/(:num)', 'Admin\Master::kelas_hapus/$1');

        $routes->get('mapel', 'Admin\Master::mapel'); // Menampilkan halaman
        $routes->post('mapel_simpan', 'Admin\Master::mapel_simpan'); // PINTU SIMPAN (POST)
        $routes->get('mapel_hapus/(:num)', 'Admin\Master::mapel_hapus/$1'); // PINTU HAPUS
});
    $routes->get('pengaturan', 'Admin::pengaturan');
    $routes->post('pengaturan/update', 'Admin::pengaturan_update');

    // --- Manajemen Data Guru ---
    $routes->group('guru', function($routes) {
        $routes->get('/', 'Admin::guru');
        $routes->get('tambah', 'Admin::guru_tambah');
        $routes->post('simpan', 'Admin::guru_simpan');
        $routes->get('edit/(:num)', 'Admin::guru_edit/$1');
        $routes->post('update/(:num)', 'Admin::guru_update/$1');
        $routes->get('hapus/(:num)', 'Admin::guru_hapus/$1');
    });

    // --- Manajemen Data Siswa ---
    $routes->group('siswa', function($routes) {
        $routes->get('/', 'Admin::data_siswa');
        $routes->get('tambah', 'Admin::tambah_siswa');
        $routes->post('simpan', 'Admin::simpan_siswa');
        $routes->get('edit/(:num)', 'Admin::edit_siswa/$1');
        $routes->post('update/(:num)', 'Admin::update_siswa/$1');
        $routes->get('delete/(:num)', 'Admin::delete_siswa/$1');
    });

    // --- Manajemen Data Orang Tua ---
    $routes->group('ortu', function($routes) {
        $routes->get('/', 'Admin::data_ortu');
        $routes->get('tambah', 'Admin::tambah_ortu');
        $routes->post('simpan', 'Admin::simpan_ortu');
        $routes->get('edit/(:num)', 'Admin::edit_ortu/$1');
        $routes->post('update/(:num)', 'Admin::update_ortu/$1');
        $routes->get('delete/(:num)', 'Admin::delete_ortu/$1');
    });

    // --- Manajemen Kelas ---
    $routes->group('kelas', function($routes) {
        $routes->get('/', 'Admin::kelas');
        $routes->get('tambah', 'Admin::tambah_kelas');
        $routes->post('simpan', 'Admin::simpan_kelas');
        $routes->get('edit/(:num)', 'Admin::edit_kelas/$1');
        $routes->post('update/(:num)', 'Admin::update_kelas/$1');
        $routes->get('hapus/(:num)', 'Admin::hapus_kelas/$1');
    });

    // --- Manajemen Ekskul ---
    $routes->group('ekskul', function($routes) {
        $routes->get('/', 'Admin::ekskul');
        $routes->get('tambah', 'Admin::ekskul_tambah');
        $routes->post('simpan', 'Admin::ekskul_simpan');
        $routes->get('detail/(:num)', 'Admin::ekskul_detail/$1');
        $routes->get('edit/(:num)', 'Admin::ekskul_edit/$1');
        $routes->post('update/(:num)', 'Admin::ekskul_update/$1');
        $routes->get('hapus/(:num)', 'Admin::ekskul_hapus/$1');
    });
});


// =========================================================================
// 3. GURU ROUTES (Protected: Guru Only)
// =========================================================================
$routes->group('guru', ['filter' => 'role:guru'], function($routes) {
    $routes->get('dashboard', 'Guru::index'); 
    
    // Nanti tambah route jadwal, penilaian, dll disini
    // $routes->get('jadwal', 'Guru::jadwal');
});


// =========================================================================
// 4. SISWA ROUTES (Protected: Siswa Only)
// =========================================================================
$routes->group('siswa', ['filter' => 'role:siswa'], function($routes) {
    $routes->get('dashboard', 'Siswa::index');
    
    // Nanti tambah route lihat nilai, jadwal siswa disini
});


// =========================================================================
// 5. PIKET ROUTES (Protected: Piket Only)
// =========================================================================
$routes->group('piket', ['filter' => 'role:piket'], function($routes) {
    $routes->get('dashboard', 'Piket::index');
    $routes->get('jurnal', 'Piket::jurnal');
});
$routes->post('admin/import/kelas', 'Admin\Import::kelas');