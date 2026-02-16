<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Google\Client;
use Google\Service\Calendar;

class Google extends BaseController
{
    protected $db;
    protected $client;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        
        $this->client = new Client();
        // File JSON harus di folder root (sejajar dengan spark)
        $this->client->setAuthConfig(ROOTPATH . 'client_secret.json'); 
        $this->client->addScope(Calendar::CALENDAR);
        $this->client->setAccessType('offline'); 
        $this->client->setPrompt('select_account consent');
        $this->client->setRedirectUri(base_url('admin/google/callback'));
    }

    public function connect()
    {
        return redirect()->to($this->client->createAuthUrl());
    }

    public function callback()
    {
        $code = $this->request->getGet('code');
        if ($code) {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (!isset($token['error'])) {
                // Simpan SELURUH array token sebagai JSON agar formatnya valid
                $idUser = session()->get('id_user');
                $this->db->table('tbl_guru')
                         ->where('id_user', $idUser)
                         ->update(['google_refresh_token' => json_encode($token)]);

                return redirect()->to('admin/elearning')->with('success', 'Akun Google berhasil terhubung!');
            }
        }
        return redirect()->to('admin/elearning')->with('error', 'Gagal menghubungkan akun Google.');
    }
}