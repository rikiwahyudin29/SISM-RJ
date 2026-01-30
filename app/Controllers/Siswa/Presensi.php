<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Presensi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // --- FUNGSI PENTING: Cari ID Siswa Asli ---
    private function getRealSiswaID($id_user_login)
    {
        // 1. Cek Relasi Langsung
        $kolom_user = $this->db->fieldExists('id_user', 'tbl_siswa') ? 'id_user' : 'user_id';
        if ($this->db->fieldExists($kolom_user, 'tbl_siswa')) {
            $siswa = $this->db->table('tbl_siswa')->where($kolom_user, $id_user_login)->get()->getRow();
            if ($siswa) return $siswa->id;
        }

        // 2. Cek via Username == NIS
        $akun = $this->db->table('tbl_users')->where('id', $id_user_login)->get()->getRow();
        
        if ($akun) {
            if (!empty($akun->username)) {
                $siswaByNIS = $this->db->table('tbl_siswa')->where('nis', $akun->username)->get()->getRow();
                if ($siswaByNIS) return $siswaByNIS->id;
            }
            if (!empty($akun->email)) {
                $siswaByEmail = $this->db->table('tbl_siswa')->where('email', $akun->email)->get()->getRow();
                if ($siswaByEmail) return $siswaByEmail->id;
            }
        }

        return $id_user_login;
    }

    // =========================================================================
    // FITUR BARU: ABSENSI GEOLOCATION & SELFIE (SISWA)
    // =========================================================================

    public function absen_harian()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login);

        // 1. Ambil Setting Lokasi Sekolah
        $setting = $this->db->table('tbl_jam_sekolah')->where('id', 1)->get()->getRow();

        if (!$setting) {
            return redirect()->to('siswa/dashboard')->with('error', 'Settingan Lokasi Sekolah belum diatur Admin!');
        }

        // 2. Cek apakah sudah absen hari ini?
        $hari_ini = date('Y-m-d');
        $sudah_absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_siswa)
            ->where('role', 'siswa') // PENTING: Role Siswa
            ->where('tanggal', $hari_ini)
            ->get()->getRow();

        return view('siswa/presensi/absen_geo', [
            'title'       => 'Absensi Harian Siswa',
            'lokasi'      => $setting,
            'sudah_absen' => $sudah_absen
        ]);
    }

    public function submit_absen()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login);

        // 1. Ambil Data Form
        $lat_user    = $this->request->getPost('latitude');
        $long_user   = $this->request->getPost('longitude');
        $foto_base64 = $this->request->getPost('foto_selfie');
        
        // 2. Validasi Setting Sekolah
        $setting = $this->db->table('tbl_jam_sekolah')->where('id', 1)->get()->getRow();
        
        // 3. Hitung Jarak (Server Side Validation)
        $jarak_meter = $this->hitungJarak($setting->latitude, $setting->longitude, $lat_user, $long_user);

        if ($jarak_meter > $setting->radius) {
            return redirect()->back()->with('error', "Gagal! Anda berada di luar radius sekolah. Jarak: $jarak_meter meter.");
        }

        // 4. Proses Foto Selfie
        $nama_foto = '';
        if ($foto_base64) {
            $image_parts = explode(";base64,", $foto_base64);
            $image_base64 = base64_decode($image_parts[1]);
            $nama_foto = 'siswa_' . date('Ymd_His') . '_' . $id_siswa . '.jpg';
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
            'user_id'           => $id_siswa,
            'role'              => 'siswa',
            'tanggal'           => date('Y-m-d'),
            'jam_masuk'         => $jam_sekarang,
            'latitude'          => $lat_user,
            'longitude'         => $long_user,
            'jarak_meter'       => $jarak_meter,
            'bukti_izin'        => $nama_foto, // Foto disimpan di sini
            'status_kehadiran'  => $status_kehadiran,
            'metode'            => 'Online',
            'status_verifikasi' => 'Disetujui' // Otomatis disetujui
        ];

        $this->db->table('tbl_presensi')->insert($data);

        return redirect()->to('siswa/presensi')->with('success', "Absen Berhasil! Status: $status_kehadiran");
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c);
    }

    // =========================================================================
    // FITUR LAMA (EXISTING)
    // =========================================================================

    public function index()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login);
        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');

        $data = $this->db->table('tbl_presensi')
            ->where('user_id', $id_siswa)
            ->where('role', 'siswa')
            ->like('tanggal', $bulan)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        return view('siswa/presensi/index', [
            'title' => 'Riwayat Kehadiran',
            'data'  => $data,
            'bulan' => $bulan
        ]);
    }

    public function rekap()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login);
        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        $absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_siswa)
            ->where('role', 'siswa')
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

        return view('siswa/presensi/rekap', [
            'title'    => 'Rekap Bulanan',
            'bulan'    => $bulan,
            'map'      => $map,
            'total'    => $total,
            'jml_hari' => $jml_hari
        ]);
    }

    public function izin()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login);
        $riwayat = $this->db->table('tbl_presensi')
            ->where('user_id', $id_siswa)
            ->where('role', 'siswa')
            ->whereIn('status_kehadiran', ['Izin', 'Sakit'])
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        return view('siswa/presensi/izin', [
            'title'   => 'Pengajuan Izin',
            'riwayat' => $riwayat
        ]);
    }

    public function ajukan()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login);

        $file = $this->request->getFile('bukti');
        $namaFile = null;
        if ($file && $file->isValid()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/surat_izin', $namaFile);
        }

        $cek = $this->db->table('tbl_presensi')
             ->where('user_id', $id_siswa)
             ->where('tanggal', $this->request->getPost('tanggal'))
             ->countAllResults();

        if($cek > 0) {
            return redirect()->back()->with('error', 'Anda sudah tercatat presensi pada tanggal tersebut.');
        }

        $this->db->table('tbl_presensi')->insert([
            'user_id'           => $id_siswa,
            'role'              => 'siswa',
            'tanggal'           => $this->request->getPost('tanggal'),
            'status_kehadiran'  => $this->request->getPost('status'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'bukti_izin'        => $namaFile,
            'metode'            => 'Online',
            'status_verifikasi' => 'Pending'
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim. Menunggu ACC Guru/Admin.');
    }

    public function cetak_rekap()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login);
        
        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        $siswa = $this->db->table('tbl_siswa')
            ->select('tbl_siswa.*, tbl_kelas.nama_kelas')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            ->where('tbl_siswa.id', $id_siswa)
            ->get()->getRowArray();

        $absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_siswa)
            ->where('role', 'siswa')
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

        return view('siswa/presensi/cetak_rekap', [
            'siswa'    => $siswa,
            'bulan'    => $bulan,
            'map'      => $map,
            'total'    => $total,
            'jml_hari' => $jml_hari,
            'sekolah'  => [
                'nama'   => 'SMK DIGITAL INDONESIA',
                'alamat' => 'Jl. Teknologi No. 1'
            ]
        ]);
    }

    public function pelajaran()
    {
        $id_user = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user);
        
        $ta_list = $this->db->table('tbl_tahun_ajaran')
            ->orderBy('tahun_ajaran', 'DESC')
            ->orderBy('semester', 'DESC')
            ->get()->getResultArray();

        $ta_aktif = $this->db->table('tbl_tahun_ajaran')->where('status', 'Aktif')->get()->getRow();
        $selected_ta_id = $this->request->getGet('id_ta') ?? ($ta_aktif->id ?? 0);

        $kolom_kelas = $this->db->fieldExists('id_kelas', 'tbl_siswa') ? 'id_kelas' : 'kelas_id';
        $siswa = $this->db->table('tbl_siswa')->select($kolom_kelas)->where('id', $id_siswa)->get()->getRow();

        if (!$siswa) return redirect()->to('siswa/dashboard');
        $id_kelas = $siswa->$kolom_kelas;

        $mapel = $this->db->table('tbl_jadwal')
            ->select('tbl_mapel.id as id_mapel, tbl_mapel.nama_mapel')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
            ->where('tbl_jadwal.id_kelas', $id_kelas)
            ->where('tbl_jadwal.id_tahun_ajaran', $selected_ta_id)
            ->groupBy('tbl_jadwal.id_mapel') 
            ->get()->getResultArray();

        foreach ($mapel as &$m) {
            $guru = $this->db->table('tbl_jadwal')
                ->select('tbl_guru.nama_lengkap')
                ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal.id_guru')
                ->where('id_kelas', $id_kelas)
                ->where('id_mapel', $m['id_mapel'])
                ->where('id_tahun_ajaran', $selected_ta_id)
                ->get()->getRow();
            $m['nama_guru'] = $guru ? $guru->nama_lengkap : '-';

            $stats = $this->db->table('tbl_absensi_mapel')
                ->select("
                    COUNT(*) as total,
                    SUM(CASE WHEN tbl_absensi_mapel.status = 'H' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN tbl_absensi_mapel.status = 'S' THEN 1 ELSE 0 END) as sakit,
                    SUM(CASE WHEN tbl_absensi_mapel.status = 'I' THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN tbl_absensi_mapel.status = 'A' THEN 1 ELSE 0 END) as alpha
                ")
                ->join('tbl_jurnal', 'tbl_jurnal.id = tbl_absensi_mapel.id_jurnal')
                ->where('tbl_absensi_mapel.id_siswa', $id_siswa)
                ->where('tbl_jurnal.id_mapel', $m['id_mapel'])
                ->where('tbl_jurnal.id_tahun_ajaran', $selected_ta_id)
                ->get()->getRowArray();

            $m['stats'] = $stats;
            
            if ($stats['total'] > 0) {
                $m['persentase'] = round(($stats['hadir'] / $stats['total']) * 100);
            } else {
                $m['persentase'] = 0;
            }
        }

        return view('siswa/presensi/pelajaran_list', [
            'title'       => 'Absensi Pelajaran',
            'mapel'       => $mapel,
            'ta_list'     => $ta_list,
            'selected_ta' => $selected_ta_id
        ]);
    }

    public function pelajaran_detail($id_mapel)
    {
        $id_user = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user);

        $ta_aktif = $this->db->table('tbl_tahun_ajaran')->where('status', 'Aktif')->get()->getRow();
        $id_ta = $this->request->getGet('id_ta') ?? ($ta_aktif->id ?? 0);

        $info_mapel = $this->db->table('tbl_mapel')->where('id', $id_mapel)->get()->getRowArray();
        $info_ta = $this->db->table('tbl_tahun_ajaran')->where('id', $id_ta)->get()->getRowArray();

        $builder = $this->db->table('tbl_absensi_mapel')
            ->select('tbl_absensi_mapel.status, tbl_jurnal.tanggal, tbl_jurnal.jam_ke, tbl_jurnal.materi, tbl_jurnal.foto_kegiatan, tbl_guru.nama_lengkap as nama_guru')
            ->join('tbl_jurnal', 'tbl_jurnal.id = tbl_absensi_mapel.id_jurnal')
            ->join('tbl_guru', 'tbl_guru.id = tbl_jurnal.id_guru')
            ->where('tbl_absensi_mapel.id_siswa', $id_siswa)
            ->where('tbl_jurnal.id_mapel', $id_mapel);

        if ($this->db->fieldExists('id_tahun_ajaran', 'tbl_jurnal')) {
            $builder->where('tbl_jurnal.id_tahun_ajaran', $id_ta);
        }

        $riwayat = $builder->orderBy('tbl_jurnal.tanggal', 'DESC')->get()->getResultArray();

        return view('siswa/presensi/pelajaran_detail', [
            'title'   => 'Detail Kehadiran',
            'mapel'   => $info_mapel,
            'riwayat' => $riwayat,
            'ta'      => $info_ta
        ]);
    }
}