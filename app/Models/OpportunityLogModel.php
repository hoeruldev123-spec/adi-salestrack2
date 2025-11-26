<?php

namespace App\Models;

use CodeIgniter\Model;

class OpportunityLogModel extends Model
{
    protected $table = 'opportunity_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['opportunity_id', 'user_id', 'action', 'old_value', 'new_value'];
    protected $useTimestamps = false;


    /**
     * Add log entry
     */
    public function addLog($opportunityId, $userId, $action, $oldValue = null, $newValue = null)
    {
        $data = [
            'opportunity_id' => $opportunityId,
            'user_id' => $userId,
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue
        ];

        return $this->insert($data);
    }

    /**
     * Get logs for opportunity
     */
    public function getLogsByOpportunity($opportunityId)
    {
        return $this->select('opportunity_logs.*, users.name as user_name, users.role as user_role')
            ->join('users', 'users.id = opportunity_logs.user_id')
            ->where('opportunity_logs.opportunity_id', $opportunityId)
            ->orderBy('opportunity_logs.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity($limit = 20)
    {
        return $this->select('opportunity_logs.*, users.name as user_name, opportunities.project_name')
            ->join('users', 'users.id = opportunity_logs.user_id')
            ->join('opportunities', 'opportunities.id = opportunity_logs.opportunity_id')
            ->orderBy('opportunity_logs.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
