<?php
namespace App\Models;
use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'tbl_siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nis', 'nisn', 'nama', 'tempat_lahir', 'tanggal_lahir', 
        'jk', 'nik', 'nama_ibu', 'kelas_id', 'ekskul_id', 'status'
    ];

    // Fungsi JOIN tabel agar yang muncul Nama Kelas & Nama Ekskul (Bukan ID nya)
    public function getSiswaLengkap()
    {
        return $this->select('tbl_siswa.*, tbl_kelas.nama_kelas, tbl_ekskul.nama_ekskul')
                    ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
                    ->join('tbl_ekskul', 'tbl_ekskul.id = tbl_siswa.ekskul_id', 'left')
                    ->findAll();
    }
}