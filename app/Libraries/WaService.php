<?php

namespace App\Libraries;

class WaService
{
    // --- GANTI TOKEN INI DENGAN TOKEN FONNTE ANDA ---
    // Daftar gratis di https://fonnte.com/ untuk dapat token
    private $token = 'Ab345WhQTE5umcSJgGbG'; 

    public function kirim($nomor, $pesan)
    {
        if (empty($nomor)) return false;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
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
                'Authorization: ' . $this->token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}