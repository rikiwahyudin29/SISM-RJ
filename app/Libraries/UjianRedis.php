<?php

namespace App\Libraries;

use App\Models\BankSoalModel;
use App\Models\SoalModel;
use App\Models\OpsiSoalModel;

class UjianRedis
{
    protected $cache;
    
    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    // 1. FUNGSI AMBIL SOAL (CACHE FIRST)
    public function getSoalUjian($idBankSoal)
    {
        $cacheKey = "paket_soal_{$idBankSoal}";

        // Cek apakah soal sudah ada di Redis?
        $dataSoal = $this->cache->get($cacheKey);

        if (!$dataSoal) {
            // Jika KOSONG di Redis, baru ambil dari MySQL (Cuma terjadi 1x untuk user pertama)
            $db = \Config\Database::connect();
            
            // Ambil Header
            $bankModel = new BankSoalModel();
            $header = $bankModel->find($idBankSoal);

            // Ambil Detail Soal & Opsi
            $soal = $db->table('tbl_soal')
                       ->where('id_bank_soal', $idBankSoal)
                       ->orderBy('id', 'ASC') // Atau Random nanti di sisi Client
                       ->get()->getResultArray();

            foreach ($soal as &$s) {
                $s['opsi'] = $db->table('tbl_opsi_soal')
                                ->where('id_soal', $s['id'])
                                ->get()->getResultArray();
            }

            $dataSoal = [
                'header' => $header,
                'soal'   => $soal
            ];

            // SIMPAN KE REDIS (Durasi misal 3 jam)
            // Semua user berikutnya akan ambil dari sini
            $this->cache->save($cacheKey, $dataSoal, 10800); 
        }

        return $dataSoal;
    }
    public function simpanJawabanSiswa($idUser, $idBankSoal, $idSoal, $jawaban)
    {
        // Key Unik per User per Ujian
        // Contoh Key: jawaban_siswa_105_25 (User 105, Bank Soal 25)
        $key = "jawaban_siswa_{$idUser}_{$idBankSoal}";

        // Kita ambil data jawaban lama dari Redis
        $currentAnswers = $this->cache->get($key) ?? [];

        // Update jawaban untuk soal nomor sekian
        $currentAnswers[$idSoal] = $jawaban;

        // Simpan balik ke Redis
        // Ini super cepat (microsecond), server tidak akan berat
        $this->cache->save($key, $currentAnswers, 10800);

        return true;
    }
    
    // 3. AMBIL JAWABAN SISWA (Untuk ditampilkan kembali jika refresh page)
    public function getJawabanSiswa($idUser, $idBankSoal)
    {
        $key = "jawaban_siswa_{$idUser}_{$idBankSoal}";
        return $this->cache->get($key) ?? [];
    }
    public function selesaiUjian()
    {
        $ujianRedis = new \App\Libraries\UjianRedis();
        $idUser     = session()->get('id_user');
        $idBankSoal = $this->request->getPost('id_bank_soal');

        // 1. Ambil semua jawaban dari Redis
        $jawabanRedis = $ujianRedis->getJawabanSiswa($idUser, $idBankSoal);

        if (empty($jawabanRedis)) {
            return redirect()->to('/')->with('error', 'Tidak ada jawaban yang disimpan.');
        }

        // 2. Siapkan Array untuk Insert Batch ke MySQL
        $batchData = [];
        $nilaiTotal = 0; // Logika hitung nilai bisa disini

        foreach ($jawabanRedis as $idSoal => $jawab) {
            // Cek Kunci Jawaban (Logic hitung nilai disederhanakan)
            // ... (Logic cek kunci jawaban ambil dari cache soal) ...

            $batchData[] = [
                'id_user'      => $idUser,
                'id_bank_soal' => $idBankSoal,
                'id_soal'      => $idSoal,
                'jawaban'      => $jawab,
                'ragu_ragu'    => 0
            ];
        }

        // 3. INSERT KE MYSQL (Satu kali query untuk puluhan soal)
        $db = \Config\Database::connect();
        $db->table('tbl_jawaban_siswa')->insertBatch($batchData);

        // 4. Hapus Cache Jawaban Siswa (Bersih-bersih)
        cache()->delete("jawaban_siswa_{$idUser}_{$idBankSoal}");

        return redirect()->to('ujian/hasil')->with('success', 'Ujian Selesai!');
    }
}