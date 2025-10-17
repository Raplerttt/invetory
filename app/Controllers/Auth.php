<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation()
        ];
        
        return view('auth/login', $data);
    }
    
    public function attemptLogin()
    {
        // Validasi input
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Cari user by username
        $user = $this->userModel->where('username', $username)
                               ->where('is_active', true)
                               ->first();

        if ($user && password_verify($password, $user['password'])) {
            // Set session data
            $sessionData = [
                'userId'    => $user['id'],
                'username'  => $user['username'],
                'name'      => $user['name'],
                'role'      => $user['role'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);

            // Set remember me cookie (30 hari)
            if ($remember) {
                $this->setRememberMe($user['id']);
            }

            // Redirect ke dashboard
            return redirect()->to('/dashboard')->with('success', 'Login berhasil! Selamat datang ' . $user['name']);
        } else {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah!');
        }
    }

    public function register()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Register',
            'validation' => \Config\Services::validation()
        ];
        
        return view('auth/register', $data);
    }

    public function attemptRegister()
    {
        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]',
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email'
        ];

        $messages = [
            'username' => [
                'required' => 'Username harus diisi',
                'min_length' => 'Username minimal 3 karakter',
                'max_length' => 'Username maksimal 20 karakter',
                'is_unique' => 'Username sudah digunakan'
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'pass_confirm' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Konfirmasi password tidak sama'
            ],
            'name' => [
                'required' => 'Nama lengkap harus diisi',
                'min_length' => 'Nama minimal 3 karakter',
                'max_length' => 'Nama maksimal 100 karakter'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Simpan user baru
        $userData = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'name' => $this->request->getPost('name'),
            'role' => 'staff', // Default role untuk registrasi
            'is_active' => true
        ];

        // Jika ada email, tambahkan
        if ($this->request->getPost('email')) {
            $userData['email'] = $this->request->getPost('email');
        }

        try {
            $this->userModel->save($userData);
            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        // Hapus remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            unset($_COOKIE['remember_token']);
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        // Destroy session
        session()->destroy();
        
        return redirect()->to('/login')->with('success', 'Logout berhasil!');
    }

    private function setRememberMe($userId)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 hari
        
        // Simpan token di database
        $this->userModel->update($userId, ['remember_token' => $token]);
        
        // Set cookie
        setcookie('remember_token', $token, $expiry, '/');
    }
}