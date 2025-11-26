<?php

namespace App\Models;

use CodeIgniter\Model;

class LicenseTypeModel extends Model
{
    protected $table = 'master_license_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['license_type', 'is_active'];
    protected $useTimestamps = false;


    /**
     * Get active license types
     */
    public function getActiveLicenseTypes()
    {
        return $this->where('is_active', 1)
            ->orderBy('license_type', 'ASC')
            ->findAll();
    }

    /**
     * Get license type by name
     */
    public function getByName($name)
    {
        return $this->where('license_type', $name)
            ->where('is_active', 1)
            ->first();
    }
}
