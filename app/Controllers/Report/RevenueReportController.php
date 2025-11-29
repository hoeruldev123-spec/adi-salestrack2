<?php

namespace App\Controllers\Report;

use App\Models\AccountModel;
use App\Models\PrincipalModel;

class RevenueReportController extends BaseReportController
{
    protected $accountModel;
    protected $principalModel;

    public function __construct()
    {
        parent::__construct();
        $this->accountModel = new AccountModel();
        $this->principalModel = new PrincipalModel();
    }

    public function overview()
    {
        [$startDate, $endDate] = $this->getDateRange();
        $filters = $this->getFilters();

        // Revenue by month (last 12 months)
        $revenueByMonth = $this->opportunityModel
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, 
                     SUM(CASE WHEN opportunity_status = 'closed_won' THEN estimated_value ELSE 0 END) as revenue,
                     COUNT(CASE WHEN opportunity_status = 'closed_won' THEN 1 ELSE NULL END) as deals_count,
                     COUNT(*) as total_opportunities,
                     SUM(estimated_value) as total_pipeline")
            ->where('created_at >=', date('Y-m-01', strtotime('-11 months')))
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month', 'ASC')
            ->findAll();

        // Revenue by principal
        $revenueByPrincipal = $this->opportunityModel
            ->select('master_principals.principal_name,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as revenue,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_won" THEN 1 ELSE NULL END) as deals_count')
            ->join('master_principals', 'master_principals.id = opportunities.principal_id')
            ->where('opportunities.created_at >=', $startDate)
            ->where('opportunities.created_at <=', $endDate . ' 23:59:59')
            ->groupBy('master_principals.id, master_principals.principal_name')
            ->orderBy('revenue', 'DESC')
            ->findAll();

        // Revenue by account
        $revenueByAccount = $this->opportunityModel
            ->select('master_accounts.account_name,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as revenue,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_won" THEN 1 ELSE NULL END) as deals_count')
            ->join('master_accounts', 'master_accounts.id = opportunities.account_id')
            ->where('opportunities.created_at >=', $startDate)
            ->where('opportunities.created_at <=', $endDate . ' 23:59:59')
            ->groupBy('master_accounts.id, master_accounts.account_name')
            ->orderBy('revenue', 'DESC')
            ->limit(10)
            ->findAll();

        // Calculate totals
        $totalRevenue = array_sum(array_column($revenueByMonth, 'revenue'));
        $totalDeals = array_sum(array_column($revenueByMonth, 'deals_count'));
        $averageDealSize = $totalDeals > 0 ? $totalRevenue / $totalDeals : 0;

        $data = [
            'title' => 'Revenue Overview Report',
            'active_menu' => 'reports',
            'active_submenu' => 'revenue-overview',
            'revenue_by_month' => $revenueByMonth,
            'revenue_by_principal' => $revenueByPrincipal,
            'revenue_by_account' => $revenueByAccount,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'filters' => $filters,
            'metrics' => [
                'total_revenue' => $totalRevenue,
                'total_deals' => $totalDeals,
                'average_deal_size' => $averageDealSize,
                'current_month_revenue' => $this->getCurrentMonthRevenue(),
                'previous_month_revenue' => $this->getPreviousMonthRevenue(),
                'growth_rate' => $this->calculateGrowthRate()
            ]
        ];

        return $this->render('report/revenue/overview', $data);
    }

    public function forecast()
    {
        [$startDate, $endDate] = $this->getDateRange();

        // Revenue forecast by status
        $forecastByStatus = $this->opportunityModel
            ->select('opportunity_status, 
                     COUNT(*) as deal_count,
                     SUM(estimated_value) as potential_value,
                     AVG(progress_percentage) as avg_progress')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->where('opportunity_status !=', 'closed_lost')
            ->groupBy('opportunity_status')
            ->findAll();

        // Forecast by month (next 6 months)
        $currentMonth = date('Y-m');
        $forecastMonths = [];
        for ($i = 0; $i < 6; $i++) {
            $month = date('Y-m', strtotime("+$i months"));
            $forecastMonths[] = [
                'month' => $month,
                'forecast' => $this->calculateMonthlyForecast($month)
            ];
        }

        // Win probability analysis
        $winProbability = $this->calculateWinProbability();

        $data = [
            'title' => 'Revenue Forecast Report',
            'active_menu' => 'reports',
            'active_submenu' => 'revenue-forecast',
            'forecast_by_status' => $forecastByStatus,
            'forecast_months' => $forecastMonths,
            'win_probability' => $winProbability,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_forecast' => [
                'potential' => array_sum(array_column($forecastByStatus, 'potential_value')),
                'weighted' => $this->calculateWeightedForecast($forecastByStatus)
            ]
        ];

        return $this->render('report/revenue/forecast', $data);
    }

    public function comparison()
    {
        [$startDate, $endDate] = $this->getDateRange();
        $period = $this->request->getGet('period') ?: 'monthly';

        // Revenue comparison (current vs previous period)
        $comparisonData = $this->getRevenueComparison($startDate, $endDate, $period);

        // Year-over-year growth
        $yoyGrowth = $this->getYearOverYearGrowth();

        // Quarter performance
        $quarterPerformance = $this->getQuarterPerformance();

        $data = [
            'title' => 'Revenue Comparison Report',
            'active_menu' => 'reports',
            'active_submenu' => 'revenue-comparison',
            'comparison_data' => $comparisonData,
            'yoy_growth' => $yoyGrowth,
            'quarter_performance' => $quarterPerformance,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'period' => $period,
            'metrics' => [
                'current_period' => $comparisonData['current']['revenue'] ?? 0,
                'previous_period' => $comparisonData['previous']['revenue'] ?? 0,
                'growth_amount' => ($comparisonData['current']['revenue'] ?? 0) - ($comparisonData['previous']['revenue'] ?? 0),
                'growth_percentage' => $this->calculateGrowthRate(
                    $comparisonData['previous']['revenue'] ?? 0,
                    $comparisonData['current']['revenue'] ?? 0
                )
            ]
        ];

        return $this->render('report/revenue/comparison', $data);
    }

    /**
     * Helper methods for revenue calculations
     */
    private function getCurrentMonthRevenue()
    {
        $currentMonth = date('Y-m');
        return $this->opportunityModel
            ->selectSum('estimated_value')
            ->where('opportunity_status', 'closed_won')
            ->where("DATE_FORMAT(created_at, '%Y-%m') =", $currentMonth)
            ->get()
            ->getRow()->estimated_value ?? 0;
    }

    private function getPreviousMonthRevenue()
    {
        $previousMonth = date('Y-m', strtotime('-1 month'));
        return $this->opportunityModel
            ->selectSum('estimated_value')
            ->where('opportunity_status', 'closed_won')
            ->where("DATE_FORMAT(created_at, '%Y-%m') =", $previousMonth)
            ->get()
            ->getRow()->estimated_value ?? 0;
    }

    private function calculateGrowthRate($previous = null, $current = null)
    {
        if ($previous === null) {
            $previous = $this->getPreviousMonthRevenue();
        }
        if ($current === null) {
            $current = $this->getCurrentMonthRevenue();
        }

        if ($previous == 0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    }

    private function calculateMonthlyForecast($month)
    {
        // Simple forecast based on current pipeline and historical win rates
        $pipeline = $this->opportunityModel
            ->selectSum('estimated_value')
            ->where('opportunity_status !=', 'closed_lost')
            ->where('closing_date LIKE', $month . '%')
            ->get()
            ->getRow()->estimated_value ?? 0;

        // Apply historical win rate (adjust based on your business)
        $historicalWinRate = 0.3; // 30% win rate
        return $pipeline * $historicalWinRate;
    }

    private function calculateWinProbability()
    {
        $statusWinRates = [
            'introduction' => 0.1,
            'qualification' => 0.3,
            'proposal' => 0.6,
            'negotiation' => 0.8,
            'closed_won' => 1.0,
            'closed_lost' => 0.0
        ];

        $probabilities = [];
        foreach ($statusWinRates as $status => $winRate) {
            $opportunities = $this->opportunityModel
                ->where('opportunity_status', $status)
                ->findAll();

            $probabilities[$status] = [
                'win_rate' => $winRate * 100,
                'count' => count($opportunities),
                'potential_value' => array_sum(array_column($opportunities, 'estimated_value')),
                'weighted_forecast' => array_sum(array_column($opportunities, 'estimated_value')) * $winRate
            ];
        }

        return $probabilities;
    }

    private function calculateWeightedForecast($forecastByStatus)
    {
        $statusWeights = [
            'introduction' => 0.1,
            'qualification' => 0.3,
            'proposal' => 0.6,
            'negotiation' => 0.8
        ];

        $weightedTotal = 0;
        foreach ($forecastByStatus as $forecast) {
            $weight = $statusWeights[$forecast['opportunity_status']] ?? 0;
            $weightedTotal += $forecast['potential_value'] * $weight;
        }

        return $weightedTotal;
    }

    private function getRevenueComparison($startDate, $endDate, $period)
    {
        // Current period
        $current = $this->opportunityModel
            ->select('COUNT(*) as deals, SUM(estimated_value) as revenue')
            ->where('opportunity_status', 'closed_won')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->get()
            ->getRow();

        // Previous period
        $previousStart = date('Y-m-d', strtotime($startDate . ' -1 ' . $period));
        $previousEnd = date('Y-m-d', strtotime($endDate . ' -1 ' . $period));

        $previous = $this->opportunityModel
            ->select('COUNT(*) as deals, SUM(estimated_value) as revenue')
            ->where('opportunity_status', 'closed_won')
            ->where('created_at >=', $previousStart)
            ->where('created_at <=', $previousEnd . ' 23:59:59')
            ->get()
            ->getRow();

        return [
            'current' => (array)$current,
            'previous' => (array)$previous
        ];
    }

    private function getYearOverYearGrowth()
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
            'current_year' => $currentYearRevenue,
            'previous_year' => $previousYearRevenue,
            'growth' => $this->calculateGrowthRate($previousYearRevenue, $currentYearRevenue)
        ];
    }

    private function getQuarterPerformance()
    {
        $currentQuarter = ceil(date('n') / 3);
        $currentYear = date('Y');

        $quarters = [];
        for ($q = 1; $q <= 4; $q++) {
            $quarterRevenue = $this->opportunityModel
                ->selectSum('estimated_value')
                ->where('opportunity_status', 'closed_won')
                ->where('YEAR(created_at)', $currentYear)
                ->where('QUARTER(created_at)', $q)
                ->get()
                ->getRow()->estimated_value ?? 0;

            $quarters[$q] = $quarterRevenue;
        }

        return $quarters;
    }
}
