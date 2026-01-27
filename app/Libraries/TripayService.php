<?php

namespace App\Libraries;

class TripayService
{
    // --- ISI DENGAN DATA DARI DASHBOARD TRIPAY ANDA ---
    private $apiKey       = 'DEV-rsVpftAi4jRUkGYGkU5zj15OCaT62kPmq9Sa36IU'; // API Key
    private $privateKey   = 'BzhlB-aNk8w-AkaDG-7sVyY-96yrG';     // Private Key
    private $merchantCode = 'T39326';      // Kode Merchant
    private $mode         = 'sandbox';   // 'sandbox' atau 'production'

    public function getBaseUrl()
    {
        return ($this->mode === 'production') 
            ? 'https://tripay.co.id/api/' 
            : 'https://tripay.co.id/api-sandbox/';
    }

    // 1. AMBIL DAFTAR CHANNEL PEMBAYARAN (QRIS, ALFAMART, DLL)
    public function getChannels()
    {
        $url = $this->getBaseUrl() . 'merchant/payment-channel';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
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

        if ($err) return [];
        
        $result = json_decode($response, true);
        return $result['success'] ? $result['data'] : [];
    }

    // 2. REQUEST TRANSAKSI BARU
    public function requestTransaction($data)
    {
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
            CURLOPT_TIMEOUT => 0,
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

        if ($err) return ['success' => false, 'message' => $err];

        return json_decode($response, true);
    }
}