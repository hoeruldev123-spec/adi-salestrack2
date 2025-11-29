<?php

namespace App\Controllers\Report;

use App\Models\AccountModel;
use App\Models\PrincipalModel;

class SalesReportController extends BaseReportController
{
    protected $accountModel;
    protected $principalModel;

    public function __construct()
    {
        parent::__construct();
        $this->accountModel = new AccountModel();
        $this->principalModel = new PrincipalModel();
    }

    public function activity()
    {
        [$startDate, $endDate] = $this->getDateRange();
        $filters = $this->getFilters();

        // Get opportunities within date range
        $opportunities = $this->opportunityModel
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->findAll();

        // Apply additional filters
        if (!empty($filters['sales_id'])) {
            $opportunities = array_filter($opportunities, function ($opp) use ($filters) {
                return $opp['sales_id'] == $filters['sales_id'];
            });
        }

        // Calculate metrics
        $totalOpportunities = count($opportunities);
        $totalValue = array_sum(array_column($opportunities, 'estimated_value'));
        $wonDeals = array_filter($opportunities, function ($opp) {
            return $opp['opportunity_status'] === 'closed_won';
        });
        $winRate = $this->calculatePercentage(count($wonDeals), $totalOpportunities);

        $data = [
            'title' => 'Sales Activity Report',
            'active_menu' => 'reports',
            'active_submenu' => 'sales-activity',
            'opportunities' => $opportunities,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'filters' => $filters,
            'metrics' => [
                'total_opportunities' => $totalOpportunities,
                'total_value' => $totalValue,
                'won_deals' => count($wonDeals),
                'win_rate' => $winRate,
                'average_deal_size' => $totalOpportunities > 0 ? $totalValue / $totalOpportunities : 0
            ],
            'sales_people' => $this->userModel->where('role', 'sales')->where('is_active', 1)->findAll(),
            'accounts' => $this->accountModel->findAll(),
            'principals' => $this->principalModel->findAll()
        ];

        return $this->render('report/sales/activity', $data);
    }

    public function performance()
    {
        [$startDate, $endDate] = $this->getDateRange();

        // Get sales performance data
        $salesPerformance = $this->opportunityModel
            ->select('users.id, users.name, 
                     COUNT(opportunities.id) as total_opportunities,
                     SUM(opportunities.estimated_value) as total_value,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as won_value,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_won" THEN 1 ELSE NULL END) as won_count')
            ->join('users', 'users.id = opportunities.sales_id')
            ->where('opportunities.created_at >=', $startDate)
            ->where('opportunities.created_at <=', $endDate . ' 23:59:59')
            ->where('users.role', 'sales')
            ->where('users.is_active', 1)
            ->groupBy('users.id, users.name')
            ->findAll();

        // Calculate additional metrics
        foreach ($salesPerformance as &$sales) {
            $sales['win_rate'] = $this->calculatePercentage($sales['won_count'], $sales['total_opportunities']);
            $sales['average_deal_size'] = $sales['total_opportunities'] > 0
                ? $sales['total_value'] / $sales['total_opportunities']
                : 0;
        }

        $data = [
            'title' => 'Sales Performance Report',
            'active_menu' => 'reports',
            'active_submenu' => 'sales-performance',
            'sales_performance' => $salesPerformance,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_metrics' => [
                'total_sales' => count($salesPerformance),
                'total_opportunities' => array_sum(array_column($salesPerformance, 'total_opportunities')),
                'total_value' => array_sum(array_column($salesPerformance, 'total_value')),
                'total_won_value' => array_sum(array_column($salesPerformance, 'won_value'))
            ]
        ];

        return $this->render('report/sales/performance', $data);
    }

    public function pipeline()
    {
        [$startDate, $endDate] = $this->getDateRange();

        // Pipeline by status
        $pipelineByStatus = $this->opportunityModel
            ->select('opportunity_status, COUNT(*) as count, SUM(estimated_value) as value')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->where('opportunity_status !=', 'closed_lost')
            ->groupBy('opportunity_status')
            ->findAll();

        // Pipeline by month
        $pipelineByMonth = $this->opportunityModel
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, 
                     COUNT(*) as count, 
                     SUM(estimated_value) as value")
            ->where('created_at >=', date('Y-01-01')) // Current year
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Sales Pipeline Report',
            'active_menu' => 'reports',
            'active_submenu' => 'sales-pipeline',
            'pipeline_by_status' => $pipelineByStatus,
            'pipeline_by_month' => $pipelineByMonth,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_pipeline' => [
                'count' => array_sum(array_column($pipelineByStatus, 'count')),
                'value' => array_sum(array_column($pipelineByStatus, 'value'))
            ]
        ];

        return $this->render('report/sales/pipeline', $data);
    }

    public function export()
    {
        $type = $this->request->getPost('export_type');
        $report = $this->request->getPost('report_type');

        // TODO: Implement export functionality (PDF, Excel)
        return redirect()->back()->with('success', 'Export feature coming soon!');
    }
}
