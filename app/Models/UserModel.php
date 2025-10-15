<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'name', 'role', 'email', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        return $data;
    }

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)
                    ->where('is_active', 1)
                    ->first();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)
                    ->where('is_active', 1)
                    ->first();
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function getUsersByRole($role = null)
    {
        if ($role) {
            return $this->where('role', $role)
                        ->where('is_active', 1)
                        ->findAll();
        }
        return $this->where('is_active', 1)->findAll();
    }
}