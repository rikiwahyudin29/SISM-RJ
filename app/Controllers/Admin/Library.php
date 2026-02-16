<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Library extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

   public function index()
    {
        // 1. Ambil ID User yang sedang login
        $id_user = session()->get('id_user');
        $db = \Config\Database::connect();

        // 2. CEK DATABASE LANGSUNG (JANGAN PERCAYA SESSION)
        // Kita cek di tabel 'user_roles', apakah user ini punya Role ID 1 (Admin) atau 8 (Guru)
        //
        $canUpload = $db->table('user_roles')
                        ->where('user_id', $id_user)
                        ->whereIn('role_id', [1, 8]) // ID 1=Admin, 8=Guru
                        ->countAllResults() > 0;

        $data = [
            'title'      => 'Perpustakaan Digital',
            'buku'       => $this->db->table('tbl_elibrary')->orderBy('id', 'DESC')->get()->getResultArray(),
            'can_upload' => $canUpload // <-- INI KUNCINYA
        ];
        
        return view('admin/library/index', $data);
    }

    public function upload()
    {
        if (!$this->validate([
            'ebook' => 'uploaded[ebook]|ext_in[ebook,pdf]|max_size[ebook,10240]', // Max 10MB
            'cover' => 'is_image[cover]|max_size[cover,2048]' // Opsional
        ])) {
            return redirect()->back()->with('error', 'Format file salah. Ebook wajib PDF, Cover wajib Gambar.');
        }

        $filePdf = $this->request->getFile('ebook');
        $namaPdf = $filePdf->getRandomName();
        $filePdf->move('uploads/library', $namaPdf);

        // Handle Cover (Jika ada)
        $namaCover = 'default_book.png';
        $fileCover = $this->request->getFile('cover');
        if ($fileCover && $fileCover->isValid()) {
            $namaCover = $fileCover->getRandomName();
            $fileCover->move('uploads/library/covers', $namaCover);
        }

        $this->db->table('tbl_elibrary')->insert([
            'judul'     => $this->request->getPost('judul'),
            'kategori'  => $this->request->getPost('kategori'),
            'penulis'   => $this->request->getPost('penulis'),
            'file_ebook'=> $namaPdf,
            'cover'     => $namaCover
        ]);

        return redirect()->to('admin/library')->with('success', 'Buku berhasil ditambahkan ke rak digital.');
    }

    public function baca($id)
    {
        // Hitung viewer +1
        $this->db->query("UPDATE tbl_elibrary SET diakses = diakses + 1 WHERE id = $id");

        $buku = $this->db->table('tbl_elibrary')->where('id', $id)->get()->getRow();
        return view('admin/library/baca', ['buku' => $buku]);
    }

    public function delete($id)
    {
        $buku = $this->db->table('tbl_elibrary')->where('id', $id)->get()->getRow();
        // Hapus file fisik
        if(file_exists('uploads/library/' . $buku->file_ebook)) unlink('uploads/library/' . $buku->file_ebook);
        
        $this->db->table('tbl_elibrary')->where('id', $id)->delete();
        return redirect()->to('admin/library')->with('success', 'Buku dihapus.');
    }
    public function counter($id)
    {
        $this->db->query("UPDATE tbl_elibrary SET diakses = diakses + 1 WHERE id = $id");
        return $this->response->setJSON(['status' => 'success']);
    }
}