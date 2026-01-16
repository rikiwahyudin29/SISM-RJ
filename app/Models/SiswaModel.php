<?php
namespace App\Models;
use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'tbl_siswa';
    protected $primaryKey = 'id';
   protected $allowedFields = [
    'user_id',         // <--- INI WAJIB ADA (Kunci Relasi)
    'nisn',            // Username Login
    'nis', 
    'nama_lengkap', 
    'kelas_id',
    'jurusan_id',
    'jenis_kelamin',
    'tempat_lahir',
    'tanggal_lahir',
    'agama',
    'alamat',
    'no_hp_siswa',
    'email_siswa',
    'nama_ayah',
    'nama_ibu',
    'nama_wali',
    'no_hp_ortu',
    'status_siswa',
    'foto'
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