<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class MonitoringRuang extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // ... (Function index biarkan tetap seperti sebelumnya)
        $ruangan = $this->db->table('tbl_ruangan')->get()->getResultArray();
        foreach ($ruangan as &$r) {
            $r['jumlah_siswa'] = $this->db->table('tbl_ruang_peserta')->where('id_ruangan', $r['id'])->countAllResults();
        }
        $data = ['title' => 'Monitoring Ruangan', 'ruangan' => $ruangan];
        return view('admin/monitoring_ruang/index', $data);
    }

    // 1. TAMPILAN DETAIL (TABLE STYLE)
    public function lihat($id_ruangan)
    {
        $ruangInfo = $this->db->table('tbl_ruangan')->where('id', $id_ruangan)->get()->getRowArray();
        
        // Ambil data siswa + status ujian
        $siswa = $this->db->table('tbl_ruang_peserta')
            ->select('tbl_ruang_peserta.no_komputer, tbl_siswa.id as siswa_id, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas, 
                      tbl_nilai.id as nilai_id, tbl_nilai.status, tbl_nilai.nilai_sementara, tbl_nilai.jml_benar, tbl_nilai.jml_salah,
                      tbl_nilai.waktu_mulai, tbl_nilai.waktu_selesai, tbl_nilai.ip_address, tbl_nilai.is_locked,
                      tbl_bank_soal.judul_ujian')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ruang_peserta.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            ->join('tbl_nilai', 'tbl_nilai.id_siswa = tbl_siswa.id AND tbl_nilai.status != "NONAKTIF"', 'left')
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_nilai.id_jadwal', 'left')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal', 'left')
            ->where('tbl_ruang_peserta.id_ruangan', $id_ruangan)
            ->orderBy('tbl_ruang_peserta.no_komputer', 'ASC')
            ->get()->getResultArray();

        // HITUNG STATISTIK UNTUK KARTU ATAS
        $stats = [
            'belum'  => 0,
            'sedang' => 0,
            'selesai'=> 0,
            'total'  => count($siswa)
        ];

        foreach($siswa as $s) {
            if($s['status'] == 'SELESAI') $stats['selesai']++;
            elseif($s['status'] == 'SEDANG MENGERJAKAN' || $s['status'] == 'RAGU') $stats['sedang']++;
            else $stats['belum']++;
        }

        $data = [
            'title' => 'Live: ' . $ruangInfo['nama_ruangan'],
            'ruang' => $ruangInfo,
            'siswa' => $siswa,
            'stats' => $stats
        ];

        return view('admin/monitoring_ruang/detail', $data);
    }

    // 2. PROSES AKSI MASAL (AJAX)
    public function aksi_masal()
    {
        $jenis_aksi = $this->request->getPost('aksi'); // reset, stop, unlock, add_time
        $siswa_ids  = $this->request->getPost('ids'); // Array ID Siswa
        $menit      = $this->request->getPost('menit') ?? 0;

        if(empty($siswa_ids)) return $this->response->setJSON(['status' => 'error', 'msg' => 'Tidak ada siswa dipilih']);

        $db = $this->db->table('tbl_nilai');

        // Logic Aksi
        switch ($jenis_aksi) {
            case 'reset':
                // Hapus data nilai biar siswa bisa login ulang dari nol
                $db->whereIn('id_siswa', $siswa_ids)->delete();
                $msg = 'Ujian berhasil di-reset.';
                break;

            case 'stop':
                // Paksa Selesai
                $db->whereIn('id_siswa', $siswa_ids)
                   ->where('status !=', 'SELESAI')
                   ->update(['status' => 'SELESAI', 'waktu_selesai' => date('Y-m-d H:i:s')]);
                $msg = 'Ujian dipaksa selesai.';
                break;

            case 'unlock':
                // Buka Kunci Login (Reset Device)
                $db->whereIn('id_siswa', $siswa_ids)->update(['is_locked' => 0]);
                $msg = 'Login peserta dibuka.';
                break;

            case 'add_time':
                // Tambah Waktu (Update kolom extra_time)
                $db->whereIn('id_siswa', $siswa_ids)->set('extra_time', "extra_time + $menit", false)->update();
                $msg = "Waktu ditambah $menit menit.";
                break;
            
            default:
                return $this->response->setJSON(['status' => 'error', 'msg' => 'Aksi tidak dikenal']);
        }

        return $this->response->setJSON(['status' => 'success', 'msg' => $msg]);
    }
}