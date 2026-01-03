<?php

namespace App\Libraries;

use App\Models\PengaturanModel;

class TelegramService
{
    private $token;

    public function __construct()
    {
        // Panggil Model Pengaturan
        $model = new PengaturanModel();
        
        // Cari baris di database yang kuncinya 'telegram_token'
        // Ingat: tabelmu strukturnya id, kunci, nilai
        $data = $model->where('kunci', 'telegram_token')->first();

        // Cek apakah datanya ketemu dan kolom 'nilai' tidak kosong
        if ($data && !empty($data['nilai'])) {
            $this->token = $data['nilai']; 
        } else {
            // Default kosong jika belum diisi di dashboard
            $this->token = ''; 
        }
    }

    public function kirim($chatId, $pesan)
    {
        // Kalau token kosong, langsung berhenti (jangan kirim)
        if (empty($this->token)) {
            log_message('error', 'Gagal kirim Telegram: Token belum disetting di Dashboard Admin.');
            return false;
        }

        $url = "https://api.telegram.org/bot" . $this->token . "/sendMessage";
        
        $data = [
            'chat_id'    => $chatId,
            'text'       => $pesan,
            'parse_mode' => 'Markdown'
        ];

        $client = \Config\Services::curlrequest();
        
        try {
            $client->request('POST', $url, ['form_params' => $data]);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Telegram Error: ' . $e->getMessage());
            return false;
        }
    }
}