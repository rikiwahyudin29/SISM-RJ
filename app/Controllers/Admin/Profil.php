<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Profil extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

   public function index() {
    $userId = session()->get('id_user') ?? session()->get('user_id');
    
    // Ambil data gabungan dari tbl_guru dan tbl_users
    $profil = $this->db->table('tbl_guru')
        ->select('tbl_guru.*, tbl_users.email, tbl_users.telegram_chat_id, tbl_users.username')
        // Gunakan COALESCE agar sinkron dengan data Dapodik (id_user) maupun Manual (user_id)
        ->join('tbl_users', 'tbl_users.id = COALESCE(tbl_guru.user_id, tbl_guru.id_user)', 'left')
        ->where('COALESCE(tbl_guru.user_id, tbl_guru.id_user)', $userId)
        ->get()->getRowArray();

    return view('guru/profil_v', ['profil' => $profil, 'title' => 'Profil Saya']);
}

 public function simpan() {
    $userId = session()->get('id_user') ?? session()->get('user_id');
    
    // Sesuaikan name field ini dengan yang ada di view profil_v.php
    $data_guru = [
        'gelar_depan'    => $this->request->getPost('gelar_depan'),
        'gelar_belakang' => $this->request->getPost('gelar_belakang'),
        'no_hp'          => $this->request->getPost('no_hp'),
        'tempat_lahir'   => $this->request->getPost('tempat_lahir'),
        'tgl_lahir'      => $this->request->getPost('tgl_lahir'),
        'ibu_kandung'    => $this->request->getPost('ibu_kandung'),
        'alamat'         => $this->request->getPost('alamat'),
    ];

    $data_user = [
        'email'            => $this->request->getPost('email'),
        'telegram_chat_id' => $this->request->getPost('telegram_chat_id'),
    ];

    $this->db->transStart();
    // Update tbl_guru (cek kedua kolom relasi)
    $this->db->table('tbl_guru')
             ->groupStart()
                ->where('user_id', $userId)
                ->orWhere('id_user', $userId)
             ->groupEnd()
             ->update($data_guru);
             
    // Update tbl_users
    $this->db->table('tbl_users')->where('id', $userId)->update($data_user);
    $this->db->transComplete();

    return redirect()->back()->with('success', 'Profil berhasil disimpan!');
}
}