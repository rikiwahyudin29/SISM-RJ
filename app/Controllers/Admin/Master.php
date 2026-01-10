<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Master extends BaseController {
    
    // TAHUN AJARAN
    public function tahun_ajaran() {
        $db = \Config\Database::connect();
        $data = [
            'title' => 'Tahun Ajaran',
            'ta'    => $db->table('tbl_tahun_ajaran')->get()->getResultArray()
        ];
        return view('admin/master/tahun_ajaran', $data);
    }
    public function ta_simpan() // Pastikan nama ini SAMA dengan yang di Routes
    {
        $db = \Config\Database::connect();
        
        $data = [
            'tahun_ajaran' => $this->request->getPost('tahun_ajaran'),
            'semester'     => $this->request->getPost('semester'),
            'status'       => 'Nonaktif' // Default saat input baru adalah nonaktif
        ];

        $db->table('tbl_tahun_ajaran')->insert($data);

        return redirect()->to(base_url('admin/master/tahun_ajaran'))->with('success', 'Tahun Ajaran baru berhasil ditambahkan!');
    }
public function ta_aktif($id)
{
    $db = \Config\Database::connect();
    
    // 1. Set semua menjadi Nonaktif terlebih dahulu 
    $db->table('tbl_tahun_ajaran')->update(['status' => 'Nonaktif']);
    
    // 2. Set ID yang dipilih menjadi Aktif 
    $db->table('tbl_tahun_ajaran')->where('id', $id)->update(['status' => 'Aktif']);
    
    return redirect()->back()->with('success', 'Tahun Ajaran Aktif berhasil diperbarui!');
}

public function ta_hapus($id)
{
    $db = \Config\Database::connect();
    
    // Cek apakah yang dihapus sedang aktif
    $cek = $db->table('tbl_tahun_ajaran')->where('id', $id)->get()->getRowArray();
    if ($cek['status'] == 'Aktif') {
        return redirect()->back()->with('error', 'Tidak boleh menghapus Tahun Ajaran yang sedang AKTIF!');
    }

    $db->table('tbl_tahun_ajaran')->where('id', $id)->delete();
    return redirect()->back()->with('success', 'Data Tahun Ajaran berhasil dihapus!');
}
    // JURUSAN
    public function jurusan() {
        $db = \Config\Database::connect();
        $data = [
            'title' => 'Data Jurusan',
            'jurusan' => $db->table('tbl_jurusan')->get()->getResultArray()
        ];
        return view('admin/master/jurusan', $data);
    }
    public function jurusan_simpan() 
{
    // Tambahkan baris ini untuk koneksi database
    $db = \Config\Database::connect(); 

    $db->table('tbl_jurusan')->insert([
        'kode_jurusan' => $this->request->getPost('kode_jurusan'),
        'nama_jurusan' => $this->request->getPost('nama_jurusan')
    ]);

    return redirect()->back()->with('success', 'Jurusan baru berhasil ditambahkan!');
}
public function jurusan_update($id)
{
    // Validasi target Jumat 
    if (!$this->validate([
        'kode_jurusan' => "required|is_unique[tbl_jurusan.kode_jurusan,id,$id]",
        'nama_jurusan' => 'required'
    ])) {
        return redirect()->back()->with('error', 'Data tidak valid atau kode jurusan sudah digunakan!');
    }

    $db = \Config\Database::connect();
    $db->table('tbl_jurusan')->where('id', $id)->update([
        'kode_jurusan' => strtoupper($this->request->getPost('kode_jurusan')),
        'nama_jurusan' => $this->request->getPost('nama_jurusan')
    ]);

    return redirect()->back()->with('success', 'Entitas jurusan berhasil diperbarui!');
}

public function ruangan()
{
    $db = \Config\Database::connect();
    $data = [
        'title'   => 'Data Ruangan',
        'ruangan' => $db->table('tbl_ruangan')->get()->getResultArray()
    ];
    // Menggunakan font Google Plus Jakarta Sans sesuai permintaan Bos
    return view('admin/master/ruangan', $data);
}
public function ruangan_simpan() 
{
    // Tambahkan juga di sini
    $db = \Config\Database::connect(); 

    $db->table('tbl_ruangan')->insert([
        'nama_ruangan' => $this->request->getPost('nama_ruangan')
    ]);

    return redirect()->back()->with('success', 'Ruangan berhasil didaftarkan!');
}
public function ruangan_hapus($id)
{
    $db = \Config\Database::connect();
    $db->table('tbl_ruangan')->where('id', $id)->delete();
    
    // Kirim pesan sukses untuk memancing popup
    return redirect()->to(base_url('admin/master/ruangan'))->with('success', 'Entitas ruangan telah dihapus dari database.');
}
public function kelas()
{
    // Menggunakan Model agar logic lebih bersih sesuai target Refactor 
    $modelKelas = new \App\Models\KelasModel();
    $db = \Config\Database::connect();
    
    $data = [
        'title'   => 'Manajemen Kelas',
        // Mengambil data lengkap (Join Guru, Jurusan, & Hitung Siswa) dari Model
        'kelas'   => $modelKelas->getKelasLengkap(), 
        // Data untuk dropdown Wali Kelas di Modal 
        'guru'    => $db->table('tbl_guru')->get()->getResultArray(),
        // Data untuk dropdown Jurusan di Modal agar tidak Undefined Variable
        'jurusan' => $db->table('tbl_jurusan')->get()->getResultArray() 
    ];
    
    return view('admin/master/kelas', $data);
}

public function kelas_simpan()
{
    $modelKelas = new \App\Models\KelasModel();
    
    // Validasi input wajib sesuai target target Jumat 
    if (!$this->validate([
        'nama_kelas' => 'required|is_unique[tbl_kelas.nama_kelas]',
        'id_jurusan' => 'required',
        'guru_id'    => 'required'
    ])) {
        return redirect()->back()->withInput()->with('error', 'Nama Kelas, Jurusan, dan Wali Kelas wajib diisi!');
    }

    // Menggunakan save() agar sinkron dengan allowedFields di Model
    $modelKelas->save([
        'nama_kelas' => $this->request->getPost('nama_kelas'),
        'id_jurusan' => $this->request->getPost('id_jurusan'),
        'guru_id'    => $this->request->getPost('guru_id') 
    ]);
    
    return redirect()->to(base_url('admin/master/kelas'))->with('success', 'Entitas Kelas Berhasil Didaftarkan!');
}

public function kelas_update($id)
{
    $modelKelas = new \App\Models\KelasModel();
    
    // Validasi update (mencegah duplikat kecuali id milik sendiri) 
    if (!$this->validate([
        'nama_kelas' => "required|is_unique[tbl_kelas.nama_kelas,id,$id]",
        'id_jurusan' => 'required',
        'guru_id'    => 'required'
    ])) {
        return redirect()->back()->withInput()->with('error', 'Update gagal! Pastikan form terisi benar.');
    }

    $modelKelas->update($id, [
        'nama_kelas' => $this->request->getPost('nama_kelas'),
        'id_jurusan' => $this->request->getPost('id_jurusan'),
        'guru_id'    => $this->request->getPost('guru_id') 
    ]);
    
    return redirect()->back()->with('success', 'Data entitas kelas berhasil diperbarui!');
}
public function kelas_detail($id_kelas)
{
    $db = \Config\Database::connect();
    
    // Ambil info kelas untuk judul halaman
    $kelas = $db->table('tbl_kelas')->where('id', $id_kelas)->get()->getRowArray();
    
    if (!$kelas) {
        return redirect()->back()->with('error', 'Data kelas tidak ditemukan!');
    }

    $data = [
        'title'      => 'Daftar Siswa Kelas ' . $kelas['nama_kelas'],
        'nama_kelas' => $kelas['nama_kelas'],
        // Ambil siswa yang memiliki kelas_id sesuai dengan ID kelas ini
        'siswa'      => $db->table('tbl_siswa')
                             ->where('kelas_id', $id_kelas)
                             ->get()->getResultArray()
    ];

    // Karena modul Siswa baru dikerjakan hari Selasa, 
    // pastikan Bos sudah punya view 'admin/master/siswa_list' atau sesuaikan namanya.
    return view('admin/master/siswa_list', $data); 
}
public function kelas_hapus($id)
{
    $modelKelas = new \App\Models\KelasModel();
    
    // Cek apakah data ada sebelum dihapus
    $data = $modelKelas->find($id);
    if ($data) {
        $modelKelas->delete($id);
        return redirect()->to(base_url('admin/master/kelas'))->with('success', 'Data kelas berhasil dimusnahkan! 🗑️');
    } else {
        return redirect()->to(base_url('admin/master/kelas'))->with('error', 'Data tidak ditemukan, Bos!');
    }
}
public function mapel()
{
    $db = \Config\Database::connect();
    $data = [
        'title'   => 'Mata Pelajaran',
        'mapel'   => $db->table('tbl_mapel')->get()->getResultArray(),
        'jurusan' => $db->table('tbl_jurusan')->get()->getResultArray() // Untuk centang relasi
    ];
    return view('admin/master/mapel', $data);
}

public function mapel_simpan()
{
    $db = \Config\Database::connect();
    
    // Ambil data dari form
    $nama_mapel = $this->request->getPost('nama_mapel');
    $kode_mapel = $this->request->getPost('kode_mapel');
    $kelompok   = $this->request->getPost('kelompok');
    $id_jurusan = $this->request->getPost('id_jurusan'); // Array dari checkbox

    $db->transStart(); // Mulai transaksi agar data aman

    // 1. Simpan ke tbl_mapel
    $db->table('tbl_mapel')->insert([
        'nama_mapel' => $nama_mapel,
        'kode_mapel' => $kode_mapel,
        'kelompok'   => $kelompok // Pastikan kolom ini sudah ditambah di database!
    ]);
    
    $mapel_id = $db->insertID();

    // 2. Simpan Relasi ke tbl_mapel_jurusan
    if (!empty($id_jurusan)) {
        foreach ($id_jurusan as $jurusan_id) {
            $db->table('tbl_mapel_jurusan')->insert([
                'id_mapel'   => $mapel_id,
                'id_jurusan' => $jurusan_id
            ]);
        }
    }

    $db->transComplete();

    if ($db->transStatus() === FALSE) {
        return redirect()->back()->with('error', 'Gagal menyimpan data, cek koneksi database!');
    }

    return redirect()->to(base_url('admin/master/mapel'))->with('success', 'Mata Pelajaran berhasil ditambahkan dan direlasikan!');
}
}