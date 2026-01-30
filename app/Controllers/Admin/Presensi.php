<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\WaService; // Library WA yang sudah kita buat sebelumnya

class Presensi extends BaseController
{
    protected $db;
    protected $wa;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->wa = new WaService();
    }

    // 1. TAMPILAN SCANNER (WEB)
    public function scanner()
    {
        return view('admin/presensi/scanner', [
            'title' => 'Scanner Presensi (QR Code)'
        ]);
    }

    // 2. LOGIKA UTAMA (Dipakai Web & IoT)
  public function proses_scan()
    {
        $kode = $this->request->getPost('kode');
        $tipe_input = $this->request->getPost('tipe') ?? 'QR';

        // Siapkan Token Baru
        $newToken = csrf_hash();

        if (empty($kode)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Kode tidak terbaca!',
                'token' => $newToken
            ]);
        }

        $tgl_hari_ini = date('Y-m-d');
        $jam_sekarang = date('H:i:s');

        // A. CEK DATA USER
        $role = '';
        $nama = '';
        $nomorWA = '';
        $userID = 0;

        // 1. Cek di Tabel SISWA
        $siswa = $this->db->table('tbl_siswa')
            ->where('qr_code', $kode)->orWhere('rfid_uid', $kode)->orWhere('nis', $kode)
            ->get()->getRowArray();
        
        if ($siswa) {
            $role = 'siswa';
            $userID = $siswa['id'];
            $nama = $siswa['nama_lengkap'];
            $nomorWA = $siswa['no_hp_siswa'] ?? '';
        } else {
            // 2. Cek di Tabel GURU
            $guru = $this->db->table('tbl_guru')
                ->where('qr_code', $kode)->orWhere('rfid_uid', $kode)->orWhere('nip', $kode) // Cek NIP juga
                ->get()->getRowArray();

            if ($guru) {
                $role = 'guru';
                $userID = $guru['id'];
                
                // --- PERBAIKAN UTAMA DISINI ---
                // Cek variasi nama kolom biar gak error "Undefined key"
                $nama = $guru['nama_guru'] ?? $guru['nama_lengkap'] ?? $guru['nama'] ?? 'Guru';
                // ------------------------------
                
                $nomorWA = $guru['no_hp'] ?? '';
            } else {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Data tidak ditemukan!',
                    'token' => $newToken
                ]);
            }
        }

        // B. AMBIL PENGATURAN JAM
        $jamSetting = $this->db->table('tbl_jam_sekolah')->where('id', 1)->get()->getRow();

        // C. CEK PRESENSI
        $cekAbsen = $this->db->table('tbl_presensi')
            ->where('user_id', $userID)->where('role', $role)->where('tanggal', $tgl_hari_ini)
            ->get()->getRowArray();

        // --- LOGIKA ABSEN ---
        if (!$cekAbsen) {
            // MASUK
            $batas_masuk = $jamSetting->jam_masuk_akhir; // Default jam siswa
            
            // Kalau guru, bisa dikasih dispensasi (Opsional, di sini kita samakan dulu)
            $status = ($jam_sekarang > $batas_masuk) ? 'Terlambat' : 'Tepat Waktu';

            $this->db->table('tbl_presensi')->insert([
                'user_id' => $userID, 
                'role' => $role, 
                'tanggal' => $tgl_hari_ini,
                'jam_masuk' => $jam_sekarang, 
                'status_kehadiran' => ($status == 'Terlambat') ? 'Terlambat' : 'Hadir', 
                'metode' => $tipe_input,
                'status_verifikasi' => 'Disetujui' // Otomatis sah
            ]);

            // Kirim WA (Hanya Siswa)
            if ($role == 'siswa' && !empty($nomorWA)) {
                $this->wa->kirim($nomorWA, "📢 *INFO PRESENSI*\n\nAnak Anda ($nama) telah TIBA di sekolah.\n🕒 $jam_sekarang\nℹ️ Status: *$status*");
            }

            return $this->response->setJSON([
                'status' => 'success', 'tipe' => 'MASUK', 'nama' => $nama, 
                'jam' => $jam_sekarang, 'ket' => $status,
                'token' => $newToken
            ]);

        } else {
            // PULANG
            if ($cekAbsen['jam_pulang'] != null) {
                return $this->response->setJSON([
                    'status' => 'error', 'message' => 'Sudah absen pulang!',
                    'token' => $newToken
                ]);
            }

            $this->db->table('tbl_presensi')->where('id', $cekAbsen['id'])->update(['jam_pulang' => $jam_sekarang]);

            if ($role == 'siswa' && !empty($nomorWA)) {
                $this->wa->kirim($nomorWA, "📢 *INFO KEPULANGAN*\n\nAnak Anda ($nama) telah PULANG dari sekolah.\n🕒 $jam_sekarang");
            }

            return $this->response->setJSON([
                'status' => 'success', 'tipe' => 'PULANG', 'nama' => $nama, 
                'jam' => $jam_sekarang, 'ket' => 'Hati-hati di jalan',
                'token' => $newToken
            ]);
        }
    }
   public function izin()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        // 1. DATA IZIN SISWA
        // Mengambil data presensi siswa yang statusnya Izin/Sakit
        $kolom_kelas = $this->db->fieldExists('kelas_id', 'tbl_siswa') ? 'kelas_id' : 'id_kelas';
        $izinSiswa = $this->db->table('tbl_presensi')
            ->select('tbl_presensi.*, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_presensi.user_id')
            ->join('tbl_kelas', "tbl_kelas.id = tbl_siswa.$kolom_kelas")
            ->where('tbl_presensi.role', 'siswa')
            ->whereIn('tbl_presensi.status_kehadiran', ['Izin', 'Sakit'])
            // Filter tanggal opsional, kalau mau tampilkan semua hapus baris ini
            // ->where('tbl_presensi.tanggal', $tanggal) 
            ->orderBy('tbl_presensi.created_at', 'DESC')
            ->get()->getResultArray();

        // 2. DATA IZIN GURU
        // Mengambil data presensi guru yang statusnya Izin/Sakit/Dinas
        $kolom_nama_guru = $this->db->fieldExists('nama_guru', 'tbl_guru') ? 'nama_guru' : 'nama_lengkap'; // Cek nama kolom
        $izinGuru = $this->db->table('tbl_presensi')
            ->select("tbl_presensi.*, tbl_guru.$kolom_nama_guru as nama_guru, tbl_guru.nip")
            ->join('tbl_guru', 'tbl_guru.id = tbl_presensi.user_id')
            ->where('tbl_presensi.role', 'guru')
            ->whereIn('tbl_presensi.status_kehadiran', ['Izin', 'Sakit', 'Dinas Luar'])
            // ->where('tbl_presensi.tanggal', $tanggal)
            ->orderBy('tbl_presensi.created_at', 'DESC')
            ->get()->getResultArray();

        // Data Kelas untuk Modal Tambah Manual
        $kelas = $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray();

        return view('admin/presensi/izin', [
            'title'     => 'Verifikasi Izin & Sakit',
            'izinSiswa' => $izinSiswa,
            'izinGuru'  => $izinGuru,
            'tanggal'   => $tanggal,
            'kelas'     => $kelas
        ]);
    }

    public function simpan_izin()
    {
        $id_siswa = $this->request->getPost('id_siswa');
        $status   = $this->request->getPost('status'); // Izin atau Sakit
        $ket      = $this->request->getPost('keterangan');
        $tanggal  = $this->request->getPost('tanggal') ?? date('Y-m-d');
        
        // Handle Upload Surat (Opsional)
        $fileSurat = $this->request->getFile('bukti');
        $namaFile  = null;

        if ($fileSurat && $fileSurat->isValid() && !$fileSurat->hasMoved()) {
            $namaFile = $fileSurat->getRandomName();
            $fileSurat->move('uploads/surat_izin', $namaFile);
        }

        // Cek apakah sudah absen hari ini?
        $cek = $this->db->table('tbl_presensi')
            ->where('user_id', $id_siswa)
            ->where('role', 'siswa')
            ->where('tanggal', $tanggal)
            ->countAllResults();

        if ($cek > 0) {
            return redirect()->back()->with('error', 'Siswa ini sudah absen (Hadir/Izin/Sakit) hari ini.');
        }

        // Simpan Data
        $this->db->table('tbl_presensi')->insert([
            'user_id'          => $id_siswa,
            'role'             => 'siswa',
            'tanggal'          => $tanggal,
            'jam_masuk'        => date('H:i:s'), // Jam saat diinput
            'status_kehadiran' => $status,
            'metode'           => 'Manual',
            'keterangan'       => $ket,
            'bukti_izin'       => $namaFile
        ]);
        
        // Kirim Notifikasi WA (Opsional)
        $siswa = $this->db->table('tbl_siswa')->where('id', $id_siswa)->get()->getRow();
        if($siswa && !empty($siswa->no_hp_siswa)) {
            $pesan = "📢 *INFO PRESENSI*\n\n";
            $pesan .= "Status siswa a.n *$siswa->nama_lengkap* hari ini tercatat: *$status*.\n";
            $pesan .= "📝 Ket: $ket\n\n_Terima kasih._";
            $this->wa->kirim($siswa->no_hp_siswa, $pesan);
        }

        return redirect()->back()->with('success', 'Data Izin/Sakit berhasil disimpan.');
    }

    public function hapus_izin($id)
    {
        // Hapus file fisik jika ada
        $data = $this->db->table('tbl_presensi')->where('id', $id)->get()->getRow();
        if ($data && $data->bukti_izin) {
            $path = 'uploads/surat_izin/' . $data->bukti_izin;
            if (file_exists($path)) unlink($path);
        }

        $this->db->table('tbl_presensi')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    // API: Cari Siswa per Kelas (Untuk Dropdown Dinamis)
    // API: Cari Siswa per Kelas (FIXED: Pakai kelas_id)
    public function get_siswa_by_kelas($id_kelas)
    {
        // Langsung pakai 'kelas_id' sesuai info Bos
        $siswa = $this->db->table('tbl_siswa')
            ->select('id, nama_lengkap, nis')
            ->where('kelas_id', $id_kelas) 
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();
            
        // Kirim data JSON ke browser
        return $this->response->setJSON($siswa);
    }
    public function laporan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $id_kelas = $this->request->getGet('id_kelas');

        $builder = $this->db->table('tbl_presensi')
            ->select('tbl_presensi.*, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_presensi.user_id')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id') // Pastikan pakai kelas_id
            ->where('tbl_presensi.role', 'siswa')
            ->like('tbl_presensi.tanggal', $bulan);

        if ($id_kelas) {
            $builder->where('tbl_siswa.kelas_id', $id_kelas);
        }

        $data = $builder->orderBy('tbl_presensi.tanggal', 'DESC')
                        ->orderBy('tbl_kelas.nama_kelas', 'ASC')
                        ->get()->getResultArray();

        $kelas = $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray();

        return view('admin/presensi/laporan', [
            'title' => 'Laporan Kehadiran',
            'data'  => $data,
            'kelas' => $kelas,
            'bulan' => $bulan,
            'filter_kelas' => $id_kelas
        ]);
    }

    public function cetak_harian()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        // Ambil data absen hari ini (Telat, Izin, Sakit, Alpha)
        $data = $this->db->table('tbl_presensi')
            ->select('tbl_presensi.*, tbl_siswa.nama_lengkap, tbl_kelas.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_presensi.user_id')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            ->where('tbl_presensi.role', 'siswa')
            ->where('tbl_presensi.tanggal', $tanggal)
            ->where('tbl_presensi.status_kehadiran !=', 'Hadir') // Hanya yang bermasalah/tidak masuk
            ->orderBy('tbl_kelas.nama_kelas', 'ASC')
            ->get()->getResultArray();

        return view('admin/presensi/cetak_harian', [
            'data' => $data,
            'tanggal' => $tanggal,
            'sekolah' => [
                'nama' => 'SMK DIGITAL INDONESIA', // Sesuaikan
                'alamat' => 'Jl. Pendidikan No. 1'
            ]
        ]);
    }
    // --- FITUR BARU: CETAK REKAP MATRIX BULANAN PER KELAS ---
public function cetak_bulanan()
    {
        // 1. Ambil Filter
        $bulanStr = $this->request->getGet('bulan');
        $id_kelas = $this->request->getGet('id_kelas');

        if (empty($bulanStr) || empty($id_kelas)) {
            return redirect()->back()->with('error', 'Pilih Bulan dan Kelas terlebih dahulu!');
        }

        // 2. Info Kelas & Sekolah
        $kelas = $this->db->table('tbl_kelas')->where('id', $id_kelas)->get()->getRowArray();
        $sekolah = [
            'nama'   => 'SMK DIGITAL INDONESIA',
            'alamat' => 'Jl. Teknologi No. 1'
        ];

        // 3. Ambil Semua Siswa (REVISI: Pakai 'kelas_id')
        $siswa = $this->db->table('tbl_siswa')
            ->where('kelas_id', $id_kelas) // <--- DULU 'id_kelas', SEKARANG 'kelas_id'
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // 4. Ambil Data Absensi (REVISI: Pakai 'kelas_id' saat filter siswa)
        $absensi = $this->db->table('tbl_presensi')
            ->select('tbl_presensi.*, tbl_siswa.id as id_siswa_real')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_presensi.user_id')
            ->where('tbl_presensi.role', 'siswa')
            ->where('tbl_siswa.kelas_id', $id_kelas) // <--- DULU 'id_kelas', SEKARANG 'kelas_id'
            ->like('tbl_presensi.tanggal', $bulanStr)
            ->get()->getResultArray();

        // 5. Mapping Data ke Matrix
        $data_matrix = [];
        foreach ($absensi as $row) {
            $tgl = (int) date('d', strtotime($row['tanggal']));
            $id_s = $row['id_siswa_real'];
            
            // Kode Status Singkat
            $status = 'A';
            if ($row['status_kehadiran'] == 'Hadir') $status = 'H';
            if ($row['status_kehadiran'] == 'Terlambat') $status = 'T';
            if ($row['status_kehadiran'] == 'Sakit') $status = 'S';
            if ($row['status_kehadiran'] == 'Izin') $status = 'I';
            if ($row['status_kehadiran'] == 'Alpha') $status = 'A';

            $data_matrix[$id_s][$tgl] = $status;
        }

        $jml_hari = date('t', strtotime($bulanStr));

        return view('admin/presensi/cetak_matrix', [
            'title'       => 'Rekap Absensi Bulanan',
            'siswa'       => $siswa,
            'matrix'      => $data_matrix,
            'jml_hari'    => $jml_hari,
            'bulan'       => $bulanStr,
            'kelas'       => $kelas,
            'sekolah'     => $sekolah
        ]);
    }
    public function rekap()
    {
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $id_kelas = $this->request->getGet('id_kelas');
        
        // Data Kelas untuk Filter
        $kelas = $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray();
        
        // Jika kelas belum dipilih, jangan load data dulu biar ringan
        if (!$id_kelas) {
            return view('admin/presensi/rekap', [
                'title' => 'Rekapitulasi Bulanan',
                'kelas' => $kelas,
                'bulan' => $bulan,
                'filter_kelas' => '',
                'data_rekap' => []
            ]);
        }

        // 1. Ambil Semua Siswa di Kelas Tersebut
        // Cek nama kolom (kelas_id / id_kelas) seperti biasa
        $kolom_kelas = $this->db->fieldExists('kelas_id', 'tbl_siswa') ? 'kelas_id' : 'id_kelas';
        
        $siswa = $this->db->table('tbl_siswa')
            ->where($kolom_kelas, $id_kelas)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // 2. Ambil Data Presensi Bulan Tersebut
        $presensi = $this->db->table('tbl_presensi')
            ->where('role', 'siswa')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        // 3. Mapping Data (Logic Matrix)
        // Kita ubah data presensi jadi array asosiatif biar gampang dipanggil: $data[id_siswa][tanggal] = 'Hadir'
        $absen_map = [];
        foreach ($presensi as $p) {
            // Ambil tanggalnya saja (misal '2025-01-28' jadi '28')
            $tgl = (int) date('d', strtotime($p['tanggal'])); 
            $absen_map[$p['user_id']][$tgl] = $p['status_kehadiran'];
        }

        // 4. Hitung Statistik per Siswa
        $data_rekap = [];
        $jumlah_hari = date('t', strtotime($bulan)); // Total hari dalam bulan tsb (28/30/31)

        foreach ($siswa as $s) {
            $row = [
                'nama' => $s['nama_lengkap'],
                'nis'  => $s['nis'],
                'harian' => [],
                'total' => ['H'=>0, 'S'=>0, 'I'=>0, 'A'=>0, 'T'=>0],
                'persen' => 0
            ];

            // Loop tanggal 1 s/d Akhir Bulan
            for ($d = 1; $d <= $jumlah_hari; $d++) {
                $status = $absen_map[$s['id']][$d] ?? '-'; // Ambil status, kalau gak ada isi '-'
                $row['harian'][$d] = $status;

                // Hitung Total
                if ($status == 'Hadir') $row['total']['H']++;
                if ($status == 'Terlambat') { $row['total']['T']++; $row['total']['H']++; } // Terlambat dihitung Hadir juga
                if ($status == 'Sakit') $row['total']['S']++;
                if ($status == 'Izin') $row['total']['I']++;
                if ($status == 'Alpha') $row['total']['A']++;
            }

            // Hitung Persentase (Hadir / (Total Hari - Libur))
            // Anggaplah hari kerja efektif = Total Hadir + S + I + A (Simulasi sederhana)
            $total_efektif = $row['total']['H'] + $row['total']['S'] + $row['total']['I'] + $row['total']['A'];
            
            if ($total_efektif > 0) {
                $row['persen'] = round(($row['total']['H'] / $total_efektif) * 100);
            } else {
                $row['persen'] = 0;
            }

            $data_rekap[] = $row;
        }

        return view('admin/presensi/rekap', [
            'title' => 'Rekapitulasi Bulanan',
            'kelas' => $kelas,
            'bulan' => $bulan,
            'filter_kelas' => $id_kelas,
            'data_rekap' => $data_rekap,
            'jml_hari' => $jumlah_hari
        ]);
    }
    public function cetak_rekap()
    {
        $bulan = $this->request->getGet('bulan');
        $id_kelas = $this->request->getGet('id_kelas');

        // Validasi Sederhana
        if (!$bulan || !$id_kelas) {
            return "Silakan pilih Kelas dan Bulan terlebih dahulu.";
        }

        // 1. Ambil Data Kelas & Sekolah
        $kelas = $this->db->table('tbl_kelas')->where('id', $id_kelas)->get()->getRow();
        
        // 2. Ambil Siswa
        $kolom_kelas = $this->db->fieldExists('kelas_id', 'tbl_siswa') ? 'kelas_id' : 'id_kelas';
        $siswa = $this->db->table('tbl_siswa')
            ->where($kolom_kelas, $id_kelas)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // 3. Ambil Presensi
        $presensi = $this->db->table('tbl_presensi')
            ->where('role', 'siswa')
            ->like('tanggal', $bulan)
            ->get()->getResultArray();

        // 4. Mapping Data (Logic Matrix)
        $absen_map = [];
        foreach ($presensi as $p) {
            $tgl = (int) date('d', strtotime($p['tanggal'])); 
            $absen_map[$p['user_id']][$tgl] = $p['status_kehadiran'];
        }

        // 5. Hitung Statistik
        $data_rekap = [];
        $jumlah_hari = date('t', strtotime($bulan)); 

        foreach ($siswa as $s) {
            $row = [
                'nama' => $s['nama_lengkap'],
                'nis'  => $s['nis'],
                'harian' => [],
                'total' => ['H'=>0, 'S'=>0, 'I'=>0, 'A'=>0, 'T'=>0],
                'persen' => 0
            ];

            for ($d = 1; $d <= $jumlah_hari; $d++) {
                $status = $absen_map[$s['id']][$d] ?? '-';
                $row['harian'][$d] = $status;

                if ($status == 'Hadir') $row['total']['H']++;
                if ($status == 'Terlambat') { $row['total']['T']++; $row['total']['H']++; }
                if ($status == 'Sakit') $row['total']['S']++;
                if ($status == 'Izin') $row['total']['I']++;
                if ($status == 'Alpha') $row['total']['A']++;
            }

            $total_efektif = $row['total']['H'] + $row['total']['S'] + $row['total']['I'] + $row['total']['A'];
            if ($total_efektif > 0) {
                $row['persen'] = round(($row['total']['H'] / $total_efektif) * 100);
            }
            $data_rekap[] = $row;
        }

        return view('admin/presensi/cetak_rekap', [
            'data_rekap' => $data_rekap,
            'kelas' => $kelas,
            'bulan' => $bulan,
            'jml_hari' => $jumlah_hari,
            'sekolah' => [
                'nama' => 'SMK DIGITAL INDONESIA', // Sesuaikan
                'alamat' => 'Jl. Teknologi No. 1'
            ]
        ]);
    }
    public function verifikasi($id, $status)
    {
        // $status isinya: 'Disetujui' atau 'Ditolak'
        $this->db->table('tbl_presensi')->where('id', $id)->update([
            'status_verifikasi' => $status
        ]);

        $pesan = ($status == 'Disetujui') ? 'Pengajuan berhasil di-ACC ✅' : 'Pengajuan ditolak ❌';
        return redirect()->back()->with('success', $pesan);
    }
}