<?php

namespace App\Controllers\Admin; // <--- Namespace langsung ke Admin (Bukan Master)

use App\Controllers\BaseController;
use App\Models\JamModel;

class Jam extends BaseController
{
    protected $jamModel;

    public function __construct()
    {
        $this->jamModel = new JamModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Jam Pelajaran',
            'jam'   => $this->jamModel->getAllJam()
        ];

        // View tetap saya arahkan ke folder master biar rapi di Views
        return view('admin/master/jam.php', $data);
    }

    public function simpan()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'urutan'      => $this->request->getPost('urutan'),
            'nama_jam'    => $this->request->getPost('nama_jam'),
            'jam_mulai'   => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'is_istirahat'=> $this->request->getPost('is_istirahat') ? 1 : 0 
        ];

        // Validasi
        if ($data['jam_mulai'] >= $data['jam_selesai']) {
            return redirect()->back()->withInput()->with('error', 'Jam Selesai harus lebih besar dari Jam Mulai');
        }

        if ($id) {
            $this->jamModel->update($id, $data);
            $msg = 'Data Jam berhasil diperbarui!';
        } else {
            $this->jamModel->insert($data);
            $msg = 'Data Jam berhasil ditambahkan!';
        }

        // Redirect URL disesuaikan
        return redirect()->to(base_url('admin/jam'))->with('success', $msg);
    }

    public function hapus($id)
    {
        $this->jamModel->delete($id);
        return redirect()->to(base_url('admin/jam'))->with('success', 'Data berhasil dihapus.');
    }
}