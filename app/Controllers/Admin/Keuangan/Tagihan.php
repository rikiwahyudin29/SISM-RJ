<?php

namespace App\Controllers\Admin\Keuangan;

use App\Controllers\BaseController;

class Tagihan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 1. HALAMAN KELOLA (Lihat Daftar Siswa & Edit Nominal)
    public function kelola($id_jenis)
    {
        // Info Settingan
        $info = $this->db->table('tbl_jenis_bayar')
            ->select('tbl_jenis_bayar.*, tbl_pos_bayar.nama_pos, tbl_tahun_ajaran.tahun_ajaran')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->join('tbl_tahun_ajaran', 'tbl_tahun_ajaran.id = tbl_jenis_bayar.id_tahun_ajaran')
            ->where('tbl_jenis_bayar.id', $id_jenis)
            ->get()->getRowArray();

        if(!$info) return redirect()->to('admin/keuangan/jenis');

        // Filter Kelas (Jika ada request get)
        $filterKelas = $this->request->getGet('kelas');
        
        // Query Tagihan Siswa
        $builder = $this->db->table('tbl_tagihan')
            ->select('tbl_tagihan.*, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_tagihan.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_tagihan.id_kelas')
            ->where('tbl_tagihan.id_jenis_bayar', $id_jenis);

        if($filterKelas) {
            $builder->where('tbl_tagihan.id_kelas', $filterKelas);
        }

        // Tampilkan 100 data saja biar tidak berat (Pagination manual via search nanti)
        // Atau urutkan berdasarkan kelas & nama
        $tagihan = $builder->orderBy('tbl_kelas.nama_kelas', 'ASC')
                           ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
                           ->orderBy('tbl_tagihan.bulan_ke', 'ASC') // Biar Juli duluan
                           ->get()->getResultArray();

        $kelas = $this->db->table('tbl_kelas')->orderBy('nama_kelas', 'ASC')->get()->getResultArray();

        return view('admin/keuangan/tagihan/kelola', [
            'info'    => $info,
            'tagihan' => $tagihan,
            'kelas'   => $kelas,
            'filter'  => $filterKelas
        ]);
    }

    // 2. GENERATE MASSAL (Support Per Kelas)
    public function generate()
    {
        $id_jenis = $this->request->getPost('id_jenis_bayar');
        $kelas_ids = $this->request->getPost('id_kelas'); 
        
        if(empty($kelas_ids)) {
            return redirect()->back()->with('error', 'Harap pilih minimal satu kelas!');
        }

        // 1. Ambil Info Jenis Pembayaran
        $jenis = $this->db->table('tbl_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->where('tbl_jenis_bayar.id', $id_jenis)
            ->get()->getRowArray();

        // 2. Ambil Siswa
        $builder = $this->db->table('tbl_siswa');
        
        // Cek nama kolom kelas (antisipasi beda nama: kelas_id atau id_kelas)
        if ($this->db->fieldExists('kelas_id', 'tbl_siswa')) {
            $builder->select('id, kelas_id as id_kelas_siswa'); 
            $builder->whereIn('kelas_id', $kelas_ids);
        } else {
            $builder->select('id, id_kelas as id_kelas_siswa');
            $builder->whereIn('id_kelas', $kelas_ids);
        }
        
        // PERBAIKAN: Hapus filter ->where('status', 'Aktif') karena kolom tidak ada
        $siswa = $builder->get()->getResultArray();

        if (empty($siswa)) return redirect()->back()->with('error', 'Tidak ada siswa di kelas yang dipilih.');

        $sukses = 0;
        $bulanIndo = [1=>'Juli', 2=>'Agustus', 3=>'September', 4=>'Oktober', 5=>'November', 6=>'Desember', 7=>'Januari', 8=>'Februari', 9=>'Maret', 10=>'April', 11=>'Mei', 12=>'Juni'];

        // 3. Loop Insert
        foreach ($siswa as $s) {
            $kelasSiswa = $s['id_kelas_siswa'];

            if ($jenis['tipe_bayar'] == 'BEBAS') {
                $exist = $this->db->table('tbl_tagihan')
                    ->where(['id_jenis_bayar'=>$id_jenis, 'id_siswa'=>$s['id']])
                    ->countAllResults();
                
                if ($exist == 0) {
                    $this->db->table('tbl_tagihan')->insert([
                        'id_jenis_bayar' => $id_jenis,
                        'id_siswa'       => $s['id'], 
                        'id_kelas'       => $kelasSiswa,
                        'nominal_tagihan'=> $jenis['nominal_default'],
                        'keterangan'     => $jenis['nama_pos']
                    ]);
                    $sukses++;
                }
            } else {
                foreach ($bulanIndo as $idx => $bln) {
                    $exist = $this->db->table('tbl_tagihan')
                        ->where(['id_jenis_bayar'=>$id_jenis, 'id_siswa'=>$s['id'], 'bulan_ke'=>$idx])
                        ->countAllResults();

                    if ($exist == 0) {
                        $this->db->table('tbl_tagihan')->insert([
                            'id_jenis_bayar' => $id_jenis,
                            'id_siswa'       => $s['id'], 
                            'id_kelas'       => $kelasSiswa,
                            'nominal_tagihan'=> $jenis['nominal_default'],
                            'keterangan'     => $bln, 
                            'bulan_ke'       => $idx
                        ]);
                        $sukses++;
                    }
                }
            }
        }

        return redirect()->to('admin/keuangan/tagihan/kelola/' . $id_jenis)->with('success', "$sukses tagihan berhasil dibuat.");
    }

    // 3. UPDATE NOMINAL (AJAX - Untuk Beasiswa)
    public function update_nominal()
    {
        $id = $this->request->getPost('pk'); // ID Tagihan
        $nominal = str_replace('.', '', $this->request->getPost('value')); // Nominal Baru

        // Cek dulu apakah sudah ada pembayaran
        $cek = $this->db->table('tbl_tagihan')->where('id', $id)->get()->getRow();
        if ($cek->nominal_terbayar > 0 && $nominal < $cek->nominal_terbayar) {
            return $this->response->setStatusCode(400)->setBody('Gagal! Nominal tagihan tidak boleh lebih kecil dari yang sudah dibayar.');
        }

        $this->db->table('tbl_tagihan')->where('id', $id)->update(['nominal_tagihan' => $nominal]);
        return $this->response->setJSON(['status' => 'success']);
    }
}