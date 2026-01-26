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
        // Filter: Hanya jadwal milik guru yang sedang login
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_mapel.nama_mapel, 
                      (SELECT COUNT(*) FROM tbl_jadwal_kelas WHERE id_jadwal_ujian = tbl_jadwal_ujian.id) as total_kelas')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal_ujian.id_guru') 
            ->where('tbl_guru.user_id', $guruId) 
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        return view('guru/ujian/index', ['title' => 'Manajemen Jadwal Ujian', 'jadwal' => $jadwal]);
    }

    public function tambah()
    {
        $userId = session()->get('id_user'); 
        
        // Ambil data Guru berdasarkan User ID Login
        $guru = $this->db->table('tbl_guru')->where('user_id', $userId)->get()->getRow();
        
        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil guru tidak ditemukan. Hubungi Admin.');
        }

        $data = [
            'title' => 'Buat Jadwal Ujian',
            
            // Ambil Bank Soal MILIK GURU INI SAJA
            'bank_soal' => $this->db->table('tbl_bank_soal')
                            ->select('tbl_bank_soal.*, tbl_mapel.nama_mapel')
                            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel', 'left') 
                            ->where('tbl_bank_soal.id_guru', $guru->id) 
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
        $userId = session()->get('id_user');
        
        // 1. Ambil ID Guru yang sedang login
        $guru = $this->db->table('tbl_guru')->where('user_id', $userId)->get()->getRow();

        if (!$guru) {
            return redirect()->back()->with('error', 'Sesi Guru Valid Tidak Ditemukan');
        }

        $data = [
            'nama_ujian'    => $this->request->getPost('nama_ujian'),
            'id_bank_soal'  => $this->request->getPost('id_bank_soal'),
            
            // [FIX PENTING] Simpan ID Guru agar muncul "Guru Pengampu"
            'id_guru'       => $guru->id, 

            // Data Lainnya
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
            'id_jenis_ujian'  => $this->request->getPost('id_jenis_ujian'),
            'bobot_pg'        => $this->request->getPost('bobot_pg'),
            'bobot_esai'      => $this->request->getPost('bobot_esai'),
            
            'waktu_mulai'       => $this->request->getPost('waktu_mulai'),
            'waktu_selesai'     => $this->request->getPost('waktu_selesai'),
            'durasi'            => $this->request->getPost('durasi'),
            'min_waktu_selesai' => $this->request->getPost('min_waktu') ?? 0, // Pastikan input name di view adalah 'min_waktu'
            
            // Settings
            'setting_strict'        => $this->request->getPost('setting_strict') ? 1 : 0,
            'setting_afk_timeout'   => $this->request->getPost('setting_afk_timeout') ?? 0,
            'setting_max_violation' => $this->request->getPost('setting_max_violation') ?? 3,
            'setting_token'         => $this->request->getPost('setting_token') ? 1 : 0,
            'token'                 => strtoupper($this->request->getPost('token')),
            'setting_show_score'    => $this->request->getPost('setting_show_score') ? 1 : 0,
            'setting_multi_login'   => $this->request->getPost('setting_multi_login') ? 1 : 0,
            'acak_soal'             => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_opsi'             => $this->request->getPost('acak_opsi') ? 1 : 0,
            'status_ujian'          => 'AKTIF',
            'created_at'            => date('Y-m-d H:i:s')
        ];

        $this->db->table('tbl_jadwal_ujian')->insert($data);
        $idJadwal = $this->db->insertID();

        // Simpan Relasi Kelas
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

    // --- HALAMAN MONITORING LIVE ---
    public function monitoring($idJadwal)
    {
        // 1. Ambil Data Jadwal
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_bank_soal.jumlah_soal, tbl_mapel.nama_mapel')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel') // Join Mapel biar namanya ada
            ->where('tbl_jadwal_ujian.id', $idJadwal)
            ->get()->getRowArray();

        if (!$jadwal) return redirect()->back()->with('error', 'Jadwal tidak ditemukan');

        // 2. Ambil Kelas Target
        $kelasTarget = $this->db->table('tbl_jadwal_kelas')
            ->where('id_jadwal_ujian', $idJadwal)
            ->get()->getResultArray();
        
        $idKelasArr = array_column($kelasTarget, 'id_kelas');

        // Deteksi Kolom Kelas di Tabel Siswa
        $fields = $this->db->getFieldNames('tbl_siswa'); 
        $kolomKelas = 'id_kelas'; // Default
        if (in_array('kelas_id', $fields)) $kolomKelas = 'kelas_id';
        elseif (in_array('id_rombel', $fields)) $kolomKelas = 'id_rombel';
        elseif (in_array('rombel_id', $fields)) $kolomKelas = 'rombel_id';

        // 3. Ambil Siswa + Status (FIXED JOIN LOGIC)
        if (empty($idKelasArr)) {
            $siswa = [];
        } else {
            $siswa = $this->db->table('tbl_siswa')
                ->select('
                    tbl_siswa.nama_lengkap, 
                    tbl_siswa.nis, 
                    tbl_kelas.nama_kelas, 
                    tbl_ujian_siswa.id as id_sesi, 
                    tbl_ujian_siswa.status, 
                    tbl_ujian_siswa.waktu_mulai, 
                    tbl_ujian_siswa.nilai, 
                    tbl_ujian_siswa.ip_address, 
                    tbl_ujian_siswa.is_blocked,
                    "' . $jadwal['nama_mapel'] . '" as mapel_sekarang  // Tambahkan Nama Mapel ke Row Siswa
                ')
                
                ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.' . $kolomKelas) 
                
                // --- [PERBAIKAN UTAMA DISINI] ---
                // Join berdasarkan ID_JADWAL, bukan Bank Soal.
                // Ini membuat monitoring TERISOLASI per jadwal.
                // Jadwal hari ini tidak akan tercampur dengan jawaban kemarin.
                ->join('tbl_ujian_siswa', 'tbl_ujian_siswa.id_siswa = tbl_siswa.id AND tbl_ujian_siswa.id_jadwal = ' . $idJadwal, 'left')
                
                ->whereIn('tbl_siswa.' . $kolomKelas, $idKelasArr)
                ->orderBy('tbl_kelas.nama_kelas', 'ASC')
                ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
                ->get()->getResultArray();
        }

        // Hitung Statistik
        $stats = ['total' => count($siswa), 'sudah' => 0, 'sedang'=> 0, 'belum' => 0];
        foreach($siswa as $s) {
            if ($s['status'] === '1') $stats['sudah']++; // Status '1' dari Database
            elseif ($s['status'] === '0') $stats['sedang']++; // Status '0' dari Database
            else $stats['belum']++;
        }

        return view('guru/ujian/monitoring', [
            'title' => 'Monitoring Ujian',
            'jadwal' => $jadwal,
            'siswa' => $siswa,
            'stats' => $stats
        ]);
    }

    // --- FITUR RESET PESERTA (Reset Sesi) ---
    public function resetPeserta()
    {
        $idSesi = $this->request->getPost('id_sesi');
        
        if(!$idSesi) return $this->response->setJSON(['status' => 'error', 'message' => 'ID Sesi tidak valid']);

        // Reset Status ke 0 (Mengerjakan) dan Buka Blokir
        // Agar siswa bisa login lagi dan melanjutkan (atau mulai ulang tergantung logic di siswa)
        $this->db->table('tbl_ujian_siswa')->update(
            ['status' => 0, 'is_blocked' => 0, 'is_locked' => 0, 'ip_address' => null], 
            ['id' => $idSesi]
        );
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Peserta berhasil di-reset.']);
    }
}