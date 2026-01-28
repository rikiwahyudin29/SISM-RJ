<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Jurnal extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Helper: Cari ID Guru Asli
    private function getRealGuruID($id_user_login)
    {
        $kolom_user = $this->db->fieldExists('id_user', 'tbl_guru') ? 'id_user' : 'user_id';
        if ($this->db->fieldExists($kolom_user, 'tbl_guru')) {
            $guru = $this->db->table('tbl_guru')->where($kolom_user, $id_user_login)->get()->getRow();
            if ($guru) return $guru->id;
        }
        return $id_user_login; 
    }

    // 1. HALAMAN RIWAYAT JURNAL
    public function index()
    {
        $id_user = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user);
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');

        $data = $this->db->table('tbl_jurnal')
            ->select('tbl_jurnal.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jurnal.id_kelas')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jurnal.id_mapel')
            ->where('tbl_jurnal.id_guru', $id_guru)
            ->like('tbl_jurnal.tanggal', $bulan)
            ->orderBy('tbl_jurnal.tanggal', 'DESC')
            ->orderBy('tbl_jurnal.jam_ke', 'DESC')
            ->get()->getResultArray();

        return view('guru/jurnal/index', [
            'title' => 'Jurnal Mengajar',
            'data'  => $data,
            'bulan' => $bulan
        ]);
    }

    // 2. FORM INPUT JURNAL
    public function input()
    {
        // ... (Logic Input masih sama) ...
        $id_user = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user);
        
        // Ambil Jadwal Hari Ini
        $hari_ini = $this->getHariIndo(date('l'));
        $jadwal_hari_ini = $this->db->table('tbl_jadwal')
            ->select('tbl_jadwal.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal.id_kelas')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
            ->where('id_guru', $id_guru)
            ->where('hari', $hari_ini)
            ->orderBy('jam_mulai', 'ASC')
            ->get()->getResultArray();

        return view('guru/jurnal/input', ['title' => 'Isi Jurnal KBM', 'jadwal' => $jadwal_hari_ini]);
    }

    // 3. PROSES SIMPAN JURNAL -> REDIRECT KE ABSEN
    public function simpan()
    {
        $id_user = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user);

        $file = $this->request->getFile('foto');
        $namaFile = null;
        if ($file && $file->isValid()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/jurnal', $namaFile);
        }

        $data = [
            'id_guru'       => $id_guru,
            'id_kelas'      => $this->request->getPost('id_kelas'),
            'id_mapel'      => $this->request->getPost('id_mapel'),
            'tanggal'       => $this->request->getPost('tanggal'),
            'jam_ke'        => $this->request->getPost('jam_ke'),
            'materi'        => $this->request->getPost('materi'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'foto_kegiatan' => $namaFile
        ];

        $this->db->table('tbl_jurnal')->insert($data);
        
        // --- PERUBAHAN: AMBIL ID JURNAL BARU & REDIRECT KE ABSEN ---
        $id_jurnal_baru = $this->db->insertID();
        
        return redirect()->to('guru/jurnal/absen/' . $id_jurnal_baru);
    }

    // 4. HALAMAN ABSEN SISWA (PER MAPEL)
    public function absen($id_jurnal)
    {
        // Ambil Data Jurnal
        $jurnal = $this->db->table('tbl_jurnal')
            ->select('tbl_jurnal.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jurnal.id_kelas')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jurnal.id_mapel')
            ->where('tbl_jurnal.id', $id_jurnal)
            ->get()->getRowArray();

        if(!$jurnal) return redirect()->to('guru/jurnal');

        // Cek Nama Kolom Kelas ID
        $kolom_kelas = $this->db->fieldExists('kelas_id', 'tbl_siswa') ? 'kelas_id' : 'id_kelas';

        // Ambil Siswa di Kelas Tersebut
        $siswa = $this->db->table('tbl_siswa')
            ->where($kolom_kelas, $jurnal['id_kelas'])
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // Cek apakah sudah pernah absen sebelumnya? (Untuk Edit)
        $existing = $this->db->table('tbl_absensi_mapel')->where('id_jurnal', $id_jurnal)->get()->getResultArray();
        $absen_data = [];
        foreach($existing as $e) $absen_data[$e['id_siswa']] = $e['status'];

        return view('guru/jurnal/absen', [
            'jurnal' => $jurnal,
            'siswa'  => $siswa,
            'existing' => $absen_data
        ]);
    }

    // 5. SIMPAN ABSEN
    public function simpan_absen()
    {
        $id_jurnal = $this->request->getPost('id_jurnal');
        $status    = $this->request->getPost('status'); // Array [id_siswa => status]

        if(!empty($status)) {
            // Hapus data lama biar bersih (update logic)
            $this->db->table('tbl_absensi_mapel')->where('id_jurnal', $id_jurnal)->delete();

            $batch = [];
            foreach($status as $id_siswa => $st) {
                $batch[] = [
                    'id_jurnal' => $id_jurnal,
                    'id_siswa'  => $id_siswa,
                    'status'    => $st
                ];
            }
            $this->db->table('tbl_absensi_mapel')->insertBatch($batch);
        }

        return redirect()->to('guru/jurnal')->with('success', 'Jurnal & Absensi berhasil disimpan!');
    }

    // 6. DETAIL ABSEN (UNTUK MODAL POPUP DI INDEX)
    public function get_detail_absen($id_jurnal)
    {
        $data = $this->db->table('tbl_absensi_mapel')
            ->select('tbl_siswa.nama_lengkap, tbl_absensi_mapel.status')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_absensi_mapel.id_siswa')
            ->where('id_jurnal', $id_jurnal)
            ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();
            
        return $this->response->setJSON($data);
    }
    
    // Helper hari
    private function getHariIndo($day) {
        $hari = ['Sunday'=>'Minggu', 'Monday'=>'Senin', 'Tuesday'=>'Selasa', 'Wednesday'=>'Rabu', 'Thursday'=>'Kamis', 'Friday'=>'Jumat', 'Saturday'=>'Sabtu'];
        return $hari[$day] ?? 'Senin';
    }

    public function hapus($id) {
        $this->db->table('tbl_jurnal')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data dihapus.');
    }
}