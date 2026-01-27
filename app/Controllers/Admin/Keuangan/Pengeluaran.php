<?php

namespace App\Controllers\Admin\Keuangan;

use App\Controllers\BaseController;

class Pengeluaran extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil Data Master untuk Dropdown
        $divisi = $this->db->table('tbl_divisi')->orderBy('nama_divisi', 'ASC')->get()->getResultArray();
        $jenis  = $this->db->table('tbl_jenis_pengeluaran')->orderBy('nama_jenis', 'ASC')->get()->getResultArray();

        // Ambil Data Pengeluaran Bulan Ini (Default view)
        $pengeluaran = $this->db->table('tbl_pengeluaran')
            ->select('tbl_pengeluaran.*, tbl_divisi.nama_divisi, tbl_jenis_pengeluaran.nama_jenis')
            ->join('tbl_divisi', 'tbl_divisi.id = tbl_pengeluaran.id_divisi')
            ->join('tbl_jenis_pengeluaran', 'tbl_jenis_pengeluaran.id = tbl_pengeluaran.id_jenis')
            ->orderBy('tbl_pengeluaran.tanggal', 'DESC')
            ->orderBy('tbl_pengeluaran.created_at', 'DESC')
            ->limit(50) // Tampilkan 50 terakhir biar ringan
            ->get()->getResultArray();

        return view('admin/keuangan/pengeluaran/index', [
            'title' => 'Pengeluaran Sekolah',
            'divisi' => $divisi,
            'jenis' => $jenis,
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function simpan()
    {
        $nominal = str_replace('.', '', $this->request->getPost('nominal'));
        
        $data = [
            'id_divisi'         => $this->request->getPost('id_divisi'),
            'id_jenis'          => $this->request->getPost('id_jenis'),
            'judul_pengeluaran' => $this->request->getPost('judul'),
            'nominal'           => $nominal,
            'tanggal'           => $this->request->getPost('tanggal'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'petugas_id'        => session()->get('id') ?? 1
        ];

        $this->db->table('tbl_pengeluaran')->insert($data);
        return redirect()->back()->with('success', 'Data pengeluaran berhasil dicatat.');
    }

    public function hapus()
    {
        $id = $this->request->getPost('id');
        $this->db->table('tbl_pengeluaran')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    // --- QUICK ADD MASTER DATA ---
    public function simpan_divisi() {
        $this->db->table('tbl_divisi')->insert(['nama_divisi' => $this->request->getPost('nama')]);
        return redirect()->back()->with('success', 'Divisi baru ditambahkan.');
    }

    public function simpan_jenis() {
        $this->db->table('tbl_jenis_pengeluaran')->insert(['nama_jenis' => $this->request->getPost('nama')]);
        return redirect()->back()->with('success', 'Jenis pengeluaran baru ditambahkan.');
    }
}