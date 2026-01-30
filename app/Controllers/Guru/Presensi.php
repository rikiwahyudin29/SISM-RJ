<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Presensi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // --- HELPER: Cari ID Guru Asli ---
    private function getRealGuruID($id_user_login)
    {
        $kolom_user = $this->db->fieldExists('id_user', 'tbl_guru') ? 'id_user' : 'user_id';
        
        if ($this->db->fieldExists($kolom_user, 'tbl_guru')) {
            $guru = $this->db->table('tbl_guru')->where($kolom_user, $id_user_login)->get()->getRow();
            if ($guru) return $guru->id;
        }

        $guru_direct = $this->db->table('tbl_guru')->where('id', $id_user_login)->get()->getRow();
        if ($guru_direct) return $guru_direct->id;

        return $id_user_login;
    }

    // --- FITUR 1: RIWAYAT (EXISTING) ---
    public function index()
    {
        $id_user_login = session()->get('id_user');
        $id_guru_fix = $this->getRealGuruID($id_user_login);
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');

        $data = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru_fix)
            ->where('role', 'guru')
            ->like('tanggal', $bulan)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        return view('guru/presensi/index', [
            'title' => 'Riwayat Kehadiran Anda',
            'data'  => $data,
            'bulan' => $bulan
        ]);
    }

    // --- FITUR 2: FORM ABSEN GEOLOCATION & SELFIE (BARU) ---
    public function absen_harian()
    {
        $id_user_login = session()->get('id_user');
        $id_guru_fix = $this->getRealGuruID($id_user_login);

        // 1. Ambil Setting Lokasi Sekolah
        $setting = $this->db->table('tbl_jam_sekolah')->where('id', 1)->get()->getRow();

        if (!$setting) {
            return redirect()->to('guru/dashboard')->with('error', 'Settingan Lokasi Sekolah belum diatur Admin!');
        }

        // 2. Cek apakah sudah absen hari ini?
        $hari_ini = date('Y-m-d');
        $sudah_absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru_fix)
            ->where('role', 'guru')
            ->where('tanggal', $hari_ini)
            ->get()->getRow();

        return view('guru/presensi/absen_geo', [
            'title'       => 'Absensi Harian',
            'lokasi'      => $setting,
            'sudah_absen' => $sudah_absen
        ]);
    }

    // --- FITUR 3: PROSES SIMPAN ABSEN GEO (BARU) ---
    public function submit_absen()
    {
        $id_user_login = session()->get('id_user');
        $id_guru_fix = $this->getRealGuruID($id_user_login);

        // 1. Ambil Data Form
        $lat_user    = $this->request->getPost('latitude');
        $long_user   = $this->request->getPost('longitude');
        $foto_base64 = $this->request->getPost('foto_selfie');
        
        // 2. Validasi Setting Sekolah
        $setting = $this->db->table('tbl_jam_sekolah')->where('id', 1)->get()->getRow();
        
        // 3. Hitung Jarak (Server Side Validation)
        $jarak_meter = $this->hitungJarak($setting->latitude, $setting->longitude, $lat_user, $long_user);

        if ($jarak_meter > $setting->radius) {
            return redirect()->back()->with('error', "Gagal! Anda berada di luar radius. Jarak: $jarak_meter meter (Max: $setting->radius m).");
        }

        // 4. Proses Foto Selfie
        $nama_foto = '';
        if ($foto_base64) {
            $image_parts = explode(";base64,", $foto_base64);
            $image_base64 = base64_decode($image_parts[1]);
            $nama_foto = 'absen_' . date('Ymd_His') . '_' . $id_guru_fix . '.jpg';
            $path = FCPATH . 'uploads/absensi/';
            
            if (!is_dir($path)) mkdir($path, 0777, true);
            file_put_contents($path . $nama_foto, $image_base64);
        } else {
            return redirect()->back()->with('error', 'Foto selfie wajib diambil!');
        }

        // 5. Cek Telat atau Tepat Waktu
        $jam_sekarang = date('H:i:s');
        $status_kehadiran = ($jam_sekarang > $setting->jam_masuk_akhir) ? 'Terlambat' : 'Hadir';

        // 6. Simpan ke Database
        $data = [
            'user_id'           => $id_guru_fix,
            'role'              => 'guru',
            'tanggal'           => date('Y-m-d'),
            'jam_masuk'         => $jam_sekarang,
            'latitude'          => $lat_user,
            'longitude'         => $long_user,
            'jarak_meter'       => $jarak_meter,
            'bukti_izin'        => $nama_foto, // Kita simpan foto di kolom ini atau buat kolom baru 'foto'
            'status_kehadiran'  => $status_kehadiran,
            'metode'            => 'QRCode/Geo', // Penanda absen online
            'status_verifikasi' => 'Terverifikasi'
        ];

        $this->db->table('tbl_presensi')->insert($data);

        return redirect()->to('guru/presensi')->with('success', "Absen Berhasil! Status: $status_kehadiran (Jarak: $jarak_meter m)");
    }

    // --- HELPER: RUMUS JARAK (BARU) ---
    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c);
    }

    // --- FITUR 4: REKAP BULANAN (EXISTING) ---
    public function rekap()
    {
        $id_user_login = session()->get('id_user');
        $id_guru_fix = $this->getRealGuruID($id_user_login);
        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        $absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru_fix)
            ->where('role', 'guru')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        $map = [];
        $total = ['H'=>0, 'S'=>0, 'I'=>0, 'A'=>0, 'T'=>0];

        foreach($absen as $a) {
            $tgl = (int) date('d', strtotime($a['tanggal']));
            $st  = $a['status_kehadiran'];
            $map[$tgl] = $st;

            if($st == 'Hadir') $total['H']++;
            if($st == 'Terlambat') { $total['T']++; $total['H']++; }
            if($st == 'Sakit') $total['S']++;
            if($st == 'Izin') $total['I']++;
            if($st == 'Alpha') $total['A']++;
        }

        return view('guru/presensi/rekap', [
            'title'    => 'Rekap Bulanan Guru',
            'bulan'    => $bulan,
            'map'      => $map,
            'total'    => $total,
            'jml_hari' => $jml_hari
        ]);
    }

    // --- FITUR 5: HALAMAN IZIN (EXISTING) ---
    public function izin()
    {
        $id_user_login = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user_login); 

        $riwayat = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru)
            ->where('role', 'guru')
            ->whereIn('status_kehadiran', ['Izin', 'Sakit', 'Dinas Luar']) 
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        return view('guru/presensi/izin', [
            'title'   => 'Ajukan Izin/Sakit',
            'riwayat' => $riwayat
        ]);
    }

    // --- FITUR 6: PROSES AJUKAN IZIN (EXISTING) ---
    public function ajukan()
    {
        $id_user_login = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user_login);

        $file = $this->request->getFile('bukti');
        $namaFile = null;
        if ($file && $file->isValid()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/surat_izin', $namaFile);
        }

        $cek = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru)
            ->where('tanggal', $this->request->getPost('tanggal'))
            ->countAllResults();

        if ($cek > 0) return redirect()->back()->with('error', 'Anda sudah absen hari ini.');

        $this->db->table('tbl_presensi')->insert([
            'user_id' => $id_guru,
            'role' => 'guru',
            'tanggal' => $this->request->getPost('tanggal'),
            'status_kehadiran' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
            'bukti_izin' => $namaFile,
            'metode' => 'Online',
            'status_verifikasi' => 'Pending' 
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim.');
    }

    // --- FITUR 7: CETAK (EXISTING) ---
    public function cetak_rekap()
    {
        $id_user_login = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user_login); 
        
        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        $guru = $this->db->table('tbl_guru')->where('id', $id_guru)->get()->getRowArray();
        $nama_guru = $guru['nama_guru'] ?? $guru['nama_lengkap'] ?? $guru['nama'];

        $absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru)
            ->where('role', 'guru')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        $map = [];
        $total = ['H'=>0, 'S'=>0, 'I'=>0, 'A'=>0, 'T'=>0];
        foreach($absen as $a) {
            $tgl = (int) date('d', strtotime($a['tanggal']));
            $st  = $a['status_kehadiran'];
            $map[$tgl] = $st;

            if($st == 'Hadir') $total['H']++;
            if($st == 'Terlambat') { $total['T']++; $total['H']++; }
            if($st == 'Sakit') $total['S']++;
            if($st == 'Izin') $total['I']++;
            if($st == 'Alpha') $total['A']++;
        }

        return view('guru/presensi/cetak_rekap', [
            'guru'     => $guru,
            'nama_guru'=> $nama_guru,
            'bulan'    => $bulan,
            'map'      => $map,
            'total'    => $total,
            'jml_hari' => $jml_hari,
            'sekolah'  => [
                'nama' => 'SMK DIGITAL INDONESIA',
                'kepsek' => 'Bapak Kepala Sekolah, M.Pd'
            ]
        ]);
    }
}