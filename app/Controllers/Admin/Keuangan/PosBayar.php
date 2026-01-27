<?php

namespace App\Controllers\Admin\Keuangan;

use App\Controllers\BaseController;

class PosBayar extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Pos Bayar',
            'pos'   => $this->db->table('tbl_pos_bayar')->orderBy('id', 'DESC')->get()->getResultArray()
        ];
        return view('admin/keuangan/pos/index', $data);
    }

    public function simpan()
    {
        $data = [
            'nama_pos'   => $this->request->getPost('nama_pos'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];
        $this->db->table('tbl_pos_bayar')->insert($data);
        return redirect()->back()->with('success', 'Data Pos berhasil disimpan');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $data = [
            'nama_pos'   => $this->request->getPost('nama_pos'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];
        $this->db->table('tbl_pos_bayar')->where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Data Pos berhasil diperbarui');
    }

    public function hapus()
    {
        $id = $this->request->getPost('id');
        // Cek apakah sudah dipakai di jenis_bayar (Relasi)
        $cek = $this->db->table('tbl_jenis_bayar')->where('id_pos_bayar', $id)->countAllResults();
        
        if($cek > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Pos ini sudah digunakan dalam setting tagihan.');
        }

        $this->db->table('tbl_pos_bayar')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Pos berhasil dihapus');
    }
}