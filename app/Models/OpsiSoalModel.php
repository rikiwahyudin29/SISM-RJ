<?php

namespace App\Models;

use CodeIgniter\Model;

class OpsiSoalModel extends Model
{
    protected $table            = 'tbl_opsi_soal';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_soal', 'kode_opsi', 'teks_opsi', 'is_benar', 'pasangan_uuid'];
}