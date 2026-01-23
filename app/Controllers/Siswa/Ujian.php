<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Ujian extends BaseController
{
    protected $db;
    protected $id_siswa;        // ID Primary Key (Angka)
    protected $user_id_session; // User ID dari Session (String/NIS)
    protected $id_kelas;
    protected $cache;           // Layanan Redis

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->cache = \Config\Services::cache(); // LOAD REDIS SERVICE
        
        // 1. SIMPAN SESI LOGIN
        $this->user_id_session = session()->get('id_user');
        
        // 2. AMBIL DATA REAL DARI DB
        $siswaData = $this->db->table('tbl_siswa')->where('user_id', $this->user_id_session)->get()->getRow();
        
        if ($siswaData) {
            $this->id_siswa = $siswaData->id; 
            $this->id_kelas = $siswaData->id_kelas ?? $siswaData->kelas_id ?? $siswaData->id_rombel ?? 0;
        } else {
            $this->id_siswa = 0;
            $this->id_kelas = 0;
        }
    }

    // 1. DAFTAR UJIAN
    public function index()
    {
        if(empty($this->id_siswa)) return redirect()->to('/logout')->with('error', 'Sesi siswa tidak valid.');

        $now = date('Y-m-d H:i:s');

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
            ->where('tbl_jadwal_kelas.id_kelas', $this->id_kelas)
            ->where('tbl_jadwal_ujian.status', 1) 
            ->orderBy('tbl_jadwal_ujian.waktu_mulai', 'DESC')
            ->get()->getResultArray();

        foreach($ujian as &$u) {
            $u['jumlah_soal'] = ($u['jumlah_soal_pg'] ?? 0) + ($u['jumlah_soal_esai'] ?? 0);

            if ($now < $u['waktu_mulai']) {
                $u['status_waktu'] = 'BELUM_MULAI';
            } elseif ($now > $u['waktu_selesai']) {
                $u['status_waktu'] = 'TERLEWAT';
            } else {
                $u['status_waktu'] = 'BERLANGSUNG';
            }

            $log = $this->db->table('tbl_ujian_siswa')
                ->where('id_jadwal', $u['id_jadwal']) 
                ->where('id_siswa', $this->id_siswa)
                ->get()->getRow();
                
            $u['status_ujian'] = $log ? $log->status : 'BELUM_KERJA'; 
            $u['id'] = $u['id_jadwal']; 
        }

        return view('siswa/ujian/index', ['title' => 'Jadwal Ujian', 'ujian' => $ujian]);
    }

    // 2. KONFIRMASI
    public function konfirmasi($idJadwal)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')
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
        
        $jadwal['jumlah_soal'] = $jadwal['jumlah_soal_pg'] + $jadwal['jumlah_soal_esai'];

        return view('siswa/ujian/konfirmasi', ['title' => 'Konfirmasi', 'bank' => $jadwal]);
    }

    // 3. MULAI UJIAN
    public function mulai()
    {
        $id_jadwal = $this->request->getPost('id_jadwal');
        $token_input = strtoupper($this->request->getPost('token'));

        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $id_jadwal)->get()->getRow();

        if ($jadwal->setting_token == 1) {
            if ($token_input !== strtoupper($jadwal->token)) {
                return redirect()->back()->with('error', 'Token Salah!');
            }
        }

        $now = date('Y-m-d H:i:s');
        if ($now < $jadwal->waktu_mulai) return redirect()->back()->with('error', 'Ujian belum dimulai.');
        if ($now > $jadwal->waktu_selesai) return redirect()->back()->with('error', 'Waktu ujian sudah habis.');

        $cekSesi = $this->db->table('tbl_ujian_siswa')
                        ->where('id_jadwal', $id_jadwal)
                        ->where('id_siswa', $this->id_siswa)
                        ->get()->getRow();

        if ($cekSesi) {
            if ($cekSesi->is_blocked == 1) return redirect()->back()->with('error', 'Akun TERBLOKIR. Hubungi Pengawas.');
            if ($cekSesi->status == 1) return redirect()->to('siswa/ujian')->with('error', 'Anda sudah menyelesaikan ujian ini.');
            return redirect()->to('siswa/ujian/kerjakan/' . $cekSesi->id);
        }

        // Buat Sesi Baru
        $waktuSelesaiDurasi = date('Y-m-d H:i:s', strtotime("+$jadwal->durasi minutes"));
        $waktuFinal = ($waktuSelesaiDurasi < $jadwal->waktu_selesai) ? $waktuSelesaiDurasi : $jadwal->waktu_selesai;

        $dataNilai = [
            'id_jadwal'                => $id_jadwal,
            'id_bank_soal'             => $jadwal->id_bank_soal,
            'id_siswa'                 => $this->id_siswa,
            'status'                   => 0,
            'waktu_mulai'              => $now,
            'waktu_selesai_seharusnya' => $waktuFinal,
            'ip_address'               => $this->request->getIPAddress(),
            'user_agent'               => $this->request->getUserAgent()->getAgentString(),
            'is_locked'                => 0,
            'is_blocked'               => 0
        ];
        
        $this->db->table('tbl_ujian_siswa')->insert($dataNilai);
        $id_ujian_siswa = $this->db->insertID();

        // Generate Soal ke DB (Struktur Awal)
        $soalList = $this->db->table('tbl_soal')->where('id_bank_soal', $jadwal->id_bank_soal)->get()->getResultArray();

        if ($jadwal->acak_soal == 1) shuffle($soalList);

        $batch = [];
        $no = 1;
        foreach ($soalList as $s) {
            $batch[] = [
                'id_ujian_siswa' => $id_ujian_siswa,
                'id_soal'        => $s['id'],
                'nomor_urut'     => $no++
            ];
        }

        if (!empty($batch)) {
            $this->db->table('tbl_jawaban_siswa')->insertBatch($batch);
        }

        return redirect()->to('siswa/ujian/kerjakan/' . $id_ujian_siswa);
    }

    // 4. KERJAKAN (READ FROM REDIS WITH ID_SOAL KEY)
    public function kerjakan($idUjianSiswa)
    {
        $sesi = $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->get()->getRow();
        
        if (!$sesi || $sesi->id_siswa != $this->id_siswa) return redirect()->to('siswa/ujian');
        if ($sesi->status == 1) return redirect()->to('siswa/ujian')->with('error', 'Ujian selesai.');

        // Info Jadwal
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_mapel.nama_mapel')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->where('tbl_jadwal_ujian.id', $sesi->id_jadwal)
            ->get()->getRow();

        // Ambil Data Soal (PENTING: Kita join untuk dapat tipe soal dll, dan kita perlu ID Soal Asli)
        $listSoal = $this->db->table('tbl_jawaban_siswa')
            ->select('
                tbl_jawaban_siswa.*, 
                tbl_soal.pertanyaan, 
                tbl_soal.tipe_soal, 
                tbl_soal.file_gambar, 
                tbl_soal.file_audio, 
                tbl_soal.bobot, 
                tbl_soal.id as id_soal_real
            ')
            ->join('tbl_soal', 'tbl_soal.id = tbl_jawaban_siswa.id_soal')
            ->where('id_ujian_siswa', $idUjianSiswa)
            ->orderBy('tbl_jawaban_siswa.nomor_urut', 'ASC')
            ->get()->getResultArray();

        // --- MERGE REDIS (FIXED LOGIC) ---
        $keyRedis = "ujian_tmp_" . $idUjianSiswa;
        $cachedAnswers = $this->cache->get($keyRedis); 

        // Jika ada cache, timpa data DB dengan data dari RAM
        if ($cachedAnswers && is_array($cachedAnswers)) {
            foreach ($listSoal as &$item) {
                // KUNCI PENCOCOKAN: ID SOAL (id_soal_real)
                // Bukan ID Baris Tabel Jawaban
                $idSoal = $item['id_soal_real']; 
                
                if (isset($cachedAnswers[$idSoal])) {
                    $item['id_opsi'] = $cachedAnswers[$idSoal]['id_opsi'];
                    $item['jawaban_siswa'] = $cachedAnswers[$idSoal]['jawaban_siswa'];
                }
            }
        }
        // --- END REDIS ---

        // Ambil Opsi Jawaban
        foreach ($listSoal as &$item) {
            $builderOpsi = $this->db->table('tbl_opsi_soal')->where('id_soal', $item['id_soal_real']);
            if ($jadwal->acak_opsi == 1) $builderOpsi->orderBy('RAND()');
            else $builderOpsi->orderBy('id', 'ASC');
            $item['opsi'] = $builderOpsi->get()->getResultArray();
        }

        return view('siswa/ujian/kerjakan', [
            'title' => 'Lembar Ujian', 'sesi' => $sesi, 'soal' => $listSoal, 'jadwal' => $jadwal 
        ]);
    }

    // 5. SIMPAN JAWABAN (SAVE TO REDIS WITH ID_SOAL KEY)
    public function simpanJawaban()
    {
        $idUjianSiswa = $this->request->getPost('id_ujian_siswa');
        $idSoal       = $this->request->getPost('id_soal'); // Ini ID Soal Real
        
        $newData = [
            'id_opsi' => 0,
            'jawaban_siswa' => null
        ];

        // LOGIKA MAPPING INPUT
        if($this->request->getPost('id_opsi') !== null) {
            $val = $this->request->getPost('id_opsi');
            if(is_array($val)) {
                $newData['jawaban_siswa'] = json_encode($val); // PG Kompleks (Array)
                $newData['id_opsi'] = 0;
            } elseif ($val === '') {
                // Kosong
            } else {
                $newData['id_opsi'] = $val; // PG Biasa
            }
        }
        if($this->request->getPost('jawaban_isian') !== null) {
            $newData['jawaban_siswa'] = $this->request->getPost('jawaban_isian'); // Esai
            $newData['id_opsi'] = 0;
        }

        // --- SIMPAN KE REDIS ---
        $keyRedis = "ujian_tmp_" . $idUjianSiswa;
        
        $currentCache = $this->cache->get($keyRedis);
        if (!$currentCache || !is_array($currentCache)) {
            $currentCache = [];
        }

        // PENTING: Key Array adalah ID SOAL
        $currentCache[$idSoal] = $newData;

        // Simpan (TTL 3 Jam)
        $this->cache->save($keyRedis, $currentCache, 10800); 

        return $this->response->setJSON(['status' => 'ok', 'source' => 'redis']);
    }

    // 6. SELESAI UJIAN (SYNC REDIS -> DB + SCORING)
    public function selesaiUjian()
    {
        $idUjianSiswa = $this->request->getPost('id_ujian_siswa');
        $keyRedis = "ujian_tmp_" . $idUjianSiswa;

        // --- STEP 1: SINKRONISASI (REDIS -> DB) ---
        $cachedAnswers = $this->cache->get($keyRedis);
        
        if ($cachedAnswers && is_array($cachedAnswers)) {
            // Kita butuh mapping: ID Soal -> ID Primary Key Tabel Jawaban
            // Karena updateBatch butuh Primary Key
            $mapping = $this->db->table('tbl_jawaban_siswa')
                ->select('id, id_soal')
                ->where('id_ujian_siswa', $idUjianSiswa)
                ->get()->getResultArray();
            
            $mapSoalToId = [];
            foreach($mapping as $m) $mapSoalToId[$m['id_soal']] = $m['id'];

            $batchUpdate = [];
            
            // Loop data Redis (Key-nya adalah ID Soal)
            foreach ($cachedAnswers as $idSoal => $data) {
                if(isset($mapSoalToId[$idSoal])) {
                    $batchUpdate[] = [
                        'id'            => $mapSoalToId[$idSoal], // Update row ini
                        'id_opsi'       => $data['id_opsi'],
                        'jawaban_siswa' => $data['jawaban_siswa']
                    ];
                }
            }

            // Eksekusi Update Massal
            if (!empty($batchUpdate)) {
                $this->db->table('tbl_jawaban_siswa')->updateBatch($batchUpdate, 'id');
            }
            
            // Hapus cache
            $this->cache->delete($keyRedis);
        }

        // --- STEP 2: PENILAIAN (AUTO SCORING SKALA 100) ---
        // Ambil data lengkap yang baru saja di-sync
        $jawabanSiswa = $this->db->table('tbl_jawaban_siswa')
            ->select('tbl_jawaban_siswa.*, tbl_soal.tipe_soal, tbl_soal.bobot, tbl_soal.id as id_soal_real')
            ->join('tbl_soal', 'tbl_soal.id = tbl_jawaban_siswa.id_soal')
            ->where('id_ujian_siswa', $idUjianSiswa)
            ->get()->getResultArray();
            
        $totalSkorPerolehan = 0;
        $totalBobotMaksimal = 0;
        
        $benar = 0; $salah = 0; $kosong = 0;

        foreach($jawabanSiswa as $j) {
            $skor = 0; 
            $is_correct = 0; 
            
            $inputSiswa = $j['jawaban_siswa'] ?? null;
            $idOpsi     = $j['id_opsi'] ?? 0;

            // A. PILIHAN GANDA
            if ($j['tipe_soal'] == 'PG') {
                if(!empty($idOpsi)) {
                    $cek = $this->db->table('tbl_opsi_soal')->where(['id' => $idOpsi, 'is_benar' => 1])->countAllResults();
                    if($cek > 0) { $skor = 1; $is_correct = 1; }
                } else { $kosong++; }
            }
            // B. PG KOMPLEKS
            elseif ($j['tipe_soal'] == 'PG_KOMPLEKS') {
                $arr = json_decode($inputSiswa, true);
                if (!empty($arr) && is_array($arr)) {
                    $kunci = array_column($this->db->table('tbl_opsi_soal')->select('id')->where(['id_soal'=>$j['id_soal_real'], 'is_benar'=>1])->get()->getResultArray(), 'id');
                    sort($arr); sort($kunci);
                    if ($arr == $kunci) { $skor = 1; $is_correct = 1; }
                } else { $kosong++; }
            }
            // C. MENJODOHKAN
            elseif ($j['tipe_soal'] == 'MENJODOHKAN') {
                $obj = json_decode($inputSiswa, true);
                if (!empty($obj)) {
                    $totalPasang = $this->db->table('tbl_opsi_soal')->where('id_soal', $j['id_soal_real'])->countAllResults();
                    $hit = 0;
                    foreach ($obj as $k => $v) {
                        if($this->db->table('tbl_opsi_soal')->where(['id_soal'=>$j['id_soal_real'], 'kode_opsi'=>$k, 'teks_opsi'=>$v])->countAllResults() > 0) $hit++;
                    }
                    if ($totalPasang > 0) {
                        $skor = $hit / $totalPasang;
                        if ($skor == 1) $is_correct = 1;
                    }
                } else { $kosong++; }
            }
            // D. ISIAN SINGKAT
            elseif ($j['tipe_soal'] == 'ISIAN_SINGKAT') {
                $txt = trim(strtolower($inputSiswa ?? ''));
                if ($txt !== '') {
                    $kunciRow = $this->db->table('tbl_opsi_soal')->where(['id_soal'=>$j['id_soal_real'], 'is_benar'=>1])->get()->getRow();
                    if ($kunciRow && $txt == trim(strtolower($kunciRow->teks_opsi))) { $skor = 1; $is_correct = 1; }
                } else { $kosong++; }
            }
            // E. URAIAN
            elseif ($j['tipe_soal'] == 'URAIAN') {
                if (empty($inputSiswa)) $kosong++;
            }

            // HITUNG POIN (Skor * Bobot)
            $nilaiItem = $skor * $j['bobot'];
            $totalSkorPerolehan += $nilaiItem;
            $totalBobotMaksimal += $j['bobot'];

            if ($skor >= 1) $benar++;
            else if ($skor == 0 && ($inputSiswa || $idOpsi)) $salah++;
            
            // Simpan hasil koreksi per soal
            $this->db->table('tbl_jawaban_siswa')->where('id', $j['id'])->update(['is_benar' => $is_correct, 'nilai' => $nilaiItem]);
        }

        // HITUNG NILAI AKHIR (SKALA 100)
        $nilaiAkhir = ($totalBobotMaksimal > 0) ? round(($totalSkorPerolehan / $totalBobotMaksimal) * 100, 2) : 0;

        // UPDATE REKAP UJIAN SISWA
        $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->update([
            'status'       => 1, 
            'waktu_submit' => date('Y-m-d H:i:s'),
            'nilai'        => $nilaiAkhir,
            'jml_benar'    => $benar,
            'jml_salah'    => $salah,
            'jml_kosong'   => $kosong
        ]);

        return $this->response->setJSON(['status' => 'success', 'redirect' => base_url('siswa/ujian')]);
    }
    
    // 7. CATAT PELANGGARAN
    public function catatPelanggaran()
    {
        $idUjianSiswa = $this->request->getPost('id_ujian_siswa');
        $jenis        = $this->request->getPost('jenis');

        $sesi = $this->db->table('tbl_ujian_siswa')
            ->select('tbl_ujian_siswa.*, tbl_jadwal_ujian.setting_strict, tbl_jadwal_ujian.setting_max_violation')
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_ujian_siswa.id_jadwal')
            ->where('tbl_ujian_siswa.id', $idUjianSiswa)
            ->get()->getRow();

        if (!$sesi || $sesi->status == 1) return $this->response->setJSON(['status' => 'finished']);
        if ($sesi->setting_strict == 0) return $this->response->setJSON(['status' => 'ignored']);

        if ($jenis == 'timeout') {
            $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->update(['is_locked' => 1]);
            return $this->response->setJSON(['status' => 'locked', 'msg' => 'Ujian Terkunci! Waktu toleransi habis.']);
        }

        $jmlBaru = $sesi->jml_pelanggaran + 1;
        $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->update(['jml_pelanggaran' => $jmlBaru]);

        if ($jmlBaru >= $sesi->setting_max_violation) {
            $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->update([
                'status' => 1, 'waktu_submit' => date('Y-m-d H:i:s'), 'is_blocked' => 1
            ]);
            return $this->response->setJSON(['status' => 'kicked', 'msg' => 'Diskualifikasi: Pelanggaran berlebih.']);
        }

        return $this->response->setJSON(['status' => 'warning', 'sisa_nyawa' => ($sesi->setting_max_violation - $jmlBaru)]);
    }
}