<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =========================================================================
// 1. PUBLIC & AUTH ROUTES
// =========================================================================
$routes->get('/', 'Home::index');
$routes->get('test-ui', function() { return view('test_ui'); });

$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::auth');
    $routes->get('logout', 'Auth::logout');
    $routes->get('google', 'Auth::google_login');
    $routes->get('google_callback', 'Auth::google_callback');
});

$routes->get('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'role']);


// =========================================================================
// 2. ADMIN ROUTES (Protected)
// =========================================================================
$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    
    // Dashboard & Settings
    $routes->get('dashboard', 'Admin::index');
    $routes->get('pengaturan', 'Admin::pengaturan');
    $routes->post('pengaturan/update', 'Admin::pengaturan_update');

    // --- GROUP: MASTER DATA ---
    $routes->group('master', function($routes) {
        // Tahun Ajaran
        $routes->get('tahun_ajaran', 'Admin\Master::tahun_ajaran');
        $routes->post('ta_simpan', 'Admin\Master::ta_simpan');
        $routes->get('ta_aktif/(:num)', 'Admin\Master::ta_aktif/$1');
        $routes->get('ta_hapus/(:num)', 'Admin\Master::ta_hapus/$1');

        // Jurusan
        $routes->get('jurusan', 'Admin\Master::jurusan');
        $routes->post('jurusan_simpan', 'Admin\Master::jurusan_simpan');
        $routes->post('jurusan_update/(:num)', 'Admin\Master::jurusan_update/$1');
        // Download Template Jurusan
        $routes->get('download_template_jurusan', 'Admin\Master::download_template_jurusan');

        // Ruangan
        $routes->get('ruangan', 'Admin\Master::ruangan');
        $routes->post('ruangan_simpan', 'Admin\Master::ruangan_simpan');
        $routes->get('ruangan_hapus/(:num)', 'Admin\Master::ruangan_hapus/$1');

        // Kelas
        $routes->get('kelas', 'Admin\Master::kelas'); 
        $routes->post('kelas_simpan', 'Admin\Master::kelas_simpan');
        $routes->post('kelas_update/(:num)', 'Admin\Master::kelas_update/$1');
        $routes->get('kelas_detail/(:num)', 'Admin\Master::kelas_detail/$1');
        $routes->get('kelas_hapus/(:num)', 'Admin\Master::kelas_hapus/$1');
        $routes->get('download_template_kelas', 'Admin\Master::download_template_kelas');

        // Mapel
        $routes->get('mapel', 'Admin\Master::mapel');
        $routes->post('mapel_simpan', 'Admin\Master::mapel_simpan');
        $routes->get('mapel_hapus/(:num)', 'Admin\Master::mapel_hapus/$1');
        
        // --- DOWNLOAD TEMPLATE ---
        $routes->get('download_template_guru', 'Admin\Master::download_template_guru');
        $routes->get('download_template_siswa', 'Admin\Master::download_template_siswa'); // <--- INI DIA YANG DICARI
    });

    // --- GROUP: IMPORT DATA (Excel) ---
    $routes->group('import', function($routes) {
        $routes->post('kelas', 'Admin\Import::kelas');
        $routes->post('guru', 'Admin\Import::guru');
        $routes->post('jurusan', 'Admin\Import::jurusan');
        $routes->post('siswa', 'Admin\Import::siswa');
    });

    // --- MANAJEMEN USER ---
    $routes->group('users', function($routes) {
        $routes->get('/', 'Admin::users');
        $routes->post('simpan', 'Admin::simpan_user');
        $routes->post('simpan_role', 'Admin::simpan_user_role');
        $routes->post('update/(:num)', 'Admin::users_update/$1');
    });

    // --- MANAJEMEN GURU ---
    $routes->group('guru', function($routes) {
        $routes->get('/', 'Admin\Guru::index');
        $routes->post('simpan', 'Admin\Guru::simpan');
        $routes->get('hapus/(:num)', 'Admin\Guru::hapus/$1');
    });

    // --- MANAJEMEN SISWA ---
    $routes->group('siswa', function($routes) {
        $routes->get('/', 'Admin\Siswa::index');
        $routes->post('simpan', 'Admin\Siswa::simpan');
        $routes->get('delete/(:num)', 'Admin\Siswa::delete_siswa/$1');
    });

    // --- MANAJEMEN ORTU ---
    $routes->group('ortu', function($routes) {
        $routes->get('/', 'Admin::data_ortu');
        $routes->post('simpan', 'Admin::simpan_ortu');
        $routes->get('delete/(:num)', 'Admin::delete_ortu/$1');
    });

    // --- EKSKUL ---
    $routes->group('ekskul', function($routes) {
        $routes->get('/', 'Admin::ekskul');
        $routes->post('simpan', 'Admin::ekskul_simpan');
        $routes->get('hapus/(:num)', 'Admin::ekskul_hapus/$1');
    });

});


// =========================================================================
// 3. ROLE ROUTES
// =========================================================================
$routes->group('guru', ['filter' => 'role:guru'], function($routes) {
    $routes->get('dashboard', 'Guru::index');
});

$routes->group('siswa', ['filter' => 'role:siswa'], function($routes) {
    $routes->get('dashboard', 'Siswa::index');
});

$routes->group('piket', ['filter' => 'role:piket'], function($routes) {
    $routes->get('dashboard', 'Piket::index');
    $routes->get('jurnal', 'Piket::jurnal');
});