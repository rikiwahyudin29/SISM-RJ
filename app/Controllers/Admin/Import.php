<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; // Library yang baru diinstal
use App\Models\UserModel;
use App\Models\GuruModel;

class Import extends BaseController {
    
    public function kelas() {
        $file = $this->request->getFile('file_excel');
        
        // Validasi file agar tidak error
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file->getTempName());
            $dataExcel = $spreadsheet->getActiveSheet()->toArray();

            $db = \Config\Database::connect();
            foreach ($dataExcel as $key => $row) {
                if ($key == 0) continue; // Lewati baris judul (Header)

                $db->table('tbl_kelas')->insert([
                    'nama_kelas' => $row[0], // Kolom A
                    'id_jurusan' => $row[1], // Kolom B
                    'guru_id'    => $row[2]  // Kolom C
                ]);
            }
            return redirect()->back()->with('success', 'Data Kelas Massal Berhasil Diimport! 🚀');
        }
    }
    public function guru()
{
    $file = $this->request->getFile('file_excel');

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file->getTempName());
        $dataExcel = $spreadsheet->getActiveSheet()->toArray();

        $userModel = new \App\Models\UserModel();
        $guruModel = new \App\Models\GuruModel();
        $db = \Config\Database::connect();

        $db->transStart();

        foreach ($dataExcel as $key => $row) {
            if ($key == 0) continue; // Skip Header

            // Mapping Excel (Sesuaikan urutan template Bos)
            $nip  = $row[0]; // Kolom A
            $nama = $row[1]; // Kolom B
            $jk   = $row[2]; // Kolom C
            $hp   = $row[3] ?? '-'; // Kolom D
            $pendidikan = $row[4] ?? '-'; // Kolom E
            $status = $row[5] ?? 'GTY'; // Kolom F

            // Cek NIP agar tidak duplikat
            if($guruModel->where('nip', $nip)->first()) continue; 

            // [FIXED] Insert ke tbl_users (LENGKAP)
            $userData = [
                'username'      => $nip,
                'email'         => $nip . '@sekolah.id',
                'password'      => password_hash((string)$nip, PASSWORD_DEFAULT), // FIX: Kolom 'password'
                'nama_lengkap'  => $nama, // FIX: Nama masuk
                'nomor_wa'      => $hp,   // FIX: No HP masuk
                'role'          => 'guru',
                'active'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            $userModel->insert($userData);
            $newUserId = $userModel->getInsertID();

            // Insert ke tbl_guru
            $guruData = [
                'user_id'       => $newUserId,
                'nip'           => $nip,
                'nama_lengkap'  => $nama,
                'jenis_kelamin' => $jk,
                'no_hp'         => $hp,
                'pendidikan_terakhir' => $pendidikan,
                'status_guru'   => $status,
                'foto'          => 'default.png',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            $guruModel->insert($guruData);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal Import Data Guru!');
        } else {
            return redirect()->back()->with('success', 'Import Berhasil! Akun login guru sudah dibuat.');
        }
    } else {
        return redirect()->back()->with('error', 'File tidak valid!');
    }
}
public function siswa()
{
    $file = $this->request->getFile('file_excel');

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file->getTempName());
        $dataExcel = $spreadsheet->getActiveSheet()->toArray();

        $userModel = new \App\Models\UserModel(); // Pastikan use Model di atas
        $siswaModel = new \App\Models\SiswaModel();
        $db = \Config\Database::connect();

        $db->transStart(); // Mulai Transaksi Database

        foreach ($dataExcel as $key => $row) {
            if ($key == 0) continue; // Skip Header

            // Mapping Kolom Excel
            $nisn        = $row[0]; // A
            $nis         = $row[1]; // B
            $nama        = $row[2]; // C
            $kelas       = $row[3]; // D
            $jurusan     = $row[4]; // E
            $jk          = $row[5]; // F
            $tempat      = $row[6]; // G
            $tgl         = $row[7]; // H
            $hp          = $row[8]; // I
            $ayah        = $row[9]; // J
            $ibu         = $row[10];// K
            $hp_ortu     = $row[11];// L
            $telegram_id = $row[12] ?? null; // M - TELEGRAM ID (Ambil dari kolom ke-13)

            // 1. Validasi: Cek NISN Duplikat di Siswa
            if ($siswaModel->where('nisn', $nisn)->first()) continue;

            // 2. Buat Akun Login (tbl_users)
            $userData = [
                'username'         => $nisn,
                'password'         => password_hash((string)$nisn, PASSWORD_DEFAULT),
                'nama_lengkap'     => $nama,
                'email'            => $nisn . '@student.sch.id',
                'nomor_wa'         => $hp, 
                'telegram_chat_id' => $telegram_id, // <--- MASUK KE DATABASE USER
                'role'             => 'siswa',
                'active'           => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s')
            ];
            $userModel->insert($userData);
            $newUserId = $userModel->getInsertID();

            // 3. Buat Data Siswa (tbl_siswa)
            $siswaData = [
                'user_id'       => $newUserId, // Relasi ke User
                'nisn'          => $nisn,
                'nis'           => $nis,
                'nama_lengkap'  => $nama,
                'kelas_id'      => $kelas,
                'jurusan_id'    => $jurusan,
                'jenis_kelamin' => $jk,
                'tempat_lahir'  => $tempat,
                'tanggal_lahir' => $tgl,
                'no_hp_siswa'   => $hp,
                'nama_ayah'     => $ayah,
                'nama_ibu'      => $ibu,
                'no_hp_ortu'    => $hp_ortu,
                'status_siswa'  => 'Aktif',
                'foto'          => 'default.png',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            $siswaModel->insert($siswaData);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal import siswa. Terjadi kesalahan sistem.');
        } else {
            return redirect()->back()->with('success', 'Data Siswa & Akun Login (termasuk Telegram ID) berhasil diimport!');
        }
    }
    return redirect()->back()->with('error', 'File tidak valid.');
}
}