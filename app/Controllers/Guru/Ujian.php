<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Ujian extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $guruId = session()->get('id_user');
        
        // Ambil Data Jadwal Join ke Bank Soal & Mapel
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_mapel.nama_mapel, 
                      (SELECT COUNT(*) FROM tbl_jadwal_kelas WHERE id_jadwal_ujian = tbl_jadwal_ujian.id) as total_kelas')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal_ujian.id_guru') // Join ke guru pemilik jadwal
            ->where('tbl_guru.user_id', $guruId) // Filter punya guru ini saja
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        return view('guru/ujian/index', ['title' => 'Manajemen Jadwal Ujian', 'jadwal' => $jadwal]);
    }

    public function tambah()
    {
        $userId = session()->get('id_user'); // Ini ID Login (misal: 10)
        
        // LANGKAH 1: Ambil ID Guru Asli berdasarkan ID User Login
        $guru = $this->db->table('tbl_guru')->where('user_id', $userId)->get()->getRow();
        
        // Validasi: Kalau data guru belum lengkap/tidak ada
        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil guru tidak ditemukan. Hubungi Admin.');
        }

        $data = [
            'title' => 'Buat Jadwal Ujian',
            
            // LANGKAH 2: Ambil Bank Soal pakai ID GURU ($guru->id), BUKAN $userId
            'bank_soal' => $this->db->table('tbl_bank_soal')
                            ->select('tbl_bank_soal.*, tbl_mapel.nama_mapel')
                            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel', 'left') // Pakai LEFT JOIN biar kalau mapel dihapus, soal tetap muncul
                            ->where('tbl_bank_soal.id_guru', $guru->id) // <--- INI KUNCINYA
                            ->orderBy('tbl_bank_soal.id', 'DESC')
                            ->get()->getResultArray(),
                            
            'kelas' => $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray(),
            
            'tahun_ajaran' => $this->db->table('tbl_tahun_ajaran')
                                ->where('status', 'Aktif')
                                ->orWhere('status', '1')
                                ->orderBy('id', 'DESC')
                                ->get()->getResultArray(),
                                
            'jenis_ujian'  => $this->db->table('tbl_jenis_ujian')->get()->getResultArray()
        ];

        return view('guru/ujian/tambah', $data);
    }
    public function simpan()
    {
        $guruId = session()->get('id_user');
        $guru = $this->db->table('tbl_guru')->where('user_id', $guruId)->get()->getRow();

       $data = [
            'nama_ujian'    => $this->request->getPost('nama_ujian'),
            'id_bank_soal'  => $this->request->getPost('id_bank_soal'),
            
            // INPUTAN BARU
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
            'id_jenis_ujian'  => $this->request->getPost('id_jenis_ujian'),
            'bobot_pg'        => $this->request->getPost('bobot_pg'),
            'bobot_esai'      => $this->request->getPost('bobot_esai'),
            
            // INPUTAN LAMA (Tetap Ada)
            'waktu_mulai'       => $this->request->getPost('waktu_mulai'),
            'waktu_selesai'     => $this->request->getPost('waktu_selesai'),
            'durasi'            => $this->request->getPost('durasi'),
            'min_waktu_selesai' => $this->request->getPost('min_waktu') ?? 0,
            
            // SETTINGS
            'setting_strict'        => $this->request->getPost('setting_strict') ? 1 : 0,
            'setting_afk_timeout'   => $this->request->getPost('setting_afk_timeout') ?? 0,
            'setting_max_violation' => $this->request->getPost('setting_max_violation') ?? 3,
            'setting_token'         => $this->request->getPost('setting_token') ? 1 : 0,
            'token'                 => strtoupper($this->request->getPost('token')),
            'setting_show_score'    => $this->request->getPost('setting_show_score') ? 1 : 0,
            'setting_multi_login'   => $this->request->getPost('setting_multi_login') ? 1 : 0,
            'acak_soal'             => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_opsi'             => $this->request->getPost('acak_opsi') ? 1 : 0,
            'status_ujian'          => 'AKTIF'
        ];

        $this->db->table('tbl_jadwal_ujian')->insert($data);
        $idJadwal = $this->db->insertID();

        // Simpan Kelas
        $kelas = $this->request->getPost('kelas');
        if ($kelas) {
            $batch = [];
            foreach ($kelas as $k) {
                $batch[] = ['id_jadwal_ujian' => $idJadwal, 'id_kelas' => $k];
            }
            $this->db->table('tbl_jadwal_kelas')->insertBatch($batch);
        }

        return redirect()->to('guru/ujian')->with('success', 'Jadwal Berhasil Diterbitkan!');
    }
    
    public function hapus($id)
    {
        $this->db->table('tbl_jadwal_kelas')->where('id_jadwal_ujian', $id)->delete();
        $this->db->table('tbl_jadwal_ujian')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Jadwal dihapus.');
    }
   // --- HALAMAN MONITORING LIVE (FIXED COLUMN NAME) ---
    public function monitoring($idJadwal)
    {
        // 1. Ambil Data Jadwal
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_bank_soal.jumlah_soal')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->where('tbl_jadwal_ujian.id', $idJadwal)
            ->get()->getRowArray();

        if (!$jadwal) return redirect()->back()->with('error', 'Jadwal tidak ditemukan');

        // 2. Ambil Kelas yang ditargetkan
        $kelasTarget = $this->db->table('tbl_jadwal_kelas')
            ->where('id_jadwal_ujian', $idJadwal)
            ->get()->getResultArray();
        
        $idKelasArr = array_column($kelasTarget, 'id_kelas');

        // --- DETEKSI NAMA KOLOM KELAS DI DATABASE ---
        $fields = $this->db->getFieldNames('tbl_siswa'); // Ambil semua nama kolom
        $kolomKelas = 'id_kelas'; // Default
        
        if (in_array('kelas_id', $fields)) {
            $kolomKelas = 'kelas_id';
        } elseif (in_array('id_rombel', $fields)) {
            $kolomKelas = 'id_rombel';
        } elseif (in_array('rombel_id', $fields)) {
            $kolomKelas = 'rombel_id';
        } 
        // ---------------------------------------------

        // 3. Ambil Semua Siswa + Status Ujian
        if (empty($idKelasArr)) {
            $siswa = [];
        } else {
            $siswa = $this->db->table('tbl_siswa')
                ->select('tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas, tbl_ujian_siswa.id as id_sesi, tbl_ujian_siswa.status, tbl_ujian_siswa.waktu_mulai, tbl_ujian_siswa.nilai, tbl_ujian_siswa.ip_address, tbl_ujian_siswa.is_blocked')
                
                // JOIN PAKE NAMA KOLOM YANG DITEMUKAN
                ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.' . $kolomKelas) 
                
                ->join('tbl_ujian_siswa', 'tbl_ujian_siswa.id_siswa = tbl_siswa.id AND tbl_ujian_siswa.id_bank_soal = ' . $jadwal['id_bank_soal'], 'left')
                
                // WHERE PAKE NAMA KOLOM YANG DITEMUKAN
                ->whereIn('tbl_siswa.' . $kolomKelas, $idKelasArr)
                
                ->orderBy('tbl_kelas.nama_kelas', 'ASC')
                ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
                ->get()->getResultArray();
        }

        // Hitung Statistik
        $stats = [
            'total' => count($siswa),
            'sudah' => 0,
            'sedang'=> 0,
            'belum' => 0
        ];

        foreach($siswa as $s) {
            if ($s['status'] === '1') $stats['sudah']++;
            elseif ($s['status'] === '0') $stats['sedang']++;
            else $stats['belum']++;
        }

        return view('guru/ujian/monitoring', [
            'title' => 'Monitoring Ujian',
            'jadwal' => $jadwal,
            'siswa' => $siswa,
            'stats' => $stats
        ]);
    }
    // --- FITUR RESET PESERTA (JIKA LOGOUT TIBA-TIBA) ---
    public function resetPeserta()
    {
        $idSesi = $this->request->getPost('id_sesi');
        // Hapus sesi agar siswa bisa login ulang / mulai dari awal
        // Opsi A: Hapus Total (Ulang dari nol) -> $this->db->table('tbl_ujian_siswa')->delete(['id' => $idSesi]);
        // Opsi B: Reset Status saja (Lanjut mengerjakan) -> 
        $this->db->table('tbl_ujian_siswa')->update(['status' => 0, 'is_blocked' => 0], ['id' => $idSesi]);
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Status peserta berhasil di-reset. Siswa bisa login kembali.']);
    }
}