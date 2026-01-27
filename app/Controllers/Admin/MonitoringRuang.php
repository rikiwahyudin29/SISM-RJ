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

    // 1. HALAMAN DEPAN (DAFTAR RUANGAN)
    public function index()
    {
        $ruangan = $this->db->table('tbl_ruangan')->orderBy('nama_ruangan', 'ASC')->get()->getResultArray();
        
        foreach ($ruangan as &$r) {
            $r['jumlah_siswa'] = $this->db->table('tbl_ruang_peserta')
                                    ->where('id_ruangan', $r['id'])
                                    ->countAllResults();
            
            // Hitung yang sedang online/mengerjakan (Opsional, biar admin tau mana ruang yang aktif)
            $r['sedang_ujian'] = $this->db->table('tbl_ruang_peserta')
                                    ->join('tbl_ujian_siswa', 'tbl_ujian_siswa.id_siswa = tbl_ruang_peserta.id_siswa')
                                    ->where('tbl_ruang_peserta.id_ruangan', $r['id'])
                                    ->where('DATE(tbl_ujian_siswa.waktu_mulai)', date('Y-m-d'))
                                    ->where('tbl_ujian_siswa.status', 0)
                                    ->countAllResults();
        }

        return view('admin/monitoring_ruang/index', ['title' => 'Monitoring Ruangan', 'ruangan' => $ruangan]);
    }

    // 2. HALAMAN DETAIL (LIVE MONITORING)
    public function lihat($id_ruangan)
    {
        $ruangInfo = $this->db->table('tbl_ruangan')->where('id', $id_ruangan)->get()->getRowArray();
        if(!$ruangInfo) return redirect()->to('admin/monitoring-ruang');

        // AMBIL DATA SISWA + UJIAN HARI INI
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
                tbl_ujian_siswa.waktu_mulai, 
                tbl_ujian_siswa.waktu_submit as waktu_selesai,
                tbl_ujian_siswa.is_blocked,
                tbl_ujian_siswa.is_locked,
                
                tbl_mapel.nama_mapel
            ')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ruang_peserta.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            
            // JOIN UJIAN (Hanya Hari Ini)
            ->join('tbl_ujian_siswa', 'tbl_ujian_siswa.id_siswa = tbl_siswa.id AND DATE(tbl_ujian_siswa.waktu_mulai) = CURDATE()', 'left')
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_ujian_siswa.id_jadwal', 'left')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal', 'left')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel', 'left')
            
            ->where('tbl_ruang_peserta.id_ruangan', $id_ruangan)
            ->orderBy('tbl_ruang_peserta.no_komputer', 'ASC')
            ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // MAPPING STATUS
        $stats = ['belum' => 0, 'sedang' => 0, 'selesai' => 0];
        foreach($siswa as &$s) {
            if ($s['status_raw'] === '1') {
                $s['status'] = 'SELESAI';
                $stats['selesai']++;
            } elseif ($s['status_raw'] === '0') {
                $s['status'] = 'MENGERJAKAN';
                $stats['sedang']++;
            } else {
                $s['status'] = null; 
                $stats['belum']++;
            }
        }

        return view('admin/monitoring_ruang/detail', [
            'ruang' => $ruangInfo,
            'siswa' => $siswa,
            'stats' => $stats
        ]);
    }

    // 3. AKSI MASAL (RESET, STOP, UNLOCK, ADD TIME)
    public function aksi_masal()
    {
        $jenis_aksi = $this->request->getPost('aksi'); 
        $sesi_ids   = $this->request->getPost('ids'); 
        $menit      = $this->request->getPost('menit') ?? 0;

        if(empty($sesi_ids)) return $this->response->setJSON(['status' => 'error', 'msg' => 'Pilih data dulu']);

        $cache = \Config\Services::cache();
        $db = $this->db->table('tbl_ujian_siswa');

        switch ($jenis_aksi) {
            case 'reset':
                $db->whereIn('id', $sesi_ids)->delete();
                foreach($sesi_ids as $sid) { $cache->delete("ujian_tmp_" . $sid); }
                $msg = 'Ujian berhasil di-reset.';
                break;

            case 'stop':
                $db->whereIn('id', $sesi_ids)->where('status', 0)->update([
                    'status' => 1, 'waktu_submit' => date('Y-m-d H:i:s'), 'is_locked' => 0
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