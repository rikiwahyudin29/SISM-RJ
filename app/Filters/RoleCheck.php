<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah sudah login?
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Ambil Role yang dibutuhkan halaman ini (dari Routes)
        // Contoh: $arguments[0] berisi 'admin' atau 'guru'
        $requiredRole = $arguments[0] ?? null;

        // 3. Ambil Daftar Role User dari Session (Array)
        $userRoles = session()->get('roles'); // Hasil: ['guru', 'piket']

        // Pastikan userRoles tidak kosong (array)
        if (empty($userRoles)) {
             return redirect()->to(base_url('login'))->with('error', 'Akun error: Tidak punya role.');
        }

        // 4. Cek: Apakah user punya tiket untuk masuk?
        // Logic: Jika halaman butuh 'admin', cek apakah 'admin' ada di saku user
        if ($requiredRole && !in_array($requiredRole, $userRoles)) {
    $myDashboard = session()->get('role_active'); 
    return redirect()->to(base_url($myDashboard . '/dashboard'))
                     ->with('access_denied', 'Eits! Anda tidak punya akses ke halaman itu.');
}
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}