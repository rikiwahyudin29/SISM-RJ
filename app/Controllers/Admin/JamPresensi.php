<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class JamPresensi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil data setting (ID 1)
        $jam = $this->db->table('tbl_jam_sekolah')->where('id', 1)->get()->getRowArray();

        // Jika tabel kosong (belum ada data), insert data default
        if (!$jam) {
            $this->db->table('tbl_jam_sekolah')->insert([
                'id' => 1,
                'jam_masuk_mulai' => '06:00:00',
                'jam_masuk_akhir' => '07:15:00',
                'jam_pulang_mulai' => '14:00:00'
            ]);
            return redirect()->to('admin/presensi/jam');
        }

        return view('admin/presensi/jam', [
            'title' => 'Pengaturan Jam Sekolah',
            'jam'   => $jam
        ]);
    }

    public function update()
    {
        $data = [
            // Data Jam
            'jam_masuk_mulai'   => $this->request->getPost('jam_masuk_mulai'),
            'jam_masuk_akhir'   => $this->request->getPost('jam_masuk_akhir'),
            'jam_pulang_mulai'  => $this->request->getPost('jam_pulang_mulai'),
            
            // Data Lokasi (BARU)
            'latitude'          => $this->request->getPost('latitude'),
            'longitude'         => $this->request->getPost('longitude'),
            'radius'            => $this->request->getPost('radius'),
        ];

        $this->db->table('tbl_jam_sekolah')->where('id', 1)->update($data);
        
        return redirect()->to('admin/presensi/jam')->with('success', 'Pengaturan Jam & Lokasi berhasil disimpan!');
    }
}