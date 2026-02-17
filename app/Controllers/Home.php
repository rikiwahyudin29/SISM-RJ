<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. DATA IDENTITAS SEKOLAH
        $sekolah = $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();

        // [TAMBAHAN] Ambil Data Profil Web (Agar fitur CMS Sambutan & Foto Kepsek tetap jalan)
        $webProfil = $this->db->table('tbl_web_profil')->where('id', 1)->get()->getRowArray();
        
        // Gabungkan: Data Sekolah + Data CMS Web
        // Jika $webProfil kosong (belum ada tabel), pakai $sekolah saja
        $dataWeb = $webProfil ? array_merge($sekolah, $webProfil) : $sekolah;

        // 2. DATA CMS (Slider, Berita, Galeri)
        $sliders = $this->db->table('tbl_slider')
            ->where('is_active', 1)
            ->orderBy('urutan', 'ASC')
            ->get()->getResultArray();

        $berita = $this->db->table('tbl_berita')
            ->where('is_published', 1)
            ->orderBy('created_at', 'DESC')
            ->limit(3) // Ambil 3 berita terbaru
            ->get()->getResultArray();

        $galeri = $this->db->table('tbl_galeri')
            ->orderBy('id', 'DESC')
            ->limit(8) // Ambil 8 foto terbaru
            ->get()->getResultArray();

        // 3. STATISTIK REAL-TIME (Sesuai Request Bos)
        $stats = [
            'guru'      => $this->db->table('tbl_guru')->countAllResults(),
            'siswa'     => $this->db->table('tbl_siswa')->where('status_siswa', 'Aktif')->countAllResults(),
            'pendaftar' => $this->db->table('tbl_pendaftar')->countAllResults(), // Data dari Modul PPDB
            // Hitung jurusan real jika tabel ada, kalau tidak default 5
            'jurusan'   => $this->db->tableExists('tbl_jurusan') ? $this->db->table('tbl_jurusan')->countAllResults() : 5 
        ];

        // Packing Data untuk View
        $data = [
            'web'     => $dataWeb, // Menggunakan data gabungan agar lengkap
            'sliders' => $sliders,
            'berita'  => $berita,
            'galeri'  => $galeri,
            'stats'   => $stats
        ];

        return view('welcome_message', $data);
    }
}