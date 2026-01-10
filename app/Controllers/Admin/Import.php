<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; // Library yang baru diinstal

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
}