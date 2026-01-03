<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SiswaModel;
use App\Models\KelasModel;  // Tambahan
use App\Models\EkskulModel; // Tambahan
use App\Models\OrangTuaModel;
use App\Models\GuruModel;
use App\Libraries\TelegramService;
use App\Models\PengaturanModel;

class Admin extends BaseController
{
    protected $kelasModel;
    protected $guruModel;
    protected $pengaturanModel;
    protected $ekskulModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->guruModel  = new GuruModel();
        $this->pengaturanModel = new PengaturanModel(); 
        $this->ekskulModel     = new EkskulModel();
    }
    public function index()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('auth'))->with('error', 'Akses ditolak!');
        }

        $db = \Config\Database::connect();
        
        $totalGuru  = $db->table('users')->where('role', 'guru')->countAllResults();
        $totalSiswa = $db->table('users')->where('role', 'siswa')->countAllResults();
        $totalAll   = $db->table('users')->countAll();

        $logs = [
            ['time' => '2 menit lalu', 'user' => 'Admin', 'act' => 'Update Data Guru'],
            ['time' => '1 jam lalu', 'user' => 'System', 'act' => 'OTP Sent via OneSender'],
            ['time' => '3 jam lalu', 'user' => 'System', 'act' => 'Backup database selesai'],
        ];

        $data = [
            'title'       => 'Dashboard Admin Pro',
            'total_guru'  => $totalGuru,
            'total_siswa' => $totalSiswa,
            'total_all'   => $totalAll,
            'logs'        => $logs 
        ];

        return view('admin/dashboard', $data);
    }

    // --- MANAJEMEN GURU ---

    // =================================================================
    // MANAJEMEN GURU (FULL FIX)
    // =================================================================

    // 1. DAFTAR GURU (INDEX)
    public function guru()
{
    $db = \Config\Database::connect();
    
    // 1. UBAH SUMBER TABEL UTAMA KE 'tbl_users'
    $builder = $db->table('tbl_users'); 
    
    // 2. SELECT DATA (Gunakan 'tbl_users.' bukan 'users.')
    $builder->select('tbl_guru.*, tbl_users.username, tbl_users.email');
    
    // 3. JOIN JUGA DISESUAIKAN
    $builder->join('tbl_guru', 'tbl_guru.user_id = tbl_users.id', 'left'); 
    
    // 4. FILTER ROLE
    $builder->where('tbl_users.role', 'guru');
    
    $query = $builder->get();

    $data = [
        'title' => 'Daftar Guru',
        'guru'  => $query->getResultArray() 
    ];

    return view('admin/guru/index', $data); 
}

    // 2. FORM TAMBAH GURU
    public function guru_tambah()
    {
        $data = [
            'title' => 'Tambah Data Guru'
        ];
        return view('admin/guru/tambah', $data);
    }

    // 3. PROSES SIMPAN GURU (AUTO CREATE AKUN)
   public function guru_simpan()
{
    // 1. VALIDASI
    if (!$this->validate([
        'nip'          => 'required|is_unique[tbl_guru.nip]',
        'nama_lengkap' => 'required',
        // Tambahan validasi WA (opsional)
        'foto'         => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
    ])) {
        return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek NIP atau Format Foto.');
    }

    $userModel = new \App\Models\UserModel();
    $guruModel = new \App\Models\GuruModel();

    // 2. DATA UNTUK AKUN LOGIN (tbl_users)
    $nip = $this->request->getPost('nip');
    $namaLengkap = $this->request->getPost('nama_lengkap'); // Ambil nama
    $nomorWa = $this->request->getPost('nomor_wa');         // Ambil WA

    $passwordAsli = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
    
    $userData = [
        'username'     => $nip,
        'password'     => password_hash($passwordAsli, PASSWORD_DEFAULT),
        'email'        => $nip . '@sekolah.id',
        'role'         => 'guru',
        // --- PERBAIKAN: SIMPAN JUGA NAMA & WA KE TABEL USER ---
        'nama_lengkap' => $namaLengkap, 
        'nomor_wa'     => $nomorWa
    ];
    
    try {
        $userModel->insert($userData);
        $userId = $userModel->getInsertID();
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal buat akun user: ' . $e->getMessage());
    }

    // 3. UPLOAD FOTO
    $fileFoto = $this->request->getFile('foto');
    if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
        $namaFoto = $fileFoto->getRandomName();
        $fileFoto->move('uploads/guru', $namaFoto);
    } else {
        $namaFoto = 'default.png';
    }

    // 4. DATA UNTUK PROFIL GURU (tbl_guru)
    $guruData = [
        'user_id'        => $userId,
        'nip'            => $nip,
        'nama_lengkap'   => $namaLengkap, // Simpan juga di sini
        'gelar_depan'    => $this->request->getPost('gelar_depan'),
        'gelar_belakang' => $this->request->getPost('gelar_belakang'),
        'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
        'alamat'         => $this->request->getPost('alamat'),
        'foto'           => $namaFoto
    ];
    
    try {
        $guruModel->insert($guruData);
    } catch (\Exception $e) {
        $userModel->delete($userId);
        return redirect()->back()->with('error', 'Gagal simpan profil guru: ' . $e->getMessage());
    }

    $pesan = "Berhasil! <br>Username: <b>$nip</b> <br>Password Baru: <b>$passwordAsli</b> <br>(Harap dicatat!)";
    return redirect()->to(base_url('admin/guru'))->with('success', $pesan);
}

    // 4. FORM EDIT GURU
    // =================================================================
    // FORM EDIT GURU (FIXED)
    // =================================================================
    public function guru_edit($id)
    {
        $guruModel = new \App\Models\GuruModel();
        $userModel = new \App\Models\UserModel(); // Panggil Model User

        // 1. Ambil data profil guru
        $guru = $guruModel->find($id);

        if (!$guru) {
            return redirect()->to('/admin/guru')->with('error', 'Data guru tidak ditemukan.');
        }

        // 2. Ambil data akun user terkait (berdasarkan user_id di tabel guru)
        // Ini yang tadi bikin error "Undefined variable $user"
        $user = $userModel->find($guru['user_id']);

        $data = [
            'title' => 'Edit Data Guru',
            'guru'  => $guru,
            'user'  => $user // <--- WAJIB DIKIRIM KE VIEW
        ];

        return view('admin/guru/edit', $data);
    }

    // =================================================================
    // PROSES UPDATE GURU (FIXED)
    // =================================================================
    public function guru_update($id)
{
    $guruModel = new \App\Models\GuruModel();
    $userModel = new \App\Models\UserModel();

    $guruLama = $guruModel->find($id);
    if (!$guruLama) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // 1. UPDATE DATA AKUN (tbl_users)
    $userData = [
        'username'     => $this->request->getPost('username'),
        'email'        => $this->request->getPost('email'),
        // --- PERBAIKAN: UPDATE JUGA NAMA & WA DI TABEL USER ---
        'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        'nomor_wa'     => $this->request->getPost('nomor_wa'),
    ];
    
    // Cek password ganti atau tidak
    $passwordBaru = $this->request->getPost('password');
    if (!empty($passwordBaru)) {
        $userData['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
    }

    $userModel->update($guruLama['user_id'], $userData);

    // 2. UPDATE FOTO
    $fileFoto = $this->request->getFile('foto');
    if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
        if ($guruLama['foto'] != 'default.png' && file_exists('uploads/guru/' . $guruLama['foto'])) {
            unlink('uploads/guru/' . $guruLama['foto']);
        }
        $namaFoto = $fileFoto->getRandomName();
        $fileFoto->move('uploads/guru', $namaFoto);
    } else {
        $namaFoto = $guruLama['foto'];
    }

    // 3. UPDATE PROFIL GURU (tbl_guru)
    $guruData = [
        'nip'            => $this->request->getPost('nip'),
        'nama_lengkap'   => $this->request->getPost('nama_lengkap'),
        'gelar_depan'    => $this->request->getPost('gelar_depan'),
        'gelar_belakang' => $this->request->getPost('gelar_belakang'),
        'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
        'alamat'         => $this->request->getPost('alamat'),
        'foto'           => $namaFoto
    ];

    $guruModel->update($id, $guruData);

    return redirect()->to(base_url('admin/guru'))->with('success', 'Data guru berhasil diperbarui!');
}
    // 6. HAPUS GURU
    public function guru_hapus($id) // $id adalah ID GURU
    {
        $guruModel = new \App\Models\GuruModel();
        $userModel = new \App\Models\UserModel();

        // 1. Cari Data Guru
        $guru = $guruModel->find($id);

        if (!$guru) {
            return redirect()->to('/admin/guru')->with('error', 'Data guru tidak ditemukan.');
        }

        // 2. Hapus Foto Fisik
        if ($guru['foto'] != 'default.png' && file_exists('uploads/guru/' . $guru['foto'])) {
            unlink('uploads/guru/' . $guru['foto']);
        }

        // 3. Simpan User ID buat dihapus nanti
        $userId = $guru['user_id'];

        // 4. Hapus Data di Tabel Guru Dulu
        $guruModel->delete($id);

        // 5. Hapus Akun Login (User)
        if ($userId) {
            $userModel->delete($userId);
        }

        return redirect()->to('/admin/guru')->with('success', 'Data guru dan akun login berhasil dihapus.');
    }
    
    public function data_siswa()
    {
        $model = new SiswaModel();
        
        $data = [
            'title' => 'Data Siswa Lengkap',
            // Pakai method custom getSiswaLengkap() yg tadi kita buat di Model
            'siswa' => $model->getSiswaLengkap() 
        ];

        return view('admin/siswa/index', $data);
    }

    // 2. FORM TAMBAH (Kirim Data Kelas & Ekskul untuk Dropdown)
    public function tambah_siswa()
    {
        $kelasModel  = new KelasModel();
        $ekskulModel = new EkskulModel();

        $data = [
            'title'       => 'Tambah Siswa',
            'data_kelas'  => $kelasModel->findAll(),  // Untuk dropdown kelas
            'data_ekskul' => $ekskulModel->findAll()  // Untuk dropdown ekskul
        ];
        return view('admin/siswa/tambah', $data);
    }

    // 3. PROSES SIMPAN (Input Lengkap)
    public function simpan_siswa()
    {
        $model = new SiswaModel();

        // Validasi
        if (!$this->validate([
            'nis'  => 'required|is_unique[tbl_siswa.nis]',
            'nama' => 'required',
            'kelas_id' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
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

        return redirect()->to('/admin/siswa')->with('message', 'Data siswa lengkap berhasil disimpan');
    }
    public function edit_siswa($id)
    {
        $siswaModel  = new SiswaModel();
        $kelasModel  = new KelasModel();
        $ekskulModel = new EkskulModel();

        // Ambil data siswa berdasarkan ID
        $siswa = $siswaModel->find($id);

        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Data Siswa',
            'siswa'       => $siswa,                // Data siswa yang akan diedit
            'data_kelas'  => $kelasModel->findAll(), // Dropdown kelas
            'data_ekskul' => $ekskulModel->findAll() // Dropdown ekskul
        ];

        return view('admin/siswa/edit', $data);
    }

    // 5. PROSES UPDATE SISWA
    public function update_siswa($id)
    {
        $model = new SiswaModel();

        // Validasi
        // Note: Rule 'is_unique' harus mengecualikan ID siswa yang sedang diedit agar tidak dianggap duplikat diri sendiri.
        if (!$this->validate([
            "nis"  => "required|is_unique[tbl_siswa.nis,id,$id]", 
            "nama" => "required",
            "kelas_id" => "required"
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
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
            // Status tidak diupdate di sini, kecuali mau tambah field status di form edit
        ]);

        return redirect()->to('/admin/siswa')->with('message', 'Data siswa berhasil diperbarui.');
    }

    // 6. PROSES HAPUS SISWA
    public function delete_siswa($id)
    {
        $model = new SiswaModel();
        
        // Cek dulu datanya ada atau tidak
        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            return redirect()->to('/admin/siswa')->with('message', 'Data siswa berhasil dihapus.');
        } else {
            return redirect()->to('/admin/siswa')->with('error', 'Data tidak ditemukan.');
        }
    }
    public function data_ortu()
    {
        $model = new OrangTuaModel();
        
        $data = [
            'title' => 'Data Orang Tua',
            'ortu'  => $model->getOrangTuaLengkap() // Pakai fungsi JOIN tadi
        ];

        return view('admin/ortu/index', $data);
    }

    // 2. FORM TAMBAH (Butuh Data Siswa untuk Dropdown)
    public function tambah_ortu()
    {
        $siswaModel = new SiswaModel();
        
        // Ambil siswa lengkap dengan kelas untuk dropdown
        $data = [
            'title'      => 'Tambah Data Wali',
            'data_siswa' => $siswaModel->getSiswaLengkap()
        ];
        return view('admin/ortu/tambah', $data);
    }

    // 3. PROSES SIMPAN
    public function simpan_ortu()
    {
        $model = new OrangTuaModel();

        if (!$this->validate([
            'siswa_id'   => 'required|is_unique[tbl_orangtua.siswa_id]', // 1 Siswa = 1 Data Ortu (Ayah)
            'nama_ayah'  => 'required',
            'no_hp_ortu' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'siswa_id'       => $this->request->getPost('siswa_id'),
            'nama_ayah'      => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'no_hp_ortu'     => $this->request->getPost('no_hp_ortu'),
        ]);

        return redirect()->to('/admin/ortu')->with('message', 'Data orang tua berhasil ditambahkan.');
    }

    // 4. FORM EDIT
    public function edit_ortu($id)
    {
        $model      = new OrangTuaModel();
        $siswaModel = new SiswaModel();

        $ortu = $model->find($id);
        if (!$ortu) return redirect()->to('/admin/ortu');

        $data = [
            'title'      => 'Edit Data Wali',
            'ortu'       => $ortu,
            'data_siswa' => $siswaModel->getSiswaLengkap()
        ];
        return view('admin/ortu/edit', $data);
    }

    // 5. PROSES UPDATE
    public function update_ortu($id)
    {
        $model = new OrangTuaModel();

        // Validasi (Unik siswa_id dikecualikan untuk ID ini)
        if (!$this->validate([
            'siswa_id'   => "required|is_unique[tbl_orangtua.siswa_id,id,$id]",
            'nama_ayah'  => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'siswa_id'       => $this->request->getPost('siswa_id'),
            'nama_ayah'      => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'no_hp_ortu'     => $this->request->getPost('no_hp_ortu'),
        ]);

        return redirect()->to('/admin/ortu')->with('message', 'Data berhasil diperbarui.');
    }

    // 6. HAPUS
    public function delete_ortu($id)
    {
        $model = new OrangTuaModel();
        $model->delete($id);
        return redirect()->to('/admin/ortu')->with('message', 'Data berhasil dihapus.');
    }
public function kelas()
    {
        $data = [
            'title' => 'Manajemen Kelas',
            'kelas' => $this->kelasModel->getKelasLengkap() // Panggil fungsi join tadi
        ];
        return view('admin/kelas/index', $data);
    }

    public function tambah_kelas()
    {
        $data = [
            'title' => 'Tambah Kelas',
            'gurus' => $this->guruModel->findAll() // Ambil semua guru untuk dropdown
        ];
        return view('admin/kelas/tambah', $data);
    }

    public function simpan_kelas()
    {
        if (!$this->validate([
            'nama_kelas' => 'required',
            'guru_id'    => 'required'
        ])) {
            return redirect()->back()->withInput();
        }

        $this->kelasModel->save([
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'guru_id'    => $this->request->getPost('guru_id'),
        ]);

        return redirect()->to('/admin/kelas')->with('success', 'Data kelas berhasil ditambahkan');
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

        return redirect()->to('/admin/kelas')->with('success', 'Data kelas berhasil diupdate');
    }

    public function hapus_kelas($id)
    {
        $this->kelasModel->delete($id);
        return redirect()->to('/admin/kelas')->with('success', 'Data berhasil dihapus');
    }
public function pengaturan()
{
    // Panggil fungsi sakti tadi
    $dataSetting = $this->pengaturanModel->ambilDataArray();

    // Pastikan key 'telegram_token' ada biar gak error di view (kalau lupa insert SQL)
    if(!isset($dataSetting['telegram_token'])) $dataSetting['telegram_token'] = '';

    $data = [
        'title'   => 'Pengaturan Sekolah',
        'setting' => $dataSetting
    ];

    return view('admin/pengaturan/index', $data);
}

public function simpan_pengaturan()
{
    // Ambil semua input dari form
    $semuaInput = $this->request->getPost();

    // Oper ke model untuk disimpan per baris
    $this->pengaturanModel->simpanBatch($semuaInput);

    return redirect()->to('/admin/pengaturan')->with('success', 'Pengaturan berhasil disimpan!');
}
public function tes_kirim()
{
    $telegram = new TelegramService();
    
    // Masukkan ID Telegram tujuan (misal ID kamu sendiri)
    $chatId = '123456789'; 
    $pesan  = 'Halo bos, ini tes notifikasi dari sistem sekolah!';

    if ($telegram->kirim($chatId, $pesan)) {
        echo "Berhasil dikirim!";
    } else {
        echo "Gagal kirim. Cek log atau token.";
    }
}
public function ekskul()
    {
        $data = [
            'title'  => 'Manajemen Ekstrakurikuler',
            // Ambil data ekskul lengkap (Join Guru & Count Siswa)
            'ekskul' => $this->ekskulModel->getEkskulLengkap()
        ];
        return view('admin/ekskul/index', $data);
    }

    public function ekskul_tambah()
    {
        $data = [
            'title' => 'Tambah Ekskul',
            'guru'  => $this->guruModel->findAll() // Data Guru untuk Dropdown
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
        return redirect()->to('/admin/ekskul')->with('success', 'Ekskul berhasil ditambahkan.');
    }

    public function ekskul_detail($id)
    {
        $data = [
            'title'   => 'Detail Anggota Ekskul',
            'ekskul'  => $this->ekskulModel->find($id),
            'anggota' => $this->ekskulModel->getAnggota($id) // List Siswa
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
        return redirect()->to('/admin/ekskul')->with('success', 'Ekskul berhasil diupdate.');
    }

    public function ekskul_hapus($id)
    {
        $this->ekskulModel->delete($id);
        return redirect()->to('/admin/ekskul')->with('success', 'Ekskul dihapus.');
    }
}