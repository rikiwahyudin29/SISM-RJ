<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Monitoring extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 1. HALAMAN DEPAN (DAFTAR RUANGAN)
    public function index()
    {
        $ruangan = $this->db->table('tbl_ruangan')->orderBy('nama_ruangan', 'ASC')->get()->getResultArray();
        
        foreach ($ruangan as &$r) {
            $r['jumlah_siswa'] = $this->db->table('tbl_ruang_peserta')
                                    ->where('id_ruangan', $r['id'])
                                    ->countAllResults();
        }

        return view('guru/monitoring/index', ['title' => 'Monitoring Ruangan', 'ruangan' => $ruangan]);
    }

    // 2. HALAMAN DETAIL (MONITORING SISWA)
    public function lihat($id_ruangan)
    {
        $ruangInfo = $this->db->table('tbl_ruangan')->where('id', $id_ruangan)->get()->getRowArray();
        if(!$ruangInfo) return redirect()->to('guru/monitoring');

        // LOGIC UTAMA:
        // Ambil siswa + Join Ujian Hari Ini
        
        $siswa = $this->db->table('tbl_ruang_peserta')
            ->select('
                tbl_ruang_peserta.no_komputer, 
                tbl_siswa.id as siswa_id, 
                tbl_siswa.nama_lengkap, 
                tbl_siswa.nis, 
                tbl_kelas.nama_kelas, 
                
                tbl_ujian_siswa.id as id_ujian_siswa,
                tbl_ujian_siswa.status as status_raw, 
                tbl_ujian_siswa.nilai as nilai_sementara, 
                tbl_ujian_siswa.jml_benar, 
                tbl_ujian_siswa.jml_salah,
                tbl_ujian_siswa.waktu_mulai, 
                tbl_ujian_siswa.waktu_submit as waktu_selesai,
                tbl_ujian_siswa.ip_address, 
                tbl_ujian_siswa.is_blocked,
                tbl_ujian_siswa.is_locked,
                
                tbl_bank_soal.judul_ujian,
                tbl_mapel.nama_mapel
            ')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ruang_peserta.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            
            // JOIN DATA UJIAN (HANYA HARI INI)
            ->join('tbl_ujian_siswa', 'tbl_ujian_siswa.id_siswa = tbl_siswa.id AND DATE(tbl_ujian_siswa.waktu_mulai) = CURDATE()', 'left')
            
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_ujian_siswa.id_jadwal', 'left')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal', 'left')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel', 'left')
            
            ->where('tbl_ruang_peserta.id_ruangan', $id_ruangan)
            ->orderBy('tbl_ruang_peserta.no_komputer', 'ASC')
            ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // MAPPING STATUS UNTUK VIEW
        $stats = ['belum' => 0, 'sedang' => 0, 'selesai' => 0];
        
        foreach($siswa as &$s) {
            if ($s['status_raw'] === '1') {
                $s['status'] = 'SELESAI';
                $stats['selesai']++;
            } elseif ($s['status_raw'] === '0') {
                $s['status'] = 'MENGERJAKAN';
                $stats['sedang']++;
            } else {
                $s['status'] = null; // Belum mulai
                $stats['belum']++;
            }
        }

        return view('guru/monitoring/detail', [
            'ruang' => $ruangInfo,
            'siswa' => $siswa,
            'stats' => $stats
        ]);
    }

    // 3. AKSI MASAL (RESET / STOP / DLL)
    public function aksi_masal()
    {
        $jenis_aksi = $this->request->getPost('aksi'); 
        $sesi_ids   = $this->request->getPost('ids'); // INI ADALAH ID SESI (id_ujian_siswa)
        $menit      = $this->request->getPost('menit') ?? 0;

        if(empty($sesi_ids)) return $this->response->setJSON(['status' => 'error', 'msg' => 'Pilih data ujian dulu']);

        $cache = \Config\Services::cache();

        // TARGETKAN TABEL tbl_ujian_siswa BERDASARKAN ID (PRIMARY KEY)
        $db = $this->db->table('tbl_ujian_siswa');

        switch ($jenis_aksi) {
            case 'reset':
                // Hapus Sesi Spesifik
                $db->whereIn('id', $sesi_ids)->delete();
                
                // Hapus Cache Redis
                foreach($sesi_ids as $sid) {
                    $cache->delete("ujian_tmp_" . $sid);
                }
                
                $msg = 'Ujian berhasil di-reset. Siswa bisa login ulang untuk mapel ini.';
                break;

            case 'stop':
                // Paksa Selesai (Update Status = 1)
                $db->whereIn('id', $sesi_ids)
                   ->where('status', 0) // Hanya yang sedang mengerjakan
                   ->update([
                        'status' => 1, 
                        'waktu_submit' => date('Y-m-d H:i:s'),
                        'is_locked' => 0
                   ]);
                $msg = 'Ujian dipaksa selesai.';
                break;

            case 'unlock':
                $db->whereIn('id', $sesi_ids)->update(['is_locked' => 0, 'is_blocked' => 0]);
                $msg = 'Kunci dibuka.';
                break;

            case 'add_time':
                foreach($sesi_ids as $sid) {
                    $this->db->query("UPDATE tbl_ujian_siswa SET waktu_selesai_seharusnya = DATE_ADD(waktu_selesai_seharusnya, INTERVAL ? MINUTE) WHERE id = ? AND status = 0", [$menit, $sid]);
                }
                $msg = "Waktu ditambah $menit menit.";
                break;
            
            default:
                return $this->response->setJSON(['status' => 'error', 'msg' => 'Aksi error']);
        }

        return $this->response->setJSON(['status' => 'success', 'msg' => $msg]);
    }
}