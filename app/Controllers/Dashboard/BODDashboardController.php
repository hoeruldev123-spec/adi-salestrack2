<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;

class BODDashboardController extends BaseController
{
    public function index()
    {
        // Get BOD-specific data
        $totalValue = $this->opportunityModel
            ->selectSum('estimated_value')
            ->get()
            ->getRow();

        $opportunityStats = $this->opportunityModel
            ->select('opportunity_status, COUNT(*) as count')
            ->groupBy('opportunity_status')
            ->findAll();

        $data = [
            'title' => 'Dashboard BOD',
            'active_menu' => 'dashboard',
            'total_value' => $totalValue->estimated_value ?? 0,
            'opportunity_stats' => $opportunityStats
        ];

        return $this->render('dashboard/bod', $data);
    }
}
