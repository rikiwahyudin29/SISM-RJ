<?php

namespace App\Controllers\Admin\Keuangan;

use App\Controllers\BaseController;
use App\Libraries\LogService; // Pastikan Library Log dipanggil
use App\Libraries\WaService;

class Pembayaran extends BaseController
{
    protected $db;
    protected $log;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->log = new LogService(); // Init Logger
    }

    // 1. HALAMAN PENCARIAN SISWA
    public function index()
    {
        $keyword = $this->request->getGet('q');
        $siswa = [];

        if ($keyword) {
            $builder = $this->db->table('tbl_siswa');
            $builder->select('tbl_siswa.*, tbl_kelas.nama_kelas');
            
            if ($this->db->fieldExists('kelas_id', 'tbl_siswa')) {
                $builder->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id');
            } else {
                $builder->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.id_kelas');
            }

            $siswa = $builder->groupStart()
                        ->like('nama_lengkap', $keyword)
                        ->orLike('nis', $keyword)
                    ->groupEnd()
                    ->limit(10)
                    ->get()->getResultArray();
        }

        return view('admin/keuangan/pembayaran/index', [
            'title' => 'Kasir Pembayaran',
            'siswa' => $siswa,
            'keyword' => $keyword
        ]);
    }

    // 2. HALAMAN TRANSAKSI PER SISWA
    public function transaksi($id_siswa)
    {
        $builder = $this->db->table('tbl_siswa');
        $builder->select('tbl_siswa.*, tbl_kelas.nama_kelas');
        
        if ($this->db->fieldExists('kelas_id', 'tbl_siswa')) {
            $builder->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id');
        } else {
            $builder->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.id_kelas');
        }

        $siswa = $builder->where('tbl_siswa.id', $id_siswa)->get()->getRowArray();

        if (!$siswa) return redirect()->to('admin/keuangan/pembayaran');

        // Tagihan
        $tagihan = $this->db->table('tbl_tagihan')
            ->select('tbl_tagihan.*, tbl_jenis_bayar.id_pos_bayar, tbl_jenis_bayar.tipe_bayar, tbl_pos_bayar.nama_pos')
            ->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->where('tbl_tagihan.id_siswa', $id_siswa)
            ->orderBy('tbl_tagihan.status_bayar', 'DESC')
            ->orderBy('tbl_tagihan.id', 'ASC')
            ->get()->getResultArray();

        // Riwayat
        $riwayat = $this->db->table('tbl_transaksi')
            ->select('tbl_transaksi.*, tbl_pos_bayar.nama_pos, tbl_tagihan.keterangan')
            ->join('tbl_tagihan', 'tbl_tagihan.id = tbl_transaksi.id_tagihan')
            ->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->where('tbl_transaksi.id_siswa', $id_siswa)
            ->orderBy('tbl_transaksi.created_at', 'DESC')
            ->limit(10) 
            ->get()->getResultArray();

        return view('admin/keuangan/pembayaran/transaksi', [
            'siswa'   => $siswa,
            'tagihan' => $tagihan,
            'riwayat' => $riwayat
        ]);
    }

    // 3. PROSES BAYAR
    public function proses_bayar()
    {
        $id_tagihan = $this->request->getPost('id_tagihan');
        $bayar      = (float) str_replace('.', '', $this->request->getPost('jumlah_bayar'));
        $id_siswa   = $this->request->getPost('id_siswa');
        
        $tagihan = $this->db->table('tbl_tagihan')->where('id', $id_tagihan)->get()->getRow();
        if(!$tagihan) return redirect()->back()->with('error', 'Tagihan error');

        // Validasi Sisa
        $sisa = $tagihan->nominal_tagihan - $tagihan->nominal_terbayar;
        if($bayar > ($sisa + 1)) return redirect()->back()->with('error', 'Kelebihan bayar!');

        // Insert Transaksi
        $this->db->table('tbl_transaksi')->insert([
            'kode_transaksi' => 'TRX-' . date('ymdHis') . rand(10,99),
            'id_tagihan'     => $id_tagihan,
            'id_siswa'       => $id_siswa,
            'jumlah_bayar'   => $bayar,
            'petugas_id'     => session()->get('id_user') ?? 0,
            'created_at'     => date('Y-m-d H:i:s')
        ]);
        
        $trx_id = $this->db->insertID();

        // Update Tagihan
        $total_terbayar = $tagihan->nominal_terbayar + $bayar;
        $status_baru = ($total_terbayar >= $tagihan->nominal_tagihan) ? 'LUNAS' : 'CICIL';

        $this->db->table('tbl_tagihan')->where('id', $id_tagihan)->update([
            'nominal_terbayar' => $total_terbayar,
            'status_bayar'     => $status_baru
        ]);

        // LOG & NOTIFIKASI WA
        // 1. Ambil Data Siswa (Nama & No HP)
        $siswaLog = $this->db->table('tbl_siswa')
            ->select('nama_lengkap, no_hp_siswa, nis') // Pastikan kolom no_hp_siswa ada
            ->where('id', $id_siswa)->get()->getRow();
            
        $namaSiswa = $siswaLog ? $siswaLog->nama_lengkap : 'ID:'.$id_siswa;
        $nomorHp   = $siswaLog ? $siswaLog->no_hp_siswa : ''; // Bisa diganti no_hp_ortu juga

        // 2. Catat Log
        $this->log->catat("Menerima Pembayaran Tunai Rp " . number_format($bayar, 0, ',', '.') . " dari siswa $namaSiswa");

        // 3. KIRIM WHATSAPP (New!)
        if (!empty($nomorHp)) {
            $wa = new \App\Libraries\WaService();
            $tgl = date('d-m-Y H:i');
            $rp  = number_format($bayar, 0, ',', '.');
            
            $pesanWA = "*PEMBAYARAN DITERIMA* ✅\n\n";
            $pesanWA .= "Halo, pembayaran sekolah telah kami terima.\n";
            $pesanWA .= "👤 Siswa: *$namaSiswa*\n";
            $pesanWA .= "💰 Nominal: *Rp $rp*\n";
            $pesanWA .= "📅 Tanggal: $tgl\n";
            $pesanWA .= "ℹ️ Status: *$status_baru*\n\n";
            $pesanWA .= "Terima kasih. \n_Keuangan SMK Digital Indonesia_";

            $wa->kirim($nomorHp, $pesanWA);
        }

        return redirect()->back()->with('success', 'Pembayaran Berhasil & Notifikasi WA Terkirim!')->with('new_trx_id', $trx_id);
    }

    // 4. BATALKAN TRANSAKSI (AUDIT DETAIL)
    public function batal()
    {
        $id_transaksi = $this->request->getPost('id_transaksi');
        
        // 1. Ambil data transaksi
        $trx = $this->db->table('tbl_transaksi')->where('id', $id_transaksi)->get()->getRow();
        if(!$trx) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        // 2. Ambil tagihan terkait
        $tagihan = $this->db->table('tbl_tagihan')->where('id', $trx->id_tagihan)->get()->getRow();

        // 3. AMBIL DATA SISWA & NOMINAL (Supaya Log Jelas)
        $siswa = $this->db->table('tbl_siswa')->where('id', $trx->id_siswa)->get()->getRow();
        $nama_siswa = $siswa ? $siswa->nama_lengkap : 'Siswa Tidak Dikenal';
        $nominal_rp = number_format($trx->jumlah_bayar, 0, ',', '.');

        // 4. Kembalikan saldo tagihan (Reverse)
        $saldo_baru = $tagihan->nominal_terbayar - $trx->jumlah_bayar;
        
        // Tentukan status baru (Mundur)
        if ($saldo_baru <= 0) {
            $saldo_baru = 0;
            $status_baru = 'BELUM';
        } elseif ($saldo_baru < $tagihan->nominal_tagihan) {
            $status_baru = 'CICIL';
        } else {
            $status_baru = 'LUNAS'; 
        }

        // 5. Update DB
        $this->db->table('tbl_tagihan')->where('id', $trx->id_tagihan)->update([
            'nominal_terbayar' => $saldo_baru,
            'status_bayar'     => $status_baru
        ]);

        // 6. Hapus Log Transaksi
        $this->db->table('tbl_transaksi')->where('id', $id_transaksi)->delete();

        // 7. REKAM LOG AKTIVITAS (AUDIT TRAIL LENGKAP)
        $this->log->catat("MENGHAPUS Pembayaran a.n $nama_siswa sebesar Rp $nominal_rp (ID Trx: $id_transaksi). Saldo dikembalikan.");

        return redirect()->back()->with('success', 'Transaksi berhasil dibatalkan. Saldo tagihan dikembalikan.');
    }

    // 5. CETAK STRUK
    public function cetak($id_transaksi)
    {
        $mode = $this->request->getGet('mode') ?? 'thermal'; 

        $transaksi = $this->db->table('tbl_transaksi')
            ->select('tbl_transaksi.*, 
                      tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas,
                      tbl_pos_bayar.nama_pos, tbl_tagihan.keterangan as ket_tagihan,
                      tbl_users.nama_lengkap as nama_petugas') 
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_transaksi.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = ' . ($this->db->fieldExists('kelas_id', 'tbl_siswa') ? 'tbl_siswa.kelas_id' : 'tbl_siswa.id_kelas'))
            ->join('tbl_tagihan', 'tbl_tagihan.id = tbl_transaksi.id_tagihan')
            ->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->join('tbl_users', 'tbl_users.id = tbl_transaksi.petugas_id', 'left')
            ->where('tbl_transaksi.id', $id_transaksi)
            ->get()->getRowArray();

        if (!$transaksi) return "Transaksi hilang!";

        $data = [
            'trx' => $transaksi,
            'sekolah' => [
                'nama' => 'SMK DIGITAL INDONESIA', 
                'alamat' => 'Jl. Teknologi No. 1, Jakarta Selatan',
                'telp' => '(021) 12345678'
            ]
        ];

        if ($mode == 'a4') {
            return view('admin/keuangan/pembayaran/cetak_a4', $data);
        } else {
            return view('admin/keuangan/pembayaran/cetak_thermal', $data);
        }
    }
}