<?php

namespace App\Controllers;

class Guru extends BaseController
{
    // Di Controller Guru.php
public function index()
{
    $guruModel = new \App\Models\GuruModel();
    
    // Ambil User ID yang sedang login
    $userId = session()->get('user_id');

    // Cari profil guru berdasarkan user_id
    $profil = $guruModel->where('user_id', $userId)->first();

    $data = [
        'title' => 'Dashboard Guru',
        // Jika profil belum diisi, pakai username sebagai cadangan
        'nama'  => $profil['nama_lengkap'] ?? session()->get('username'),
        'nip'   => $profil['nip'] ?? '-',
        'gelar' => $profil['gelar_belakang'] ?? ''
    ];

    return view('guru/dashboard', $data);
}
public function update_profile()
{
    $id = session()->get('id_user');
    $password = $this->request->getPost('password');
    $confirm  = $this->request->getPost('confirm_password');

    if (!empty($password)) {
        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok!');
        }
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        $db->table('tbl_users')->where('id', $id)->update($data);
    }

    // Logic Upload Foto juga bisa ditaruh di sini
    
    return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
}
}