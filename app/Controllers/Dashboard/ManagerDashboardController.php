<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;

class ManagerDashboardController extends BaseController
{
    public function index()
    {
        // Get manager-specific data
        $totalOpportunities = $this->opportunityModel->countAll();

        $recentOpportunities = $this->opportunityModel
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'title' => 'Dashboard Manager',
            'active_menu' => 'dashboard',
            'total_opportunities' => $totalOpportunities,
            'recent_opportunities' => $recentOpportunities
        ];

        return $this->render('dashboard/manager', $data);
    }
}
