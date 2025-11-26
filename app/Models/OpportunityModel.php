<?php

namespace App\Models;

use CodeIgniter\Model;

class OpportunityModel extends Model
{
    protected $table = 'opportunities';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sales_id',
        'account_id',
        'principal_id',
        'solution_architect_id',
        'pic',
        'project_name',
        'license_type_id',
        'services_license',
        'closing_date',
        'progress',
        'opportunity_status',
        'progress_percentage',
        'deal_reg',
        'estimated_value',
        'remarks'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at'; // Tambahkan ini
    protected $useSoftDeletes = true;       // Tambahkan ini


    /**
     * Get opportunities with related data
     */
    public function getOpportunitiesWithRelations($conditions = [])
    {
        $builder = $this->select('opportunities.*, 
                                 users.name as sales_name,
                                 master_accounts.account_name,
                                 master_principals.principal_name,
                                 master_solution_architects.sa_name,
                                 master_license_types.license_type')
            ->join('users', 'users.id = opportunities.sales_id')
            ->join('master_accounts', 'master_accounts.id = opportunities.account_id')
            ->join('master_principals', 'master_principals.id = opportunities.principal_id')
            ->join('master_solution_architects', 'master_solution_architects.id = opportunities.solution_architect_id', 'left')
            ->join('master_license_types', 'master_license_types.id = opportunities.license_type_id', 'left');

        // Apply conditions
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $builder->where($field, $value);
            }
        }

        return $builder->orderBy('opportunities.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get opportunities by sales ID
     */
    public function getBySalesId($salesId)
    {
        return $this->getOpportunitiesWithRelations(['sales_id' => $salesId]);
    }

    /**
     * Get opportunity by ID with relations
     */
    public function getByIdWithRelations($id)
    {
        return $this->select('opportunities.*, 
                             users.name as sales_name,
                             master_accounts.account_name,
                             master_principals.principal_name,
                             master_solution_architects.sa_name,
                             master_license_types.license_type')
            ->join('users', 'users.id = opportunities.sales_id')
            ->join('master_accounts', 'master_accounts.id = opportunities.account_id')
            ->join('master_principals', 'master_principals.id = opportunities.principal_id')
            ->join('master_solution_architects', 'master_solution_architects.id = opportunities.solution_architect_id', 'left')
            ->join('master_license_types', 'master_license_types.id = opportunities.license_type_id', 'left')
            ->where('opportunities.id', $id)
            ->first();
    }

    /**
     * Get opportunities by status
     */
    public function getByStatus($status)
    {
        return $this->where('opportunity_status', $status)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get recent opportunities
     */
    public function getRecentOpportunities($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get opportunities count by status
     */
    public function getCountByStatus()
    {
        return $this->select('opportunity_status, COUNT(*) as count')
            ->groupBy('opportunity_status')
            ->findAll();
    }

    /**
     * Get total estimated value by sales
     */
    public function getTotalValueBySales()
    {
        return $this->select('users.name as sales_name, SUM(opportunities.estimated_value) as total_value')
            ->join('users', 'users.id = opportunities.sales_id')
            ->groupBy('opportunities.sales_id')
            ->findAll();
    }

    /**
     * Update opportunity status and progress
     */
    public function updateProgress($id, $status, $progressPercentage)
    {
        return $this->update($id, [
            'opportunity_status' => $status,
            'progress_percentage' => $progressPercentage,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
