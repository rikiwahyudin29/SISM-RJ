<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table            = 'tbl_jadwal';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_tahun_ajaran', 'id_kelas', 'id_mapel', 'id_guru', 'hari', 'jam_mulai', 'jam_selesai'];
    protected $useTimestamps    = true;

    // AMBIL JADWAL DENGAN FILTER
    public function getJadwalFilter($filters = [])
    {
        $builder = $this->select('tbl_jadwal.*, tbl_kelas.nama_kelas, tbl_kelas.id_jurusan, tbl_mapel.nama_mapel, tbl_guru.nama_lengkap as nama_guru, tbl_tahun.tahun_ajaran, tbl_jurusan.nama_jurusan')
                    ->join('tbl_kelas', 'tbl_kelas.id = tbl_jadwal.id_kelas')
                    ->join('tbl_jurusan', 'tbl_jurusan.id = tbl_kelas.id_jurusan', 'left') // Join Jurusan
                    ->join('tbl_mapel', 'tbl_mapel.id = tbl_jadwal.id_mapel')
                    ->join('tbl_guru', 'tbl_guru.id = tbl_jadwal.id_guru')
                    ->join('tbl_tahun_ajaran as tbl_tahun', 'tbl_tahun.id = tbl_jadwal.id_tahun_ajaran');

        // Filter Tahun Ajaran (Wajib)
        if (!empty($filters['id_tahun_ajaran'])) {
            $builder->where('tbl_jadwal.id_tahun_ajaran', $filters['id_tahun_ajaran']);
        }

        // Filter Kelas
        if (!empty($filters['id_kelas'])) {
            $builder->where('tbl_jadwal.id_kelas', $filters['id_kelas']);
        }

        // Filter Guru
        if (!empty($filters['id_guru'])) {
            $builder->where('tbl_jadwal.id_guru', $filters['id_guru']);
        }

        // Filter Jurusan
        if (!empty($filters['id_jurusan'])) {
            $builder->where('tbl_kelas.id_jurusan', $filters['id_jurusan']);
        }

        return $builder->orderBy('FIELD(hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")')
                       ->orderBy('jam_mulai', 'ASC')
                       ->findAll();
    }

    // Logic Anti Bentrok (Tetap Sama)
    public function cekBentrok($id_guru, $id_kelas, $hari, $jam_mulai, $jam_selesai, $id_jadwal_exclude = null)
    {
        // 1. CEK BENTROK GURU
        $builder = $this->where('hari', $hari)->where('id_guru', $id_guru)
                        ->groupStart()->where("'$jam_mulai' < jam_selesai")->where("'$jam_selesai' > jam_mulai")->groupEnd();
        if ($id_jadwal_exclude) $builder->where('id !=', $id_jadwal_exclude);
        if ($builder->countAllResults() > 0) return "GURU TERSEBUT SUDAH ADA JADWAL DI JAM INI!";

        // 2. CEK BENTROK KELAS
        $builder2 = $this->where('hari', $hari)->where('id_kelas', $id_kelas)
                         ->groupStart()->where("'$jam_mulai' < jam_selesai")->where("'$jam_selesai' > jam_mulai")->groupEnd();
        if ($id_jadwal_exclude) $builder2->where('id !=', $id_jadwal_exclude);
        if ($builder2->countAllResults() > 0) return "KELAS INI SEDANG DIPAKAI PELAJARAN LAIN!";

        return "AMAN"; 
    }
}