<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Tugas extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

   public function index()
    {
        $id_user = session()->get('id_user');
        
        // 1. PERBAIKAN: Deteksi Otomatis Nama Kolom (user_id atau id_user)
        // Ini biar aman kalau struktur database beda-beda
        $kolom_user = $this->db->fieldExists('id_user', 'tbl_siswa') ? 'id_user' : 'user_id';
        
        // Ambil Data Siswa menggunakan kolom yang sudah dideteksi
        $siswa = $this->db->table('tbl_siswa')->where($kolom_user, $id_user)->get()->getRow();

        // Validasi Ekstra: Jangan redirect ke login, tapi ke dashboard saja kalau error
        if (!$siswa) {
            return redirect()->to('siswa/dashboard')->with('error', 'Data siswa tidak ditemukan. Hubungi Admin.');
        }

        // 2. Ambil Tugas Sesuai KELAS SISWA
        $tugas = $this->db->table('tbl_tugas')
            ->select('tbl_tugas.*, tbl_mapel.nama_mapel, tbl_guru.nama_lengkap as nama_guru,
                      kumpul.id as id_kumpul, kumpul.file_jawaban, kumpul.nilai, kumpul.komentar_guru, 
                      kumpul.tgl_kumpul, kumpul.status_kumpul, kumpul.catatan_siswa')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_tugas.mapel_id')
            ->join('tbl_guru', 'tbl_guru.id = tbl_tugas.guru_id', 'left')
            // Fix Query: Pastikan join kumpul pakai ID Siswa yang benar
            ->join('tbl_tugas_kumpul as kumpul', "kumpul.tugas_id = tbl_tugas.id AND kumpul.siswa_id = {$siswa->id}", 'left')
            ->where('tbl_tugas.kelas_id', $siswa->kelas_id)
            ->where('tbl_tugas.status', 1) 
            ->orderBy('tbl_tugas.created_at', 'DESC')
            ->get()->getResultArray();

        return view('siswa/tugas/index', [
            'title' => 'Tugas Sekolah',
            'siswa' => $siswa,
            'tugas' => $tugas
        ]);
    }
    public function upload()
    {
        $siswa_id = $this->request->getPost('siswa_id');
        $tugas_id = $this->request->getPost('tugas_id');
        $catatan  = $this->request->getPost('catatan_siswa');
        
        // Cek Deadline
        $tugasInfo = $this->db->table('tbl_tugas')->where('id', $tugas_id)->get()->getRow();
        $sekarang = date('Y-m-d H:i:s');
        $status_kumpul = ($sekarang > $tugasInfo->deadline) ? 'Terlambat' : 'Tepat Waktu';

        // Handle File
        $file = $this->request->getFile('file_jawaban');
        $namaFile = null;

        // Cek apakah ini update (sudah pernah kumpul)?
        $cek = $this->db->table('tbl_tugas_kumpul')
            ->where(['tugas_id' => $tugas_id, 'siswa_id' => $siswa_id])
            ->get()->getRow();

        if ($file && $file->isValid()) {
            // Hapus file lama jika ada
            if ($cek && $cek->file_jawaban && file_exists('uploads/tugas_siswa/' . $cek->file_jawaban)) {
                unlink('uploads/tugas_siswa/' . $cek->file_jawaban);
            }
            
            $namaFile = $file->getRandomName();
            $file->move('uploads/tugas_siswa', $namaFile);
        } else {
            // Jika tidak upload file baru, pakai file lama (kalau update)
            $namaFile = $cek ? $cek->file_jawaban : null;
        }

        $data = [
            'tugas_id'      => $tugas_id,
            'siswa_id'      => $siswa_id,
            'catatan_siswa' => $catatan,
            'file_jawaban'  => $namaFile,
            'tgl_kumpul'    => $sekarang,
            'status_kumpul' => $status_kumpul
        ];

        if ($cek) {
            // UPDATE
            $this->db->table('tbl_tugas_kumpul')->where('id', $cek->id)->update($data);
            $msg = 'Jawaban berhasil diperbarui!';
        } else {
            // INSERT BARU
            $this->db->table('tbl_tugas_kumpul')->insert($data);
            $msg = 'Jawaban berhasil dikirim!';
        }

        return redirect()->to('siswa/tugas')->with('success', $msg);
    }
}