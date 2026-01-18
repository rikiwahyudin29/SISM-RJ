<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\JurusanModel;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $userModel;
    protected $kelasModel;
    protected $jurusanModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->userModel = new UserModel();
        $this->kelasModel = new KelasModel();
        $this->jurusanModel = new JurusanModel();
    }

    public function index()
    {
        // JOIN Table: Siswa + User (Telegram) + Kelas + Jurusan
        $siswa = $this->siswaModel
            ->select('tbl_siswa.*, tbl_users.telegram_chat_id, tbl_kelas.nama_kelas, tbl_jurusan.nama_jurusan')
            ->join('tbl_users', 'tbl_users.id = tbl_siswa.user_id', 'left')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id', 'left')
            ->join('tbl_jurusan', 'tbl_jurusan.id = tbl_siswa.jurusan_id', 'left')
            ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
            ->findAll();

        $data = [
            'title'   => 'Data Peserta Didik',
            'siswa'   => $siswa,
            'kelas'   => $this->kelasModel->findAll(),   // Untuk Dropdown di Modal
            'jurusan' => $this->jurusanModel->findAll()  // Untuk Dropdown di Modal
        ];

        return view('admin/siswa/index', $data);
    }

   public function simpan()
    {
        // 1. Tangkap Inputan
        $id = $this->request->getVar('id');
        $nisn = trim($this->request->getVar('nisn')); 
        $nama = strtoupper(trim($this->request->getVar('nama_lengkap'))); 
        $telegram_id = $this->request->getVar('telegram_chat_id');
        $email = $this->request->getVar('email_siswa');
        $hp_siswa = $this->request->getVar('no_hp_siswa');

        // Validasi Sederhana
        if (empty($nisn) || empty($nama)) {
            return redirect()->back()->withInput()->with('error', 'NISN dan Nama Wajib Diisi!');
        }

        // Siapkan Data Siswa
        $dataSiswa = [
            'nisn'           => $nisn,
            'nis'            => $this->request->getVar('nis'),
            'nama_lengkap'   => $nama,
            'kelas_id'       => $this->request->getVar('kelas_id'),
            'jurusan_id'     => $this->request->getVar('jurusan_id'),
            'jenis_kelamin'  => $this->request->getVar('jenis_kelamin'),
            'tempat_lahir'   => $this->request->getVar('tempat_lahir'),
            'tanggal_lahir'  => $this->request->getVar('tanggal_lahir'),
            'agama'          => $this->request->getVar('agama'),
            'alamat'         => $this->request->getVar('alamat'),
            'no_hp_siswa'    => $hp_siswa,
            'email_siswa'    => $email,
            'nama_ayah'      => $this->request->getVar('nama_ayah'),
            'nama_ibu'       => $this->request->getVar('nama_ibu'),
            'no_hp_ortu'     => $this->request->getVar('no_hp_ortu'),
            'status_siswa'   => $this->request->getVar('status_siswa'),
        ];

        // Upload Foto
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/siswa', $namaFoto);
            $dataSiswa['foto'] = $namaFoto;
        }

        $db = \Config\Database::connect();
        
        try {
            $db->transException(true)->transStart();

            if (empty($id)) {
                // === MODE TAMBAH BARU ===

                // 1. Cek User (Berdasarkan Username/NISN)
                $existingUser = $db->table('tbl_users')->where('username', $nisn)->get()->getRowArray();
                
                $userId = null;

                if ($existingUser) {
                    // SKENARIO 1: USER SUDAH ADA -> UPDATE DATA NYA
                    $userId = $existingUser['id'];
                    $db->table('tbl_users')->where('id', $userId)->update([
                        'nama_lengkap'     => $nama,
                        'password'         => password_hash((string)$nisn, PASSWORD_DEFAULT),
                        'role'             => 'siswa', // Isi kolom role enum
                        'updated_at'       => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // SKENARIO 2: USER BARU -> INSERT
                    $db->table('tbl_users')->insert([
                        'username'         => $nisn,
                        'email'            => $email ?? $nisn . '@student.sch.id',
                        'password'         => password_hash((string)$nisn, PASSWORD_DEFAULT),
                        'nama_lengkap'     => $nama,
                        'nomor_wa'         => $hp_siswa,
                        'telegram_chat_id' => $telegram_id,
                        'role'             => 'siswa', // Isi kolom role enum
                        'created_at'       => date('Y-m-d H:i:s'),
                        // Hapus 'active' & 'updated_at' (biar default NULL dulu gpp)
                    ]);
                    $userId = $db->insertID();
                }

                // 2. Pastikan Role di Tabel Relasi (ID 11)
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

                // 3. Simpan ke Tabel Siswa
                $cekSiswa = $db->table('tbl_siswa')->where('nisn', $nisn)->countAllResults();
                
                $dataSiswa['user_id'] = $userId;
                if(!isset($dataSiswa['foto'])) $dataSiswa['foto'] = 'default.png';

                if ($cekSiswa > 0) {
                     // Update Zombie Siswa (Overwrite data lama)
                     $db->table('tbl_siswa')->where('nisn', $nisn)->update($dataSiswa);
                } else {
                     // Insert Baru
                     $dataSiswa['created_at'] = date('Y-m-d H:i:s');
                     $dataSiswa['updated_at'] = date('Y-m-d H:i:s');
                     $db->table('tbl_siswa')->insert($dataSiswa);
                }

                $msg = 'Siswa berhasil ditambahkan!';

            } else {
                // === MODE EDIT DATA ===
                $siswaLama = $this->siswaModel->find($id);
                $this->siswaModel->update($id, $dataSiswa);

                // Update User Terkait
                if (!empty($siswaLama['user_id'])) {
                    $dataUserUpdate = [
                        'nama_lengkap'     => $nama,
                        'nomor_wa'         => $hp_siswa,
                        'telegram_chat_id' => $telegram_id
                    ];
                    
                    if($siswaLama['nisn'] != $nisn) {
                         $cekTabrakan = $db->table('tbl_users')->where('username', $nisn)->countAllResults();
                         if($cekTabrakan == 0) {
                             $dataUserUpdate['username'] = $nisn;
                         }
                    }
                    $this->userModel->update($siswaLama['user_id'], $dataUserUpdate);
                }
                $msg = 'Data siswa berhasil diperbarui!';
            }

            $db->transComplete();
            
            return redirect()->to(base_url('admin/siswa'))->with('success', $msg);

        } catch (\DatabaseException $e) {
            return redirect()->back()->withInput()->with('error', 'Database Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'System Error: ' . $e->getMessage());
        }
    }

    public function delete_siswa($id)
    {
        $siswa = $this->siswaModel->find($id);
        if ($siswa) {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $this->siswaModel->delete($id);
            if (!empty($siswa['user_id'])) {
                $this->userModel->delete($siswa['user_id']); // Hapus Akun Login
                
                // Hapus Role juga (jika database tidak cascade)
                $db->table('user_roles')->where('user_id', $siswa['user_id'])->delete();
            }
            
            $db->transComplete();
            return redirect()->to(base_url('admin/siswa'))->with('success', 'Data Siswa & Akun Login dihapus.');
        }
        return redirect()->to(base_url('admin/siswa'))->with('error', 'Data tidak ditemukan.');
    }

    // --- FITUR HAPUS SEMUA SISWA ---
    public function hapus_semua()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Ambil semua ID User yang terhubung dengan siswa
        $allSiswa = $this->siswaModel->findAll();
        
        if (empty($allSiswa)) {
            return redirect()->to(base_url('admin/siswa'))->with('error', 'Tidak ada data siswa untuk dihapus.');
        }

        $userIds = array_column($allSiswa, 'user_id');
        
        // 2. Hapus Semua Data di Tabel Siswa
        $this->siswaModel->truncate(); // Truncate lebih bersih daripada emptyTable

        // 3. Hapus Semua Akun User Terkait
        if (!empty($userIds)) {
            $this->userModel->whereIn('id', $userIds)->delete();
            
            // Hapus relasi role user tersebut
            $db->table('user_roles')->whereIn('user_id', $userIds)->delete();
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->to(base_url('admin/siswa'))->with('error', 'Gagal menghapus semua data.');
        }

        return redirect()->to(base_url('admin/siswa'))->with('success', 'SEMUA Data Siswa & Akun Login berhasil dihapus bersih!');
    }

    // --- FITUR DOWNLOAD TEMPLATE EXCEL ---
    public function download_template_siswa()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        // --- SHEET 1: FORM INPUT SISWA ---
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Form Input Siswa');

        // Header Kolom (DITAMBAH KOLOM M UNTUK TELEGRAM)
        $headers = [
            'A1' => 'NISN (Wajib/Username)',
            'B1' => 'NIS LOKAL',
            'C1' => 'NAMA LENGKAP',
            'D1' => 'ID KELAS (Lihat Sheet 2)',
            'E1' => 'ID JURUSAN (Lihat Sheet 2)',
            'F1' => 'JENIS KELAMIN (L/P)',
            'G1' => 'TEMPAT LAHIR',
            'H1' => 'TANGGAL LAHIR (YYYY-MM-DD)',
            'I1' => 'NO HP SISWA',
            'J1' => 'NAMA AYAH',
            'K1' => 'NAMA IBU',
            'L1' => 'NO HP ORTU (WA)',
            'M1' => 'TELEGRAM CHAT ID (Untuk OTP)' 
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getColumnDimension(substr($cell, 0, 1))->setAutoSize(true);
            
            // Kasih warna kuning di header biar jelas
            $sheet->getStyle($cell)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFFFFF00');
        }

        // Contoh Data (Dummy)
        $sheet->setCellValue('A2', '0054321987');
        $sheet->setCellValue('B2', '2023001');
        $sheet->setCellValue('C2', 'Ahmad Siswa Teladan');
        $sheet->setCellValue('D2', '1'); // ID Kelas
        $sheet->setCellValue('E2', '1'); // ID Jurusan
        $sheet->setCellValue('F2', 'L');
        $sheet->setCellValue('G2', 'Subang');
        $sheet->setCellValue('H2', '2008-05-20');
        $sheet->setCellValue('I2', '08123456789');
        $sheet->setCellValue('J2', 'Budi Ayah');
        $sheet->setCellValue('K2', 'Siti Ibu');
        $sheet->setCellValue('L2', '08139876543');
        $sheet->setCellValue('M2', '123456789'); // Contoh Telegram ID

        // --- SHEET 2: DATA REFERENSI (CONTEKAN ID) ---
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1);
        $sheet2->setTitle('Kode Referensi');

        // Header Referensi
        $sheet2->setCellValue('A1', 'ID KELAS');
        $sheet2->setCellValue('B1', 'NAMA KELAS');
        $sheet2->setCellValue('D1', 'ID JURUSAN');
        $sheet2->setCellValue('E1', 'NAMA JURUSAN');
        $sheet2->getStyle('A1:E1')->getFont()->setBold(true);

        // Ambil Data Kelas & Jurusan dari Database
        $dataKelas = $this->kelasModel->findAll();
        $dataJurusan = $this->jurusanModel->findAll();

        // Loop Data Kelas
        $row = 2;
        foreach ($dataKelas as $k) {
            $sheet2->setCellValue('A' . $row, $k['id']);
            $sheet2->setCellValue('B' . $row, $k['nama_kelas']);
            $row++;
        }

        // Loop Data Jurusan
        $row = 2;
        foreach ($dataJurusan as $j) {
            $sheet2->setCellValue('D' . $row, $j['id']);
            $sheet2->setCellValue('E' . $row, $j['nama_jurusan']);
            $row++;
        }

        // Kembali ke Sheet 1
        $spreadsheet->setActiveSheetIndex(0);

        // Output File
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Import_Siswa_Lengkap.xlsx"');
        $writer->save('php://output');
        exit;
    }
}