<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Cms extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // --- 1. MANAJEMEN SLIDER ---
    public function slider()
    {
        $data = [
            'title'   => 'Manajemen Slider',
            'sliders' => $this->db->table('tbl_slider')->orderBy('urutan', 'ASC')->get()->getResultArray()
        ];
        return view('admin/cms/slider', $data);
    }

    public function save_slider()
    {
        $file = $this->request->getFile('gambar');
        $namaFile = '';

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/slider', $namaFile);
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'urutan'    => $this->request->getPost('urutan'),
        ];

        // Kalau ada upload gambar baru, simpan. Kalau tidak, skip.
        if (!empty($namaFile)) {
            $data['gambar'] = $namaFile;
        }

        $id = $this->request->getPost('id');
        if (empty($id)) {
            $this->db->table('tbl_slider')->insert($data);
        } else {
            // Hapus gambar lama jika ada gambar baru (optional logic here)
            $this->db->table('tbl_slider')->where('id', $id)->update($data);
        }

        return redirect()->to('admin/cms/slider')->with('success', 'Slider berhasil disimpan');
    }

    public function delete_slider($id)
    {
        $this->db->table('tbl_slider')->where('id', $id)->delete();
        return redirect()->to('admin/cms/slider')->with('success', 'Slider dihapus');
    }

    // --- 2. MANAJEMEN BERITA ---
    public function berita()
    {
        $data = [
            'title'  => 'Daftar Berita',
            'berita' => $this->db->table('tbl_berita')->orderBy('created_at', 'DESC')->get()->getResultArray()
        ];
        return view('admin/cms/berita_list', $data);
    }

    public function berita_add()
    {
        return view('admin/cms/berita_form', ['title' => 'Tambah Berita', 'berita' => null]);
    }

    public function berita_edit($id)
    {
        $berita = $this->db->table('tbl_berita')->where('id', $id)->get()->getRowArray();
        return view('admin/cms/berita_form', ['title' => 'Edit Berita', 'berita' => $berita]);
    }

    public function save_berita()
    {
        $judul = $this->request->getPost('judul');
        $slug  = url_title($judul, '-', true);
        
        $data = [
            'judul' => $judul,
            'slug'  => $slug,
            'isi'   => $this->request->getPost('isi'),
            'is_published' => 1
        ];

        // Handle Gambar Berita
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/berita', $namaFile);
            $data['gambar'] = $namaFile;
        }

        $id = $this->request->getPost('id');
        if (empty($id)) {
            $this->db->table('tbl_berita')->insert($data);
        } else {
            // Jika edit, jangan update slug (opsional, biar link gak putus)
            // unset($data['slug']); 
            $this->db->table('tbl_berita')->where('id', $id)->update($data);
        }

        return redirect()->to('admin/cms/berita')->with('success', 'Berita berhasil dipublikasikan');
    }
    
    public function delete_berita($id)
    {
        $this->db->table('tbl_berita')->where('id', $id)->delete();
        return redirect()->to('admin/cms/berita')->with('success', 'Berita dihapus');
    }

    // --- 3. MANAJEMEN HALAMAN (VISI MISI / SPMB) ---
    public function halaman()
    {
        $data = [
            'title' => 'Halaman Statis',
            'pages' => $this->db->table('tbl_halaman')->get()->getResultArray()
        ];
        return view('admin/cms/halaman_list', $data);
    }

    public function halaman_edit($slug)
    {
        $page = $this->db->table('tbl_halaman')->where('slug', $slug)->get()->getRowArray();
        return view('admin/cms/halaman_form', ['title' => 'Edit Halaman', 'page' => $page]);
    }

    public function save_halaman()
    {
        $id = $this->request->getPost('id');
        $data = [
            'judul' => $this->request->getPost('judul'),
            'isi'   => $this->request->getPost('isi'),
        ];
        
        $this->db->table('tbl_halaman')->where('id', $id)->update($data);
        return redirect()->to('admin/cms/halaman')->with('success', 'Halaman berhasil diperbarui');
    }

    // --- 4. MANAJEMEN GALERI ---
    public function galeri()
    {
        $data = [
            'title'  => 'Galeri Foto',
            'galeri' => $this->db->table('tbl_galeri')->orderBy('id', 'DESC')->get()->getResultArray()
        ];
        return view('admin/cms/galeri', $data);
    }

    public function save_galeri()
    {
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/galeri', $namaFile);
            
            $this->db->table('tbl_galeri')->insert([
                'judul'    => $this->request->getPost('judul'),
                'kategori' => $this->request->getPost('kategori'),
                'gambar'   => $namaFile
            ]);
            
            return redirect()->to('admin/cms/galeri')->with('success', 'Foto berhasil diupload');
        }
        return redirect()->to('admin/cms/galeri')->with('error', 'Gagal upload gambar');
    }
    
    public function delete_galeri($id)
    {
        // Hapus file fisik dulu (best practice)
        $foto = $this->db->table('tbl_galeri')->where('id', $id)->get()->getRowArray();
        if($foto && file_exists('uploads/galeri/' . $foto['gambar'])) {
            unlink('uploads/galeri/' . $foto['gambar']);
        }
        
        $this->db->table('tbl_galeri')->where('id', $id)->delete();
        return redirect()->to('admin/cms/galeri')->with('success', 'Foto dihapus');
    }

    public function profil()
    {
        $data = [
            'title'  => 'Konfigurasi Tampilan Website',
            'web'    => $this->db->table('tbl_web_profil')->where('id', 1)->get()->getRowArray()
        ];
        return view('admin/cms/profil', $data);
    }

    public function save_profil()
    {
        $id = 1; // Selalu update ID 1
        
        $data = [
            'deskripsi_hero'  => $this->request->getPost('deskripsi_hero'),
            'nama_kepsek'     => $this->request->getPost('nama_kepsek'),
            'sambutan_kepsek' => $this->request->getPost('sambutan_kepsek'),
            'link_fb'         => $this->request->getPost('link_fb'),
            'link_ig'         => $this->request->getPost('link_ig'),
            'link_yt'         => $this->request->getPost('link_yt'),
            'link_map'        => $this->request->getPost('link_map'),
        ];

        // Upload Foto Kepsek (Khusus Web)
        $file = $this->request->getFile('foto_kepsek');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/profil', $namaFile);
            $data['foto_kepsek'] = $namaFile;
        }

        $this->db->table('tbl_web_profil')->where('id', $id)->update($data);
        return redirect()->to('admin/cms/profil')->with('success', 'Profil website berhasil diperbarui');
    }
}