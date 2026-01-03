<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel; // Menggunakan Model yang sudah kita buat
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
        // Cek sesi login
        if (session()->get('logged_in')) {
            return redirect()->to(base_url(session()->get('role') . '/dashboard'));
        }
        return view('auth/login');
    }

    // --- LOGIKA LOGIN MANUAL (FORM) ---
    public function login() // Di Routes ini dipanggil via POST 'auth/login'
    {
        // Ini hanya alias jika diakses via GET (menampilkan view)
        if ($this->request->getMethod() === 'get') {
            return $this->index();
        }

        // Proses Auth
        return $this->auth();
    }

    // Proses Cek Database Manual
    public function auth()
    {
        $model = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            // Verifikasi Password (Hash atau Plain text untuk dev)
            $verify_pass = password_verify($password, $user['password']);
            
            // BYPASS: Jika password di DB masih 'admin' polos (belum hash), izinkan login
            if (!$verify_pass && $password == $user['password']) {
                $verify_pass = true;
            }

            if ($verify_pass) {
                // Cek apakah punya Nomor WA untuk OTP
                if (empty($user['nomor_wa'])) {
                    return redirect()->back()->with('error', 'Akun ini belum memiliki Nomor WA untuk verifikasi OTP. Hubungi Admin.');
                }

                // Kirim OTP
                return $this->_initiateOTP($user, $user['nama_lengkap']);
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
        
        if(!$code){
             return redirect()->to(base_url('login'))->with('error', 'Gagal login Google (No Code).');
        }

        $token = $this->googleClient->fetchAccessTokenWithAuthCode($code);

        if (!isset($token['error'])) {
            $this->googleClient->setAccessToken($token['access_token']);
            $googleService = new Oauth2($this->googleClient);
            $googleUser = $googleService->userinfo->get();

            $model = new UserModel();
            // Cek user berdasarkan email google
            $user = $model->where('email', $googleUser->email)->first();

            if ($user) {
                // Jika user ada, lanjut OTP
                if (empty($user['nomor_wa'])) {
                    return redirect()->to(base_url('login'))->with('error', 'Email Google terdaftar, tapi Nomor WA kosong. Hubungi Admin.');
                }
                return $this->_initiateOTP($user, $googleUser->name);
            } else {
                return redirect()->to(base_url('login'))->with('error', 'Email Google tidak terdaftar di sistem!');
            }
        }
        return redirect()->to(base_url('login'))->with('error', 'Gagal autentikasi Google.');
    }

    // --- LOGIKA VERIFIKASI OTP ---
    public function verify_otp()
    {
        if (!session()->get('temp_user_id')) {
            return redirect()->to(base_url('login'));
        }
        return view('auth/verify_otp');
    }

    public function check_otp()
    {
        $input_otp = $this->request->getPost('otp_code');
        
        if ($input_otp == session()->get('otp_code')) {
            // SET SESSION UTAMA (LOGIN SUKSES)
            session()->set([
                'id'           => session()->get('temp_user_id'),
                'username'     => session()->get('temp_username'),
                'nama_lengkap' => session()->get('temp_nama'),
                'role'         => session()->get('temp_role'),
                'logged_in'    => true,
            ]);

            // Bersihkan temp session
            session()->remove(['temp_user_id', 'temp_username', 'temp_nama', 'temp_role', 'otp_code', 'temp_phone']);
            
            return redirect()->to(base_url(session()->get('role') . '/dashboard'));
        }

        return redirect()->back()->with('error', 'Kode OTP salah!');
    }

    public function resend_otp()
    {
        if (!session()->get('temp_user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Sesi berakhir.');
        }

        $target = session()->get('temp_phone');
        $nama_user = session()->get('temp_nama');
        $new_otp = rand(100000, 999999);
        session()->set('otp_code', $new_otp);

        $this->_sendOneSender($target, $new_otp, $nama_user);
        return redirect()->back()->with('success', 'OTP baru telah dikirim!');
    }

    // --- FUNGSI HELPER (PRIVATE) ---

    private function _initiateOTP($user, $display_name)
    {
        $otp = rand(100000, 999999);
        
        // Simpan data user di session sementara sebelum OTP valid
        session()->set([
            'temp_user_id'  => $user['id'],
            'temp_username' => $user['username'],
            'temp_nama'     => $user['nama_lengkap'], // Pakai nama dari DB
            'temp_role'     => $user['role'],
            'temp_phone'    => $user['nomor_wa'],
            'otp_code'      => $otp
        ]);

        // Kirim WA
        $this->_sendOneSender($user['nomor_wa'], $otp, $display_name);
        
        return redirect()->to(base_url('auth/verify_otp'));
    }

    private function _sendOneSender($target, $otp, $nama_user) 
    {
        $apiKey = "u72643b10aaf6428.f1b2f2c6564344f7b48cde5a92424bf4"; // API KEY ANDA
        
        $sapaan = ['Halo', 'Hi', 'SIAKAD Info:'];
        $random_sapaan = $sapaan[array_rand($sapaan)];
        $ref_id = strtoupper(substr(uniqid(), -5));

        $isi_pesan = "$random_sapaan *$nama_user*,\n\n";
        $isi_pesan .= "Kode verifikasi (OTP) login Anda: *$otp*\n\n";
        $isi_pesan .= "_Jaga kerahasiaan kode ini._";

        $data = [
            "recipient_type" => "individual",
            "to"             => $target,
            "type"           => "text",
            "text"           => [
                "body" => $isi_pesan
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wa3409.cloudwa.my.id/api/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json"
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('error', 'Anda telah berhasil keluar.');
    }
}