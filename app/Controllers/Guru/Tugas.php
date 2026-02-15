<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Tugas extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Fungsi BANTUAN: Ambil ID Guru yang Valid
    private function getGuruId()
    {
        // 1. Cek Session Dulu
        $id_guru = session()->get('id_guru');
        
        // 2. Kalau Session Kosong, Cari Manual via ID User Login
        if (!$id_guru) {
            $user_id = session()->get('id_user');
            $guru = $this->db->table('tbl_guru')->where('user_id', $user_id)->get()->getRow();
            
            if ($guru) {
                $id_guru = $guru->id;
                session()->set('id_guru', $id_guru); // Simpan lagi ke session biar aman
            }
        }
        return $id_guru;
    }

    public function index()
    {
        $id_guru = $this->getGuruId();

        // VALIDASI: Kalau masih gak ketemu juga, tendang ke login (berarti akun error)
        if (!$id_guru) {
            return redirect()->to('login')->with('error', 'Data Guru tidak ditemukan. Hubungi Admin.');
        }

        // 1. Ambil Data Tugas
        $tugas = $this->db->table('tbl_tugas')
            ->select('tbl_tugas.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel, 
                     (SELECT COUNT(*) FROM tbl_tugas_kumpul WHERE tbl_tugas_kumpul.tugas_id = tbl_tugas.id) as total_kumpul')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_tugas.kelas_id')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_tugas.mapel_id')
            ->where('tbl_tugas.guru_id', $id_guru)
            ->orderBy('tbl_tugas.created_at', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Manajemen Tugas',
            'tugas' => $tugas,
            'kelas' => $this->db->table('tbl_kelas')->get()->getResultArray(),
            'mapel' => $this->db->table('tbl_mapel')->get()->getResultArray(),
            'guru'  => (object)['id' => $id_guru] // Kirim object ID Guru ke View
        ];

        return view('guru/tugas/index', $data);
    }

    public function save()
    {
        // AMBIL ID GURU LAGI (Biar gak bergantung 100% sama input hidden form)
        $id_guru = $this->getGuruId();
        
        if (!$id_guru) {
             return redirect()->back()->with('error', 'Gagal menyimpan. ID Guru tidak teridentifikasi.');
        }

        // Handle File
        $file = $this->request->getFile('file_pendukung');
        $namaFile = null;

        if ($file && $file->isValid()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/tugas', $namaFile);
        }

        // Simpan
        $this->db->table('tbl_tugas')->insert([
            'guru_id'        => $id_guru, // Pakai ID dari fungsi getGuruId(), bukan dari Post form
            'kelas_id'       => $this->request->getPost('kelas_id'),
            'mapel_id'       => $this->request->getPost('mapel_id'),
            'judul'          => $this->request->getPost('judul'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'deadline'       => $this->request->getPost('deadline'),
            'file_pendukung' => $namaFile,
            'status'         => 1
        ]);

        return redirect()->to('guru/tugas')->with('success', 'Tugas berhasil diterbitkan!');
    }

    public function delete($id)
    {
        // Hapus File Fisik
        $tugas = $this->db->table('tbl_tugas')->where('id', $id)->get()->getRow();
        if ($tugas && $tugas->file_pendukung && file_exists('uploads/tugas/' . $tugas->file_pendukung)) {
            unlink('uploads/tugas/' . $tugas->file_pendukung);
        }

        // Hapus Data (Cascading manual ke tabel pengumpulan siswa dulu)
        $this->db->table('tbl_tugas_kumpul')->where('tugas_id', $id)->delete();
        $this->db->table('tbl_tugas')->where('id', $id)->delete();

        return redirect()->to('guru/tugas')->with('success', 'Tugas berhasil dihapus.');
    }
    public function hasil($tugas_id)
    {
        // Ambil Data Tugas
        $tugas = $this->db->table('tbl_tugas')
            ->select('tbl_tugas.*, tbl_kelas.nama_kelas, tbl_mapel.nama_mapel')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_tugas.kelas_id')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_tugas.mapel_id')
            ->where('tbl_tugas.id', $tugas_id)
            ->get()->getRow();

        if (!$tugas) return redirect()->to('guru/tugas')->with('error', 'Tugas tidak ditemukan');

        // Ambil Semua Siswa di Kelas tersebut
        $siswa = $this->db->table('tbl_siswa')
            ->select('id, nama_lengkap, nis')
            ->where('kelas_id', $tugas->kelas_id)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // Ambil Data Pengumpulan (Jawaban Siswa)
        $kumpul_raw = $this->db->table('tbl_tugas_kumpul')
            ->where('tugas_id', $tugas_id)
            ->get()->getResultArray();
        
        // Mapping Data Kumpul biar mudah dipanggil: $data[siswa_id]
        $pengumpulan = [];
        foreach ($kumpul_raw as $k) {
            $pengumpulan[$k['siswa_id']] = $k;
        }

        return view('guru/tugas/hasil', [
            'title' => 'Koreksi Tugas',
            'tugas' => $tugas,
            'siswa' => $siswa,
            'pengumpulan' => $pengumpulan
        ]);
    }

    // 5. Proses Simpan Nilai
    public function nilai()
    {
        $tugas_id = $this->request->getPost('tugas_id');
        $siswa_id = $this->request->getPost('siswa_id');
        $nilai    = $this->request->getPost('nilai');
        $komentar = $this->request->getPost('komentar_guru'); // Sesuaikan DB: komentar_guru

        // Cek apakah siswa sudah kumpul? (Harusnya sudah, kalau belum kumpul masa dinilai?)
        // Tapi kita buat fleksibel: Kalau belum kumpul tapi guru mau kasih nilai 0, sistem akan insert.
        $cek = $this->db->table('tbl_tugas_kumpul')
            ->where(['tugas_id' => $tugas_id, 'siswa_id' => $siswa_id])
            ->countAllResults();

        $data = [
            'nilai'          => $nilai,
            'komentar_guru'  => $komentar
        ];

        if ($cek > 0) {
            $this->db->table('tbl_tugas_kumpul')
                ->where(['tugas_id' => $tugas_id, 'siswa_id' => $siswa_id])
                ->update($data);
        } else {
            // Kasus khusus: Siswa belum upload tapi guru maksa nilai (misal nilai 0 karena nyontek/tidak kumpul)
            $data['tugas_id'] = $tugas_id;
            $data['siswa_id'] = $siswa_id;
            $data['status_kumpul'] = 'Terlambat'; // Asumsi
            $this->db->table('tbl_tugas_kumpul')->insert($data);
        }

        return redirect()->to('guru/tugas/hasil/'.$tugas_id)->with('success', 'Nilai berhasil disimpan.');
    }
}