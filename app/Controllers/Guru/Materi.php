<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Materi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 1. HALAMAN DAFTAR MATERI
    public function index()
    {
        // Ambil ID User yang login
        $id_user = session()->get('id'); // Sesuaikan session user id bos
        
        // Cari ID Guru berdasarkan User Login
        $guru = $this->db->table('tbl_guru')->where('id_user', $id_user)->get()->getRow();
        if (!$guru) return redirect()->to('/')->with('error', 'Data Guru tidak ditemukan.');

        // Ambil Materi milik guru ini saja
        $materi = $this->db->table('tbl_materi')
            ->select('tbl_materi.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_materi.kelas_id')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_materi.mapel_id')
            ->where('tbl_materi.guru_id', $guru->id)
            ->orderBy('tbl_materi.created_at', 'DESC')
            ->get()->getResultArray();

        // Ambil Data Kelas & Mapel untuk Dropdown di Modal Tambah
        $kelas = $this->db->table('tbl_kelas')->get()->getResultArray();
        $mapel = $this->db->table('tbl_mapel')->get()->getResultArray();

        $data = [
            'title'  => 'Materi Pembelajaran',
            'materi' => $materi,
            'kelas'  => $kelas,
            'mapel'  => $mapel,
            'guru'   => $guru
        ];

        return view('guru/materi/index', $data);
    }

    // 2. PROSES SIMPAN UPLOAD
    public function save()
    {
        // Validasi
        if (!$this->validate([
            'judul'    => 'required',
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'file_materi' => [
                'rules' => 'max_size[file_materi,10240]|ext_in[file_materi,pdf,doc,docx,ppt,pptx,jpg,png]',
                'errors' => [
                    'max_size' => 'Ukuran file terlalu besar (Maks 10MB)',
                    'ext_in'   => 'Format file tidak diizinkan'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi Gagal. Cek ukuran atau format file.');
        }

        $id_guru = $this->request->getPost('guru_id');
        $file = $this->request->getFile('file_materi');
        $nama_file = null;

        // Handle File Upload
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $nama_file = $file->getRandomName(); // Nama acak biar aman
            $file->move('uploads/materi', $nama_file);
        }

        $data = [
            'guru_id'      => $id_guru,
            'mapel_id'     => $this->request->getPost('mapel_id'),
            'kelas_id'     => $this->request->getPost('kelas_id'),
            'judul'        => $this->request->getPost('judul'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'link_youtube' => $this->request->getPost('link_youtube'),
            'file_materi'  => $nama_file,
            'status'       => 1
        ];

        $this->db->table('tbl_materi')->insert($data);
        return redirect()->back()->with('success', 'Materi berhasil diupload!');
    }

    // 3. HAPUS MATERI
    public function delete($id)
    {
        // Cek file lama untuk dihapus dari folder
        $materi = $this->db->table('tbl_materi')->where('id', $id)->get()->getRow();
        
        if ($materi) {
            if ($materi->file_materi && file_exists('uploads/materi/' . $materi->file_materi)) {
                unlink('uploads/materi/' . $materi->file_materi);
            }
            $this->db->table('tbl_materi')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Materi dihapus.');
        }
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
}