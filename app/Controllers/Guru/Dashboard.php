<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Helper: Translate Hari
    private function getHariIndo($day) {
        $hari = ['Sunday'=>'Minggu', 'Monday'=>'Senin', 'Tuesday'=>'Selasa', 'Wednesday'=>'Rabu', 'Thursday'=>'Kamis', 'Friday'=>'Jumat', 'Saturday'=>'Sabtu'];
        return $hari[$day] ?? 'Senin';
    }

    // Helper: Ambil ID Guru dari User Login
    private function getRealGuruID($id_user) {
        // Cek apakah ada kolom user_id atau id_user di tabel guru
        $kolom_user = $this->db->fieldExists('id_user', 'tbl_guru') ? 'id_user' : 'user_id';
        
        $guru = $this->db->table('tbl_guru')->where($kolom_user, $id_user)->get()->getRow();
        return $guru ? $guru->id : 0;
    }

    public function index()
    {
        $id_user = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user);
        
        $hari_ini = $this->getHariIndo(date('l'));
        $tanggal_ini = date('Y-m-d');
        $bulan_ini = date('Y-m');

        // 1. STATISTIK RINGKAS
        // Total Jadwal Hari Ini
        $total_jadwal = $this->db->table('tbl_jadwal')
            ->where('id_guru', $id_guru)
            ->where('hari', $hari_ini)
            ->countAllResults();

        // Total Jurnal Bulan Ini
        $total_jurnal = $this->db->table('tbl_jurnal')
            ->where('id_guru', $id_guru)
            ->like('tanggal', $bulan_ini)
            ->countAllResults();

        // 2. JADWAL MENGAJAR HARI INI (Untuk Shortcut)
        $jadwal_hari_ini = $this->db->table('tbl_jadwal')
            ->select('tbl_jadwal.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal.id_kelas')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
            ->where('id_guru', $id_guru)
            ->where('hari', $hari_ini)
            ->orderBy('jam_mulai', 'ASC')
            ->get()->getResultArray();

        // Cek status jurnal untuk jadwal hari ini (Sudah isi atau belum?)
        foreach($jadwal_hari_ini as &$j) {
            $cek = $this->db->table('tbl_jurnal')
                ->where('id_guru', $id_guru)
                ->where('id_kelas', $j['id_kelas'])
                ->where('id_mapel', $j['id_mapel'])
                ->where('tanggal', $tanggal_ini)
                ->countAllResults();
            $j['sudah_jurnal'] = ($cek > 0);
        }

        return view('guru/dashboard', [
            'title' => 'Dashboard Guru',
            'total_jadwal' => $total_jadwal,
            'total_jurnal' => $total_jurnal,
            'jadwal' => $jadwal_hari_ini,
            'hari_ini' => $hari_ini,
            'tanggal' => $tanggal_ini
        ]);
    }
}