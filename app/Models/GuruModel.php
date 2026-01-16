<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table            = 'tbl_guru';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // SESUAIKAN DENGAN STRUKTUR DATABASE TERBARU
    protected $allowedFields    = [
        'user_id', 'nip', 'nama_lengkap', 'gelar_depan', 'gelar_belakang',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat',
        'no_hp', 'email', 'pendidikan_terakhir', 'sertifikasi', 
        'status_guru', 'foto'
    ];

    // Dates
    protected $useTimestamps = true;
}