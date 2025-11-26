<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'master_accounts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['account_name', 'is_active'];
    protected $useTimestamps = false;


    /**
     * Get active accounts
     */
    public function getActiveAccounts()
    {
        return $this->where('is_active', 1)
            ->orderBy('account_name', 'ASC')
            ->findAll();
    }

    /**
     * Search accounts by name
     */
    public function search($keyword)
    {
        return $this->like('account_name', $keyword)
            ->where('is_active', 1)
            ->findAll();
    }
}
