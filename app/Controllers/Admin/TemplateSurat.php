<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class TemplateSurat extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Template Surat',
            'templates' => $this->db->table('tbl_surat_template')->get()->getResultArray()
        ];
        return view('admin/template_surat/index', $data);
    }

    public function save()
    {
        $this->db->table('tbl_surat_template')->insert([
            'nama_template' => $this->request->getPost('nama_template'),
            'format_nomor'  => $this->request->getPost('format_nomor'),
            'isi_html'      => $this->request->getPost('isi_html'),
        ]);
        return redirect()->to('admin/templatesurat')->with('success', 'Template berhasil dibuat.');
    }

    public function delete($id)
    {
        $this->db->table('tbl_surat_template')->where('id', $id)->delete();
        return redirect()->to('admin/templatesurat')->with('success', 'Template dihapus.');
    }
}