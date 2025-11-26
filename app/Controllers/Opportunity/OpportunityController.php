<?php

namespace App\Controllers\Opportunity;

use App\Controllers\BaseController;
use App\Models\OpportunityModel;
use App\Models\AccountModel;
use App\Models\PrincipalModel;
use App\Models\SolutionArchitectModel;
use App\Models\LicenseTypeModel;

class OpportunityController extends BaseController
{
    protected $opportunityModel;
    protected $accountModel;
    protected $principalModel;
    protected $saModel;
    protected $licenseModel;

    public function __construct()
    {
        $this->opportunityModel = new OpportunityModel();
        $this->accountModel = new AccountModel();
        $this->principalModel = new PrincipalModel();
        $this->saModel = new SolutionArchitectModel();
        $this->licenseModel = new LicenseTypeModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        // Sales hanya lihat opportunities mereka sendiri
        if ($userRole === 'sales') {
            $opportunities = $this->opportunityModel->getBySalesId($userId);
        } else {
            // Manager & BOD lihat semua
            $opportunities = $this->opportunityModel->getOpportunitiesWithRelations();
        }

        $data = [
            'title' => 'Opportunities',
            'active_menu' => 'opportunities',
            'opportunities' => $opportunities
        ];

        return $this->render('opportunity/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Opportunity',
            'active_menu' => 'opportunities',
            'accounts' => $this->accountModel->getActiveAccounts(),
            'principals' => $this->principalModel->getActivePrincipals(),
            'solution_architects' => $this->saModel->getActiveSA(),
            'license_types' => $this->licenseModel->getActiveLicenseTypes()
        ];

        return $this->render('opportunity/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'project_name' => 'required',
            'account_id' => 'required',
            'principal_id' => 'required',
            'estimated_value' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'sales_id' => session()->get('user_id'),
            'project_name' => $this->request->getPost('project_name'),
            'account_id' => $this->request->getPost('account_id'),
            'principal_id' => $this->request->getPost('principal_id'),
            'solution_architect_id' => $this->request->getPost('solution_architect_id'),
            'license_type_id' => $this->request->getPost('license_type_id'),
            'pic' => $this->request->getPost('pic'),
            'services_license' => $this->request->getPost('services_license'),
            'closing_date' => $this->request->getPost('closing_date'),
            'progress' => $this->request->getPost('progress'),
            'opportunity_status' => $this->request->getPost('opportunity_status'),
            'progress_percentage' => $this->request->getPost('progress_percentage'),
            'deal_reg' => $this->request->getPost('deal_reg'),
            'estimated_value' => $this->request->getPost('estimated_value'),
            'remarks' => $this->request->getPost('remarks')
        ];

        if ($this->opportunityModel->insert($data)) {
            return redirect()->to('/sales/opportunities')
                ->with('success', 'Opportunity created successfully!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create opportunity');
    }
}
