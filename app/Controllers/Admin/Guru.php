<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use App\Models\UserModel;

class Guru extends BaseController
{
    protected $guruModel;
    protected $userModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->userModel = new UserModel();
    }

  public function index()
    {
        // [FIXED] JOIN dengan tbl_users untuk ambil telegram_chat_id & email akun
        $guru = $this->guruModel
            ->select('tbl_guru.*, tbl_users.telegram_chat_id, tbl_users.username')
            ->join('tbl_users', 'tbl_users.id = tbl_guru.user_id', 'left') // Gabungkan tabel
            ->orderBy('tbl_guru.nama_lengkap', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Data Guru & Tenaga Pendidik',
            'guru'  => $guru
        ];
        return view('admin/guru/index', $data);
    }

    public function simpan()
{
    $id = $this->request->getVar('id');
    $nip = $this->request->getVar('nip');
    $nama = $this->request->getVar('nama_lengkap');
    $no_hp = $this->request->getVar('no_hp');
    $email = $this->request->getVar('email');
    $telegram_id = $this->request->getVar('telegram_chat_id'); // [BARU] Ambil input Telegram

    // 1. Data untuk tbl_guru
    $dataGuru = [
        'nip'                 => $nip,
        'nama_lengkap'        => $nama,
        'gelar_depan'         => $this->request->getVar('gelar_depan'),
        'gelar_belakang'      => $this->request->getVar('gelar_belakang'),
        'jenis_kelamin'       => $this->request->getVar('jenis_kelamin'),
        'tempat_lahir'        => $this->request->getVar('tempat_lahir'),
        'tanggal_lahir'       => $this->request->getVar('tanggal_lahir'),
        'alamat'              => $this->request->getVar('alamat'),
        'no_hp'               => $no_hp,
        'email'               => $email,
        'pendidikan_terakhir' => $this->request->getVar('pendidikan_terakhir'),
        'sertifikasi'         => $this->request->getVar('sertifikasi'),
        'status_guru'         => $this->request->getVar('status_guru'),
    ];

    // Handle Upload Foto
    $fileFoto = $this->request->getFile('foto');
    if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
        $namaFoto = $fileFoto->getRandomName();
        $fileFoto->move('uploads/guru', $namaFoto);
        $dataGuru['foto'] = $namaFoto;
    }

    $db = \Config\Database::connect();
    $db->transStart(); // Mulai Transaksi biar aman

    if (empty($id)) {
        // --- LOGIC TAMBAH BARU (INSERT) ---
        
        // Cek NIP duplikat
        if ($this->guruModel->where('nip', $nip)->first()) {
            return redirect()->back()->with('error', 'Gagal! NIP sudah terdaftar.');
        }

        // [FIXED] Mapping Data ke tbl_users sesuai screenshot image_23088a.png
        $userData = [
                'username'         => $nip,
                'email'            => $email ?? $nip . '@sekolah.id',
                'password'         => password_hash((string)$nip, PASSWORD_DEFAULT),
                'nama_lengkap'     => $nama,
                'nomor_wa'         => $no_hp,
                'telegram_chat_id' => $telegram_id, // [FIXED] Masukkan Telegram ID
                'role'             => 'guru',
                'active'           => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s')
        ];
        
        $this->userModel->insert($userData);
        $newUserId = $this->userModel->getInsertID(); // Ambil ID User baru

        // Simpan Data Guru (Link ke User ID)
        $dataGuru['user_id'] = $newUserId;
        $this->guruModel->insert($dataGuru);
        
        $msg = 'Guru baru & Akun Login berhasil dibuat!';

    } else {
        // --- LOGIC UPDATE (EDIT) ---
        
        // Ambil data lama untuk cek user_id
        $guruLama = $this->guruModel->find($id);
        $this->guruModel->update($id, $dataGuru);

        // Update juga data di tbl_users jika ada user_id
        if (!empty($guruLama['user_id'])) {
            $dataUserUpdate = [
                'nama_lengkap'     => $nama,
                    'email'            => $email,
                    'nomor_wa'         => $no_hp,
                    'telegram_chat_id' => $telegram_id, // [FIXED] Update Telegram ID
                    'updated_at'       => date('Y-m-d H:i:s')
            ];
            // Opsional: Jika mau reset password user saat edit, tambahkan logic disini
            $this->userModel->update($guruLama['user_id'], $dataUserUpdate);
        }

        $msg = 'Biodata Guru berhasil diperbarui!';
    }

    $db->transComplete();

    if ($db->transStatus() === FALSE) {
        return redirect()->back()->with('error', 'Database Error: Gagal menyimpan data.');
    }

    return redirect()->to(base_url('admin/guru'))->with('success', $msg);
}

    public function hapus($id)
    {
        // Ambil data guru dulu untuk dapat user_id
        $guru = $this->guruModel->find($id);
        
        if ($guru) {
            $db = \Config\Database::connect();
            $db->transStart();
            
            // 1. Hapus data di tbl_guru
            $this->guruModel->delete($id);
            
            // 2. Hapus akun login di tbl_users (Jika ada)
            if (!empty($guru['user_id'])) {
                $this->userModel->delete($guru['user_id']);
            }
            
            $db->transComplete();
            return redirect()->to(base_url('admin/guru'))->with('success', 'Data Guru & Akun Login berhasil dihapus permanen.');
        }

        return redirect()->to(base_url('admin/guru'))->with('error', 'Data tidak ditemukan.');
    }
}