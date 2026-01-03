<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table            = 'tbl_pengaturan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['kunci', 'nilai'];
    protected $useTimestamps    = false;

    // FUNGSI SAKTI: Mengubah data baris jadi Array
    // Dari:  [ {kunci: 'email', nilai: 'a@a.com'} ]
    // Jadi:  ['email' => 'a@a.com']
    public function ambilDataArray()
    {
        $semuaData = $this->findAll();
        $hasil = [];
        
        foreach ($semuaData as $row) {
            $hasil[$row['kunci']] = $row['nilai'];
        }

        return $hasil;
    }

    // FUNGSI UPDATE PINTAR
    public function simpanBatch($dataInput)
    {
        foreach ($dataInput as $key => $value) {
            // Cek apakah kuncinya valid (ada di database)?
            // Kita skip 'id', 'csrf_token', atau tombol submit
            if ($this->where('kunci', $key)->countAllResults() > 0) {
                $this->where('kunci', $key)->set(['nilai' => $value])->update();
            }
        }
    }
}