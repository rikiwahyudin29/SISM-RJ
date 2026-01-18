<?php

namespace App\Models;

use CodeIgniter\Model;

class JamModel extends Model
{
    protected $table            = 'tbl_jam_master';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['urutan', 'nama_jam', 'jam_mulai', 'jam_selesai', 'is_istirahat'];
    
    // Ambil jam pelajaran saja (bukan istirahat) urut dari pagi
    public function getJamPelajaran()
    {
        return $this->where('is_istirahat', 0)->orderBy('urutan', 'ASC')->findAll();
    }

    // Ambil SEMUA jam (untuk perhitungan durasi)
    public function getAllJam()
    {
        return $this->orderBy('urutan', 'ASC')->findAll();
    }
}