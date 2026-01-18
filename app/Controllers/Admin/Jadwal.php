<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\MapelModel;
use App\Models\GuruModel;
use App\Models\TahunAjaranModel;
use App\Models\JamModel;
use App\Models\JurusanModel;

class Jadwal extends BaseController
{
    protected $jadwalModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $guruModel;
    protected $tahunModel;
    protected $jamModel;
    protected $jurusanModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalModel();
        $this->kelasModel  = new KelasModel();
        $this->mapelModel  = new MapelModel();
        $this->guruModel   = new GuruModel();
        $this->tahunModel  = new TahunAjaranModel();
        $this->jamModel    = new JamModel();
        $this->jurusanModel = new \App\Models\JurusanModel();
    }

    public function index()
    {
        $tahunAktif = $this->tahunModel->where('status', 'Aktif')->first();
        
        // --- LOGIC FILTER ---
        $filterKelas = $this->request->getGet('id_kelas');
        $filterGuru  = $this->request->getGet('id_guru');
        $filterJurusan = $this->request->getGet('id_jurusan');

        $filters = [
            'id_tahun_ajaran' => $tahunAktif['id'] ?? 0,
            'id_kelas'        => $filterKelas,
            'id_guru'         => $filterGuru,
            'id_jurusan'      => $filterJurusan
        ];

        // Ambil Data dengan Filter
        $jadwal = $this->jadwalModel->getJadwalFilter($filters);

        // Data Pendukung View
        $jamAll = $this->jamModel->getAllJam();
        $jamPelajaran = $this->jamModel->getJamPelajaran();

        $data = [
            'title'   => 'Jadwal Pelajaran',
            'jadwal'  => $jadwal,
            'kelas'   => $this->kelasModel->findAll(),
            'mapel'   => $this->mapelModel->findAll(),
            'guru'    => $this->guruModel->findAll(),
            'jurusan' => $this->jurusanModel->findAll(), // Untuk dropdown filter
            'tahun'   => $tahunAktif,
            'jam_master' => $jamPelajaran,
            'jam_all_json' => json_encode($jamAll),
            // Kirim balik nilai filter agar dropdown tidak reset
            'f_kelas' => $filterKelas,
            'f_guru'  => $filterGuru,
            'f_jurusan' => $filterJurusan
        ];

        return view('admin/jadwal/index', $data);
    }

    // --- FITUR CETAK / EXPORT PDF ---
    public function cetak()
    {
        $tahunAktif = $this->tahunModel->where('status', 'Aktif')->first();
        
        $filterKelas = $this->request->getGet('id_kelas');
        $filterGuru  = $this->request->getGet('id_guru');
        $filterJurusan = $this->request->getGet('id_jurusan');

        $filters = [
            'id_tahun_ajaran' => $tahunAktif['id'] ?? 0,
            'id_kelas'        => $filterKelas,
            'id_guru'         => $filterGuru,
            'id_jurusan'      => $filterJurusan
        ];

        $jadwal = $this->jadwalModel->getJadwalFilter($filters);

        // Tentukan Judul Laporan
        $judul = "Jadwal Pelajaran Sekolah";
        $subJudul = "Semua Data";
        
        if($filterKelas) {
            $k = $this->kelasModel->find($filterKelas);
            $judul = "Jadwal Kelas " . $k['nama_kelas'];
            $subJudul = "Wali Kelas: " . ($k['wali_kelas'] ?? '-');
        } elseif($filterGuru) {
            $g = $this->guruModel->find($filterGuru);
            $judul = "Jadwal Mengajar Guru";
            $subJudul = $g['nama_lengkap'];
        } elseif($filterJurusan) {
            $j = $this->jurusanModel->find($filterJurusan);
            $judul = "Jadwal Jurusan " . $j['nama_jurusan'];
        }

        $data = [
            'jadwal' => $jadwal,
            'tahun'  => $tahunAktif,
            'judul'  => $judul,
            'subJudul' => $subJudul
        ];

        return view('admin/jadwal/cetak', $data);
    }

    public function simpan()
    {
        $id = $this->request->getPost('id');
        $id_tahun  = $this->request->getPost('id_tahun_ajaran');
        $id_kelas  = $this->request->getPost('id_kelas');
        $id_mapel  = $this->request->getPost('id_mapel');
        $id_guru   = $this->request->getPost('id_guru');
        $hari      = $this->request->getPost('hari');
        $jam_mulai = $this->request->getPost('jam_mulai');
        $jam_selesai = $this->request->getPost('jam_selesai');

        // Validasi Tahun Ajaran (Wajib Ada)
        if (empty($id_tahun)) {
            return redirect()->back()->withInput()->with('error', 'Tahun Ajaran Aktif Belum Disetting! Silakan aktifkan di Master Data.');
        }

        if ($jam_mulai >= $jam_selesai) {
            return redirect()->back()->withInput()->with('error', 'Jam selesai harus lebih besar dari jam mulai!');
        }

        // Cek Bentrok
        $cek = $this->jadwalModel->cekBentrok($id_guru, $id_kelas, $hari, $jam_mulai, $jam_selesai, $id);
        
        if ($cek != "AMAN") {
            return redirect()->back()->withInput()->with('error', 'GAGAL: ' . $cek);
        }

        $data = [
            'id_tahun_ajaran' => $id_tahun,
            'id_kelas'        => $id_kelas,
            'id_mapel'        => $id_mapel,
            'id_guru'         => $id_guru,
            'hari'            => $hari,
            'jam_mulai'       => $jam_mulai,
            'jam_selesai'     => $jam_selesai
        ];

        if ($id) {
            $this->jadwalModel->update($id, $data);
            $msg = 'Jadwal berhasil diupdate!';
        } else {
            $this->jadwalModel->insert($data);
            $msg = 'Jadwal berhasil ditambahkan!';
        }

        return redirect()->to(base_url('admin/jadwal'))->with('success', $msg);
    }

    public function hapus($id)
    {
        $this->jadwalModel->delete($id);
        return redirect()->to(base_url('admin/jadwal'))->with('success', 'Jadwal berhasil dihapus.');
    }
    // --- FITUR REKAP JAM MENGAJAR (HITUNG GAJI) ---
    public function rekap()
    {
        $tahunAktif = $this->tahunModel->where('status', 'Aktif')->first();
        
        // Ambil semua jadwal + Data Guru + Mapel
        // Kita butuh raw data untuk dihitung di PHP
        $jadwal = $this->jadwalModel
                       ->select('tbl_jadwal.*, tbl_guru.nama_lengkap, tbl_guru.nip, tbl_mapel.nama_mapel, tbl_kelas.nama_kelas')
                       ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal.id_guru')
                       ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
                       ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal.id_kelas')
                       ->where('tbl_jadwal.id_tahun_ajaran', $tahunAktif['id'] ?? 0)
                       ->orderBy('tbl_guru.nama_lengkap', 'ASC')
                       ->findAll();

        // LOGIKA HITUNG DURASI
        $rekapGuru = [];

        foreach($jadwal as $j) {
            $guruId = $j['id_guru'];
            
            // Hitung selisih waktu (Menit)
            $start = strtotime($j['jam_mulai']);
            $end   = strtotime($j['jam_selesai']);
            $diffMenit = ($end - $start) / 60;

            if(!isset($rekapGuru[$guruId])) {
                $rekapGuru[$guruId] = [
                    'nama' => $j['nama_lengkap'],
                    'nip'  => $j['nip'],
                    'total_menit' => 0,
                    'total_jp_40' => 0, // Estimasi jika 1 JP = 40 menit
                    'total_jp_45' => 0, // Estimasi jika 1 JP = 45 menit
                    'kelas_ajar' => [] // List kelas yang diajar
                ];
            }

            $rekapGuru[$guruId]['total_menit'] += $diffMenit;
            // Masukkan kelas ke list (unik)
            if(!in_array($j['nama_kelas'], $rekapGuru[$guruId]['kelas_ajar'])) {
                $rekapGuru[$guruId]['kelas_ajar'][] = $j['nama_kelas'];
            }
        }

        // Konversi Menit ke JP
        foreach($rekapGuru as $key => $val) {
            $rekapGuru[$key]['total_jp_40'] = round($val['total_menit'] / 40, 1);
            $rekapGuru[$key]['total_jp_45'] = round($val['total_menit'] / 45, 1);
            $rekapGuru[$key]['jam_asli'] = floor($val['total_menit'] / 60) . ' Jam ' . ($val['total_menit'] % 60) . ' Menit';
        }

        $data = [
            'title' => 'Rekap Jam Mengajar',
            'rekap' => $rekapGuru,
            'tahun' => $tahunAktif
        ];

        return view('admin/jadwal/rekap', $data);
    }
    // --- CETAK PDF REKAP HONOR ---
    public function cetakRekap()
    {
        $tahunAktif = $this->tahunModel->where('status', 'Aktif')->first();
        
        // Ambil data mentah
        $jadwal = $this->jadwalModel
                       ->select('tbl_jadwal.*, tbl_guru.nama_lengkap, tbl_guru.nip, tbl_kelas.nama_kelas')
                       ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal.id_guru')
                       ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal.id_kelas')
                       ->where('tbl_jadwal.id_tahun_ajaran', $tahunAktif['id'] ?? 0)
                       ->orderBy('tbl_guru.nama_lengkap', 'ASC')
                       ->findAll();

        // LOGIKA HITUNG DURASI (Sama seperti function rekap)
        $rekapGuru = [];
        foreach($jadwal as $j) {
            $guruId = $j['id_guru'];
            $start = strtotime($j['jam_mulai']);
            $end   = strtotime($j['jam_selesai']);
            $diffMenit = ($end - $start) / 60;

            if(!isset($rekapGuru[$guruId])) {
                $rekapGuru[$guruId] = [
                    'nama' => $j['nama_lengkap'],
                    'nip'  => $j['nip'],
                    'total_menit' => 0,
                    'kelas_ajar' => []
                ];
            }
            $rekapGuru[$guruId]['total_menit'] += $diffMenit;
            if(!in_array($j['nama_kelas'], $rekapGuru[$guruId]['kelas_ajar'])) {
                $rekapGuru[$guruId]['kelas_ajar'][] = $j['nama_kelas'];
            }
        }

        // Hitung JP
        foreach($rekapGuru as $key => $val) {
            $rekapGuru[$key]['total_jp_40'] = round($val['total_menit'] / 40, 1);
            $rekapGuru[$key]['total_jp_45'] = round($val['total_menit'] / 45, 1);
            
            // Format jam menit: "20 Jam 30 Menit"
            $jam = floor($val['total_menit'] / 60);
            $menit = $val['total_menit'] % 60;
            $rekapGuru[$key]['jam_asli'] = $jam . " Jam " . ($menit > 0 ? $menit . " Menit" : "");
        }

        $data = [
            'rekap' => $rekapGuru,
            'tahun' => $tahunAktif
        ];

        return view('admin/jadwal/cetak_rekap', $data);
    }
}