<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Google\Client as GoogleClient;
use Google\Service\Oauth2;

class Auth extends BaseController
{
    protected $googleClient;

    public function __construct()
    {
        // Konfigurasi Google Client
        $this->googleClient = new GoogleClient();
        $this->googleClient->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $this->googleClient->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $this->googleClient->setRedirectUri(base_url('auth/google_callback'));
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url(session()->get('role_active') . '/dashboard'));
        }
        return view('auth/login');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'get') {
            return $this->index();
        }
        return $this->auth();
    }

    // --- LOGIKA LOGIN MANUAL ---
    public function auth()
    {
        $model = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->groupStart()
                      ->where('username', $username)
                      ->orWhere('email', $username)
                      ->groupEnd()
                      ->first();

        if ($user) {
            $verify_pass = password_verify($password, $user['password']);
            if (!$verify_pass && $password == $user['password']) { $verify_pass = true; } // Bypass dev

            if ($verify_pass) {
                // 1. CEK MULTI-ROLE
                $rawRoles = $model->getRoles($user['id']);
                if (empty($rawRoles)) {
                    return redirect()->back()->with('error', 'Akun valid tapi tidak memiliki Jabatan.');
                }
                $roles = array_column($rawRoles, 'role_key');

                // 2. CEK ID TELEGRAM (GANTI DARI WA KE TELEGRAM)
                if (empty($user['telegram_chat_id'])) {
                    return redirect()->back()->with('error', 'ID Telegram belum terhubung. Silakan hubungi Admin untuk update data.');
                }

                // 3. KIRIM OTP VIA TELEGRAM
                return $this->_initiateOTP($user, $user['nama_lengkap'], $roles);
            }
        }

        return redirect()->back()->with('error', 'Username atau Password salah!');
    }

    // --- LOGIKA LOGIN GOOGLE ---
    public function google_login()
    {
        return redirect()->to($this->googleClient->createAuthUrl());
    }

    public function google_callback()
    {
        $code = $this->request->getVar('code');
        if(!$code){ return redirect()->to(base_url('login'))->with('error', 'Gagal login Google.'); }

        $token = $this->googleClient->fetchAccessTokenWithAuthCode($code);

        if (!isset($token['error'])) {
            $this->googleClient->setAccessToken($token['access_token']);
            $googleService = new Oauth2($this->googleClient);
            $googleUser = $googleService->userinfo->get();

            $model = new UserModel();
            $user = $model->where('email', $googleUser->email)->first();

            if ($user) {
                $rawRoles = $model->getRoles($user['id']);
                if (empty($rawRoles)) { return redirect()->to(base_url('login'))->with('error', 'Tidak punya jabatan.'); }
                $roles = array_column($rawRoles, 'role_key');

                // CEK TELEGRAM
                if (empty($user['telegram_chat_id'])) {
                    return redirect()->to(base_url('login'))->with('error', 'ID Telegram kosong. Hubungi Admin.');
                }

                return $this->_initiateOTP($user, $googleUser->name, $roles);
            } else {
                return redirect()->to(base_url('login'))->with('error', 'Email tidak terdaftar.');
            }
        }
        return redirect()->to(base_url('login'))->with('error', 'Gagal autentikasi Google.');
    }

    // --- VERIFIKASI OTP ---
    public function verify_otp()
    {
        if (!session()->get('temp_user_id')) { return redirect()->to(base_url('login')); }
        return view('auth/verify_otp');
    }

    public function check_otp()
    {
        $input_otp = $this->request->getPost('otp_code');
        
        // Cek Kesesuaian OTP
        if ($input_otp == session()->get('otp_code')) {
            
            // 1. Ambil Data Session Sementara
            $roles = session()->get('temp_roles');
            $idUser = session()->get('temp_user_id');
            $namaUser = session()->get('temp_nama');
            $chatId = session()->get('temp_telegram'); // ID Telegram User

            // 2. Tentukan Role Utama
            $activeRole = 'siswa';
            if (in_array('admin', $roles)) $activeRole = 'admin';
            elseif (in_array('kepsek', $roles)) $activeRole = 'kepsek';
            elseif (in_array('guru', $roles)) $activeRole = 'guru';

            // 3. Set Session Utama (Resmi Login)
            session()->set([
                'id_user'      => $idUser,
                'username'     => session()->get('temp_username'),
                'nama_lengkap' => $namaUser,
                'roles'        => $roles,
                'role_active'  => $activeRole,
                'logged_in'    => true,
            ]);

            // =====================================================
            // 🔥 FITUR BARU: KIRIM NOTIFIKASI LOGIN SUKSES
            // =====================================================
            $this->_kirimNotifLogin($chatId, $namaUser); 
            // =====================================================

            // 4. Bersihkan Session Sampah
            session()->remove(['temp_user_id', 'temp_username', 'temp_nama', 'temp_roles', 'otp_code', 'temp_telegram']);
            
            // 5. Redirect ke Dashboard
            return redirect()->to(base_url($activeRole . '/dashboard'));
        }

        return redirect()->back()->with('error', 'Kode OTP salah!');
    }

    /**
     * FUNGSI TAMBAHAN: DETEKSI PERANGKAT & LOKASI (IP)
     */
    private function _kirimNotifLogin($chatId, $namaUser)
    {
        // 1. Deteksi User Agent (Perangkat)
        $agent = $this->request->getUserAgent();
        
        if ($agent->isBrowser()) {
            $device = $agent->getBrowser() . ' ' . $agent->getVersion();
        } elseif ($agent->isRobot()) {
            $device = $agent->getRobot();
        } elseif ($agent->isMobile()) {
            $device = $agent->getMobile();
        } else {
            $device = 'Aplikasi Tidak Dikenal';
        }
        
        $platform = $agent->getPlatform(); // Windows, Android, iOS, dll
        
        // 2. Deteksi IP Address
        $ip = $this->request->getIPAddress();
        
        // 3. Waktu Login
        $waktu = date('d-m-Y H:i:s');

        // 4. Susun Pesan
        $pesan = "🔔 *LOGIN ALERT - SISM RJ*\n\n";
        $pesan .= "Halo *$namaUser*,\n";
        $pesan .= "Akun Anda baru saja berhasil login.\n\n";
        $pesan .= "📱 *Perangkat:* $device ($platform)\n";
        $pesan .= "🌐 *IP Address:* $ip\n";
        $pesan .= "📅 *Waktu:* $waktu\n\n";
        $pesan .= "_Jika ini bukan Anda, segera hubungi Admin!_";

        // 5. Kirim via Telegram (Pakai fungsi yg sudah ada)
        $this->_sendTelegram($chatId, 0, $namaUser, $pesan); 
    }
    public function resend_otp()
    {
        if (!session()->get('temp_user_id')) { return redirect()->to(base_url('login'))->with('error', 'Sesi berakhir.'); }

        $target = session()->get('temp_telegram');
        $nama_user = session()->get('temp_nama');
        $new_otp = rand(100000, 999999);
        session()->set('otp_code', $new_otp);

        $this->_sendTelegram($target, $new_otp, $nama_user); // PANGGIL FUNGSI TELEGRAM
        return redirect()->back()->with('success', 'OTP baru telah dikirim ke Telegram!');
    }

    // --- HELPER FUNCTIONS ---

    private function _initiateOTP($user, $display_name, $roles)
    {
        $otp = rand(100000, 999999);
        
        session()->set([
            'temp_user_id'  => $user['id'],
            'temp_username' => $user['username'],
            'temp_nama'     => $user['nama_lengkap'], 
            'temp_roles'    => $roles,
            'temp_telegram' => $user['telegram_chat_id'], // Simpan Chat ID Telegram
            'otp_code'      => $otp
        ]);

        // Kirim Telegram
        $this->_sendTelegram($user['telegram_chat_id'], $otp, $display_name);
        
        return redirect()->to(base_url('auth/verify_otp'));
    }

    /**
     * FUNGSI PENGIRIM PESAN TELEGRAM
     */
    private function _sendTelegram($chatId, $otpCode = 0, $nama_user = '', $customMessage = null) 
    {
        $token = getenv('TELEGRAM_BOT_TOKEN');
        
        // LOGIKA: Kalau ada Custom Message, pakai itu. Kalau tidak, pakai format OTP.
        if ($customMessage) {
            $pesan = $customMessage;
        } else {
            // Format OTP Bawaan
            $pesan = "🔐 *SISM-RJ LOGIN*\n\n";
            $pesan .= "Halo *$nama_user*,\n";
            $pesan .= "Kode OTP Anda adalah: `$otpCode`\n\n";
            $pesan .= "_(Jangan berikan kode ini kepada siapapun)_";
        }

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
        
        $data = [
            'chat_id'    => $chatId,
            'text'       => $pesan,
            'parse_mode' => 'Markdown'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('error', 'Anda telah berhasil keluar.');
    }
}