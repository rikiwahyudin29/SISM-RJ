<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =========================================================================
// 1. PUBLIC & AUTH ROUTES (Akses Tanpa Login)
// =========================================================================
$routes->get('/', 'Home::index');
$routes->get('test-ui', function() { return view('test_ui'); });

// Callback Payment Gateway
$routes->post('callback/tripay', 'TripayCallback::index');

// Auth Group
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::auth');
    $routes->get('logout', 'Auth::logout');
    
    // Google Auth
    $routes->get('google', 'Auth::google_login');
    $routes->get('google_callback', 'Auth::google_callback');
    
    // OTP
    $routes->get('verify_otp', 'Auth::verify_otp');
    $routes->post('verify_otp', 'Auth::verify_otp');
    $routes->post('check_otp', 'Auth::check_otp');
});

// Shortcut Login/Logout
$routes->get('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Global Dashboard Redirect (Filter Role Otomatis)
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'role']);


// =========================================================================
// 2. SHARED ROUTES: ADMIN & PIKET (Presensi & Monitoring)
// =========================================================================
// Fitur ini bisa diakses oleh ADMIN dan PIKET
$routes->group('admin', ['filter' => 'role:admin,piket'], function($routes) {

    // --- MONITORING KBM & PIKET ---
    $routes->get('piket', 'Admin\Piket::index'); // Dashboard Monitoring

    // --- PRESENSI SISWA ---
    $routes->group('presensi', function($routes) {
        // Scanner
        $routes->get('scanner', 'Admin\Presensi::scanner');
        $routes->post('proses_scan', 'Admin\Presensi::proses_scan'); // Web
        
        // Manual & Izin
        $routes->get('izin', 'Admin\Presensi::izin');
        $routes->post('simpan_izin', 'Admin\Presensi::simpan_izin');
        $routes->get('hapus_izin/(:num)', 'Admin\Presensi::hapus_izin/$1');
        
        // Laporan & Rekap
        $routes->get('laporan', 'Admin\Presensi::laporan');
        $routes->get('rekap', 'Admin\Presensi::rekap');
        $routes->get('cetak_harian', 'Admin\Presensi::cetak_harian');
        $routes->get('cetak_rekap', 'Admin\Presensi::cetak_rekap');
        
        // Helper Data
        $routes->get('get_siswa_by_kelas/(:num)', 'Admin\Presensi::get_siswa_by_kelas/$1');
        $routes->get('verifikasi/(:num)/(:segment)', 'Admin\Presensi::verifikasi/$1/$2');
    });

    // --- PRESENSI GURU ---
    $routes->group('presensi_guru', function($routes) {
        $routes->get('/', 'Admin\PresensiGuru::index');
        $routes->post('simpan_manual', 'Admin\PresensiGuru::simpan_manual');
        $routes->get('rekap', 'Admin\PresensiGuru::rekap');
        $routes->get('cetak_rekap', 'Admin\PresensiGuru::cetak_rekap');
    });

    // --- MONITORING JURNAL KBM ---
    $routes->get('jurnal', 'Admin\Jurnal::index');
    $routes->get('jurnal/cetak', 'Admin\Jurnal::cetak');
});

// Route API untuk Alat IoT (Tidak butuh session login, biasanya pakai token API)
$routes->post('api/iot/scan', 'Admin\Presensi::proses_scan');


// =========================================================================
// 3. ADMIN ONLY ROUTES (Full Control)
// =========================================================================
$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    
    // Dashboard Utama & Pengaturan
    $routes->get('dashboard', 'Admin::index');
    $routes->get('pengaturan', 'Admin::pengaturan');
    $routes->post('pengaturan/update', 'Admin::pengaturan_update');

    // Setting Presensi (Jam & Lokasi) -> Hanya Admin yang boleh ubah
    $routes->get('presensi/jam', 'Admin\JamPresensi::index');
    $routes->post('jam-presensi/update', 'Admin\JamPresensi::update');

    // Cetak Kartu Pelajar
    $routes->group('kartu', function($routes) {
        $routes->get('/', 'Admin\Kartu::index');
        $routes->get('cetak', 'Admin\Kartu::cetak');
        $routes->get('registrasi', 'Admin\Kartu::registrasi');
        $routes->post('simpan_uid', 'Admin\Kartu::simpan_uid');
    });

    // --- MASTER DATA ---
    $routes->group('master', function($routes) {
        // Tahun Ajaran
        $routes->get('tahun_ajaran', 'Admin\Master::tahun_ajaran');
        $routes->post('ta_simpan', 'Admin\Master::ta_simpan');
        $routes->get('ta_aktif/(:num)', 'Admin\Master::ta_aktif/$1');
        $routes->get('ta_hapus/(:num)', 'Admin\Master::ta_hapus/$1');

        // Jurusan & Ruangan
        $routes->get('jurusan', 'Admin\Master::jurusan');
        $routes->post('jurusan_simpan', 'Admin\Master::jurusan_simpan');
        $routes->post('jurusan_update/(:num)', 'Admin\Master::jurusan_update/$1');
        $routes->get('download_template_jurusan', 'Admin\Master::download_template_jurusan');
        
        $routes->get('ruangan', 'Admin\Master::ruangan');
        $routes->post('ruangan_simpan', 'Admin\Master::ruangan_simpan');
        $routes->get('ruangan_hapus/(:num)', 'Admin\Master::ruangan_hapus/$1');

        // Kelas & Mapel
        $routes->get('kelas', 'Admin\Master::kelas'); 
        $routes->post('kelas_simpan', 'Admin\Master::kelas_simpan');
        $routes->post('kelas_update/(:num)', 'Admin\Master::kelas_update/$1');
        $routes->get('kelas_detail/(:num)', 'Admin\Master::kelas_detail/$1');
        $routes->get('kelas_hapus/(:num)', 'Admin\Master::kelas_hapus/$1');
        $routes->get('download_template_kelas', 'Admin\Master::download_template_kelas');

        $routes->get('mapel', 'Admin\Master::mapel');
        $routes->post('mapel_simpan', 'Admin\Master::mapel_simpan');
        $routes->get('mapel_hapus/(:num)', 'Admin\Master::mapel_hapus/$1');
        
        // Download Templates
        $routes->get('download_template_guru', 'Admin\Master::download_template_guru');
        $routes->get('download_template_siswa', 'Admin\Master::download_template_siswa');
    });

    // --- IMPORT DATA ---
    $routes->group('import', function($routes) {
        $routes->post('kelas', 'Admin\Import::kelas');
        $routes->post('guru', 'Admin\Import::guru');
        $routes->post('jurusan', 'Admin\Import::jurusan');
        $routes->post('siswa', 'Admin\Import::siswa');
    });

    // --- MANAJEMEN USER & SDM ---
    $routes->group('users', function($routes) {
        $routes->get('/', 'Admin::users');
        $routes->post('simpan', 'Admin::simpan_user');
        $routes->post('simpan_role', 'Admin::simpan_user_role');
        $routes->post('update/(:num)', 'Admin::users_update/$1');
    });

    $routes->group('guru', function($routes) {
        $routes->get('/', 'Admin\Guru::index');
        $routes->post('simpan', 'Admin\Guru::simpan');
        $routes->get('hapus/(:num)', 'Admin\Guru::hapus/$1');
    });

    $routes->group('siswa', function($routes) {
        $routes->get('/', 'Admin\Siswa::index');
        $routes->post('simpan', 'Admin\Siswa::simpan');
        $routes->get('delete/(:num)', 'Admin\Siswa::delete_siswa/$1');
        $routes->get('hapus_semua', 'Admin\Siswa::hapus_semua');
    });
    
    $routes->group('ortu', function($routes) {
        $routes->get('/', 'Admin::data_ortu');
        $routes->post('simpan', 'Admin::simpan_ortu');
        $routes->get('delete/(:num)', 'Admin::delete_ortu/$1');
    });

    // --- AKADEMIK (Jadwal, Rombel, Ujian) ---
    $routes->group('rombel', function($routes) {
        $routes->get('/', 'Admin\Rombel::index');
        $routes->get('atur/(:num)', 'Admin\Rombel::atur/$1');
        $routes->post('proses_pindah', 'Admin\Rombel::proses_pindah');
        $routes->get('alumni', 'Admin\Rombel::alumni');
    });

    $routes->group('ekskul', function($routes) {
        $routes->get('/', 'Admin::ekskul');
        $routes->post('simpan', 'Admin::ekskul_simpan');
        $routes->get('hapus/(:num)', 'Admin::ekskul_hapus/$1');
    });

    $routes->group('jadwal', function($routes) {
        $routes->get('/', 'Admin\Jadwal::index');
        $routes->get('cetak', 'Admin\Jadwal::cetak'); 
        $routes->get('rekap', 'Admin\Jadwal::rekap');
        $routes->get('rekap/cetak', 'Admin\Jadwal::cetakRekap');
        $routes->post('simpan', 'Admin\Jadwal::simpan');
        $routes->get('hapus/(:num)', 'Admin\Jadwal::hapus/$1');
    });

    // Ujian & Bank Soal
    $routes->get('jadwalujian', 'Admin\JadwalUjian::index');
    $routes->get('jadwalujian/tambah', 'Admin\JadwalUjian::tambah');
    $routes->post('jadwalujian/simpan', 'Admin\JadwalUjian::simpan');
    $routes->get('jadwalujian/edit/(:num)', 'Admin\JadwalUjian::edit/$1');
    $routes->post('jadwalujian/update/(:num)', 'Admin\JadwalUjian::update/$1');
    $routes->get('jadwalujian/hapus/(:num)', 'Admin\JadwalUjian::hapus/$1');

    $routes->get('aturruangan', 'Admin\AturRuangan::index');
    $routes->get('aturruangan/kelola/(:num)', 'Admin\AturRuangan::kelola/$1');
    $routes->post('aturruangan/tambah', 'Admin\AturRuangan::tambah');
    $routes->get('aturruangan/hapus/(:num)/(:num)/(:num)', 'Admin\AturRuangan::hapus/$1/$2/$3');

    $routes->get('monitoring-ruang', 'Admin\MonitoringRuang::index');
    $routes->get('monitoring-ruang/lihat/(:num)', 'Admin\MonitoringRuang::lihat/$1');
    $routes->post('monitoring-ruang/aksi_masal', 'Admin\MonitoringRuang::aksi_masal');

    $routes->get('bank-soal', 'Admin\BankSoal::index');
    $routes->get('bank-soal/detail/(:num)', 'Admin\BankSoal::detail/$1');
    $routes->post('bank-soal/update-target', 'Admin\BankSoal::updateTarget');
});


// C. GROUP KEUANGAN (Boleh Admin, Bendahara, Keuangan)
// ----------------------------------------------------
$routes->group('admin/keuangan', ['filter' => 'role:admin,bendahara,keuangan'], function($routes) {
    
    // Master
    $routes->get('pos', 'Admin\Keuangan\PosBayar::index');
    $routes->post('pos/simpan', 'Admin\Keuangan\PosBayar::simpan');
    $routes->post('pos/update', 'Admin\Keuangan\PosBayar::update');
    $routes->post('pos/hapus', 'Admin\Keuangan\PosBayar::hapus');

    $routes->get('jenis', 'Admin\Keuangan\JenisBayar::index');
    $routes->post('jenis/simpan', 'Admin\Keuangan\JenisBayar::simpan');
    $routes->post('jenis/hapus', 'Admin\Keuangan\JenisBayar::hapus');

    // Tagihan & Pembayaran
    $routes->post('tagihan/generate', 'Admin\Keuangan\Tagihan::generate');
    $routes->get('tagihan/kelola/(:num)', 'Admin\Keuangan\Tagihan::kelola/$1');
    $routes->post('tagihan/update_nominal', 'Admin\Keuangan\Tagihan::update_nominal');
    
    $routes->get('pembayaran', 'Admin\Keuangan\Pembayaran::index');
    $routes->get('pembayaran/siswa/(:num)', 'Admin\Keuangan\Pembayaran::transaksi/$1');
    $routes->post('pembayaran/proses', 'Admin\Keuangan\Pembayaran::proses_bayar');
    $routes->post('pembayaran/batal', 'Admin\Keuangan\Pembayaran::batal');
    $routes->get('pembayaran/cetak/(:num)', 'Admin\Keuangan\Pembayaran::cetak/$1');

    // Laporan
    $routes->get('laporan', 'Admin\Keuangan\Laporan::index');
    $routes->get('laporan/cetak', 'Admin\Keuangan\Laporan::cetak_harian');
    $routes->get('laporan/cetak_transaksi', 'Admin\Keuangan\Laporan::cetak_transaksi');
    $routes->get('laporan/cetak_tunggakan', 'Admin\Keuangan\Laporan::cetak_tunggakan');
    $routes->get('laporan/export_excel', 'Admin\Keuangan\Laporan::export_excel');

    // Pengeluaran
    $routes->get('pengeluaran', 'Admin\Keuangan\Pengeluaran::index');
    $routes->post('pengeluaran/simpan', 'Admin\Keuangan\Pengeluaran::simpan');
    $routes->post('pengeluaran/hapus', 'Admin\Keuangan\Pengeluaran::hapus');
    $routes->post('pengeluaran/master/simpan_divisi', 'Admin\Keuangan\Pengeluaran::simpan_divisi');
    $routes->post('pengeluaran/master/simpan_jenis', 'Admin\Keuangan\Pengeluaran::simpan_jenis');
    
    $routes->get('log', 'Admin\Keuangan\Log::index');
});


// =========================================================================
// 5. GURU ROUTES
// =========================================================================
$routes->group('guru', ['filter' => 'role:guru'], function($routes) {
    $routes->get('dashboard', 'Guru\Dashboard::index');
    
    // Akademik
    $routes->get('jadwal', 'Guru\Jadwal::index');
    $routes->get('nilai', 'Guru\Nilai::index');
    
    // Jurnal KBM
    $routes->get('jurnal', 'Guru\Jurnal::index');
    $routes->get('jurnal/input', 'Guru\Jurnal::input');
    $routes->post('jurnal/simpan', 'Guru\Jurnal::simpan');
    $routes->get('jurnal/hapus/(:num)', 'Guru\Jurnal::hapus/$1');
    $routes->get('jurnal/absen/(:num)', 'Guru\Jurnal::absen/$1');
    $routes->post('jurnal/simpan_absen', 'Guru\Jurnal::simpan_absen');
    
    // Presensi Guru
    $routes->get('presensi', 'Guru\Presensi::index');
    $routes->get('presensi/rekap', 'Guru\Presensi::rekap');
    $routes->get('presensi/izin', 'Guru\Presensi::izin');
    $routes->post('presensi/ajukan', 'Guru\Presensi::ajukan');
    $routes->get('presensi/cetak_rekap', 'Guru\Presensi::cetak_rekap');

    // Bank Soal
    $routes->get('bank_soal', 'Guru\BankSoal::index');
    $routes->post('bank_soal/simpan', 'Guru\BankSoal::simpan');
    $routes->get('bank_soal/hapus/(:num)', 'Guru\BankSoal::hapus/$1');
    $routes->get('bank_soal/kelola/(:num)', 'Guru\BankSoal::kelola/$1');
    $routes->post('bank_soal/simpanSoal', 'Guru\BankSoal::simpanSoal');
    $routes->post('bank_soal/simpanSoalAjax', 'Guru\BankSoal::simpanSoalAjax');
    $routes->post('bank_soal/importSoal', 'Guru\BankSoal::importSoal');
    $routes->get('bank_soal/getDetailSoal/(:num)', 'Guru\BankSoal::getDetailSoal/$1');
    $routes->get('bank_soal/hapusSoal/(:num)/(:num)', 'Guru\BankSoal::hapusSoal/$1/$2');
    $routes->get('bank_soal/downloadTemplateWord', 'Guru\BankSoal::downloadTemplateWord');
    $routes->post('bank_soal/importSoalWord', 'Guru\BankSoal::importSoalWord');

    // Ujian
    $routes->get('ujian', 'Guru\Ujian::index');
    $routes->get('ujian/tambah', 'Guru\Ujian::tambah');
    $routes->post('ujian/simpan', 'Guru\Ujian::simpan');
    $routes->get('ujian/hapus/(:num)', 'Guru\Ujian::hapus/$1');
    $routes->post('ujian/toggle_status', 'Guru\Ujian::toggleStatus');
    $routes->get('ujian/monitoring/(:num)', 'Guru\Ujian::monitoring/$1');
    $routes->post('ujian/reset_peserta', 'Guru\Ujian::resetPeserta');

    // Monitoring & Hasil
    $routes->get('monitoring', 'Guru\Monitoring::index');
    $routes->get('monitoring/lihat/(:num)', 'Guru\Monitoring::index/$1');
    $routes->post('monitoring/aksi_masal', 'Guru\Monitoring::aksi_masal');
    
    $routes->get('hasil/index/(:num)', 'Guru\Hasil::index/$1');
    $routes->get('hasil/pdf/(:num)', 'Guru\Hasil::pdf/$1');
    $routes->get('hasil/excel/(:num)', 'Guru\Hasil::excel/$1');
});


// =========================================================================
// 6. SISWA ROUTES
// =========================================================================
$routes->group('siswa', ['filter' => 'role:siswa'], function($routes) {
    $routes->get('dashboard', 'Siswa\Dashboard::index');
    
    // Keuangan
    $routes->get('keuangan', 'Siswa\Keuangan::index');
    $routes->post('keuangan/bayar_online', 'Siswa\Keuangan::bayar_online');

    // Presensi
    $routes->get('presensi', 'Siswa\Presensi::index');
    $routes->get('presensi/rekap', 'Siswa\Presensi::rekap');
    $routes->get('presensi/izin', 'Siswa\Presensi::izin');
    $routes->post('presensi/ajukan', 'Siswa\Presensi::ajukan');
    $routes->get('presensi/cetak_rekap', 'Siswa\Presensi::cetak_rekap');
    $routes->get('presensi/pelajaran', 'Siswa\Presensi::pelajaran');

    // Ujian
    $routes->get('ujian', 'Siswa\Ujian::index');
    $routes->get('ujian/konfirmasi/(:num)', 'Siswa\Ujian::konfirmasi/$1');
    $routes->post('ujian/mulai', 'Siswa\Ujian::mulai');
    $routes->get('ujian/kerjakan/(:num)', 'Siswa\Ujian::kerjakan/$1');
    $routes->post('ujian/simpanjawaban', 'Siswa\Ujian::simpanJawaban');
    $routes->post('ujian/selesaiUjian', 'Siswa\Ujian::selesaiUjian');
    
    // Keamanan Ujian
    $routes->post('ujian/catatPelanggaran', 'Siswa\Ujian::catatPelanggaran'); 
    $routes->post('ujian/blokirSiswa', 'Siswa\Ujian::blokirSiswa'); 
    $routes->get('presensi/pelajaran', 'Siswa\Presensi::pelajaran'); // Halaman List Mapel
    $routes->get('presensi/pelajaran/(:num)', 'Siswa\Presensi::pelajaran_detail/$1'); // Halaman Detail per Mapel
});