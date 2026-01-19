<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;

class Ujian extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

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
        // Logikanya: Cek tabel tbl_jadwal_kelas -> Join ke tbl_jadwal_ujian
        $now = Time::now()->toDateTimeString();

        $ujian = $this->db->table('tbl_jadwal_kelas')
            ->select('
                tbl_jadwal_ujian.id as id_jadwal,
                tbl_jadwal_ujian.waktu_mulai,
                tbl_jadwal_ujian.waktu_selesai,
                tbl_jadwal_ujian.durasi,
                tbl_jadwal_ujian.token,
                tbl_jadwal_ujian.wajib_lokasi,
                tbl_bank_soal.judul_ujian,
                tbl_bank_soal.jumlah_soal,
                tbl_mapel.nama_mapel,
                tbl_guru.nama_lengkap as nama_guru
            ')
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_jadwal_kelas.id_jadwal_ujian')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal_ujian.id_guru')
            ->where('tbl_jadwal_kelas.id_kelas', $idKelas)
            ->where('tbl_jadwal_ujian.status', 1) // Hanya jadwal aktif
            ->orderBy('tbl_jadwal_ujian.waktu_mulai', 'DESC')
            ->get()->getResultArray();

        // C. Cek Status Pengerjaan Siswa
        foreach($ujian as &$u) {
            // Cek apakah waktu ujian sudah lewat atau belum mulai
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
                ->where('id_jadwal', $u['id_jadwal']) // Cek by ID Jadwal
                ->where('id_siswa', $siswa['id'])
                ->get()->getRow();
                
            $u['status_ujian'] = $log ? $log->status : 'BELUM_KERJA'; 
            
            // Override ID agar view tidak bingung (ID yang dipakai adalah ID Jadwal)
            $u['id'] = $u['id_jadwal']; 
        }

        return view('siswa/ujian/index', ['title' => 'Jadwal Ujian', 'ujian' => $ujian]);
    }

   // 2. HALAMAN KONFIRMASI (FIXED: JUMLAH SOAL DITAMPILKAN)
    public function konfirmasi($idJadwal)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            // PERBAIKAN DISINI: Tambahkan 'tbl_bank_soal.jumlah_soal' ke dalam select
            ->select('
                tbl_jadwal_ujian.*, 
                tbl_bank_soal.judul_ujian, 
                tbl_bank_soal.jumlah_soal, 
                tbl_mapel.nama_mapel
            ')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->where('tbl_jadwal_ujian.id', $idJadwal)
            ->get()->getRowArray();
            
        if(!$jadwal) return redirect()->to('siswa/ujian')->with('error', 'Jadwal tidak ditemukan');

        return view('siswa/ujian/konfirmasi', ['title' => 'Konfirmasi', 'bank' => $jadwal]);
    }

    public function mulai()
    {
        $idJadwal = $this->request->getPost('id_bank_soal'); // ID JADWAL
        
        $userId = session()->get('id_user');
        $siswa = $this->db->table('tbl_siswa')->where('user_id', $userId)->get()->getRowArray();
        $siswaId = $siswa['id'];

        // Ambil Data Jadwal
        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $idJadwal)->get()->getRow();
        
        if(!$jadwal) {
            return redirect()->back()->with('error', 'Data jadwal tidak ditemukan.');
        }

        // 1. VALIDASI TOKEN
        if (!empty($jadwal->token)) {
            $inputToken = strtoupper($this->request->getPost('token'));
            if ($inputToken !== strtoupper($jadwal->token)) {
                return redirect()->back()->with('error', 'Token Salah! Silakan tanya pengawas.');
            }
        }

        // 2. VALIDASI WAKTU (Sekarang sudah Asia/Jakarta)
        $now = Time::now()->toDateTimeString();
        
        if ($now < $jadwal->waktu_mulai) {
            return redirect()->back()->with('error', 'Ujian belum dimulai. Jam Server: ' . $now);
        }
        if ($now > $jadwal->waktu_selesai) {
            return redirect()->back()->with('error', 'Waktu ujian sudah habis.');
        }

        // 3. CEK JUMLAH SOAL
        $jumlahSoal = $this->db->table('tbl_soal')->where('id_bank_soal', $jadwal->id_bank_soal)->countAllResults();
        if ($jumlahSoal == 0) {
            return redirect()->back()->with('error', 'Soal Kosong! Hubungi Guru.');
        }

        // 4. CEK SESI LAMA
        $cek = $this->db->table('tbl_ujian_siswa')
                    ->where(['id_jadwal'=>$idJadwal, 'id_siswa'=>$siswaId])
                    ->get()->getRow();

        if ($cek) {
        // KASUS 1: SUDAH SELESAI
        if($cek->status == 1) {
            return redirect()->back()->with('error', 'Anda sudah menyelesaikan ujian ini.');
        }
        
        // KASUS 2: TERBLOKIR (PENTING!!)
        if($cek->is_blocked == 1) {
             return redirect()->back()->with('error', 'AKUN TERBLOKIR! Hubungi pengawas untuk reset.');
        }

        // KASUS 3: SEDANG MENGERJAKAN (Lanjut/Refresh)
        if($cek->status == 0) {
            return redirect()->to(base_url('siswa/ujian/kerjakan/' . $cek->id));
        }
        }

        // 5. BUAT SESI BARU
        $waktuMulai = Time::now();
        $waktuSelesaiDurasi = Time::now()->addMinutes($jadwal->durasi);
        $waktuSelesaiJadwal = Time::parse($jadwal->waktu_selesai);
        $waktuFinal = ($waktuSelesaiDurasi < $waktuSelesaiJadwal) ? $waktuSelesaiDurasi : $waktuSelesaiJadwal;

        $this->db->table('tbl_ujian_siswa')->insert([
            'id_jadwal'    => $idJadwal,
            'id_bank_soal' => $jadwal->id_bank_soal,
            'id_siswa'     => $siswaId,
            'waktu_mulai'  => $waktuMulai,
            'waktu_selesai_seharusnya' => $waktuFinal,
            'ip_address'   => $this->request->getIPAddress(),
            'user_agent'   => $this->request->getUserAgent()->getAgentString(),
            'status'       => 0
        ]);
        $idUjianSiswa = $this->db->insertID();

        // 6. GENERATE SOAL
        $soalBuilder = $this->db->table('tbl_soal')->where('id_bank_soal', $jadwal->id_bank_soal);
        if ($jadwal->acak_soal == 1) $soalBuilder->orderBy('RAND()'); else $soalBuilder->orderBy('id', 'ASC');
        
        $listSoal = $soalBuilder->get()->getResultArray();
        
        $dataJawaban = [];
        foreach ($listSoal as $s) {
            $dataJawaban[] = ['id_ujian_siswa' => $idUjianSiswa, 'id_soal' => $s['id']];
        }
        
        if(!empty($dataJawaban)) {
            $this->db->table('tbl_jawaban_siswa')->insertBatch($dataJawaban);
        }

        return redirect()->to(base_url('siswa/ujian/kerjakan/' . $idUjianSiswa));
    }
    // ... Function KERJAKAN, SIMPAN JAWABAN, SELESAI dll sama seperti sebelumnya ...
    // HANYA PASTIKAN FUNCTION KERJAKAN & SIMPAN JAWABAN TETAP ADA DI FILE INI
    // (Copy paste dari jawaban sebelumnya kalau terhapus)
    
    // --- COPY PASTE METHOD KERJAKAN DARI SEBELUMNYA DI SINI ---
    public function kerjakan($idUjianSiswa)
    {
        $sesi = $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->get()->getRow();
        if (!$sesi || $sesi->status == 1) return redirect()->to('siswa/ujian')->with('error', 'Akses ditolak.');

       $listSoal = $this->db->table('tbl_jawaban_siswa')
            ->select('tbl_jawaban_siswa.*, tbl_soal.pertanyaan, tbl_soal.tipe_soal, tbl_soal.bobot') // <--- CEK INI
            ->join('tbl_soal', 'tbl_soal.id = tbl_jawaban_siswa.id_soal')
            ->where('id_ujian_siswa', $idUjianSiswa)
            ->orderBy('tbl_jawaban_siswa.id', 'ASC')
            ->get()->getResultArray();

        foreach ($listSoal as &$item) {
            $item['opsi'] = $this->db->table('tbl_opsi_soal')->where('id_soal', $item['id_soal'])->orderBy('id', 'ASC')->get()->getResultArray();
        }
$jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $sesi->id_jadwal)->get()->getRow();

return view('siswa/ujian/kerjakan', [
    'title' => 'Ujian Berlangsung', 
    'sesi' => $sesi, 
    'soal' => $listSoal,
    'jadwal' => $jadwal // <--- TAMBAHKAN INI
]);
        return view('siswa/ujian/kerjakan', ['title' => 'Ujian Berlangsung', 'sesi' => $sesi, 'soal' => $listSoal]);
    }
    
    // --- COPY PASTE METHOD SIMPAN JAWABAN DARI SEBELUMNYA DI SINI ---
    public function simpanJawaban()
    {
        $id = $this->request->getPost('id_jawaban_siswa');
        $idOpsi = $this->request->getPost('id_opsi'); // Bisa berupa Array (PG Kompleks) atau String
        $isian = $this->request->getPost('jawaban_isian');
        $ragu = $this->request->getPost('ragu');

        $data = [];

        // LOGIKA PENYIMPANAN JAWABAN
        if ($idOpsi !== null) {
            if (is_array($idOpsi)) {
                // KASUS PG KOMPLEKS: Simpan sebagai JSON (misal: ["12","15"])
                // Kita simpan di kolom jawaban_isian karena id_opsi (INT) tidak muat array
                $data['jawaban_isian'] = json_encode($idOpsi);
                $data['id_opsi'] = 0; // Tandai 0 agar tidak error relasi
            } else {
                // KASUS PG BIASA / JODOH (Single ID)
                $data['id_opsi'] = $idOpsi;
            }
        }

        if ($isian !== null) {
            $data['jawaban_isian'] = $isian;
        }

        if ($ragu !== null) {
            $data['ragu'] = $ragu;
        }

        $this->db->table('tbl_jawaban_siswa')->where('id', $id)->update($data);
        return $this->response->setJSON(['status' => 'ok']);
    }

    // --- COPY PASTE METHOD SELESAI UJIAN & BLOKIR DI SINI ---
    // --- PENILAIAN OTOMATIS (AUTO SCORING ENGINE) ---
    // --- PENILAIAN OTOMATIS (VERSI ROBUST/ANTI-CRASH) ---
    public function selesaiUjian()
    {
        // Gunakan Try-Catch agar error tertangkap rapi
        try {
            $idUjianSiswa = $this->request->getPost('id_ujian_siswa');
            
            if(empty($idUjianSiswa)) {
                throw new \Exception("ID Ujian tidak dikirim/kosong.");
            }

            // 1. Ambil Jawaban Siswa
            $jawabanSiswa = $this->db->table('tbl_jawaban_siswa')
                ->select('tbl_jawaban_siswa.*, tbl_soal.tipe_soal, tbl_soal.bobot')
                ->join('tbl_soal', 'tbl_soal.id = tbl_jawaban_siswa.id_soal')
                ->where('id_ujian_siswa', $idUjianSiswa)
                ->get()->getResultArray();

            $totalNilai = 0;
            $jmlBenar = 0;
            $jmlSalah = 0;

            // Kita pakai Builder biar query update lebih aman
            $builder = $this->db->table('tbl_jawaban_siswa');

            foreach ($jawabanSiswa as $js) {
                $skorDidapat = 0; 
                $isBenar = 0;     
                $tipe = strtoupper($js['tipe_soal'] ?? 'PG');
                $bobot = $js['bobot'] ?? 1;

                // DETEKSI TIPE (NORMALISASI)
                $realTipe = 'PG';
                if (strpos($tipe, 'ISIAN') !== false || strpos($tipe, 'SINGKAT') !== false) $realTipe = 'ISIAN';
                elseif (strpos($tipe, 'JODOH') !== false || strpos($tipe, 'MENJODOHKAN') !== false) $realTipe = 'JODOH';
                elseif (strpos($tipe, 'KOMPLEKS') !== false) $realTipe = 'PG_KOMPLEKS';
                elseif (strpos($tipe, 'ESSAY') !== false) $realTipe = 'ESSAY';

                // --- LOGIKA KOREKSI ---

                // 1. ISIAN SINGKAT
                if ($realTipe == 'ISIAN') {
                    $kunci = $this->db->table('tbl_opsi_soal')->where('id_soal', $js['id_soal'])->where('is_benar', 1)->get()->getRow();
                    if ($kunci) {
                        $inputSiswa = strtolower(trim($js['jawaban_isian'] ?? ''));
                        $kunciJawaban = strtolower(trim($kunci->teks_opsi ?? ''));
                        if ($inputSiswa !== '' && $inputSiswa == $kunciJawaban) {
                            $skorDidapat = 1; $isBenar = 1;
                        }
                    }
                }

                // 2. MENJODOHKAN
                elseif ($realTipe == 'JODOH') {
                    $jawabanArr = json_decode($js['jawaban_isian'] ?? '', true); 
                    if (is_array($jawabanArr) && !empty($jawabanArr)) {
                        $totalPasanganBenarDB = $this->db->table('tbl_opsi_soal')->where('id_soal', $js['id_soal'])->countAllResults();
                        $benarUser = 0;
                        foreach ($jawabanArr as $kiri => $kanan) {
                            $cek = $this->db->table('tbl_opsi_soal')
                                ->where('id_soal', $js['id_soal'])
                                ->where('kode_opsi', trim($kiri))
                                ->where('teks_opsi', trim($kanan))
                                ->countAllResults();
                            if ($cek > 0) $benarUser++;
                        }
                        if ($totalPasanganBenarDB > 0) $skorDidapat = $benarUser / $totalPasanganBenarDB;
                        if ($skorDidapat >= 0.99) $isBenar = 1; 
                    }
                }

                // 3. PG KOMPLEKS
                elseif ($realTipe == 'PG_KOMPLEKS') {
                    $jawabanArr = json_decode($js['jawaban_isian'] ?? '', true);
                    if (is_array($jawabanArr) && !empty($jawabanArr)) {
                        $kunciArr = $this->db->table('tbl_opsi_soal')->where(['id_soal'=>$js['id_soal'], 'is_benar'=>1])->select('id')->get()->getResultArray();
                        $kunciIds = array_column($kunciArr, 'id');
                        $totalKunci = count($kunciIds);
                        $benarUser = 0;
                        foreach ($jawabanArr as $id) { 
                            if (in_array($id, $kunciIds)) $benarUser++; 
                        }
                        if ($totalKunci > 0) $skorDidapat = $benarUser / $totalKunci;
                        if ($skorDidapat >= 0.99) $isBenar = 1;
                    }
                }

                // 4. ESSAY
                elseif ($realTipe == 'ESSAY') { 
                    $skorDidapat = 0; $isBenar = 0; // Tunggu koreksi guru
                }

                // 5. PG BIASA
                else {
                    if(!empty($js['id_opsi'])) {
                         $cek = $this->db->table('tbl_opsi_soal')->where(['id'=>$js['id_opsi'], 'is_benar'=>1])->countAllResults();
                         if ($cek > 0) { $skorDidapat = 1; $isBenar = 1; }
                    }
                }

                // Hitung Nilai Item
                $nilaiItem = $skorDidapat * $bobot;
                $totalNilai += $nilaiItem;
                ($isBenar == 1) ? $jmlBenar++ : $jmlSalah++;

                // UPDATE PER BUTIR SOAL
                // Cek dulu apakah kolom nilai_esai ada? (Prevent error column not found)
                $dataJawabanUpdate = ['is_benar' => $isBenar];
                if ($this->db->fieldExists('nilai_esai', 'tbl_jawaban_siswa')) {
                    $dataJawabanUpdate['nilai_esai'] = ($realTipe == 'ESSAY') ? 0 : $nilaiItem;
                }

                $builder->where('id', $js['id'])->update($dataJawabanUpdate);
            }

            // SIMPAN HASIL AKHIR (Gunakan date() bawaan PHP biar aman)
            $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->update([
                'status'       => 1, 
                'waktu_submit' => date('Y-m-d H:i:s'), // <--- INI PERBAIKANNYA (Ganti Time::now())
                'nilai'        => $totalNilai,
                'jml_benar'    => $jmlBenar,
                'jml_salah'    => $jmlSalah
            ]);

            return $this->response->setJSON(['status' => 'success', 'redirect' => base_url('siswa/ujian')]);

        } catch (\Exception $e) {
            // KIRIM ERROR ASLI KE JAVASCRIPT
            return $this->response->setStatusCode(200)->setJSON([
                'status' => 'error', 
                'message' => 'Error System: ' . $e->getMessage()
            ]);
        }
    }
    
    public function blokirSiswa() {
        $id = $this->request->getPost('id_ujian_siswa');
        $this->db->table('tbl_ujian_siswa')->update(['is_blocked' => 1, 'alasan_blokir' => $this->request->getPost('alasan')], ['id' => $id]);
        return $this->response->setJSON(['status' => 'ok']);
    }
}