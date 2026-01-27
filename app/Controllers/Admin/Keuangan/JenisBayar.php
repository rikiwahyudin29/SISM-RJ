<?php

namespace App\Controllers\Admin\Keuangan;

use App\Controllers\BaseController;

class JenisBayar extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $jenis = $this->db->table('tbl_jenis_bayar')
            ->select('tbl_jenis_bayar.*, tbl_pos_bayar.nama_pos, tbl_tahun_ajaran.tahun_ajaran')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->join('tbl_tahun_ajaran', 'tbl_tahun_ajaran.id = tbl_jenis_bayar.id_tahun_ajaran')
            ->orderBy('tbl_jenis_bayar.id', 'DESC')
            ->get()->getResultArray();

        $pos = $this->db->table('tbl_pos_bayar')->orderBy('nama_pos', 'ASC')->get()->getResultArray();
        $tahun = $this->db->table('tbl_tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        
        // [TAMBAHAN] Ambil Data Kelas untuk Filter Generate
        $kelas = $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray();

        $data = [
            'title' => 'Setting Jenis Pembayaran',
            'jenis' => $jenis,
            'pos'   => $pos,
            'tahun' => $tahun,
            'kelas' => $kelas // Kirim ke view
        ];

        return view('admin/keuangan/jenis/index', $data);
    }

    public function simpan()
    {
        $id = $this->request->getPost('id');
        
        // Hapus titik ribuan sebelum simpan ke DB
        $nominal = str_replace('.', '', $this->request->getPost('nominal_default'));

        $data = [
            'id_pos_bayar'    => $this->request->getPost('id_pos_bayar'),
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
            'tipe_bayar'      => $this->request->getPost('tipe_bayar'),
            'nominal_default' => $nominal
        ];

        if (empty($id)) {
            // Cek Duplikasi (Pos yang sama di Tahun yang sama tidak boleh double)
            $cek = $this->db->table('tbl_jenis_bayar')
                ->where('id_pos_bayar', $data['id_pos_bayar'])
                ->where('id_tahun_ajaran', $data['id_tahun_ajaran'])
                ->countAllResults();

            if($cek > 0) {
                return redirect()->back()->with('error', 'Gagal! Jenis pembayaran ini sudah ada di tahun ajaran tersebut.');
            }

            $this->db->table('tbl_jenis_bayar')->insert($data);
            $msg = 'Setting pembayaran berhasil ditambahkan.';
        } else {
            $this->db->table('tbl_jenis_bayar')->where('id', $id)->update($data);
            $msg = 'Setting pembayaran berhasil diperbarui.';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function hapus()
    {
        $id = $this->request->getPost('id');
        // Nanti di sini bisa ditambah cek relasi ke tabel transaksi siswa
        
        $this->db->table('tbl_jenis_bayar')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}