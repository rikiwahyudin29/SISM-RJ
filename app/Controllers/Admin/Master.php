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
    $db = \Config\Database::connect();
    
    $data = [
        'title' => 'Data Kelas',
        'kelas' => $db->table('tbl_kelas')
                      // Sesuaikan dengan kolom di tabel guru Bos (misal: nama_lengkap)
                      ->select('tbl_kelas.*, tbl_guru.nama_lengkap as nama_guru')
                      // REVISI: Pakai guru_id sesuai screenshot database Bos!
                      ->join('tbl_guru', 'tbl_guru.id = tbl_kelas.guru_id', 'left') 
                      ->get()->getResultArray(),
        'guru'  => $db->table('tbl_guru')->get()->getResultArray()
    ];
    
    return view('admin/master/kelas', $data);
}

public function kelas_simpan()
{
    $db = \Config\Database::connect();
    $db->table('tbl_kelas')->insert([
        'nama_kelas' => $this->request->getPost('nama_kelas'),
        // REVISI: Simpan ke kolom guru_id
        'guru_id'    => $this->request->getPost('id_guru') 
    ]);
    
    return redirect()->to(base_url('admin/master/kelas'))->with('success', 'Entitas Kelas Berhasil Didaftarkan!');
}
public function kelas_update($id)
{
    $db = \Config\Database::connect();
    
    // Sesuaikan kolom guru_id sesuai database Bos tadi
    $db->table('tbl_kelas')->where('id', $id)->update([
        'nama_kelas' => $this->request->getPost('nama_kelas'),
        'guru_id'    => $this->request->getPost('guru_id') 
    ]);
    
    return redirect()->back()->with('success', 'Data entitas kelas berhasil diperbarui!');
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