<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Leger extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $kelas_id = $this->request->getGet('kelas_id');
        
        $data = [
            'title' => 'Leger & Ranking Nilai',
            'kelas' => $this->db->table('tbl_kelas')->get()->getResultArray(),
            'kelas_pilih' => null,
            'mapel' => [],
            'leger' => []
        ];

        if ($kelas_id) {
            // 1. Ambil Info Kelas
            $data['kelas_pilih'] = $this->db->table('tbl_kelas')->where('id', $kelas_id)->get()->getRow();

            // 2. FILTER MAPEL (Perbaikan Nama Kolom)
            $data['mapel'] = $this->db->table('tbl_mapel')
                ->select('tbl_mapel.id, tbl_mapel.nama_mapel, tbl_mapel.kode_mapel')
                ->groupStart()
                    ->whereIn('tbl_mapel.id', function($builder) use ($kelas_id) {
                        // --- PERBAIKAN DISINI ---
                        // Cek apakah di tabel jadwal nama kolomnya 'id_mapel' atau 'mapel_id'
                        // Biasanya tabel jadwal pakai 'id_mapel'
                        return $builder->select('id_mapel')->from('tbl_jadwal')->where('id_kelas', $kelas_id); 
                        // Note: Cek juga 'id_kelas' atau 'kelas_id' di tabel jadwal
                    })
                    ->orWhereIn('tbl_mapel.id', function($builder) use ($kelas_id) {
                        // Kalau tabel nilai kita yang buat tadi pakai 'mapel_id'
                        return $builder->select('mapel_id')->from('tbl_nilai')->where('kelas_id', $kelas_id);
                    })
                ->groupEnd()
                ->orderBy('tbl_mapel.nama_mapel', 'ASC')
                ->get()->getResultArray();

            // 3. Ambil Siswa
            $siswa = $this->db->table('tbl_siswa')
                ->select('id, nama_lengkap, nis')
                ->where('kelas_id', $kelas_id) // Pastikan ini sesuai (kelas_id / id_kelas)
                ->orderBy('nama_lengkap', 'ASC')
                ->get()->getResultArray();

            // 4. Ambil NILAI
            $nilai_raw = $this->db->table('tbl_nilai')
                ->where('kelas_id', $kelas_id)
                ->get()->getResultArray();

            // 5. Mapping Nilai
            $nilai_map = [];
            foreach ($nilai_raw as $n) {
                $nilai_map[$n['siswa_id']][$n['mapel_id']] = $n['akhir'];
            }

            // 6. Susun Data Leger & Hitung Rata-rata
            $leger_final = [];
            foreach ($siswa as $s) {
                $row = [
                    'id'    => $s['id'],
                    'nama'  => $s['nama_lengkap'],
                    'nis'   => $s['nis'],
                    'nilai' => [], 
                    'total' => 0,
                    'rata'  => 0
                ];

                $count_mapel = 0;
                foreach ($data['mapel'] as $m) {
                    $score = $nilai_map[$s['id']][$m['id']] ?? 0;
                    $row['nilai'][$m['id']] = $score;
                    
                    $row['total'] += $score;
                }

                $jumlah_mapel = count($data['mapel']);
                $row['rata'] = ($jumlah_mapel > 0) ? round($row['total'] / $jumlah_mapel, 2) : 0;

                $leger_final[] = $row;
            }

            // 7. LOGIKA RANKING
            usort($leger_final, function ($a, $b) {
                return $b['rata'] <=> $a['rata'];
            });

            $rank = 1;
            foreach ($leger_final as &$lf) {
                $lf['ranking'] = $rank++;
            }

            $data['leger'] = $leger_final;
        }

        return view('guru/leger/index', $data);
    }
    
    // Fitur Cetak (Print Friendly)
   public function cetak()
    {
        $kelas_id = $this->request->getGet('kelas_id');
        if (!$kelas_id) return redirect()->to('guru/leger');

        // 1. Ambil Data Kelas
        $kelas = $this->db->table('tbl_kelas')->where('id', $kelas_id)->get()->getRow();
        
        // 2. Ambil Mapel (Logic Filter Jadwal yang sudah kita perbaiki tadi)
        $mapel = $this->db->table('tbl_mapel')
            ->select('tbl_mapel.id, tbl_mapel.nama_mapel, tbl_mapel.kode_mapel')
            ->groupStart()
                ->whereIn('tbl_mapel.id', function($builder) use ($kelas_id) {
                    return $builder->select('id_mapel')->from('tbl_jadwal')->where('id_kelas', $kelas_id);
                })
                ->orWhereIn('tbl_mapel.id', function($builder) use ($kelas_id) {
                    return $builder->select('mapel_id')->from('tbl_nilai')->where('kelas_id', $kelas_id);
                })
            ->groupEnd()
            ->orderBy('tbl_mapel.nama_mapel', 'ASC')
            ->get()->getResultArray();

        // 3. Ambil Siswa
        $siswa = $this->db->table('tbl_siswa')
            ->select('id, nama_lengkap, nis')
            ->where('kelas_id', $kelas_id) // Pastikan nama kolom 'kelas_id' atau 'id_kelas' sesuai DB
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // 4. Ambil NILAI
        $nilai_raw = $this->db->table('tbl_nilai')->where('kelas_id', $kelas_id)->get()->getResultArray();

        // 5. Mapping & Hitung (Sama persis dengan index)
        $nilai_map = [];
        foreach ($nilai_raw as $n) {
            $nilai_map[$n['siswa_id']][$n['mapel_id']] = $n['akhir'];
        }

        $leger_final = [];
        foreach ($siswa as $s) {
            $row = [
                'nama'  => $s['nama_lengkap'],
                'nis'   => $s['nis'],
                'nilai' => [],
                'total' => 0,
                'rata'  => 0
            ];

            foreach ($mapel as $m) {
                $score = $nilai_map[$s['id']][$m['id']] ?? 0;
                $row['nilai'][$m['id']] = $score;
                $row['total'] += $score;
            }

            $jumlah_mapel = count($mapel);
            $row['rata'] = ($jumlah_mapel > 0) ? round($row['total'] / $jumlah_mapel, 2) : 0;
            $leger_final[] = $row;
        }

        // Sort Ranking
        usort($leger_final, function ($a, $b) {
            return $b['rata'] <=> $a['rata'];
        });

        // Tambahkan Ranking
        $rank = 1;
        foreach ($leger_final as &$lf) {
            $lf['ranking'] = $rank++;
        }

        // 6. Ambil Data Tambahan (Wali Kelas & Sekolah)
        // Asumsi ada tabel tbl_sekolah, jika belum ada pakai placeholder dulu
        $sekolah = [
            'nama_sekolah' => 'SMK DIGITAL INDONESIA',
            'alamat'       => 'Jl. Teknologi No. 1, Jakarta',
            'kepsek'       => 'Dr. H. Ahmad Dahlan, M.Pd', // Ganti dengan data dinamis jika ada
            'nip_kepsek'   => '19800101 200501 1 001'
        ];

        // Ambil Nama Wali Kelas (Jika ada relasi di tbl_kelas)
        // $wali = $this->db->table('tbl_guru')->where('id', $kelas->wali_kelas_id)->get()->getRow();
        $nama_wali = "Nama Wali Kelas"; // Placeholder

        return view('guru/leger/cetak', [
            'kelas'   => $kelas,
            'mapel'   => $mapel,
            'leger'   => $leger_final,
            'sekolah' => $sekolah,
            'wali'    => $nama_wali,
            'tanggal' => date('d F Y')
        ]);
    }
}