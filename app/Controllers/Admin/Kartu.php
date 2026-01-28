<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Kartu extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil Data Kelas untuk Dropdown
        $kelas = $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray();
        
        return view('admin/kartu/index', [
            'title' => 'Cetak Kartu Pelajar',
            'kelas' => $kelas
        ]);
    }

    public function cetak()
    {
        $id_kelas = $this->request->getGet('id_kelas');
        
        if (!$id_kelas) return redirect()->to('admin/kartu');

        // REVISI BAGIAN INI: Menggunakan 'kelas_id'
        $builder = $this->db->table('tbl_siswa');
        $builder->select('tbl_siswa.*, tbl_kelas.nama_kelas');
        
        // Cek nama kolom secara dinamis biar aman
        if ($this->db->fieldExists('kelas_id', 'tbl_siswa')) {
            $builder->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id');
            $builder->where('tbl_siswa.kelas_id', $id_kelas);
        } else {
            $builder->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.id_kelas');
            $builder->where('tbl_siswa.id_kelas', $id_kelas);
        }

        $siswa = $builder->orderBy('tbl_siswa.nama_lengkap', 'ASC')
                         ->get()->getResultArray();

        $data_kelas = $this->db->table('tbl_kelas')->where('id', $id_kelas)->get()->getRow();

        return view('admin/kartu/cetak', [
            'siswa' => $siswa,
            'kelas' => $data_kelas,
            'sekolah' => [
                'nama'   => 'SMK DIGITAL INDONESIA', // Ganti Nama Sekolah Bos
                'alamat' => 'Jl. Teknologi No. 1, Jakarta',
                'logo'   => base_url('assets/img/logo.png') // Pastikan logo ada
            ]
        ]);
    }
    public function registrasi()
    {
        $keyword = $this->request->getGet('q');
        
        $builder = $this->db->table('tbl_siswa');
        $builder->select('tbl_siswa.id, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_siswa.rfid_uid, tbl_kelas.nama_kelas');
        
        // Join dinamis (antisipasi nama kolom kelas_id)
        if ($this->db->fieldExists('kelas_id', 'tbl_siswa')) {
            $builder->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id');
        } else {
            $builder->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.id_kelas');
        }

        if ($keyword) {
            $builder->like('nama_lengkap', $keyword)->orLike('nis', $keyword);
        }

        $siswa = $builder->orderBy('tbl_kelas.nama_kelas', 'ASC')
                         ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
                         ->limit(50) // Batasi biar gak berat
                         ->get()->getResultArray();

        return view('admin/kartu/registrasi', [
            'title' => 'Registrasi Kartu RFID/QR',
            'siswa' => $siswa,
            'keyword' => $keyword
        ]);
    }

    // 2. PROSES SIMPAN UID VIA AJAX
    public function simpan_uid()
    {
        $id_siswa = $this->request->getPost('id_siswa');
        $uid      = $this->request->getPost('uid'); // Kode RFID/QR

        if (!$id_siswa || !$uid) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        // Cek apakah UID sudah dipakai orang lain?
        $cek = $this->db->table('tbl_siswa')->where('rfid_uid', $uid)->where('id !=', $id_siswa)->countAllResults();
        if ($cek > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kartu ini sudah dipakai siswa lain!']);
        }

        // Update Data
        $this->db->table('tbl_siswa')->where('id', $id_siswa)->update([
            'rfid_uid' => $uid,
            'qr_code'  => $uid // Kita samakan saja isinya biar fleksibel
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Kartu berhasil didaftarkan!']);
    }
}