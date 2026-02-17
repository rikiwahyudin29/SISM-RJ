<?php

namespace App\Libraries;

class TelegramService
{
    private $token;

    public function __construct()
    {
        $db = \Config\Database::connect();
        // Ambil token bot dari tbl_sekolah ID 1
        $config = $db->table('tbl_sekolah')->where('id', 1)->get()->getRow();

        if ($config && !empty($config->tele_bot_token)) {
            $this->token = $config->tele_bot_token;
        } else {
            $this->token = '';
        }
    }

    public function kirim($chatId, $pesan)
    {
        // Kalau token atau Chat ID kosong, berhenti
        if (empty($this->token) || empty($chatId)) {
            log_message('error', 'Gagal kirim Telegram: Token atau Chat ID belum disetting di Data Sekolah.');
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