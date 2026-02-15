<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Rapor extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Halaman Pilih Siswa untuk Dicetak
    public function index()
    {
        $kelas_id = $this->request->getGet('kelas_id');
        
        $data = [
            'title' => 'Cetak Rapor Semester',
            'kelas' => $this->db->table('tbl_kelas')->get()->getResultArray(),
            'siswa' => []
        ];

        if ($kelas_id) {
            $data['siswa'] = $this->db->table('tbl_siswa')
                ->select('id, nama_lengkap, nis')
                ->where('kelas_id', $kelas_id)
                ->orderBy('nama_lengkap', 'ASC')
                ->get()->getResultArray();
        }

        return view('guru/rapor/index', $data);
    }

    // Halaman Cetak (PDF View)
    public function cetak($siswa_id)
    {
        // 1. Ambil Data Siswa & Kelas
        // PERBAIKAN: Mengambil 'guru_id' dari tabel kelas sebagai 'wali_kelas_id'
        $siswa = $this->db->table('tbl_siswa')
            ->select('tbl_siswa.*, tbl_kelas.nama_kelas, tbl_kelas.guru_id as wali_kelas_id') 
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            ->where('tbl_siswa.id', $siswa_id)
            ->get()->getRow();

        if (!$siswa) return "Data siswa tidak ditemukan!";

        // 2. Ambil Nilai Mapel
        $nilai = $this->db->table('tbl_nilai')
            ->select('tbl_nilai.*, tbl_mapel.nama_mapel, tbl_mapel.kode_mapel, tbl_set_nilai.kkm')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_nilai.mapel_id')
            ->join('tbl_set_nilai', 'tbl_set_nilai.mapel_id = tbl_nilai.mapel_id AND tbl_set_nilai.kelas_id = tbl_nilai.kelas_id', 'left')
            ->where('tbl_nilai.siswa_id', $siswa_id)
            ->orderBy('tbl_mapel.nama_mapel', 'ASC')
            ->get()->getResultArray();

        // 3. Ambil Absensi & Catatan Wali
        $catatan = $this->db->table('tbl_catatan_wali')
            ->where('siswa_id', $siswa_id)
            ->get()->getRow();

        // 4. Data Sekolah (Bisa disesuaikan manual atau ambil dari database setting)
        $sekolah = [
            'nama'    => 'SMK DIGITAL INDONESIA',
            'alamat'  => 'Jl. Teknologi No. 1, Jakarta Selatan',
            'kepsek'  => 'Dr. H. Ahmad Dahlan, M.Pd',
            'nip'     => '19750101 200001 1 001'
        ];

        // 5. Nama Wali Kelas
        // Menggunakan ID yang sudah kita ambil tadi (guru_id)
        $nama_wali = '..........................';
        if (!empty($siswa->wali_kelas_id)) {
            $wali = $this->db->table('tbl_guru')
                ->select('nama_lengkap') // Pastikan di tbl_guru kolomnya 'nama_lengkap' atau 'nama_guru'
                ->where('id', $siswa->wali_kelas_id)
                ->get()->getRow();
                
            if ($wali) {
                // Cek variasi nama kolom di tbl_guru (jaga-jaga)
                $nama_wali = $wali->nama_lengkap ?? $wali->nama_guru ?? $wali->nama ?? 'Nama Tidak Ditemukan';
            }
        }

        return view('guru/rapor/cetak', [
            'siswa'   => $siswa,
            'nilai'   => $nilai,
            'catatan' => $catatan,
            'sekolah' => $sekolah,
            'wali'    => $nama_wali,
            'tahun'   => date('Y') . ' / ' . (date('Y')+1), 
            'semester'=> (date('n') > 6) ? 'Ganjil' : 'Genap',
            'tanggal' => date('d F Y')
        ]);
    }
}