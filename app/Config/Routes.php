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
    // Route untuk OTP
    $routes->get('verify_otp', 'Auth::verify_otp');          // Menampilkan Halaman Input OTP
    $routes->post('verify_otp', 'Auth::verify_otp');
    $routes->post('check_otp', 'Auth::check_otp'); // Memproses Submit OTP
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
        $routes->get('hapus_semua', 'Admin\Siswa::hapus_semua');
    });

    $routes->group('rombel', function($routes) {
    $routes->get('/', 'Admin\Rombel::index');
    $routes->get('atur/(:num)', 'Admin\Rombel::atur/$1');
    $routes->post('proses_pindah', 'Admin\Rombel::proses_pindah');
    $routes->get('alumni', 'Admin\Rombel::alumni');
});
 $routes->get('jam', 'Admin\Jam::index');
    $routes->post('jam/simpan', 'Admin\Jam::simpan');
    $routes->get('jam/hapus/(:num)', 'Admin\Jam::hapus/$1');


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
$routes->group('jadwal', function($routes) {
    $routes->get('/', 'Admin\Jadwal::index');
    $routes->get('cetak', 'Admin\Jadwal::cetak'); 
    $routes->get('rekap', 'Admin\Jadwal::rekap');
    $routes->get('rekap/cetak', 'Admin\Jadwal::cetakRekap');// <--- TAMBAH INI
    $routes->post('simpan', 'Admin\Jadwal::simpan');
    $routes->get('hapus/(:num)', 'Admin\Jadwal::hapus/$1');

});
$routes->get('jadwalujian', 'Admin\JadwalUjian::index');
    $routes->get('jadwalujian/hapus/(:num)', 'Admin\JadwalUjian::hapus/$1');
    $routes->get('jadwalujian/tambah', 'Admin\JadwalUjian::tambah');
$routes->post('jadwalujian/simpan', 'Admin\JadwalUjian::simpan');
$routes->get('jadwalujian/edit/(:num)', 'Admin\JadwalUjian::edit/$1');
$routes->post('jadwalujian/update/(:num)', 'Admin\JadwalUjian::update/$1');
// Manajemen Atur Ruangan (Plotting)
$routes->get('aturruangan', 'Admin\AturRuangan::index');
$routes->get('aturruangan/kelola/(:num)', 'Admin\AturRuangan::kelola/$1');
$routes->post('aturruangan/tambah', 'Admin\AturRuangan::tambah');
$routes->get('aturruangan/hapus/(:num)/(:num)/(:num)', 'Admin\AturRuangan::hapus/$1/$2/$3');

// Monitoring Ruangan (yang tadi dibuat)
$routes->get('monitoring-ruang', 'Admin\MonitoringRuang::index');
    $routes->get('monitoring-ruang/lihat/(:num)', 'Admin\MonitoringRuang::lihat/$1');
    $routes->post('monitoring-ruang/aksi_masal', 'Admin\MonitoringRuang::aksi_masal');
$routes->get('bank-soal', 'Admin\BankSoal::index');
    $routes->get('bank-soal/detail/(:num)', 'Admin\BankSoal::detail/$1');
    $routes->post('bank-soal/update-target', 'Admin\BankSoal::updateTarget');
});


// =========================================================================
// 3. ROLE ROUTES
// =========================================================================
$routes->group('guru', ['filter' => 'role:guru'], function($routes) {
   $routes->get('dashboard', 'Guru\Dashboard::index');
    
    // MENU JADWAL
    $routes->get('jadwal', 'Guru\Jadwal::index');
    
    // Menu Nilai (Persiapan nanti)
    $routes->get('nilai', 'Guru\Nilai::index');
    // --- FITUR BANK SOAL ---
    $routes->get('bank_soal', 'Guru\BankSoal::index');
    $routes->post('bank_soal/simpan', 'Guru\BankSoal::simpan');
    $routes->get('bank_soal/hapus/(:num)', 'Guru\BankSoal::hapus/$1');

    // Route untuk Kelola Butir Soal (Persiapan Langkah Selanjutnya)
    $routes->get('bank_soal/kelola/(:num)', 'Guru\BankSoal::kelola/$1');
    // CRUD SOAL
    $routes->get('bank_soal/kelola/(:num)', 'Guru\BankSoal::kelola/$1');
    $routes->post('bank_soal/simpanSoal', 'Guru\BankSoal::simpanSoal');
    $routes->get('bank_soal/hapusSoal/(:num)/(:num)', 'Guru\BankSoal::hapusSoal/$1/$2');
    $routes->post('bank_soal/simpanSoalAjax', 'Guru\BankSoal::simpanSoalAjax');
$routes->post('bank_soal/importSoal', 'Guru\BankSoal::importSoal');
$routes->get('bank_soal/getDetailSoal/(:num)', 'Guru\BankSoal::getDetailSoal/$1');
$routes->get('bank_soal/hapusSoal/(:num)/(:num)', 'Guru\BankSoal::hapusSoal/$1/$2');
$routes->get('bank_soal/downloadTemplateWord', 'Guru\BankSoal::downloadTemplateWord');
$routes->post('bank_soal/importSoalWord', 'Guru\BankSoal::importSoalWord');
// MENU JADWAL UJIAN (Pemisahan dari Bank Soal)
    $routes->get('ujian', 'Guru\Ujian::index');            // List Jadwal
    $routes->get('ujian/tambah', 'Guru\Ujian::tambah');    // Form Tambah Jadwal
    $routes->post('ujian/simpan', 'Guru\Ujian::simpan');   // Proses Simpan
    $routes->get('ujian/hapus/(:num)', 'Guru\Ujian::hapus/$1');
    $routes->post('ujian/toggle_status', 'Guru\Ujian::toggleStatus'); // On/Off Ujian
    // ... di dalam group 'guru' ...
$routes->get('ujian/monitoring/(:num)', 'Guru\Ujian::monitoring/$1'); // <--- TAMBAHKAN INI
$routes->post('ujian/reset_peserta', 'Guru\Ujian::resetPeserta');       // <--- TAMBAHKAN INI JUGA (Buat Reset)
// MONITORING UJIAN (MENGAWAS)
    $routes->get('monitoring', 'Guru\Monitoring::index');
    $routes->get('monitoring/lihat/(:num)', 'Guru\Monitoring::lihat/$1');
    $routes->post('monitoring/aksi_masal', 'Guru\Monitoring::aksi_masal');
    $routes->get('monitoring/lihat/(:num)', 'Guru\Monitoring::index/$1');
    $routes->get('hasil/index/(:num)', 'Guru\Hasil::index/$1');
    $routes->get('hasil/pdf/(:num)', 'Guru\Hasil::pdf/$1');
    $routes->get('hasil/excel/(:num)', 'Guru\Hasil::excel/$1');
});

$routes->group('siswa', ['filter' => 'role:siswa'], function($routes) {
    // Ubah $this menjadi $routes
    $routes->get('dashboard', 'Siswa\Dashboard::index'); 
    $routes->get('ujian', 'Siswa\Ujian::index');
    $routes->get('ujian/konfirmasi/(:num)', 'Siswa\Ujian::konfirmasi/$1');
    $routes->post('ujian/mulai', 'Siswa\Ujian::mulai');
    $routes->get('ujian/kerjakan/(:num)', 'Siswa\Ujian::kerjakan/$1');
    
    $routes->post('ujian/simpanjawaban', 'Siswa\Ujian::simpanJawaban');
   $routes->post('ujian/selesaiUjian', 'Siswa\Ujian::selesaiUjian');
    
    // --- TAMBAHKAN INI (Jalur Anti-Cheat) ---
    $routes->post('ujian/catatPelanggaran', 'Siswa\Ujian::catatPelanggaran'); 
    $routes->post('ujian/blokirSiswa', 'Siswa\Ujian::blokirSiswa'); // Opsional jika pakai logic blokir terpisah
    $routes->post('ujian/simpanJawaban', 'Siswa\Ujian::simpanJawaban');
    // KEUANGAN SISWA
    $routes->get('keuangan', 'Siswa\Keuangan::index');
    $routes->post('keuangan/bayar_online', 'Siswa\Keuangan::bayar_online');
});

$routes->group('piket', ['filter' => 'role:piket'], function($routes) {
    $routes->get('dashboard', 'Piket::index');
    $routes->get('jurnal', 'Piket::jurnal');
});

// MODUL KEUANGAN SEKOLAH
$routes->group('admin/keuangan', ['filter' => 'role:admin,bendahara'], function($routes) {
    
    // Master Pos Bayar
    $routes->get('pos', 'Admin\Keuangan\PosBayar::index');
    $routes->post('pos/simpan', 'Admin\Keuangan\PosBayar::simpan');
    $routes->post('pos/update', 'Admin\Keuangan\PosBayar::update');
    $routes->post('pos/hapus', 'Admin\Keuangan\PosBayar::hapus');

    // Setting Jenis Bayar
    $routes->get('jenis', 'Admin\Keuangan\JenisBayar::index');
    $routes->post('jenis/simpan', 'Admin\Keuangan\JenisBayar::simpan');
    $routes->post('jenis/hapus', 'Admin\Keuangan\JenisBayar::hapus');
    // GENERATE TAGIHAN
    $routes->post('tagihan/generate', 'Admin\Keuangan\Tagihan::generate');
    // MANAJEMEN TAGIHAN (Generate & Edit Nominal)
    $routes->get('tagihan/kelola/(:num)', 'Admin\Keuangan\Tagihan::kelola/$1'); // Halaman Edit
    $routes->post('tagihan/generate', 'Admin\Keuangan\Tagihan::generate');       // Proses Generate
    $routes->post('tagihan/update_nominal', 'Admin\Keuangan\Tagihan::update_nominal'); // AJAX Edit
    // HALAMAN KASIR / PEMBAYARAN
    $routes->get('pembayaran', 'Admin\Keuangan\Pembayaran::index');           // Cari Siswa
    $routes->get('pembayaran/siswa/(:num)', 'Admin\Keuangan\Pembayaran::transaksi/$1'); // Halaman Bayar
    $routes->post('pembayaran/proses', 'Admin\Keuangan\Pembayaran::proses_bayar');      // Aksi Bayar
    $routes->post('pembayaran/batal', 'Admin\Keuangan\Pembayaran::batal'); // Route Batal
    // CETAK STRUK (Diakses dari halaman kasir/riwayat)
    $routes->get('pembayaran/cetak/(:num)', 'Admin\Keuangan\Pembayaran::cetak/$1');

    // LAPORAN KEUANGAN
    $routes->get('laporan', 'Admin\Keuangan\Laporan::index');
    $routes->get('laporan/cetak', 'Admin\Keuangan\Laporan::cetak_harian'); // Cetak Laporan PDF/Print
    $routes->get('laporan/cetak_transaksi', 'Admin\Keuangan\Laporan::cetak_transaksi'); // Cetak Pemasukan
    $routes->get('laporan/cetak_tunggakan', 'Admin\Keuangan\Laporan::cetak_tunggakan'); // Cetak Tunggakan
    // PENGELUARAN OPERASIONAL
    $routes->get('pengeluaran', 'Admin\Keuangan\Pengeluaran::index');
    $routes->post('pengeluaran/simpan', 'Admin\Keuangan\Pengeluaran::simpan');
    $routes->post('pengeluaran/hapus', 'Admin\Keuangan\Pengeluaran::hapus');
    
    // Master Data Pengeluaran (Ajax Save)
    $routes->post('pengeluaran/master/simpan_divisi', 'Admin\Keuangan\Pengeluaran::simpan_divisi');
    $routes->post('pengeluaran/master/simpan_jenis', 'Admin\Keuangan\Pengeluaran::simpan_jenis');
});

// ROUTE CALLBACK TRIPAY (Akses Publik untuk Server Tripay)
$routes->post('callback/tripay', 'TripayCallback::index');