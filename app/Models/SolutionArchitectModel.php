<?php

namespace App\Models;

use CodeIgniter\Model;

class SolutionArchitectModel extends Model
{
    protected $table = 'master_solution_architects';
    protected $primaryKey = 'id';
    protected $allowedFields = ['sa_name', 'is_active'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    /**
     * Get active solution architects
     */
    public function getActiveSA()
    {
        return $this->where('is_active', 1)
            ->orderBy('sa_name', 'ASC')
            ->findAll();
    }

    /**
     * Count opportunities by SA
     */
    public function getOpportunityCount()
    {
        return $this->select('master_solution_architects.*, COUNT(opportunities.id) as opportunity_count')
            ->join('opportunities', 'opportunities.solution_architect_id = master_solution_architects.id', 'left')
            ->where('master_solution_architects.is_active', 1)
            ->groupBy('master_solution_architects.id')
            ->findAll();
    }
}
