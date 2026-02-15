<?php

namespace App\Controllers\Bk;

use App\Controllers\BaseController;

class Pelanggaran extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil ID User Login (Role BK)
        $id_user = session()->get('id_user');

        // 1. Ambil Riwayat Pelanggaran
        $riwayat = $this->db->table('tbl_siswa_pelanggaran')
            ->select('tbl_siswa_pelanggaran.*, tbl_siswa.nama_lengkap as nama_siswa, tbl_siswa.kelas_id, 
                      tbl_master_pelanggaran.nama_pelanggaran, tbl_master_pelanggaran.poin, tbl_master_pelanggaran.kategori,
                      tbl_kelas.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_siswa_pelanggaran.siswa_id')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
            ->join('tbl_master_pelanggaran', 'tbl_master_pelanggaran.id = tbl_siswa_pelanggaran.pelanggaran_id')
            ->orderBy('tbl_siswa_pelanggaran.tanggal', 'DESC')
            ->get()->getResultArray();

        // 2. Data Statistik (Opsional, biar dashboard BK keren)
        $total_kasus = count($riwayat);
        $poin_tertinggi = $this->db->query("SELECT s.nama_lengkap, SUM(m.poin) as total_poin 
                                            FROM tbl_siswa_pelanggaran p 
                                            JOIN tbl_siswa s ON s.id = p.siswa_id 
                                            JOIN tbl_master_pelanggaran m ON m.id = p.pelanggaran_id 
                                            GROUP BY p.siswa_id 
                                            ORDER BY total_poin DESC LIMIT 1")->getRow();

        // 3. Data Pendukung untuk Modal
        $siswa = $this->db->table('tbl_siswa')
            ->select('tbl_siswa.id, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $jenis = $this->db->table('tbl_master_pelanggaran')->get()->getResultArray();

        return view('bk/pelanggaran/index', [
            'title'   => 'Buku Kasus Siswa',
            'riwayat' => $riwayat,
            'siswa'   => $siswa,
            'jenis'   => $jenis,
            'stats'   => [
                'total' => $total_kasus,
                'top_siswa' => $poin_tertinggi ? $poin_tertinggi->nama_lengkap : '-'
            ]
        ]);
    }

    public function save()
    {
        $data = [
            'siswa_id'       => $this->request->getPost('siswa_id'),
            'pelanggaran_id' => $this->request->getPost('pelanggaran_id'),
            'pelapor_id'     => session()->get('id_user'), // ID Akun BK
            'tanggal'        => date('Y-m-d H:i:s'),
            'catatan'        => $this->request->getPost('catatan'),
            'status'         => 'Baru'
        ];

        $this->db->table('tbl_siswa_pelanggaran')->insert($data);
        return redirect()->to('bk/pelanggaran')->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function delete($id)
    {
        $this->db->table('tbl_siswa_pelanggaran')->where('id', $id)->delete();
        return redirect()->to('bk/pelanggaran')->with('success', 'Data dihapus.');
    }
}