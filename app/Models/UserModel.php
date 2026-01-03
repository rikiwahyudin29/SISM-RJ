<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Nama tabel di database
    protected $table            = 'tbl_users';
    
    // Primary key dari tabel
    protected $primaryKey       = 'id';
    
    // Auto increment diaktifkan
    protected $useAutoIncrement = true;
    
    // Data akan dikembalikan dalam bentuk Array
    protected $returnType       = 'array';
    
    // Tidak menggunakan fitur hapus sementara (soft deletes)
    protected $useSoftDeletes   = false;
    
    // Fitur keamanan untuk membatasi kolom yang bisa diisi secara massal
    protected $protectFields    = true;

    /**
     * Kolom yang diizinkan untuk diisi (Mass Assignment).
     * Pastikan 'email' dan 'nomor_wa' ada di sini agar sistem 
     * bisa membaca/menyimpan data saat Login Google & kirim OTP.
     */
    protected $allowedFields    = [
        'nama_lengkap', 'username', 'email', 'nomor_wa', 'password', 'role'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    /**
     * Pengaturan Waktu (Timestamps)
     * Mengaktifkan pencatatan otomatis created_at dan updated_at
     */
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Pengaturan Validasi (Bisa diisi jika ingin validasi otomatis di level Model)
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks (Logika yang dijalankan sebelum/sesudah query)
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    public function getRoles($userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_roles');
        // Kita ambil kode role (misal: 'guru') dan nama aslinya (misal: 'Guru Mapel')
        $builder->select('roles.role_key, roles.role_name');
        $builder->join('roles', 'roles.id = user_roles.role_id');
        $builder->where('user_roles.user_id', $userId);
        
        return $builder->get()->getResultArray();
    }
}