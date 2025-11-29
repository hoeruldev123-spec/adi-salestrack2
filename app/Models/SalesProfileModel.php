<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesProfileModel extends Model
{
    protected $table = 'sales_profiles';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sales_id',
        'sales_name',
        'sales_email',
        'sales_phone',
        'join_date',
        'location',
        'specialization',
        'profile_picture',
        'is_active'
    ];
    protected $useTimestamps = true;

    public function getTopPerformer()
    {
        return $this->select('sales_profiles.*, sales_performance.win_rate, sales_performance.total_deals, sales_performance.total_revenue, sales_performance.performance_rank')
            ->join('sales_performance', 'sales_performance.sales_id = sales_profiles.sales_id')
            ->where('sales_profiles.is_active', 1)
            ->orderBy('sales_performance.win_rate', 'DESC')
            ->first();
    }

    public function getAllSalesWithPerformance()
    {
        return $this->select('sales_profiles.*, sales_performance.win_rate, sales_performance.total_deals, sales_performance.total_revenue, sales_performance.performance_rank')
            ->join('sales_performance', 'sales_performance.sales_id = sales_profiles.sales_id')
            ->where('sales_profiles.is_active', 1)
            ->orderBy('sales_performance.win_rate', 'DESC')
            ->findAll();
    }
}
