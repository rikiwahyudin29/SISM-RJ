<?php
namespace App\Models;
use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'tbl_kelas';
    protected $primaryKey = 'id';
    
    // REVISI: Tambahkan id_jurusan agar bisa disimpan ke database
    protected $allowedFields = ['nama_kelas', 'guru_id', 'id_jurusan'];
    
    public function getKelasLengkap()
    {
        return $this->select('
                        tbl_kelas.*, 
                        tbl_guru.nama_lengkap as nama_guru, 
                        tbl_jurusan.kode_jurusan, 
                        tbl_jurusan.nama_jurusan,
                        COUNT(tbl_siswa.id) as jumlah_siswa
                    ')
                    // Join ke tabel guru sesuai guru_id di database Bos
                    ->join('tbl_guru', 'tbl_guru.id = tbl_kelas.guru_id', 'left')
                    // REVISI: Join ke tabel jurusan agar tidak muncul "UMUM" terus
                    ->join('tbl_jurusan', 'tbl_jurusan.id = tbl_kelas.id_jurusan', 'left')
                    // Join ke tabel siswa untuk hitung total murid
                    ->join('tbl_siswa', 'tbl_siswa.kelas_id = tbl_kelas.id', 'left')
                    ->groupBy('tbl_kelas.id')
                    ->findAll();
    }
}