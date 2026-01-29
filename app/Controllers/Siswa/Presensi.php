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
        // 1. Cek Relasi Langsung (Kolom id_user di tabel siswa)
        $kolom_user = $this->db->fieldExists('id_user', 'tbl_siswa') ? 'id_user' : 'user_id';
        if ($this->db->fieldExists($kolom_user, 'tbl_siswa')) {
            $siswa = $this->db->table('tbl_siswa')->where($kolom_user, $id_user_login)->get()->getRow();
            if ($siswa) return $siswa->id;
        }

        // 2. Cek via Username == NIS (Biasanya akun siswa username-nya adalah NIS)
        $akun = $this->db->table('tbl_users')->where('id', $id_user_login)->get()->getRow(); // Ganti 'tbl_users' sesuaikan nama tabel user Bos (misal 'users')
        
        if ($akun) {
            // Coba cari Siswa yang NIS-nya sama dengan Username akun
            if (!empty($akun->username)) {
                $siswaByNIS = $this->db->table('tbl_siswa')->where('nis', $akun->username)->get()->getRow();
                if ($siswaByNIS) return $siswaByNIS->id;
            }
            
            // Coba cari Siswa yang Email-nya sama dengan Email akun
            if (!empty($akun->email)) {
                $siswaByEmail = $this->db->table('tbl_siswa')->where('email', $akun->email)->get()->getRow();
                if ($siswaByEmail) return $siswaByEmail->id;
            }
        }

        // 3. Fallback: Kembalikan ID User apa adanya
        return $id_user_login;
    }

    // 1. RIWAYAT HARIAN
    public function index()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login); // Pakai ID Hasil Pencarian
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

    // 2. REKAP BULANAN (MATRIX)
    public function rekap()
    {
        $id_user_login = session()->get('id_user');
        
        // --- Cari ID Siswa Fix ---
        $id_siswa = $this->getRealSiswaID($id_user_login);
        // -------------------------

        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        // Ambil Data Absen
        $absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_siswa)
            ->where('role', 'siswa')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        // Mapping Data: Tanggal => Status
        $map = [];
        $total = ['H'=>0, 'S'=>0, 'I'=>0, 'A'=>0, 'T'=>0];

        foreach($absen as $a) {
            $tgl = (int) date('d', strtotime($a['tanggal']));
            $st  = $a['status_kehadiran'];
            $map[$tgl] = $st;

            // Hitung Total
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

    // 3. HALAMAN PENGAJUAN IZIN
    public function izin()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login); // Fix ID

        // Ambil Riwayat Pengajuan
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

    // 4. PROSES PENGAJUAN IZIN
    public function ajukan()
    {
        $id_user_login = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user_login); // Fix ID

        $file = $this->request->getFile('bukti');
        $namaFile = null;
        if ($file && $file->isValid()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/surat_izin', $namaFile);
        }

        // Cek apakah sudah absen hari ini?
        $cek = $this->db->table('tbl_presensi')
             ->where('user_id', $id_siswa)
             ->where('tanggal', $this->request->getPost('tanggal'))
             ->countAllResults();

        if($cek > 0) {
            return redirect()->back()->with('error', 'Anda sudah tercatat presensi pada tanggal tersebut.');
        }

        $this->db->table('tbl_presensi')->insert([
            'user_id'           => $id_siswa, // Masukkan ID Siswa yang benar
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
        $id_siswa = $this->getRealSiswaID($id_user_login); // Pakai Helper ID Asli
        
        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        // Ambil Data Siswa (Untuk Kop Surat)
        $siswa = $this->db->table('tbl_siswa')
            ->select('tbl_siswa.*, tbl_kelas.nama_kelas')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id') // Sesuaikan kolom (kelas_id/id_kelas)
            ->where('tbl_siswa.id', $id_siswa)
            ->get()->getRowArray();

        // Ambil Absensi
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
                'nama'   => 'SMK DIGITAL INDONESIA', // Sesuaikan Nama Sekolah
                'alamat' => 'Jl. Teknologi No. 1'
            ]
        ]);
    }
   public function pelajaran()
    {
        $id_user = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user);
        
        // A. FILTER TAHUN AJARAN
        $ta_list = $this->db->table('tbl_tahun_ajaran')
            ->orderBy('tahun_ajaran', 'DESC')
            ->orderBy('semester', 'DESC')
            ->get()->getResultArray();

        $ta_aktif = $this->db->table('tbl_tahun_ajaran')->where('status', 'Aktif')->get()->getRow();
        
        // Ambil ID TA dari URL, kalau tidak ada pakai TA Aktif
        $selected_ta_id = $this->request->getGet('id_ta') ?? ($ta_aktif->id ?? 0);

        // B. DATA SISWA & KELAS
        $kolom_kelas = $this->db->fieldExists('id_kelas', 'tbl_siswa') ? 'id_kelas' : 'kelas_id';
        $siswa = $this->db->table('tbl_siswa')->select($kolom_kelas)->where('id', $id_siswa)->get()->getRow();

        if (!$siswa) return redirect()->to('siswa/dashboard');
        $id_kelas = $siswa->$kolom_kelas;

        // C. AMBIL MAPEL (Hanya yang ada jadwalnya di Semester Ini)
        $mapel = $this->db->table('tbl_jadwal')
            ->select('tbl_mapel.id as id_mapel, tbl_mapel.nama_mapel')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
            ->where('tbl_jadwal.id_kelas', $id_kelas)
            ->where('tbl_jadwal.id_tahun_ajaran', $selected_ta_id) // Filter Mapel per Semester
            ->groupBy('tbl_jadwal.id_mapel') 
            ->get()->getResultArray();

        // D. HITUNG STATISTIK (FIX: FILTER STRICT PER SEMESTER)
        foreach ($mapel as &$m) {
            
            // Nama Guru
            $guru = $this->db->table('tbl_jadwal')
                ->select('tbl_guru.nama_lengkap')
                ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal.id_guru')
                ->where('id_kelas', $id_kelas)
                ->where('id_mapel', $m['id_mapel'])
                ->where('id_tahun_ajaran', $selected_ta_id)
                ->get()->getRow();
            $m['nama_guru'] = $guru ? $guru->nama_lengkap : '-';

            // --- PERBAIKAN UTAMA DISINI ---
            // Statistik hanya menghitung jurnal yang ID TA-nya sesuai pilihan dropdown
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
                ->where('tbl_jurnal.id_tahun_ajaran', $selected_ta_id) // <--- PENYEBAB MASALAH SEBELUMNYA (LUPA FILTER INI)
                ->get()->getRowArray();

            $m['stats'] = $stats;
            
            // Persentase
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

   // -------------------------------------------------------------------------
    // 2. HALAMAN DETAIL RIWAYAT (FILTER SEMESTER)
    // -------------------------------------------------------------------------
    public function pelajaran_detail($id_mapel)
    {
        $id_user = session()->get('id_user');
        $id_siswa = $this->getRealSiswaID($id_user);

        // Ambil Filter dari URL, jika tidak ada ambil TA Aktif
        $ta_aktif = $this->db->table('tbl_tahun_ajaran')->where('status', 'Aktif')->get()->getRow();
        $id_ta = $this->request->getGet('id_ta') ?? ($ta_aktif->id ?? 0);

        // Info Mapel & TA
        $info_mapel = $this->db->table('tbl_mapel')->where('id', $id_mapel)->get()->getRowArray();
        $info_ta = $this->db->table('tbl_tahun_ajaran')->where('id', $id_ta)->get()->getRowArray();

        // Query Riwayat (Filter Ketat Semester)
        $builder = $this->db->table('tbl_absensi_mapel')
            ->select('tbl_absensi_mapel.status, tbl_jurnal.tanggal, tbl_jurnal.jam_ke, tbl_jurnal.materi, tbl_jurnal.foto_kegiatan, tbl_guru.nama_lengkap as nama_guru')
            ->join('tbl_jurnal', 'tbl_jurnal.id = tbl_absensi_mapel.id_jurnal')
            ->join('tbl_guru', 'tbl_guru.id = tbl_jurnal.id_guru')
            ->where('tbl_absensi_mapel.id_siswa', $id_siswa)
            ->where('tbl_jurnal.id_mapel', $id_mapel);

        // Cek kolom id_tahun_ajaran di tbl_jurnal
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