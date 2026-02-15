<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Materi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil data siswa berdasarkan session login (id_user)
        $id_user = session()->get('id_user');
        $siswa = $this->db->table('tbl_siswa')->where('id_user', $id_user)->get()->getRow();

        if (!$siswa) {
            return redirect()->to('login')->with('error', 'Sesi siswa tidak ditemukan.');
        }

        // Ambil materi yang ditujukan untuk kelas siswa tersebut
        $materi = $this->db->table('tbl_materi')
            ->select('tbl_materi.*, tbl_mapel.nama_mapel, tbl_guru.nama_lengkap as nama_guru')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_materi.mapel_id')
            ->join('tbl_guru', 'tbl_guru.id = tbl_materi.guru_id', 'left')
            ->where('tbl_materi.kelas_id', $siswa->kelas_id)
            ->orderBy('tbl_materi.created_at', 'DESC')
            ->get()->getResultArray();

        return view('siswa/materi/index', [
            'title'  => 'Bahan Ajar & Materi',
            'materi' => $materi,
            'siswa'  => $siswa
        ]);
    }
}