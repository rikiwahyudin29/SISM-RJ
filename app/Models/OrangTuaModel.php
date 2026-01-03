<?php

namespace App\Models;

use CodeIgniter\Model;

class OrangTuaModel extends Model
{
    protected $table            = 'tbl_orangtua';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['siswa_id', 'nama_ayah', 'pekerjaan_ayah', 'no_hp_ortu'];

    // Fungsi JOIN 3 Tabel (OrangTua -> Siswa -> Kelas)
    public function getOrangTuaLengkap()
    {
        return $this->select('tbl_orangtua.*, tbl_siswa.nama as nama_siswa, tbl_siswa.nis, tbl_siswa.nama_ibu, tbl_kelas.nama_kelas')
                    ->join('tbl_siswa', 'tbl_siswa.id = tbl_orangtua.siswa_id')
                    ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
                    ->findAll();
    }
}