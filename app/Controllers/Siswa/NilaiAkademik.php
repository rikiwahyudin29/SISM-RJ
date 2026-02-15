<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class NilaiAkademik extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $id_user = session()->get('id_user'); // Ambil ID User Login
        
        // 1. Cari ID Siswa Asli berdasarkan User ID
        // Kita cek kolom relasi di tbl_siswa (apakah 'user_id' atau 'id_user')
        $kolom_user = $this->db->fieldExists('id_user', 'tbl_siswa') ? 'id_user' : 'user_id';
        $siswa = $this->db->table('tbl_siswa')->where($kolom_user, $id_user)->get()->getRow();

        if (!$siswa) {
            return redirect()->to('siswa/dashboard')->with('error', 'Data siswa tidak ditemukan untuk akun ini.');
        }

        // 2. Ambil Nilai Akademik (E-Rapor)
        $nilai = $this->db->table('tbl_nilai')
            ->select('tbl_nilai.*, tbl_mapel.nama_mapel, tbl_set_nilai.kkm')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_nilai.mapel_id')
            ->join('tbl_set_nilai', 'tbl_set_nilai.mapel_id = tbl_nilai.mapel_id AND tbl_set_nilai.kelas_id = tbl_nilai.kelas_id', 'left')
            ->where('tbl_nilai.siswa_id', $siswa->id)
            ->orderBy('tbl_mapel.nama_mapel', 'ASC')
            ->get()->getResultArray();

        // 3. Ambil Catatan Wali Kelas
        $catatan = $this->db->table('tbl_catatan_wali')
            ->where('siswa_id', $siswa->id)
            ->get()->getRow();

        // Mengarah ke folder view 'nilai_akademik'
        return view('siswa/nilai_akademik/index', [
            'title'   => 'Laporan Hasil Belajar',
            'siswa'   => $siswa,
            'nilai'   => $nilai,
            'catatan' => $catatan
        ]);
    }
}