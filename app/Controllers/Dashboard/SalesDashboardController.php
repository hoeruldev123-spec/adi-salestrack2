<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;

class SalesDashboardController extends BaseController
{
    public function index()
    {
        // Get sales-specific data
        $totalOpportunities = $this->opportunityModel
            ->where('sales_id', session()->get('user_id'))
            ->countAllResults();

        $recentOpportunities = $this->opportunityModel
            ->where('sales_id', session()->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'title' => 'Dashboard Sales',
            'active_menu' => 'dashboard',
            'total_opportunities' => $totalOpportunities,
            'recent_opportunities' => $recentOpportunities
        ];

        return $this->render('dashboard/sales', $data);
    }
}
