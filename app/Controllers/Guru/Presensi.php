<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Presensi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Fungsi Helper: Cari ID Guru Asli berdasarkan User Login
    private function getRealGuruID($id_user_login)
    {
        // 1. Cek apakah tabel guru punya kolom penghubung ke user?
        // Biasanya namanya 'id_user' atau 'user_id'
        $kolom_user = $this->db->fieldExists('id_user', 'tbl_guru') ? 'id_user' : 'user_id';
        
        if ($this->db->fieldExists($kolom_user, 'tbl_guru')) {
            $guru = $this->db->table('tbl_guru')->where($kolom_user, $id_user_login)->get()->getRow();
            if ($guru) {
                return $guru->id; // KETEMU! Pakai ID Guru ini.
            }
        }

        // 2. Jika tidak ada relasi, cek apakah ID User = ID Guru (Sistem Sederhana)
        $guru_direct = $this->db->table('tbl_guru')->where('id', $id_user_login)->get()->getRow();
        if ($guru_direct) {
            return $guru_direct->id;
        }

        // 3. Fallback: Kembalikan ID User login apa adanya
        return $id_user_login;
    }

    public function index()
    {
        $id_user_login = session()->get('id_user');
        
        // --- PERBAIKAN: Cari ID Guru yang SEBENARNYA ---
        $id_guru_fix = $this->getRealGuruID($id_user_login);
        // ------------------------------------------------

        $bulan   = $this->request->getGet('bulan') ?? date('Y-m');

        $data = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru_fix) // Pakai ID Guru Fix
            ->where('role', 'guru')
            ->like('tanggal', $bulan)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        return view('guru/presensi/index', [
            'title' => 'Riwayat Kehadiran Anda',
            'data'  => $data,
            'bulan' => $bulan
        ]);
    }

    public function rekap()
    {
        $id_user_login  = session()->get('id_user');
        
        // --- PERBAIKAN: Cari ID Guru yang SEBENARNYA ---
        $id_guru_fix = $this->getRealGuruID($id_user_login);
        // ------------------------------------------------

        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        $absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru_fix) // Pakai ID Guru Fix
            ->where('role', 'guru')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        $map = [];
        $total = ['H'=>0, 'S'=>0, 'I'=>0, 'A'=>0, 'T'=>0];

        foreach($absen as $a) {
            $tgl = (int) date('d', strtotime($a['tanggal']));
            $st  = $a['status_kehadiran'];
            $map[$tgl] = $st;

            if($st == 'Hadir') $total['H']++;
            if($st == 'Terlambat') { $total['T']++; $total['H']++; }
            if($st == 'Sakit') $total['S']++;
            if($st == 'Izin') $total['I']++;
            if($st == 'Alpha') $total['A']++;
        }

        return view('guru/presensi/rekap', [
            'title'    => 'Rekap Bulanan Guru',
            'bulan'    => $bulan,
            'map'      => $map,
            'total'    => $total,
            'jml_hari' => $jml_hari
        ]);
    }
    public function izin()
    {
        $id_user_login = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user_login); // Pakai helper yang sudah ada

        $riwayat = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru)
            ->where('role', 'guru')
            ->whereIn('status_kehadiran', ['Izin', 'Sakit', 'Dinas Luar']) // Guru ada Dinas Luar
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        return view('guru/presensi/izin', [
            'title'   => 'Ajukan Izin/Sakit',
            'riwayat' => $riwayat
        ]);
    }

    // --- PROSES SIMPAN IZIN GURU ---
    public function ajukan()
    {
        $id_user_login = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user_login);

        // Upload Bukti (Opsional buat guru, tapi disarankan)
        $file = $this->request->getFile('bukti');
        $namaFile = null;
        if ($file && $file->isValid()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/surat_izin', $namaFile);
        }

        // Cek Duplikat
        $cek = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru)
            ->where('tanggal', $this->request->getPost('tanggal'))
            ->countAllResults();

        if ($cek > 0) return redirect()->back()->with('error', 'Anda sudah absen hari ini.');

        $this->db->table('tbl_presensi')->insert([
            'user_id' => $id_guru,
            'role' => 'guru',
            'tanggal' => $this->request->getPost('tanggal'),
            'status_kehadiran' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
            'bukti_izin' => $namaFile,
            'metode' => 'Online',
            'status_verifikasi' => 'Pending' // Menunggu ACC Admin/Kepsek
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim.');
    }
    public function cetak_rekap()
    {
        $id_user_login = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user_login); // Helper ID
        
        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        // Ambil Data Guru
        $guru = $this->db->table('tbl_guru')->where('id', $id_guru)->get()->getRowArray();
        // Handle nama kolom (nama_guru / nama_lengkap / nama)
        $nama_guru = $guru['nama_guru'] ?? $guru['nama_lengkap'] ?? $guru['nama'];

        $absen = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru)
            ->where('role', 'guru')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        $map = [];
        $total = ['H'=>0, 'S'=>0, 'I'=>0, 'A'=>0, 'T'=>0];
        foreach($absen as $a) {
            $tgl = (int) date('d', strtotime($a['tanggal']));
            $st  = $a['status_kehadiran'];
            $map[$tgl] = $st;

            if($st == 'Hadir') $total['H']++;
            if($st == 'Terlambat') { $total['T']++; $total['H']++; }
            if($st == 'Sakit') $total['S']++;
            if($st == 'Izin') $total['I']++;
            if($st == 'Alpha') $total['A']++;
        }

        return view('guru/presensi/cetak_rekap', [
            'guru'     => $guru,
            'nama_guru'=> $nama_guru,
            'bulan'    => $bulan,
            'map'      => $map,
            'total'    => $total,
            'jml_hari' => $jml_hari,
            'sekolah'  => [
                'nama' => 'SMK DIGITAL INDONESIA',
                'kepsek' => 'Bapak Kepala Sekolah, M.Pd'
            ]
        ]);
    }
}