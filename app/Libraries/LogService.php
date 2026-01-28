<?php

namespace App\Libraries;

class LogService
{
    protected $db;
    protected $request;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();
    }

    public function catat($aksi)
    {
        // 1. Ambil Data User dari Session
        $session = session();
        $userId = $session->get('id_user') ?? 0;
        $namaUser = $session->get('nama_lengkap') ?? 'Guest/System';
        $role = $session->get('role') ?? 'Unknown';

        // 2. Ambil IP Address
        $ip = $this->request->getIPAddress();
        
        // 3. Deteksi Lokasi (Pakai API Gratis ip-api.com)
        // Jika localhost (::1 atau 127.0.0.1), set Lokasi Lokal
        $lokasi = 'Localhost / Private Network';
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            try {
                // Timeout 2 detik biar gak bikin lemot loading
                $ctx = stream_context_create(['http' => ['timeout' => 2]]); 
                $geo = json_decode(@file_get_contents("http://ip-api.com/json/{$ip}", false, $ctx));
                if ($geo && $geo->status == 'success') {
                    $lokasi = $geo->city . ', ' . $geo->regionName . ' (' . $geo->country . ')';
                }
            } catch (\Exception $e) {
                $lokasi = 'Lokasi tidak terdeteksi';
            }
        }

        // 4. Ambil Info Device (Browser/OS)
        $agent = $this->request->getUserAgent();
        $device = $agent->getBrowser() . ' ' . $agent->getVersion() . ' on ' . $agent->getPlatform();
        if ($agent->isMobile()) {
            $device .= ' (Mobile: ' . $agent->getMobile() . ')';
        }

        // 5. Simpan ke Database
        $this->db->table('tbl_log_keuangan')->insert([
            'user_id'     => $userId,
            'nama_user'   => $namaUser,
            'role'        => $role,
            'ip_address'  => $ip,
            'lokasi'      => $lokasi,
            'device_info' => $device,
            'aksi'        => $aksi,
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }
}