<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Mengawas extends BaseController
{
    protected $db;
    protected $id_user_login;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->id_user_login = session()->get('id_user');
    }

    public function index()
    {
        // KITA SESUAIKAN QUERY DENGAN PERMINTAAN VIEW (index.php)
        
        $ruangan = $this->db->table('tbl_jadwal_ujian')
            ->select('
                tbl_jadwal_ujian.id, 
                tbl_jadwal_ujian.status,
                
                -- ALIAS 1: View minta "nama_ruangan", kita ambil dari "nama_kelas"
                tbl_kelas.nama_kelas AS nama_ruangan, 
                
                -- ALIAS 2: View minta "jumlah_siswa", kita hitung dari tabel siswa
                (SELECT COUNT(*) FROM tbl_siswa WHERE tbl_siswa.id_kelas = tbl_kelas.id) AS jumlah_siswa
            ')
            // JOIN UTAMA
            ->join('tbl_jadwal_kelas', 'tbl_jadwal_kelas.id_jadwal_ujian = tbl_jadwal_ujian.id')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal_kelas.id_kelas')
            
            // FILTER: Hanya Ujian yang STATUSNYA AKTIF (1)
            ->where('tbl_jadwal_ujian.status', 1) 
            
            // GROUP BY: Supaya kalau 1 jadwal ada banyak mapel/guru, tidak duplikat
            ->groupBy('tbl_jadwal_ujian.id')
            
            ->orderBy('tbl_jadwal_ujian.waktu_mulai', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title'   => 'Mengawas Ujian',
            'ruangan' => $ruangan // Ini variabel yang ditunggu View
        ];

        return view('guru/mengawas/index', $data);
    }
}