<?php

namespace App\Models;

use CodeIgniter\Model;

class MapelModel extends Model
{
    protected $table            = 'tbl_mapel';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['kode_mapel', 'nama_mapel', 'kelompok']; // Sesuaikan dengan kolom di DB Bos
    protected $useTimestamps    = true;
}