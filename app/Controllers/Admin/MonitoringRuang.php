<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Monitoring extends BaseController
{
    protected $db;
    protected $id_user_login;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->id_user_login = session()->get('id_user');
    }

    // 1. HALAMAN DEPAN: DAFTAR RUANGAN (CARD VIEW)
    public function index()
    {
        // Ambil semua ruangan
        $ruangan = $this->db->table('tbl_ruangan')->orderBy('nama_ruangan', 'ASC')->get()->getResultArray();
        
        // Hitung jumlah siswa per ruangan (subquery manual biar ringan)
        foreach ($ruangan as &$r) {
            $r['jumlah_siswa'] = $this->db->table('tbl_ruang_peserta')
                                    ->where('id_ruangan', $r['id'])
                                    ->countAllResults();
        }

        $data = [
            'title'   => 'Monitoring Ruangan',
            'ruangan' => $ruangan
        ];

        return view('guru/monitoring/index', $data);
    }

    // 2. HALAMAN DETAIL: TABEL SISWA (REALTIME)
    public function lihat($id_ruangan)
    {
        // Ambil Info Ruangan
        $ruangInfo = $this->db->table('tbl_ruangan')->where('id', $id_ruangan)->get()->getRowArray();

        if(!$ruangInfo) {
            return redirect()->to('guru/monitoring')->with('error', 'Ruangan tidak ditemukan.');
        }
        
        // QUERY UTAMA (Sama persis dengan Admin)
        // Mengambil data siswa yang duduk di ruangan ini + Status Ujiannya
        $siswa = $this->db->table('tbl_ruang_peserta')
            ->select('
                tbl_ruang_peserta.no_komputer, 
                tbl_siswa.id as siswa_id, 
                tbl_siswa.nama_lengkap, 
                tbl_siswa.nis, 
                tbl_kelas.nama_kelas, 
                
                -- Data Nilai / Status Ujian
                tbl_nilai.id as nilai_id, 
                tbl_nilai.status, 
                tbl_nilai.nilai_sementara, 
                tbl_nilai.jml_benar, 
                tbl_nilai.jml_salah,
                tbl_nilai.waktu_mulai, 
                tbl_nilai.waktu_selesai, 
                tbl_nilai.ip_address, 
                tbl_nilai.is_locked,
                
                -- Info Ujian
                tbl_bank_soal.judul_ujian
            ')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ruang_peserta.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            
            // LEFT JOIN ke Nilai (Cari yang sedang aktif / hari ini)
            // Menggunakan logika admin: status != NONAKTIF
            ->join('tbl_nilai', 'tbl_nilai.id_siswa = tbl_siswa.id AND tbl_nilai.status != "NONAKTIF"', 'left')
            
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_nilai.id_jadwal', 'left')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal', 'left')
            
            ->where('tbl_ruang_peserta.id_ruangan', $id_ruangan)
            ->orderBy('tbl_ruang_peserta.no_komputer', 'ASC')
            ->get()->getResultArray();

        // HITUNG STATISTIK (CARD ATAS)
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

        // Pastikan nama file view sesuai (lihat langkah 3)
        return view('guru/monitoring/lihat', $data); 
    }

    // 3. PROSES AKSI MASAL (RESET/STOP/UNLOCK/ADD TIME)
    public function aksi_masal()
    {
        $jenis_aksi = $this->request->getPost('aksi'); 
        $siswa_ids  = $this->request->getPost('ids'); 
        $menit      = $this->request->getPost('menit') ?? 0;

        if(empty($siswa_ids)) return $this->response->setJSON(['status' => 'error', 'msg' => 'Tidak ada siswa dipilih']);

        $db = $this->db->table('tbl_nilai');

        switch ($jenis_aksi) {
            case 'reset':
                // Hapus nilai (Siswa login dari awal)
                $db->whereIn('id_siswa', $siswa_ids)->delete();
                // Hapus jawaban detail juga jika perlu
                $this->db->table('tbl_jawaban_siswa')->whereIn('id_siswa', $siswa_ids)->delete();
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
                // Buka Kunci
                $db->whereIn('id_siswa', $siswa_ids)->update(['is_locked' => 0]);
                $msg = 'Login peserta dibuka.';
                break;

            case 'add_time':
                // Tambah Waktu
                // Menggunakan query raw untuk increment
                foreach($siswa_ids as $sid) {
                    $this->db->query("UPDATE tbl_nilai SET extra_time = extra_time + ? WHERE id_siswa = ?", [$menit, $sid]);
                }
                $msg = "Waktu ditambah $menit menit.";
                break;
            
            default:
                return $this->response->setJSON(['status' => 'error', 'msg' => 'Aksi tidak dikenal']);
        }

        return $this->response->setJSON(['status' => 'success', 'msg' => $msg]);
    }
}