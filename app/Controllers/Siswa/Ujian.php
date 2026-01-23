<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;

class Ujian extends BaseController
{
    protected $db;
    protected $id_siswa;
    protected $id_kelas;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->id_siswa = session()->get('id_user');
        
        // Ambil ID Kelas Siswa
        $siswaData = $this->db->table('tbl_siswa')->where('user_id', $this->id_siswa)->get()->getRow();
        // Deteksi kolom kelas (antisipasi beda nama kolom)
        $this->id_kelas = $siswaData->id_kelas ?? $siswaData->kelas_id ?? $siswaData->id_rombel ?? 0;
    }

    // 1. DASHBOARD LIST UJIAN
   // 1. DAFTAR UJIAN (BERDASARKAN JADWAL)
    // 1. DAFTAR UJIAN (BERDASARKAN JADWAL)
    public function index()
    {
        $siswaId = session()->get('id_user'); 
        
        // A. Ambil Data Siswa & Deteksi Kelas
        $siswa = $this->db->table('tbl_siswa')->where('user_id', $siswaId)->get()->getRowArray();
        if(!$siswa) return redirect()->to('/logout')->with('error', 'Data siswa tidak ditemukan.');

        // Deteksi Nama Kolom Kelas (Auto-Detect)
        $fields = $this->db->getFieldNames('tbl_siswa');
        $kolomKelas = 'id_kelas'; 
        if (in_array('kelas_id', $fields)) $kolomKelas = 'kelas_id';
        elseif (in_array('id_rombel', $fields)) $kolomKelas = 'id_rombel';
        elseif (in_array('rombel_id', $fields)) $kolomKelas = 'rombel_id';

        $idKelas = $siswa[$kolomKelas] ?? null;

        if (empty($idKelas)) {
            return redirect()->to('siswa/dashboard')->with('error', 'Akun siswa belum masuk kelas.');
        }

        // B. Ambil JADWAL UJIAN yang Aktif untuk Kelas Ini
        $now = \CodeIgniter\I18n\Time::now()->toDateTimeString();

        $ujian = $this->db->table('tbl_jadwal_kelas')
            ->select('
                tbl_jadwal_ujian.id as id_jadwal,
                tbl_jadwal_ujian.waktu_mulai,
                tbl_jadwal_ujian.waktu_selesai,
                tbl_jadwal_ujian.durasi,
                tbl_jadwal_ujian.token,
                tbl_jadwal_ujian.wajib_lokasi,
                tbl_bank_soal.judul_ujian,
                tbl_bank_soal.jumlah_soal_pg,
                tbl_bank_soal.jumlah_soal_esai,
                tbl_mapel.nama_mapel,
                tbl_guru.nama_lengkap as nama_guru
            ')
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_jadwal_kelas.id_jadwal_ujian')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal_ujian.id_guru')
            ->where('tbl_jadwal_kelas.id_kelas', $idKelas)
            ->where('tbl_jadwal_ujian.status', 1) 
            ->orderBy('tbl_jadwal_ujian.waktu_mulai', 'DESC')
            ->get()->getResultArray();

        // C. Cek Status Pengerjaan Siswa
        foreach($ujian as &$u) {
            // HITUNG MANUAL JUMLAH SOAL
            $u['jumlah_soal'] = ($u['jumlah_soal_pg'] ?? 0) + ($u['jumlah_soal_esai'] ?? 0);

            // Cek Waktu
            $start = $u['waktu_mulai'];
            $end   = $u['waktu_selesai'];

            if ($now < $start) {
                $u['status_waktu'] = 'BELUM_MULAI';
            } elseif ($now > $end) {
                $u['status_waktu'] = 'TERLEWAT';
            } else {
                $u['status_waktu'] = 'BERLANGSUNG';
            }

            // Cek Log Sesi Siswa
            $log = $this->db->table('tbl_ujian_siswa')
                ->where('id_jadwal', $u['id_jadwal']) 
                ->where('id_siswa', $siswa['id'])
                ->get()->getRow();
                
            $u['status_ujian'] = $log ? $log->status : 'BELUM_KERJA'; 
            
            // Override ID agar view aman
            $u['id'] = $u['id_jadwal']; 
        }

        return view('siswa/ujian/index', ['title' => 'Jadwal Ujian', 'ujian' => $ujian]);
    }

    // 2. HALAMAN KONFIRMASI
   public function konfirmasi($idJadwal)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            // HAPUS 'tbl_bank_soal.jumlah_soal', GANTI DENGAN PG & ESAI
            ->select('
                tbl_jadwal_ujian.*, 
                tbl_bank_soal.judul_ujian, 
                tbl_bank_soal.jumlah_soal_pg, 
                tbl_bank_soal.jumlah_soal_esai, 
                tbl_mapel.nama_mapel
            ')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->where('tbl_jadwal_ujian.id', $idJadwal)
            ->get()->getRowArray();
            
        if(!$jadwal) return redirect()->to('siswa/ujian')->with('error', 'Jadwal tidak ditemukan');
        
        // HITUNG MANUAL TOTAL SOAL
        $jadwal['jumlah_soal'] = $jadwal['jumlah_soal_pg'] + $jadwal['jumlah_soal_esai'];

        return view('siswa/ujian/konfirmasi', ['title' => 'Konfirmasi', 'bank' => $jadwal]);
    }

    // 3. PROSES MULAI (LOGIC INTI)
    // 3. PROSES MULAI (LOGIC INTI)
    public function mulai()
    {
        $id_jadwal = $this->request->getPost('id_jadwal');
        $token_input = strtoupper($this->request->getPost('token'));

        // Ambil Data Jadwal (Object)
        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $id_jadwal)->get()->getRow();

        // VALIDASI 1: TOKEN
        if ($jadwal->setting_token == 1) {
            if ($token_input !== strtoupper($jadwal->token)) {
                return redirect()->back()->with('error', 'Token Salah!');
            }
        }

        // VALIDASI 2: WAKTU
        $now = date('Y-m-d H:i:s');
        if ($now < $jadwal->waktu_mulai) return redirect()->back()->with('error', 'Ujian belum dimulai.');
        if ($now > $jadwal->waktu_selesai) return redirect()->back()->with('error', 'Waktu ujian sudah habis.');

        // CEK SESI LAMA (RESUME)
        $cekNilai = $this->db->table('tbl_nilai')
                        ->where('id_jadwal', $id_jadwal)
                        ->where('id_siswa', $this->id_siswa)
                        ->get()->getRow(); // Ini mengembalikan OBJECT

        if ($cekNilai) {
            if ($cekNilai->is_blocked == 1) return redirect()->back()->with('error', 'Akun TERBLOKIR. Hubungi Pengawas.');
            
            // --- PERBAIKAN DISINI (Ganti ['id'] jadi ->id) ---
            if ($cekNilai->is_locked == 1)  return redirect()->to('siswa/ujian/kerjakan/' . $cekNilai->id);
            return redirect()->to('siswa/ujian/kerjakan/' . $cekNilai->id);
        }

        // --- BUAT SESI BARU ---
        $waktuSelesaiDurasi = date('Y-m-d H:i:s', strtotime("+$jadwal->durasi minutes"));
        $waktuFinal = ($waktuSelesaiDurasi < $jadwal->waktu_selesai) ? $waktuSelesaiDurasi : $jadwal->waktu_selesai;

        $dataNilai = [
            'id_jadwal'    => $id_jadwal,
            'id_siswa'     => $this->id_siswa,
            'status'       => 'SEDANG MENGERJAKAN',
            'waktu_mulai'  => $now,
            'waktu_selesai'=> $waktuFinal,
            'ip_address'   => $this->request->getIPAddress(),
            'user_agent'   => $this->request->getUserAgent()->getAgentString(),
            'jml_pelanggaran' => 0 // Inisialisasi
        ];
        $this->db->table('tbl_nilai')->insert($dataNilai);
        $id_ujian_siswa = $this->db->insertID();

        // --- GENERATE SOAL ---
        $soalList = $this->db->table('tbl_soal')->where('id_bank_soal', $jadwal->id_bank_soal)->get()->getResultArray();

        // Logic Acak Soal
        if ($jadwal->acak_soal == 1) {
            shuffle($soalList);
        }

        $batch = [];
        $no = 1;
        foreach ($soalList as $s) {
            $batch[] = [
                'id_ujian_siswa' => $id_ujian_siswa,
                'id_soal'        => $s['id'],
                'nomor_urut'     => $no++ // Simpan urutan
            ];
        }

        if (!empty($batch)) {
            $this->db->table('tbl_jawaban_siswa')->insertBatch($batch);
        }

        return redirect()->to('siswa/ujian/kerjakan/' . $id_ujian_siswa);
    }

    // 4. HALAMAN MENGERJAKAN
    public function kerjakan($idUjianSiswa)
    {
        $sesi = $this->db->table('tbl_nilai')->where('id', $idUjianSiswa)->get()->getRow();
        
        // Validasi Akses
        if (!$sesi || $sesi->id_siswa != $this->id_siswa) return redirect()->to('siswa/ujian');
        if ($sesi->status == 'SELESAI') return redirect()->to('siswa/ujian')->with('error', 'Ujian sudah selesai.');
        
        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $sesi->id_jadwal)->get()->getRow();

        // --- CEK SINGLE DEVICE (Jika Diaktifkan) ---
        if ($jadwal->setting_multi_login == 1) {
            $currentIP = $this->request->getIPAddress();
            // Jika IP berubah drastis (opsional: bisa pakai session ID juga)
            if ($sesi->ip_address != $currentIP) {
                // Jangan blokir, tapi peringatkan atau logoutkan sesi lama
                // Disini kita update IP baru saja biar siswa bisa pindah device kalau device lama mati
                $this->db->table('tbl_nilai')->where('id', $idUjianSiswa)->update(['ip_address' => $currentIP]);
            }
        }

        // --- CEK LOCK STATUS ---
        if ($sesi->is_locked == 1) {
            return view('siswa/ujian/locked', ['sesi' => $sesi]); // Halaman Minta Token Reset
        }
        if ($sesi->is_blocked == 1) {
            return view('siswa/ujian/blocked', ['sesi' => $sesi]); // Halaman Diskualifikasi
        }

        // Ambil Soal
        $listSoal = $this->db->table('tbl_jawaban_siswa')
            ->select('tbl_jawaban_siswa.*, tbl_soal.pertanyaan, tbl_soal.tipe_soal, tbl_soal.file_gambar, tbl_soal.file_audio, tbl_soal.bobot')
            ->join('tbl_soal', 'tbl_soal.id = tbl_jawaban_siswa.id_soal')
            ->where('id_ujian_siswa', $idUjianSiswa)
            ->orderBy('tbl_jawaban_siswa.nomor_urut', 'ASC')
            ->get()->getResultArray();

        // Ambil Opsi Jawaban
        foreach ($listSoal as &$item) {
            $builderOpsi = $this->db->table('tbl_opsi_soal')->where('id_soal', $item['id_soal']);
            
            // Logic Acak Opsi (Sesuai Setting Jadwal)
            if ($jadwal->acak_opsi == 1) {
                $builderOpsi->orderBy('RAND()');
            } else {
                $builderOpsi->orderBy('id', 'ASC');
            }
            $item['opsi'] = $builderOpsi->get()->getResultArray();
        }

        return view('siswa/ujian/kerjakan', [
            'title' => 'Lembar Ujian', 
            'sesi' => $sesi, 
            'soal' => $listSoal, 
            'jadwal' => $jadwal // Kirim data jadwal untuk JS Config
        ]);
    }

    // 5. API CATAT PELANGGARAN (STRICT MODE)
    public function catatPelanggaran()
    {
        $idUjianSiswa = $this->request->getPost('id_ujian_siswa');
        $jenis        = $this->request->getPost('jenis'); // 'violation' atau 'timeout'

        $sesi = $this->db->table('tbl_nilai')
            ->select('tbl_nilai.*, tbl_jadwal_ujian.setting_strict, tbl_jadwal_ujian.setting_max_violation')
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_nilai.id_jadwal')
            ->where('tbl_nilai.id', $idUjianSiswa)
            ->get()->getRow();

        if (!$sesi || $sesi->status == 'SELESAI') return $this->response->setJSON(['status' => 'finished']);

        // Jika Mode Strict OFF, abaikan
        if ($sesi->setting_strict == 0) return $this->response->setJSON(['status' => 'ignored']);

        // KASUS A: WAKTU TOLERANSI HABIS (TIMEOUT) -> KUNCI UJIAN
        if ($jenis == 'timeout') {
            $this->db->table('tbl_nilai')->where('id', $idUjianSiswa)->update(['is_locked' => 1]);
            return $this->response->setJSON(['status' => 'locked', 'msg' => 'Ujian Terkunci! Waktu toleransi habis.']);
        }

        // KASUS B: PELANGGARAN BIASA -> KURANGI NYAWA
        $jmlBaru = $sesi->jml_pelanggaran + 1;
        $this->db->table('tbl_nilai')->where('id', $idUjianSiswa)->update(['jml_pelanggaran' => $jmlBaru]);

        // Cek Max Pelanggaran
        if ($jmlBaru >= $sesi->setting_max_violation) {
            // HUKUMAN: SELESAI OTOMATIS (AUTO SUBMIT)
            // Kita panggil fungsi selesaiUjian logic disini (atau redirect JS)
            $this->db->table('tbl_nilai')->where('id', $idUjianSiswa)->update([
                'status' => 'SELESAI', 
                'waktu_submit' => date('Y-m-d H:i:s'),
                'is_blocked' => 1 // Tandai blokir (diskualifikasi)
            ]);
            
            return $this->response->setJSON(['status' => 'kicked', 'msg' => 'Batas pelanggaran habis. Ujian otomatis dikumpulkan.']);
        }

        return $this->response->setJSON([
            'status' => 'warning', 
            'sisa_nyawa' => ($sesi->setting_max_violation - $jmlBaru)
        ]);
    }

    // 6. SIMPAN JAWABAN
    public function simpanJawaban()
    {
        $id = $this->request->getPost('id_jawaban_siswa');
        $data = [];
        
        if($this->request->getPost('id_opsi')) {
            $val = $this->request->getPost('id_opsi');
            if(is_array($val)) {
                // PG Kompleks (Simpan JSON)
                $data['jawaban_siswa'] = json_encode($val);
                $data['id_opsi'] = 0;
            } else {
                // PG Biasa
                $data['id_opsi'] = $val;
            }
        }
        
        if($this->request->getPost('jawaban_isian')) {
            $data['jawaban_siswa'] = $this->request->getPost('jawaban_isian');
        }
        
        // Simpan Log Jodoh (JSON)
        // ... (Logic jodoh sudah di handle view kirim JSON string ke jawaban_isian) ...

        $this->db->table('tbl_jawaban_siswa')->where('id', $id)->update($data);
        return $this->response->setJSON(['status' => 'ok']);
    }

    // 7. SELESAI UJIAN (Auto Score)
    public function selesaiUjian()
    {
        $idUjianSiswa = $this->request->getPost('id_ujian_siswa');
        
        // Ambil semua jawaban
        $jawaban = $this->db->table('tbl_jawaban_siswa')
            ->select('tbl_jawaban_siswa.*, tbl_soal.tipe_soal, tbl_soal.bobot')
            ->join('tbl_soal', 'tbl_soal.id = tbl_jawaban_siswa.id_soal')
            ->where('id_ujian_siswa', $idUjianSiswa)
            ->get()->getResultArray();
            
        $totalNilai = 0; $benar = 0; $salah = 0;

        foreach($jawaban as $j) {
            // ... (Logic koreksi detil sama seperti sebelumnya) ...
            // ... (Paste logic koreksi PG, Isian, Jodoh disini) ...
            // Sederhananya untuk PG Biasa:
            $skor = 0;
            if(!empty($j['id_opsi'])) {
                $cek = $this->db->table('tbl_opsi_soal')->where(['id'=>$j['id_opsi'], 'is_benar'=>1])->countAllResults();
                if($cek > 0) $skor = 1;
            }
            // Tambahkan logic tipe lain...
            
            $nilaiItem = $skor * $j['bobot'];
            $totalNilai += $nilaiItem;
            if($skor == 1) $benar++; else $salah++;
            
            $this->db->table('tbl_jawaban_siswa')->where('id', $j['id'])->update(['is_benar' => $skor, 'nilai_per_soal' => $nilaiItem]);
        }

        $this->db->table('tbl_nilai')->where('id', $idUjianSiswa)->update([
            'status' => 'SELESAI',
            'waktu_submit' => date('Y-m-d H:i:s'),
            'nilai_sementara' => $totalNilai, // Total Skor
            'jml_benar' => $benar,
            'jml_salah' => $salah
        ]);

        return $this->response->setJSON(['status' => 'success', 'redirect' => base_url('siswa/nilai')]);
    }
}