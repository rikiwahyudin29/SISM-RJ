<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id_user');

        // 1. AMBIL DATA SISWA (Untuk tahu dia kelas berapa)
        $siswa = $db->table('tbl_siswa')
                    ->select('tbl_siswa.*, tbl_kelas.nama_kelas, tbl_jurusan.nama_jurusan')
                    ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
                    ->join('tbl_jurusan', 'tbl_jurusan.id = tbl_siswa.jurusan_id', 'left')
                    ->where('tbl_siswa.user_id', $userId)
                    ->get()->getRowArray();

        if (!$siswa) {
            return "Error: Data profil siswa tidak ditemukan.";
        }

        // 2. AMBIL JADWAL PELAJARAN (Sesuai Kelas Siswa)
        $jadwal = [];
        if (!empty($siswa['kelas_id'])) {
            $jadwal = $db->table('tbl_jadwal')
                         ->select('tbl_jadwal.*, tbl_mapel.nama_mapel, tbl_guru.nama_lengkap as nama_guru')
                         ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
                         ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal.id_guru')
                         ->where('tbl_jadwal.id_kelas', $siswa['kelas_id'])
                         ->orderBy('FIELD(hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")')
                         ->orderBy('jam_mulai', 'ASC')
                         ->get()->getResultArray();
        }

        $data = [
            'title'  => 'Dashboard Siswa',
            'siswa'  => $siswa,
            'jadwal' => $jadwal
        ];

        return view('siswa/dashboard', $data);
    }
}