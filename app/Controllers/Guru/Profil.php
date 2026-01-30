<?php
namespace App\Controllers\Guru;
use App\Controllers\BaseController;

class Profil extends BaseController {
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index() {
        // Ambil ID User dari session login
        $userId = session()->get('id_user') ?? session()->get('user_id');
        
        // Query Join menggunakan COALESCE agar sinkron dengan Dapodik (id_user) maupun Manual (user_id)
        $profil = $this->db->table('tbl_guru')
            ->select('tbl_guru.*, tbl_users.email, tbl_users.telegram_chat_id, tbl_users.username')
            ->join('tbl_users', 'tbl_users.id = COALESCE(tbl_guru.user_id, tbl_guru.id_user)', 'left')
            ->where('COALESCE(tbl_guru.user_id, tbl_guru.id_user)', $userId)
            ->get()->getRowArray();

        $data = [
            'profil' => $profil,
            'title'  => 'Profil Saya'
        ];

        return view('guru/profil_v', $data);
    }

    public function simpan() {
        $userId = session()->get('id_user') ?? session()->get('user_id');
        
        // Data Profil (Tabel Guru)
        $data_guru = [
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tgl_lahir'    => $this->request->getPost('tgl_lahir'),
            'alamat'       => $this->request->getPost('alamat'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'ibu_kandung'  => $this->request->getPost('ibu_kandung'),
            'gelar_depan'  => $this->request->getPost('gelar_depan'),
            'gelar_belakang'=> $this->request->getPost('gelar_belakang'),
        ];

        // Data Akun (Tabel Users)
        $data_user = [
            'email'            => $this->request->getPost('email'),
            'telegram_chat_id' => $this->request->getPost('telegram_chat_id'),
        ];

        // Jalankan Update
        $this->db->transStart();
        $this->db->table('tbl_guru')->where('user_id', $userId)->orWhere('id_user', $userId)->update($data_guru);
        $this->db->table('tbl_users')->where('id', $userId)->update($data_user);
        $this->db->transComplete();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}