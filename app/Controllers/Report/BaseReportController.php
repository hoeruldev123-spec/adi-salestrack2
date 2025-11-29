<?php

namespace App\Controllers\Report;

use App\Controllers\BaseController;
use App\Models\OpportunityModel;
use App\Models\UserModel;

class BaseReportController extends BaseController
{
    protected $opportunityModel;
    protected $userModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->opportunityModel = new \App\Models\OpportunityModel();
        $this->userModel = new \App\Models\UserModel();
    }

    /**
     * Get date range for reports
     */
    protected function getDateRange()
    {
        $startDate = $this->request->getGet('start_date')
            ? date('Y-m-d', strtotime($this->request->getGet('start_date')))
            : date('Y-m-01'); // First day of current month

        $endDate = $this->request->getGet('end_date')
            ? date('Y-m-d', strtotime($this->request->getGet('end_date')))
            : date('Y-m-t'); // Last day of current month

        return [$startDate, $endDate];
    }

    /**
     * Get filter parameters
     */
    protected function getFilters()
    {
        return [
            'sales_id' => $this->request->getGet('sales_id'),
            'account_id' => $this->request->getGet('account_id'),
            'principal_id' => $this->request->getGet('principal_id'),
            'status' => $this->request->getGet('status')
        ];
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters($builder, $filters)
    {
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $builder->where($field, $value);
            }
        }
        return $builder;
    }

    /**
     * Format currency
     */
    protected function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Calculate percentage
     */
    protected function calculatePercentage($part, $total)
    {
        if ($total == 0) return 0;
        return round(($part / $total) * 100, 2);
    }
}
