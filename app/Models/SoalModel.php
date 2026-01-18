<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table            = 'tbl_soal';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_bank_soal', 'tipe_soal', 'pertanyaan', 'file_audio', 'file_video', 'bobot'];
    protected $useTimestamps    = true;
}