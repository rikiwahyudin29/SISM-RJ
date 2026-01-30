<?php
namespace App\Controllers\Siswa;
use App\Controllers\BaseController;

class Profil extends BaseController {
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index() {
    $userId = session()->get('id_user') ?? session()->get('user_id');
    
    // Query Gabungan sesuai struktur tabel siswa Anda
    $profil = $this->db->table('tbl_siswa')
        ->select('tbl_siswa.*, tbl_users.email, tbl_users.telegram_chat_id, tbl_users.username')
        // Relasi utama menggunakan id_user sesuai database Anda
        ->join('tbl_users', 'tbl_users.id = COALESCE(tbl_siswa.id_user, tbl_siswa.user_id)', 'left')
        ->where('COALESCE(tbl_siswa.id_user, tbl_siswa.user_id)', $userId)
        ->get()->getRowArray();

    return view('siswa/profil_v', ['profil' => $profil, 'title' => 'Profil Saya']);
}

public function simpan() {
    $userId = session()->get('id_user') ?? session()->get('user_id');
    
    // SINKRONISASI DATA SESUAI PERMINTAAN
    $nomor_hp = $this->request->getPost('no_hp');
    $email_baru = $this->request->getPost('email');

    // 1. Data untuk tbl_siswa
    $data_siswa = [
        'no_hp_siswa'  => $nomor_hp, // Masuk ke tbl_siswa
        'tempat_lahir' => $this->request->getPost('tempat_lahir'),
        'tanggal_lahir'=> $this->request->getPost('tgl_lahir'), // Sesuaikan kolom tgl_lahir/tanggal_lahir
        'alamat'       => $this->request->getPost('alamat'),
    ];

    // 2. Data untuk tbl_users
    $data_user = [
        'email'            => $email_baru, // Email masuk ke tbl_users
        'nomor_wa'         => $nomor_hp,   // Nomor HP juga masuk ke tbl_users
        'telegram_chat_id' => $this->request->getPost('telegram_chat_id'),
    ];

    $this->db->transStart();
    
    // Update profil di tbl_siswa (Cek kedua kolom relasi agar tidak meleset)
    $this->db->table('tbl_siswa')
             ->groupStart()
                ->where('id_user', $userId)
                ->orWhere('user_id', $userId)
             ->groupEnd()
             ->update($data_siswa);
             
    // Update akun di tbl_users
    $this->db->table('tbl_users')->where('id', $userId)->update($data_user);
    
    $this->db->transComplete();

    return redirect()->back()->with('success', 'Profil Siswa Berhasil Disinkronkan!');
}
}