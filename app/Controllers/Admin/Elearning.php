<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;

class Elearning extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $id_user = session()->get('id_user');
        
        // Cek Role Guru (ID 8) via database agar lebih akurat
        $isGuru = $this->db->table('user_roles')->where(['user_id' => $id_user, 'role_id' => 8])->countAllResults() > 0;
        $isAdmin = $this->db->table('user_roles')->where(['user_id' => $id_user, 'role_id' => 1])->countAllResults() > 0;
        $isSiswa = $this->db->table('user_roles')->where(['user_id' => $id_user, 'role_id' => 11])->countAllResults() > 0;

        $data = [
            'title' => 'Kelas Virtual (G-Meet)',
            'role'  => $isAdmin ? 'admin' : ($isGuru ? 'guru' : ($isSiswa ? 'siswa' : 'guest')),
            'is_connected' => false,
            'kelas' => $this->db->table('tbl_kelas')->get()->getResultArray()
        ];

        if ($isGuru) {
            $guru = $this->db->table('tbl_guru')->where('id_user', $id_user)->get()->getRow();
            $data['is_connected'] = ($guru && !empty($guru->google_refresh_token));
            
            // Query Jadwal Guru dengan Alias v (v.*) untuk cegah Ambiguous
            $data['jadwal'] = $this->db->table('tbl_kelas_virtual v')
                ->select('v.*, k.nama_kelas')
                ->join('tbl_kelas k', 'k.id = v.kelas_id')
                ->where('v.guru_id', $guru->id ?? 0)
                ->orderBy('v.id', 'DESC')->get()->getResultArray();
                
        } elseif ($isSiswa) {
            $siswa = $this->db->table('tbl_siswa')->where('id_user', $id_user)->get()->getRow();
            $data['jadwal'] = $this->db->table('tbl_kelas_virtual v')
                ->select('v.*, g.nama_lengkap as nama_guru')
                ->join('tbl_guru g', 'g.id = v.guru_id')
                ->where('v.kelas_id', $siswa->kelas_id ?? 0)
                ->where('v.status', 'Aktif')
                ->orderBy('v.id', 'DESC')->get()->getResultArray();
        }

        return view('admin/elearning/index', $data);
    }

   public function save()
{
    $id_user = session()->get('id_user');
    $guru = $this->db->table('tbl_guru')->where('id_user', $id_user)->get()->getRow();

    if (!$guru || empty($guru->google_refresh_token)) {
        return redirect()->back()->with('error', 'Silakan hubungkan akun Google terlebih dahulu!');
    }

    // 1. Ambil data dari FORM Bos
    $mapel      = $this->request->getPost('mapel');      // Mengambil name="mapel"
    $kelas_id   = $this->request->getPost('kelas_id');   // Mengambil name="kelas_id"
    $jam_mulai   = $this->request->getPost('jam_mulai');   // Mengambil name="jam_mulai" (format 00:00)
    $jam_selesai = $this->request->getPost('jam_selesai'); // Mengambil name="jam_selesai"

    // 2. Gabungkan JAM dari form dengan TANGGAL hari ini untuk Google & Database
    $tgl_hari_ini = date('Y-m-d');
    $full_start   = $tgl_hari_ini . ' ' . $jam_mulai . ':00';
    $full_end     = $tgl_hari_ini . ' ' . $jam_selesai . ':00';

    // 3. Setup Google Client
    $client = new \Google\Client();
    $client->setAuthConfig(ROOTPATH . 'client_secret.json');
    $client->setAccessToken(json_decode($guru->google_refresh_token, true));

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $this->db->table('tbl_guru')->where('id_user', $id_user)->update(['google_refresh_token' => json_encode($newToken)]);
        }
    }

    $service = new \Google\Service\Calendar($client);

    // 4. Buat Event Google Meet
    $event = new \Google\Service\Calendar\Event([
        'summary'     => 'Kelas: ' . $mapel,
        'description' => 'Dibuat otomatis via SIAKAD',
        'start'       => ['dateTime' => date('c', strtotime($full_start)), 'timeZone' => 'Asia/Jakarta'],
        'end'         => ['dateTime' => date('c', strtotime($full_end)), 'timeZone' => 'Asia/Jakarta'],
        'conferenceData' => [
            'createRequest' => ['requestId' => uniqid(), 'conferenceSolutionKey' => ['type' => 'hangoutsMeet']]
        ]
    ]);

    try {
        $event = $service->events->insert('primary', $event, ['conferenceDataVersion' => 1]);
        $meetLink = $event->getHangoutLink();

        // 5. Simpan ke Database Bos sesuai nama kolom di tbl_kelas_virtual
        $this->db->table('tbl_kelas_virtual')->insert([
            'guru_id'         => $guru->id,
            'kelas_id'        => $kelas_id,
            'mapel_id'        => 0, // Bisa diisi ID mapel jika ada master datanya
            'judul_pertemuan' => $mapel,        // Sesuai kolom judul_pertemuan
            'tgl_pertemuan'   => $full_start,   // Sesuai kolom tgl_pertemuan (datetime)
            'link_meet'       => $meetLink,     // Sesuai kolom link_meet
            'status'          => 'Aktif'        // Sesuai enum status
        ]);

        return redirect()->to('admin/elearning')->with('success', 'Link Google Meet berhasil dibuat!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}

    public function delete($id)
    {
        $this->db->table('tbl_kelas_virtual')->where('id', $id)->delete();
        return redirect()->to('admin/elearning')->with('success', 'Jadwal berhasil dihapus.');
    }
}