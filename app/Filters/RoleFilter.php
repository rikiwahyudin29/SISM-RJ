<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cek Role (Argumen diambil dari Routes)
        $userRoles = session()->get('roles') ?? [];
        $requiredRole = $arguments[0]; // Misalnya 'admin'

        if (!in_array($requiredRole, $userRoles)) {
            // Jika bukan admin, tendang ke dashboard role masing-masing
            $roleActive = session()->get('role_active');
            return redirect()->to(base_url($roleActive . '/dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi setelah request
    }
}