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
// Group ADMIN (Semua route di sini butuh login sebagai admin/piket)
$routes->group('admin', ['filter' => 'role:admin,piket'], function($routes) {

    // --- 1. MONITORING UTAMA ---
    $routes->get('piket', 'Admin\Piket::index'); // Dashboard

    // --- 2. FITUR GURU PIKET (Jurnal & Izin) ---
    // Saya buatkan group 'piket' agar URL-nya rapi: /admin/piket/jurnal dll
    $routes->group('piket', function($routes) {
        $routes->get('jurnal', 'Admin\Piket::jurnal');
        $routes->post('saveJurnal', 'Admin\Piket::saveJurnal');
        
        $routes->get('izin', 'Admin\Piket::izin');
        $routes->post('saveIzin', 'Admin\Piket::saveIzin');
        $routes->get('cetakIzin/(:num)', 'Admin\Piket::cetakIzin/$1');
    });

    // --- 3. PRESENSI SISWA ---
    $routes->group('presensi', function($routes) {
        // Scanner
        $routes->get('scanner', 'Admin\Presensi::scanner');
        $routes->post('proses_scan', 'Admin\Presensi::proses_scan'); // Web Scan
        
        // Manual & Izin
        $routes->get('izin', 'Admin\Presensi::izin');
        $routes->post('simpan_izin', 'Admin\Presensi::simpan_izin');
        $routes->get('hapus_izin/(:num)', 'Admin\Presensi::hapus_izin/$1');
        
        // Laporan & Rekap
        $routes->get('laporan', 'Admin\Presensi::laporan');
        $routes->get('rekap', 'Admin\Presensi::rekap');
        $routes->get('cetak_harian', 'Admin\Presensi::cetak_harian');
        $routes->get('cetak_rekap', 'Admin\Presensi::cetak_rekap');
        $routes->get('cetak_bulanan', 'Admin\Presensi::cetak_bulanan');
        
        // Helper Data
        $routes->get('get_siswa_by_kelas/(:num)', 'Admin\Presensi::get_siswa_by_kelas/$1');
        $routes->get('verifikasi/(:num)/(:segment)', 'Admin\Presensi::verifikasi/$1/$2');
    });

    // --- 4. PRESENSI GURU ---
    $routes->group('presensi_guru', function($routes) {
        $routes->get('/', 'Admin\PresensiGuru::index');
        $routes->post('simpan_manual', 'Admin\PresensiGuru::simpan_manual');
        $routes->get('rekap', 'Admin\PresensiGuru::rekap');
        $routes->get('cetak_rekap', 'Admin\PresensiGuru::cetak_rekap');
    });

    // --- 5. MONITORING JURNAL KBM ---
    $routes->get('jurnal', 'Admin\Jurnal::index');
    $routes->get('jurnal/cetak', 'Admin\Jurnal::cetak');

}); // <--- Tutup Group Admin di sini

// --- ROUTE API (Di luar group Admin agar tidak kena filter login session) ---
$routes->post('api/iot/scan', 'Admin\Presensi::proses_scan');

// =========================================================================
// 3. ADMIN ONLY ROUTES (Full Control)
// =========================================================================
$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    
    // Dashboard Utama & Pengaturan
    $routes->get('dashboard', 'Admin::index');
    $routes->get('pengaturan', 'Admin::pengaturan');
    $routes->post('pengaturan/update', 'Admin::pengaturan_update');
    $routes->get('sekolah/identitas', 'Admin\Sekolah::identitas');
    // Tambahkan ini untuk profil
    $routes->get('profil', 'Admin\Profil::index');
    $routes->post('profil/simpan', 'Admin\Profil::simpan');

    // INTEGRASI DAPODIK
    $routes->get('dapodik', 'Admin\Dapodik::index');
    $routes->post('dapodik/update_setting', 'Admin\Dapodik::update_setting'); // Buat method ini jika perlu simpan
    $routes->get('dapodik/cek_koneksi', 'Admin\Dapodik::cek_koneksi');
    $routes->get('dapodik/tarik_siswa', 'Admin\Dapodik::tarik_siswa');
    $routes->get('dapodik/tarik_guru', 'Admin\Dapodik::tarik_guru');
    $routes->post('dapodik/kirim_raport', 'Admin\Dapodik::kirim_raport');
    $routes->get('dapodik/test_manual', 'Admin\Dapodik::test_manual');
    $routes->get('dapodik/tarik_rombel', 'Admin\Dapodik::tarik_rombel'); // <--- TAMBAHKAN INI
    $routes->get('dapodik/tarik_jurusan', 'Admin\Dapodik::tarik_jurusan');
    $routes->get('dapodik/test_jurusan', 'Admin\Dapodik::test_jurusan');
    $routes->get('dapodik/tarik_mapel', 'Admin\Dapodik::tarik_mapel');
    $routes->get('dapodik/test_mapel', 'Admin\Dapodik::test_mapel');
    $routes->get('dapodik/tarik_sekolah', 'Admin\Dapodik::tarik_sekolah');

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
$routes->get('jenis_ujian', 'Admin\Master::jenis_ujian');
    $routes->post('jenis_ujian/simpan', 'Admin\Master::jenis_ujian_simpan');
    $routes->post('jenis_ujian/update/(:num)', 'Admin\Master::jenis_ujian_update/$1');
    $routes->get('jenis_ujian/hapus/(:num)', 'Admin\Master::jenis_ujian_hapus/$1');
    $routes->get('jam', 'Admin\Jam::index');
    $routes->post('jam/simpan', 'Admin\Jam::simpan');
    $routes->get('jam/hapus/(:num)', 'Admin\Jam::hapus/$1');
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

    $routes->get('sekolah', 'Admin\Sekolah::index');
    $routes->post('sekolah/update', 'Admin\Sekolah::update');

    // ==========================================
    // PERSIAPAN RUTE PPDB (Penerimaan Siswa Baru)
    // ==========================================
    $routes->get('ppdb', 'Admin\Ppdb::index');
    $routes->get('ppdb/detail/(:num)', 'Admin\Ppdb::detail/$1');
    $routes->post('ppdb/update-status', 'Admin\Ppdb::updateStatus');
    $routes->get('ppdb/delete/(:num)', 'Admin\Ppdb::delete/$1');

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

    $routes->get('profil', 'Guru\Profil::index');
    $routes->post('profil/simpan', 'Guru\Profil::simpan');
    
    // Jurnal KBM
    $routes->get('jurnal', 'Guru\Jurnal::index');
    $routes->get('jurnal/input', 'Guru\Jurnal::input');
    $routes->post('jurnal/simpan', 'Guru\Jurnal::simpan');
    $routes->get('jurnal/hapus/(:num)', 'Guru\Jurnal::hapus/$1');
    $routes->get('jurnal/absen/(:num)', 'Guru\Jurnal::absen/$1');
    $routes->post('jurnal/simpan_absen', 'Guru\Jurnal::simpan_absen');

    // ROUTE E-LEARNING MATERI
  $routes->get('materi', 'Guru\Materi::index');
    $routes->post('materi/save', 'Guru\Materi::save');
    $routes->post('materi/update', 'Guru\Materi::update');
    $routes->get('materi/delete/(:num)', 'Guru\Materi::delete/$1');

    // Presensi Guru
    $routes->get('presensi', 'Guru\Presensi::index');
    $routes->get('presensi/rekap', 'Guru\Presensi::rekap');
    $routes->get('presensi/izin', 'Guru\Presensi::izin');
    $routes->post('presensi/ajukan', 'Guru\Presensi::ajukan');
    $routes->get('presensi/cetak_rekap', 'Guru\Presensi::cetak_rekap');
    $routes->get('presensi/absen', 'Guru\Presensi::absen_harian');       // Halaman Absen
    $routes->post('presensi/submit_absen', 'Guru\Presensi::submit_absen'); // Proses Simpan

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
    $routes->get('monitoring/lihat/(:num)', 'Guru\Monitoring::lihat/$1');
    $routes->post('monitoring/aksi_masal', 'Guru\Monitoring::aksi_masal');
    
    $routes->get('hasil/index/(:num)', 'Guru\Hasil::index/$1');
    $routes->get('hasil/pdf/(:num)', 'Guru\Hasil::pdf/$1');
    $routes->get('hasil/excel/(:num)', 'Guru\Hasil::excel/$1');
    // --- FITUR NILAI & IMPORT ---
    $routes->get('nilai', 'Guru\Nilai::index');
    $routes->post('nilai/save_batch', 'Guru\Nilai::save_batch');   // Simpan Nilai Tabel
    $routes->post('nilai/save_setting', 'Guru\Nilai::save_setting'); // Simpan Bobot/KKM
    $routes->get('nilai/download_template', 'Guru\Nilai::download_template'); // <--- YANG BOS CARI
    $routes->post('nilai/import', 'Guru\Nilai::import');           // Proses Upload CSV
// --- FITUR LEGER ---
    $routes->get('leger', 'Guru\Leger::index');
    $routes->get('leger/cetak', 'Guru\Leger::cetak');
    // --- FITUR CATATAN WALI ---
    $routes->get('catatan', 'Guru\Catatan::index');
    $routes->post('catatan/save', 'Guru\Catatan::save');
    $routes->get('catatan/generate/(:num)', 'Guru\Catatan::generate_absensi/$1');
    // Rapor
    $routes->get('rapor', 'Guru\Rapor::index');
    $routes->get('rapor/cetak/(:num)', 'Guru\Rapor::cetak/$1');
    // --- MODUL TUGAS ---
    $routes->get('tugas', 'Guru\Tugas::index');
    $routes->post('tugas/save', 'Guru\Tugas::save');
    $routes->get('tugas/delete/(:num)', 'Guru\Tugas::delete/$1');
    $routes->get('tugas/hasil/(:num)', 'Guru\Tugas::hasil/$1'); // Lihat Hasil
    $routes->post('tugas/nilai', 'Guru\Tugas::nilai');          // Simpan Nilai
    
    // --- MODUL BK (Simple) ---
    $routes->get('bk', 'Guru\Bk::index');
    $routes->post('bk/save', 'Guru\Bk::save');
    $routes->get('bk/delete/(:num)', 'Guru\Bk::delete/$1');
    // --- MODUL MASTER BK (Satu Controller dengan BK) ---
    $routes->get('bk/master', 'Guru\Bk::master');
    $routes->post('bk/master/save', 'Guru\Bk::saveMaster');
    $routes->get('bk/master/delete/(:num)', 'Guru\Bk::deleteMaster/$1');
    $routes->get('bk/settings', 'Guru\Bk::settings');
    $routes->post('bk/save-settings', 'Guru\Bk::saveSettings');
    $routes->get('bk/detail-siswa/(:num)', 'Guru\Bk::detailSiswa/$1');

    // --- FILEBOX ---
    $routes->get('filebox', 'Admin\Filebox::index');
    $routes->post('filebox/upload', 'Admin\Filebox::upload');
    $routes->post('filebox/nilai', 'Admin\Filebox::nilai');
    $routes->get('filebox/download/(:segment)', 'Admin\Filebox::download/$1');
    $routes->get('filebox/hapus/(:num)', 'Admin\Filebox::hapus/$1');
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
    $routes->get('presensi/absen', 'Siswa\Presensi::absen_harian');       // NEW
    $routes->post('presensi/submit_absen', 'Siswa\Presensi::submit_absen'); // NEW

    // Ujian
    $routes->get('ujian', 'Siswa\Ujian::index');
    $routes->get('ujian/konfirmasi/(:num)', 'Siswa\Ujian::konfirmasi/$1');
    $routes->post('ujian/mulai', 'Siswa\Ujian::mulai');
    $routes->get('ujian/kerjakan/(:num)', 'Siswa\Ujian::kerjakan/$1');
   $routes->post('ujian/simpanJawaban', 'Siswa\Ujian::simpanJawaban');
    $routes->post('ujian/selesaiUjian', 'Siswa\Ujian::selesaiUjian');
    $routes->post('ujian/catatPelanggaran', 'Siswa\Ujian::catatPelanggaran');
    // Keamanan Ujian
    $routes->post('ujian/catatPelanggaran', 'Siswa\Ujian::catatPelanggaran'); 
    $routes->post('ujian/blokirSiswa', 'Siswa\Ujian::blokirSiswa'); 
    $routes->get('presensi/pelajaran', 'Siswa\Presensi::pelajaran'); // Halaman List Mapel
    $routes->get('presensi/pelajaran/(:num)', 'Siswa\Presensi::pelajaran_detail/$1'); // Halaman Detail per Mapel

    $routes->get('profil', 'Siswa\Profil::index');
    $routes->post('profil/simpan', 'Siswa\Profil::simpan');
    // --- Rapor / Nilai Akademik ---
    // Gunakan URL 'nilai-akademik' agar beda dengan 'nilai' (Ujian)
    $routes->get('nilai-akademik', 'Siswa\NilaiAkademik::index');

    // --- MODUL MATERI ---
    $routes->get('materi', 'Siswa\Materi::index');

    // --- MODUL TUGAS ---
    $routes->get('tugas', 'Siswa\Tugas::index');
    $routes->post('tugas/upload', 'Siswa\Tugas::upload');

    $routes->get('bk', 'Siswa\Bk::index');
});

$routes->group('admin', ['filter' => 'role:admin,kepsek,guru'], function($routes) {
    
    // ... route piket dll ...

    // --- FILEBOX (Bisa diakses Admin, Kepsek, Guru) ---
    $routes->get('filebox', 'Admin\Filebox::index');
    $routes->post('filebox/upload', 'Admin\Filebox::upload');
    $routes->post('filebox/nilai', 'Admin\Filebox::nilai');
    $routes->get('filebox/download/(:segment)', 'Admin\Filebox::download/$1');
    $routes->get('filebox/hapus/(:num)', 'Admin\Filebox::hapus/$1');
    $routes->post('filebox/revisi', 'Admin\Filebox::revisi');

    });

    $routes->group('admin', ['filter' => 'role:admin,piket,sarpras'], function($routes) {
        // --- ROUTE SARPRAS MULTI-ROLE ---
    // Karena sudah masuk group 'admin', URL-nya jadi: localhost:8080/admin/sarpras
    $routes->get('sarpras', 'Admin\Sarpras::index');
    $routes->post('sarpras/save', 'Admin\Sarpras::save');
    $routes->post('sarpras/update', 'Admin\Sarpras::update');
    $routes->get('sarpras/delete/(:num)', 'Admin\Sarpras::delete/$1');

});

$routes->group('admin', ['filter' => 'role:admin,tu'], function($routes) {
    // ... route sarpras, piket, dll ...
    
    // ROUTE E-SURAT
    $routes->get('surat', 'Admin\Surat::index');
    $routes->post('surat/create', 'Admin\Surat::create');
    $routes->get('surat/cetak/(:num)', 'Admin\Surat::cetak/$1');
    $routes->get('surat/delete/(:num)', 'Admin\Surat::delete/$1');

    // TEMPLATE SURAT
    $routes->get('templatesurat', 'Admin\TemplateSurat::index');
    $routes->post('templatesurat/save', 'Admin\TemplateSurat::save');
    $routes->get('templatesurat/delete/(:num)', 'Admin\TemplateSurat::delete/$1');

    // SURAT OTOMATIS
    $routes->post('surat/generate', 'Admin\Surat::generate');
});

$routes->group('admin', ['filter' => 'role:admin,guru,siswa'], function($routes) {
    
    // ... route lainnya ...

    // --- E-LIBRARY ---
    $routes->get('library', 'Admin\Library::index');
    $routes->post('library/upload', 'Admin\Library::upload');
    $routes->get('library/baca/(:num)', 'Admin\Library::baca/$1');
    $routes->get('library/delete/(:num)', 'Admin\Library::delete/$1');

    // --- E-LEARNING (GMEET) ---
    $routes->get('elearning', 'Admin\Elearning::index');
    $routes->post('elearning/save', 'Admin\Elearning::save');
    $routes->get('elearning/delete/(:num)', 'Admin\Elearning::delete/$1');

    // GOOGLE AUTH ROUTES
    $routes->get('google/connect', 'Admin\Google::connect');
    $routes->get('google/callback', 'Admin\Google::callback');

    });

    $routes->group('admin', ['filter' => 'role:admin,guru'], function($routes) {
    // ... route lain ...
    
    // GOOGLE AUTH
    $routes->get('google/connect', 'Admin\Google::connect');
    $routes->get('google/callback', 'Admin\Google::callback');
});

// GROUP CMS ADMIN
$routes->group('admin/cms', ['filter' => 'role:admin'], function($routes) {
    // Slider
    $routes->get('slider', 'Admin\Cms::slider');
    $routes->post('slider/save', 'Admin\Cms::save_slider');
    $routes->post('slider/delete/(:num)', 'Admin\Cms::delete_slider/$1');
    
    // Berita
    $routes->get('berita', 'Admin\Cms::berita');
    $routes->get('berita/add', 'Admin\Cms::berita_add');
    $routes->get('berita/edit/(:num)', 'Admin\Cms::berita_edit/$1');
    $routes->post('berita/save', 'Admin\Cms::save_berita');
    $routes->post('berita/delete/(:num)', 'Admin\Cms::delete_berita/$1');
    
    // Halaman (Profil, Info SPMB)
    $routes->get('halaman', 'Admin\Cms::halaman');
    $routes->get('halaman/edit/(:any)', 'Admin\Cms::halaman_edit/$1');
    $routes->post('halaman/save', 'Admin\Cms::save_halaman');
    
    // Galeri
    $routes->get('galeri', 'Admin\Cms::galeri');
    $routes->post('galeri/save', 'Admin\Cms::save_galeri');
    $routes->post('galeri/delete/(:num)', 'Admin\Cms::delete_galeri/$1');

    $routes->get('profil', 'Admin\Cms::profil');
$routes->post('save_profil', 'Admin\Cms::save_profil');
    });

// GROUP SPMB (ADMIN)
$routes->group('admin/spmb', ['filter' => 'role:admin'], function($routes) {
    $routes->get('dashboard', 'Admin\Spmb::dashboard');
    $routes->get('pendaftar', 'Admin\Spmb::pendaftar');
    $routes->get('detail/(:num)', 'Admin\Spmb::detail/$1');
    $routes->post('update_status', 'Admin\Spmb::update_status');
    $routes->post('delete/(:num)', 'Admin\Spmb::delete/$1');
    $routes->get('cetak_formulir/(:num)', 'Admin\Spmb::cetak_formulir/$1');
    // $routes->get('laporan', 'Admin\Spmb::laporan'); // Nanti kita buat
});
// 1. Route Download (SPESIFIK) - Taruh paling atas!
$routes->get('verifikasi/download/(:any)', 'Admin\Surat::cetak_public/$1'); 

// 2. Route Scan QR (UMUM) - Taruh di bawahnya
$routes->get('verifikasi/(:any)', 'Admin\Surat::verifikasi/$1');

$routes->get('spmb/register', 'Spmb::register');
$routes->post('spmb/save', 'Spmb::save');
$routes->get('spmb/success/(:any)', 'Spmb::success/$1');
$routes->get('spmb/cetak/(:num)', 'Spmb::cetak/$1');