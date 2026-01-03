<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    // Pastikan nama tabel ini SAMA dengan yang di PHPMyAdmin
    // (Berdasarkan screenshot kamu sebelumnya, namanya 'tbl_guru')
    protected $table            = 'tbl_guru'; 
    
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // --- BAGIAN INI YANG BIKIN ERROR ---
    // Kita harus daftarkan SEMUA kolom yang mau disimpan
    protected $allowedFields    = [
        'user_id', 
        'nip', 
        'nama_lengkap', 
        'gelar_depan',     // <--- Sering lupa dimasukkan
        'gelar_belakang',  // <--- Sering lupa dimasukkan
        'jenis_kelamin', 
        'alamat', 
        'foto'             // <--- Wajib ada biar fotonya tersimpan
    ];

    // --- TIMESTAMPS ---
    // Pastikan di tabel 'tbl_guru' sudah ada kolom created_at & updated_at
    // (Lihat screenshot lama kamu, sepertinya sudah ada)
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}