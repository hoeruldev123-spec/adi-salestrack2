<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesPerformanceModel extends Model
{
    protected $table = 'sales_performance';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sales_id',
        'total_deals',
        'won_deals',
        'lost_deals',
        'win_rate',
        'total_revenue',
        'avg_deal_size',
        'performance_rank',
        'quota_achievement'
    ];
    protected $useTimestamps = true;

    public function getTeamPerformanceSummary()
    {
        return $this->select('
        COUNT(sales_profiles.id) as total_sales,
        AVG(sales_performance.win_rate) as avg_win_rate,
        SUM(sales_performance.total_revenue) as team_total_revenue,
        MAX(sales_performance.win_rate) as max_win_rate
    ')
            ->join('sales_performance', 'sales_performance.sales_id = sales_profiles.sales_id')
            ->where('sales_profiles.is_active', 1)
            ->get()
            ->getRow();
    }

    public function getSalesLeaderboard($limit = 5)
    {
        return $this->select('sales_profiles.*, sales_performance.*')
            ->join('sales_performance', 'sales_performance.sales_id = sales_profiles.sales_id')
            ->where('sales_profiles.is_active', 1)
            ->orderBy('sales_performance.win_rate', 'DESC')
            ->orderBy('sales_performance.total_revenue', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
