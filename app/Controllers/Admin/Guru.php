<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use App\Models\UserModel;
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
        // === JURUS PAMUNGKAS (COALESCE) ===
        // Logika: Ambil User ID dari 'user_id'. Jika NULL, ambil dari 'id_user'.
        // Ini mengatasi data yang terpecah di dua kolom berbeda.
        
        $guru = $this->db->table('tbl_guru')
            ->select('tbl_guru.*, tbl_users.email, tbl_users.telegram_chat_id') 
            ->join('tbl_users', 'tbl_users.id = COALESCE(tbl_guru.user_id, tbl_guru.id_user)', 'left') 
            ->orderBy('tbl_guru.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Data Guru',
            'guru'  => $guru
        ];

        return view('admin/guru/index', $data);
    }

    // --- TAMBAH / EDIT MANUAL ---
    public function simpan()
    {
        $id_guru = $this->request->getPost('id');
        
        // Data untuk tabel Guru
        $data_guru = [
            'nip'                => $this->request->getPost('nip'),
            'nik'                => $this->request->getPost('nik'),
            'nuptk'              => $this->request->getPost('nuptk'),
            'nama_lengkap'       => $this->request->getPost('nama_lengkap'),
            'gelar_depan'        => $this->request->getPost('gelar_depan'),
            'gelar_belakang'     => $this->request->getPost('gelar_belakang'),
            'tempat_lahir'       => $this->request->getPost('tempat_lahir'),
            'tgl_lahir'          => $this->request->getPost('tgl_lahir'),
            'jenis_kelamin'      => $this->request->getPost('jk'), 
            'ibu_kandung'        => $this->request->getPost('ibu_kandung'),
            'alamat'             => $this->request->getPost('alamat'),
            'no_hp'              => $this->request->getPost('no_hp'),
            'pendidikan_terakhir'=> $this->request->getPost('pendidikan_terakhir'),
            'status_kepegawaian' => $this->request->getPost('status_kepegawaian'),
            'rfid_uid'           => $this->request->getPost('rfid_uid'),
        ];

        // Data untuk tabel Users
        $data_user = [
            'telegram_chat_id' => $this->request->getPost('telegram_chat_id'),
            'email'            => $this->request->getPost('email'),
        ];

        if (empty($id_guru)) {
            // === INSERT BARU (MANUAL) ===
            // Default masuk ke kolom 'user_id' biar rapi
            
            $username = !empty($data_guru['nik']) ? $data_guru['nik'] : $data_guru['nip'];
            if(empty($username)) $username = 'GURU'.rand(100,999);

            $this->userModel->insert([
                'nama_lengkap'     => $data_guru['nama_lengkap'],
                'username'         => $username,
                'password'         => password_hash('123456', PASSWORD_DEFAULT),
                'role'             => 'guru',
                'email'            => $data_user['email'],
                'telegram_chat_id' => $data_user['telegram_chat_id'],
                'active'           => 1
            ]);
            
            $newUserId = $this->userModel->getInsertID();

            // Simpan ke 'user_id' (Kolom standar manual)
            $data_guru['user_id'] = $newUserId; 
            $this->guruModel->insert($data_guru);

            // Tambah Role
            $this->db->table('user_roles')->insert([
                'user_id' => $newUserId,
                'role_id' => 8 
            ]);

        } else {
            // === UPDATE DATA ===
            
            // 1. Update profil guru
            $this->db->table('tbl_guru')->where('id', $id_guru)->update($data_guru);
            
            // 2. Cari USER ID (Cek kedua kolom: user_id ATAU id_user)
            $guru_exist = $this->db->table('tbl_guru')->where('id', $id_guru)->get()->getRow();
            
            // Logika Detektif: Pakai user_id kalau ada, kalau kosong pakai id_user
            $target_user_id = !empty($guru_exist->user_id) ? $guru_exist->user_id : $guru_exist->id_user;
            
            if (!empty($target_user_id)) {
                // 3. Update Email & Telegram di user yang ketemu
                $this->db->table('tbl_users')->where('id', $target_user_id)->update($data_user);
            }
        }

        return redirect()->to(base_url('admin/guru'))->with('success', 'Data Guru Berhasil Disimpan!');
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
            $roleIdGuru = 8; 

            $this->db->transStart();

            foreach ($sheet as $key => $row) {
                if ($key == 0) continue; 

                $nip  = $row[0]; 
                $nama = $row[1];
                $jk   = $row[2]; 
                
                if(empty($nip) || $this->guruModel->where('nip', $nip)->first()) continue;

                $this->userModel->insert([
                    'nama_lengkap'     => $nama,
                    'username'         => $nip,
                    'email'            => $nip.'@guru.sekolah.id',
                    'password'         => password_hash('123456', PASSWORD_DEFAULT),
                    'role'             => 'guru',
                    'active'           => 1
                ]);
                $newUserId = $this->userModel->getInsertID();

                $this->db->table('user_roles')->insert([
                    'user_id' => $newUserId,
                    'role_id' => $roleIdGuru
                ]);

                // Simpan ke user_id (Format Manual)
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
            
            // Logika Detektif Hapus: Cek kedua kolom
            $target_user_id = !empty($guru['user_id']) ? $guru['user_id'] : $guru['id_user'];

            if(!empty($target_user_id)) {
                $this->db->table('user_roles')->where('user_id', $target_user_id)->delete();
                $this->userModel->delete($target_user_id);
            }
            
            $this->guruModel->delete($id);
            $this->db->transComplete();
        }
        return redirect()->to(base_url('admin/guru'))->with('success', 'Data guru berhasil dihapus.');
    }
}