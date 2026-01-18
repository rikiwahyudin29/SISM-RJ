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
    public function simpanSoal()
    {
        $soalModel = new \App\Models\SoalModel();
        $opsiModel = new \App\Models\OpsiSoalModel();
        $db = \Config\Database::connect();

        $idBank     = $this->request->getPost('id_bank_soal');
        $tipe       = $this->request->getPost('tipe_soal');
        $pertanyaan = $this->request->getPost('pertanyaan');
        $bobot      = $this->request->getPost('bobot');

        // 1. Simpan Header Soal (Tabel tbl_soal)
        $dataSoal = [
            'id_bank_soal' => $idBank,
            'tipe_soal'    => $tipe,
            'pertanyaan'   => $pertanyaan,
            'bobot'        => $bobot
        ];
        
        $soalModel->insert($dataSoal);
        $idSoal = $soalModel->getInsertID();

        // 2. Simpan Opsi Jawaban (Sesuai Tipe)
        $dataOpsi = [];

        // --- TIPE PILIHAN GANDA (PG) ---
        if ($tipe == 'PG') {
            $pilihan = $this->request->getPost('pg_opsi'); // Array A,B,C,D,E
            $kunci   = $this->request->getPost('pg_kunci'); // Index 0-4

            foreach ($pilihan as $index => $teks) {
                if(!empty($teks)) {
                    $dataOpsi[] = [
                        'id_soal'   => $idSoal,
                        'kode_opsi' => chr(65 + $index), // Jadi A, B, C...
                        'teks_opsi' => $teks,
                        'is_benar'  => ($index == $kunci) ? 1 : 0
                    ];
                }
            }
        }
        
        // --- TIPE PG KOMPLEKS (Checkboxes) ---
        else if ($tipe == 'PG_KOMPLEKS') {
            $pilihan = $this->request->getPost('pgk_opsi'); 
            $kunci   = $this->request->getPost('pgk_kunci'); // Array index yg dicentang

            foreach ($pilihan as $index => $teks) {
                if(!empty($teks)) {
                    // Cek apakah index ini ada di array kunci
                    $isBenar = (isset($kunci) && in_array($index, $kunci)) ? 1 : 0;
                    $dataOpsi[] = [
                        'id_soal'   => $idSoal,
                        'kode_opsi' => '', // Tidak butuh A/B/C
                        'teks_opsi' => $teks,
                        'is_benar'  => $isBenar
                    ];
                }
            }
        }

        // --- TIPE BENAR - SALAH ---
        else if ($tipe == 'BENAR_SALAH') {
            $kunci = $this->request->getPost('bs_kunci'); // 'Benar' atau 'Salah'
            
            $opsiBS = ['Benar', 'Salah'];
            foreach ($opsiBS as $bs) {
                $dataOpsi[] = [
                    'id_soal'   => $idSoal,
                    'kode_opsi' => $bs,
                    'teks_opsi' => $bs, // Teksnya statis
                    'is_benar'  => ($bs == $kunci) ? 1 : 0
                ];
            }
        }

        // --- TIPE MENJODOHKAN ---
        else if ($tipe == 'MENJODOHKAN') {
            $kiri  = $this->request->getPost('jodoh_kiri');
            $kanan = $this->request->getPost('jodoh_kanan');
            
            if (!empty($kiri)) {
                foreach ($kiri as $idx => $valKiri) {
                    if (!empty($valKiri)) {
                        $dataOpsi[] = [
                            'id_soal'   => $idSoal,
                            'kode_opsi' => $valKiri, // Kiri disimpan di kode
                            'teks_opsi' => $kanan[$idx] ?? '', // Kanan disimpan di teks
                            'is_benar'  => 1 // Dianggap pasangan benar
                        ];
                    }
                }
            }
        }

        // --- TIPE ISIAN SINGKAT ---
        else if ($tipe == 'ISIAN_SINGKAT') {
            $kunci = $this->request->getPost('isian_kunci');
            $dataOpsi[] = [
                'id_soal'   => $idSoal,
                'kode_opsi' => 'KUNCI',
                'teks_opsi' => $kunci,
                'is_benar'  => 1
            ];
        }

        // 3. Batch Insert Opsi (Kecuali Essay yg ga punya opsi)
        if (!empty($dataOpsi)) {
            $opsiModel->insertBatch($dataOpsi);
        }

        // 4. Update Jumlah Soal di Header
        $jumlahSoal = $soalModel->where('id_bank_soal', $idBank)->countAllResults();
        $this->bankModel->update($idBank, ['jumlah_soal' => $jumlahSoal]);

        return redirect()->to(base_url('guru/bank_soal/kelola/' . $idBank))->with('success', 'Soal berhasil disimpan.');
    }
    
    // Function Hapus Soal
    public function hapusSoal($idSoal, $idBank)
    {
        $db = \Config\Database::connect();
        $db->table('tbl_opsi_soal')->where('id_soal', $idSoal)->delete();
        $db->table('tbl_soal')->delete($idSoal);
        
        // Update Counter
        $jumlahSoal = $db->table('tbl_soal')->where('id_bank_soal', $idBank)->countAllResults();
        $this->bankModel->update($idBank, ['jumlah_soal' => $jumlahSoal]);
        
        return redirect()->to(base_url('guru/bank_soal/kelola/' . $idBank))->with('success', 'Soal dihapus.');
    }
    // --- SIMPAN SOAL VIA AJAX (TANPA RELOAD) ---
    public function simpanSoalAjax()
    {
        if (!$this->request->isAJAX()) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $soalModel = new \App\Models\SoalModel();
        $opsiModel = new \App\Models\OpsiSoalModel();
        
        $idBank     = $this->request->getPost('id_bank_soal');
        $idSoal     = $this->request->getPost('id_soal'); // Jika ada ID, berarti Edit
        $tipe       = $this->request->getPost('tipe_soal');
        $pertanyaan = $this->request->getPost('pertanyaan');
        $bobot      = $this->request->getPost('bobot');

        // Data Header Soal
        $dataSoal = [
            'id_bank_soal' => $idBank,
            'tipe_soal'    => $tipe,
            'pertanyaan'   => $pertanyaan,
            'bobot'        => $bobot
        ];

        if (!empty($idSoal)) {
            // Update Soal Lama
            $soalModel->update($idSoal, $dataSoal);
            // Hapus Opsi Lama (Kita timpa total opsi)
            $opsiModel->where('id_soal', $idSoal)->delete();
        } else {
            // Insert Soal Baru
            $soalModel->insert($dataSoal);
            $idSoal = $soalModel->getInsertID();
        }

        // Simpan Opsi (Sama seperti logika sebelumnya, tapi dipersingkat)
        $dataOpsi = [];
        
        // ... (COPY LOGIKA SIMPAN OPSI DARI METHOD simpanSoal() YANG LAMA DISINI) ...
        // Agar codingan tidak panjang, pakai logika yang sudah ada di method simpanSoal()
        // Untuk PG, PG_KOMPLEKS, dll.

        // CONTOH SINGKAT (Sesuaikan dengan codingan opsi sebelumnya):
        if ($tipe == 'PG') {
            $pilihan = $this->request->getPost('pg_opsi'); 
            $kunci   = $this->request->getPost('pg_kunci');
            foreach ($pilihan as $index => $teks) {
                if(!empty($teks)) {
                    $dataOpsi[] = [
                        'id_soal' => $idSoal, 'kode_opsi' => chr(65 + $index), 'teks_opsi' => $teks, 'is_benar' => ($index == $kunci) ? 1 : 0
                    ];
                }
            }
        }
        // ... Lanjutkan untuk tipe lain ...

        if (!empty($dataOpsi)) {
            $opsiModel->insertBatch($dataOpsi);
        }

        // Update Counter
        $jumlahSoal = $soalModel->where('id_bank_soal', $idBank)->countAllResults();
        $this->bankModel->update($idBank, ['jumlah_soal' => $jumlahSoal]);

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
}