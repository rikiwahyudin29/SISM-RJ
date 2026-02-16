<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Sekolah extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title'   => 'Konfigurasi Sekolah',
            'sekolah' => $this->db->table('tbl_sekolah')->where('id', 1)->get()->getRowArray() // Asumsi ID Sekolah selalu 1
        ];
        return view('admin/sekolah/index', $data);
    }

    public function update()
    {
        $id = 1; // ID Default
        
       $data = [
            // 1. PROFIL UTAMA
            'nama_sekolah'      => $this->request->getPost('nama_sekolah'),
            'npsn'              => $this->request->getPost('npsn'),
            'akreditasi'        => $this->request->getPost('akreditasi'),
            'status_sekolah'    => 'Swasta', // Atau ambil dari input
            
            // 2. ALAMAT LENGKAP
            'alamat'            => $this->request->getPost('alamat'),
            'kelurahan'         => $this->request->getPost('kelurahan'),
            'kecamatan'         => $this->request->getPost('kecamatan'),
            'kabupaten'         => $this->request->getPost('kabupaten'),
            'provinsi'          => $this->request->getPost('provinsi'),
            'kode_pos'          => $this->request->getPost('kode_pos'),
            'koordinat_longlat' => $this->request->getPost('koordinat_longlat'),

            // 3. KONTAK & SOSMED
            'no_telp'           => $this->request->getPost('no_telp'),
            'email'             => $this->request->getPost('email'),
            'website'           => $this->request->getPost('website'),
            'facebook'          => $this->request->getPost('facebook'),
            'instagram'         => $this->request->getPost('instagram'),
            'youtube'           => $this->request->getPost('youtube'),
            'tiktok'            => $this->request->getPost('tiktok'),

            // 4. KEPALA SEKOLAH & LEGALITAS
            'nama_kepsek'       => $this->request->getPost('nama_kepsek'),
            'nip_kepsek'        => $this->request->getPost('nip_kepsek'),
            'no_sk_pendirian'   => $this->request->getPost('no_sk_pendirian'),
            'tgl_sk_pendirian'  => $this->request->getPost('tgl_sk_pendirian'),
            'slogan_sekolah'    => $this->request->getPost('slogan_sekolah'),

            // 5. API CONFIG (LENGKAP)
            'google_client_id'    => $this->request->getPost('google_client_id'),
            'google_client_secret'=> $this->request->getPost('google_client_secret'),
            'wa_api_url'          => $this->request->getPost('wa_api_url'), // Tambahan URL
            'wa_api_token'        => $this->request->getPost('wa_api_token'),
            'tele_bot_token'      => $this->request->getPost('tele_bot_token'),
            'tele_chat_id'        => $this->request->getPost('tele_chat_id'), // <-- INI YANG TADI HILANG BOS
            'tripay_api_key'      => $this->request->getPost('tripay_api_key'),
            'tripay_private_key'  => $this->request->getPost('tripay_private_key'),
            'tripay_merchant_code'=> $this->request->getPost('tripay_merchant_code'),
            'mode_transaksi'      => $this->request->getPost('mode_transaksi'),
        ];

        // HANDLE UPLOAD KOP SURAT
       $fileLogo = $this->request->getFile('logo');
        if ($fileLogo && $fileLogo->isValid()) {
            $namaLogo = $fileLogo->getRandomName();
            $fileLogo->move('uploads/identitas', $namaLogo);
            $data['logo'] = $namaLogo;
        }
        // HANDLE KOP SURAT
        $fileKop = $this->request->getFile('kop_surat');
        if ($fileKop && $fileKop->isValid()) {
            $namaKop = $fileKop->getRandomName();
            $fileKop->move('uploads/identitas', $namaKop);
            $data['kop_surat'] = $namaKop;
        }
        // HANDLE TTD
        $fileTtd = $this->request->getFile('ttd_kepsek');
        if ($fileTtd && $fileTtd->isValid()) {
            $namaTtd = $fileTtd->getRandomName();
            $fileTtd->move('uploads/identitas', $namaTtd);
            $data['ttd_kepsek'] = $namaTtd;
        }

        $this->db->table('tbl_sekolah')->where('id', $id)->update($data);
        return redirect()->to('admin/sekolah')->with('success', 'Data Sekolah SUPER LENGKAP Berhasil Disimpan!');
    }
}