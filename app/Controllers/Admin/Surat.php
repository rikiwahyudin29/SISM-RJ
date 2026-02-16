<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Surat extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'E-Arsip & Surat Keluar',
            'surat' => $this->db->table('tbl_surat_keluar')
                ->select('tbl_surat_keluar.*, tbl_siswa.nama_lengkap as nama_siswa')
                ->join('tbl_siswa', 'tbl_siswa.id = tbl_surat_keluar.siswa_id', 'left')
                ->orderBy('id', 'DESC')
                ->get()->getResultArray(),
            'templates' => $this->db->table('tbl_surat_template')->get()->getResultArray(),
            'siswa' => $this->db->table('tbl_siswa')
                        ->select('tbl_siswa.*, tbl_kelas.nama_kelas')
                        ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
                        ->get()->getResultArray(),
        ];
        return view('admin/surat/index', $data);
    }

    // --- PROSES GENERATE SURAT OTOMATIS ---
    public function generate()
    {
        $templateId = $this->request->getPost('template_id');
        $siswaId    = $this->request->getPost('siswa_id');
        
        // 1. Ambil Data Template
        $template = $this->db->table('tbl_surat_template')->where('id', $templateId)->get()->getRowArray();
        
        // 2. Ambil Data Siswa
        $siswa = $this->db->table('tbl_siswa')
                ->select('tbl_siswa.*, tbl_kelas.nama_kelas')
                ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
                ->where('tbl_siswa.id', $siswaId)
                ->get()->getRowArray();

        // 3. Generate Nomor Surat Otomatis
        // Hitung jumlah surat bulan ini untuk penomoran
        $bulanIni = date('Y-m');
        $count = $this->db->table('tbl_surat_keluar')->like('tgl_surat', $bulanIni)->countAllResults();
        $noUrut = str_pad($count + 1, 3, '0', STR_PAD_LEFT); // Jadinya 001, 002, dst
        
        // Replace format nomor
        $nomorSurat = str_replace(
            ['{NO}', '{THN}', '{BLN}'], 
            [$noUrut, date('Y'), date('m')], 
            $template['format_nomor']
        );

        // 4. Replace Isi Surat dengan Data Siswa
        $isiFinal = str_replace(
            ['{NAMA}', '{NIS}', '{KELAS}', '{ALAMAT}', '{TAHUN_AJARAN}', '{HARI_INI}'], 
            [
                $siswa['nama_lengkap'], 
                $siswa['nis'], 
                $siswa['nama_kelas'], 
                $siswa['alamat'] ?? 'Alamat Siswa', 
                date('Y') . '/' . (date('Y')+1),
                date('d F Y')
            ], 
            $template['isi_html']
        );

        // 5. Simpan ke Database
        $token = md5(uniqid(rand(), true));
        $this->db->table('tbl_surat_keluar')->insert([
            'template_id' => $templateId,
            'no_surat'    => $nomorSurat,
            'siswa_id'    => $siswaId,
            'perihal'     => $template['nama_template'],
            'isi_final'   => $isiFinal, // Simpan HTML yang sudah matang
            'tgl_surat'   => date('Y-m-d'),
            'ttd_oleh'    => 1, // ID User Kepala Sekolah (Default)
            'token_validasi' => $token,
            'status'      => 'Disetujui'
        ]);

        return redirect()->to('admin/surat')->with('success', 'Surat berhasil dibuat dengan Nomor: ' . $nomorSurat);
    }

    public function cetak($id)
    {
        $surat = $this->db->table('tbl_surat_keluar')->where('id', $id)->get()->getRowArray();
        $sekolah = $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();
        
        // Generate QR
        $qr_content = base_url('verifikasi/' . $surat['token_validasi']);
        
        $data = [
            'surat' => $surat,
            'sekolah' => $sekolah,
            'qr_link' => "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qr_content)
        ];

        return view('admin/surat/cetak', $data);
    }

    public function delete($id)
    {
        $this->db->table('tbl_surat_keluar')->where('id', $id)->delete();
        return redirect()->to('admin/surat')->with('success', 'Arsip dihapus.');
    }

    // --- HALAMAN PUBLIK (SCAN QR CODE) ---
    public function verifikasi($token)
    {
        // 1. Cari surat & JOIN ke Siswa untuk dapat Nama Siswa
        $surat = $this->db->table('tbl_surat_keluar')
            ->select('tbl_surat_keluar.*, s.nama_lengkap as nama_siswa, s.nis, s.kelas_id, k.nama_kelas')
            ->join('tbl_siswa s', 's.id = tbl_surat_keluar.siswa_id', 'left')
            ->join('tbl_kelas k', 'k.id = s.kelas_id', 'left')
            ->where('token_validasi', $token)
            ->get()->getRow();

        if ($surat) {
            // 2. Ambil Data Sekolah (Kepsek)
            $sekolah = $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRow();
            
            // 3. Inject Data Kepsek
            $surat->nama_kepsek = $sekolah->nama_kepsek; 
            $surat->nip         = $sekolah->nip_kepsek;

            return view('verifikasi_valid', ['surat' => $surat]);
        } else {
            return view('verifikasi_invalid');
        }
    }
    
    // TAMBAHAN: Fungsi Download Publik (Tanpa Login)
    public function download_public($token)
    {
        // ... (Logic sama dengan cetak, tapi cari by token) ...
        $surat = $this->db->table('tbl_surat_keluar')
             ->where('token_validasi', $token)->get()->getRow();
             
        if($surat) {
             // Redirect ke fungsi cetak internal (tapi harus diubah dikit biar public)
             // Atau render view cetak langsung di sini
             return $this->cetak($surat->id); 
        }
    }
    public function cetak_public($token)
    {
        // Logic sama persis dengan cetak($id), tapi where('token_validasi', $token)
        $surat = $this->db->table('tbl_surat_keluar')
            ->select('tbl_surat_keluar.*, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_siswa.kelas_id, k.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_surat_keluar.siswa_id', 'left')
            ->join('tbl_kelas k', 'k.id = tbl_siswa.kelas_id', 'left')
            ->where('token_validasi', $token) // CARI PAKAI TOKEN
            ->get()->getRowArray();

        if(!$surat) return "Dokumen tidak ditemukan.";

        $sekolah = $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();
        $qr_content = base_url('verifikasi/' . $surat['token_validasi']);
        
        $data = [
            'surat' => $surat,
            'sekolah' => $sekolah,
            'qr_link' => "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qr_content)
        ];

        return view('admin/surat/cetak', $data);
    }
}