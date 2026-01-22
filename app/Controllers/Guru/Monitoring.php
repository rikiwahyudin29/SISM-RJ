<?php
namespace App\Controllers\Guru;
use App\Controllers\BaseController;

class Monitoring extends BaseController
{
    protected $db;
    public function __construct() { $this->db = \Config\Database::connect(); }

    // 1. HALAMAN PILIH RUANG/JADWAL
    public function index()
    {
        // Tampilkan daftar jadwal yang SEDANG AKTIF saja
        $jadwalAktif = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_bank_soal.judul_ujian, tbl_mapel.nama_mapel')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->where('status_ujian', 'AKTIF')
            ->orderBy('waktu_mulai', 'DESC')
            ->get()->getResultArray();

        return view('guru/monitoring/index', ['jadwal' => $jadwalAktif]);
    }

    // 2. LIVE VIEW RUANGAN (Data Siswa)
    public function ruang($idJadwal)
    {
        // Ambil Data Peserta di Jadwal ini
        // Logic: Ambil semua siswa yg ada di kelas yg ditargetkan jadwal ini
        // Lalu Left Join ke tabel ujian_siswa buat liat statusnya
        
        $peserta = $this->db->query("
            SELECT 
                s.id as id_siswa, s.nama_lengkap, s.nomor_peserta, k.nama_kelas,
                u.id as id_ujian_siswa, u.status, u.nilai, u.waktu_mulai, u.is_blocked,
                u.sisa_waktu_tambahan
            FROM tbl_jadwal_kelas jk
            JOIN tbl_siswa s ON s.id_kelas = jk.id_kelas
            JOIN tbl_kelas k ON k.id = s.id_kelas
            LEFT JOIN tbl_ujian_siswa u ON u.id_siswa = s.id AND u.id_jadwal = jk.id_jadwal_ujian
            WHERE jk.id_jadwal_ujian = ?
            ORDER BY k.nama_kelas ASC, s.nama_lengkap ASC
        ", [$idJadwal])->getResultArray();

        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $idJadwal)->get()->getRow();

        return view('guru/monitoring/ruang', [
            'peserta' => $peserta,
            'jadwal' => $jadwal
        ]);
    }

    // 3. EKSEKUSI MASSAL (API)
    public function aksi()
    {
        $aksi = $this->request->getPost('aksi'); // reset, stop, add_time, unlock
        $ids  = $this->request->getPost('ids'); // Array ID Ujian Siswa
        $menit = $this->request->getPost('menit'); // Untuk tambah waktu

        if(empty($ids)) return $this->response->setJSON(['status'=>'error', 'msg'=>'Pilih siswa dulu!']);

        $builder = $this->db->table('tbl_ujian_siswa')->whereIn('id', $ids);

        switch ($aksi) {
            case 'reset':
                // Hapus sesi biar siswa bisa mulai dari awal (soal baru)
                $builder->delete(); 
                // Opsional: Hapus jawaban juga jika perlu bersih total
                // $this->db->table('tbl_jawaban_siswa')->whereIn('id_ujian_siswa', $ids)->delete();
                break;

            case 'stop':
                // Paksa Selesai
                $builder->update(['status' => 1, 'waktu_submit' => date('Y-m-d H:i:s')]);
                break;

            case 'unlock':
                // Buka Blokir
                $builder->update(['is_blocked' => 0]);
                break;

            case 'add_time':
                // Tambah Waktu (Custom Logic di view kerjakan harus support ini)
                // Kita update kolom 'waktu_selesai_seharusnya'
                // Ini agak tricky karena harus loop update one by one idealnya, 
                // atau pakai raw query DATE_ADD
                foreach($ids as $id) {
                    $this->db->query("UPDATE tbl_ujian_siswa SET waktu_selesai_seharusnya = DATE_ADD(waktu_selesai_seharusnya, INTERVAL ? MINUTE) WHERE id = ?", [$menit, $id]);
                }
                break;
        }

        return $this->response->setJSON(['status'=>'success']);
    }
}