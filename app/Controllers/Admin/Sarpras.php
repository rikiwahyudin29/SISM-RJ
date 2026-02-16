<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Sarpras extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Fitur Pencarian Sederhana
        $keyword = $this->request->getGet('q');
        $builder = $this->db->table('tbl_inventaris');

        if ($keyword) {
            $builder->like('nama_barang', $keyword)
                    ->orLike('kode_barang', $keyword)
                    ->orLike('lokasi', $keyword);
        }

        $data = [
            'title'  => 'Data Inventaris Barang',
            'barang' => $builder->orderBy('id', 'DESC')->get()->getResultArray(),
            'keyword'=> $keyword
        ];

        return view('admin/sarpras/index', $data);
    }

    public function save()
    {
        $this->db->table('tbl_inventaris')->insert([
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'lokasi'      => $this->request->getPost('lokasi'),
            'jumlah'      => $this->request->getPost('jumlah'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'tgl_masuk'   => $this->request->getPost('tgl_masuk'),
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('admin/sarpras')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->db->table('tbl_inventaris')->where('id', $id)->update([
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'lokasi'      => $this->request->getPost('lokasi'),
            'jumlah'      => $this->request->getPost('jumlah'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('admin/sarpras')->with('success', 'Data barang diperbarui.');
    }

    public function delete($id)
    {
        $this->db->table('tbl_inventaris')->where('id', $id)->delete();
        return redirect()->to('admin/sarpras')->with('success', 'Barang dihapus dari inventaris.');
    }
}