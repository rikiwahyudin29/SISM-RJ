<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new \App\Models\UserModel();
        $guruModel = new \App\Models\GuruModel();

        // 1. Buat Akun Login Guru
        $userData = [
            'username'   => 'guru01',
            'password'   => password_hash('guru123', PASSWORD_DEFAULT),
            'email'      => 'budi@sekolah.sch.id',
            'nomor_wa'   => '628123456789', // Ganti WA Anda
            'role'       => 'guru',
        ];

        // Insert User dan Ambil ID-nya
        $userModel->insert($userData);
        $userID = $userModel->getInsertID(); 

        // 2. Buat Data Biodata Guru yang terhubung ke ID tadi
        $guruData = [
            'user_id'      => $userID, // <-- Ini kuncinya!
            'nip'          => '198001012022011001',
            'nama_lengkap' => 'Budi Santoso',
            'gelar_belakang'=> 'S.Pd.Kom',
            'alamat'       => 'Jl. Pendidikan No. 1'
        ];

        $guruModel->insert($guruData);
    }
}