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

    // Validation rules
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'name' => 'required|min_length[3]|max_length[200]',
        'email' => 'required|valid_email|max_length[200]|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[admin,manager,sales,BOD]', // HAPUS 'staff', GANTI DENGAN 'sales'
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Username sudah digunakan'
        ],
        'email' => [
            'is_unique' => 'Email sudah terdaftar'
        ],
    ];

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

    /**
     * Get allowed roles based on current user role
     */
    public function getAllowedRolesForRole($role)
    {
        if ($role === 'admin') {
            return ['admin', 'manager', 'sales', 'bod'];
        } elseif ($role === 'manager') {
            return ['sales']; // Manager hanya bisa tambah/edit sales
        }
        return [];
    }

    /**
     * Check if manager can modify user
     */
    public function canManagerModify($managerRole, $targetUserRole)
    {
        if ($managerRole === 'manager' && $targetUserRole === 'sales') {
            return true;
        }
        return false;
    }

    /**
     * Check if current user can create user with specific role
     */
    public function canCreateRole($currentRole, $targetRole)
    {
        $allowedRoles = $this->getAllowedRolesForRole($currentRole);
        return in_array($targetRole, $allowedRoles);
    }
}
