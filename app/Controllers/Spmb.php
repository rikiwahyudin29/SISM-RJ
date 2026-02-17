<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\WaService; // Load Library

class Spmb extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function register()
    {
        // Ambil Data Jurusan dari DB
        $jurusan = $this->db->table('tbl_jurusan')->get()->getResultArray();

        $data = [
            'web'     => $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray(),
            'jurusan' => $jurusan 
        ];
        return view('spmb/register', $data);
    }

    public function save()
    {
        // 1. Generate No Pendaftaran
        $tahun = date('Y');
        $last = $this->db->table('tbl_pendaftar')->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        $nomor = 1;
        
        if($last) {
            $lastNo = explode('-', $last['no_pendaftaran']);
            if(isset($lastNo[2])) {
                $nomor = (int)$lastNo[2] + 1;
            }
        }
        $no_pendaftaran = 'REG-' . $tahun . '-' . str_pad($nomor, 4, '0', STR_PAD_LEFT);

        // 2. Upload Berkas (Foto, KK, Ijazah)
        $namaFoto = $this->uploadFile('foto', 'default.png');
        $namaKK = $this->uploadFile('berkas_kk', null);
        $namaIjazah = $this->uploadFile('berkas_ijazah', null);

        // 3. Tangkap Semua Data Sesuai tbl_pendaftar
        $data = [
            'no_pendaftaran' => $no_pendaftaran,
            'nama_lengkap'   => strtoupper($this->request->getPost('nama_lengkap')),
            'nisn'           => $this->request->getPost('nisn'),
            'nik'            => $this->request->getPost('nik'),
            'jk'             => $this->request->getPost('jk'),
            'tempat_lahir'   => strtoupper($this->request->getPost('tempat_lahir')),
            'tgl_lahir'      => $this->request->getPost('tgl_lahir'),
            'agama'          => $this->request->getPost('agama'),
            
            // Alamat Lengkap
            'alamat_jalan'   => strtoupper($this->request->getPost('alamat_jalan')),
            'rt_rw'          => $this->request->getPost('rt_rw'),
            'desa_kelurahan' => strtoupper($this->request->getPost('desa_kelurahan')),
            'kecamatan'      => strtoupper($this->request->getPost('kecamatan')),
            'kabupaten'      => strtoupper($this->request->getPost('kabupaten')),
            'provinsi'       => strtoupper($this->request->getPost('provinsi')),
            'kode_pos'       => $this->request->getPost('kode_pos'),

            // Kontak & Sekolah
            'asal_sekolah'   => strtoupper($this->request->getPost('asal_sekolah')),
            'jurusan_minat'  => $this->request->getPost('jurusan_minat'),
            'no_hp_siswa'    => $this->request->getPost('no_hp_siswa'),

            // Data Ayah
            'nama_ayah'      => strtoupper($this->request->getPost('nama_ayah')),
            'pekerjaan_ayah' => strtoupper($this->request->getPost('pekerjaan_ayah')),
            'no_hp_ayah'     => $this->request->getPost('no_hp_ayah'),

            // Data Ibu
            'nama_ibu'       => strtoupper($this->request->getPost('nama_ibu')),
            'pekerjaan_ibu'  => strtoupper($this->request->getPost('pekerjaan_ibu')),
            'no_hp_ibu'      => $this->request->getPost('no_hp_ibu'),

            // Data Wali (Opsional)
            'nama_wali'      => strtoupper($this->request->getPost('nama_wali')),
            'pekerjaan_wali' => strtoupper($this->request->getPost('pekerjaan_wali')),
            'no_hp_wali'     => $this->request->getPost('no_hp_wali'),

            // Berkas
            'foto'           => $namaFoto,
            'berkas_kk'      => $namaKK,
            'berkas_ijazah'  => $namaIjazah,

            // System
            'status_pendaftaran' => 'Pending',
            'tgl_daftar'     => date('Y-m-d H:i:s')
        ];

        // 4. Simpan DB
        if ($this->db->table('tbl_pendaftar')->insert($data)) {
            $this->kirimNotifikasiWA($data);
            return redirect()->to('spmb/success/' . $no_pendaftaran);
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function success($no_reg)
    {
        $data = [
            'web' => $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray(),
            'pendaftar' => $this->db->table('tbl_pendaftar')->where('no_pendaftaran', $no_reg)->get()->getRowArray(),
            'no_reg' => $no_reg
        ];
        return view('spmb/success', $data);
    }

    // --- Helper Kirim WA ---
    private function kirimNotifikasiWA($data)
    {
        $sekolah = $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray();
        
        $pesan = "*PENDAFTARAN BERHASIL!* 🎓\n\n";
        $pesan .= "Halo *" . $data['nama_lengkap'] . "*,\n";
        $pesan .= "Selamat! Data pendaftaran Anda di *" . $sekolah['nama_sekolah'] . "* telah kami terima.\n\n";
        $pesan .= "📋 *Detail Pendaftaran:*\n";
        $pesan .= "----------------------------------\n";
        $pesan .= "🏷️ No. Reg : *" . $data['no_pendaftaran'] . "*\n";
        $pesan .= "👤 Nama : " . $data['nama_lengkap'] . "\n";
        $pesan .= "🏫 Asal : " . $data['asal_sekolah'] . "\n";
        $pesan .= "🎓 Jurusan : " . $data['jurusan_minat'] . "\n";
        $pesan .= "----------------------------------\n\n";
        $pesan .= "Mohon tunggu verifikasi dari panitia. Terima kasih.";

        try {
            $wa = new WaService();
            
            // PERBAIKAN: Menggunakan method kirim() bukan send()
            $wa->kirim($data['no_hp_siswa'], $pesan);
            
            if(!empty($data['no_hp_ortu']) && $data['no_hp_ortu'] != $data['no_hp_siswa']){
                 $wa->kirim($data['no_hp_ortu'], $pesan);
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal WA: ' . $e->getMessage());
        }
    }

    public function cetak($id)
    {
        $pendaftar = $this->db->table('tbl_pendaftar')->where('id', $id)->get()->getRowArray();

        // Validasi: Jika data tidak ditemukan
        if (!$pendaftar) {
            return redirect()->to('/')->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'web'       => $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray(),
            'pendaftar' => $pendaftar
        ];

        // Menggunakan View yang sama dengan Admin biar hemat kodingan
        return view('spmb/print_formulir', $data);
    }

    private function uploadFile($fieldName, $default = null) {
        $file = $this->request->getFile($fieldName);
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $name = $file->getRandomName();
            $file->move('uploads/ppdb', $name);
            return $name;
        }
        return $default;
    }
}