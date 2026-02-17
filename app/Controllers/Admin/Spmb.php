<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Spmb extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // --- DASHBOARD PREMIUM ---
    public function dashboard()
    {
        $builder = $this->db->table('tbl_pendaftar');
        
        $data = [
            'title' => 'Dashboard Premium PPDB',
            'stats' => [
                'total'    => $builder->countAllResults(false), 
                'pending'  => $builder->where('status_pendaftaran', 'Pending')->countAllResults(false),
                'diterima' => $builder->where('status_pendaftaran', 'Diterima')->countAllResults(false),
                'ditolak'  => $builder->where('status_pendaftaran', 'Ditolak')->countAllResults(false),
            ],
            // Grafik Pendaftaran per Jurusan
            'jurusan' => $builder->select('jurusan_minat, COUNT(*) as jumlah')->groupBy('jurusan_minat')->get()->getResultArray(),
            'terbaru' => $builder->orderBy('id', 'DESC')->limit(10)->get()->getResultArray()
        ];

        return view('admin/spmb/dashboard', $data);
    }

    // --- DATA PENDAFTAR & LAPORAN ---
    public function pendaftar()
    {
        // Filter Laporan Sederhana
        $status = $this->request->getGet('status');
        $jurusan = $this->request->getGet('jurusan');
        
        $builder = $this->db->table('tbl_pendaftar');
        if($status) $builder->where('status_pendaftaran', $status);
        if($jurusan) $builder->where('jurusan_minat', $jurusan);

        // Cek apakah tabel jurusan ada, jika tidak kosongkan array agar tidak error
        $jurusan_list = [];
        if ($this->db->tableExists('tbl_jurusan')) {
            $jurusan_list = $this->db->table('tbl_jurusan')->get()->getResultArray();
        }

        $data = [
            'title' => 'Data Pendaftar & Laporan',
            'pendaftar' => $builder->orderBy('tgl_daftar', 'DESC')->get()->getResultArray(),
            'jurusan_list' => $jurusan_list
        ];
        return view('admin/spmb/pendaftar_list', $data);
    }

    // --- DETAIL & MIGRASI ---
    public function detail($id)
    {
        // PERBAIKAN 1: Ganti tbl_rombel jadi tbl_kelas
        $data = [
            'title' => 'Detail Lengkap Pendaftar',
            'siswa' => $this->db->table('tbl_pendaftar')->where('id', $id)->get()->getRowArray(),
            'rombel_list' => $this->db->table('tbl_kelas')->get()->getResultArray() 
        ];
        return view('admin/spmb/detail', $data);
    }

    // --- FITUR MIGRASI KE DATA SISWA (PREMIUM) ---
    public function migrasi_siswa()
    {
        $id_pendaftar = $this->request->getPost('id_pendaftar');
        
        // PERBAIKAN 2: Input dari form tetap id_rombel (biar gak ubah view), tapi kita mapping ke id_kelas
        $id_kelas     = $this->request->getPost('id_rombel'); 
        $nis_baru     = $this->request->getPost('nis_baru');

        $pendaftar = $this->db->table('tbl_pendaftar')->where('id', $id_pendaftar)->get()->getRowArray();

        if (!$pendaftar) return redirect()->back()->with('error', 'Data tidak ditemukan');

        // 1. Insert ke tbl_siswa
        $dataSiswa = [
            'nis' => $nis_baru,
            'nisn' => $pendaftar['nisn'],
            'nama_lengkap' => $pendaftar['nama_lengkap'],
            'jk' => $pendaftar['jk'],
            'tempat_lahir' => $pendaftar['tempat_lahir'],
            'tgl_lahir' => $pendaftar['tgl_lahir'],
            'alamat' => $pendaftar['alamat_jalan'], // Pastikan kolom ini ada di tbl_pendaftar hasil upgrade
            'nama_ayah' => $pendaftar['nama_ayah'],
            'nama_ibu' => $pendaftar['nama_ibu'],
            'no_hp' => $pendaftar['no_hp_siswa'],
            'foto' => $pendaftar['foto'],
            'status_siswa' => 'Aktif',
            'password' => password_hash($nis_baru, PASSWORD_DEFAULT), // Default password = NIS
            'role_id' => 11 // Role Siswa
        ];
        
        $this->db->trans_start();
        $this->db->table('tbl_siswa')->insert($dataSiswa);
        $id_siswa_baru = $this->db->insertID();

        // 2. Masukkan ke Kelas (PERBAIKAN: Gunakan tbl_anggota_kelas)
        // Cek dulu tabel mana yang ada: tbl_anggota_kelas atau tbl_anggota_rombel
        if ($this->db->tableExists('tbl_anggota_kelas')) {
            $this->db->table('tbl_anggota_kelas')->insert([
                'id_kelas' => $id_kelas,
                'id_siswa' => $id_siswa_baru,
                'tahun_ajaran' => date('Y') . '/' . (date('Y')+1)
            ]);
        } elseif ($this->db->tableExists('tbl_anggota_rombel')) {
             $this->db->table('tbl_anggota_rombel')->insert([
                'id_rombel' => $id_kelas,
                'id_siswa' => $id_siswa_baru,
                'tahun_ajaran' => date('Y') . '/' . (date('Y')+1)
            ]);
        }

        // 3. Update Status Pendaftar jadi Migrated
        $this->db->table('tbl_pendaftar')->where('id', $id_pendaftar)->update([
            'status_pendaftaran' => 'Diterima',
            'is_migrated' => 1
        ]);
        
        $this->db->trans_complete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal migrasi data. Cek struktur tabel anggota kelas.');
        }

        return redirect()->to('admin/siswa')->with('success', 'Siswa berhasil diterima dan masuk kelas!');
    }

    public function update_status()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $catatan = $this->request->getPost('catatan');

        $this->db->table('tbl_pendaftar')->where('id', $id)->update([
            'status_pendaftaran' => $status,
            'catatan_admin' => $catatan
        ]);
        
        return redirect()->to('admin/spmb/detail/'.$id)->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->db->table('tbl_pendaftar')->where('id', $id)->delete();
        return redirect()->to('admin/spmb/pendaftar')->with('success', 'Data pendaftar dihapus.');
    }

    // --- CETAK FORMULIR FISIK ---
    public function cetak_formulir($id)
    {
        $data = [
            'web'       => $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray(),
            'pendaftar' => $this->db->table('tbl_pendaftar')->where('id', $id)->get()->getRowArray()
        ];
        // Menggunakan View yang sama
        return view('spmb/print_formulir', $data);
    }
}