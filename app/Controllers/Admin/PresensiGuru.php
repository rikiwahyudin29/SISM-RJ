<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class PresensiGuru extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 1. MONITORING HARIAN
    public function index()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        // REVISI: Ganti nama_guru jadi nama_lengkap
        // Cek dulu kolom yang tersedia biar aman
      $kolom_nama = $this->db->fieldExists('nama_guru', 'tbl_guru') ? 'nama_guru' : 'nama_lengkap';

        // Ambil Presensi Guru Hari Ini
        $presensi = $this->db->table('tbl_presensi')
            ->select("tbl_presensi.*, tbl_guru.$kolom_nama as nama_guru, tbl_guru.nip")
            ->join('tbl_guru', 'tbl_guru.id = tbl_presensi.user_id')
            ->where('tbl_presensi.role', 'guru')
            ->where('tbl_presensi.tanggal', $tanggal)
            ->orderBy('tbl_presensi.jam_masuk', 'ASC')
            ->get()->getResultArray();

        // Ambil Guru yang BELUM Absen (Alpha)
        $sudah_absen = array_column($presensi, 'user_id');
        $belum_absen = [];
        
        $builder = $this->db->table('tbl_guru')->select("*, $kolom_nama as nama_guru"); // Alias biar view gak bingung
        
        if(empty($sudah_absen)) {
            $belum_absen = $builder->orderBy($kolom_nama, 'ASC')->get()->getResultArray();
        } else {
            $belum_absen = $builder->whereNotIn('id', $sudah_absen)
                ->orderBy($kolom_nama, 'ASC')
                ->get()->getResultArray();
        }

        return view('admin/presensi_guru/harian', [
            'title'   => 'Presensi Harian Guru',
            'tanggal' => $tanggal,
            'hadir'   => $presensi,
            'alpha'   => $belum_absen
        ]);
    }

    // 2. REKAP BULANAN (MATRIX VIEW)
    public function rekap()
    {
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $jml_hari = date('t', strtotime($bulan));

        $kolom_nama = $this->db->fieldExists('nama_guru', 'tbl_guru') ? 'nama_guru' : 'nama_lengkap';

        // Ambil Semua Guru
        $guru = $this->db->table('tbl_guru')
            ->select("*, $kolom_nama as nama_guru")
            ->orderBy($kolom_nama, 'ASC')
            ->get()->getResultArray();

        // Ambil Data Presensi Bulan Tersebut
        $data_absen = $this->db->table('tbl_presensi')
            ->where('role', 'guru')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        // Mapping Data: $map[id_guru][tanggal] = 'Hadir'
        $map = [];
        foreach ($data_absen as $row) {
            $tgl = (int) date('d', strtotime($row['tanggal']));
            $map[$row['user_id']][$tgl] = $row['status_kehadiran'];
        }

        return view('admin/presensi_guru/rekap', [
            'title'    => 'Rekap Absensi Guru',
            'bulan'    => $bulan,
            'guru'     => $guru,
            'map'      => $map,
            'jml_hari' => $jml_hari
        ]);
    }

    // 3. CETAK REKAP PDF
    public function cetak_rekap()
    {
        $bulan = $this->request->getGet('bulan');
        if(!$bulan) return "Pilih bulan dulu!";

        $jml_hari = date('t', strtotime($bulan));
        $kolom_nama = $this->db->fieldExists('nama_guru', 'tbl_guru') ? 'nama_guru' : 'nama_lengkap';
        
        $guru = $this->db->table('tbl_guru')
            ->select("*, $kolom_nama as nama_guru")
            ->orderBy($kolom_nama, 'ASC')
            ->get()->getResultArray();
        
        $data_absen = $this->db->table('tbl_presensi')
            ->where('role', 'guru')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        $map = [];
        foreach ($data_absen as $row) {
            $tgl = (int) date('d', strtotime($row['tanggal']));
            $map[$row['user_id']][$tgl] = $row['status_kehadiran'];
        }

        return view('admin/presensi_guru/cetak_rekap', [
            'bulan'    => $bulan,
            'guru'     => $guru,
            'map'      => $map,
            'jml_hari' => $jml_hari,
            'sekolah'  => [
                'nama' => 'SMK DIGITAL INDONESIA',
                'kepsek' => 'Bapak Kepala Sekolah, M.Pd'
            ]
        ]);
    }

    // 4. INPUT MANUAL (IZIN/SAKIT/DINAS LUAR)
    public function simpan_manual()
    {
        $id_guru = $this->request->getPost('id_guru');
        $status  = $this->request->getPost('status');
        $ket     = $this->request->getPost('keterangan');
        $tanggal = $this->request->getPost('tanggal');

        // Cek duplikat
        $cek = $this->db->table('tbl_presensi')
            ->where('user_id', $id_guru)->where('role', 'guru')->where('tanggal', $tanggal)
            ->countAllResults();

        if ($cek > 0) return redirect()->back()->with('error', 'Guru tersebut sudah absen hari ini.');

        $this->db->table('tbl_presensi')->insert([
            'user_id' => $id_guru,
            'role'    => 'guru',
            'tanggal' => $tanggal,
            'jam_masuk' => date('H:i:s'),
            'status_kehadiran' => $status,
            'metode'  => 'Manual',
            'keterangan' => $ket,
            'status_verifikasi' => 'Disetujui'
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }
}