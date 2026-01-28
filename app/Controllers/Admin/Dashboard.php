<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. DATA CARD STATISTIK (Counter Real)
        $total_guru  = $this->db->table('tbl_guru')->countAllResults();
        $total_siswa = $this->db->table('tbl_siswa')->countAllResults();
        $total_kelas = $this->db->table('tbl_kelas')->countAllResults();
        $total_mapel = $this->db->table('tbl_mapel')->countAllResults();
        
        // Asumsi: Wali kelas adalah guru yang datanya ada di tabel kelas
        $total_walikelas = $this->db->table('tbl_kelas')->countAllResults(); 

        // 2. DATA KEUANGAN REAL (Cek dulu tabelnya ada atau tidak)
        $total_bayar   = 0;
        $total_tunggak = 0; // Bisa diisi logika hitung tunggakan jika ada datanya
        
        if($this->db->tableExists('tbl_pembayaran')) {
            $queryBayar = $this->db->table('tbl_pembayaran')->selectSum('jumlah_bayar')->get()->getRow();
            $total_bayar = $queryBayar->jumlah_bayar ?? 0;
        }

        // Data untuk Grafik Keuangan (Donat)
        // Jika belum ada data tunggakan, kita buat perbandingan statis agar grafik tetap muncul
        $sisa_persen = ($total_bayar > 0) ? 30 : 100; // Contoh simulasi visual
        $chart_keuangan = [($total_bayar > 0 ? 70 : 0), $sisa_persen];

        // 3. DATA GRAFIK PRESENSI (REAL 7 HARI TERAKHIR)
        $chart_labels = [];
        $chart_presensi = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chart_labels[] = date('d M', strtotime($date)); // Cth: 28 Jan

            // Hitung Siswa yang Hadir/Telat pada tanggal tersebut
            $hadir = $this->db->table('tbl_presensi')
                ->where('tanggal', $date)
                ->whereIn('status_kehadiran', ['Hadir', 'Terlambat']) // Telat tetap dihitung masuk
                ->countAllResults();
            
            // Hitung Persentase (Hadir / Total Siswa * 100)
            $persen = ($total_siswa > 0) ? round(($hadir / $total_siswa) * 100) : 0;
            $chart_presensi[] = $persen;
        }

        // 4. LOG LOGIN TERAKHIR (REAL)
        // Kita ambil SELECT * agar aman, jadi mau kolomnya 'role' atau 'level' tetap keambil
        $last_login = $this->db->table('tbl_users')
            ->select('*') 
            ->orderBy('last_login', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // 5. LOG TRANSAKSI KEUANGAN TERAKHIR (REAL)
        $log_keuangan = [];
        if($this->db->tableExists('tbl_pembayaran')) {
            // Join ke tabel siswa untuk dapat nama pembayar
            $log_keuangan = $this->db->table('tbl_pembayaran')
                ->select('tbl_pembayaran.*, tbl_siswa.nama_lengkap')
                ->join('tbl_siswa', 'tbl_siswa.id = tbl_pembayaran.id_siswa', 'left')
                ->orderBy('tbl_pembayaran.tanggal_bayar', 'DESC')
                ->limit(5)
                ->get()->getResultArray();
        }

        return view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'total_guru' => $total_guru,
            'total_siswa' => $total_siswa,
            'total_kelas' => $total_kelas,
            'total_mapel' => $total_mapel,
            'total_walikelas' => $total_walikelas,
            'total_piket' => 5, // Bisa diganti query real jika ada tabel piket
            'total_bk' => 2,    // Bisa diganti query real jika ada tabel pelanggaran
            'total_bayar' => $total_bayar,
            'total_tunggak' => $total_tunggak,
            'chart_labels' => $chart_labels,
            'chart_presensi' => $chart_presensi,
            'chart_keuangan' => $chart_keuangan,
            'last_login' => $last_login,
            'log_keuangan' => $log_keuangan
        ]);
    }
}