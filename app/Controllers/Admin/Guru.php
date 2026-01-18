<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use App\Models\UserModel; // Model Custom Bos
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Guru extends BaseController
{
    protected $guruModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->userModel = new UserModel(); 
        $this->db = \Config\Database::connect();
    }

   public function index()
    {
        // PERBAIKAN: Tambahkan 'tbl_users.telegram_chat_id' dan 'tbl_users.nomor_wa' di select
        $guru = $this->guruModel
                     ->select('tbl_guru.*, tbl_users.username, tbl_users.email as email_login, tbl_users.telegram_chat_id, tbl_users.nomor_wa') 
                     ->join('tbl_users', 'tbl_users.id = tbl_guru.user_id', 'left')
                     ->findAll();

        $data = [
            'title' => 'Data Guru',
            'guru'  => $guru
        ];
        return view('admin/guru/index', $data);
    }
    // --- TAMBAH / EDIT MANUAL ---
    public function simpan()
    {
        $id = $this->request->getPost('id');
        
        $dataGuru = [
            'nip'           => $this->request->getPost('nip'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'email'         => $this->request->getPost('email'),
            'alamat'        => $this->request->getPost('alamat'),
            'status_guru'   => 'Aktif'
        ];

        // Mulai Transaksi Database
        $this->db->transStart();

        if ($id) {
            // --- EDIT DATA ---
            $this->guruModel->update($id, $dataGuru);
            
            // Update nama di tabel user juga biar sinkron
            $guruExisting = $this->guruModel->find($id);
            if ($guruExisting && $guruExisting['user_id']) {
                $this->userModel->update($guruExisting['user_id'], [
                    'nama_lengkap' => $dataGuru['nama_lengkap']
                ]);
            }
            
            $msg = 'Data Guru berhasil diperbarui!';
        } else {
            // --- TAMBAH BARU ---
            
            // 1. SIAPKAN DATA USER (Login)
            $userData = [
                'nama_lengkap'     => $dataGuru['nama_lengkap'],
                'username'         => $dataGuru['nip'], // Username pakai NIP
                'email'            => $dataGuru['email'] ?? $dataGuru['nip'].'@guru.sekolah.id',
                'password'         => password_hash('123456', PASSWORD_DEFAULT), // Pass Default: 123456
                'role'             => 'guru',
                'nomor_wa'         => $dataGuru['no_hp'] ?? '',
                'telegram_chat_id' => null
            ];

            $this->userModel->insert($userData);
            $newUserId = $this->userModel->getInsertID();

            // 2. MASUKKAN HAK AKSES (ROLE ID = 8)
            // Kita pakai angka 8 sesuai info Bos
            $this->db->table('user_roles')->insert([
                'user_id' => $newUserId,
                'role_id' => 8  // <--- KUNCI DISINI (Role Guru Mapel)
            ]);

            // 3. SIMPAN DATA PROFIL GURU
            $dataGuru['user_id'] = $newUserId;
            $this->guruModel->insert($dataGuru);
            
            $msg = 'Guru baru berhasil ditambahkan beserta Akun Login!';
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan. Pastikan NIP belum terdaftar.');
        }

        return redirect()->to(base_url('admin/guru'))->with('success', $msg);
    }

    // --- IMPORT EXCEL ---
    public function import()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = $file->getClientExtension();
            $reader = ('xls' == $ext) ? new \PhpOffice\PhpSpreadsheet\Reader\Xls() : new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet()->toArray();
            $jumlahSukses = 0;

            // ID Role Guru Mapel
            $roleIdGuru = 8; 

            $this->db->transStart();

            foreach ($sheet as $key => $row) {
                if ($key == 0) continue; // Lewati Baris Header

                $nip  = $row[0]; 
                $nama = $row[1];
                $jk   = $row[2]; 
                
                // Skip jika NIP kosong atau sudah ada
                if(empty($nip) || $this->guruModel->where('nip', $nip)->first()) continue;

                // 1. Buat User
                $this->userModel->insert([
                    'nama_lengkap'     => $nama,
                    'username'         => $nip,
                    'email'            => $nip.'@guru.sekolah.id',
                    'password'         => password_hash('123456', PASSWORD_DEFAULT),
                    'role'             => 'guru',
                    'nomor_wa'         => '',
                    'telegram_chat_id' => null
                ]);
                $newUserId = $this->userModel->getInsertID();

                // 2. Beri Hak Akses (Role ID 8)
                $this->db->table('user_roles')->insert([
                    'user_id' => $newUserId,
                    'role_id' => $roleIdGuru // <--- Pakai ID 8
                ]);

                // 3. Buat Data Guru
                $this->guruModel->insert([
                    'user_id'       => $newUserId,
                    'nip'           => $nip,
                    'nama_lengkap'  => $nama,
                    'jenis_kelamin' => $jk,
                    'status_guru'   => 'Aktif'
                ]);

                $jumlahSukses++;
            }

            $this->db->transComplete();
            return redirect()->to(base_url('admin/guru'))->with('success', "Berhasil import $jumlahSukses data guru!");
        }

        return redirect()->back()->with('error', 'File excel tidak valid.');
    }

    public function hapus($id)
    {
        $guru = $this->guruModel->find($id);
        if($guru) {
            $this->db->transStart();
            
            // Hapus Akun Login & Role jika ada
            if(!empty($guru['user_id'])) {
                $this->db->table('user_roles')->where('user_id', $guru['user_id'])->delete();
                $this->userModel->delete($guru['user_id']);
            }
            
            // Hapus Data Guru
            $this->guruModel->delete($id);
            $this->db->transComplete();
        }
        return redirect()->to(base_url('admin/guru'))->with('success', 'Data guru berhasil dihapus.');
    }
}