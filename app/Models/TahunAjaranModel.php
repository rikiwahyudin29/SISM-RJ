<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAjaranModel extends Model
{
    protected $table            = 'tbl_tahun_ajaran';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['tahun', 'semester', 'status'];
    protected $useTimestamps    = true;
}