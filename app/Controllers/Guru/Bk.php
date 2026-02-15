<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Libraries\WaService;

class Bk extends BaseController
{
    protected $db;
    protected $id_user;
    protected $role;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->id_user = session()->get('id_user');
        $this->role    = session()->get('role');
        $this->wa = new WaService();
    }

    private function cekAkses()
    {
        if ($this->role == 'admin') return true;

        $isBk = $this->db->table('user_roles')
            ->where('user_id', $this->id_user)
            ->where('role_id', 7) // ID 7 = BK
            ->countAllResults();

        return ($isBk > 0);
    }

    // --- FITUR 1: TRANSAKSI PELANGGARAN (Index Utama) ---
public function index()
{
    if (!$this->cekAkses()) return redirect()->to('dashboard');

    // 1. Ambil Parameter Filter
    $perPage      = $this->request->getVar('perPage') ?? 10; 
    $filter_kelas = $this->request->getVar('kelas');         
    $keyword      = $this->request->getVar('keyword');       

    // 2. Ambil Master Data Ringan (Selalu Load)
    $list_kelas = $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray();
    $setSp      = $this->db->table('tbl_set_sp')->get()->getRow();
    $jenis      = $this->db->table('tbl_master_pelanggaran')->get()->getResultArray();

    // 3. Inisialisasi Data Utama
    $rekap_siswa = [];
    $pager = null;

    // LOGIKA KRUSIAL: Query Dashboard hanya jalan jika KELAS dipilih atau ada KEYWORD
    // Ini agar ribuan data tidak tumpah sekaligus
    if ($filter_kelas || $keyword) {
        $siswaModel = new \App\Models\SiswaModel();
        
        $siswaModel->select('tbl_siswa.id, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas, 
                             COUNT(p.id) as jumlah_kasus, 
                             IFNULL(SUM(m.poin), 0) as total_minus')
                   ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
                   ->join('tbl_siswa_pelanggaran p', 'p.siswa_id = tbl_siswa.id', 'left')
                   ->join('tbl_master_pelanggaran m', 'm.id = p.pelanggaran_id', 'left')
                   ->groupBy('tbl_siswa.id');

        if ($filter_kelas) {
            $siswaModel->where('tbl_siswa.kelas_id', $filter_kelas);
        }
        
        if ($keyword) {
            $siswaModel->groupStart()
                       ->like('tbl_siswa.nama_lengkap', $keyword)
                       ->orLike('tbl_siswa.nis', $keyword)
                       ->groupEnd();
        }

        // Jalankan Pagination
        $rekap_siswa = $siswaModel->orderBy('total_minus', 'DESC')->paginate($perPage, 'rekap');
        $pager = $siswaModel->pager;
    }

    // 4. Data Modal Lapor (Juga difilter agar ringan)
    $all_siswa = [];
    if ($filter_kelas) {
        $all_siswa = $this->db->table('tbl_siswa')
                              ->where('kelas_id', $filter_kelas)
                              ->select('id, nama_lengkap, nis')
                              ->get()->getResultArray();
    }

    return view('guru/bk/index', [
        'title'         => 'Dashboard Kedisiplinan',
        'rekap_siswa'   => $rekap_siswa,
        'setSp'         => $setSp,
        'all_siswa'     => $all_siswa,
        'list_kelas'    => $list_kelas,
        'jenis'         => $jenis,
        'perPage'       => $perPage,
        'filter_kelas'  => $filter_kelas,
        'keyword'       => $keyword,
        'pager'         => $pager,
    ]);
}
public function save()
{
    if (!$this->cekAkses()) return redirect()->to('dashboard');

    $siswa_id       = $this->request->getPost('siswa_id');
    $pelanggaran_id = $this->request->getPost('pelanggaran_id');
    $catatan        = $this->request->getPost('catatan');
    $tanggal        = date('Y-m-d H:i:s');

    // 1. Simpan Transaksi Pelanggaran ke Database
    $this->db->table('tbl_siswa_pelanggaran')->insert([
        'siswa_id'       => $siswa_id,
        'pelanggaran_id' => $pelanggaran_id,
        'catatan'        => $catatan,
        'tanggal'        => $tanggal
    ]);

    // 2. Ambil Data Lengkap untuk Notifikasi (Nama Kolom: no_hp_ortu)
    $siswa = $this->db->table('tbl_siswa')
        ->select('tbl_siswa.nama_lengkap, tbl_siswa.no_hp_ortu, tbl_kelas.nama_kelas')
        ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
        ->where('tbl_siswa.id', $siswa_id)
        ->get()->getRow();

    $pelanggaran = $this->db->table('tbl_master_pelanggaran')
        ->where('id', $pelanggaran_id)
        ->get()->getRow();

    // 3. Hitung Saldo Akhir (100 - Total Poin)
    $rekap = $this->db->table('tbl_siswa_pelanggaran')
        ->selectSum('tbl_master_pelanggaran.poin', 'total')
        ->join('tbl_master_pelanggaran', 'tbl_master_pelanggaran.id = tbl_siswa_pelanggaran.pelanggaran_id')
        ->where('siswa_id', $siswa_id)
        ->get()->getRow();

    $sisa_poin = 100 - ($rekap->total ?? 0);
    $setSp     = $this->db->table('tbl_set_sp')->get()->getRow();

    // 4. Susun Format Pesan WhatsApp Profesional
    $pesan = "*NOTIFIKASI KEDISIPLINAN SISWA*\n\n";
    $pesan .= "Yth. Orang Tua dari:\n";
    $pesan .= "Nama: *{$siswa->nama_lengkap}*\n";
    $pesan .= "Kelas: {$siswa->nama_kelas}\n\n";
    $pesan .= "Memberitahukan bahwa putra/putri Anda tercatat melakukan pelanggaran:\n";
    $pesan .= "Jenis: *{$pelanggaran->nama_pelanggaran}*\n";
    $pesan .= "Poin: -{$pelanggaran->poin}\n";
    $pesan .= "Catatan: {$catatan}\n\n";
    $pesan .= "Sisa Saldo Poin: *{$sisa_poin} / 100*\n";

    // Cek Ambang Batas SP
    if ($sisa_poin <= $setSp->sp_3) {
        $pesan .= "\n*STATUS: SP 3 (DO / KELUAR)*\nSiswa telah mencapai batas poin minimal. Mohon segera datang ke sekolah.";
    } elseif ($sisa_poin <= $setSp->sp_2) {
        $pesan .= "\n*STATUS: SP 2 (PANGGILAN ORANG TUA)*\nMohon kehadiran Bapak/Ibu di ruang BK untuk pembinaan.";
    } elseif ($sisa_poin <= $setSp->sp_1) {
        $pesan .= "\n*STATUS: SP 1 (TEGURAN)*\nMohon pantau kedisiplinan putra/putri Anda agar poin tidak terus berkurang.";
    }

    $pesan .= "\n\n_Pesan otomatis via Sistem Informasi Sekolah_";

    // 5. Eksekusi Kirim menggunakan method 'kirim' dari WaService.php
    if ($siswa->no_hp_ortu) {
        // PERBAIKAN: Menggunakan method 'kirim' sesuai library
        $this->wa->kirim($siswa->no_hp_ortu, $pesan);
    }

    return redirect()->to('guru/bk')->with('success', 'Data pelanggaran berhasil disimpan dan notifikasi terkirim.');
}

    public function delete($id)
    {
        if (!$this->cekAkses()) return redirect()->to('dashboard');
        $this->db->table('tbl_siswa_pelanggaran')->where('id', $id)->delete();
        return redirect()->to('guru/bk')->with('success', 'Data dihapus.');
    }

    // --- FITUR 2: MASTER DATA PELANGGARAN (Tambahan Baru) ---
    
    public function master()
    {
        if (!$this->cekAkses()) return redirect()->to('dashboard');

        $data = $this->db->table('tbl_master_pelanggaran')
            ->orderBy('poin', 'ASC')
            ->get()->getResultArray();

        return view('guru/bk/master', [
            'title' => 'Master Jenis Pelanggaran',
            'data'  => $data
        ]);
    }

    public function saveMaster()
    {
        if (!$this->cekAkses()) return redirect()->to('dashboard');

        $id = $this->request->getPost('id');
        $data = [
            'nama_pelanggaran' => $this->request->getPost('nama_pelanggaran'),
            'poin'             => $this->request->getPost('poin'),
            'kategori'         => $this->request->getPost('kategori'),
        ];

        if ($id) {
            $this->db->table('tbl_master_pelanggaran')->where('id', $id)->update($data);
            $msg = 'Jenis pelanggaran diperbarui.';
        } else {
            $this->db->table('tbl_master_pelanggaran')->insert($data);
            $msg = 'Jenis pelanggaran ditambahkan.';
        }

        return redirect()->to('guru/bk/master')->with('success', $msg);
    }

    public function deleteMaster($id)
    {
        if (!$this->cekAkses()) return redirect()->to('dashboard');
        $this->db->table('tbl_master_pelanggaran')->where('id', $id)->delete();
        return redirect()->to('guru/bk/master')->with('success', 'Jenis pelanggaran dihapus.');
    }

    // Tambahkan fungsi baru di dalam class Bk
public function settings()
{
    if (!$this->cekAkses()) return redirect()->to('dashboard');

    $settings = $this->db->table('tbl_set_sp')->get()->getRow();
    
    return view('guru/bk/settings', [
        'title' => 'Pengaturan Ambang Batas SP',
        'set'   => $settings
    ]);
}

public function saveSettings()
{
    if (!$this->cekAkses()) return redirect()->to('dashboard');

    $data = [
        'sp_1' => $this->request->getPost('sp_1'),
        'sp_2' => $this->request->getPost('sp_2'),
        'sp_3' => $this->request->getPost('sp_3'),
    ];

    $this->db->table('tbl_set_sp')->where('id', 1)->update($data);
    return redirect()->to('guru/bk/settings')->with('success', 'Pengaturan SP berhasil diperbarui.');
}
public function detailSiswa($id)
{
    if (!$this->cekAkses()) return $this->response->setJSON([]);

    $riwayat = $this->db->table('tbl_siswa_pelanggaran')
        ->select('tbl_siswa_pelanggaran.id, tbl_siswa_pelanggaran.tanggal, tbl_siswa_pelanggaran.catatan, tbl_master_pelanggaran.nama_pelanggaran, tbl_master_pelanggaran.poin')
        ->join('tbl_master_pelanggaran', 'tbl_master_pelanggaran.id = tbl_siswa_pelanggaran.pelanggaran_id')
        ->where('tbl_siswa_pelanggaran.siswa_id', $id)
        ->orderBy('tbl_siswa_pelanggaran.tanggal', 'DESC')
        ->get()->getResultArray();

    return $this->response->setJSON($riwayat);
}
}