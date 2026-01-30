<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Ujian extends BaseController
{
    protected $db;
    protected $id_siswa = 0;
    protected $id_kelas = 0;
    protected $cache;

    public function __construct()
{
    $this->db = \Config\Database::connect();
    $this->cache = \Config\Services::cache(); 
    
    // Ambil ID dari session (Cek kedua kemungkinan key)
    $userId = session()->get('id_user') ?? session()->get('user_id');

    if($userId) {
        // PERBAIKAN: Gunakan WHERE dengan COALESCE agar mendeteksi user_id maupun id_user
        $siswa = $this->db->table('tbl_siswa')
            ->where('COALESCE(user_id, id_user) =', $userId)
            ->get()->getRow();

        if ($siswa) {
            $this->id_siswa = $siswa->id;
            // Gunakan null coalescing untuk fleksibilitas nama kolom kelas
            $this->id_kelas = $siswa->kelas_id ?? $siswa->id_kelas ?? $siswa->id_rombel ?? 0;
        }
    }
}

    // 1. DAFTAR UJIAN & HISTORI NILAI
    public function index()
    {
        if (empty($this->id_siswa)) return redirect()->to('/logout')->with('error', 'Sesi tidak valid.');
        
        $now = date('Y-m-d H:i:s');
        
        // Ambil Data Jadwal + Setting Show Score
        $ujian = $this->db->table('tbl_jadwal_kelas')
            ->select('
                tbl_jadwal_ujian.id as id_jadwal,
                tbl_jadwal_ujian.waktu_mulai,
                tbl_jadwal_ujian.waktu_selesai,
                tbl_jadwal_ujian.durasi,
                tbl_jadwal_ujian.token,
                tbl_jadwal_ujian.setting_show_score, 
                tbl_bank_soal.judul_ujian,
                tbl_jadwal_ujian.id_bank_soal,
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
           $u['jumlah_soal'] = $this->db->table('tbl_soal')
                                         ->where('id_bank_soal', $u['id_bank_soal'])
                                         ->countAllResults();

            // Status Waktu Global
            if ($now < $u['waktu_mulai']) $u['status_waktu'] = 'BELUM_MULAI';
            elseif ($now > $u['waktu_selesai']) $u['status_waktu'] = 'TERLEWAT';
            else $u['status_waktu'] = 'BERLANGSUNG';

            // Cek Riwayat Pengerjaan Siswa
            $log = $this->db->table('tbl_ujian_siswa')
                ->where('id_jadwal', $u['id_jadwal']) 
                ->where('id_siswa', $this->id_siswa)
                ->orderBy('id', 'DESC')
                ->get()->getRow();
                
            $u['status_ujian'] = $log ? $log->status : 'BELUM_KERJA'; // 0=Mengerjakan, 1=Selesai
            $u['id'] = $u['id_jadwal']; 
            $u['id_sesi'] = $log ? $log->id : null;
            
            // [LOGIC TAMPIL NILAI]
            // Jika status selesai (1), ambil nilai dari database
            $u['nilai_saya'] = ($log && $log->status == 1) ? $log->nilai : null;
            $u['waktu_submit'] = ($log && $log->status == 1) ? $log->waktu_submit : null;
        }

        return view('siswa/ujian/index', ['title' => 'Jadwal & Hasil Ujian', 'ujian' => $ujian]);
    }

    // 2. HALAMAN KONFIRMASI TOKEN
    public function konfirmasi($idJadwal)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_mapel.nama_mapel')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->where('tbl_jadwal_ujian.id', $idJadwal)->get()->getRowArray();
            
        if(!$jadwal) return redirect()->to('siswa/ujian');
        return view('siswa/ujian/konfirmasi', ['title' => 'Konfirmasi', 'bank' => $jadwal]);
    }

    // 3. MULAI SESI UJIAN BARU
    public function mulai()
    {
        $id_jadwal = $this->request->getPost('id_jadwal');
        $token = strtoupper($this->request->getPost('token'));
        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $id_jadwal)->get()->getRow();
        
        // Cek Token
        if ($jadwal->setting_token == 1 && $token !== strtoupper($jadwal->token)) {
            return redirect()->back()->with('error', 'Token Salah!');
        }

        // Cek Sesi Existing (Resume)
        $cek = $this->db->table('tbl_ujian_siswa')
                    ->where('id_jadwal', $id_jadwal)
                    ->where('id_siswa', $this->id_siswa)
                    ->orderBy('id', 'DESC')
                    ->get()->getRow();

        if ($cek) {
            if($cek->status == 1) return redirect()->to('siswa/ujian')->with('error', 'Anda sudah selesai.');
            if($cek->is_locked == 1) return view('siswa/ujian/locked', ['title' => 'Ujian Terkunci']);
            return redirect()->to('siswa/ujian/kerjakan/' . $cek->id);
        }

        // Buat Sesi Baru
        $now = date('Y-m-d H:i:s');
        $waktuSelesaiDurasi = date('Y-m-d H:i:s', strtotime("+$jadwal->durasi minutes"));
        // Ambil waktu paling cepat: Durasi habis atau Jadwal Global habis
        $waktuFinal = ($waktuSelesaiDurasi < $jadwal->waktu_selesai) ? $waktuSelesaiDurasi : $jadwal->waktu_selesai;

        $this->db->table('tbl_ujian_siswa')->insert([
            'id_jadwal'    => $id_jadwal,
            'id_bank_soal' => $jadwal->id_bank_soal,
            'id_siswa'     => $this->id_siswa,
            'status'       => 0, // 0 = Sedang Mengerjakan
            'is_locked'    => 0,
            'waktu_mulai'  => $now,
            'waktu_selesai_seharusnya' => $waktuFinal
        ]);
        $idUjian = $this->db->insertID();

        // Generate Soal ke Tabel Jawaban Siswa
        $soal = $this->db->table('tbl_soal')->where('id_bank_soal', $jadwal->id_bank_soal)->get()->getResultArray();
        if ($jadwal->acak_soal == 1) shuffle($soal);
        
        $batch = []; $no = 1;
        foreach ($soal as $s) { 
            $batch[] = ['id_ujian_siswa'=>$idUjian, 'id_soal'=>$s['id'], 'nomor_urut'=>$no++]; 
        }
        if($batch) $this->db->table('tbl_jawaban_siswa')->insertBatch($batch);

        return redirect()->to('siswa/ujian/kerjakan/' . $idUjian);
    }

    // 4. HALAMAN MENGERJAKAN SOAL
    public function kerjakan($idUjianSiswa)
    {
        $sesi = $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->get()->getRow();
        if (!$sesi || $sesi->id_siswa != $this->id_siswa) return redirect()->to('siswa/ujian');
        if ($sesi->status == 1) return redirect()->to('siswa/ujian')->with('error', 'Ujian selesai.');
        if ($sesi->is_locked == 1) return view('siswa/ujian/locked', ['title' => 'Ujian Terkunci']);

        // Update Heartbeat / User Agent
        $this->db->table('tbl_ujian_siswa')->where('id', $idUjianSiswa)->update(['user_agent' => 'Aktif']);

        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $sesi->id_jadwal)->get()->getRow();
        $info = $this->db->table('tbl_jadwal_ujian')
                     ->select('tbl_bank_soal.judul_ujian, tbl_mapel.nama_mapel, tbl_jadwal_ujian.*')
                     ->join('tbl_bank_soal', 'tbl_bank_soal.id=tbl_jadwal_ujian.id_bank_soal')
                     ->join('tbl_mapel', 'tbl_mapel.id=tbl_bank_soal.id_mapel')
                     ->where('tbl_jadwal_ujian.id', $sesi->id_jadwal)->get()->getRow();

        // Ambil Soal & Jawaban
        $listSoal = $this->db->table('tbl_jawaban_siswa')
            ->select('tbl_jawaban_siswa.*, tbl_soal.pertanyaan, tbl_soal.tipe_soal, tbl_soal.file_gambar, tbl_soal.file_audio, tbl_soal.bobot, tbl_soal.id as id_soal_real')
            ->join('tbl_soal', 'tbl_soal.id = tbl_jawaban_siswa.id_soal')
            ->where('id_ujian_siswa', $idUjianSiswa)
            ->orderBy('tbl_jawaban_siswa.nomor_urut', 'ASC')
            ->get()->getResultArray();

        // Merge dengan Cache Redis (Jika ada)
        $keyRedis = "ujian_tmp_" . $idUjianSiswa;
        $cached = $this->cache->get($keyRedis); 
        if ($cached && is_array($cached)) {
            foreach ($listSoal as &$item) {
                if (isset($cached[$item['id_soal_real']])) {
                    $item['id_opsi'] = $cached[$item['id_soal_real']]['id_opsi'];
                    $item['jawaban_siswa'] = $cached[$item['id_soal_real']]['jawaban_siswa'];
                    // Status Ragu (Optional, jika disimpan di redis)
                    if(isset($cached[$item['id_soal_real']]['ragu'])){
                        $item['ragu'] = $cached[$item['id_soal_real']]['ragu'];
                    }
                }
            }
        }

        // Ambil Opsi Jawaban
        foreach ($listSoal as &$item) {
            $builder = $this->db->table('tbl_opsi_soal')->where('id_soal', $item['id_soal_real']);
            if ($jadwal->acak_opsi == 1) $builder->orderBy('RAND()'); else $builder->orderBy('id', 'ASC');
            $item['opsi'] = $builder->get()->getResultArray();
        }

        return view('siswa/ujian/kerjakan', ['title' => 'Ujian', 'sesi' => $sesi, 'soal' => $listSoal, 'jadwal' => $info]);
    }

    // 5. SIMPAN JAWABAN (AJAX)
    public function simpanJawaban()
    {
        $idUjian = $this->request->getPost('id_ujian_siswa');
        $idSoal  = $this->request->getPost('id_soal'); 
        
        $newData = ['id_opsi' => 0, 'jawaban_siswa' => null];

        // Handle Pilihan Ganda / Kompleks
        if($this->request->getPost('id_opsi') !== null) {
            $val = $this->request->getPost('id_opsi');
            if(is_array($val)) { $newData['jawaban_siswa'] = json_encode($val); } 
            elseif ($val !== '') { $newData['id_opsi'] = $val; }
        }
        // Handle Essai / Isian
        if($this->request->getPost('jawaban_isian') !== null) {
            $newData['jawaban_siswa'] = $this->request->getPost('jawaban_isian');
        }
        // Handle Status Ragu-ragu
        if($this->request->getPost('ragu') !== null) {
            $newData['ragu'] = $this->request->getPost('ragu');
        }

        // Simpan ke Redis (Cache)
        $key = "ujian_tmp_" . $idUjian;
        $curr = $this->cache->get($key);
        if (!$curr || !is_array($curr)) $curr = [];
        
        if(isset($curr[$idSoal])) {
            $newData = array_merge($curr[$idSoal], $newData); // Merge biar data ragu gak ilang
        }
        $curr[$idSoal] = $newData; 
        $this->cache->save($key, $curr, 10800); // Expire 3 jam

        // Simpan ke Database
        $this->db->table('tbl_jawaban_siswa')
                 ->where('id_ujian_siswa', $idUjian)
                 ->where('id_soal', $idSoal)
                 ->update($newData);

        return $this->response->setJSON(['status' => 'ok']);
    }

    // 6. SELESAI UJIAN & HITUNG NILAI
    public function selesaiUjian()
    {
        $idUjian = $this->request->getPost('id_ujian_siswa');
        $isAuto  = $this->request->getPost('is_auto'); // Flag auto submit jika waktu habis

        $sesi = $this->db->table('tbl_ujian_siswa')->where('id', $idUjian)->get()->getRow();
        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $sesi->id_jadwal)->get()->getRow();

        // Cek Minimal Waktu (Jika bukan auto submit)
        if ($isAuto != '1') {
            $minMenit = $jadwal->min_waktu_selesai ?? 0;
            $waktuMulai = strtotime($sesi->waktu_mulai);
            $selisih = floor((time() - $waktuMulai) / 60);
            
            if ($selisih < $minMenit) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => "Belum bisa selesai. Minimal waktu pengerjaan: $minMenit menit. Anda baru berjalan $selisih menit."
                ]);
            }
        }

        // Hapus Cache Redis
        $this->cache->delete("ujian_tmp_" . $idUjian);

        // Ambil Jawaban untuk Scoring
        $list = $this->db->table('tbl_jawaban_siswa')
            ->select('tbl_jawaban_siswa.*, tbl_soal.tipe_soal, tbl_soal.bobot, tbl_soal.id as id_soal_real')
            ->join('tbl_soal', 'tbl_soal.id = tbl_jawaban_siswa.id_soal')
            ->where('id_ujian_siswa', $idUjian)
            ->get()->getResultArray();
            
        $skorTot = 0; $bobotTot = 0; $ben = 0; $sal = 0; $kos = 0;

        foreach($list as $j) {
            $sc = 0; $cor = 0;
            $inp = $j['jawaban_siswa']; $opt = $j['id_opsi'];

            // Logic Scoring
            if ($j['tipe_soal'] == 'PG' && $opt) {
                if($this->db->table('tbl_opsi_soal')->where(['id'=>$opt, 'is_benar'=>1])->countAllResults()) { $sc=1; $cor=1; }
            } 
            elseif ($j['tipe_soal'] == 'PG_KOMPLEKS' && $inp) {
                $arr = json_decode($inp, true);
                $kunci = array_column($this->db->table('tbl_opsi_soal')->select('id')->where(['id_soal'=>$j['id_soal_real'],'is_benar'=>1])->get()->getResultArray(),'id');
                if($arr){ sort($arr); sort($kunci); if($arr==$kunci){ $sc=1; $cor=1; } }
            } 
            elseif ($j['tipe_soal'] == 'MENJODOHKAN' && $inp) {
                $obj = json_decode($inp, true);
                $tot = $this->db->table('tbl_opsi_soal')->where('id_soal',$j['id_soal_real'])->countAllResults(); 
                $hit = 0;
                if($obj){ 
                    foreach($obj as $k=>$v){ 
                        if($this->db->table('tbl_opsi_soal')->where(['id_soal'=>$j['id_soal_real'],'kode_opsi'=>$k,'teks_opsi'=>$v])->countAllResults()) $hit++; 
                    } 
                    if($tot>0){ $sc=$hit/$tot; if($sc==1)$cor=1; } // Skor Parsial (Ratio)
                }
            } 
            elseif ($j['tipe_soal'] == 'ISIAN_SINGKAT' && $inp) {
                $kunci = $this->db->table('tbl_opsi_soal')->where(['id_soal'=>$j['id_soal_real'],'is_benar'=>1])->get()->getRow();
                if($kunci && trim(strtolower($inp))==trim(strtolower($kunci->teks_opsi))) { $sc=1; $cor=1; }
            }

            // Hitung Statistik Benar/Salah/Kosong
            if($sc==0 && (empty($inp)&&empty($opt))) $kos++; 
            elseif($sc==0) $sal++; 
            else $ben++;

            // Hitung Skor Berbobot
            $nilai = $sc * $j['bobot']; 
            $skorTot += $nilai; 
            $bobotTot += $j['bobot'];
            
            $this->db->table('tbl_jawaban_siswa')->where('id', $j['id'])->update(['is_benar'=>$cor, 'nilai'=>$nilai]);
        }

        // Nilai Akhir (Skala 100)
        $final = ($bobotTot > 0) ? round(($skorTot / $bobotTot) * 100, 2) : 0;
        
        // Update Status Selesai
        $this->db->table('tbl_ujian_siswa')->where('id', $idUjian)->update([
            'status'       => 1, // 1 = Selesai
            'waktu_submit' => date('Y-m-d H:i:s'), 
            'nilai'        => $final, 
            'jml_benar'    => $ben, 
            'jml_salah'    => $sal, 
            'jml_kosong'   => $kos
        ]);

        return $this->response->setJSON(['status' => 'success', 'redirect' => base_url('siswa/ujian')]);
    }

    // 7. CATAT PELANGGARAN & AUTO LOCK
    public function catatPelanggaran() 
    {
        $id = $this->request->getPost('id_ujian_siswa'); 
        $jenis = $this->request->getPost('jenis');
        
        $sesi = $this->db->table('tbl_ujian_siswa')
            ->select('tbl_ujian_siswa.*, tbl_jadwal_ujian.setting_strict, tbl_jadwal_ujian.setting_max_violation')
            ->join('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id = tbl_ujian_siswa.id_jadwal')
            ->where('tbl_ujian_siswa.id', $id)
            ->get()->getRow();
        
        if(!$sesi || $sesi->status == 1) return $this->response->setJSON(['status'=>'finished']);
        
        $jml = $sesi->jml_pelanggaran + 1;
        $this->db->table('tbl_ujian_siswa')->where('id', $id)->update(['jml_pelanggaran'=>$jml]);

        if($sesi->setting_strict == 1) {
            // Jika Timeout (Waktu Warning Habis) -> Kunci Ujian
            if($jenis == 'timeout') {
                $this->db->table('tbl_ujian_siswa')->where('id', $id)->update(['is_locked'=>1]);
                return $this->response->setJSON(['status'=>'locked', 'msg'=>'Waktu toleransi habis. Ujian terkunci.']);
            }
            // Jika Pelanggaran Berlebih -> Diskualifikasi
            if($jml >= $sesi->setting_max_violation) {
                $this->db->table('tbl_ujian_siswa')->where('id', $id)->update(['status'=>1, 'is_blocked'=>1]);
                return $this->response->setJSON(['status'=>'kicked', 'msg'=>'Anda didiskualifikasi karena terlalu banyak pelanggaran.']);
            }
        }
        return $this->response->setJSON(['status'=>'warning', 'sisa_nyawa'=>($sesi->setting_max_violation - $jml)]);
    }
}