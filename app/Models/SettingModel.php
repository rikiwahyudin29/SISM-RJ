<?php
namespace App\Models;
use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'tbl_pengaturan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kunci', 'nilai'];

    public function getVal($kunci) {
        $row = $this->where('kunci', $kunci)->first();
        return $row ? $row['nilai'] : '';
    }
}