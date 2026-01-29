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

        // 1. Ambil Filter Bulan (Default bulan ini)
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');

        // 2. Ambil Tahun Ajaran Aktif
        $ta_aktif = $this->db->table('tbl_tahun_ajaran')->where('status', 'Aktif')->get()->getRow();

        // 3. QUERY JURNAL (PAKAI LEFT JOIN AGAR LEBIH AMAN)
        // LEFT JOIN menjamin data jurnal tetap muncul meski data kelas/mapel terhapus/error
        $builder = $this->db->table('tbl_jurnal')
            ->select('tbl_jurnal.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jurnal.id_kelas', 'left') // <--- PENTING: left
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jurnal.id_mapel', 'left') // <--- PENTING: left
            ->where('tbl_jurnal.id_guru', $id_guru);

        // 4. FILTER SEMESTER (Jika TA Aktif ditemukan)
        if ($ta_aktif) {
            $builder->where('tbl_jurnal.id_tahun_ajaran', $ta_aktif->id);
        }

        // 5. FILTER BULAN
        if (!empty($bulan)) {
            $builder->like('tbl_jurnal.tanggal', $bulan, 'after');
        }

        $jurnal = $builder->orderBy('tbl_jurnal.id', 'DESC')->get()->getResultArray();

        // 6. DEBUG FLASH MESSAGE (Jika Kosong)
        // Ini akan memberi tahu kita kenapa kosong
        if (empty($jurnal)) {
            $jumlah_asli = $this->db->table('tbl_jurnal')->where('id_guru', $id_guru)->countAllResults();
            $pesan = "Data kosong. Padahal di database Guru ID $id_guru punya $jumlah_asli data total.";
            if ($ta_aktif) $pesan .= " (Filter TA ID: $ta_aktif->id).";
            session()->setFlashdata('error', $pesan);
        }

        return view('guru/jurnal/index', [
            'title'  => 'Riwayat Jurnal Mengajar',
            'jurnal' => $jurnal,
            'bulan'  => $bulan
        ]);
    }

    // 2. FORM INPUT JURNAL
   public function input()
    {
        $id_user = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user);

        // 1. AMBIL TAHUN AJARAN AKTIF (Wajib)
        $ta_aktif = $this->db->table('tbl_tahun_ajaran')
            ->where('status', 'Aktif')
            ->get()->getRow();

        if (!$ta_aktif) {
            return redirect()->to('guru/dashboard')->with('error', 'Tidak ada Tahun Ajaran aktif!');
        }

        // 2. AMBIL JADWAL HARI INI (FILTER SEMESTER AKTIF)
        // Kita tambahkan select 'id_kelas' dan 'id_mapel' agar bisa dipakai untuk pengecekan jurnal
        $jadwal = $this->db->table('tbl_jadwal')
            ->select('tbl_jadwal.id, tbl_jadwal.id_kelas, tbl_jadwal.id_mapel, tbl_mapel.nama_mapel, tbl_kelas.nama_kelas, tbl_jadwal.jam_mulai, tbl_jadwal.jam_selesai')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal.id_kelas')
            ->where('tbl_jadwal.id_guru', $id_guru)
            ->where('tbl_jadwal.hari', $this->getHariIndo(date('l'))) // Hari ini
            ->where('tbl_jadwal.id_tahun_ajaran', $ta_aktif->id)     // Filter Semester Aktif
            ->get()->getResultArray();

        // 3. CEK APAKAH SUDAH ISI JURNAL?
        // Perbaikan: Cek berdasarkan Kelas & Mapel (Bukan id_jadwal)
        foreach ($jadwal as &$j) {
            $cek = $this->db->table('tbl_jurnal')
                ->where('id_kelas', $j['id_kelas'])
                ->where('id_mapel', $j['id_mapel'])
                ->where('id_guru', $id_guru)
                ->where('tanggal', date('Y-m-d'))
                ->countAllResults();
                
            $j['sudah_isi'] = ($cek > 0);
        }

        return view('guru/jurnal/input', [
            'title'    => 'Isi Jurnal Mengajar',
            'jadwal'   => $jadwal,
            'ta_aktif' => $ta_aktif
        ]);
    }

    // 3. PROSES SIMPAN JURNAL -> REDIRECT KE ABSEN
    public function simpan()
    {
        // 1. AMBIL ID GURU & TA AKTIF
        $id_user = session()->get('id_user');
        $id_guru = $this->getRealGuruID($id_user); 

        $ta_aktif = $this->db->table('tbl_tahun_ajaran')->where('status', 'Aktif')->get()->getRow();
        
        if(!$ta_aktif) {
            return redirect()->back()->with('error', 'Gagal: Tidak ada Tahun Ajaran aktif!');
        }

        // 2. SIAPKAN DATA (REVISI: Hapus id_jadwal)
        $data = [
            'id_guru'         => $id_guru,
            'id_tahun_ajaran' => $ta_aktif->id,
            'id_kelas'        => $this->request->getPost('id_kelas'),
            'id_mapel'        => $this->request->getPost('id_mapel'),
            // 'id_jadwal'    => ...,  <-- BARIS INI KITA HAPUS KARENA KOLOMNYA TIDAK ADA
            'tanggal'         => date('Y-m-d'),
            'jam_ke'          => $this->request->getPost('jam_ke'),
            'materi'          => $this->request->getPost('materi'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'foto_kegiatan'   => '' 
        ];

        // 3. PROSES UPLOAD FOTO
        $file = $this->request->getFile('foto_kegiatan');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/jurnal', $newName);
            $data['foto_kegiatan'] = $newName;
        }

        // 4. EKSEKUSI SIMPAN
        $this->db->table('tbl_jurnal')->insert($data);
        $id_jurnal = $this->db->insertID();

        // 5. Redirect ke Absen
        return redirect()->to('guru/jurnal/absen/'.$id_jurnal)->with('success', 'Jurnal berhasil disimpan!');
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