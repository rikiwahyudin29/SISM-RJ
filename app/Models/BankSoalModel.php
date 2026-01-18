<?php

namespace App\Models;

use CodeIgniter\Model;

class BankSoalModel extends Model
{
    protected $table            = 'tbl_bank_soal';
    protected $primaryKey       = 'id';
    // Hapus id_kelas & id_jurusan karena sudah pakai tabel pivot
    protected $allowedFields    = ['kode_bank', 'judul_ujian', 'id_mapel', 'id_guru', 'jumlah_soal', 'status'];
    protected $useTimestamps    = true;

    // FUNGSI 1: Ambil Data Bank Soal Lengkap untuk Tampilan Tabel
    public function getBankLengkap($idGuru = null)
    {
        // Kita gunakan Subquery untuk menggabungkan nama-nama kelas menjadi satu string (misal: "X RPL 1, X RPL 2")
        // Ini pengganti JOIN biasa karena sekarang hubungannya Many-to-Many
        $subQueryKelas = "(SELECT GROUP_CONCAT(k.nama_kelas SEPARATOR ', ') 
                           FROM tbl_bank_soal_kelas bsk 
                           JOIN tbl_kelas k ON k.id = bsk.id_kelas 
                           WHERE bsk.id_bank_soal = tbl_bank_soal.id)";

        $builder = $this->select("tbl_bank_soal.*, tbl_mapel.nama_mapel, tbl_guru.nama_lengkap as nama_guru, $subQueryKelas as nama_kelas")
                        ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
                        ->join('tbl_guru', 'tbl_guru.id = tbl_bank_soal.id_guru');
        
        if($idGuru) {
            $builder->where('tbl_bank_soal.id_guru', $idGuru);
        }

        return $builder->orderBy('id', 'DESC')->findAll();
    }

    // FUNGSI 2: Ambil Daftar ID Kelas untuk keperluan Edit (Centang Checkbox otomatis)
    public function getTargetKelas($idBankSoal)
    {
        $db = \Config\Database::connect();
        return $db->table('tbl_bank_soal_kelas')
                  ->select('id_kelas')
                  ->where('id_bank_soal', $idBankSoal)
                  ->get()->getResultArray(); 
    }
}