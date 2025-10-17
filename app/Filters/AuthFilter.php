<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to('/login');
        }

        // Cek role-based access jika ada arguments
        if (!empty($arguments)) {
            $userRole = session()->get('role');
            
            // Jika user role tidak ada dalam arguments yang diizinkan
            if (!in_array($userRole, $arguments)) {
                session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
                return redirect()->to('/dashboard');
            }
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
        return $response;
    }
}