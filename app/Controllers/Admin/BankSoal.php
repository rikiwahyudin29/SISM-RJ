<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BankSoal extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Ambil Data Bank Soal Lengkap
        $bank = $this->db->table('tbl_bank_soal')
            ->select('
                tbl_bank_soal.*,
                tbl_guru.nama_lengkap as nama_guru,
                tbl_mapel.nama_mapel,
                (SELECT COUNT(*) FROM tbl_soal WHERE tbl_soal.id_bank_soal = tbl_bank_soal.id) as total_soal_asli
            ')
            ->join('tbl_guru', 'tbl_guru.id = tbl_bank_soal.id_guru', 'left')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel', 'left')
            ->orderBy('tbl_bank_soal.id', 'DESC')
            ->get()->getResultArray();

        // 2. Hitung Statistik untuk Kartu Atas (Biar Gak Polos)
        $stats = [
            'total_bank' => count($bank),
            'guru_aktif' => count(array_unique(array_column($bank, 'id_guru'))),
            'soal_siap'  => 0,
            'soal_kurang'=> 0
        ];

        foreach($bank as $b) {
            $target = $b['jumlah_soal_pg'] + $b['jumlah_soal_esai'];
            if ($b['total_soal_asli'] >= $target && $target > 0) {
                $stats['soal_siap']++;
            } else {
                $stats['soal_kurang']++;
            }
        }

        return view('admin/bank_soal/index', [
            'title' => 'Monitoring Bank Soal',
            'bank'  => $bank,
            'stats' => $stats
        ]);
    }

    public function detail($id)
    {
        $bank = $this->db->table('tbl_bank_soal')
            ->select('tbl_bank_soal.*, tbl_guru.nama_lengkap, tbl_mapel.nama_mapel')
            ->join('tbl_guru', 'tbl_guru.id = tbl_bank_soal.id_guru')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->where('tbl_bank_soal.id', $id)
            ->get()->getRowArray();

        if(!$bank) return redirect()->to('admin/bank-soal');

        $soal = $this->db->table('tbl_soal')->where('id_bank_soal', $id)->get()->getResultArray();

        return view('admin/bank_soal/detail', [
            'title' => 'Detail Soal: ' . $bank['judul_ujian'],
            'bank' => $bank,
            'soal' => $soal
        ]);
    }

    // --- FITUR BARU: UPDATE TARGET SOAL ---
    public function updateTarget()
    {
        $id = $this->request->getPost('id');
        $pg = $this->request->getPost('jml_pg');
        $esai = $this->request->getPost('jml_esai');

        $this->db->table('tbl_bank_soal')->where('id', $id)->update([
            'jumlah_soal_pg'   => $pg,
            'jumlah_soal_esai' => $esai
        ]);

        return redirect()->to('admin/bank-soal')->with('success', 'Target soal berhasil diperbarui!');
    }
}