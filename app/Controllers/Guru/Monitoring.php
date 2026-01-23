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

    // 1. HALAMAN DEPAN: DAFTAR RUANGAN
    public function index()
    {
        $data_ruangan = $this->db->table('tbl_jadwal_ujian')
            ->select('
                tbl_jadwal_ujian.id, 
                tbl_jadwal_ujian.status,
                tbl_kelas.nama_kelas AS nama_ruangan, 
                
                -- PERBAIKAN DISINI: Pakai kelas_id
                (SELECT COUNT(*) FROM tbl_siswa WHERE tbl_siswa.kelas_id = tbl_kelas.id) AS jumlah_siswa
            ')
            ->join('tbl_jadwal_kelas', 'tbl_jadwal_kelas.id_jadwal_ujian = tbl_jadwal_ujian.id')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal_kelas.id_kelas')
            ->where('tbl_jadwal_ujian.status', 1) 
            ->orderBy('tbl_jadwal_ujian.waktu_mulai', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title'   => 'Monitoring Ujian',
            'ruangan' => $data_ruangan 
        ];

        return view('guru/monitoring/index', $data);
    }

    // 2. HALAMAN DETAIL (Masuk Ruangan)
    public function lihat($id_jadwal)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_mapel.nama_mapel, tbl_kelas.nama_kelas')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->join('tbl_jadwal_kelas', 'tbl_jadwal_kelas.id_jadwal_ujian = tbl_jadwal_ujian.id')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal_kelas.id_kelas')
            ->where('tbl_jadwal_ujian.id', $id_jadwal)
            ->get()->getRowArray();

        if(!$jadwal) return redirect()->to('guru/monitoring')->with('error', 'Jadwal tidak ditemukan');

        // Pastikan Anda punya view 'guru/monitoring/lihat.php'
        return view('guru/monitoring/lihat', [
            'title' => 'Pantau Ujian',
            'jadwal' => $jadwal,
            'id_jadwal' => $id_jadwal
        ]);
    }

    // 3. API REALTIME
    public function get_data_monitoring()
    {
        $id_jadwal = $this->request->getPost('id_jadwal');
        if(!$id_jadwal) return $this->response->setJSON(['data' => []]);

        $kelas_list = $this->db->table('tbl_jadwal_kelas')->select('id_kelas')->where('id_jadwal_ujian', $id_jadwal)->get()->getResultArray();
        $arr_kelas = array_column($kelas_list, 'id_kelas');
        if(empty($arr_kelas)) return $this->response->setJSON(['data' => []]);

        // Auto-detect kolom kelas (untuk jaga-jaga)
        $fields = $this->db->getFieldNames('tbl_siswa');
        $kolomKelas = 'id_kelas'; 
        if (in_array('kelas_id', $fields)) $kolomKelas = 'kelas_id';

        $builder = $this->db->table('tbl_siswa s');
        $builder->select('s.nama_siswa as nama, s.nis, n.id as id_ujian_siswa, n.status, n.is_locked, n.is_blocked, n.jml_pelanggaran, n.ip_address, n.user_agent');
        $builder->join('tbl_nilai n', '(n.id_siswa = s.user_id OR n.id_siswa = s.id) AND n.id_jadwal = ' . $id_jadwal, 'left');
        $builder->whereIn("s.$kolomKelas", $arr_kelas);
        $builder->orderBy('s.nama_siswa', 'ASC');
        
        $data_siswa = $builder->get()->getResultArray();
        
        $result = [];
        foreach($data_siswa as $row) {
            $status_text = '<span class="badge-abu">BELUM</span>';
            if (!empty($row['id_ujian_siswa'])) {
                if ($row['is_blocked'] == 1) $status_text = '<span class="badge-merah">DISKUALIFIKASI</span>';
                elseif ($row['is_locked'] == 1) $status_text = '<span class="badge-oren">TERKUNCI</span>';
                elseif ($row['status'] == 'SELESAI') $status_text = '<span class="badge-hijau">SELESAI</span>';
                else $status_text = '<span class="badge-biru">MENGERJAKAN</span>';
            }
            $result[] = [
                'id_ujian_siswa' => $row['id_ujian_siswa'] ?? 0, 
                'nama' => $row['nama'],
                'nis' => $row['nis'],
                'status_html' => $status_text,
                'ip' => $row['ip_address'] ?? '-',
                'device' => $row['user_agent'] ? substr($row['user_agent'], 0, 20).'...' : '-',
                'pelanggaran' => $row['jml_pelanggaran'] ?? 0,
                'is_locked' => $row['is_locked']
            ];
        }
        return $this->response->setJSON(['data' => $result]);
    }
    
    // 4. AKSI TOMBOL (RESET/STOP/UNLOCK)
    public function aksi_masal()
    {
        $jenis_aksi = $this->request->getPost('aksi'); 
        $siswa_ids  = $this->request->getPost('ids'); 

        if(is_array($siswa_ids)) {
            $siswa_ids = array_filter($siswa_ids, function($v) { return !empty($v) && $v > 0; });
        }

        if(empty($siswa_ids)) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Pilih siswa yang sudah memulai ujian.']);
        }

        $db = $this->db->table('tbl_nilai');
        $db->whereIn('id', $siswa_ids);

        $msg = "";
        switch ($jenis_aksi) {
            case 'reset':
                $db->delete(); 
                $this->db->table('tbl_jawaban_siswa')->whereIn('id_ujian_siswa', $siswa_ids)->delete();
                $msg = 'Ujian berhasil di-reset.';
                break;
            case 'stop':
                $db->update(['status' => 'SELESAI', 'waktu_selesai' => date('Y-m-d H:i:s')]);
                $msg = 'Ujian dipaksa selesai.';
                break;
            case 'unlock':
                $db->update(['is_locked' => 0, 'is_blocked' => 0, 'jml_pelanggaran' => 0]);
                $msg = 'Kunci berhasil dibuka.';
                break;
            default:
                return $this->response->setJSON(['status' => 'error', 'msg' => 'Aksi error']);
        }

        return $this->response->setJSON(['status' => 'success', 'msg' => $msg]);
    }
}