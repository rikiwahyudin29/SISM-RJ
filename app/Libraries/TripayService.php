<?php

namespace App\Libraries;

class TripayService
{
    private $apiKey;
    private $privateKey;
    private $merchantCode;
    private $mode;

    public function __construct()
    {
        // AMBIL DATA DARI DATABASE TBL_SEKOLAH (ID 1)
        $db = \Config\Database::connect();
        $config = $db->table('tbl_sekolah')->where('id', 1)->get()->getRow();

        if ($config) {
            $this->apiKey       = $config->tripay_api_key;
            $this->privateKey   = $config->tripay_private_key;
            $this->merchantCode = $config->tripay_merchant_code;
            
            // Konversi enum 'Sandbox'/'Production' ke lowercase ('sandbox'/'production')
            $this->mode         = strtolower($config->mode_transaksi); 
        } else {
            // Fallback jika data kosong (hindari error)
            $this->apiKey       = '';
            $this->privateKey   = '';
            $this->merchantCode = '';
            $this->mode         = 'sandbox';
        }
    }

    public function getBaseUrl()
    {
        return ($this->mode === 'production') 
            ? 'https://tripay.co.id/api/' 
            : 'https://tripay.co.id/api-sandbox/';
    }

    // 1. AMBIL DAFTAR CHANNEL PEMBAYARAN (QRIS, ALFAMART, DLL)
    public function getChannels()
    {
        // Cek jika API Key kosong
        if (empty($this->apiKey)) {
            return [];
        }

        $url = $this->getBaseUrl() . 'merchant/payment-channel';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->apiKey
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            log_message('error', 'Tripay Error: ' . $err);
            return [];
        }
        
        $result = json_decode($response, true);
        return (isset($result['success']) && $result['success']) ? $result['data'] : [];
    }

    // 2. REQUEST TRANSAKSI BARU
    public function requestTransaction($data)
    {
        // Cek Kelengkapan Config
        if (empty($this->apiKey) || empty($this->privateKey) || empty($this->merchantCode)) {
            return ['success' => false, 'message' => 'Konfigurasi Tripay di Database Sekolah Belum Lengkap!'];
        }

        $url = $this->getBaseUrl() . 'transaction/create';
        
        // Buat Signature (Wajib: merchant_code + merchant_ref + amount)
        $signature = hash_hmac('sha256', $this->merchantCode . $data['merchant_ref'] . $data['amount'], $this->privateKey);

        $payload = [
            'method'         => $data['method'], // Kode Channel (misal: BRIVA)
            'merchant_ref'   => $data['merchant_ref'],
            'amount'         => $data['amount'],
            'customer_name'  => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'order_items'    => $data['order_items'],
            'return_url'     => base_url('siswa/keuangan'), // Balik kemana setelah bayar
            'expired_time'   => (time() + (24 * 60 * 60)), // Expire 24 jam
            'signature'      => $signature
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->apiKey
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            log_message('error', 'Tripay Transaksi Error: ' . $err);
            return ['success' => false, 'message' => $err];
        }

        return json_decode($response, true);
    }
}