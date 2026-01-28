<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Libraries\TripayService; // Panggil Library Tripay
use App\Libraries\LogService;    // Panggil Library Log (Mata-mata)

class Keuangan extends BaseController
{
    protected $db;
    protected $tripay;
    protected $log;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->tripay = new TripayService(); // Init Library Tripay
        $this->log = new LogService();       // Init Library Log
    }

    public function index()
    {
        $id_user = session()->get('id_user');

        // 1. Cari Data Siswa
        $siswa = $this->db->table('tbl_siswa')
            ->select('tbl_siswa.id as id_siswa, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left') 
            ->where('tbl_siswa.user_id', $id_user)
            ->get()->getRowArray();

        if (!$siswa) return "Error: Data siswa tidak ditemukan.";
        $id_siswa_asli = $siswa['id_siswa'];

        // 2. Tagihan & Riwayat
        $tagihan = $this->db->table('tbl_tagihan')
            ->select('tbl_tagihan.*, tbl_pos_bayar.nama_pos')
            ->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->where('tbl_tagihan.id_siswa', $id_siswa_asli)
            ->where('tbl_tagihan.status_bayar !=', 'LUNAS')
            ->get()->getResultArray();

        $riwayat = $this->db->table('tbl_transaksi')
            ->select('tbl_transaksi.*, tbl_pos_bayar.nama_pos, tbl_tagihan.keterangan')
            ->join('tbl_tagihan', 'tbl_tagihan.id = tbl_transaksi.id_tagihan')
            ->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->where('tbl_transaksi.id_siswa', $id_siswa_asli)
            ->orderBy('tbl_transaksi.created_at', 'DESC')
            ->get()->getResultArray();

        $total_tunggakan = 0;
        foreach ($tagihan as $t) $total_tunggakan += ($t['nominal_tagihan'] - $t['nominal_terbayar']);

        // 3. AMBIL CHANNEL PEMBAYARAN TRIPAY (Untuk Modal)
        $channels = $this->tripay->getChannels();

        return view('siswa/keuangan/index', [
            'title'     => 'Keuangan Saya',
            'siswa'     => $siswa,
            'tagihan'   => $tagihan,
            'riwayat'   => $riwayat,
            'tunggakan' => $total_tunggakan,
            'channels'  => $channels
        ]);
    }

    // --- PROSES BAYAR TRIPAY ---
    public function bayar_online()
    {
        $id_tagihan  = $this->request->getPost('id_tagihan');
        $kode_metode = $this->request->getPost('method'); // Contoh: BRIVA, QRIS
        
        // Validasi Input Dasar
        if(!$id_tagihan || !$kode_metode) {
            return redirect()->back()->with('error', 'Data pembayaran tidak lengkap.');
        }

        // 1. Ambil Data Tagihan & Siswa Lengkap
        $tagihan = $this->db->table('tbl_tagihan')
            ->select('tbl_tagihan.*, tbl_pos_bayar.nama_pos, tbl_siswa.id as id_siswa_asli, tbl_siswa.nama_lengkap, tbl_siswa.email_siswa, tbl_siswa.no_hp_siswa')
            ->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_tagihan.id_siswa')
            ->where('tbl_tagihan.id', $id_tagihan)
            ->get()->getRowArray();

        if (!$tagihan) return redirect()->back()->with('error', 'Tagihan tidak valid.');

        // 2. LOGIKA CICILAN (Baru)
        $sisa_tagihan = $tagihan['nominal_tagihan'] - $tagihan['nominal_terbayar'];

        $input_nominal = $this->request->getPost('nominal_bayar');
        $nominal_fix   = $input_nominal ? (int)$input_nominal : $sisa_tagihan;

        // Validasi A: Jangan sampai user bayar melebihi sisa hutang
        if ($nominal_fix > $sisa_tagihan) {
            $nominal_fix = $sisa_tagihan;
        }

        // Validasi B: Minimal pembayaran
        if ($nominal_fix < 10000) {
            return redirect()->back()->with('error', 'Minimal pembayaran adalah Rp 10.000');
        }

        // Generate Merchant Ref Unik
        $merchant_ref  = 'INV-' . date('ymdHis') . rand(100,999); 

        // 3. Siapkan Payload ke Tripay
        $payload = [
            'method'         => $kode_metode,
            'merchant_ref'   => $merchant_ref,
            'amount'         => $nominal_fix,
            'customer_name'  => $tagihan['nama_lengkap'],
            'customer_email' => $tagihan['email_siswa'] ?? 'siswa@sekolah.sch.id',
            'customer_phone' => $tagihan['no_hp_siswa'] ?? '08123456789',
            'order_items'    => [
                [
                    'sku'      => 'TAG-' . $tagihan['id'],
                    'name'     => $tagihan['nama_pos'] . ' (Cicilan)',
                    'price'    => $nominal_fix,
                    'quantity' => 1
                ]
            ]
        ];

        // 4. Tembak API Tripay
        $result = $this->tripay->requestTransaction($payload);

        if ($result['success'] == false) {
            return redirect()->back()->with('error', 'Gagal koneksi ke Tripay: ' . ($result['message'] ?? 'Unknown Error'));
        }

        $data_tripay = $result['data'];

        // 5. Simpan ke Database (Status: UNPAID)
        $this->db->table('tbl_transaksi')->insert([
            'merchant_ref'      => $merchant_ref,
            'reference'         => $data_tripay['reference'], // ID Tripay
            'id_tagihan'        => $id_tagihan,
            'id_siswa'          => $tagihan['id_siswa_asli'],
            'jumlah_bayar'      => $nominal_fix,
            'fee_admin'         => $data_tripay['total_fee'] - $data_tripay['amount'],
            'total_bayar'       => $data_tripay['total_fee'],
            'payment_type'      => $data_tripay['payment_name'], 
            'status_transaksi'  => 'UNPAID',
            'checkout_url'      => $data_tripay['checkout_url'],
            'petugas_id'        => 0, // 0 artinya Mandiri Online
            'created_at'        => date('Y-m-d H:i:s')
        ]);

        // --- REKAM LOG (Siswa Request Pembayaran) ---
        $this->log->catat("Membuat Invoice Tripay ($kode_metode) Ref: $merchant_ref senilai Rp " . number_format($nominal_fix, 0, ',', '.'));
        // --------------------------------------------

        // 6. Redirect Siswa ke Halaman Bayar Tripay
        return redirect()->to($data_tripay['checkout_url']);
    }
}