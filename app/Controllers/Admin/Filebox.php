<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Filebox extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $role = session()->get('role');
        $id_user = session()->get('id_user');
        
        // Ambil semua data file + nama guru
        $builder = $this->db->table('tbl_filebox')
            ->select('tbl_filebox.*, tbl_guru.nama_lengkap, tbl_guru.nip')
            ->join('tbl_guru', 'tbl_guru.id = tbl_filebox.guru_id', 'left') // Gunakan Left Join agar file admin (dummy) tetap muncul
            ->orderBy('created_at', 'DESC');

        // Jika Guru, hanya tampilkan file miliknya
        if ($role == 'guru') {
            $guru = $this->db->table('tbl_guru')->where('id_user', $id_user)->get()->getRow();
            if($guru) {
                $builder->where('guru_id', $guru->id);
            }
        }

        $data = [
            'title' => 'E-Filebox Guru',
            'files' => $builder->get()->getResultArray(),
            'role'  => $role // Penting dikirim ke view
        ];

        return view('admin/filebox/index', $data);
    }

    public function upload()
    {
        if (!$this->validate([
            'berkas' => [
                'rules' => 'uploaded[berkas]|max_size[berkas,5120]|ext_in[berkas,pdf,doc,docx]',
                'errors' => [
                    'uploaded' => 'Pilih file terlebih dahulu',
                    'max_size' => 'Ukuran file terlalu besar (Max 5MB)',
                    'ext_in' => 'Hanya boleh upload file PDF atau Word'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $file = $this->request->getFile('berkas');
        $namaRandom = $file->getRandomName();
        $file->move('uploads/berkas_guru', $namaRandom);

        // Ambil ID Guru dari Session User
        $guru = $this->db->table('tbl_guru')->where('id_user', session()->get('id_user'))->get()->getRow();
        
        // Perbaikan: Jika user bukan guru (misal Admin yg ngetes), pakai ID 0
        $guru_id = $guru ? $guru->id : 0;

        $this->db->table('tbl_filebox')->insert([
            'guru_id'   => $guru_id,
            'judul'     => $this->request->getPost('judul'),
            'kategori'  => $this->request->getPost('kategori'),
            'nama_file' => $namaRandom,
            'status'    => 'Pending'
        ]);

        return redirect()->to('admin/filebox')->with('success', 'Dokumen berhasil diupload.');
    }

    public function nilai()
    {
        $this->db->table('tbl_filebox')->where('id', $this->request->getPost('id'))->update([
            'status' => $this->request->getPost('status'),
            'catatan_kepsek' => $this->request->getPost('catatan')
        ]);
        return redirect()->to('admin/filebox')->with('success', 'Penilaian disimpan.');
    }
    
    public function download($nama_file)
    {
        return $this->response->download('uploads/berkas_guru/' . $nama_file, null);
    }
    
    public function hapus($id)
    {
        $file = $this->db->table('tbl_filebox')->where('id', $id)->get()->getRow();
        if($file && file_exists('uploads/berkas_guru/' . $file->nama_file)) {
            unlink('uploads/berkas_guru/' . $file->nama_file);
        }
        $this->db->table('tbl_filebox')->where('id', $id)->delete();
        return redirect()->to('admin/filebox')->with('success', 'File dihapus.');
    }
    public function revisi()
{
    // 1. Validasi File
    if (!$this->validate([
        'berkas' => [
            'rules' => 'uploaded[berkas]|max_size[berkas,5120]|ext_in[berkas,pdf,doc,docx]',
            'errors' => [
                'uploaded' => 'Pilih file revisi terlebih dahulu',
                'max_size' => 'Ukuran file terlalu besar (Max 5MB)',
                'ext_in' => 'Hanya boleh upload file PDF atau Word'
            ]
        ]
    ])) {
        return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
    }

    $id = $this->request->getPost('id');
    $file = $this->request->getFile('berkas');
    $namaRandom = $file->getRandomName();

    // 2. Hapus File Lama (Opsional, biar server gak penuh)
    $oldFile = $this->db->table('tbl_filebox')->where('id', $id)->get()->getRow();
    if ($oldFile && file_exists('uploads/berkas_guru/' . $oldFile->nama_file)) {
        unlink('uploads/berkas_guru/' . $oldFile->nama_file);
    }

    // 3. Upload File Baru
    $file->move('uploads/berkas_guru', $namaRandom);

    // 4. Update Database: Status jadi 'Pending' lagi
    $this->db->table('tbl_filebox')->where('id', $id)->update([
        'nama_file' => $namaRandom,
        'status'    => 'Pending', // Reset status agar diperiksa ulang
        'created_at'=> date('Y-m-d H:i:s') // Update waktu upload
    ]);

    return redirect()->to('admin/filebox')->with('success', 'Dokumen perbaikan berhasil diupload. Menunggu pemeriksaan ulang.');
}
}