<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\LogService; // Panggil Library Log

class TripayCallback extends Controller
{
    protected $db;
    protected $log;
    
    // --- GANTI DENGAN PRIVATE KEY TRIPAY ANDA ---
    private $privateKey = 'BzhlB-aNk8w-AkaDG-7sVyY-96yrG'; 

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->log = new LogService(); // Init Library Log
    }

    public function index()
    {
        // 1. Ambil Data JSON dari Tripay
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // 2. Validasi Signature (Keamanan Wajib)
        $callbackSignature = $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ?? '';
        $localSignature = hash_hmac('sha256', $json, $this->privateKey);

        if ($callbackSignature !== $localSignature) {
            return $this->response->setStatusCode(403)->setBody('Invalid Signature');
        }

        // 3. Cek Event (Hanya proses jika 'payment_status' adalah 'PAID')
        if ($data['status'] == 'PAID') {
            
            $merchant_ref = $data['merchant_ref'];
            
            $transaksi = $this->db->table('tbl_transaksi')
                ->where('merchant_ref', $merchant_ref)
                ->where('status_transaksi !=', 'SUCCESS')
                ->get()->getRowArray();

            if ($transaksi) {
                // Update Transaksi & Tagihan (Biarkan sama seperti sebelumnya)
                $this->db->table('tbl_transaksi')->where('id', $transaksi['id'])->update([
                    'status_transaksi' => 'SUCCESS',
                    'updated_at'       => date('Y-m-d H:i:s')
                ]);

                $tagihan = $this->db->table('tbl_tagihan')->where('id', $transaksi['id_tagihan'])->get()->getRowArray();
                
                if ($tagihan) {
                    $bayar_baru = $tagihan['nominal_terbayar'] + $transaksi['jumlah_bayar'];
                    $status_baru = ($bayar_baru >= $tagihan['nominal_tagihan']) ? 'LUNAS' : 'CICIL';

                    $this->db->table('tbl_tagihan')->where('id', $tagihan['id'])->update([
                        'nominal_terbayar' => $bayar_baru,
                        'status_bayar'     => $status_baru
                    ]);

                    // --- INTEGRASI WHATSAPP OTOMATIS (New!) ---
                    // 1. Cari Nomor HP Siswa
                    $siswa = $this->db->table('tbl_siswa')->where('id', $transaksi['id_siswa'])->get()->getRow();
                    
                    if ($siswa && !empty($siswa->no_hp_siswa)) {
                        $wa = new \App\Libraries\WaService();
                        $rp = number_format($transaksi['jumlah_bayar'], 0, ',', '.');
                        
                        $pesanWA = "*PEMBAYARAN ONLINE SUKSES* 💚\n\n";
                        $pesanWA .= "Terima kasih, pembayaran via Tripay berhasil diverifikasi.\n";
                        $pesanWA .= "👤 Siswa: *{$siswa->nama_lengkap}*\n";
                        $pesanWA .= "💰 Nominal: *Rp $rp*\n";
                        $pesanWA .= "💳 Channel: *{$transaksi['payment_type']}*\n";
                        $pesanWA .= "ℹ️ Sisa Tagihan: *Rp " . number_format($tagihan['nominal_tagihan'] - $bayar_baru, 0, ',', '.') . "*\n\n";
                        $pesanWA .= "_Sistem Otomatis SMK Digital Indonesia_";

                        $wa->kirim($siswa->no_hp_siswa, $pesanWA);
                    }

                    // --- REKAM LOG (System Menerima Uang) ---
                    // Karena ini diakses server Tripay, session user kosong.
                    // LogService akan otomatis mencatat sebagai "Guest/System"
                    $this->log->catat("SYSTEM: Pembayaran Diterima via Tripay Ref: $merchant_ref (LUNAS/CICIL)");
                    // ----------------------------------------
                }
            }
        }

        // Respon ke Tripay bahwa kita sudah terima datanya
        return $this->response->setJSON(['success' => true]);
    }
}