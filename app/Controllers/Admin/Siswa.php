<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\JurusanModel;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $userModel;
    protected $kelasModel;
    protected $jurusanModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->userModel = new UserModel();
        $this->kelasModel = new KelasModel();
        $this->jurusanModel = new JurusanModel();
    }

    public function index()
    {
        // JOIN Table: Siswa + User (Telegram) + Kelas + Jurusan
        $siswa = $this->siswaModel
            ->select('tbl_siswa.*, tbl_users.telegram_chat_id, tbl_kelas.nama_kelas, tbl_jurusan.nama_jurusan')
            ->join('tbl_users', 'tbl_users.id = tbl_siswa.user_id', 'left')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
            ->join('tbl_jurusan', 'tbl_jurusan.id = tbl_siswa.jurusan_id', 'left')
            ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
            ->findAll();

        $data = [
            'title'   => 'Data Peserta Didik',
            'siswa'   => $siswa,
            'kelas'   => $this->kelasModel->findAll(),   // Untuk Dropdown di Modal
            'jurusan' => $this->jurusanModel->findAll()  // Untuk Dropdown di Modal
        ];

        return view('admin/siswa/index', $data);
    }

    public function simpan()
    {
        $id = $this->request->getVar('id');
        $nisn = $this->request->getVar('nisn');
        $nama = $this->request->getVar('nama_lengkap');
        $telegram_id = $this->request->getVar('telegram_chat_id');
        $email = $this->request->getVar('email_siswa');
        $hp_siswa = $this->request->getVar('no_hp_siswa');

        // Data Siswa
        $dataSiswa = [
            'nisn'           => $nisn,
            'nis'            => $this->request->getVar('nis'),
            'nama_lengkap'   => $nama,
            'kelas_id'       => $this->request->getVar('kelas_id'),
            'jurusan_id'     => $this->request->getVar('jurusan_id'),
            'jenis_kelamin'  => $this->request->getVar('jenis_kelamin'),
            'tempat_lahir'   => $this->request->getVar('tempat_lahir'),
            'tanggal_lahir'  => $this->request->getVar('tanggal_lahir'),
            'agama'          => $this->request->getVar('agama'),
            'alamat'         => $this->request->getVar('alamat'),
            'no_hp_siswa'    => $hp_siswa,
            'email_siswa'    => $email,
            'nama_ayah'      => $this->request->getVar('nama_ayah'),
            'nama_ibu'       => $this->request->getVar('nama_ibu'),
            'no_hp_ortu'     => $this->request->getVar('no_hp_ortu'),
            'status_siswa'   => $this->request->getVar('status_siswa'),
        ];

        // Upload Foto
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/siswa', $namaFoto);
            $dataSiswa['foto'] = $namaFoto;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        if (empty($id)) {
            // --- INSERT BARU ---
            if ($this->siswaModel->where('nisn', $nisn)->first()) {
                return redirect()->back()->with('error', 'Gagal! NISN sudah terdaftar.');
            }

            // 1. Buat Akun User (Username = NISN)
            $userData = [
                'username'         => $nisn,
                'email'            => $email ?? $nisn . '@student.sch.id',
                'password'         => password_hash((string)$nisn, PASSWORD_DEFAULT),
                'nama_lengkap'     => $nama,
                'nomor_wa'         => $hp_siswa,
                'telegram_chat_id' => $telegram_id,
                'role'             => 'siswa',
                'active'           => 1
            ];
            $this->userModel->insert($userData);
            $newUserId = $this->userModel->getInsertID();

            // 2. Simpan Siswa
            $dataSiswa['user_id'] = $newUserId;
            $dataSiswa['foto'] = 'default.png'; // Foto default kalau tidak upload
            if(isset($namaFoto)) $dataSiswa['foto'] = $namaFoto;
            
            $this->siswaModel->insert($dataSiswa);
            $msg = 'Siswa baru & Akun Login berhasil dibuat!';

        } else {
            // --- UPDATE DATA ---
            $siswaLama = $this->siswaModel->find($id);
            $this->siswaModel->update($id, $dataSiswa);

            // Update User Terkait (Nama, WA, Telegram)
            if (!empty($siswaLama['user_id'])) {
                $dataUserUpdate = [
                    'nama_lengkap'     => $nama,
                    'nomor_wa'         => $hp_siswa,
                    'telegram_chat_id' => $telegram_id
                ];
                if($email) $dataUserUpdate['email'] = $email;
                
                $this->userModel->update($siswaLama['user_id'], $dataUserUpdate);
            }
            $msg = 'Data siswa berhasil diperbarui!';
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }

        return redirect()->to(base_url('admin/siswa'))->with('success', $msg);
    }

    public function delete_siswa($id)
    {
        $siswa = $this->siswaModel->find($id);
        if ($siswa) {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $this->siswaModel->delete($id);
            if (!empty($siswa['user_id'])) {
                $this->userModel->delete($siswa['user_id']); // Hapus Akun Login
            }
            
            $db->transComplete();
            return redirect()->to(base_url('admin/siswa'))->with('success', 'Data Siswa & Akun Login dihapus.');
        }
        return redirect()->to(base_url('admin/siswa'))->with('error', 'Data tidak ditemukan.');
    }
}