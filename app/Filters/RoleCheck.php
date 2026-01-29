<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Cek apakah sudah login?
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Jika route tidak butuh role khusus (kosong), loloskan saja
        if (empty($arguments)) {
            return;
        }

        // 3. Ambil Daftar Role User dari Session
        $userRoles = $session->get('roles') ?? [];
        
        // Normalisasi: Pastikan bentuknya array & huruf kecil semua
        if (!is_array($userRoles)) {
            $userRoles = [$userRoles];
        }
        $userRoles = array_map('strtolower', $userRoles);


        // 4. Ambil Role yang Dibolehkan (Dari Routes)
        // Contoh argumen dari route 'role:admin,piket' adalah ['admin', 'piket']
        $allowedRoles = array_map('strtolower', $arguments);


        // 5. LOGIKA SAKTI (INTERSECT)
        // Cek apakah ada IRISAN antara role user dengan role yang dibolehkan.
        // Contoh: User=['guru', 'piket']. Dibolehkan=['admin', 'piket'].
        // Ada yang sama? Ada! ('piket'). Berarti LOLOS.
        $hasAccess = !empty(array_intersect($userRoles, $allowedRoles));

        if (!$hasAccess) {
            // Ambil role yang sedang aktif buat redirect balik ke dashboardnya
            $myDashboard = $session->get('role_active') ?? 'siswa'; 
            
            return redirect()->to(base_url($myDashboard . '/dashboard'))
                             ->with('access_denied', 'Eits! Anda tidak punya akses ke halaman itu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}