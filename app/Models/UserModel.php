<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'name', 'email', 'password', 'role', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Get users by role
     */
    public function getByRole($role)
    {
        return $this->where('role', $role)
            ->where('is_active', 1)
            ->findAll();
    }

    /**
     * Get active sales users
     */
    public function getActiveSales()
    {
        return $this->where('role', 'sales')
            ->where('is_active', 1)
            ->findAll();
    }

    /**
     * Find user by username or email
     */
    public function findByUsernameOrEmail($username)
    {
        return $this->where('username', $username)
            ->orWhere('email', $username)
            ->first();
    }
}
