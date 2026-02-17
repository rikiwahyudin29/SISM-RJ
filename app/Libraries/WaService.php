<?php

namespace App\Libraries;

class WaService
{
    private $token;
    private $url;

    public function __construct()
    {
        $db = \Config\Database::connect();
        // Ambil konfigurasi dari tbl_sekolah ID 1
        $config = $db->table('tbl_sekolah')->where('id', 1)->get()->getRow();

        if ($config) {
            $this->token = $config->wa_api_token;
            // Jika URL kosong di DB, pakai default Fonnte, jika ada pakai dari DB
            $this->url   = !empty($config->wa_api_url) ? $config->wa_api_url : 'https://api.fonnte.com/send';
        }
    }

    public function kirim($nomor, $pesan)
    {
        // Cek jika token atau nomor kosong
        if (empty($this->token) || empty($nomor)) {
            log_message('error', 'Gagal kirim WA: Token atau Nomor tujuan kosong.');
            return false;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $nomor,
                'message' => $pesan,
                'countryCode' => '62', // Otomatis ubah 08 jadi 62
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $this->token // Token dari Database
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            log_message('error', 'CURL Error WA: ' . curl_error($curl));
        }

        curl_close($curl);

        return $response;
    }
}