<?php

namespace App\Controllers;

use App\Models\PengaturanModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\SliderModel;
use App\Models\KegiatanModel;
use App\Models\GalleryModel;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Ambil Pengaturan Web
        $builder = $db->table('tbl_pengaturan');
        $query   = $builder->get()->getResultArray();
        $setting = [];
        foreach ($query as $row) {
            $setting[$row['kunci']] = $row['nilai'];
        }

        // 2. Hitung Statistik Real-time (BAGIAN INI YANG SAYA PERBAIKI)
        // Saya hapus ->where('status', 'aktif') agar tidak error
        $jml_siswa  = $db->table('tbl_siswa')->countAllResults(); 
        $jml_guru   = $db->table('tbl_guru')->countAllResults();
        // Pastikan tabel tbl_ekskul ada, kalau belum ada matikan baris di bawah ini
        $jml_ekskul = $db->table('tbl_ekskul')->countAllResults(); 

        // 3. Ambil Data List (Slider, Berita, Gallery)
        $sliders  = $db->table('tbl_slider')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $kegiatan = $db->table('tbl_kegiatan')->orderBy('tanggal', 'DESC')->limit(3)->get()->getResultArray(); 
        $gallery  = $db->table('tbl_gallery')->orderBy('id', 'DESC')->limit(8)->get()->getResultArray();

        $data = [
            'web'       => $setting,
            'stats'     => [
                'siswa'  => $jml_siswa,
                'guru'   => $jml_guru,
                'ekskul' => $jml_ekskul
            ],
            'sliders'   => $sliders,
            'kegiatan'  => $kegiatan,
            'gallery'   => $gallery
        ];

        return view('welcome_message', $data);
    }
}