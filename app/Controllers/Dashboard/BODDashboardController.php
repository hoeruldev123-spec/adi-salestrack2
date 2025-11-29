<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\UserModel;

class BODDashboardController extends BaseController
{
    protected $userModel;
    protected $opportunityModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Initialize models
        $this->userModel = new UserModel();
        $this->opportunityModel = new \App\Models\OpportunityModel();
    }

    public function index()
    {
        // Strategic metrics
        $totalRevenue = $this->opportunityModel
            ->selectSum('estimated_value')
            ->where('opportunity_status', 'closed_won')
            ->get()
            ->getRow()->estimated_value ?? 0;

        // Quarterly Performance
        $quarterlyPerformance = $this->opportunityModel
            ->select("CONCAT('Q', QUARTER(created_at), ' ', YEAR(created_at)) as quarter,
                     SUM(CASE WHEN opportunity_status = 'closed_won' THEN estimated_value ELSE 0 END) as revenue,
                     COUNT(CASE WHEN opportunity_status = 'closed_won' THEN 1 ELSE NULL END) as deals")
            ->where('created_at >=', date('Y-01-01', strtotime('-1 year')))
            ->groupBy('YEAR(created_at), QUARTER(created_at)')
            ->orderBy('YEAR(created_at) DESC, QUARTER(created_at) DESC')
            ->findAll();

        // Revenue by Account Size
        $revenueByAccountSize = $this->getRevenueByAccountSize();

        // Growth Metrics
        $growthMetrics = $this->calculateGrowthMetrics();

        $data = [
            'title' => 'Dashboard BOD',
            'active_menu' => 'dashboard',
            'total_revenue' => $totalRevenue,
            'quarterly_performance' => $quarterlyPerformance,
            'revenue_by_account_size' => $revenueByAccountSize,
            'growth_metrics' => $growthMetrics,
            'strategic_metrics' => [
                'total_opportunities' => $this->opportunityModel->countAll(),
                'active_sales' => $this->userModel->where('role', 'sales')->where('is_active', 1)->countAllResults(),
                'avg_deal_size' => $this->calculateAverageDealSize(),
                'customer_acquisition' => $this->calculateCustomerAcquisition()
            ]
        ];

        return $this->render('dashboard/bod', $data);
    }

    private function getRevenueByAccountSize()
    {
        $accounts = $this->opportunityModel
            ->select('master_accounts.account_name,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as revenue')
            ->join('master_accounts', 'master_accounts.id = opportunities.account_id')
            ->groupBy('master_accounts.id, master_accounts.account_name')
            ->findAll();

        $segments = [
            'enterprise' => ['min' => 5000000000, 'count' => 0, 'revenue' => 0],
            'corporate' => ['min' => 1000000000, 'max' => 5000000000, 'count' => 0, 'revenue' => 0],
            'business' => ['min' => 500000000, 'max' => 1000000000, 'count' => 0, 'revenue' => 0],
            'smb' => ['min' => 0, 'max' => 500000000, 'count' => 0, 'revenue' => 0]
        ];

        foreach ($accounts as $account) {
            $revenue = $account['revenue'];
            if ($revenue >= $segments['enterprise']['min']) {
                $segments['enterprise']['count']++;
                $segments['enterprise']['revenue'] += $revenue;
            } elseif ($revenue >= $segments['corporate']['min']) {
                $segments['corporate']['count']++;
                $segments['corporate']['revenue'] += $revenue;
            } elseif ($revenue >= $segments['business']['min']) {
                $segments['business']['count']++;
                $segments['business']['revenue'] += $revenue;
            } else {
                $segments['smb']['count']++;
                $segments['smb']['revenue'] += $revenue;
            }
        }

        return $segments;
    }

    private function calculateGrowthMetrics()
    {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        $currentYearRevenue = $this->opportunityModel
            ->selectSum('estimated_value')
            ->where('opportunity_status', 'closed_won')
            ->where('YEAR(created_at)', $currentYear)
            ->get()
            ->getRow()->estimated_value ?? 0;

        $previousYearRevenue = $this->opportunityModel
            ->selectSum('estimated_value')
            ->where('opportunity_status', 'closed_won')
            ->where('YEAR(created_at)', $previousYear)
            ->get()
            ->getRow()->estimated_value ?? 0;

        return [
            'yoy_growth' => $previousYearRevenue > 0 ?
                (($currentYearRevenue - $previousYearRevenue) / $previousYearRevenue) * 100 : 0,
            'current_year' => $currentYearRevenue,
            'previous_year' => $previousYearRevenue
        ];
    }

    private function calculateAverageDealSize()
    {
        $result = $this->opportunityModel
            ->selectAvg('estimated_value')
            ->where('opportunity_status', 'closed_won')
            ->get()
            ->getRow();

        return $result ? $result->estimated_value : 0;
    }

    private function calculateCustomerAcquisition()
    {
        $totalAccounts = $this->opportunityModel
            ->select('COUNT(DISTINCT account_id) as unique_accounts')
            ->where('opportunity_status', 'closed_won')
            ->get()
            ->getRow()->unique_accounts ?? 0;

        $totalOpportunities = $this->opportunityModel->countAll();

        return $totalOpportunities > 0 ? ($totalAccounts / $totalOpportunities) * 100 : 0;
    }
}
