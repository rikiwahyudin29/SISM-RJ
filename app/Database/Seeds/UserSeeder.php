<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Pastikan Role Dasar Ada (Jaga-jaga kalau tabel roles masih kosong)
        // Menggunakan INSERT IGNORE agar tidak error jika data sudah ada
        $db->query("INSERT IGNORE INTO roles (role_key, role_name) VALUES 
            ('admin', 'Administrator'),
            ('guru', 'Guru Mapel'),
            ('siswa', 'Peserta Didik'),
            ('kepsek', 'Kepala Sekolah')
        ");

        // Ambil ID Role supaya sinkron
        $roleAdmin  = $db->table('roles')->where('role_key', 'admin')->get()->getRow()->id;
        $roleGuru   = $db->table('roles')->where('role_key', 'guru')->get()->getRow()->id;
        $roleSiswa  = $db->table('roles')->where('role_key', 'siswa')->get()->getRow()->id;

        // Password Default: 123456
        $passwordHash = password_hash('123456', PASSWORD_DEFAULT);

        // ==========================================
        // 2. BUAT AKUN ADMIN
        // ==========================================
        $dataAdmin = [
            'username'         => 'admin',
            'email'            => 'admin@sekolah.id',
            'password'         => $passwordHash,
            'nama_lengkap'     => 'Super Administrator',
            'nomor_wa'         => '081234567890', // Ganti nomor WA Bos disini kalau mau langsung tes OTP
            'telegram_chat_id' => '123456789',   // Ganti ID Telegram Bos disini
            'created_at'       => date('Y-m-d H:i:s'),
        ];
        $db->table('tbl_users')->insert($dataAdmin);
        $adminId = $db->insertID();
        
        // Assign Role Admin
        $db->table('user_roles')->insert(['user_id' => $adminId, 'role_id' => $roleAdmin]);


        // ==========================================
        // 3. BUAT AKUN GURU DUMMY
        // ==========================================
        $dataGuru = [
            'username'         => '198501012010011001', // NIP
            'email'            => 'budi.santoso@sekolah.id',
            'password'         => $passwordHash,
            'nama_lengkap'     => 'Budi Santoso, S.Pd.',
            'nomor_wa'         => '089876543210',
            'telegram_chat_id' => '', 
            'created_at'       => date('Y-m-d H:i:s'),
        ];
        $db->table('tbl_users')->insert($dataGuru);
        $guruId = $db->insertID();

        // Isi Tabel Profil Guru
        $db->table('tbl_guru')->insert([
            'user_id'       => $guruId,
            'nip'           => '198501012010011001',
            'nama_lengkap'  => 'Budi Santoso',
            'gelar_belakang'=> 'S.Pd.',
            'foto'          => 'default.png'
        ]);

        // Assign Role Guru
        $db->table('user_roles')->insert(['user_id' => $guruId, 'role_id' => $roleGuru]);


        // ==========================================
        // 4. BUAT AKUN SISWA DUMMY
        // ==========================================
        $dataSiswa = [
            'username'         => '2024001', // NIS
            'email'            => 'ani.suryani@sekolah.id',
            'password'         => $passwordHash,
            'nama_lengkap'     => 'Ani Suryani',
            'nomor_wa'         => '085678901234',
            'telegram_chat_id' => '', 
            'created_at'       => date('Y-m-d H:i:s'),
        ];
        $db->table('tbl_users')->insert($dataSiswa);
        $siswaId = $db->insertID();

        // Isi Tabel Profil Siswa (Pastikan tabel tbl_siswa sudah ada field user_id jika relasi)
        // Jika belum ada relasi user_id di tbl_siswa, skip bagian insert tbl_siswa ini
        // $db->table('tbl_siswa')->insert([...]); 

        // Assign Role Siswa
        $db->table('user_roles')->insert(['user_id' => $siswaId, 'role_id' => $roleSiswa]);
    }
}