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

    // 2. HALAMAN DETAIL (SINKRONISASI FIX)
    public function lihat($id_ruangan)
    {
        $ruangInfo = $this->db->table('tbl_ruangan')->where('id', $id_ruangan)->get()->getRowArray();
        if(!$ruangInfo) return redirect()->to('guru/monitoring');

        // PERBAIKAN: Ambil data dari tbl_ujian_siswa (Bukan tbl_nilai)
        // Kita gunakan 'status_raw' untuk angka asli database (0/1)
        // Dan alias kolom lain agar sesuai dengan View (nilai_sementara, dll)
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
                tbl_ujian_siswa.is_locked,
                
                tbl_bank_soal.judul_ujian
            ')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ruang_peserta.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            
            // JOIN KE TABEL BARU (tbl_ujian_siswa)
            // Filter hanya ujian hari ini agar tidak mengambil data lama
            ->join('tbl_ujian_siswa', 'tbl_ujian_siswa.id_siswa = tbl_siswa.id AND DATE(tbl_ujian_siswa.waktu_mulai) = CURDATE()', 'left')
            
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_ujian_siswa.id_jadwal', 'left')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal', 'left')
            
            ->where('tbl_ruang_peserta.id_ruangan', $id_ruangan)
            ->orderBy('tbl_ruang_peserta.no_komputer', 'ASC')
            ->get()->getResultArray();

        // LOGIC MAPPING STATUS (Agar View Bos tidak perlu diubah)
        // View mengharapkan string "SELESAI" atau truthy value untuk "Mengerjakan"
        $stats = ['belum' => 0, 'sedang' => 0, 'selesai' => 0];
        
        foreach($siswa as &$s) {
            // Mapping Status Angka (0/1) ke String View
            if ($s['status_raw'] === '1') {
                $s['status'] = 'SELESAI';
                $stats['selesai']++;
            } elseif ($s['status_raw'] === '0') {
                $s['status'] = 'MENGERJAKAN'; // String apa saja yg penting tidak kosong & bukan SELESAI
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

    // 3. AKSI MASAL (SINKRONISASI FIX)
    public function aksi_masal()
    {
        $jenis_aksi = $this->request->getPost('aksi'); 
        $siswa_ids  = $this->request->getPost('ids'); 
        $menit      = $this->request->getPost('menit') ?? 0;

        if(empty($siswa_ids)) return $this->response->setJSON(['status' => 'error', 'msg' => 'Pilih siswa dulu']);

        // TARGETKAN TABEL tbl_ujian_siswa
        $db = $this->db->table('tbl_ujian_siswa');

        switch ($jenis_aksi) {
            case 'reset':
                $db->whereIn('id_siswa', $siswa_ids)->delete();
                // Hapus jawaban juga agar bersih total
                // Kita perlu cari id_ujian_siswa-nya dulu atau hapus by id_siswa di tabel jawaban jika ada relasi
                // Opsi aman: Hapus sesi ujian saja, siswa login ulang -> create sesi baru
                $msg = 'Ujian di-reset.';
                break;

            case 'stop':
                $db->whereIn('id_siswa', $siswa_ids)
                   ->where('status', 0)
                   ->update([
                       'status' => 1, 
                       'waktu_submit' => date('Y-m-d H:i:s')
                   ]);
                $msg = 'Ujian dipaksa selesai.';
                break;

            case 'unlock':
                $db->whereIn('id_siswa', $siswa_ids)->update(['is_locked' => 0, 'is_blocked' => 0]);
                $msg = 'Kunci dibuka.';
                break;

            case 'add_time':
                foreach($siswa_ids as $sid) {
                    $this->db->query("UPDATE tbl_ujian_siswa SET waktu_selesai_seharusnya = DATE_ADD(waktu_selesai_seharusnya, INTERVAL ? MINUTE) WHERE id_siswa = ?", [$menit, $sid]);
                }
                $msg = "Waktu ditambah $menit menit.";
                break;
            
            default:
                return $this->response->setJSON(['status' => 'error', 'msg' => 'Aksi error']);
        }

        return $this->response->setJSON(['status' => 'success', 'msg' => $msg]);
    }
}