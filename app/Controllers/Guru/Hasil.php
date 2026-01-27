<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class Hasil extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 1. TAMPILAN WEB (LIST NILAI)
    public function index($idJadwal)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_mapel.nama_mapel, tbl_bank_soal.judul_ujian')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->where('tbl_jadwal_ujian.id', $idJadwal)
            ->get()->getRowArray();

        if(!$jadwal) return redirect()->to('guru/ujian');

        // Ambil Data Siswa yang sudah mengerjakan
        $nilai = $this->db->table('tbl_ujian_siswa')
            ->select('tbl_ujian_siswa.*, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ujian_siswa.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id') // Sesuaikan nama kolom kelas
            ->where('tbl_ujian_siswa.id_jadwal', $idJadwal)
            ->where('tbl_ujian_siswa.status', 1) // Hanya yang sudah selesai
            ->orderBy('tbl_ujian_siswa.nilai', 'DESC')
            ->get()->getResultArray();

        return view('guru/hasil/index', [
            'title' => 'Hasil Ujian',
            'jadwal' => $jadwal,
            'nilai' => $nilai
        ]);
    }

    // 2. EXPORT PDF (FORMAT PRINT)
    public function pdf($idJadwal)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')
            ->select('tbl_jadwal_ujian.*, tbl_mapel.nama_mapel, tbl_bank_soal.judul_ujian')
            ->join('tbl_bank_soal', 'tbl_bank_soal.id = tbl_jadwal_ujian.id_bank_soal')
            ->join('tbl_mapel', 'tbl_mapel.id = tbl_bank_soal.id_mapel')
            ->where('tbl_jadwal_ujian.id', $idJadwal)->get()->getRowArray();

        $siswa = $this->db->table('tbl_ujian_siswa')
            ->select('tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas, tbl_ujian_siswa.nilai')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ujian_siswa.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            ->where('tbl_ujian_siswa.id_jadwal', $idJadwal)
            ->where('tbl_ujian_siswa.status', 1)
            ->orderBy('tbl_siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $html = view('guru/hasil/print_pdf', ['jadwal' => $jadwal, 'siswa' => $siswa]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Nilai_" . $jadwal['nama_ujian'] . ".pdf", ["Attachment" => false]);
    }

    // 3. EXPORT EXCEL (MULTI SHEET: NILAI, ANALISIS, PELANGGARAN)
    public function excel($idJadwal)
    {
        $jadwal = $this->db->table('tbl_jadwal_ujian')->where('id', $idJadwal)->get()->getRowArray();
        
        $spreadsheet = new Spreadsheet();
        
        // --- SHEET 1: NILAI SISWA ---
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Nilai');
        
        // Header
        $headers = ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Lama Ujian', 'Benar', 'Salah', 'Nilai Akhir', 'Jawaban (No|Opsi)'];
        $col = 'A';
        foreach($headers as $h) { $sheet->setCellValue($col++ . '1', $h); }

        // Data
        $dataSiswa = $this->db->table('tbl_ujian_siswa')
            ->select('tbl_ujian_siswa.*, tbl_siswa.nama_lengkap, tbl_siswa.nis, tbl_kelas.nama_kelas')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ujian_siswa.id_siswa')
            ->join('tbl_kelas', 'tbl_kelas.id = tbl_siswa.kelas_id')
            ->where('tbl_ujian_siswa.id_jadwal', $idJadwal)
            ->where('tbl_ujian_siswa.status', 1)
            ->get()->getResultArray();

        $row = 2;
        foreach ($dataSiswa as $idx => $s) {
            // Hitung Durasi
            $start = strtotime($s['waktu_mulai']);
            $end = strtotime($s['waktu_submit']);
            $durasi = round(($end - $start) / 60) . " Menit";

            // Generate String Jawaban (Misal: 1|A, 2|C)
            $jawabanRaw = $this->db->table('tbl_jawaban_siswa')
                ->select('tbl_jawaban_siswa.nomor_urut, tbl_opsi_soal.kode_opsi, tbl_jawaban_siswa.jawaban_siswa')
                ->join('tbl_opsi_soal', 'tbl_opsi_soal.id = tbl_jawaban_siswa.id_opsi', 'left')
                ->where('tbl_jawaban_siswa.id_ujian_siswa', $s['id'])
                ->orderBy('tbl_jawaban_siswa.nomor_urut', 'ASC')
                ->get()->getResultArray();
            
            $strJawab = [];
            foreach($jawabanRaw as $j) {
                // Jika PG pakai kode_opsi (A,B,C), jika Essai pakai jawaban text
                $ans = $j['kode_opsi'] ?? substr($j['jawaban_siswa'] ?? '-', 0, 1); 
                $strJawab[] = $j['nomor_urut'] . "|" . $ans;
            }

            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $s['nis']);
            $sheet->setCellValue('C' . $row, $s['nama_lengkap']);
            $sheet->setCellValue('D' . $row, $s['nama_kelas']);
            $sheet->setCellValue('E' . $row, $durasi);
            $sheet->setCellValue('F' . $row, $s['jml_benar']);
            $sheet->setCellValue('G' . $row, $s['jml_salah']);
            $sheet->setCellValue('H' . $row, $s['nilai']);
            $sheet->setCellValue('I' . $row, implode(', ', $strJawab));
            $row++;
        }

        // --- SHEET 2: ANALISIS SOAL ---
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Analisis Soal');
        $sheet2->setCellValue('A1', 'No')->setCellValue('B1', 'Pertanyaan')->setCellValue('C1', 'Jml Benar')->setCellValue('D1', 'Total Peserta')->setCellValue('E1', 'Tingkat Kesukaran');

        // Ambil Soal
        $soal = $this->db->table('tbl_soal')->where('id_bank_soal', $jadwal['id_bank_soal'])->get()->getResultArray();
        $totalPeserta = count($dataSiswa);
        
        $row2 = 2;
        foreach ($soal as $idx => $q) {
            // Hitung berapa siswa yang menjawab benar soal ini
            // Kita harus join ke tbl_jawaban_siswa -> tbl_ujian_siswa -> filter id_jadwal
            $jmlBenar = $this->db->table('tbl_jawaban_siswa')
                ->join('tbl_ujian_siswa', 'tbl_ujian_siswa.id = tbl_jawaban_siswa.id_ujian_siswa')
                ->where('tbl_ujian_siswa.id_jadwal', $idJadwal)
                ->where('tbl_jawaban_siswa.id_soal', $q['id'])
                ->where('tbl_jawaban_siswa.is_benar', 1)
                ->countAllResults();

            $ratio = ($totalPeserta > 0) ? ($jmlBenar / $totalPeserta) : 0;
            
            // Kategori Kesukaran
            if ($ratio > 0.7) $kat = 'MUDAH';
            elseif ($ratio > 0.3) $kat = 'SEDANG';
            else $kat = 'SUKAR';

            $sheet2->setCellValue('A' . $row2, $idx + 1);
            $sheet2->setCellValue('B' . $row2, strip_tags($q['pertanyaan'])); // Bersihkan HTML tag
            $sheet2->setCellValue('C' . $row2, $jmlBenar);
            $sheet2->setCellValue('D' . $row2, $totalPeserta);
            $sheet2->setCellValue('E' . $row2, $kat);
            $row2++;
        }

        // --- SHEET 3: PELANGGARAN ---
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Log Pelanggaran');
        $sheet3->setCellValue('A1', 'No')->setCellValue('B1', 'Nama Siswa')->setCellValue('C1', 'Jumlah Pelanggaran')->setCellValue('D1', 'Status Akun');

        $pelanggar = $this->db->table('tbl_ujian_siswa')
            ->select('tbl_ujian_siswa.*, tbl_siswa.nama_lengkap')
            ->join('tbl_siswa', 'tbl_siswa.id = tbl_ujian_siswa.id_siswa')
            ->where('tbl_ujian_siswa.id_jadwal', $idJadwal)
            ->where('tbl_ujian_siswa.jml_pelanggaran >', 0)
            ->get()->getResultArray();

        $row3 = 2;
        foreach($pelanggar as $idx => $p) {
            $status = ($p['is_blocked'] == 1) ? 'DISKUALIFIKASI' : 'Aman';
            $sheet3->setCellValue('A' . $row3, $idx + 1);
            $sheet3->setCellValue('B' . $row3, $p['nama_lengkap']);
            $sheet3->setCellValue('C' . $row3, $p['jml_pelanggaran']);
            $sheet3->setCellValue('D' . $row3, $status);
            $row3++;
        }

        // Download File
        $writer = new Xlsx($spreadsheet);
        $filename = 'Hasil_Ujian_' . date('Ymd_His');
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}