<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\EkskulModel;
use App\Models\OrangTuaModel;
use App\Models\PengaturanModel;
use App\Libraries\TelegramService; // Pastikan library ini ada, atau hapus jika belum dibuat

class Admin extends BaseController
{
    protected $userModel;
    protected $guruModel;
    protected $siswaModel;
    protected $kelasModel;
    protected $ekskulModel;
    protected $ortuModel;
    protected $pengaturanModel;
    protected $db;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->guruModel       = new GuruModel();
        $this->siswaModel      = new SiswaModel();
        $this->kelasModel      = new KelasModel();
        $this->ekskulModel     = new EkskulModel();
        $this->ortuModel       = new OrangTuaModel();
        $this->pengaturanModel = new PengaturanModel();
        $this->db              = \Config\Database::connect();
    }

    // =================================================================
    // 1. DASHBOARD
    // =================================================================
    public function index()
    {
        // Hitung User berdasarkan Role di tabel user_roles (LEBIH AKURAT)
        $totalGuru = $this->db->table('user_roles')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('roles.role_key', 'guru')
            ->countAllResults();

        $totalSiswa = $this->db->table('user_roles')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('roles.role_key', 'siswa')
            ->countAllResults();

        $totalAll = $this->db->table('tbl_users')->countAllResults();

        // Data Log Dummy (Nanti bisa dibikin tabel logs beneran)
        $logs = [
            ['time' => 'Baru saja', 'user' => 'System', 'act' => 'Sinkronisasi Multi-Role Berhasil'],
            ['time' => '10 menit lalu', 'user' => session()->get('nama'), 'act' => 'Login ke Dashboard Admin'],
        ];

        $data = [
            'title'       => 'Dashboard Administrator',
            'user'        => session()->get('nama'), // Ambil nama dari session login
            'total_guru'  => $totalGuru,
            'total_siswa' => $totalSiswa,
            'total_all'   => $totalAll,
            'logs'        => $logs
        ];

        return view('admin/dashboard', $data);
    }

    // =================================================================
    // 2. MANAJEMEN GURU (UPDATED FOR MULTI-ROLE)
    // =================================================================
    public function guru()
    {
        // Join tabel Guru dengan User untuk dapat Username/Email
        $builder = $this->db->table('tbl_guru');
        $builder->select('tbl_guru.*, tbl_users.username, tbl_users.email, tbl_users.nomor_wa');
        $builder->join('tbl_users', 'tbl_users.id = tbl_guru.user_id');
        $query = $builder->get();

        $data = [
            'title' => 'Daftar Guru',
            'guru'  => $query->getResultArray()
        ];

        return view('admin/guru/index', $data);
    }

    public function guru_tambah()
    {
        $data = ['title' => 'Tambah Data Guru'];
        return view('admin/guru/tambah', $data);
    }

    public function guru_simpan()
    {
        // 1. VALIDASI
        if (!$this->validate([
            'nip'          => 'required|is_unique[tbl_guru.nip]',
            'nama_lengkap' => 'required',
            'foto'         => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek NIP atau Format Foto.');
        }

        // 2. DATA INPUT
        $nip          = $this->request->getPost('nip');
        $namaLengkap  = $this->request->getPost('nama_lengkap');
        $nomorWa      = $this->request->getPost('nomor_wa');
        $passwordAsli = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 8); // Auto Generate Pass

        // --- MULAI TRANSAKSI DATABASE (Biar Aman) ---
        $this->db->transStart();

        try {
            // A. INSERT KE TBL_USERS
            $userData = [
                'username'     => $nip,
                'password'     => password_hash($passwordAsli, PASSWORD_DEFAULT),
                'email'        => $nip . '@sekolah.id',
                'nama_lengkap' => $namaLengkap,
                'nomor_wa'     => $nomorWa,
                // 'role'      => 'guru' (Kolom lama biarkan atau isi buat cadangan)
            ];
            $this->userModel->insert($userData);
            $userId = $this->userModel->getInsertID();

            // B. INSERT KE USER_ROLES (PENTING BUAT LOGIN!)
            // Cari ID role 'guru' dulu
            $roleGuru = $this->db->table('roles')->where('role_key', 'guru')->get()->getRow();
            if ($roleGuru) {
                $this->db->table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => $roleGuru->id
                ]);
            }

            // C. UPLOAD FOTO
            $fileFoto = $this->request->getFile('foto');
            $namaFoto = 'default.png';
            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                $namaFoto = $fileFoto->getRandomName();
                $fileFoto->move('uploads/guru', $namaFoto);
            }

            // D. INSERT KE TBL_GURU
            $guruData = [
                'user_id'        => $userId,
                'nip'            => $nip,
                'nama_lengkap'   => $namaLengkap,
                'gelar_depan'    => $this->request->getPost('gelar_depan'),
                'gelar_belakang' => $this->request->getPost('gelar_belakang'),
                'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
                'alamat'         => $this->request->getPost('alamat'),
                'foto'           => $namaFoto
            ];
            $this->guruModel->insert($guruData);

            // SELESAIKAN TRANSAKSI
            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                // Kalau gagal, file foto yang terlanjur upload dihapus
                if ($namaFoto != 'default.png') unlink('uploads/guru/' . $namaFoto);
                return redirect()->back()->with('error', 'Gagal menyimpan data ke database.');
            }

            $pesan = "Berhasil! <br>Username: <b>$nip</b> <br>Password: <b>$passwordAsli</b>";
            return redirect()->to(base_url('admin/guru'))->with('success', $pesan);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function guru_edit($id)
    {
        $guru = $this->guruModel->find($id);
        if (!$guru) return redirect()->to('/admin/guru')->with('error', 'Data guru tidak ditemukan.');

        $user = $this->userModel->find($guru['user_id']);

        $data = [
            'title' => 'Edit Data Guru',
            'guru'  => $guru,
            'user'  => $user
        ];
        return view('admin/guru/edit', $data);
    }

   public function guru_update($id)
{
    $guruLama = $this->guruModel->find($id);
    if (!$guruLama) return redirect()->back()->with('error', 'Data tidak ditemukan.');

    // 1. UPDATE USER (Tabel tbl_users)
    $userData = [
        'username'         => $this->request->getPost('username'),
        'email'            => $this->request->getPost('email'),
        'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
        'nomor_wa'         => $this->request->getPost('nomor_wa'),
        'telegram_chat_id' => $this->request->getPost('telegram_chat_id'), // 🔥 SEKARANG SUDAH DITAMBAHKAN
    ];

    $passwordBaru = $this->request->getPost('password');
    if (!empty($passwordBaru)) {
        $userData['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
    }
    
    // Eksekusi Update ke tbl_users
    $this->userModel->update($guruLama['user_id'], $userData);

    // 2. UPDATE FOTO
    $fileFoto = $this->request->getFile('foto');
    $namaFoto = $guruLama['foto'];

    if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
        // Hapus foto lama jika bukan default
        if ($guruLama['foto'] != 'default.png' && file_exists('uploads/guru/' . $guruLama['foto'])) {
            unlink('uploads/guru/' . $guruLama['foto']);
        }
        
        $namaFoto = $fileFoto->getRandomName();
        $fileFoto->move('uploads/guru', $namaFoto);

        // 🔥 EXTRA: Jika yang diedit adalah akun sendiri, update session fotonya
        if (session()->get('id_user') == $guruLama['user_id']) {
            session()->set('foto', $namaFoto);
        }
    }

    // 3. UPDATE GURU (Tabel tbl_guru)
    $guruData = [
        'nip'            => $this->request->getPost('nip'),
        'nama_lengkap'   => $this->request->getPost('nama_lengkap'),
        'gelar_depan'    => $this->request->getPost('gelar_depan'),
        'gelar_belakang' => $this->request->getPost('gelar_belakang'),
        'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
        'alamat'         => $this->request->getPost('alamat'),
        'foto'           => $namaFoto
    ];
    
    $this->guruModel->update($id, $guruData);

    return redirect()->to(base_url('admin/guru'))->with('success', 'Data guru dan ID Telegram berhasil diperbarui!');
}
    public function guru_hapus($id)
    {
        $guru = $this->guruModel->find($id);
        if (!$guru) return redirect()->to('/admin/guru');

        // Hapus Foto
        if ($guru['foto'] != 'default.png' && file_exists('uploads/guru/' . $guru['foto'])) {
            unlink('uploads/guru/' . $guru['foto']);
        }

        // Hapus Data Guru & User
        $userId = $guru['user_id'];
        $this->guruModel->delete($id);
        if ($userId) {
            $this->userModel->delete($userId);
            // Data di user_roles otomatis hilang kalau settingan foreign key CASCADE, 
            // kalau tidak, hapus manual:
            $this->db->table('user_roles')->where('user_id', $userId)->delete();
        }

        return redirect()->to('/admin/guru')->with('success', 'Data guru dihapus.');
    }

    // =================================================================
    // 3. MANAJEMEN SISWA
    // =================================================================
    public function data_siswa()
    {
        $data = [
            'title' => 'Data Siswa Lengkap',
            'siswa' => $this->siswaModel->getSiswaLengkap()
        ];
        return view('admin/siswa/index', $data);
    }

    public function tambah_siswa()
    {
        $data = [
            'title'       => 'Tambah Siswa',
            'data_kelas'  => $this->kelasModel->findAll(),
            'data_ekskul' => $this->ekskulModel->findAll()
        ];
        return view('admin/siswa/tambah', $data);
    }

    public function simpan_siswa()
    {
        if (!$this->validate([
            'nis'      => 'required|is_unique[tbl_siswa.nis]',
            'nama'     => 'required',
            'kelas_id' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->siswaModel->save([
            'nis'           => $this->request->getPost('nis'),
            'nisn'          => $this->request->getPost('nisn'),
            'nama'          => $this->request->getPost('nama'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jk'            => $this->request->getPost('jk'),
            'nik'           => $this->request->getPost('nik'),
            'nama_ibu'      => $this->request->getPost('nama_ibu'),
            'kelas_id'      => $this->request->getPost('kelas_id'),
            'ekskul_id'     => $this->request->getPost('ekskul_id'),
            'status'        => 'aktif'
        ]);

        return redirect()->to('/admin/siswa')->with('message', 'Data siswa berhasil disimpan');
    }

    public function edit_siswa($id)
    {
        $siswa = $this->siswaModel->find($id);
        if (!$siswa) return redirect()->to('/admin/siswa')->with('error', 'Siswa tidak ditemukan.');

        $data = [
            'title'       => 'Edit Data Siswa',
            'siswa'       => $siswa,
            'data_kelas'  => $this->kelasModel->findAll(),
            'data_ekskul' => $this->ekskulModel->findAll()
        ];
        return view('admin/siswa/edit', $data);
    }

    public function update_siswa($id)
    {
        if (!$this->validate([
            "nis"      => "required|is_unique[tbl_siswa.nis,id,$id]",
            "nama"     => "required",
            "kelas_id" => "required"
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->siswaModel->update($id, [
            'nis'           => $this->request->getPost('nis'),
            'nisn'          => $this->request->getPost('nisn'),
            'nama'          => $this->request->getPost('nama'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jk'            => $this->request->getPost('jk'),
            'nik'           => $this->request->getPost('nik'),
            'nama_ibu'      => $this->request->getPost('nama_ibu'),
            'kelas_id'      => $this->request->getPost('kelas_id'),
            'ekskul_id'     => $this->request->getPost('ekskul_id'),
        ]);

        return redirect()->to('/admin/siswa')->with('message', 'Data siswa diperbarui.');
    }

    public function delete_siswa($id)
    {
        $this->siswaModel->delete($id);
        return redirect()->to('/admin/siswa')->with('message', 'Data siswa dihapus.');
    }

    // =================================================================
    // 4. MANAJEMEN ORANG TUA
    // =================================================================
    public function data_ortu()
    {
        $data = [
            'title' => 'Data Orang Tua',
            'ortu'  => $this->ortuModel->getOrangTuaLengkap()
        ];
        return view('admin/ortu/index', $data);
    }

    public function tambah_ortu()
    {
        $data = [
            'title'      => 'Tambah Data Wali',
            'data_siswa' => $this->siswaModel->getSiswaLengkap()
        ];
        return view('admin/ortu/tambah', $data);
    }

    public function simpan_ortu()
    {
        if (!$this->validate([
            'siswa_id'   => 'required|is_unique[tbl_orangtua.siswa_id]',
            'nama_ayah'  => 'required',
            'no_hp_ortu' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->ortuModel->save([
            'siswa_id'       => $this->request->getPost('siswa_id'),
            'nama_ayah'      => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'no_hp_ortu'     => $this->request->getPost('no_hp_ortu'),
        ]);

        return redirect()->to('/admin/ortu')->with('message', 'Data orang tua tersimpan.');
    }

    public function edit_ortu($id)
    {
        $ortu = $this->ortuModel->find($id);
        if (!$ortu) return redirect()->to('/admin/ortu');

        $data = [
            'title'      => 'Edit Data Wali',
            'ortu'       => $ortu,
            'data_siswa' => $this->siswaModel->getSiswaLengkap()
        ];
        return view('admin/ortu/edit', $data);
    }

    public function update_ortu($id)
    {
        if (!$this->validate([
            'siswa_id'  => "required|is_unique[tbl_orangtua.siswa_id,id,$id]",
            'nama_ayah' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->ortuModel->update($id, [
            'siswa_id'       => $this->request->getPost('siswa_id'),
            'nama_ayah'      => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'no_hp_ortu'     => $this->request->getPost('no_hp_ortu'),
        ]);

        return redirect()->to('/admin/ortu')->with('message', 'Data diperbarui.');
    }

    public function delete_ortu($id)
    {
        $this->ortuModel->delete($id);
        return redirect()->to('/admin/ortu')->with('message', 'Data dihapus.');
    }

    // =================================================================
    // 5. MANAJEMEN KELAS
    // =================================================================
    public function kelas()
    {
        $data = [
            'title' => 'Manajemen Kelas',
            'kelas' => $this->kelasModel->getKelasLengkap()
        ];
        return view('admin/kelas/index', $data);
    }

    public function tambah_kelas()
    {
        $data = [
            'title' => 'Tambah Kelas',
            'gurus' => $this->guruModel->findAll()
        ];
        return view('admin/kelas/tambah', $data);
    }

    public function simpan_kelas()
    {
        if (!$this->validate(['nama_kelas' => 'required', 'guru_id' => 'required'])) {
            return redirect()->back()->withInput();
        }

        $this->kelasModel->save([
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'guru_id'    => $this->request->getPost('guru_id'),
        ]);

        return redirect()->to('/admin/kelas')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit_kelas($id)
    {
        $data = [
            'title' => 'Edit Kelas',
            'kelas' => $this->kelasModel->find($id),
            'gurus' => $this->guruModel->findAll()
        ];
        return view('admin/kelas/edit', $data);
    }

    public function update_kelas($id)
    {
        $this->kelasModel->update($id, [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'guru_id'    => $this->request->getPost('guru_id'),
        ]);
        return redirect()->to('/admin/kelas')->with('success', 'Kelas diperbarui');
    }

    public function hapus_kelas($id)
    {
        $this->kelasModel->delete($id);
        return redirect()->to('/admin/kelas')->with('success', 'Kelas dihapus');
    }

    // =================================================================
    // 6. MANAJEMEN EKSKUL
    // =================================================================
    public function ekskul()
    {
        $data = [
            'title'  => 'Manajemen Ekstrakurikuler',
            'ekskul' => $this->ekskulModel->getEkskulLengkap()
        ];
        return view('admin/ekskul/index', $data);
    }

    public function ekskul_tambah()
    {
        $data = [
            'title' => 'Tambah Ekskul',
            'guru'  => $this->guruModel->findAll()
        ];
        return view('admin/ekskul/tambah', $data);
    }

    public function ekskul_simpan()
    {
        $this->ekskulModel->save([
            'nama_ekskul' => $this->request->getPost('nama_ekskul'),
            'guru_id'     => $this->request->getPost('guru_id'),
            'hari'        => $this->request->getPost('hari'),
            'jam'         => $this->request->getPost('jam'),
        ]);
        return redirect()->to('/admin/ekskul')->with('success', 'Ekskul ditambahkan.');
    }

    public function ekskul_detail($id)
    {
        $data = [
            'title'   => 'Detail Anggota Ekskul',
            'ekskul'  => $this->ekskulModel->find($id),
            'anggota' => $this->ekskulModel->getAnggota($id)
        ];
        return view('admin/ekskul/detail', $data);
    }

    public function ekskul_edit($id)
    {
        $data = [
            'title'  => 'Edit Ekskul',
            'ekskul' => $this->ekskulModel->find($id),
            'guru'   => $this->guruModel->findAll()
        ];
        return view('admin/ekskul/edit', $data);
    }

    public function ekskul_update($id)
    {
        $this->ekskulModel->update($id, [
            'nama_ekskul' => $this->request->getPost('nama_ekskul'),
            'guru_id'     => $this->request->getPost('guru_id'),
            'hari'        => $this->request->getPost('hari'),
            'jam'         => $this->request->getPost('jam'),
        ]);
        return redirect()->to('/admin/ekskul')->with('success', 'Ekskul diupdate.');
    }

    public function ekskul_hapus($id)
    {
        $this->ekskulModel->delete($id);
        return redirect()->to('/admin/ekskul')->with('success', 'Ekskul dihapus.');
    }

    // =================================================================
    // 7. PENGATURAN & TEST SYSTEM
    // =================================================================
    public function pengaturan()
    {
        $dataSetting = $this->pengaturanModel->ambilDataArray();
        if(!isset($dataSetting['telegram_token'])) $dataSetting['telegram_token'] = '';

        $data = [
            'title'   => 'Pengaturan Sekolah',
            'setting' => $dataSetting
        ];
        return view('admin/pengaturan/index', $data);
    }

    public function simpan_pengaturan()
    {
        $semuaInput = $this->request->getPost();
        $this->pengaturanModel->simpanBatch($semuaInput);
        return redirect()->to('/admin/pengaturan')->with('success', 'Pengaturan disimpan!');
    }

    public function tes_kirim()
    {
        // Tes Kirim Telegram Manual
        // Pastikan class TelegramService sudah dibuat di App/Libraries/
        $telegram = new TelegramService();
        $chatId   = 'YOUR_CHAT_ID_HERE'; // Ganti Manual saat testing
        $pesan    = 'Halo Admin, ini tes notifikasi dari sistem sekolah!';

        if ($telegram->kirim($chatId, $pesan)) {
            echo "Berhasil dikirim!";
        } else {
            echo "Gagal kirim. Cek log atau token.";
        }
    }
    public function users()
    {
        // 1. Ambil semua user dari tabel tbl_users
        $users = $this->userModel->findAll();

        // 2. Ambil role untuk setiap user (Multi-Role)
        foreach ($users as &$u) {
            $u['roles'] = $this->userModel->getRoles($u['id']);
        }

        // 3. Ambil daftar semua role yang tersedia untuk pilihan di Modal
        $allRoles = $this->db->table('roles')->get()->getResultArray();

        $data = [
            'title'     => 'Manajemen Hak Akses',
            'users'     => $users,
            'all_roles' => $allRoles
        ];

        return view('admin/users/index', $data);
    }

    public function simpan_user_role()
    {
        $userId = $this->request->getPost('user_id');
        $roleIds = $this->request->getPost('roles'); // Berupa array dari checkbox

        if ($userId) {
            // Hapus role lama dulu
            $this->db->table('user_roles')->where('user_id', $userId)->delete();

            // Masukkan role baru
            if (!empty($roleIds)) {
                foreach ($roleIds as $rId) {
                    $this->db->table('user_roles')->insert([
                        'user_id' => $userId,
                        'role_id' => $rId
                    ]);
                }
            }
            return redirect()->to(base_url('admin/users'))->with('success', 'Hak akses berhasil diperbarui!');
        }
        
        return redirect()->back()->with('error', 'Gagal memperbarui hak akses.');
    }
}