<?php

namespace App\Controllers\Admin\Keuangan;

use App\Controllers\BaseController;

class Log extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

 public function index()
    {
        // Ambil 200 data log terakhir
        $logs = $this->db->table('tbl_log_keuangan')
            ->orderBy('created_at', 'DESC')
            ->limit(200)
            ->get()->getResultArray();

        $data = [
            'title' => 'Audit Trail & Log Aktivitas',
            'logs'  => $logs
        ];

        // Pastikan View ini juga sudah dibuat
        return view('admin/keuangan/log/index', $data);
    }
}