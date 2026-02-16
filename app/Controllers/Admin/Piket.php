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
    public function jurnal()
    {
        $data = [
            'title' => 'Jurnal Piket Harian',
            'guru'  => $this->db->table('tbl_guru')->get()->getResultArray(),
            'jurnal' => $this->db->table('tbl_jurnal_piket')
                        ->select('tbl_jurnal_piket.*, g1.nama_lengkap as nama_guru, g2.nama_lengkap as nama_pengganti')
                        ->join('tbl_guru g1', 'g1.id = tbl_jurnal_piket.guru_id')
                        ->join('tbl_guru g2', 'g2.id = tbl_jurnal_piket.guru_pengganti_id', 'left')
                        ->where('tanggal', date('Y-m-d'))
                        ->get()->getResultArray()
        ];
        return view('admin/piket/jurnal', $data);
    }

    public function saveJurnal()
    {
        $this->db->table('tbl_jurnal_piket')->insert([
            'tanggal'           => date('Y-m-d'),
            'guru_id'           => $this->request->getPost('guru_id'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'tugas'             => $this->request->getPost('tugas'),
            'guru_pengganti_id' => $this->request->getPost('guru_pengganti_id'),
            'created_at'        => date('Y-m-d H:i:s')
        ]);
        return redirect()->to('admin/piket/jurnal')->with('success', 'Jurnal berhasil disimpan.');
    }

    // --- FITUR IZIN KELUAR ---
    public function izin()
    {
        // Gunakan filter kelas seperti di BK agar tidak berat
        $filter_kelas = $this->request->getVar('kelas');
        
        $builder = $this->db->table('tbl_izin_keluar')
            ->select('tbl_izin_keluar.*, tbl_siswa.nama_lengkap, tbl_kelas.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_izin_keluar.siswa_id')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            ->orderBy('waktu_keluar', 'DESC');

        if ($filter_kelas) $builder->where('tbl_siswa.kelas_id', $filter_kelas);

        $data = [
            'title'      => 'Izin Keluar Siswa',
            'izin'       => $builder->get()->getResultArray(),
            'list_kelas' => $this->db->table('tbl_kelas')->get()->getResultArray(),
            'siswa'      => $filter_kelas ? $this->db->table('tbl_siswa')->where('kelas_id', $filter_kelas)->get()->getResultArray() : []
        ];
        return view('admin/piket/izin', $data);
    }

    public function saveIzin()
    {
        $this->db->table('tbl_izin_keluar')->insert([
            'siswa_id'     => $this->request->getPost('siswa_id'),
            'alasan'       => $this->request->getPost('alasan'),
            'waktu_keluar' => date('Y-m-d H:i:s'),
            'pencatat_id'  => session()->get('id_user')
        ]);
        return redirect()->to('admin/piket/izin')->with('success', 'Izin keluar berhasil dibuat.');
    }
    // Tambahkan method ini di dalam class Piket
public function cetakIzin($id)
{
    // Ambil data detail izin
    $izin = $this->db->table('tbl_izin_keluar')
        ->select('tbl_izin_keluar.*, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas, users.username as nama_pencatat')
        ->join('tbl_siswa', 'tbl_siswa.id = tbl_izin_keluar.siswa_id')
        ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
        ->join('users', 'users.id = tbl_izin_keluar.pencatat_id', 'left') // Join ke user yang input (Guru Piket)
        ->where('tbl_izin_keluar.id', $id)
        ->get()->getRowArray();

    if (!$izin) {
        return redirect()->to('admin/piket/izin')->with('error', 'Data izin tidak ditemukan.');
    }

    return view('admin/piket/cetak_izin', [
        'izin' => $izin
    ]);
}
}