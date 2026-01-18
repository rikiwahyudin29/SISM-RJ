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
            try {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($file->getTempName());
                $dataExcel = $spreadsheet->getActiveSheet()->toArray();

                $userModel  = new \App\Models\UserModel();
                $siswaModel = new \App\Models\SiswaModel();
                $db         = \Config\Database::connect();

                $countSuccess = 0;

                foreach ($dataExcel as $key => $row) {
                    if ($key == 0) continue; // Skip Header

                    // 1. Mapping Variabel (Pastikan urutan sesuai Template Excel)
                    $nisn        = $row[0]; 
                    $nis         = $row[1]; 
                    $nama        = $row[2]; 
                    $kelas       = $row[3]; 
                    $jurusan     = $row[4]; 
                    $jk          = $row[5]; 
                    $tempat      = $row[6]; 
                    $tgl         = $row[7]; 
                    $hp          = $row[8]; 
                    $ayah        = $row[9]; 
                    $ibu         = $row[10];
                    $hp_ortu     = $row[11];
                    $telegram_id = $row[12] ?? null;

                    // Validasi Dasar: Kalau NISN/Nama kosong, skip
                    if(empty($nisn) || empty($nama)) continue;

                    // Mulai Transaksi Per Baris
                    $db->transStart();

                    // 2. Cek apakah DATA SISWA sudah ada? (Kalau sudah ada, skip biar gak dobel)
                    if ($siswaModel->where('nisn', $nisn)->first()) {
                        $db->transRollback(); 
                        continue; 
                    }

                    // 3. CEK USER (TERMASUK YANG SOFT DELETED / ZOMBIE)
                    // Kita pakai Query Builder langsung biar bypass filter soft delete Model
                    $existingUser = $db->table('tbl_users')->where('username', $nisn)->get()->getRowArray();
                    
                    $userId = null;

                    if ($existingUser) {
                        // --- SKENARIO 1: USER SUDAH ADA (Update & Restore) ---
                        $userId = $existingUser['id'];
                        
                        $db->table('tbl_users')->where('id', $userId)->update([
                            'nama_lengkap'     => $nama,
                            'email'            => $nisn . '@student.sch.id',
                            'telegram_chat_id' => $telegram_id,
                            'is_active'        => 1,
                            'deleted_at'       => null // <--- INI KUNCINYA! Hidupkan user mati
                        ]);

                    } else {
                        // --- SKENARIO 2: USER BELUM ADA (Insert Baru) ---
                        $userData = [
                            'username'         => $nisn,
                            'password'         => password_hash((string)$nisn, PASSWORD_DEFAULT),
                            'nama_lengkap'     => $nama,
                            'email'            => $nisn . '@student.sch.id',
                            'nomor_wa'         => $hp, 
                            'telegram_chat_id' => $telegram_id,
                            'created_at'       => date('Y-m-d H:i:s'),
                            'updated_at'       => date('Y-m-d H:i:s')
                        ];
                        $userModel->insert($userData);
                        $userId = $userModel->getInsertID();
                    }

                    // 4. Pastikan Punya Role Siswa (ID 11)
                    // Cek dulu biar gak error duplicate entry
                    $cekRole = $db->table('user_roles')
                                  ->where('user_id', $userId)
                                  ->where('role_id', 11)
                                  ->countAllResults();
                                  
                    if ($cekRole == 0) {
                        $db->table('user_roles')->insert([
                            'user_id' => $userId,
                            'role_id' => 11
                        ]);
                    }

                    // 5. Akhirnya Simpan Data Siswa
                    $siswaData = [
                        'user_id'       => $userId,
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
                    
                    $db->transComplete(); // Selesai satu baris
                    
                    if ($db->transStatus()) {
                        $countSuccess++;
                    }
                }

                return redirect()->back()->with('success', "Berhasil mengimport $countSuccess data siswa!");

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error System: ' . $e->getMessage());
            }
        }
        return redirect()->back()->with('error', 'File tidak valid atau kosong.');
    }
}