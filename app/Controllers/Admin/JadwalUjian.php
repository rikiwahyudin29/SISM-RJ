<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class JadwalUjian extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Monitoring Jadwal Ujian',
            'jadwal' => $this->db->table('tbl_jadwal_ujian')
                        ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_jenis_ujian.nama_jenis, tbl_guru.nama_lengkap as nama_guru')
                        ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
                        ->join('tbl_jenis_ujian', 'tbl_jenis_ujian.id = tbl_jadwal_ujian.id_jenis_ujian', 'left')
                        ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal_ujian.id_guru', 'left')
                        ->orderBy('waktu_mulai', 'DESC')
                        ->get()->getResultArray()
        ];
        return view('admin/ujian/index', $data);
    }

    // --- FITUR TAMBAH ---
    public function tambah()
    {
        $data = [
            'title' => 'Tambah Jadwal Ujian (Admin)',
            // Ambil SEMUA Bank Soal + Nama Gurunya
            'bank_soal' => $this->db->table('tbl_bank_soal')
                            ->select('tbl_bank_soal.*, tbl_guru.nama_lengkap as nama_guru, tbl_mapel.nama_mapel')
                            ->join('tbl_guru', 'tbl_guru.id = tbl_bank_soal.id_guru')
                            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel', 'left')
                            ->orderBy('tbl_guru.nama_lengkap', 'ASC')
                            ->get()->getResultArray(),
            'kelas' => $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray(),
            'tahun_ajaran' => $this->db->table('tbl_tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray(),
            'jenis_ujian'  => $this->db->table('tbl_jenis_ujian')->get()->getResultArray()
        ];
        return view('admin/ujian/tambah', $data);
    }

    public function simpan()
    {
        // LOGIC PENTING: Cari ID Guru pemilik Bank Soal yang dipilih
        $idBankSoal = $this->request->getPost('id_bank_soal');
        $bankData = $this->db->table('tbl_bank_soal')->where('id', $idBankSoal)->get()->getRow();
        
        if(!$bankData) return redirect()->back()->with('error', 'Bank Soal tidak valid.');

        $data = [
            'id_guru'       => $bankData->id_guru, // Otomatis set pemilik soal
            'nama_ujian'    => $this->request->getPost('nama_ujian'),
            'id_bank_soal'  => $idBankSoal,
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
            'id_jenis_ujian'  => $this->request->getPost('id_jenis_ujian'),
            'waktu_mulai'       => $this->request->getPost('waktu_mulai'),
            'waktu_selesai'     => $this->request->getPost('waktu_selesai'),
            'durasi'            => $this->request->getPost('durasi'),
            'min_waktu_selesai' => $this->request->getPost('min_waktu') ?? 0,
            'bobot_pg'      => $this->request->getPost('bobot_pg'),
            'bobot_esai'    => $this->request->getPost('bobot_esai'),
            'setting_strict'        => $this->request->getPost('setting_strict') ? 1 : 0,
            'setting_afk_timeout'   => $this->request->getPost('setting_afk_timeout') ?? 0,
            'setting_max_violation' => $this->request->getPost('setting_max_violation') ?? 3,
            'setting_token'         => $this->request->getPost('setting_token') ? 1 : 0,
            'token'                 => strtoupper($this->request->getPost('token')),
            'setting_show_score'    => $this->request->getPost('setting_show_score') ? 1 : 0,
            'setting_multi_login'   => $this->request->getPost('setting_multi_login') ? 1 : 0,
            'acak_soal'             => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_opsi'             => $this->request->getPost('acak_opsi') ? 1 : 0,
            'status_ujian'          => 'AKTIF'
        ];

        $this->db->table('tbl_jadwal_ujian')->insert($data);
        $idJadwal = $this->db->insertID();

        // Simpan Kelas
        $kelas = $this->request->getPost('kelas');
        if ($kelas) {
            $batch = [];
            foreach ($kelas as $k) {
                $batch[] = ['id_jadwal_ujian' => $idJadwal, 'id_kelas' => $k];
            }
            $this->db->table('tbl_jadwal_kelas')->insertBatch($batch);
        }

        return redirect()->to('admin/jadwalujian')->with('success', 'Jadwal berhasil dibuat oleh Admin.');
    }

    // --- FITUR EDIT ---
    public function edit($id)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $id)->get()->getRowArray();
        if(!$jadwal) return redirect()->to('admin/jadwalujian');

        // Ambil kelas yang sudah terpilih
        $kelasTerpilih = $this->db->table('tbl_jadwal_kelas')->where('id_jadwal_ujian', $id)->select('id_kelas')->get()->getResultArray();
        $idsKelas = array_column($kelasTerpilih, 'id_kelas');

        $data = [
            'title' => 'Edit Jadwal Ujian',
            'j' => $jadwal,
            'kelas_terpilih' => $idsKelas,
            // Ambil semua data pendukung
            'bank_soal' => $this->db->table('tbl_bank_soal')
                            ->select('tbl_bank_soal.*, tbl_guru.nama_lengkap as nama_guru, tbl_mapel.nama_mapel')
                            ->join('tbl_guru', 'tbl_guru.id = tbl_bank_soal.id_guru')
                            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel', 'left')
                            ->orderBy('tbl_guru.nama_lengkap', 'ASC')
                            ->get()->getResultArray(),
            'kelas' => $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray(),
            'tahun_ajaran' => $this->db->table('tbl_tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray(),
            'jenis_ujian'  => $this->db->table('tbl_jenis_ujian')->get()->getResultArray()
        ];
        return view('admin/ujian/edit', $data);
    }

    public function update($id)
    {
        // Ambil data bank soal baru (jika berubah)
        $idBankSoal = $this->request->getPost('id_bank_soal');
        $bankData = $this->db->table('tbl_bank_soal')->where('id', $idBankSoal)->get()->getRow();

        $data = [
            'id_guru'       => $bankData->id_guru,
            'nama_ujian'    => $this->request->getPost('nama_ujian'),
            'id_bank_soal'  => $idBankSoal,
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
            'id_jenis_ujian'  => $this->request->getPost('id_jenis_ujian'),
            'waktu_mulai'       => $this->request->getPost('waktu_mulai'),
            'waktu_selesai'     => $this->request->getPost('waktu_selesai'),
            'durasi'            => $this->request->getPost('durasi'),
            'min_waktu_selesai' => $this->request->getPost('min_waktu') ?? 0,
            'bobot_pg'      => $this->request->getPost('bobot_pg'),
            'bobot_esai'    => $this->request->getPost('bobot_esai'),
            'setting_strict'        => $this->request->getPost('setting_strict') ? 1 : 0,
            'setting_afk_timeout'   => $this->request->getPost('setting_afk_timeout') ?? 0,
            'setting_max_violation' => $this->request->getPost('setting_max_violation') ?? 3,
            'setting_token'         => $this->request->getPost('setting_token') ? 1 : 0,
            'token'                 => strtoupper($this->request->getPost('token')),
            'setting_show_score'    => $this->request->getPost('setting_show_score') ? 1 : 0,
            'setting_multi_login'   => $this->request->getPost('setting_multi_login') ? 1 : 0,
            'acak_soal'             => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_opsi'             => $this->request->getPost('acak_opsi') ? 1 : 0,
        ];

        $this->db->table('tbl_jadwal_ujian')->where('id', $id)->update($data);

        // Update Kelas (Hapus dulu yang lama, insert baru)
        $this->db->table('tbl_jadwal_kelas')->where('id_jadwal_ujian', $id)->delete();
        $kelas = $this->request->getPost('kelas');
        if ($kelas) {
            $batch = [];
            foreach ($kelas as $k) {
                $batch[] = ['id_jadwal_ujian' => $id, 'id_kelas' => $k];
            }
            $this->db->table('tbl_jadwal_kelas')->insertBatch($batch);
        }

        return redirect()->to('admin/jadwalujian')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->db->table('tbl_jadwal_ujian')->where('id', $id)->delete();
        $this->db->table('tbl_jadwal_kelas')->where('id_jadwal_ujian', $id)->delete();
        return redirect()->to('admin/jadwalujian')->with('success', 'Jadwal berhasil dihapus.');
    }
}