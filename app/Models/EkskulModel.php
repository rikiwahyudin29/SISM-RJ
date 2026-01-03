<?php

namespace App\Models;

use CodeIgniter\Model;

class EkskulModel extends Model
{
    protected $table            = 'tbl_ekskul';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_ekskul', 'guru_id', 'hari', 'jam', 'foto'];

    // QUERY SAKTI (PERBAIKAN)
    public function getEkskulLengkap()
    {
        return $this->select('tbl_ekskul.*, tbl_guru.nama as nama_pembina') // <--- SUDAH DIGANTI JADI 'nama'
            ->join('tbl_guru', 'tbl_guru.id = tbl_ekskul.guru_id', 'left')
            ->select('(SELECT COUNT(*) FROM tbl_anggota_ekskul WHERE ekskul_id = tbl_ekskul.id) as jumlah_peserta')
            ->findAll();
    }

    public function getAnggota($ekskul_id)
    {
        return $this->db->table('tbl_anggota_ekskul')
            // --- BAGIAN INI KITA LENGKAPI ---
            // Kita ambil nis DAN nisn biar aman mau dipanggil yang mana aja di view
            ->select('tbl_siswa.nama, tbl_siswa.nis, tbl_siswa.nisn, tbl_kelas.nama_kelas') 
            // --------------------------------
            
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_anggota_ekskul.siswa_id')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
            ->where('tbl_anggota_ekskul.ekskul_id', $ekskul_id)
            ->get()->getResultArray();
    }
}