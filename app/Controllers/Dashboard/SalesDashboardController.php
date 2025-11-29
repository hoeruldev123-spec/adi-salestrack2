<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\OpportunityModel;
use App\Models\SalesProfileModel;

class SalesDashboardController extends BaseController
{
    protected $opportunityModel;
    protected $salesProfileModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Initialize models
        $this->opportunityModel = new OpportunityModel();
        $this->salesProfileModel = new SalesProfileModel();
    }

    public function index()
    {
        // Get current sales ID dari session atau auth
        $currentSalesId = session()->get('user_id'); // Sesuaikan dengan auth system Anda

        // Sales-specific metrics
        $totalOpportunities = $this->opportunityModel
            ->where('sales_id', $currentSalesId)
            ->countAllResults();

        $totalRevenue = $this->opportunityModel
            ->selectSum('estimated_value')
            ->where('sales_id', $currentSalesId)
            ->where('opportunity_status', 'closed_won')
            ->get()
            ->getRow()->estimated_value ?? 0;

        $wonDeals = $this->opportunityModel
            ->where('sales_id', $currentSalesId)
            ->where('opportunity_status', 'closed_won')
            ->countAllResults();

        // Calculate Average Deal Size
        $avgDealSizeResult = $this->opportunityModel
            ->selectAvg('estimated_value')
            ->where('sales_id', $currentSalesId)
            ->where('opportunity_status', 'closed_won')
            ->get()
            ->getRow();
        $avg_deal_size = $avgDealSizeResult->estimated_value ?? 0;

        // Recent Opportunities (5 terbaru)
        $recent_opportunities = $this->opportunityModel
            ->select('opportunities.*, master_accounts.account_name, master_principals.principal_name')
            ->join('master_accounts', 'master_accounts.id = opportunities.account_id', 'left')
            ->join('master_principals', 'master_principals.id = opportunities.principal_id', 'left')
            ->where('opportunities.sales_id', $currentSalesId)
            ->orderBy('opportunities.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // DATA UNTUK CHARTS
        // Status Distribution
        $statusDistribution = $this->opportunityModel
            ->select('opportunity_status, COUNT(*) as count')
            ->where('sales_id', $currentSalesId)
            ->groupBy('opportunity_status')
            ->findAll();

        // Monthly Performance 
        $monthlyPerformance = $this->opportunityModel
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('sales_id', $currentSalesId)
            ->where('created_at >=', date('Y-m-01', strtotime('-11 months')))
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month', 'ASC')
            ->findAll();

        // Revenue by Principal
        $revenueByPrincipal = $this->opportunityModel
            ->select('master_principals.principal_name, SUM(opportunities.estimated_value) as revenue')
            ->join('master_principals', 'master_principals.id = opportunities.principal_id')
            ->where('opportunities.sales_id', $currentSalesId)
            ->where('opportunities.opportunity_status', 'closed_won')
            ->groupBy('master_principals.id, master_principals.principal_name')
            ->orderBy('revenue', 'DESC')
            ->findAll();

        // Annual Data
        $annualData = $this->opportunityModel
            ->select("YEAR(created_at) as year, COUNT(*) as count")
            ->where('sales_id', $currentSalesId)
            ->groupBy("YEAR(created_at)")
            ->orderBy('year', 'ASC')
            ->findAll();

        // Process data untuk charts
        $data['statusLabels'] = array_map(function ($item) {
            return ucfirst(str_replace('_', ' ', $item['opportunity_status']));
        }, $statusDistribution);

        $data['statusData'] = array_column($statusDistribution, 'count');

        $data['monthlyLabels'] = array_map(function ($item) {
            $date = \DateTime::createFromFormat('Y-m', $item['month']);
            return $date ? $date->format('M Y') : $item['month'];
        }, $monthlyPerformance);

        $data['monthlyData'] = array_column($monthlyPerformance, 'count');

        $data['revenueLabels'] = array_column($revenueByPrincipal, 'principal_name');
        $data['revenueData'] = array_map(function ($item) {
            return $item['revenue'] / 1000000; // Convert to millions
        }, $revenueByPrincipal);

        $data['annualLabels'] = array_column($annualData, 'year');
        $data['annualData'] = array_column($annualData, 'count');

        $data['title'] = 'Dashboard Sales';
        $data['active_menu'] = 'dashboard';
        $data['total_opportunities'] = $totalOpportunities;
        $data['total_revenue'] = $totalRevenue;
        $data['won_deals'] = $wonDeals;
        $data['avg_deal_size'] = $avg_deal_size;
        $data['win_rate'] = $totalOpportunities > 0 ? ($wonDeals / $totalOpportunities) * 100 : 0;
        $data['recent_opportunities'] = $recent_opportunities;

        return $this->render('dashboard/sales', $data);
    }

    // Method untuk mendapatkan data status distribution
    private function getStatusDistribution($salesId = null)
    {
        $builder = $this->opportunityModel;

        if ($salesId) {
            $builder->where('sales_id', $salesId);
        }

        return $builder
            ->select('opportunity_status, COUNT(*) as count')
            ->groupBy('opportunity_status')
            ->get()
            ->getResultArray();
    }

    // Method untuk mendapatkan monthly progress
    private function getMonthlyProgress($salesId = null)
    {
        $builder = $this->opportunityModel;

        if ($salesId) {
            $builder->where('sales_id', $salesId);
        }

        return $builder
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Method untuk mendapatkan revenue by principal
    private function getRevenueByPrincipal($salesId = null)
    {
        $builder = $this->opportunityModel
            ->select('master_principals.principal_name, SUM(opportunities.estimated_value) as revenue')
            ->join('master_principals', 'master_principals.id = opportunities.principal_id')
            ->where('opportunities.opportunity_status', 'closed_won');

        if ($salesId) {
            $builder->where('opportunities.sales_id', $salesId);
        }

        return $builder
            ->groupBy('master_principals.id, master_principals.principal_name')
            ->get()
            ->getResultArray();
    }
}
