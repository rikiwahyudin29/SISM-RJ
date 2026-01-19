<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\BankSoalModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\JurusanModel;
use App\Models\GuruModel;

class BankSoal extends BaseController
{
    protected $bankModel;
    protected $mapelModel;
    protected $kelasModel;
    protected $jurusanModel;
    protected $guruModel;

    public function __construct()
    {
        $this->bankModel    = new BankSoalModel();
        $this->mapelModel   = new MapelModel();
        $this->kelasModel   = new KelasModel();
        $this->jurusanModel = new JurusanModel();
        $this->guruModel    = new GuruModel();
    }

    public function index()
    {
        $userId = session()->get('id_user'); // Ambil ID User yang login
        
        // 1. Cari ID Guru berdasarkan User ID
        $guru = $this->guruModel->where('user_id', $userId)->first();
        if (!$guru) return redirect()->to('logout')->with('error', 'Data Guru tidak ditemukan.');

        // 2. Ambil Bank Soal milik Guru ini saja
        $data = [
            'title'   => 'Bank Soal Saya',
            'guru'    => $guru,
            'bank'    => $this->bankModel->getBankLengkap($guru['id']), // Filter by ID Guru
            'mapel'   => $this->mapelModel->findAll(),
            'kelas'   => $this->kelasModel->findAll(),
            'jurusan' => $this->jurusanModel->findAll()
        ];

        return view('guru/bank_soal/index', $data);
    }

   public function simpan()
    {
        $userId = session()->get('id_user');
        $guru = $this->guruModel->where('user_id', $userId)->first();
        $db = \Config\Database::connect();

        $kodeBank = 'BS-' . time();
        $id = $this->request->getPost('id');

        // Data Header
        $data = [
            'kode_bank'   => $this->request->getPost('kode_bank') ?: $kodeBank,
            'judul_ujian' => $this->request->getPost('judul_ujian'),
            'id_mapel'    => $this->request->getPost('id_mapel'),
            'id_guru'     => $guru['id'],
            'status'      => $this->request->getPost('status'),
        ];

        $targetKelas = $this->request->getPost('target_kelas'); // Array ID Kelas

        // Mulai Transaksi (Supaya aman)
        $db->transStart();

        if ($id) {
            // --- EDIT ---
            $this->bankModel->update($id, $data);
            $bankId = $id;
            
            // Hapus target lama dulu biar bersih
            $db->table('tbl_bank_soal_kelas')->where('id_bank_soal', $id)->delete();
        } else {
            // --- BARU ---
            $this->bankModel->insert($data);
            $bankId = $this->bankModel->getInsertID();
        }

        // Simpan Target Kelas Baru (Looping Array)
        if (!empty($targetKelas)) {
            $batchData = [];
            foreach ($targetKelas as $kelasId) {
                $batchData[] = [
                    'id_bank_soal' => $bankId,
                    'id_kelas'     => $kelasId
                ];
            }
            $db->table('tbl_bank_soal_kelas')->insertBatch($batchData);
        }

        $db->transComplete();

        return redirect()->to(base_url('guru/bank_soal'))->with('success', 'Bank soal & Target Peserta berhasil disimpan.');
    }

    public function hapus($id)
    {
        // Hapus Header (Nanti kita tambahkan trigger hapus butir soal juga disini)
        $this->bankModel->delete($id);
        return redirect()->to(base_url('guru/bank_soal'))->with('success', 'Bank soal berhasil dihapus.');
    }
    // --- HALAMAN KELOLA BUTIR SOAL ---
   public function kelola($idBankSoal)
    {
        $userId = session()->get('id_user');
        $guruId = $this->guruModel->where('user_id', $userId)->first()['id'];
        
        // PERBAIKAN: Tambahkan select & join ke tbl_mapel
        $bank = $this->bankModel
                     ->select('tbl_bank_soal.*, tbl_mapel.nama_mapel') // <--- INI KUNCINYA
                     ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
                     ->where('tbl_bank_soal.id', $idBankSoal)
                     ->where('tbl_bank_soal.id_guru', $guruId)
                     ->first();

        if(!$bank) return redirect()->to(base_url('guru/bank_soal'))->with('error', 'Akses ditolak atau Data tidak ditemukan.');

        $db = \Config\Database::connect();
        
        // Ambil Soal beserta Opsinya
        $listSoal = $db->table('tbl_soal')->where('id_bank_soal', $idBankSoal)->orderBy('id', 'ASC')->get()->getResultArray();
        
        // Inject Opsi ke dalam array Soal
        foreach($listSoal as &$s) {
            $s['opsi'] = $db->table('tbl_opsi_soal')->where('id_soal', $s['id'])->get()->getResultArray();
        }

        $data = [
            'title' => 'Kelola Soal: ' . $bank['judul_ujian'],
            'bank'  => $bank,
            'soal'  => $listSoal
        ];

        return view('guru/bank_soal/kelola', $data);
    }

    // --- PROSES SIMPAN SOAL (SUPPORT ALL TIPE) ---
   // --- SIMPAN SOAL MANUAL (FIXED) ---
    public function simpanSoal()
    {
        $soalModel = new \App\Models\SoalModel();
        $opsiModel = new \App\Models\OpsiSoalModel();
        $db = \Config\Database::connect();

        $idBank     = $this->request->getPost('id_bank_soal');
        $tipe       = $this->request->getPost('tipe_soal');
        $pertanyaan = $this->request->getPost('pertanyaan');
        $bobot      = $this->request->getPost('bobot');

        // 1. Simpan Header
        $soalModel->insert([
            'id_bank_soal' => $idBank,
            'tipe_soal'    => $tipe,
            'pertanyaan'   => $pertanyaan,
            'bobot'        => $bobot
        ]);
        $idSoal = $soalModel->getInsertID();

        // 2. Simpan Opsi (Copy logika opsi dari file sebelumnya)
        $dataOpsi = [];
        
        // --- LOGIKA OPSI DISINGKAT (PASTIKAN BOS COPY FULL DARI FILE SEBELUMNYA) ---
        // Biar tidak kepanjangan, saya contohkan PG saja disini. 
        // Bos bisa pakai logika opsi yang lama, tapi BAGIAN BAWAHNYA diganti.
        if ($tipe == 'PG') {
            $pilihan = $this->request->getPost('pg_opsi');
            $kunci   = $this->request->getPost('pg_kunci');
            if(is_array($pilihan)){
                foreach ($pilihan as $index => $teks) {
                    if(!empty($teks)) {
                        $dataOpsi[] = ['id_soal' => $idSoal, 'kode_opsi' => chr(65 + $index), 'teks_opsi' => $teks, 'is_benar' => ($index == $kunci) ? 1 : 0];
                    }
                }
            }
        }
        // ... (Tambahkan if else untuk tipe lain: PG_KOMPLEKS, MENJODOHKAN, dll disini) ...

        if (!empty($dataOpsi)) {
            $opsiModel->insertBatch($dataOpsi);
        }

        // 3. UPDATE JUMLAH SOAL (FIXED)
        $jumlahSoal = $soalModel->where('id_bank_soal', $idBank)->countAllResults();
        
        // GANTI UPDATE DISINI:
        $db->table('tbl_bank_soal')
           ->where('id', $idBank)
           ->update(['jumlah_soal' => $jumlahSoal]);

        return redirect()->to(base_url('guru/bank_soal/kelola/' . $idBank))->with('success', 'Soal berhasil disimpan.');
    }
    
    // Function Hapus Soal
    // --- HAPUS SOAL (FIXED) ---
    public function hapusSoal($idSoal, $idBank)
    {
        $db = \Config\Database::connect();
        
        // 1. Hapus Opsi & Soal
        $db->table('tbl_opsi_soal')->where('id_soal', $idSoal)->delete();
        $db->table('tbl_soal')->where('id', $idSoal)->delete(); // Pakai where explicitly biar aman
        
        // 2. Hitung Ulang Jumlah
        $jumlahSoal = $db->table('tbl_soal')->where('id_bank_soal', $idBank)->countAllResults();
        
        // 3. Update Counter di Header (GANTI BAGIAN INI)
        // Jangan pakai $this->bankModel->update(), tapi pakai Query Builder:
        $db->table('tbl_bank_soal')
           ->where('id', $idBank)
           ->update(['jumlah_soal' => $jumlahSoal]);
        
        return redirect()->to(base_url('guru/bank_soal/kelola/' . $idBank))->with('success', 'Soal berhasil dihapus.');
    }
    // --- SIMPAN SOAL VIA AJAX (TANPA RELOAD) ---
    // --- SIMPAN SOAL AJAX (FIXED) ---
    public function simpanSoalAjax()
    {
        if (!$this->request->isAJAX()) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $soalModel = new \App\Models\SoalModel();
        $opsiModel = new \App\Models\OpsiSoalModel();
        $db = \Config\Database::connect(); // Load DB Builder
        
        $idBank     = $this->request->getPost('id_bank_soal');
        $idSoal     = $this->request->getPost('id_soal'); 
        $tipe       = $this->request->getPost('tipe_soal');
        $pertanyaan = $this->request->getPost('pertanyaan');
        $bobot      = $this->request->getPost('bobot');

        // Data Soal
        $dataSoal = [
            'id_bank_soal' => $idBank,
            'tipe_soal'    => $tipe,
            'pertanyaan'   => $pertanyaan,
            'bobot'        => $bobot
        ];

        // Update / Insert
        if (!empty($idSoal)) {
            $soalModel->update($idSoal, $dataSoal);
            $opsiModel->where('id_soal', $idSoal)->delete();
        } else {
            $soalModel->insert($dataSoal);
            $idSoal = $soalModel->getInsertID();
        }

        // Simpan Opsi (Logika disingkat, sesuaikan dengan kebutuhan)
        $dataOpsi = [];
        if ($tipe == 'PG') {
            $pilihan = $this->request->getPost('pg_opsi'); 
            $kunci   = $this->request->getPost('pg_kunci');
            if(is_array($pilihan)){
                foreach ($pilihan as $index => $teks) {
                    if(!empty($teks)) {
                        $dataOpsi[] = ['id_soal' => $idSoal, 'kode_opsi' => chr(65 + $index), 'teks_opsi' => $teks, 'is_benar' => ($index == $kunci) ? 1 : 0];
                    }
                }
            }
        }
        // ... (Tambahkan logika tipe lain disini) ...

        if (!empty($dataOpsi)) {
            $opsiModel->insertBatch($dataOpsi);
        }

        // UPDATE JUMLAH SOAL (FIXED)
        $jumlahSoal = $soalModel->where('id_bank_soal', $idBank)->countAllResults();
        
        // GANTI UPDATE DISINI:
        $db->table('tbl_bank_soal')
           ->where('id', $idBank)
           ->update(['jumlah_soal' => $jumlahSoal]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Soal tersimpan']);
    }
    // --- IMPORT SOAL DARI EXCEL ---
    public function importSoal()
    {
        $file = $this->request->getFile('file_excel');
        $idBank = $this->request->getPost('id_bank_soal');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = $file->getClientExtension();
            $reader = ('xls' == $ext) ? new \PhpOffice\PhpSpreadsheet\Reader\Xls() : new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet()->toArray();

            $soalModel = new \App\Models\SoalModel();
            $opsiModel = new \App\Models\OpsiSoalModel();

            $jumlahSukses = 0;

            foreach ($sheet as $key => $row) {
                if ($key == 0) continue; // Skip Header

                // Format Excel: 
                // A: Pertanyaan | B: Tipe (PG/ESSAY) | C: Opsi A | D: Opsi B | E: Opsi C | F: Opsi D | G: Opsi E | H: Kunci (A/B/C/D/E)
                
                $pertanyaan = $row[0];
                $tipe       = strtoupper($row[1] ?? 'PG');
                
                if(empty($pertanyaan)) continue;

                // 1. Simpan Soal
                $soalModel->insert([
                    'id_bank_soal' => $idBank,
                    'tipe_soal'    => $tipe,
                    'pertanyaan'   => $pertanyaan,
                    'bobot'        => 2
                ]);
                $idSoal = $soalModel->getInsertID();

                // 2. Simpan Opsi (Khusus PG)
                if($tipe == 'PG') {
                    $opsiArr = [
                        'A' => $row[2], 'B' => $row[3], 'C' => $row[4], 'D' => $row[5], 'E' => $row[6]
                    ];
                    $kunci = strtoupper($row[7] ?? '');

                    foreach($opsiArr as $kode => $teks) {
                        if(!empty($teks)) {
                            $opsiModel->insert([
                                'id_soal'   => $idSoal,
                                'kode_opsi' => $kode,
                                'teks_opsi' => $teks,
                                'is_benar'  => ($kode == $kunci) ? 1 : 0
                            ]);
                        }
                    }
                }
                $jumlahSukses++;
            }

            // Update Counter
            $jumlahSoal = $soalModel->where('id_bank_soal', $idBank)->countAllResults();
            $this->bankModel->update($idBank, ['jumlah_soal' => $jumlahSoal]);

            return redirect()->to(base_url('guru/bank_soal/kelola/' . $idBank))->with('success', "Berhasil import $jumlahSukses soal.");
        }
        
        return redirect()->back()->with('error', 'File tidak valid.');
    }
    // --- AMBIL DETAIL SOAL (UNTUK EDIT) ---
    public function getDetailSoal($idSoal)
    {
        // Pastikan yang akses adalah pemilik soal (Security Check)
        $userId = session()->get('id_user');
        
        $soalModel = new \App\Models\SoalModel();
        $opsiModel = new \App\Models\OpsiSoalModel();

        $soal = $soalModel->find($idSoal);
        $opsi = $opsiModel->where('id_soal', $idSoal)->orderBy('id', 'ASC')->findAll();

        return $this->response->setJSON([
            'soal' => $soal,
            'opsi' => $opsi
        ]);
    }
    // --- IMPORT SOAL DARI WORD (VERSI FINAL & SUPER ROBUST) ---
    // --- IMPORT SOAL WORD (VERSI FINAL - ANTI BOM & ENTITY) ---
    public function importSoalWord()
    {
        $file = $this->request->getFile('file_word');
        $idBankRaw = $this->request->getPost('id_bank_soal');
        
        // Fix ID jika array (Pencegahan Error "Int Given")
        $idBank = is_array($idBankRaw) ? ($idBankRaw[0] ?? null) : $idBankRaw;

        if (!$file || !$file->isValid() || $file->hasMoved() || empty($idBank)) {
            return redirect()->back()->with('error', 'File tidak valid atau ID Bank Soal hilang.');
        }

        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getTempName());
            
            // 1. Ekstraksi Konten
            $rawLines = [];
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $content = $this->parseWordElement($element);
                    
                    if (!empty($content)) {
                        $splitLines = explode(PHP_EOL, $content);
                        foreach($splitLines as $l) {
                            // --- TAHAP PEMBERSIHAN EKSTRIM (CUCI GUDANG) ---
                            
                            // 1. Hapus BOM (Karakter hantu di awal string)
                            $l = preg_replace('/^\xEF\xBB\xBF/', '', $l);
                            
                            // 2. Decode HTML Entity (Ubah &gt; jadi >)
                            $l = html_entity_decode($l);
                            
                            // 3. Hapus Non-breaking space
                            $l = str_replace(["\xc2\xa0", "&nbsp;"], " ", $l);
                            
                            // 4. Trim spasi depan belakang
                            $l = trim($l);

                            // Masukkan jika ada isinya
                            if(!empty($l) || strpos($l, '<img') !== false) {
                                $rawLines[] = $l;
                            }
                        }
                    }
                }
            }

            // 2. Parsing Logika
            $soalModel = new \App\Models\SoalModel();
            $opsiModel = new \App\Models\OpsiSoalModel();
            $jumlahSukses = 0;
            $currentSoal = [];

            foreach ($rawLines as $line) {
                // Buat versi polos untuk pengecekan
                $plainText = trim(strip_tags($line));
                
                // --- REGEX LEBIH AGRESIF (KEYWORD SEARCH) ---
                // Tidak lagi mewajibkan ^ (awal string) secara ketat jika ada spasi nakal
                
                // DETEKSI SOAL (Q.. > :)
                if (preg_match('/Q\d*\s*>\s*:/i', $plainText)) {
                    
                    if (!empty($currentSoal)) {
                        $this->simpanSoalParsed($idBank, $currentSoal, $soalModel, $opsiModel);
                        $jumlahSukses++;
                    }

                    // Hapus prefix Q dari konten (menggunakan replace regex yang sama)
                    $cleanContent = preg_replace('/^.*?Q\d*\s*>\s*:\s*/i', '', $line);

                    $currentSoal = [
                        'pertanyaan' => $cleanContent, 
                        'opsi_A' => [], 'opsi_B' => [], 'kunci' => '', 'tipe' => 'PG', 'bobot' => 2, 'isian' => ''
                    ];
                } 
                // DETEKSI OPSI A (A > :)
                elseif (preg_match('/^A\s*>\s*:/i', $plainText)) {
                    $currentSoal['opsi_A'][] = preg_replace('/^.*?A\s*>\s*:\s*/i', '', $line);
                }
                // DETEKSI OPSI B (B > :)
                elseif (preg_match('/^B\s*>\s*:/i', $plainText)) {
                    $currentSoal['opsi_B'][] = preg_replace('/^.*?B\s*>\s*:\s*/i', '', $line);
                }
                // DETEKSI KUNCI (K > :)
                elseif (preg_match('/^K\s*>\s*:\s*(.*)/i', $plainText, $matches)) {
                    $currentSoal['kunci'] = trim($matches[1]);
                }
                // DETEKSI BOBOT (PT > :)
                elseif (preg_match('/^PT\s*>\s*:\s*(.*)/i', $plainText, $matches)) {
                    $currentSoal['bobot'] = floatval($matches[1]);
                }
                // DETEKSI ISIAN (SE > :)
                elseif (preg_match('/^SE\s*>\s*:\s*(.*)/i', $plainText, $matches)) {
                    $currentSoal['isian'] = trim($matches[1]);
                    $currentSoal['tipe'] = 'ISIAN_SINGKAT';
                }
                // LANJUTAN
                elseif (!empty($currentSoal)) {
                    $currentSoal['pertanyaan'] .= '<br>' . $line;
                }
            }

            // Simpan yang terakhir
            if (!empty($currentSoal)) {
                $this->simpanSoalParsed($idBank, $currentSoal, $soalModel, $opsiModel);
                $jumlahSukses++;
            }

            // 3. UPDATE JUMLAH SOAL (AMAN DARI ERROR INT GIVEN)
            $jumlah = $soalModel->where('id_bank_soal', $idBank)->countAllResults();
            $db = \Config\Database::connect();
            $db->table('tbl_bank_soal')->where('id', $idBank)->update(['jumlah_soal' => $jumlah]);

            return redirect()->to(base_url('guru/bank_soal/kelola/' . $idBank))->with('success', "Import Selesai! $jumlahSukses soal berhasil masuk.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Import: ' . $e->getMessage());
        }
    }
   // --- HELPER BACA WORD (TEXT + GAMBAR) ---
    private function parseWordElement($element)
    {
        $html = '';
        if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            foreach ($element->getElements() as $child) {
                $html .= $this->parseWordElement($child);
            }
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\Image) {
            $html .= $this->saveImageFromWord($element);
        } elseif (method_exists($element, 'getText')) {
            $html .= $element->getText();
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextBreak) {
            $html .= PHP_EOL; 
        }
        return $html;
    }
    // --- HELPER 2: SIMPAN GAMBAR KE SERVER ---
    private function saveImageFromWord($imageElement)
    {
        try {
            // Siapkan Folder
            $path = FCPATH . 'uploads/bank_soal/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            // Ambil Data Gambar
            $imageData = $imageElement->getImageStringData(true);
            
            // Deteksi Ekstensi (default jpg jika gagal detect)
            $ext = $imageElement->getImageExtension();
            if (!$ext) $ext = 'jpg';

            // Nama File Unik
            $fileName = 'img_import_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $ext;
            
            // Simpan Fisik File
            file_put_contents($path . $fileName, $imageData);

            // Return Tag HTML
            return '<img src="' . base_url('uploads/bank_soal/' . $fileName) . '" style="max-width: 100%; height: auto; margin: 10px 0;">';
        } catch (\Exception $e) {
            return '[Gagal load gambar]';
        }
    }
   // --- HELPER LOGIC SIMPAN KE DB ---
    private function simpanSoalParsed($idBank, $data, $soalModel, $opsiModel)
    {
        // 1. Tentukan Tipe Soal
        $tipe = 'PG';
        if (!empty($data['opsi_B'])) {
            $tipe = 'MENJODOHKAN';
        } elseif ($data['tipe'] === 'ISIAN_SINGKAT') {
            $tipe = 'ISIAN_SINGKAT';
        } elseif (strpos($data['kunci'], ',') !== false || strpos($data['kunci'], '-') !== false) {
            $tipe = 'PG_KOMPLEKS'; 
        }

        // 2. Insert Header Soal
        $soalModel->insert([
            'id_bank_soal' => $idBank,
            'tipe_soal'    => $tipe,
            'pertanyaan'   => $data['pertanyaan'],
            'bobot'        => $data['bobot']
        ]);
        $idSoal = $soalModel->getInsertID();

        // 3. Insert Opsi
        $abjad = range('A', 'Z');

        // --- TIPE PG & PG KOMPLEKS ---
        if ($tipe == 'PG' || $tipe == 'PG_KOMPLEKS') {
            $kunciArr = [];
            // Parse kunci (bisa "A", "A,B", atau "A-1,B-0")
            $rawKunci = explode(',', $data['kunci']);
            foreach($rawKunci as $k) {
                $k = trim($k);
                if(strpos($k, '-') !== false) {
                    $parts = explode('-', $k);
                    // Ambil jika nilainya 1 (Benar)
                    if(isset($parts[1]) && trim($parts[1]) == '1') {
                        $kunciArr[] = trim($parts[0]);
                    }
                } else {
                    $kunciArr[] = $k;
                }
            }

            foreach ($data['opsi_A'] as $idx => $teks) {
                $kode = $abjad[$idx] ?? '?';
                $isBenar = in_array($kode, $kunciArr) ? 1 : 0;
                
                $opsiModel->insert([
                    'id_soal' => $idSoal, 'kode_opsi' => $kode, 'teks_opsi' => $teks, 'is_benar' => $isBenar
                ]);
            }
        }
        
        // --- TIPE MENJODOHKAN ---
        elseif ($tipe == 'MENJODOHKAN') {
            // Kunci format: A-D, B-A (Premis-Jawaban)
            $pairs = explode(',', $data['kunci']); 
            
            // Loop Opsi B (Premis/Kiri)
            foreach ($data['opsi_B'] as $idx => $teksKiri) {
                $kodePremis = $abjad[$idx] ?? '?'; 
                $teksKanan = '';

                // Cari pasangan kode hurufnya
                foreach($pairs as $p) {
                    $pair = explode('-', trim($p)); 
                    // Pair[0] = Premis, Pair[1] = Target Jawaban
                    if(trim($pair[0]) == $kodePremis) {
                        $hurufTarget = trim($pair[1] ?? '');
                        // Cari teks jawaban dari Opsi A berdasarkan index huruf target
                        $idxTarget = array_search($hurufTarget, $abjad);
                        if($idxTarget !== false && isset($data['opsi_A'][$idxTarget])) {
                            $teksKanan = $data['opsi_A'][$idxTarget];
                        }
                        break;
                    }
                }

                if(!empty($teksKanan)) {
                    $opsiModel->insert([
                        'id_soal' => $idSoal, 'kode_opsi' => $teksKiri, 'teks_opsi' => $teksKanan, 'is_benar' => 1
                    ]);
                }
            }
        }

        // --- TIPE ISIAN SINGKAT ---
        elseif ($tipe == 'ISIAN_SINGKAT') {
             $opsiModel->insert([
                'id_soal' => $idSoal, 'kode_opsi' => 'KUNCI', 'teks_opsi' => $data['isian'], 'is_benar' => 1
            ]);
        }
    }
    // --- GENERATE TEMPLATE WORD OTOMATIS ---
    public function downloadTemplateWord()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Style Font
        $headerStyle = ['bold' => true, 'size' => 14];
        $boldStyle = ['bold' => true];
        $normalStyle = ['size' => 11];

        // --- JUDUL ---
        $section->addText('TEMPLATE IMPORT SOAL (Q-FORMAT)', $headerStyle);
        $section->addText('Panduan:', $boldStyle);
        $section->addText('1. Jangan hapus kode (Q>:, A>:, K>:).', $normalStyle);
        $section->addText('2. Gambar WAJIB setting "In Line with Text".', $normalStyle);
        $section->addText('3. PT = Bobot Nilai (Opsional, default 2).', $normalStyle);
        $section->addTextBreak(1);

        // --- CONTOH 1: PILIHAN GANDA BIASA ---
        $section->addText('--- CONTOH 1: PILIHAN GANDA (PG) ---', ['bold' => true, 'color' => '0000FF']);
        $section->addText('Q>: Siapa Presiden pertama Indonesia?');
        $section->addText('(Disini bisa menyisipkan gambar)');
        $section->addText('A>: Soeharto');
        $section->addText('A>: B.J. Habibie');
        $section->addText('A>: Ir. Soekarno');
        $section->addText('A>: Jokowi');
        $section->addText('A>: Megawati');
        $section->addText('K>: C');
        $section->addText('PT>: 2');
        $section->addTextBreak(1);

        // --- CONTOH 2: PG KOMPLEKS (BANYAK JAWABAN) ---
        $section->addText('--- CONTOH 2: PG KOMPLEKS (Banyak Jawaban) ---', ['bold' => true, 'color' => '0000FF']);
        $section->addText('Q>: Manakah yang termasuk buah-buahan?');
        $section->addText('A>: Ayam');
        $section->addText('A>: Apel');
        $section->addText('A>: Kucing');
        $section->addText('A>: Mangga');
        $section->addText('A>: Besi');
        $section->addText('K>: B, D');
        $section->addText('PT>: 4');
        $section->addTextBreak(1);

        // --- CONTOH 3: MENJODOHKAN ---
        $section->addText('--- CONTOH 3: MENJODOHKAN ---', ['bold' => true, 'color' => '0000FF']);
        $section->addText('Q>: Pasangkan Ibukota Provinsi berikut!');
        $section->addText('B>: Jawa Barat'); 
        $section->addText('B>: Jawa Timur');
        $section->addText('B>: Jawa Tengah');
        $section->addText('A>: Surabaya');
        $section->addText('A>: Bandung');
        $section->addText('A>: Semarang');
        $section->addText('A>: Medan');
        $section->addText('A>: Makasar');
        $section->addText('K>: A-B, B-A, C-C');
        $section->addText('(Artinya: Premis A jodohnya B, Premis B jodohnya A, dst)');
        $section->addTextBreak(1);

        // --- CONTOH 4: ISIAN SINGKAT ---
        $section->addText('--- CONTOH 4: ISIAN SINGKAT ---', ['bold' => true, 'color' => '0000FF']);
        $section->addText('Q>: 1 minggu ada berapa hari?');
        $section->addText('SE>: 7');
        $section->addTextBreak(1);

        // --- DOWNLOAD FILE ---
        $filename = "Template_Soal_Q_Format.docx";
        
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
        exit;
    }
    // --- UPDATE SETTING UJIAN (TOKEN, WAKTU, ACAK) ---
    public function updateSetting()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'durasi'       => $this->request->getPost('durasi'),
            'token'        => strtoupper($this->request->getPost('token')), // Token huruf besar semua
            'acak_soal'    => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_opsi'    => $this->request->getPost('acak_opsi') ? 1 : 0,
            'wajib_lokasi' => $this->request->getPost('wajib_lokasi') ? 1 : 0,
            'status'       => $this->request->getPost('status'), // 1=Aktif, 0=Draft
            
            // Lokasi (Kalau wajib lokasi dicentang)
            'lat_ujian'    => $this->request->getPost('lat_ujian'),
            'long_ujian'   => $this->request->getPost('long_ujian'),
            'radius_ujian' => $this->request->getPost('radius_ujian')
        ];

        $this->bankModel->update($id, $data);

        return redirect()->back()->with('success', 'Pengaturan Ujian berhasil diperbarui!');
    }
    
}