<?php

namespace App\Models;

use CodeIgniter\Model;

class PrincipalModel extends Model
{
    protected $table = 'master_principals';
    protected $primaryKey = 'id';
    protected $allowedFields = ['principal_name', 'is_active'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    /**
     * Get active principals
     */
    public function getActivePrincipals()
    {
        return $this->where('is_active', 1)
            ->orderBy('principal_name', 'ASC')
            ->findAll();
    }

    /**
     * Get principal by name
     */
    public function getByName($name)
    {
        return $this->where('principal_name', $name)
            ->where('is_active', 1)
            ->first();
    }
}
