<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dapodik extends BaseController
{
    protected $db;
    protected $client;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->client = \Config\Services::curlrequest();
    }

    public function index()
    {
        $setting = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        return view('admin/dapodik/index', [
            'title' => 'Integrasi Dapodik',
            'setting' => $setting
        ]);
    }

    public function update_setting()
    {
        $data = [
            'ip_dapodik'    => rtrim($this->request->getPost('ip_dapodik'), '/'),
            'key_integrasi' => $this->request->getPost('key_integrasi'),
            'npsn'          => $this->request->getPost('npsn'),
            'status_koneksi'=> 'Gagal' 
        ];
        $this->db->table('tbl_dapodik_setting')->where('id', 1)->update($data);
        return redirect()->back()->with('success', 'Pengaturan disimpan. Silakan Tes Koneksi.');
    }

    public function cek_koneksi()
    {
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        try {
            $response = $this->client->request('GET', $cfg->ip_dapodik . '/WebService/getSekolah', [
                'headers' => ['Authorization' => 'Bearer ' . $cfg->key_integrasi],
                'query'   => ['npsn' => $cfg->npsn],
                'timeout' => 5
            ]);
            $body = json_decode($response->getBody());
            
            if (isset($body->rows)) {
                $sekolah = is_array($body->rows) ? ($body->rows[0] ?? $body->rows) : $body->rows;
                if (is_array($sekolah)) $sekolah = (object) $sekolah;
                $nama_sekolah = $sekolah->nama ?? 'Tidak Terbaca';
                $this->db->table('tbl_dapodik_setting')->where('id', 1)->update(['status_koneksi' => 'Terhubung']);
                return redirect()->back()->with('success', "Koneksi SUKSES! Terhubung ke: <b>$nama_sekolah</b>");
            } else {
                throw new \Exception("Data tidak ditemukan (Struktur JSON berbeda).");
            }
        } catch (\Exception $e) {
            $this->db->table('tbl_dapodik_setting')->where('id', 1)->update(['status_koneksi' => 'Gagal']);
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // --- FITUR BARU: TARIK ROMBEL (KELAS) ---
  // --- 1. FITUR BARU: TARIK JURUSAN ---
    // --- TARIK JURUSAN (METODE: SCAN DARI ROMBEL) ---
    public function tarik_jurusan()
    {
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        
        try {
            // KITA TEMBAK 'ROMBONGAN BELAJAR' KARENA 'GETJURUSAN' 404
            $response = $this->client->request('GET', $cfg->ip_dapodik . '/WebService/getRombonganBelajar', [
                'headers' => ['Authorization' => 'Bearer ' . $cfg->key_integrasi],
                'query'   => ['npsn' => $cfg->npsn, 'semester_id' => '20252'], // Pastikan semester sesuai
                'timeout' => 30
            ]);

            $body = json_decode($response->getBody());
            
            if (!isset($body->rows)) {
                if(is_array($body)) $list_rombel = $body;
                else throw new \Exception("Gagal akses data Rombel untuk ambil Jurusan.");
            } else {
                $list_rombel = $body->rows;
            }

            $masuk = 0; $update = 0;
            
            // Array untuk menampung Jurusan unik biar tidak insert berkali-kali
            $jurusan_ditemukan = []; 

            foreach ($list_rombel as $r) {
                if(is_array($r)) $r = (object) $r;
                
                // Ambil ID Jurusan dari Rombel
                $id_jurusan_dapo = $r->jurusan_id ?? null;
                
                // Jika tidak ada ID Jurusan atau sudah diproses loop ini, skip
                if (empty($id_jurusan_dapo) || in_array($id_jurusan_dapo, $jurusan_ditemukan)) continue;

                // Tandai sudah diproses
                $jurusan_ditemukan[] = $id_jurusan_dapo;

                // --- DETEKSI NAMA JURUSAN ---
                // Kadang namanya ada di 'jurusan_id_str', kadang harus tebak dari nama kelas
                $nama_jurusan_raw = $r->jurusan_id_str ?? 'Jurusan Tanpa Nama';
                
                // Jika namanya masih kosong/ID, coba ambil dari kurikulum atau tebak
                if ($nama_jurusan_raw == $id_jurusan_dapo || empty($nama_jurusan_raw)) {
                    $nama_jurusan_raw = $r->kurikulum_id_str ?? 'Jurusan ' . substr($r->nama, 0, 5);
                }

                // Buat Kode Singkatan (Misal: Teknik Komputer Jaringan -> TKJ)
                // Ini manual sederhana, nanti Bos bisa edit di aplikasi
                $kode_singkat = substr($nama_jurusan_raw, 0, 5); 

                $data = [
                    'nama_jurusan' => $nama_jurusan_raw,
                    'kode_jurusan' => $kode_singkat,
                    'dapodik_id'   => $id_jurusan_dapo
                ];

                // Cek di Database Lokal
                $cek = $this->db->table('tbl_jurusan')->where('dapodik_id', $id_jurusan_dapo)->get()->getRow();

                if ($cek) {
                    // Update (Opsional: kalau mau update nama)
                    // $this->db->table('tbl_jurusan')->where('id', $cek->id)->update($data);
                    $update++; // Hitung update (meski kita skip update nama biar setingan manual bos gak keganti)
                } else {
                    $this->db->table('tbl_jurusan')->insert($data);
                    $masuk++;
                }
            }

            return redirect()->back()->with('success', "Scan Jurusan via Rombel Selesai! Ditemukan: " . count($jurusan_ditemukan) . " Jurusan. (Baru: $masuk)");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Scan Jurusan: ' . $e->getMessage());
        }
    }

    // --- 2. UPDATE: TARIK ROMBEL (LENGKAP DENGAN WALI KELAS & JURUSAN) ---
    public function tarik_rombel()
    {
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        
        try {
            // Ambil Data Rombel
            $response = $this->client->request('GET', $cfg->ip_dapodik . '/WebService/getRombonganBelajar', [
                'headers' => ['Authorization' => 'Bearer ' . $cfg->key_integrasi],
                'query'   => ['npsn' => $cfg->npsn, 'semester_id' => '20252'], // Sesuaikan Semester
                'timeout' => 30
            ]);

            $body = json_decode($response->getBody());
            if (!isset($body->rows)) {
                if(is_array($body)) $list_data = $body;
                else throw new \Exception("Format Data Rombel Salah");
            } else {
                $list_data = $body->rows;
            }

            $masuk = 0; $update = 0;

            foreach ($list_data as $r) {
                if(is_array($r)) $r = (object) $r;
                
                // Filter: Hanya ambil Kelas (Jenis 1), abaikan Ekskul
                if ($r->jenis_rombel != 1) continue; 

                // --- LOGIKA MAPPING RELASI ---
                
                // 1. Cari ID Guru Lokal (Wali Kelas) berdasarkan ptk_id Dapodik
                $guru_lokal = null;
                if (!empty($r->ptk_id)) {
                    $guru_lokal = $this->db->table('tbl_guru')->where('dapodik_id', $r->ptk_id)->get()->getRow();
                }
                $id_wali_kelas = $guru_lokal ? $guru_lokal->id : null;

                // 2. Cari ID Jurusan Lokal berdasarkan jurusan_id Dapodik
                $jurusan_lokal = null;
                if (!empty($r->jurusan_id)) {
                    $jurusan_lokal = $this->db->table('tbl_jurusan')->where('dapodik_id', $r->jurusan_id)->get()->getRow();
                }
                $id_jurusan_fix = $jurusan_lokal ? $jurusan_lokal->id : null;


                $data = [
                    'nama_kelas' => $r->nama,
                    'tingkat'    => $r->tingkat_pendidikan_id,
                    'dapodik_id' => $r->rombongan_belajar_id,
                    'guru_id'    => $id_wali_kelas,  // <--- SUDAH TERISI
                    'id_jurusan' => $id_jurusan_fix  // <--- SUDAH TERISI
                ];

                // Cek Duplikat
                $cek = $this->db->table('tbl_kelas')->where('dapodik_id', $r->rombongan_belajar_id)->get()->getRow();
                if (!$cek) {
                    $cek = $this->db->table('tbl_kelas')->where('nama_kelas', $r->nama)->get()->getRow();
                }

                if ($cek) {
                    $this->db->table('tbl_kelas')->where('id', $cek->id)->update($data);
                    $update++;
                } else {
                    $this->db->table('tbl_kelas')->insert($data);
                    $masuk++;
                }
            }

            return redirect()->back()->with('success', "Sync Rombel + Wali Kelas Selesai! Baru: $masuk, Update: $update. (Pastikan Data Guru & Jurusan sudah ditarik duluan)");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Rombel: ' . $e->getMessage());
        }
    }

    // --- UPGRADE: TARIK SISWA LENGKAP + MAPPING KELAS ---
    // --- TARIK SISWA (VERSI ANTI ERROR) ---
// --- TARIK SISWA + AUTO CREATE USER ---
    public function tarik_siswa()
    {
        // 👇 TAMBAHKAN 2 BARIS INI (PENAMBAH NYAWA) 👇
        set_time_limit(0); // 0 artinya Unlimited (sampai kelar)
        ini_set('memory_limit', '-1'); // Antisipasi memori penuh juga
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();

        
        // --- KONFIGURASI ROLE ID (SESUAIKAN DATABASE BOS) ---
        $ROLE_ID_SISWA = 11; // Lihat di tabel auth_groups atau roles
        
        try {
            $response = $this->client->request('GET', $cfg->ip_dapodik . '/WebService/getPesertaDidik', [
                'headers' => ['Authorization' => 'Bearer ' . $cfg->key_integrasi],
                'query'   => ['npsn' => $cfg->npsn],
                'timeout' => 300 
            ]);

            $body = json_decode($response->getBody());
            // Validasi struktur data (fallback logic)
            if (!isset($body->rows)) {
                if(is_array($body)) $list_siswa = $body;
                else throw new \Exception("Format Data Salah");
            } else {
                $list_siswa = $body->rows;
            }

            $masuk = 0; $update = 0;

            foreach ($list_siswa as $d) {
                if(is_array($d)) $d = (object) $d;
                if (empty($d->nisn)) continue; // Skip jika tidak ada NISN

                // ---------------------------------------------------
                // 1. PROSES AKUN LOGIN (TBL_USERS)
                // ---------------------------------------------------
                // Cek apakah user sudah ada berdasarkan username (NISN)
                $user = $this->db->table('tbl_users')->where('username', $d->nisn)->get()->getRow();
                $id_user_akun = null;

                $data_user = [
                    'email'    => null, // Dapodik jarang ada email siswa valid
                    'username' => $d->nisn,
                    'nama_lengkap' => $d->nama ?? 'Siswa',
                    'role'     => 'siswa', // Enum di tbl_users
                    'active'   => 1
                ];

                if ($user) {
                    $id_user_akun = $user->id;
                    // Update nama jika berubah
                    $this->db->table('tbl_users')->where('id', $user->id)->update(['nama_lengkap' => $d->nama]);
                } else {
                    // Buat Akun Baru
                    $data_user['password_hash'] = password_hash('123456', PASSWORD_DEFAULT); // Default Password (sesuaikan nama kolom password/password_hash)
                    // Cek nama kolom password di DB Bos (password atau password_hash?)
                    if($this->db->fieldExists('password', 'tbl_users')) {
                        $data_user['password'] = password_hash('123456', PASSWORD_DEFAULT);
                        unset($data_user['password_hash']);
                    }

                    $this->db->table('tbl_users')->insert($data_user);
                    $id_user_akun = $this->db->insertID();
                }

                // ---------------------------------------------------
                // 2. PROSES ROLE (USER_ROLES)
                // ---------------------------------------------------
                // Cek apakah sudah punya role
                $cek_role = $this->db->table('user_roles')
                    ->where('user_id', $id_user_akun)
                    ->where('role_id', $ROLE_ID_SISWA)
                    ->countAllResults();
                
                if ($cek_role == 0) {
                    $this->db->table('user_roles')->insert([
                        'user_id' => $id_user_akun,
                        'role_id' => $ROLE_ID_SISWA
                    ]);
                }

                // ---------------------------------------------------
                // 3. PROSES PROFIL SISWA (TBL_SISWA)
                // ---------------------------------------------------
                // Cari Kelas Lokal
                $id_rombel_dapo = $d->rombongan_belajar_id ?? null;
                $id_kelas_fix = 0;
                if($id_rombel_dapo) {
                    $kls = $this->db->table('tbl_kelas')->where('dapodik_id', $id_rombel_dapo)->get()->getRow();
                    $id_kelas_fix = $kls ? $kls->id : 0;
                }

                $data_profil = [
                    'id_user'      => $id_user_akun, // RELASI PENTING
                    'nama_lengkap' => $d->nama ?? 'Tanpa Nama',
                    'nisn'         => $d->nisn,
                    'nis'          => $d->nipd ?? null,
                    'jk'           => $d->jenis_kelamin ?? 'L',
                    'tempat_lahir' => $d->tempat_lahir ?? null,
                    'tgl_lahir'    => $d->tanggal_lahir ?? null,
                    'nik'          => $d->nik ?? null,
                    'alamat'       => $d->alamat_jalan ?? $d->alamat ?? '-',
                    'nama_ayah'    => $d->nama_ayah ?? '-',
                    'nama_ibu'     => $d->nama_ibu_kandung ?? '-',
                    'agama_id'     => $d->agama_id ?? 0,
                    'dapodik_id'   => $d->peserta_didik_id ?? null,
                    'rombel_id_dapodik' => $id_rombel_dapo,
                    'kelas_id'     => $id_kelas_fix,
                    'role'         => 'siswa'
                ];

                $cek_profil = $this->db->table('tbl_siswa')->where('nisn', $d->nisn)->get()->getRow();

                if ($cek_profil) {
                    $this->db->table('tbl_siswa')->where('id', $cek_profil->id)->update($data_profil);
                    $update++;
                } else {
                    $this->db->table('tbl_siswa')->insert($data_profil);
                    $masuk++;
                }
            }

            return redirect()->back()->with('success', "Sync Siswa + Akun Login Selesai! Masuk: $masuk, Update: $update.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Sync Siswa: ' . $e->getMessage() . ' line ' . $e->getLine());
        }
    }

    // --- TARIK GURU + AUTO CREATE USER ---
// --- TARIK GURU (LOGIN BY NIK) ---
    public function tarik_guru()
    {
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        
        // --- KONFIGURASI ROLE ID ---
        $ROLE_ID_GURU = 8; // Pastikan ID ini benar sesuai database role Bos
        
        try {
            // 1. Request Data ke Dapodik
            $response = $this->client->request('GET', $cfg->ip_dapodik . '/WebService/getGtk', [
                'headers' => ['Authorization' => 'Bearer ' . $cfg->key_integrasi],
                'query'   => ['npsn' => $cfg->npsn],
                'timeout' => 120 // Perpanjang timeout takut data banyak
            ]);

            $body = json_decode($response->getBody());
            if (!isset($body->rows)) {
                if(is_array($body)) $list_guru = $body;
                else throw new \Exception("Format Data Guru Salah / Kosong");
            } else {
                $list_guru = $body->rows;
            }

            $masuk = 0; $update = 0;

            foreach ($list_guru as $g) {
                if(is_array($g)) $g = (object) $g;
                
                // --- 2. LOGIKA USERNAME LOGIN (PRIORITAS NIK) ---
                $nik_guru = !empty($g->nik) ? trim($g->nik) : '';
                $nip_guru = !empty($g->nip) ? trim($g->nip) : '';

                // Prioritaskan NIK. Jika NIK kosong, baru pakai NIP.
                $username_login = !empty($nik_guru) ? $nik_guru : $nip_guru;
                
                // Jika masih kosong juga (jarang terjadi), buat random
                if (empty($username_login)) {
                    $username_login = 'GURU' . rand(1000, 9999);
                }

                // --- 3. PROSES AKUN (TBL_USERS) ---
                // Cek apakah user ini sudah ada?
                $user = $this->db->table('tbl_users')->where('username', $username_login)->get()->getRow();
                $id_user_akun = null;

                $data_user = [
                    'username'     => $username_login,
                    'nama_lengkap' => $g->nama,
                    'role'         => 'guru', // Opsional jika pakai tabel role terpisah
                    'active'       => 1
                ];

                if ($user) {
                    // Jika ada, update namanya saja
                    $id_user_akun = $user->id;
                    $this->db->table('tbl_users')->where('id', $user->id)->update(['nama_lengkap' => $g->nama]);
                } else {
                    // Jika belum ada, BUAT USER BARU
                    // Cek nama kolom password (support 'password' atau 'password_hash')
                    if($this->db->fieldExists('password', 'tbl_users')) {
                        $data_user['password'] = password_hash($username_login, PASSWORD_DEFAULT);
                    } else {
                        $data_user['password_hash'] = password_hash($username_login, PASSWORD_DEFAULT);
                    }
                    
                    // Tambahkan email jika ada kolomnya
                    if($this->db->fieldExists('email', 'tbl_users')) {
                        $data_user['email'] = $g->email ?? null;
                    }

                    $this->db->table('tbl_users')->insert($data_user);
                    $id_user_akun = $this->db->insertID();
                }

                // --- 4. PROSES ROLE (USER_ROLES / AUTH_GROUPS_USERS) ---
                // Cek tabel 'user_roles' (sesuai codingan Bos sebelumnya)
                $cek_role = $this->db->table('user_roles')
                    ->where('user_id', $id_user_akun)
                    ->where('role_id', $ROLE_ID_GURU)
                    ->countAllResults();
                
                if ($cek_role == 0) {
                    $this->db->table('user_roles')->insert([
                        'user_id' => $id_user_akun, 
                        'role_id' => $ROLE_ID_GURU
                    ]);
                }

                // --- 5. PROSES PROFIL GURU (TBL_GURU) ---
                $data_profil = [
                    'id_user'            => $id_user_akun, // Relasi ke tabel user
                    'nama_lengkap'       => $g->nama,
                    'nik'                => $nik_guru,
                    'nip'                => $nip_guru,
                    'nuptk'              => $g->nuptk ?? null,
                    'jk'                 => $g->jenis_kelamin ?? 'L',
                    'tempat_lahir'       => $g->tempat_lahir ?? null,
                    'tgl_lahir'          => $g->tanggal_lahir ?? null,
                    'ibu_kandung'        => $g->ibu_kandung ?? null,
                    'status_kepegawaian' => $g->status_kepegawaian_id_str ?? 'Honorer',
                    'dapodik_id'         => $g->ptk_id ?? null,
                    // Fitur Foto:
                    'foto'               => $g->nama_file_foto ?? $g->gambar ?? 'default.png'
                ];

                // Cek Duplikat Profil (By Dapodik ID atau NIK)
                $cek_profil = $this->db->table('tbl_guru')->where('dapodik_id', $g->ptk_id)->get()->getRow();
                
                // Fallback cek by NIK kalau dapodik_id belum tersimpan
                if (!$cek_profil && !empty($nik_guru)) {
                    $cek_profil = $this->db->table('tbl_guru')->where('nik', $nik_guru)->get()->getRow();
                }

                if ($cek_profil) {
                    $this->db->table('tbl_guru')->where('id', $cek_profil->id)->update($data_profil);
                    $update++;
                } else {
                    $this->db->table('tbl_guru')->insert($data_profil);
                    $masuk++;
                }
            }

            return redirect()->back()->with('success', "Sync Guru Selesai! Login pakai NIK. (Baru: $masuk, Update: $update)");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Sync Guru: ' . $e->getMessage());
        }
    }
    // --- DEBUGGER KHUSUS JURUSAN (CEK ENDPOINT YANG BENAR) ---
    public function test_jurusan()
    {
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        
        echo "<h1>🕵️‍♂️ DETEKTIF URL JURUSAN DAPODIK</h1>";
        echo "<p>Target IP: <strong>{$cfg->ip_dapodik}</strong></p><hr>";

        // DAFTAR KEMUNGKINAN ENDPOINT (KITA COBA SEMUA)
        $endpoints = [
            'getJurusan'            => 'Tebakan 1 (Standar)',
            'getKompetensiKeahlian' => 'Tebakan 2 (Khusus SMK)',
            'getProgramKeahlian'    => 'Tebakan 3 (Program)',
            'getBidangKeahlian'     => 'Tebakan 4 (Bidang)'
        ];

        foreach ($endpoints as $uri => $keterangan) {
            $full_url = $cfg->ip_dapodik . '/WebService/' . $uri . '?npsn=' . $cfg->npsn;
            
            echo "<h3>Percobaan: $uri ($keterangan)</h3>";
            echo "URL: <small>$full_url</small><br>";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $full_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $cfg->key_integrasi
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            
            $output = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 200) {
                echo "<div style='background:#d1fae5; color:green; padding:10px; border:1px solid green;'>";
                echo "<strong>✅ BERHASIL (200 OK)!</strong><br>";
                echo "Isi Data (Sampel): " . substr($output, 0, 300) . "..."; // Tampilkan sedikit saja
                echo "</div>";
                echo "<p>👉 <strong>KESIMPULAN:</strong> Ganti kodingan di Controller pakai <code>$uri</code></p>";
            } elseif ($http_code == 404) {
                echo "<div style='background:#ffe4e6; color:red; padding:10px; border:1px solid red;'>";
                echo "❌ GAGAL (404 Not Found) - Alamat ini salah.";
                echo "</div>";
            } else {
                echo "Status Code: $http_code (Cek Koneksi)";
            }
            echo "<br>";
        }
    }
    // --- FITUR: TARIK MAPEL & RELASI JURUSAN ---
 // --- UPDATE FINAL: TARIK MAPEL (FILTERED & AUTO-MAPPING) ---
 // --- TARIK MAPEL (VERSI HEMAT & BERSIH) ---
    public function tarik_mapel()
    {
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        
        try {
            // Ambil Data Master (Limit besar biar kebaca semua, nanti kita saring di PHP)
            $response = $this->client->request('GET', $cfg->ip_dapodik . '/WebService/getMataPelajaran', [
                'headers' => ['Authorization' => 'Bearer ' . $cfg->key_integrasi],
                'query'   => ['npsn' => $cfg->npsn, 'limit' => 6000],
                'timeout' => 120 
            ]);

            $body = json_decode($response->getBody());
            
            if (!isset($body->rows)) {
                if(is_array($body)) $list_data = $body;
                else throw new \Exception("Gagal tarik Master Mapel.");
            } else {
                $list_data = $body->rows;
            }

            $mapel_baru = 0; 
            $relasi_baru = 0;
            $di_skip = 0;

            // Ambil semua jurusan untuk mapping Mapel Umum
            $all_jurusan = $this->db->table('tbl_jurusan')->get()->getResultArray();

            // --- DAFTAR KATA KUNCI YANG DIIZINKAN (WHITELIST) ---
            // Hanya mapel yang mengandung kata-kata ini yang akan DISIMPAN
            $whitelist = [
                'Pendidikan Agama', 'PPKn', 'Pancasila', 
                'Bahasa Indonesia', 'Matematika', 'Sejarah', 
                'Bahasa Inggris', 'Seni', 'Penjas', 'PJOK', 'Olahraga',
                'Informatika', 'IPAS', 'Projek IPAS', 
                'Kejuruan', 'Dasar-dasar', 'Konsentrasi', // Mapel C SMK
                'Projek Kreatif', 'Produk Kreatif', 'PKL', 'Praktek Kerja', // Mapel C SMK
                'Muatan Lokal', 'Bahasa Sunda', 'Bahasa Jawa', // Mulok
                'Bimbingan Konseling'
            ];

            foreach ($list_data as $p) {
                if(is_array($p)) $p = (object) $p;

                $nama_mapel = trim($p->nama);
                $id_mapel_dapo = $p->mata_pelajaran_id;

                // --- 1. FILTER KETAT (HANYA YANG ADA DI WHITELIST) ---
                $boleh_masuk = false;
                foreach ($whitelist as $keyword) {
                    // Cek apakah nama mapel mengandung kata kunci? (Case insensitive)
                    if (stripos($nama_mapel, $keyword) !== false) {
                        $boleh_masuk = true;
                        break;
                    }
                }

                // Filter Tambahan: Buang yang aneh-aneh meski lolos whitelist
                // Contoh: "Evaluasi Pendidikan Agama" (Bukan mapel siswa)
                if (stripos($nama_mapel, 'Evaluasi') !== false || stripos($nama_mapel, 'Guru') !== false) {
                    $boleh_masuk = false;
                }

                if (!$boleh_masuk) {
                    $di_skip++;
                    continue; // SKIP DATA INI
                }

                // --- 2. TENTUKAN KELOMPOK (A/B/C) ---
                $kode_kelompok = 'C'; // Default Kejuruan
                
                // Logic Kelompok A (Nasional)
                if (stripos($nama_mapel, 'Agama') !== false || stripos($nama_mapel, 'Pancasila') !== false || stripos($nama_mapel, 'PPKn') !== false || stripos($nama_mapel, 'Indonesia') !== false || stripos($nama_mapel, 'Matematika') !== false || stripos($nama_mapel, 'Sejarah') !== false || stripos($nama_mapel, 'Inggris') !== false || stripos($nama_mapel, 'Informatika') !== false || stripos($nama_mapel, 'IPAS') !== false) {
                    $kode_kelompok = 'A';
                }
                
                // Logic Kelompok B (Wilayah/Seni/Olahraga)
                if (stripos($nama_mapel, 'Seni') !== false || stripos($nama_mapel, 'Budaya') !== false || stripos($nama_mapel, 'Jasmani') !== false || stripos($nama_mapel, 'PJOK') !== false || stripos($nama_mapel, 'Olahraga') !== false || stripos($nama_mapel, 'Muatan Lokal') !== false || stripos($nama_mapel, 'Sunda') !== false || stripos($nama_mapel, 'Jawa') !== false) {
                    $kode_kelompok = 'B';
                }

                // --- 3. SIMPAN KE DATABASE ---
                // Buat kode singkat (Ambil huruf kapital)
                preg_match_all('/[A-Z]/', $nama_mapel, $matches);
                $kode_singkat = implode('', $matches[0]);
                if (strlen($kode_singkat) < 2) $kode_singkat = strtoupper(substr($nama_mapel, 0, 3));

                $data_mapel = [
                    'nama_mapel' => $nama_mapel,
                    'kode_mapel' => substr($kode_singkat, 0, 10), 
                    'kelompok'   => $kode_kelompok,
                    'dapodik_id' => $id_mapel_dapo
                ];

                // Cek & Insert
                $cek_mapel = $this->db->table('tbl_mapel')->where('dapodik_id', $id_mapel_dapo)->get()->getRow();
                $id_mapel_lokal = 0;

                if ($cek_mapel) {
                    $id_mapel_lokal = $cek_mapel->id;
                } else {
                    $this->db->table('tbl_mapel')->insert($data_mapel);
                    $id_mapel_lokal = $this->db->insertID();
                    $mapel_baru++;
                }

                // --- 4. AUTO MAPPING (KHUSUS KELOMPOK A & B) ---
                if ($kode_kelompok == 'A' || $kode_kelompok == 'B') {
                    foreach ($all_jurusan as $jur) {
                        $cek_rel = $this->db->table('tbl_mapel_jurusan')
                            ->where('id_mapel', $id_mapel_lokal)
                            ->where('id_jurusan', $jur['id'])
                            ->countAllResults();
                        
                        if ($cek_rel == 0) {
                            $this->db->table('tbl_mapel_jurusan')->insert([
                                'id_mapel' => $id_mapel_lokal,
                                'id_jurusan' => $jur['id']
                            ]);
                            $relasi_baru++;
                        }
                    }
                }
            }

            return redirect()->back()->with('success', "Sync Mapel Bersih Selesai! <br>✅ Masuk: $mapel_baru <br>🗑️ Sampah Dibuang: $di_skip <br>🔗 Auto-Relasi: $relasi_baru");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Sync Mapel: ' . $e->getMessage());
        }
    }
    // --- DEBUGGER MAPEL (CARI PINTU YANG BUKA) ---
    public function test_mapel()
    {
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        
        echo "<h1>🕵️‍♂️ DETEKTIF URL MAPEL DAPODIK</h1>";
        echo "<hr>";

        // DAFTAR KEMUNGKINAN ENDPOINT
        $endpoints = [
            'getMataPelajaran' => 'Target 1: Master Data Mapel (Biasanya ini)',
            'getPembelajaran'  => 'Target 2: Data Mengajar (Yang tadi error)',
            'getKurikulum'     => 'Target 3: Data Kurikulum'
        ];

        foreach ($endpoints as $uri => $keterangan) {
            $full_url = $cfg->ip_dapodik . '/WebService/' . $uri . '?npsn=' . $cfg->npsn;
            
            echo "<h3>Coba: $uri ($keterangan)</h3>";
            echo "URL: <small>$full_url</small><br>";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $full_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $cfg->key_integrasi]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            
            $output = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 200) {
                echo "<div style='background:#d1fae5; color:green; padding:10px; border:1px solid green;'>";
                echo "<strong>✅ BERHASIL (200 OK)!</strong><br>";
                echo "Data: " . substr($output, 0, 200) . "..."; 
                echo "</div>";
                echo "<p>👉 SOLUSI: Kita ganti kodingan pakai <code>$uri</code></p>";
            } elseif ($http_code == 404) {
                echo "<div style='background:#ffe4e6; color:red; padding:10px; border:1px solid red;'>❌ GAGAL (404 Not Found)</div>";
            } else {
                echo "Status: $http_code";
            }
            echo "<br>";
        }
    }
    // --- TARIK PROFIL SEKOLAH (UNTUK KOP SURAT & TTD RAPORT) ---
   // --- TARIK PROFIL SEKOLAH (REVISI: CARI KEPSEK LEBIH TELITI) ---
    public function tarik_sekolah()
    {
        $cfg = $this->db->table('tbl_dapodik_setting')->where('id', 1)->get()->getRow();
        
        try {
            // 1. AMBIL DATA SEKOLAH (UTAMA)
            $response = $this->client->request('GET', $cfg->ip_dapodik . '/WebService/getSekolah', [
                'headers' => ['Authorization' => 'Bearer ' . $cfg->key_integrasi],
                'query'   => ['npsn' => $cfg->npsn],
                'timeout' => 30
            ]);
            $body = json_decode($response->getBody());
            $s = is_array($body->rows) ? (object)$body->rows[0] : (object)$body->rows;

            // 2. DETEKTIF KEPALA SEKOLAH 🕵️‍♂️
            $nama_kepsek = '-';
            $nip_kepsek  = '-';
            
            try {
                // Kita cari di data GTK (Guru)
                $res_gtk = $this->client->request('GET', $cfg->ip_dapodik . '/WebService/getGtk', [
                    'headers' => ['Authorization' => 'Bearer ' . $cfg->key_integrasi],
                    'query'   => ['npsn' => $cfg->npsn],
                    'timeout' => 60 // Waktu lebih lama buat nyari
                ]);
                $gtk_body = json_decode($res_gtk->getBody());
                
                if(isset($gtk_body->rows)) {
                    foreach($gtk_body->rows as $g) {
                        if(is_array($g)) $g = (object) $g;
                        
                        // STRATEGI 1: Cek Jenis PTK ID (20 = Kepala Sekolah)
                        $is_kepsek = false;
                        if (isset($g->jenis_ptk_id) && $g->jenis_ptk_id == 20) $is_kepsek = true;
                        
                        // STRATEGI 2: Cek String Jenis PTK (Kadang ID beda tapi namanya 'Kepala Sekolah')
                        if (isset($g->jenis_ptk_id_str) && stripos($g->jenis_ptk_id_str, 'Kepala Sekolah') !== false) $is_kepsek = true;

                        // STRATEGI 3: Cek Tugas Tambahan (Jika ada field ini)
                        // (Biasanya ada field 'tugas_tambahan' di versi tertentu)
                        
                        if ($is_kepsek) {
                            $nama_kepsek = $g->nama;
                            $nip_kepsek  = $g->nip ?? '-';
                            break; // Ketemu! Berhenti looping.
                        }
                    }
                }
            } catch (\Exception $ex) {
                // Jika gagal tarik GTK, biarkan kosong dulu
            }

            // 3. SIMPAN KE DATABASE
            $data = [
                'npsn'           => $s->npsn,
                'nama_sekolah'   => $s->nama,
                'alamat'         => $s->alamat_jalan,
                'desa_kelurahan' => $s->desa_kelurahan,
                'kecamatan'      => $s->kecamatan,
                'kabupaten'      => $s->kabupaten_kota,
                'provinsi'       => $s->provinsi,
                'kode_pos'       => $s->kode_pos ?? '',
                'no_telp'        => $s->nomor_telepon ?? '',
                'email'          => $s->email ?? '',
                'website'        => $s->website ?? '',
                'kepala_sekolah' => $nama_kepsek,
                'nip_kepsek'     => $nip_kepsek,
                'dapodik_id'     => $s->sekolah_id
            ];

            // Cek apakah data sekolah sudah ada?
            $cek = $this->db->table('tbl_sekolah')->where('npsn', $s->npsn)->get()->getRow();
            if ($cek) {
                $this->db->table('tbl_sekolah')->where('id', $cek->id)->update($data);
            } else {
                $this->db->table('tbl_sekolah')->insert($data);
            }

            // Pesan Sukses
            if($nama_kepsek != '-') {
                return redirect()->back()->with('success', "Berhasil! Kepala Sekolah Ditemukan: <b>$nama_kepsek</b>");
            } else {
                return redirect()->back()->with('warning', "Data Sekolah Update, TAPI Kepala Sekolah tidak ditemukan di data GTK. Mohon isi manual di database jika perlu.");
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
    public function kirim_raport() { /* ... kodingan kirim raport sebelumnya ... */ }
    public function test_manual() { /* ... kodingan debug ... */ }
}