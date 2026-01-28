<?php

namespace App\Controllers\Admin\Keuangan;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Laporan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $jenis_filter = $this->request->getGet('filter') ?? 'harian';
        $tanggal_awal = $this->request->getGet('start');
        $tanggal_akhir = $this->request->getGet('end');

        // Logic Tanggal Default (Sama seperti sebelumnya)
        if (empty($tanggal_awal)) {
            if ($jenis_filter == 'harian') {
                $tanggal_awal = date('Y-m-d');
                $tanggal_akhir = date('Y-m-d');
            } elseif ($jenis_filter == 'mingguan') {
                $tanggal_awal = date('Y-m-d', strtotime('monday this week'));
                $tanggal_akhir = date('Y-m-d', strtotime('sunday this week'));
            } elseif ($jenis_filter == 'bulanan') {
                $tanggal_awal = date('Y-m-01');
                $tanggal_akhir = date('Y-m-t');
            } elseif ($jenis_filter == 'tahunan') {
                $tanggal_awal = date('Y-01-01');
                $tanggal_akhir = date('Y-12-31');
            }
        }

        // 1. QUERY PEMASUKAN
        $transaksi = $this->db->table('tbl_transaksi')
            ->select('tbl_transaksi.*, tbl_siswa.nama_lengkap, tbl_kelas.nama_kelas, tbl_pos_bayar.nama_pos, tbl_tagihan.keterangan')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_transaksi.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = ' . ($this->db->fieldExists('kelas_id', 'tbl_siswa') ? 'tbl_siswa.kelas_id' : 'tbl_siswa.id_kelas'))
            ->join('tbl_tagihan', 'tbl_tagihan.id = tbl_transaksi.id_tagihan')
            ->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->where("DATE(tbl_transaksi.tanggal_bayar) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'")
            ->orderBy('tbl_transaksi.created_at', 'DESC')
            ->get()->getResultArray();

        // 2. QUERY PENGELUARAN (BARU)
        $pengeluaran = $this->db->table('tbl_pengeluaran')
            ->select('tbl_pengeluaran.*, tbl_divisi.nama_divisi, tbl_jenis_pengeluaran.nama_jenis')
            ->join('tbl_divisi', 'tbl_divisi.id = tbl_pengeluaran.id_divisi')
            ->join('tbl_jenis_pengeluaran', 'tbl_jenis_pengeluaran.id = tbl_pengeluaran.id_jenis')
            ->where("tbl_pengeluaran.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'")
            ->orderBy('tbl_pengeluaran.tanggal', 'DESC')
            ->get()->getResultArray();

        // 3. HITUNG SALDO
        $total_masuk = 0;
        foreach ($transaksi as $t) $total_masuk += $t['jumlah_bayar'];

        $total_keluar = 0;
        foreach ($pengeluaran as $p) $total_keluar += $p['nominal'];

        $saldo_akhir = $total_masuk - $total_keluar;

        // 4. QUERY REKAPITULASI (Sama seperti sebelumnya)
        $rekap = $this->db->table('tbl_jenis_bayar')
            ->select('
                tbl_jenis_bayar.id, tbl_pos_bayar.nama_pos, tbl_jenis_bayar.tipe_bayar, tbl_tahun_ajaran.tahun_ajaran,
                COUNT(tbl_tagihan.id) as total_siswa_ditagih,
                SUM(tbl_tagihan.nominal_tagihan) as total_potensi_rupiah,
                SUM(CASE WHEN tbl_tagihan.status_bayar = "LUNAS" THEN 1 ELSE 0 END) as siswa_lunas,
                SUM(tbl_tagihan.nominal_terbayar) as uang_masuk,
                SUM(CASE WHEN tbl_tagihan.status_bayar != "LUNAS" THEN 1 ELSE 0 END) as siswa_belum_lunas,
                SUM(tbl_tagihan.nominal_tagihan - tbl_tagihan.nominal_terbayar) as uang_tunggakan
            ')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->join('tbl_tahun_ajaran', 'tbl_tahun_ajaran.id = tbl_jenis_bayar.id_tahun_ajaran')
            ->join('tbl_tagihan', 'tbl_tagihan.id_jenis_bayar = tbl_jenis_bayar.id', 'left') 
            ->groupBy('tbl_jenis_bayar.id')
            ->orderBy('tbl_jenis_bayar.id', 'DESC')
            ->get()->getResultArray();

        return view('admin/keuangan/laporan/index', [
            'title'     => 'Laporan Keuangan',
            'filter'    => $jenis_filter,
            'start'     => $tanggal_awal,
            'end'       => $tanggal_akhir,
            'transaksi' => $transaksi,
            'pengeluaran' => $pengeluaran, // Kirim ke view
            'total_masuk' => $total_masuk,
            'total_keluar'=> $total_keluar, // Kirim Total Keluar
            'saldo_akhir' => $saldo_akhir, // Kirim Saldo
            'rekap'     => $rekap
        ]);
    }

   // --- CETAK LAPORAN LENGKAP (PEMASUKAN & PENGELUARAN) ---
    public function cetak_transaksi()
    {
        $start = $this->request->getGet('start');
        $end   = $this->request->getGet('end');
        
        // 1. AMBIL DATA PEMASUKAN
        $transaksi = $this->db->table('tbl_transaksi')
            ->select('tbl_transaksi.*, tbl_siswa.nama_lengkap, tbl_kelas.nama_kelas, tbl_pos_bayar.nama_pos, tbl_tagihan.keterangan')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_transaksi.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = ' . ($this->db->fieldExists('kelas_id', 'tbl_siswa') ? 'tbl_siswa.kelas_id' : 'tbl_siswa.id_kelas'))
            ->join('tbl_tagihan', 'tbl_tagihan.id = tbl_transaksi.id_tagihan')
            ->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->where("DATE(tbl_transaksi.tanggal_bayar) BETWEEN '$start' AND '$end'")
            ->orderBy('tbl_transaksi.created_at', 'ASC')
            ->get()->getResultArray();

        // 2. AMBIL DATA PENGELUARAN (BARU)
        $pengeluaran = $this->db->table('tbl_pengeluaran')
            ->select('tbl_pengeluaran.*, tbl_divisi.nama_divisi, tbl_jenis_pengeluaran.nama_jenis')
            ->join('tbl_divisi', 'tbl_divisi.id = tbl_pengeluaran.id_divisi')
            ->join('tbl_jenis_pengeluaran', 'tbl_jenis_pengeluaran.id = tbl_pengeluaran.id_jenis')
            ->where("tbl_pengeluaran.tanggal BETWEEN '$start' AND '$end'")
            ->orderBy('tbl_pengeluaran.tanggal', 'ASC')
            ->get()->getResultArray();

        return view('admin/keuangan/laporan/cetak_transaksi', [
            'transaksi'   => $transaksi,
            'pengeluaran' => $pengeluaran, // Data pengeluaran dikirim ke view
            'start'       => $start,
            'end'         => $end,
            'sekolah'     => [
                'nama'   => 'SMK DIGITAL INDONESIA', 
                'alamat' => 'Jl. Teknologi No. 1, Jakarta Selatan'
            ]
        ]);
    }

    // --- CETAK LAPORAN TUNGGAKAN (PDF/PRINT) ---
    public function cetak_tunggakan()
    {
         $rekap = $this->db->table('tbl_jenis_bayar')
            ->select('
                tbl_pos_bayar.nama_pos,
                tbl_tahun_ajaran.tahun_ajaran,
                tbl_jenis_bayar.tipe_bayar,
                COUNT(tbl_tagihan.id) as total_siswa,
                SUM(tbl_tagihan.nominal_tagihan) as total_tagihan,
                SUM(tbl_tagihan.nominal_terbayar) as total_bayar,
                SUM(tbl_tagihan.nominal_tagihan - tbl_tagihan.nominal_terbayar) as total_tunggakan,
                SUM(CASE WHEN tbl_tagihan.status_bayar = "LUNAS" THEN 1 ELSE 0 END) as qty_lunas,
                SUM(CASE WHEN tbl_tagihan.status_bayar != "LUNAS" THEN 1 ELSE 0 END) as qty_belum
            ')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->join('tbl_tahun_ajaran', 'tbl_tahun_ajaran.id = tbl_jenis_bayar.id_tahun_ajaran')
            ->join('tbl_tagihan', 'tbl_tagihan.id_jenis_bayar = tbl_jenis_bayar.id', 'left')
            ->groupBy('tbl_jenis_bayar.id')
            ->orderBy('tbl_jenis_bayar.id', 'DESC')
            ->get()->getResultArray();

        return view('admin/keuangan/laporan/cetak_tunggakan', [
            'rekap' => $rekap,
            'sekolah' => ['nama' => 'SMK DIGITAL INDONESIA', 'alamat' => 'Jl. Teknologi No.1 Jakarta']
        ]);
    }
    public function export_excel()
    {
        $start = $this->request->getGet('start') ?? date('Y-m-01');
        $end   = $this->request->getGet('end')   ?? date('Y-m-d');

        // Ambil Data Lagi (Sama seperti index)
        $transaksi   = $this->db->table('tbl_transaksi')->join('tbl_siswa', 'tbl_siswa.id = tbl_transaksi.id_siswa')->join('tbl_tagihan', 'tbl_tagihan.id = tbl_transaksi.id_tagihan')->join('tbl_jenis_bayar', 'tbl_jenis_bayar.id = tbl_tagihan.id_jenis_bayar')->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')->where("DATE(tbl_transaksi.created_at) >=", $start)->where("DATE(tbl_transaksi.created_at) <=", $end)->orderBy('tbl_transaksi.created_at', 'ASC')->get()->getResultArray();
        $pengeluaran = $this->db->table('tbl_pengeluaran')->join('tbl_divisi', 'tbl_divisi.id = tbl_pengeluaran.id_divisi')->join('tbl_jenis_pengeluaran', 'tbl_jenis_pengeluaran.id = tbl_pengeluaran.id_jenis')->where("tanggal >=", $start)->where("tanggal <=", $end)->orderBy('tanggal', 'ASC')->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- HEADER LAPORAN ---
        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN SEKOLAH');
        $sheet->setCellValue('A2', 'Periode: ' . date('d/m/Y', strtotime($start)) . ' s/d ' . date('d/m/Y', strtotime($end)));
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // --- BAGIAN 1: PEMASUKAN ---
        $row = 4;
        $sheet->setCellValue('A' . $row, 'A. PEMASUKAN (PEMBAYARAN SISWA)');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;

        // Header Tabel Masuk
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Tanggal');
        $sheet->setCellValue('C' . $row, 'Siswa');
        $sheet->setCellValue('D' . $row, 'Keterangan');
        $sheet->setCellValue('E' . $row, 'Nominal (Rp)');
        
        // Style Header
        $sheet->getStyle("A$row:E$row")->getFont()->setBold(true);
        $sheet->getStyle("A$row:E$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        $row++;
        $no = 1;
        $totalMasuk = 0;
        foreach ($transaksi as $t) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d/m/Y H:i', strtotime($t['created_at'])));
            $sheet->setCellValue('C' . $row, $t['nama_lengkap']);
            $sheet->setCellValue('D' . $row, $t['nama_pos'] . ' - ' . $t['keterangan']);
            $sheet->setCellValue('E' . $row, $t['jumlah_bayar']);
            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("A$row:E$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            $totalMasuk += $t['jumlah_bayar'];
            $row++;
        }
        // Total Masuk
        $sheet->setCellValue('D' . $row, 'TOTAL PEMASUKAN');
        $sheet->setCellValue('E' . $row, $totalMasuk);
        $sheet->getStyle("D$row:E$row")->getFont()->setBold(true);
        $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row += 3; // Spasi

        // --- BAGIAN 2: PENGELUARAN ---
        $sheet->setCellValue('A' . $row, 'B. PENGELUARAN OPERASIONAL');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;

        // Header Tabel Keluar
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Tanggal');
        $sheet->setCellValue('C' . $row, 'Judul & Divisi');
        $sheet->setCellValue('D' . $row, 'Keterangan');
        $sheet->setCellValue('E' . $row, 'Nominal (Rp)');
        
        $sheet->getStyle("A$row:E$row")->getFont()->setBold(true);
        $sheet->getStyle("A$row:E$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $no = 1;
        $totalKeluar = 0;
        foreach ($pengeluaran as $p) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($p['tanggal'])));
            $sheet->setCellValue('C' . $row, $p['judul_pengeluaran'] . " (" . $p['nama_divisi'] . ")");
            $sheet->setCellValue('D' . $row, $p['keterangan']);
            $sheet->setCellValue('E' . $row, $p['nominal']);
            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("A$row:E$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            $totalKeluar += $p['nominal'];
            $row++;
        }
        // Total Keluar
        $sheet->setCellValue('D' . $row, 'TOTAL PENGELUARAN');
        $sheet->setCellValue('E' . $row, $totalKeluar);
        $sheet->getStyle("D$row:E$row")->getFont()->setBold(true);
        $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row += 2;

        // --- BAGIAN 3: SALDO AKHIR ---
        $sheet->setCellValue('D' . $row, 'SURPLUS / DEFISIT');
        $sheet->setCellValue('E' . $row, $totalMasuk - $totalKeluar);
        $sheet->getStyle("D$row:E$row")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');

        // Auto Width
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download File
        $filename = "Laporan_Keuangan_" . date('Ymd', strtotime($start)) . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // --- HELPER UNTUK REKAP TUNGGAKAN ---
    private function getRekapTunggakan()
    {
        $rekap = $this->db->table('tbl_jenis_bayar')
            ->select('tbl_jenis_bayar.id, tbl_pos_bayar.nama_pos, tbl_jenis_bayar.tipe_bayar, tbl_jenis_bayar.tahun_ajaran_id')
            ->join('tbl_pos_bayar', 'tbl_pos_bayar.id = tbl_jenis_bayar.id_pos_bayar')
            ->get()->getResultArray();

        foreach($rekap as &$r) {
            // Ambil Nama Tahun Ajaran (Optional jika ada tabelnya)
            // $r['tahun_ajaran'] = ...
            $r['tahun_ajaran'] = 'Semua Tahun'; // Default

            // Hitung Statistik
            $stats = $this->db->table('tbl_tagihan')
                ->select('SUM(nominal_tagihan) as potensi, SUM(nominal_terbayar) as masuk')
                ->where('id_jenis_bayar', $r['id'])
                ->get()->getRow();
            
            $r['total_potensi_rupiah'] = $stats->potensi ?? 0;
            $r['uang_masuk'] = $stats->masuk ?? 0;
            $r['uang_tunggakan'] = $r['total_potensi_rupiah'] - $r['uang_masuk'];
            
            $r['siswa_lunas'] = $this->db->table('tbl_tagihan')->where('id_jenis_bayar', $r['id'])->where('status_bayar', 'LUNAS')->countAllResults();
            $r['siswa_belum_lunas'] = $this->db->table('tbl_tagihan')->where('id_jenis_bayar', $r['id'])->where('status_bayar !=', 'LUNAS')->countAllResults();
        }
        return $rekap;
    }
}