<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'password', 'name', 'email', 'role', 'is_active', 'remember_token', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
        'password' => 'required|min_length[6]',
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'permit_empty|valid_email|is_unique[users.email]',
        'role'     => 'required|in_list[admin,staff]'
    ];
    
    protected $validationMessages   = [
        'username' => [
            'is_unique' => 'Username sudah digunakan'
        ],
        'email' => [
            'is_unique' => 'Email sudah digunakan'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        
        return $data;
    }

    // Method untuk mendapatkan user by username
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}