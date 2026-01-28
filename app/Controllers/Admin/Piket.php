<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Piket extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Helper: Translate Hari Inggris ke Indo
    private function getHariIndo($day) {
        $hari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        return $hari[$day] ?? 'Senin';
    }

    public function index()
    {
        $hari_ini = $this->getHariIndo(date('l'));
        $tanggal_ini = date('Y-m-d');
        $jam_sekarang = date('H:i:s');

        // --- PERBAIKAN: CEK DULU NAMA KOLOMNYA APA ---
        // Kalau ada 'nama_guru' pakai itu, kalau tidak pakai 'nama_lengkap'
        $kolom_guru = $this->db->fieldExists('nama_guru', 'tbl_guru') ? 'nama_guru' : 'nama_lengkap';

        // 1. AMBIL SEMUA JADWAL HARI INI
        $jadwal = $this->db->table('tbl_jadwal')
            // Kita pakai alias 'as nama_guru' biar di View pemanggilannya tetap sama
            ->select("tbl_jadwal.*, tbl_guru.$kolom_guru as nama_guru, tbl_mapel.nama_mapel, tbl_kelas.nama_kelas")
            ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal.id_guru')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal.id_kelas')
            ->where('tbl_jadwal.hari', $hari_ini)
            ->orderBy('tbl_jadwal.jam_mulai', 'ASC')
            ->get()->getResultArray();

        // 2. CEK STATUS SETIAP JADWAL
        $monitoring = [];
        
        foreach ($jadwal as $j) {
            // Cek apakah guru ini sudah isi jurnal?
            $cekJurnal = $this->db->table('tbl_jurnal')
                ->where('id_guru', $j['id_guru'])
                ->where('id_kelas', $j['id_kelas'])
                ->where('id_mapel', $j['id_mapel'])
                ->where('tanggal', $tanggal_ini)
                ->get()->getRowArray();

            $status = '';
            $badgeColor = '';

            // LOGIKA PENENTUAN STATUS
            if ($cekJurnal) {
                // SUDAH ISI JURNAL
                $status = 'HADIR (Mengajar)';
                $badgeColor = 'bg-emerald-100 text-emerald-700 border-emerald-200';
            } else {
                // BELUM ISI JURNAL, CEK JAM
                if ($jam_sekarang < $j['jam_mulai']) {
                    $status = 'MENUNGGU';
                    $badgeColor = 'bg-slate-100 text-slate-500 border-slate-200';
                } elseif ($jam_sekarang >= $j['jam_mulai'] && $jam_sekarang <= $j['jam_selesai']) {
                    $status = 'SEDANG BERLANGSUNG';
                    $badgeColor = 'bg-yellow-100 text-yellow-700 border-yellow-200 animate-pulse';
                } else {
                    // Jam sudah lewat tapi belum ada jurnal
                    $status = 'ALPHA (Tidak Ada Laporan)';
                    $badgeColor = 'bg-rose-100 text-rose-700 border-rose-200';
                }
            }

            $j['status_kbm'] = $status;
            $j['badge_color'] = $badgeColor;
            $j['data_jurnal'] = $cekJurnal; 
            
            // Nama Guru sudah di-alias di query atas jadi 'nama_guru', jadi aman
            $j['nama_guru_fix'] = $j['nama_guru'];

            $monitoring[] = $j;
        }

        return view('admin/piket/index', [
            'title' => 'Monitoring Guru Piket',
            'monitoring' => $monitoring,
            'hari_ini' => $hari_ini,
            'jam_sekarang' => $jam_sekarang
        ]);
    }
}