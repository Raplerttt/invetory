<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        // First check if user is logged in
        if (!$session->get('logged_in')) {
            $session->set('redirect_url', current_url());
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Then check role permissions
        if (!empty($arguments)) {
            $userRole = $session->get('role');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}