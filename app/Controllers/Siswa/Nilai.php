<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Nilai extends BaseController
{
    protected $db;
    protected $id_siswa;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->id_siswa = session()->get('id_user');
    }

    public function index()
    {
        // Ambil data ujian yang SUDAH SELESAI (status = 1) milik siswa ini
        $riwayat = $this->db->table('tbl_ujian_siswa')
            ->select('
                tbl_ujian_siswa.*,
                tbl_jadwal_ujian.id as id_jadwal,
                tbl_bank_soal.judul_ujian,
                tbl_bank_soal.jumlah_soal_pg,
                tbl_bank_soal.jumlah_soal_esai,
                tbl_mapel.nama_mapel,
                tbl_jadwal_ujian.setting_tampil_nilai,
                tbl_guru.nama_lengkap as nama_guru
            ')
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_ujian_siswa.id_jadwal')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal_ujian.id_guru')
            ->where('tbl_ujian_siswa.id_siswa', $this->id_siswa)
            ->where('tbl_ujian_siswa.status', 1) // Hanya yang sudah submit
            ->orderBy('tbl_ujian_siswa.waktu_submit', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title'   => 'Riwayat Nilai',
            'riwayat' => $riwayat
        ];

        return view('siswa/nilai/index', $data);
    }
}