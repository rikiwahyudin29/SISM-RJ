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
    $db = \Config\Database::connect();

    // 1. SDM & AKADEMIK (Join untuk Role Khusus)
    $totalGuru = $db->table('user_roles')
        ->join('roles', 'roles.id = user_roles.role_id')
        ->where('roles.role_key', 'guru')
        ->countAllResults();

    $totalSiswa = $db->table('tbl_siswa')->countAllResults(); 
    $totalUser  = $db->table('tbl_users')->countAllResults();
    $totalKelas = $db->table('tbl_kelas')->countAllResults();
    $totalMapel = $db->table('tbl_mapel')->countAllResults();

    // 2. HITUNG ROLE SPESIFIK (Wali Kelas, BK, Piket)
   $total_walikelas = $db->table('tbl_kelas')
                          ->where('guru_id !=', null) // Pastikan bukan yang 'Belum Set'
                          ->selectCount('guru_id', 'total')
                          ->get()->getRow()->total;
        
    $totalBK = $db->table('user_roles')
        ->join('roles', 'roles.id = user_roles.role_id')
        ->where('roles.role_key', 'bk')->countAllResults();

    $totalPiket = $db->table('user_roles')
        ->join('roles', 'roles.id = user_roles.role_id')
        ->where('roles.role_key', 'piket')->countAllResults();

    // 3. KEDISIPLINAN & BK
    $totalPelanggaran = $db->table('tbl_pelanggaran')->countAllResults();
    $belumDibina      = $db->table('tbl_pelanggaran')->where('status', 'belum')->countAllResults();
    $sudahDibina      = $db->table('tbl_pelanggaran')->where('status', 'sudah')->countAllResults();
    $totalPrestasi    = $db->table('tbl_prestasi')->countAllResults();

    // 4. PRESENSI & IZIN (Hari Ini)
    $tgl = date('Y-m-d');
    $absSakit  = $db->table('tbl_absensi')->where(['tgl' => $tgl, 'status' => 'S'])->countAllResults();
    $absIzin   = $db->table('tbl_absensi')->where(['tgl' => $tgl, 'status' => 'I'])->countAllResults();
    $absAlpha  = $db->table('tbl_absensi')->where(['tgl' => $tgl, 'status' => 'A'])->countAllResults();
    $izIn      = $db->table('tbl_perizinan')->where(['tgl' => $tgl, 'tipe' => 'masuk'])->countAllResults();
    $izOut     = $db->table('tbl_perizinan')->where(['tgl' => $tgl, 'tipe' => 'pulang'])->countAllResults();

    // 5. KEUANGAN (Sum Nilai dari tbl_pengaturan atau tbl_keuangan)
    $totalBayar   = $db->table('tbl_pembayaran')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
    $totalTunggak = $db->table('tbl_tagihan')->where('status', 'belum')->selectSum('sisa')->get()->getRow()->sisa ?? 0;

    // 6. DATA GRAFIK (7 Hari Terakhir)
    $chartLabels   = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
    $chartPresensi = [90, 85, 95, 92, 88, 0, 0]; // Contoh data tren kehadiran %
    $chartKeuangan = [$totalBayar, $totalTunggak]; // Data untuk Doughnut Chart

    // 7. AKTIVITAS & LOGS
    $lastLogin = $db->table('tbl_users')->select('nama_lengkap, last_login')->orderBy('last_login', 'DESC')->limit(5)->get()->getResultArray();
    $logs = [
        ['time' => 'Baru saja', 'user' => 'System', 'act' => 'Dashboard Command Center Aktif'],
        ['time' => '5 menit lalu', 'user' => session()->get('nama_lengkap'), 'act' => 'Membuka Manajemen Web'],
    ];

    $data = [
        'title'             => 'Dashboard Administrator',
        'total_guru'        => $totalGuru,
        'total_siswa'       => $totalSiswa,
        'total_user'        => $totalUser,
        'total_kelas'       => $totalKelas,
        'total_mapel'       => $totalMapel,
        'total_walikelas'   => $total_walikelas,
        'total_bk'          => $totalBK,
        'total_piket'       => $totalPiket,
        'total_pelanggaran' => $totalPelanggaran,
        'belum_dibina'      => $belumDibina,
        'sudah_dibina'      => $sudahDibina,
        'total_prestasi'    => $totalPrestasi,
        'absensi_sakit'     => $absSakit,
        'absensi_izin'      => $absIzin,
        'absensi_alpha'     => $absAlpha,
        'izin_masuk'        => $izIn,
        'izin_pulang'       => $izOut,
        'total_bayar'       => $totalBayar,
        'total_tunggak'     => $totalTunggak,
        'chart_labels'      => $chartLabels,
        'chart_presensi'    => $chartPresensi,
        'chart_keuangan'    => $chartKeuangan,
        'last_login'        => $lastLogin,
        'logs'              => $logs
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
    $model = new \App\Models\SettingModel();
    $data = [
        'title'   => 'Manajemen Web',
        'setting' => $model->findAll(),
        // Mapping data agar mudah dipanggil di View
        'config'  => array_column($model->findAll(), 'nilai', 'kunci')
    ];
    return view('admin/pengaturan/index', $data);
}

public function pengaturan_update()
{
    $model = new \App\Models\SettingModel();
    $post = $this->request->getPost();

    // 1. UPDATE DATA TEXT
    foreach ($post as $key => $value) {
        if ($key == 'csrf_test_name') continue;
        $model->where('kunci', $key)->set(['nilai' => $value])->update();
    }

    // 2. UPDATE DATA FILE (Foto Kepsek & Galeri)
    $files = $this->request->getFiles();
    foreach ($files as $key => $file) {
        if ($file->isValid() && !$file->hasMoved()) {
            // Ambil nama file lama untuk dihapus (opsional)
            $oldFile = $model->where('kunci', $key)->first()['nilai'] ?? '';
            if (!empty($oldFile) && file_exists('uploads/web/' . $oldFile)) {
                @unlink('uploads/web/' . $oldFile);
            }

            // Upload file baru
            $newName = $file->getRandomName();
            $file->move('uploads/web', $newName);
            
            // Simpan nama file ke database
            $model->where('kunci', $key)->set(['nilai' => $newName])->update();
        }
    }

    return redirect()->back()->with('success', 'Pengaturan Web & Galeri berhasil diperbarui!');
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
    public function users_save()
{
    $db = \Config\Database::connect();
    $roles = $this->request->getPost('roles');

    // 1. Simpan ke tbl_users
    $dataUser = [
        'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        'username'     => $this->request->getPost('username'),
        'password'     => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
        'created_at'   => date('Y-m-d H:i:s')
    ];
    $db->table('tbl_users')->insert($dataUser);
    $userId = $db->insertID();

    // 2. Simpan Multi-Role (Assign Jabatan)
    if (!empty($roles)) {
        foreach ($roles as $roleId) {
            $db->table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => $roleId
            ]);
        }
    }

    return redirect()->to(base_url('admin/users'))->with('success', 'User dan Role berhasil ditambahkan!');
}
public function users_index()
{
    $db = \Config\Database::connect();

    // 1. Ambil data dasar user
    $queryUsers = $db->table('tbl_users')->get()->getResultArray();

    $dataUsers = [];
    foreach ($queryUsers as $u) {
        // 2. Ambil foto secara manual dari tbl_guru berdasarkan nama_lengkap
        // Ini langkah 'Pasti' agar key 'foto' selalu ada meskipun isinya null
        $guru = $db->table('tbl_guru')
                   ->where('nama_lengkap', $u['nama_lengkap'])
                   ->get()
                   ->getRowArray();

        // 3. Ambil Roles
        $roles = $db->table('user_roles')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('user_id', $u['id'])
            ->get()->getResultArray();

        // 4. Masukkan ke array (PASTIKAN KEY 'foto' ADA DI SINI)
        $dataUsers[] = [
            'id'           => $u['id'],
            'nama_lengkap' => $u['nama_lengkap'],
            'username'     => $u['username'],
            'foto'         => $guru['foto'] ?? '', // JIKA GURU TIDAK DITEMUKAN, ISI STRING KOSONG
            'roles'        => $roles
        ];
    }

    $data = [
        'title' => 'Manajemen Hak Akses',
        'users' => $dataUsers
    ];

    return view('admin/users/index', $data);
}
public function update_user_profile($id)
{
    $db = \Config\Database::connect();
    $password = $this->request->getPost('password');
    
    $data = [
        'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        'username'     => $this->request->getPost('username'),
    ];

    // Jika password diisi, maka update password (Hashed)
    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    $db->table('tbl_users')->where('id', $id)->update($data);

    return redirect()->back()->with('success', 'Profil & Password User berhasil diperbarui!');
}
public function users_update($id)
{
    $db = \Config\Database::connect();
    
    // Ambil input dari Modal Kuning
    $nama     = $this->request->getPost('nama_lengkap');
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $data = [
        'nama_lengkap' => $nama,
        'username'     => $username,
    ];

    // Jika password diisi (tidak kosong), maka enkripsi dan masukkan ke array update
    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    // Eksekusi Update ke Database
    $db->table('tbl_users')->where('id', $id)->update($data);

    // Beri feedback popup sukses
    return redirect()->to(base_url('admin/users'))->with('success', 'Akun ' . $nama . ' berhasil diperbarui!');
}
public function users_tambah()
{
    $db = \Config\Database::connect();
    
    // Ambil data dari form modal tambah
    $nama     = $this->request->getPost('nama_lengkap');
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    // Persiapkan data untuk tbl_users
    $data = [
        'nama_lengkap' => $nama,
        'username'     => $username,
        'password'     => password_hash($password, PASSWORD_BCRYPT), // Wajib di-hash 
        'created_at'   => date('Y-m-d H:i:s')
    ];

    // Simpan ke database
    $db->table('tbl_users')->insert($data);

    // Kirim feedback sukses ke dashboard 
    return redirect()->to(base_url('admin/users'))->with('success', 'User ' . $nama . ' berhasil didaftarkan!');
}
}