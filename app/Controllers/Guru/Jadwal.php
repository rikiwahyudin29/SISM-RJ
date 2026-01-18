<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Jadwal extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id_user');

        // 1. Cari Data Guru berdasarkan User Login
        $guru = $db->table('tbl_guru')->where('user_id', $userId)->get()->getRowArray();

        if (!$guru) {
            return "Error: Akun Anda belum terhubung dengan Data Guru. Hubungi Admin.";
        }

        // 2. Ambil Jadwal Mengajar Guru Ini
        $jadwal = $db->table('tbl_jadwal')
                     ->select('tbl_jadwal.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
                     ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal.id_kelas')
                     ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
                     ->where('tbl_jadwal.id_guru', $guru['id']) // Filter PENTING: Cuma jadwal dia sendiri
                     ->orderBy('FIELD(hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")')
                     ->orderBy('jam_mulai', 'ASC')
                     ->get()->getResultArray();

        $data = [
            'title'  => 'Jadwal Mengajar Saya',
            'guru'   => $guru,
            'jadwal' => $jadwal
        ];

        return view('guru/jadwal/index', $data);
    }
}