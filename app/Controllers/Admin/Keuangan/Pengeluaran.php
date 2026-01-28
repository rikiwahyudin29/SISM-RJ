<?php

namespace App\Controllers\Admin\Keuangan;

use App\Controllers\BaseController;
use App\Libraries\LogService; // Panggil Library Mata-mata

class Pengeluaran extends BaseController
{
    protected $db;
    protected $log;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->log = new LogService(); // Init Logger
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
            ->limit(100) // Tampilkan 100 terakhir
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
        $judul   = $this->request->getPost('judul');
        
        $data = [
            'id_divisi'         => $this->request->getPost('id_divisi'),
            'id_jenis'          => $this->request->getPost('id_jenis'),
            'judul_pengeluaran' => $judul,
            'nominal'           => $nominal,
            'tanggal'           => $this->request->getPost('tanggal'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'petugas_id'        => session()->get('id_user') ?? 0 // Pastikan ID User tersimpan
        ];

        $this->db->table('tbl_pengeluaran')->insert($data);

        // --- REKAM LOG AKTIVITAS ---
        $rp = number_format($nominal, 0, ',', '.');
        $this->log->catat("Mencatat Pengeluaran: $judul sebesar Rp $rp");
        // ---------------------------

        return redirect()->back()->with('success', 'Data pengeluaran berhasil dicatat.');
    }

    public function hapus()
    {
        $id = $this->request->getPost('id');
        
        // Ambil data dulu sebelum dihapus (untuk Log)
        $dataLama = $this->db->table('tbl_pengeluaran')->where('id', $id)->get()->getRowArray();
        
        if ($dataLama) {
            $this->db->table('tbl_pengeluaran')->where('id', $id)->delete();

            // --- REKAM LOG HAPUS ---
            $judul = $dataLama['judul_pengeluaran'];
            $rp = number_format($dataLama['nominal'], 0, ',', '.');
            $this->log->catat("MENGHAPUS Pengeluaran: $judul (Rp $rp)");
            // -----------------------
            
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // --- QUICK ADD MASTER DATA ---
    public function simpan_divisi() {
        $nama = $this->request->getPost('nama');
        $this->db->table('tbl_divisi')->insert(['nama_divisi' => $nama]);
        
        $this->log->catat("Menambah Master Divisi: $nama"); // Log

        return redirect()->back()->with('success', 'Divisi baru ditambahkan.');
    }

    public function simpan_jenis() {
        $nama = $this->request->getPost('nama');
        $this->db->table('tbl_jenis_pengeluaran')->insert(['nama_jenis' => $nama]);
        
        $this->log->catat("Menambah Master Jenis Pengeluaran: $nama"); // Log

        return redirect()->back()->with('success', 'Jenis pengeluaran baru ditambahkan.');
    }
}