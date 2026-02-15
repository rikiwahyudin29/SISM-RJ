<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Catatan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $kelas_id = $this->request->getGet('kelas_id');
        
        $data = [
            'title' => 'Catatan Wali Kelas',
            'kelas' => $this->db->table('tbl_kelas')->get()->getResultArray(),
            'siswa' => []
        ];

        if ($kelas_id) {
            // Ambil Data Siswa + Join ke Catatan Wali (jika ada)
            $data['siswa'] = $this->db->table('tbl_siswa')
                ->select('tbl_siswa.id, tbl_siswa.nama_lengkap, tbl_siswa.nis, 
                          tbl_catatan_wali.sakit, tbl_catatan_wali.izin, tbl_catatan_wali.alpha, 
                          tbl_catatan_wali.catatan, tbl_catatan_wali.status_naik')
                ->join('tbl_catatan_wali', 'tbl_catatan_wali.siswa_id = tbl_siswa.id', 'left')
                ->where('tbl_siswa.kelas_id', $kelas_id)
                ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
                ->get()->getResultArray();
        }

        return view('guru/catatan/index', $data);
    }

    // Fitur Canggih: Tarik Data dari Modul Presensi
    public function generate_absensi($kelas_id)
    {
        // 1. Ambil Siswa di kelas ini
        $siswa = $this->db->table('tbl_siswa')->where('kelas_id', $kelas_id)->get()->getResultArray();

        $count = 0;
        foreach ($siswa as $s) {
            // 2. Hitung Presensi (Hanya Semester Ini - Opsional bisa tambah filter tanggal)
            $sakit = $this->db->table('tbl_presensi')->where(['user_id' => $s['id'], 'status_kehadiran' => 'Sakit'])->countAllResults();
            $izin  = $this->db->table('tbl_presensi')->where(['user_id' => $s['id'], 'status_kehadiran' => 'Izin'])->countAllResults();
            $alpha = $this->db->table('tbl_presensi')->where(['user_id' => $s['id'], 'status_kehadiran' => 'Alpha'])->countAllResults();

            // 3. Simpan/Update ke tbl_catatan_wali
            $cek = $this->db->table('tbl_catatan_wali')->where('siswa_id', $s['id'])->countAllResults();
            
            $data_update = [
                'kelas_id' => $kelas_id,
                'siswa_id' => $s['id'],
                'sakit'    => $sakit,
                'izin'     => $izin,
                'alpha'    => $alpha
            ];

            if ($cek > 0) {
                $this->db->table('tbl_catatan_wali')->where('siswa_id', $s['id'])->update($data_update);
            } else {
                $this->db->table('tbl_catatan_wali')->insert($data_update);
            }
            $count++;
        }

        return redirect()->to('guru/catatan?kelas_id='.$kelas_id)->with('success', "Berhasil menarik data absensi untuk $count siswa!");
    }

    public function save()
    {
        $kelas_id = $this->request->getPost('kelas_id');
        $catatan  = $this->request->getPost('catatan'); // Array
        $status   = $this->request->getPost('status');  // Array
        
        // Loop inputan
        foreach ($catatan as $siswa_id => $isi_catatan) {
            $status_naik = $status[$siswa_id];

            // Cek sudah ada data belum (karena mungkin sudah digenerate absensinya)
            $cek = $this->db->table('tbl_catatan_wali')->where('siswa_id', $siswa_id)->countAllResults();

            $data = [
                'catatan'     => $isi_catatan,
                'status_naik' => $status_naik,
                'kelas_id'    => $kelas_id,
                'siswa_id'    => $siswa_id
            ];

            if ($cek > 0) {
                $this->db->table('tbl_catatan_wali')->where('siswa_id', $siswa_id)->update($data);
            } else {
                $this->db->table('tbl_catatan_wali')->insert($data);
            }
        }

        return redirect()->back()->with('success', 'Catatan Wali Kelas berhasil disimpan!');
    }
}