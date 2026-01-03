<?php

namespace App\Controllers;

use App\Models\PengaturanModel; // Buat Model ini nanti
use App\Models\SiswaModel;      // Asumsi ada
use App\Models\GuruModel;       // Asumsi ada
use App\Models\SliderModel;     // Buat Model ini nanti
use App\Models\KegiatanModel;   // Asumsi ada
use App\Models\GalleryModel;    // Buat Model ini nanti

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Ambil Pengaturan Web (Konversi ke Array Asosiatif biar mudah dipanggil)
        // Hasilnya jadi: $setting['nama_sekolah'], $setting['alamat'], dst.
        $builder = $db->table('tbl_pengaturan');
        $query   = $builder->get()->getResultArray();
        $setting = [];
        foreach ($query as $row) {
            $setting[$row['kunci']] = $row['nilai'];
        }

        // 2. Hitung Statistik Real-time
        // Asumsi kamu punya tabel 'tbl_siswa' dengan status 'aktif'
        $jml_siswa = $db->table('tbl_siswa')->where('status', 'aktif')->countAllResults();
        $jml_guru  = $db->table('tbl_guru')->countAllResults();
        $jml_ekskul = $db->table('tbl_ekskul')->countAllResults(); 

        // 3. Ambil Data List (Slider, Berita, Gallery)
        $sliders  = $db->table('tbl_slider')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $kegiatan = $db->table('tbl_kegiatan')->orderBy('tanggal', 'DESC')->limit(3)->get()->getResultArray(); // Ambil 3 berita terbaru
        $gallery  = $db->table('tbl_gallery')->orderBy('id', 'DESC')->limit(8)->get()->getResultArray();

        $data = [
            'web'       => $setting,     // Data pengaturan
            'stats'     => [             // Data Statistik
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