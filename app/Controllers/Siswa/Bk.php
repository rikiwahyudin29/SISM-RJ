<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Bk extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

public function index()
{
    $id_user = session()->get('id_user');
    
    // 1. Ambil Data Siswa yang sedang login
    $siswa = $this->db->table('tbl_siswa')
        ->where('id_user', $id_user)
        ->get()->getRow();

    if (!$siswa) {
        return redirect()->to('siswa/dashboard')->with('error', 'Data profil tidak ditemukan.');
    }

    // 2. Hitung Total Pelanggaran & Poin Minus
    // Perbaikan: Gunakan alias yang spesifik (tbl_siswa_pelanggaran.id)
    $rekap = $this->db->table('tbl_siswa_pelanggaran')
        ->select('COUNT(tbl_siswa_pelanggaran.id) as total_kasus, IFNULL(SUM(p.poin), 0) as total_minus')
        ->join('tbl_master_pelanggaran p', 'p.id = tbl_siswa_pelanggaran.pelanggaran_id')
        ->where('siswa_id', $siswa->id)
        ->get()->getRow();

    // 3. Ambil List Detail Riwayat Pelanggaran Siswa
    $riwayat = $this->db->table('tbl_siswa_pelanggaran')
        ->select('tbl_siswa_pelanggaran.tanggal, tbl_siswa_pelanggaran.catatan, tbl_master_pelanggaran.nama_pelanggaran, tbl_master_pelanggaran.poin')
        ->join('tbl_master_pelanggaran', 'tbl_master_pelanggaran.id = tbl_siswa_pelanggaran.pelanggaran_id')
        ->where('siswa_id', $siswa->id)
        ->orderBy('tbl_siswa_pelanggaran.tanggal', 'DESC')
        ->get()->getResultArray();

    // 4. Ambil Ambang Batas SP
    $setSp = $this->db->table('tbl_set_sp')->get()->getRow();

    return view('siswa/bk/index', [
        'title'   => 'Poin Kedisiplinan Saya',
        'riwayat' => $riwayat,
        'rekap'   => $rekap,
        'setSp'   => $setSp,
        'saldo'   => 100 - $rekap->total_minus
    ]);
}
}