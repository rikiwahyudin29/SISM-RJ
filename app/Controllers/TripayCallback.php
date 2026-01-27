<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TripayCallback extends Controller
{
    protected $db;
    
    // --- GANTI DENGAN PRIVATE KEY TRIPAY ANDA ---
    private $privateKey = 'Isi_Private_Key_Tripay_Disini'; 

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Ambil Data JSON dari Tripay
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // 2. Validasi Signature (Keamanan Wajib)
        // Tripay mengirim signature di header 'X-Callback-Signature'
        $callbackSignature = $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ?? '';
        $localSignature = hash_hmac('sha256', $json, $this->privateKey);

        if ($callbackSignature !== $localSignature) {
            return $this->response->setStatusCode(403)->setBody('Invalid Signature'); // Tolak jika palsu
        }

        // 3. Cek Event (Hanya proses jika 'payment_status' adalah 'PAID')
        if ($data['status'] == 'PAID') {
            
            $merchant_ref = $data['merchant_ref']; // Kode Invoice Kita
            
            // Cari Transaksi Berdasarkan Merchant Ref
            $transaksi = $this->db->table('tbl_transaksi')
                ->where('merchant_ref', $merchant_ref)
                ->where('status_transaksi !=', 'SUCCESS') // Cegah double update
                ->get()->getRowArray();

            if ($transaksi) {
                // UPDATE 1: Tabel Transaksi jadi SUCCESS
                $this->db->table('tbl_transaksi')->where('id', $transaksi['id'])->update([
                    'status_transaksi' => 'SUCCESS',
                    'updated_at'       => date('Y-m-d H:i:s')
                ]);

                // UPDATE 2: Tabel Tagihan (Tambah Saldo & Cek Lunas)
                $tagihan = $this->db->table('tbl_tagihan')->where('id', $transaksi['id_tagihan'])->get()->getRowArray();
                
                if ($tagihan) {
                    $bayar_baru = $tagihan['nominal_terbayar'] + $transaksi['jumlah_bayar']; // Pakai jumlah_bayar murni (tanpa admin fee)
                    
                    // Cek Status Lunas/Cicil
                    $status_baru = ($bayar_baru >= $tagihan['nominal_tagihan']) ? 'LUNAS' : 'CICIL';

                    $this->db->table('tbl_tagihan')->where('id', $tagihan['id'])->update([
                        'nominal_terbayar' => $bayar_baru,
                        'status_bayar'     => $status_baru
                    ]);
                }
            }
        }

        // Respon ke Tripay bahwa kita sudah terima datanya
        return $this->response->setJSON(['success' => true]);
    }
}