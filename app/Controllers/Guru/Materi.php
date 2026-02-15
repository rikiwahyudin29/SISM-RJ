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

    public function index()
    {
        $id_guru = session()->get('id_guru'); // Pastikan session id_guru ada saat login
        
        // Kalau session id_guru belum diset (misal login pakai id_user), cari dulu:
        if (!$id_guru) {
            $user_id = session()->get('id_user');
            $guru = $this->db->table('tbl_guru')->where('user_id', $user_id)->get()->getRow(); // Sesuaikan kolom user_id/id_user
            $id_guru = $guru ? $guru->id : 0;
        }

        // 1. Ambil Data Materi yang pernah diupload guru ini
        // Kita join ke Tabel Kelas dan Mapel biar namanya muncul di View
       $materi = $this->db->table('tbl_materi')
        ->select('tbl_materi.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
        ->join('tbl_kelas', 'tbl_kelas.id = tbl_materi.kelas_id')
        ->join('tbl_mapel', 'tbl_mapel.id = tbl_materi.mapel_id')
        ->where('tbl_materi.guru_id', $id_guru)
        ->orderBy('tbl_materi.created_at', 'DESC')
        ->get()->getResultArray();

        // 2. Ambil Data Kelas & Mapel untuk Dropdown di Modal Tambah
        $kelas = $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray();
        $mapel = $this->db->table('tbl_mapel')->orderBy('nama_mapel', 'ASC')->get()->getResultArray();

        // Kirim data ke View index.php yang Bos upload
        return view('guru/materi/index', [
            'title'  => 'E-Learning: Materi Ajar',
            'guru'   => (object)['id' => $id_guru], // Biar form hidden guru_id tidak error
            'materi' => $materi,
            'kelas'  => $kelas,
            'mapel'  => $mapel
        ]);
    }

    // Fungsi Simpan Materi (Action form di index.php)
    public function save()
    {
        // 1. Handle File Upload
        $file = $this->request->getFile('file_materi');
        $namaFile = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/materi', $namaFile); // Pastikan folder public/uploads/materi ada
        }

        // 2. Simpan ke Database
        $data = [
            'guru_id'      => $this->request->getPost('guru_id'),
            'kelas_id'     => $this->request->getPost('kelas_id'),
            'mapel_id'     => $this->request->getPost('mapel_id'),
            'judul'        => $this->request->getPost('judul'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'link_youtube' => $this->request->getPost('link_youtube'), //
            'file_materi'  => $namaFile
        ];

        $this->db->table('tbl_materi')->insert($data);

        return redirect()->to('guru/materi')->with('success', 'Materi berhasil diupload!');
    }

    // Fungsi Hapus Materi
    public function delete($id)
    {
        // 1. Cek File Fisik dulu untuk dihapus
        $materi = $this->db->table('tbl_materi')->where('id', $id)->get()->getRow();
        
        if ($materi) {
            // Hapus file dari folder jika ada
            if ($materi->file_materi && file_exists('uploads/materi/' . $materi->file_materi)) {
                unlink('uploads/materi/' . $materi->file_materi);
            }

            // Hapus data dari database
            $this->db->table('tbl_materi')->where('id', $id)->delete();
        }

        return redirect()->to('guru/materi')->with('success', 'Materi berhasil dihapus.');
    }
    public function update()
{
    $id = $this->request->getPost('id');
    $file = $this->request->getFile('file_materi');
    
    $data = [
        'kelas_id'     => $this->request->getPost('kelas_id'),
        'mapel_id'     => $this->request->getPost('mapel_id'),
        'judul'        => $this->request->getPost('judul'),
        'deskripsi'    => $this->request->getPost('deskripsi'),
        'link_youtube' => $this->request->getPost('link_youtube'),
    ];

    if ($file && $file->isValid() && !$file->hasMoved()) {
        // Hapus file lama jika ada
        $old = $this->db->table('tbl_materi')->where('id', $id)->get()->getRow();
        if ($old->file_materi && file_exists('uploads/materi/' . $old->file_materi)) {
            unlink('uploads/materi/' . $old->file_materi);
        }

        $newName = $file->getRandomName();
        $file->move('uploads/materi', $newName);
        $data['file_materi'] = $newName;
    }

    $this->db->table('tbl_materi')->where('id', $id)->update($data);
    return redirect()->to('guru/materi')->with('success', 'Materi diperbarui!');
}
}