<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Nilai extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    // Tampilan Input Nilai
    public function index()
    {
        $id_kelas = $this->request->getGet('kelas_id');
        $id_mapel = $this->request->getGet('mapel_id');

        $data = [
            'title'  => 'Input Nilai Siswa',
            'kelas'  => $this->db->table('tbl_kelas')->get()->getResultArray(),
            'mapel'  => $this->db->table('tbl_mapel')->get()->getResultArray(),
            'siswa'  => [],
            'setting'=> null
        ];

        if ($id_kelas && $id_mapel) {
            $data['siswa'] = $this->db->table('tbl_siswa')
                ->select('tbl_siswa.id, tbl_siswa.nama_lengkap, tbl_siswa.nis, 
                          tbl_nilai.tugas, tbl_nilai.uh, tbl_nilai.pts, tbl_nilai.pas, 
                          tbl_nilai.akhir, tbl_nilai.predikat')
                ->join('tbl_nilai', "tbl_nilai.siswa_id = tbl_siswa.id AND tbl_nilai.mapel_id = $id_mapel", 'left')
                ->where('tbl_siswa.kelas_id', $id_kelas)
                ->get()->getResultArray();
            
            $data['setting'] = $this->db->table('tbl_set_nilai')
                ->where(['mapel_id' => $id_mapel, 'kelas_id' => $id_kelas])
                ->get()->getRow();
        }

        return view('guru/nilai/index', $data);
    }

    // Logika Hitung Nilai & Konversi Predikat
    private function konversi_predikat($nilai, $kkm) {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= $kkm) return 'C';
        return 'D';
    }
    public function save_batch()
{
    $kelas_id = $this->request->getPost('kelas_id');
    $mapel_id = $this->request->getPost('mapel_id');
    $nilai_input = $this->request->getPost('nilai');

    // Ambil setting bobot
    $set = $this->db->table('tbl_set_nilai')->where(['mapel_id' => $mapel_id, 'kelas_id' => $kelas_id])->get()->getRow();
    $kkm = $set->kkm ?? 75;

    foreach ($nilai_input as $siswa_id => $v) {
        // Rumus Rata-rata Akhir
        $akhir = (
            ($v['tugas'] * ($set->p_tugas ?? 20) / 100) +
            ($v['uh'] * ($set->p_uh ?? 30) / 100) +
            ($v['pts'] * ($set->p_pts ?? 25) / 100) +
            ($v['pas'] * ($set->p_pas ?? 25) / 100)
        );

        $data_update = [
            'siswa_id' => $siswa_id,
            'mapel_id' => $mapel_id,
            'kelas_id' => $kelas_id,
            'tugas'    => $v['tugas'],
            'uh'       => $v['uh'],
            'pts'      => $v['pts'],
            'pas'      => $v['pas'],
            'akhir'    => $akhir,
            'predikat' => $this->konversi_predikat($akhir, $kkm)
        ];

        // Cek apakah sudah ada data nilai
        $cek = $this->db->table('tbl_nilai')->where(['siswa_id' => $siswa_id, 'mapel_id' => $mapel_id])->get()->getRow();
        
        if ($cek) {
            $this->db->table('tbl_nilai')->where('id', $cek->id)->update($data_update);
        } else {
            $this->db->table('tbl_nilai')->insert($data_update);
        }
    }

    return redirect()->back()->with('success', 'Nilai berhasil diolah dan disimpan!');
}
public function download_template()
    {
        $kelas_id = $this->request->getGet('kelas_id');
        $mapel_id = $this->request->getGet('mapel_id');

        if (!$kelas_id || !$mapel_id) {
            return "Pilih Kelas dan Mapel terlebih dahulu di halaman sebelumnya!";
        }

        // Ambil Data Siswa di Kelas itu
        $siswa = $this->db->table('tbl_siswa')
            ->select('id, nama_lengkap, nis')
            ->where('kelas_id', $kelas_id)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $nama_kelas = $this->db->table('tbl_kelas')->where('id', $kelas_id)->get()->getRow()->nama_kelas;
        $nama_mapel = $this->db->table('tbl_mapel')->where('id', $mapel_id)->get()->getRow()->nama_mapel;

        // Set Header Browser untuk Download File CSV
        $filename = 'TEMPLATE_NILAI_' . preg_replace('/[^A-Za-z0-9]/', '_', $nama_kelas) . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $fp = fopen('php://output', 'w');

        // Header Kolom CSV
        fputcsv($fp, ['ID_SISTEM (JANGAN UBAH)', 'NIS', 'NAMA SISWA', 'TUGAS', 'UH', 'PTS', 'PAS']);

        // Isi Data Siswa
        foreach ($siswa as $s) {
            fputcsv($fp, [
                $s['id'],            // ID Sistem (Hidden key)
                $s['nis'],           // Info user
                $s['nama_lengkap'],  // Info user
                0, 0, 0, 0           // Nilai Default
            ]);
        }

        fclose($fp);
        exit;
    }

    /**
     * 2. Proses Import CSV
     */
    public function import()
    {
        $kelas_id = $this->request->getPost('kelas_id');
        $mapel_id = $this->request->getPost('mapel_id');
        $file = $this->request->getFile('file_csv');

        if (!$file->isValid() || $file->getExtension() !== 'csv') {
            return redirect()->back()->with('error', 'File tidak valid! Harap upload file .csv');
        }

        // Ambil Setting Bobot untuk Rumus Otomatis
        $set = $this->db->table('tbl_set_nilai')->where(['mapel_id' => $mapel_id, 'kelas_id' => $kelas_id])->get()->getRow();
        
        // Default Bobot jika belum disetting
        $p_tugas = $set->p_tugas ?? 20;
        $p_uh    = $set->p_uh ?? 30;
        $p_pts   = $set->p_pts ?? 25;
        $p_pas   = $set->p_pas ?? 25;
        $kkm     = $set->kkm ?? 75;

        // Baca File CSV
        $handle = fopen($file->getTempName(), 'r');
        $i = 0;
        $sukses = 0;

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $i++;
            if ($i == 1) continue; // Skip Header Baris Pertama

            // Mapping Kolom CSV (Sesuai urutan di download_template)
            $id_siswa = $row[0]; // ID Sistem
            $tugas    = floatval($row[3]);
            $uh       = floatval($row[4]);
            $pts      = floatval($row[5]);
            $pas      = floatval($row[6]);

            // Hitung Rumus Otomatis
            $akhir = ($tugas * $p_tugas / 100) + ($uh * $p_uh / 100) + ($pts * $p_pts / 100) + ($pas * $p_pas / 100);
            $predikat = $this->konversi_predikat($akhir, $kkm);

            // Simpan ke Database
            $data_simpan = [
                'siswa_id' => $id_siswa,
                'mapel_id' => $mapel_id,
                'kelas_id' => $kelas_id,
                'tugas'    => $tugas,
                'uh'       => $uh,
                'pts'      => $pts,
                'pas'      => $pas,
                'akhir'    => $akhir,
                'predikat' => $predikat
            ];

            // Cek Insert atau Update
            $cek = $this->db->table('tbl_nilai')
                ->where(['siswa_id' => $id_siswa, 'mapel_id' => $mapel_id])
                ->countAllResults();

            if ($cek > 0) {
                $this->db->table('tbl_nilai')
                    ->where(['siswa_id' => $id_siswa, 'mapel_id' => $mapel_id])
                    ->update($data_simpan);
            } else {
                $this->db->table('tbl_nilai')->insert($data_simpan);
            }
            $sukses++;
        }

        fclose($handle);
        return redirect()->back()->with('success', "Berhasil mengimport nilai untuk $sukses siswa!");
    }
    public function save_setting()
    {
        $kelas_id = $this->request->getPost('kelas_id');
        $mapel_id = $this->request->getPost('mapel_id');

        // Validasi sederhana
        if(empty($kelas_id) || empty($mapel_id)) {
            return redirect()->back()->with('error', 'Data Kelas/Mapel tidak valid.');
        }

        $data = [
            'mapel_id' => $mapel_id,
            'kelas_id' => $kelas_id,
            'p_tugas'  => $this->request->getPost('p_tugas'),
            'p_uh'     => $this->request->getPost('p_uh'),
            'p_pts'    => $this->request->getPost('p_pts'),
            'p_pas'    => $this->request->getPost('p_pas'),
            'kkm'      => $this->request->getPost('kkm'),
        ];

        // Cek apakah sudah ada setting sebelumnya?
        $cek = $this->db->table('tbl_set_nilai')
            ->where(['mapel_id' => $mapel_id, 'kelas_id' => $kelas_id])
            ->countAllResults();

        if ($cek > 0) {
            // Update
            $this->db->table('tbl_set_nilai')
                ->where(['mapel_id' => $mapel_id, 'kelas_id' => $kelas_id])
                ->update($data);
        } else {
            // Insert Baru
            $this->db->table('tbl_set_nilai')->insert($data);
        }

        return redirect()->back()->with('success', 'Setting Bobot & KKM berhasil disimpan!');
    }
}