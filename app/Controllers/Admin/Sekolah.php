<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Sekolah extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // HALAMAN IDENTITAS SEKOLAH
    public function identitas()
    {
        // Ambil data sekolah dari database
        // Kita pakai getRow() karena datanya cuma 1 baris
        $sekolah = $this->db->table('tbl_sekolah')->get()->getRow();

        $data = [
            'title'   => 'Identitas Sekolah',
            'sekolah' => $sekolah
        ];

        // Arahkan ke file view (nanti kita buat di Langkah 2)
        return view('admin/sekolah/identitas', $data);
    }
}