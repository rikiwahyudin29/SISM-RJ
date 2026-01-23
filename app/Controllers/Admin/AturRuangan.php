<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AturRuangan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil Data Ruangan
        $ruangan = $this->db->table('tbl_ruangan')->get()->getResultArray();

        // LOOPING: Hitung jumlah siswa per ruangan
        foreach ($ruangan as &$r) {
            $r['jumlah_siswa'] = $this->db->table('tbl_ruang_peserta')
                                        ->where('id_ruangan', $r['id'])
                                        ->countAllResults();
        }

        $data = [
            'title' => 'Atur Peserta & Sesi',
            'ruangan' => $ruangan
        ];
        return view('admin/atur_ruangan/index', $data);
    }

    public function kelola($id_ruang)
    {
        // Ambil Data Ruang & Sesi
        $ruang = $this->db->table('tbl_ruangan')->where('id', $id_ruang)->get()->getRowArray();
        $sesi  = $this->db->table('tbl_sesi')->get()->getResultArray();
        
        $id_kelas_filter = $this->request->getGet('id_kelas');
        $id_sesi_pilih   = $this->request->getGet('id_sesi') ?? ($sesi[0]['id'] ?? 1); 

        // --- FILTER SISWA SUMBER (KIRI) ---
        $builder = $this->db->table('tbl_siswa')
            ->select('tbl_siswa.id, tbl_siswa.nama_lengkap as nama_siswa, tbl_kelas.nama_kelas') 
            // PERBAIKAN: Pakai kelas_id
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id') 
            ->join('tbl_ruang_peserta', 'tbl_ruang_peserta.id_siswa = tbl_siswa.id', 'left')
            ->where('tbl_ruang_peserta.id_siswa', NULL); 

        if ($id_kelas_filter) {
            // PERBAIKAN: Filter juga pakai kelas_id
            $builder->where('tbl_siswa.kelas_id', $id_kelas_filter);
        }
        $siswa_tersedia = $builder->orderBy('tbl_siswa.nama_lengkap', 'ASC')->get()->getResultArray();


        // --- LOAD SISWA SUDAH MASUK (KANAN) ---
        $siswa_terdaftar = $this->db->table('tbl_ruang_peserta')
            ->select('tbl_ruang_peserta.*, tbl_siswa.nama_lengkap as nama_siswa, tbl_kelas.nama_kelas, tbl_sesi.nama_sesi')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ruang_peserta.id_siswa')
            // PERBAIKAN: Join pakai kelas_id
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id') 
            ->join('tbl_sesi', 'tbl_sesi.id = tbl_ruang_peserta.id_sesi')
            ->where('tbl_ruang_peserta.id_ruangan', $id_ruang)
            ->where('tbl_ruang_peserta.id_sesi', $id_sesi_pilih)
            ->orderBy('tbl_ruang_peserta.no_komputer', 'ASC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Kelola: ' . $ruang['nama_ruangan'],
            'ruang' => $ruang,
            'sesi'  => $sesi,
            'kelas' => $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray(),
            'siswa_tersedia'  => $siswa_tersedia,
            'siswa_terdaftar' => $siswa_terdaftar,
            'sesi_aktif'      => $id_sesi_pilih,
            'kelas_aktif'     => $id_kelas_filter
        ];

        return view('admin/atur_ruangan/kelola', $data);
    }

    public function tambah()
    {
        $id_ruang = $this->request->getPost('id_ruangan');
        $id_sesi  = $this->request->getPost('id_sesi');
        $siswa_ids = $this->request->getPost('siswa_ids'); // Array ID Siswa yang dikirim

        if (!$siswa_ids) {
            return redirect()->back()->with('error', 'Pilih minimal satu siswa!');
        }

        // --- FILTER CERDAS: CEK DUPLIKAT ---
        // 1. Cek database: Siapa dari daftar ini yang SUDAH ada di tabel peserta?
        $sudahTerdaftar = $this->db->table('tbl_ruang_peserta')
                                   ->whereIn('id_siswa', $siswa_ids)
                                   ->get()->getResultArray();
        
        // Ambil ID siswa yang bandel (udah punya ruang tapi mau masuk lagi)
        $ids_terdaftar = array_column($sudahTerdaftar, 'id_siswa');
        
        // 2. Bersihkan Array: Hanya ambil siswa yang BELUM terdaftar
        // (Array Kiriman - Array Terdaftar = Array Bersih)
        $siswa_lolos = array_diff($siswa_ids, $ids_terdaftar);

        // Kalau ternyata semua siswa yang dipilih sudah punya ruangan
        if (empty($siswa_lolos)) {
            return redirect()->back()->with('error', 'Gagal! Semua siswa yang dipilih ternyata sudah memiliki ruangan.');
        }

        // --- PROSES INSERT (HANYA UNTUK SISWA YANG LOLOS) ---
        $batch = [];
        
        // Hitung peserta yg sudah ada di ruangan target (biar nomor PC lanjut rapi)
        $existingCount = $this->db->table('tbl_ruang_peserta')
                        ->where('id_ruangan', $id_ruang)
                        ->where('id_sesi', $id_sesi)
                        ->countAllResults();
        
        $no = $existingCount + 1;

        foreach ($siswa_lolos as $id_siswa) {
            $batch[] = [
                'id_ruangan' => $id_ruang,
                'id_sesi'    => $id_sesi,
                'id_siswa'   => $id_siswa,
                'no_komputer' => 'PC-' . str_pad($no++, 2, '0', STR_PAD_LEFT)
            ];
        }

        $this->db->table('tbl_ruang_peserta')->insertBatch($batch);
        
        // Buat pesan status yang informatif
        $pesan = count($siswa_lolos) . ' Siswa berhasil dimasukkan!';
        if (count($ids_terdaftar) > 0) {
            $pesan .= ' (' . count($ids_terdaftar) . ' siswa dilewati karena sudah punya ruangan)';
        }
        
        return redirect()->to("admin/aturruangan/kelola/$id_ruang?id_sesi=$id_sesi")
                         ->with('success', $pesan);
    }

    public function hapus($id_peserta, $id_ruang, $id_sesi)
    {
        $this->db->table('tbl_ruang_peserta')->delete(['id' => $id_peserta]);
        return redirect()->to("admin/aturruangan/kelola/$id_ruang?id_sesi=$id_sesi")
                         ->with('success', 'Siswa berhasil dikeluarkan dari ruangan.');
    }
}