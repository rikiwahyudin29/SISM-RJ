<?php
namespace App\Models;
use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'tbl_kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_kelas', 'guru_id'];
    
    public function getKelasLengkap()
    {
        return $this->select('tbl_kelas.*, gurus.nama_lengkap, gurus.gelar_depan, gurus.gelar_belakang, COUNT(tbl_siswa.id) as jumlah_siswa')
                    ->join('gurus', 'gurus.id = tbl_kelas.guru_id', 'left')
                    // Join ke tabel siswa untuk menghitung jumlahnya
                    ->join('tbl_siswa', 'tbl_siswa.kelas_id = tbl_kelas.id', 'left')
                    ->groupBy('tbl_kelas.id') // Wajib digroup biar hitungannya per kelas
                    ->findAll();
    }
}