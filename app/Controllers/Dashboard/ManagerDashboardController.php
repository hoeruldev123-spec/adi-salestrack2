<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ManagerDashboardController extends BaseController
{
    protected $userModel;
    protected $opportunityModel;
    protected $salesProfileModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Initialize models
        $this->userModel = new UserModel();
        $this->opportunityModel = new \App\Models\OpportunityModel();
        $this->salesProfileModel = new \App\Models\SalesProfileModel();
    }

    public function index()
    {
        // Company-wide metrics
        $totalOpportunities = $this->opportunityModel->countAll();
        $totalRevenue = $this->opportunityModel
            ->selectSum('estimated_value')
            ->where('opportunity_status', 'closed_won')
            ->get()
            ->getRow()->estimated_value ?? 0;

        // Team Performance
        $teamPerformance = $this->opportunityModel
            ->select('users.name, 
                     COUNT(opportunities.id) as total_opportunities,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as revenue,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_won" THEN 1 ELSE NULL END) as won_deals')
            ->join('users', 'users.id = opportunities.sales_id')
            ->where('users.role', 'sales')
            ->where('users.is_active', 1)
            ->groupBy('users.id, users.name')
            ->orderBy('revenue', 'DESC')
            ->findAll();

        // Pipeline by Status (Company-wide)
        $pipelineStatus = $this->opportunityModel
            ->select('opportunity_status, COUNT(*) as count, SUM(estimated_value) as value')
            ->where('opportunity_status !=', 'closed_lost')
            ->groupBy('opportunity_status')
            ->findAll();

        // Monthly Performance (Company-wide)
        $monthlyPerformance = $this->opportunityModel
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, 
                     COUNT(*) as opportunities,
                     SUM(CASE WHEN opportunity_status = 'closed_won' THEN estimated_value ELSE 0 END) as revenue")
            ->where('created_at >=', date('Y-m-01', strtotime('-5 months')))
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month', 'ASC')
            ->findAll();

        // Principal Performance
        $principalPerformance = $this->opportunityModel
            ->select('master_principals.principal_name,
                     COUNT(opportunities.id) as opportunities,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as revenue')
            ->join('master_principals', 'master_principals.id = opportunities.principal_id')
            ->groupBy('master_principals.id, master_principals.principal_name')
            ->orderBy('revenue', 'DESC')
            ->limit(8)
            ->findAll();

        // DATA SALES TERBAIK - Ambil dari database
        $topSales = $this->salesProfileModel->getTopPerformer();

        // Jika tidak ada data dari sales_profile, ambil dari team performance
        if (!$topSales && !empty($teamPerformance)) {
            $bestSales = $teamPerformance[0]; // Sales dengan revenue tertinggi
            $topSales = [
                'sales_name' => $bestSales['name'],
                'sales_email' => strtolower(str_replace(' ', '.', $bestSales['name'])) . '@company.com',
                'sales_phone' => '+62 8XX-XXXX-XXXX',
                'location' => 'Indonesia',
                'specialization' => 'Sales Executive',
                'total_deals' => $bestSales['total_opportunities'],
                'won_deals' => $bestSales['won_deals'],
                'win_rate' => $bestSales['total_opportunities'] > 0 ?
                    ($bestSales['won_deals'] / $bestSales['total_opportunities']) * 100 : 0,
                'total_revenue' => $bestSales['revenue'],
                'performance_rank' => 1
            ];
        }

        $data = [
            'title' => 'Dashboard Manager',
            'active_menu' => 'dashboard',
            'total_opportunities' => $totalOpportunities,
            'total_revenue' => $totalRevenue,
            'team_performance' => $teamPerformance,
            'pipeline_status' => $pipelineStatus,
            'monthly_performance' => $monthlyPerformance,
            'principal_performance' => $principalPerformance,
            'active_sales' => $this->userModel->where('role', 'sales')->where('is_active', 1)->countAllResults(),
            'win_rate' => $this->calculateCompanyWinRate(),
            'top_sales' => $topSales // Tambahkan data sales terbaik
        ];

        return $this->render('dashboard/manager', $data);
    }

    private function calculateCompanyWinRate()
    {
        $wonDeals = $this->opportunityModel
            ->where('opportunity_status', 'closed_won')
            ->countAllResults();

        $lostDeals = $this->opportunityModel
            ->where('opportunity_status', 'closed_lost')
            ->countAllResults();

        $total = $wonDeals + $lostDeals;
        return $total > 0 ? ($wonDeals / $total) * 100 : 0;
    }

    // Backup method untuk mendapatkan top sales dari opportunities
    private function getTopSalesFromOpportunities()
    {
        return $this->opportunityModel
            ->select('users.id as sales_id, 
                     users.name as sales_name,
                     COUNT(opportunities.id) as total_deals,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_won" THEN 1 ELSE NULL END) as won_deals,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as total_revenue')
            ->join('users', 'users.id = opportunities.sales_id')
            ->where('users.role', 'sales')
            ->where('users.is_active', 1)
            ->groupBy('users.id, users.name')
            ->orderBy('total_revenue', 'DESC')
            ->first();
    }
}
