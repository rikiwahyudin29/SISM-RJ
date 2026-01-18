<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\SiswaModel;

class Rombel extends BaseController
{
    protected $kelasModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->siswaModel = new SiswaModel();
    }

    // Tampilan Utama (Daftar Kelas)
    public function index()
    {
        $data = [
            'title' => 'Manajemen Rombel & Kenaikan Kelas',
            'kelas' => $this->kelasModel->findAll()
        ];
        return view('admin/rombel/index', $data);
    }

    // Halaman Detail Anggota Kelas (Untuk memilih siswa yang akan dipindah)
    public function atur($id_kelas)
    {
        $kelasAsal = $this->kelasModel->find($id_kelas);
        
        // Ambil siswa yang AKTIF di kelas ini
        $siswa = $this->siswaModel->where('kelas_id', $id_kelas)
                                  ->where('status_siswa', 'Aktif')
                                  ->orderBy('nama_lengkap', 'ASC')
                                  ->findAll();

        $data = [
            'title'     => 'Atur Rombel / Kenaikan Kelas',
            'kelas_asal'=> $kelasAsal,
            'siswa'     => $siswa,
            'kelas_all' => $this->kelasModel->findAll() // Untuk dropdown tujuan
        ];

        return view('admin/rombel/atur', $data);
    }

    // PROSES PINDAH / NAIK KELAS / LULUS MASSAL
    public function proses_pindah()
    {
        $id_siswa_array = $this->request->getPost('siswa_id'); // Array ID siswa yang dicentang
        $aksi           = $this->request->getPost('aksi');     // 'pindah' atau 'lulus'
        $id_kelas_tujuan= $this->request->getPost('kelas_tujuan');

        if (empty($id_siswa_array)) {
            return redirect()->back()->with('error', 'Pilih minimal satu siswa!');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        if ($aksi == 'pindah') {
            // Validasi kelas tujuan
            if (empty($id_kelas_tujuan)) {
                return redirect()->back()->with('error', 'Pilih kelas tujuan untuk kenaikan kelas!');
            }

            // Update Kelas ID Massal
            $this->siswaModel->whereIn('id', $id_siswa_array)
                             ->set(['kelas_id' => $id_kelas_tujuan])
                             ->update();
            
            $msg = count($id_siswa_array) . ' Siswa berhasil dipindahkan/naik kelas!';

        } elseif ($aksi == 'lulus') {
            // Update Status Jadi Alumni (Lulus) & Kelas ID jadi 0 atau Null
            // Kita anggap status 'Lulus' di enum status_siswa
            $this->siswaModel->whereIn('id', $id_siswa_array)
                             ->set([
                                 'status_siswa' => 'Lulus',
                                 'kelas_id'     => 0 // 0 Artinya Alumni/Tidak punya kelas
                             ])
                             ->update();

            $msg = count($id_siswa_array) . ' Siswa berhasil diluluskan (Alumni)!';
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal memproses data.');
        }

        return redirect()->to(base_url('admin/rombel'))->with('success', $msg);
    }
    
    // Halaman Alumni
    public function alumni()
    {
         $alumni = $this->siswaModel->where('status_siswa', 'Lulus')->findAll();
         $data = [
            'title' => 'Data Alumni',
            'alumni'=> $alumni
         ];
         return view('admin/rombel/alumni', $data);
    }
}