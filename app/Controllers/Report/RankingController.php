<?php

namespace App\Controllers\Report;

use App\Models\UserModel;
use App\Models\AccountModel;
use App\Models\PrincipalModel;

class RankingController extends BaseReportController
{
    protected $userModel;
    protected $accountModel;
    protected $principalModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->accountModel = new AccountModel();
        $this->principalModel = new PrincipalModel();
    }

    public function salesPerformance()
    {
        [$startDate, $endDate] = $this->getDateRange();
        $period = $this->request->getGet('period') ?: 'monthly';

        // Sales Performance Ranking
        $salesRanking = $this->opportunityModel
            ->select('users.id, users.name, users.email,
                     COUNT(opportunities.id) as total_opportunities,
                     SUM(opportunities.estimated_value) as total_pipeline,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as won_revenue,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_won" THEN 1 ELSE NULL END) as won_deals,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_lost" THEN 1 ELSE NULL END) as lost_deals,
                     AVG(opportunities.progress_percentage) as avg_progress')
            ->join('users', 'users.id = opportunities.sales_id')
            ->where('opportunities.created_at >=', $startDate)
            ->where('opportunities.created_at <=', $endDate . ' 23:59:59')
            ->where('users.role', 'sales')
            ->where('users.is_active', 1)
            ->groupBy('users.id, users.name, users.email')
            ->orderBy('won_revenue', 'DESC')
            ->findAll();

        // Calculate additional metrics
        foreach ($salesRanking as &$sales) {
            $sales['win_rate'] = $this->calculatePercentage(
                $sales['won_deals'],
                $sales['won_deals'] + $sales['lost_deals']
            );
            $sales['avg_deal_size'] = $sales['won_deals'] > 0
                ? $sales['won_revenue'] / $sales['won_deals']
                : 0;
            $sales['pipeline_velocity'] = $this->calculatePipelineVelocity($sales['id'], $startDate, $endDate);
        }

        // Performance trends (current vs previous period)
        $performanceTrends = $this->getSalesPerformanceTrends($salesRanking, $period);

        $data = [
            'title' => 'Sales Performance Ranking',
            'active_menu' => 'reports',
            'active_submenu' => 'sales-ranking',
            'sales_ranking' => $salesRanking,
            'performance_trends' => $performanceTrends,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'period' => $period,
            'metrics' => [
                'top_performer' => $salesRanking[0] ?? null,
                'total_sales' => count($salesRanking),
                'total_revenue' => array_sum(array_column($salesRanking, 'won_revenue')),
                'avg_win_rate' => $this->calculateAverageWinRate($salesRanking)
            ]
        ];

        return $this->render('report/ranking/sales_performance', $data);
    }

    public function principalPerformance()
    {
        [$startDate, $endDate] = $this->getDateRange();

        // Principal Performance Ranking
        $principalRanking = $this->opportunityModel
            ->select('master_principals.id, master_principals.principal_name,
                     COUNT(opportunities.id) as total_opportunities,
                     SUM(opportunities.estimated_value) as total_pipeline,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as won_revenue,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_won" THEN 1 ELSE NULL END) as won_deals,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_lost" THEN 1 ELSE NULL END) as lost_deals,
                     COUNT(DISTINCT opportunities.account_id) as unique_accounts')
            ->join('master_principals', 'master_principals.id = opportunities.principal_id')
            ->where('opportunities.created_at >=', $startDate)
            ->where('opportunities.created_at <=', $endDate . ' 23:59:59')
            ->groupBy('master_principals.id, master_principals.principal_name')
            ->orderBy('won_revenue', 'DESC')
            ->findAll();

        // Calculate additional metrics
        foreach ($principalRanking as &$principal) {
            $principal['win_rate'] = $this->calculatePercentage(
                $principal['won_deals'],
                $principal['won_deals'] + $principal['lost_deals']
            );
            $principal['avg_deal_size'] = $principal['won_deals'] > 0
                ? $principal['won_revenue'] / $principal['won_deals']
                : 0;
            $principal['account_penetration'] = $this->calculateAccountPenetration($principal['id']);
        }

        // Principal growth analysis
        $principalGrowth = $this->getPrincipalGrowthAnalysis($principalRanking);

        $data = [
            'title' => 'Principal Performance Ranking',
            'active_menu' => 'reports',
            'active_submenu' => 'principal-ranking',
            'principal_ranking' => $principalRanking,
            'principal_growth' => $principalGrowth,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'metrics' => [
                'top_principal' => $principalRanking[0] ?? null,
                'total_principals' => count($principalRanking),
                'total_revenue' => array_sum(array_column($principalRanking, 'won_revenue')),
                'most_prolific' => $this->getMostProlificPrincipal($principalRanking)
            ]
        ];

        return $this->render('report/ranking/principal_performance', $data);
    }

    public function accountPerformance()
    {
        [$startDate, $endDate] = $this->getDateRange();

        // Account Performance Ranking
        $accountRanking = $this->opportunityModel
            ->select('master_accounts.id, master_accounts.account_name,
                     COUNT(opportunities.id) as total_opportunities,
                     SUM(opportunities.estimated_value) as total_pipeline,
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as won_revenue,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_won" THEN 1 ELSE NULL END) as won_deals,
                     COUNT(CASE WHEN opportunities.opportunity_status = "closed_lost" THEN 1 ELSE NULL END) as lost_deals,
                     COUNT(DISTINCT opportunities.principal_id) as unique_principals,
                     COUNT(DISTINCT opportunities.sales_id) as unique_sales')
            ->join('master_accounts', 'master_accounts.id = opportunities.account_id')
            ->where('opportunities.created_at >=', $startDate)
            ->where('opportunities.created_at <=', $endDate . ' 23:59:59')
            ->groupBy('master_accounts.id, master_accounts.account_name')
            ->orderBy('won_revenue', 'DESC')
            ->findAll();

        // Calculate additional metrics
        foreach ($accountRanking as &$account) {
            $account['win_rate'] = $this->calculatePercentage(
                $account['won_deals'],
                $account['won_deals'] + $account['lost_deals']
            );
            $account['avg_deal_size'] = $account['won_deals'] > 0
                ? $account['won_revenue'] / $account['won_deals']
                : 0;
            $account['potential_growth'] = $this->calculateAccountGrowthPotential($account['id']);
        }

        // Account value analysis
        $accountValueSegmentation = $this->segmentAccountsByValue($accountRanking);

        $data = [
            'title' => 'Account Performance Ranking',
            'active_menu' => 'reports',
            'active_submenu' => 'account-ranking',
            'account_ranking' => $accountRanking,
            'value_segmentation' => $accountValueSegmentation,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'metrics' => [
                'top_account' => $accountRanking[0] ?? null,
                'total_accounts' => count($accountRanking),
                'total_revenue' => array_sum(array_column($accountRanking, 'won_revenue')),
                'high_value_accounts' => count(array_filter($accountRanking, function ($account) {
                    return $account['won_revenue'] > 1000000000; // Accounts with > 1B revenue
                }))
            ]
        ];

        return $this->render('report/ranking/account_performance', $data);
    }

    /**
     * Helper methods for ranking calculations
     */
    private function calculatePipelineVelocity($salesId, $startDate, $endDate)
    {
        $opportunities = $this->opportunityModel
            ->where('sales_id', $salesId)
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->findAll();

        if (empty($opportunities)) return 0;

        $totalDays = 0;
        foreach ($opportunities as $opp) {
            if ($opp['closing_date'] && $opp['created_at']) {
                $created = strtotime($opp['created_at']);
                $closing = strtotime($opp['closing_date']);
                $totalDays += ($closing - $created) / (60 * 60 * 24);
            }
        }

        return count($opportunities) > 0 ? $totalDays / count($opportunities) : 0;
    }

    private function getSalesPerformanceTrends($currentRanking, $period)
    {
        $previousStart = date('Y-m-d', strtotime($this->request->getGet('start_date') . ' -1 ' . $period));
        $previousEnd = date('Y-m-d', strtotime($this->request->getGet('end_date') . ' -1 ' . $period));

        $previousRanking = $this->opportunityModel
            ->select('users.id, 
                     SUM(CASE WHEN opportunities.opportunity_status = "closed_won" THEN opportunities.estimated_value ELSE 0 END) as previous_revenue')
            ->join('users', 'users.id = opportunities.sales_id')
            ->where('opportunities.created_at >=', $previousStart)
            ->where('opportunities.created_at <=', $previousEnd . ' 23:59:59')
            ->where('users.role', 'sales')
            ->where('users.is_active', 1)
            ->groupBy('users.id')
            ->findAll();

        $trends = [];
        foreach ($currentRanking as $current) {
            $previous = array_filter($previousRanking, function ($item) use ($current) {
                return $item['id'] == $current['id'];
            });
            $previousRevenue = $previous ? array_values($previous)[0]['previous_revenue'] : 0;

            $trends[$current['id']] = [
                'growth' => $this->calculateGrowthRate($previousRevenue, $current['won_revenue']),
                'trend' => $current['won_revenue'] > $previousRevenue ? 'up' : ($current['won_revenue'] < $previousRevenue ? 'down' : 'stable')
            ];
        }

        return $trends;
    }

    private function calculateAverageWinRate($salesRanking)
    {
        $totalWinRate = 0;
        $count = 0;

        foreach ($salesRanking as $sales) {
            if ($sales['won_deals'] + $sales['lost_deals'] > 0) {
                $totalWinRate += $sales['win_rate'];
                $count++;
            }
        }

        return $count > 0 ? $totalWinRate / $count : 0;
    }

    private function calculateAccountPenetration($principalId)
    {
        $totalAccounts = $this->accountModel->countAll();
        $principalAccounts = $this->opportunityModel
            ->select('COUNT(DISTINCT account_id) as unique_accounts')
            ->where('principal_id', $principalId)
            ->get()
            ->getRow()->unique_accounts ?? 0;

        return $totalAccounts > 0 ? ($principalAccounts / $totalAccounts) * 100 : 0;
    }

    private function getPrincipalGrowthAnalysis($currentRanking)
    {
        $previousMonth = date('Y-m', strtotime('-1 month'));

        $growthData = [];
        foreach ($currentRanking as $principal) {
            $previousRevenue = $this->opportunityModel
                ->selectSum('estimated_value')
                ->where('principal_id', $principal['id'])
                ->where('opportunity_status', 'closed_won')
                ->where("DATE_FORMAT(created_at, '%Y-%m') =", $previousMonth)
                ->get()
                ->getRow()->estimated_value ?? 0;

            $growthData[$principal['id']] = [
                'previous_revenue' => $previousRevenue,
                'growth' => $this->calculateGrowthRate($previousRevenue, $principal['won_revenue']),
                'momentum' => $principal['won_revenue'] > $previousRevenue ? 'positive' : 'negative'
            ];
        }

        return $growthData;
    }

    private function getMostProlificPrincipal($principalRanking)
    {
        if (empty($principalRanking)) return null;

        return array_reduce($principalRanking, function ($carry, $item) {
            if ($carry === null) return $item;
            return $item['unique_accounts'] > $carry['unique_accounts'] ? $item : $carry;
        });
    }

    private function calculateAccountGrowthPotential($accountId)
    {
        // Calculate potential based on recent activity and deal size trends
        $recentActivity = $this->opportunityModel
            ->where('account_id', $accountId)
            ->where('created_at >=', date('Y-m-d', strtotime('-3 months')))
            ->countAllResults();

        $avgDealSize = $this->opportunityModel
            ->selectAvg('estimated_value')
            ->where('account_id', $accountId)
            ->where('opportunity_status', 'closed_won')
            ->get()
            ->getRow()->estimated_value ?? 0;

        // Simple scoring algorithm
        $activityScore = min($recentActivity / 5 * 100, 100); // Max 5 opportunities per quarter
        $dealSizeScore = min(($avgDealSize / 500000000) * 100, 100); // Max 500M average deal size

        return ($activityScore + $dealSizeScore) / 2;
    }

    private function segmentAccountsByValue($accountRanking)
    {
        $segments = [
            'platinum' => ['min' => 5000000000, 'count' => 0, 'revenue' => 0], // > 5B
            'gold' => ['min' => 1000000000, 'max' => 5000000000, 'count' => 0, 'revenue' => 0], // 1B - 5B
            'silver' => ['min' => 500000000, 'max' => 1000000000, 'count' => 0, 'revenue' => 0], // 500M - 1B
            'bronze' => ['min' => 0, 'max' => 500000000, 'count' => 0, 'revenue' => 0] // < 500M
        ];

        foreach ($accountRanking as $account) {
            $revenue = $account['won_revenue'];

            if ($revenue >= $segments['platinum']['min']) {
                $segments['platinum']['count']++;
                $segments['platinum']['revenue'] += $revenue;
            } elseif ($revenue >= $segments['gold']['min']) {
                $segments['gold']['count']++;
                $segments['gold']['revenue'] += $revenue;
            } elseif ($revenue >= $segments['silver']['min']) {
                $segments['silver']['count']++;
                $segments['silver']['revenue'] += $revenue;
            } else {
                $segments['bronze']['count']++;
                $segments['bronze']['revenue'] += $revenue;
            }
        }

        return $segments;
    }
}
