<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login - Inventory System',
            'pageTitle' => 'Login',
            'validation' => $this->validation
        ];

        if ($this->request->getMethod() === 'post') {
            return $this->processLogin();
        }

        return view('auth/login', $data);
    }

    private function processLogin()
    {
        // Validation rules
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]'
        ];

        $messages = [
            'username' => [
                'required' => 'Username harus diisi',
                'min_length' => 'Username minimal 3 karakter'
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 6 karakter'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Attempt login
        $user = $this->userModel->getUserByUsername($username);
        
        if (!$user) {
            // Coba login dengan email
            $user = $this->userModel->getUserByEmail($username);
        }

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            // Set session data
            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'logged_in' => true
            ];

            $this->session->set($sessionData);

            // Set remember me cookie jika dipilih
            if ($remember) {
                $this->setRememberMe($user['id']);
            }

            // Log login activity
            $this->logLoginActivity($user['id']);

            // Redirect berdasarkan role
            return $this->redirectAfterLogin($user['role']);
        } else {
            return redirect()->back()->withInput()->with('error', 'Username/Email atau password salah');
        }
    }

    public function logout()
    {
        // Hapus remember me cookie
        $this->clearRememberMe();
        
        // Hapus session
        $this->session->destroy();
        
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout');
    }

    private function setRememberMe($userId)
    {
        $token = bin2hex(random_bytes(32));
        $expire = time() + (30 * 24 * 60 * 60); // 30 hari
        
        // Simpan token di database
        $db = \Config\Database::connect();
        $db->table('user_tokens')->insert([
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', $expire),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Set cookie
        set_cookie('remember_token', $token, $expire);
    }

    private function clearRememberMe()
    {
        if ($cookie = get_cookie('remember_token')) {
            $db = \Config\Database::connect();
            $db->table('user_tokens')->where('token', $cookie)->delete();
            delete_cookie('remember_token');
        }
    }

    private function logLoginActivity($userId)
    {
        $db = \Config\Database::connect();
        $db->table('login_activities')->insert([
            'user_id' => $userId,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'login_time' => date('Y-m-d H:i:s')
        ]);
    }

    private function redirectAfterLogin($role)
    {
        $redirectUrl = session('redirect_url') ?? '/dashboard';
        
        // Clear redirect URL dari session
        session()->remove('redirect_url');

        return redirect()->to($redirectUrl)->with('success', 'Login berhasil! Selamat datang kembali.');
    }

    public function checkRememberMe()
    {
        if ($cookie = get_cookie('remember_token')) {
            $db = \Config\Database::connect();
            $token = $db->table('user_tokens')
                        ->where('token', $cookie)
                        ->where('expires_at >', date('Y-m-d H:i:s'))
                        ->get()
                        ->getRow();

            if ($token) {
                $user = $this->userModel->find($token->user_id);
                if ($user && $user['is_active']) {
                    $sessionData = [
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'logged_in' => true
                    ];
                    $this->session->set($sessionData);
                    return true;
                }
            }
        }
        return false;
    }
}