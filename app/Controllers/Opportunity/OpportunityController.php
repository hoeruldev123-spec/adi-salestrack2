<?php

namespace App\Controllers\Opportunity;

use App\Controllers\BaseController;
use App\Models\OpportunityModel;
use App\Models\AccountModel;
use App\Models\PrincipalModel;
use App\Models\SolutionArchitectModel;
use App\Models\LicenseTypeModel;
use App\Models\OpportunityLogModel;

class OpportunityController extends BaseController
{
    protected $opportunityModel;
    protected $accountModel;
    protected $principalModel;
    protected $saModel;
    protected $licenseModel;
    protected $logModel;

    public function __construct()
    {
        $this->opportunityModel = new OpportunityModel();
        $this->accountModel = new AccountModel();
        $this->principalModel = new PrincipalModel();
        $this->saModel = new SolutionArchitectModel();
        $this->licenseModel = new LicenseTypeModel();
        $this->logModel = new OpportunityLogModel();
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
            'title' => 'Create New Opportunity',
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
            'project_name' => 'required|min_length[3]',
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
            'solution_architect_id' => $this->request->getPost('solution_architect_id') ?: null,
            'license_type_id' => $this->request->getPost('license_type_id') ?: null,
            'pic' => $this->request->getPost('pic'),
            'services_license' => $this->request->getPost('services_license'),
            'closing_date' => $this->request->getPost('closing_date') ?: null,
            'progress' => $this->request->getPost('progress'),
            'opportunity_status' => $this->request->getPost('opportunity_status'),
            'progress_percentage' => $this->request->getPost('progress_percentage') ?: 0,
            'deal_reg' => $this->request->getPost('deal_reg'),
            'estimated_value' => $this->request->getPost('estimated_value'),
            'remarks' => $this->request->getPost('remarks')
        ];

        if ($opportunityId = $this->opportunityModel->insert($data)) {
            // Log the creation
            $this->logModel->addLog(
                $opportunityId,
                session()->get('user_id'),
                'created',
                null,
                "Opportunity created: {$data['project_name']}"
            );

            return redirect()->to('/sales/opportunities')
                ->with('success', 'Opportunity created successfully!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create opportunity');
    }

    public function edit($id)
    {
        $opportunity = $this->opportunityModel->getByIdWithRelations($id);

        // Check authorization - sales can only edit their own opportunities
        if (session()->get('role') === 'sales' && $opportunity['sales_id'] != session()->get('user_id')) {
            return redirect()->to('/sales/opportunities')
                ->with('error', 'You are not authorized to edit this opportunity');
        }

        if (!$opportunity) {
            return redirect()->to('/sales/opportunities')->with('error', 'Opportunity not found');
        }

        $data = [
            'title' => 'Edit Opportunity',
            'active_menu' => 'opportunities',
            'opportunity' => $opportunity,
            'accounts' => $this->accountModel->getActiveAccounts(),
            'principals' => $this->principalModel->getActivePrincipals(),
            'solution_architects' => $this->saModel->getActiveSA(),
            'license_types' => $this->licenseModel->getActiveLicenseTypes()
        ];

        return $this->render('opportunity/edit', $data);
    }

    public function update($id)
    {
        $opportunity = $this->opportunityModel->find($id);

        // Check authorization
        if (session()->get('role') === 'sales' && $opportunity['sales_id'] != session()->get('user_id')) {
            return redirect()->to('/sales/opportunities')
                ->with('error', 'You are not authorized to update this opportunity');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'project_name' => 'required|min_length[3]',
            'account_id' => 'required',
            'principal_id' => 'required',
            'estimated_value' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $oldData = $this->opportunityModel->find($id);
        $newData = [
            'project_name' => $this->request->getPost('project_name'),
            'account_id' => $this->request->getPost('account_id'),
            'principal_id' => $this->request->getPost('principal_id'),
            'solution_architect_id' => $this->request->getPost('solution_architect_id') ?: null,
            'license_type_id' => $this->request->getPost('license_type_id') ?: null,
            'pic' => $this->request->getPost('pic'),
            'services_license' => $this->request->getPost('services_license'),
            'closing_date' => $this->request->getPost('closing_date') ?: null,
            'progress' => $this->request->getPost('progress'),
            'opportunity_status' => $this->request->getPost('opportunity_status'),
            'progress_percentage' => $this->request->getPost('progress_percentage') ?: 0,
            'deal_reg' => $this->request->getPost('deal_reg'),
            'estimated_value' => $this->request->getPost('estimated_value'),
            'remarks' => $this->request->getPost('remarks')
        ];

        if ($this->opportunityModel->update($id, $newData)) {
            // Log the update
            $this->logChanges($id, $oldData, $newData);

            return redirect()->to('/sales/opportunities')
                ->with('success', 'Opportunity updated successfully!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update opportunity');
    }

    public function show($id)
    {
        $opportunity = $this->opportunityModel->getByIdWithRelations($id);

        if (!$opportunity) {
            return redirect()->to('/sales/opportunities')->with('error', 'Opportunity not found');
        }

        // Get activity logs
        $logs = $this->logModel->getLogsByOpportunity($id);

        $data = [
            'title' => 'Opportunity Details',
            'active_menu' => 'opportunities',
            'opportunity' => $opportunity,
            'logs' => $logs
        ];

        return $this->render('opportunity/detail', $data);
    }

    public function delete($id)
    {
        $opportunity = $this->opportunityModel->find($id);

        // Check authorization
        if (session()->get('role') === 'sales' && $opportunity['sales_id'] != session()->get('user_id')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You are not authorized to delete this opportunity'
                ]);
            }
            return redirect()->to('/sales/opportunities')
                ->with('error', 'You are not authorized to delete this opportunity');
        }

        try {
            // Soft delete - hanya set deleted_at
            if ($this->opportunityModel->delete($id)) {
                // Log the soft deletion
                $this->logModel->addLog(
                    $id,
                    session()->get('user_id'),
                    'deleted',
                    json_encode($opportunity),
                    'SOFT_DELETED'
                );

                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Opportunity deleted successfully'
                    ]);
                }

                return redirect()->to('/sales/opportunities')
                    ->with('success', 'Opportunity deleted successfully!');
            }
        } catch (\Exception $e) {
            log_message('error', 'Delete opportunity error: ' . $e->getMessage());

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete opportunity'
                ]);
            }

            return redirect()->to('/sales/opportunities')
                ->with('error', 'Failed to delete opportunity');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete opportunity'
            ]);
        }

        return redirect()->to('/sales/opportunities')
            ->with('error', 'Failed to delete opportunity');
    }

    /**
     * Log changes for audit trail
     */
    private function logChanges($opportunityId, $oldData, $newData)
    {
        $changes = [];

        foreach ($newData as $field => $newValue) {
            $oldValue = $oldData[$field] ?? null;

            if ($oldValue != $newValue) {
                $changes[] = [
                    'field' => $field,
                    'old' => $oldValue,
                    'new' => $newValue
                ];

                $this->logModel->addLog(
                    $opportunityId,
                    session()->get('user_id'),
                    'updated',
                    "{$field}: {$oldValue}",
                    "{$field}: {$newValue}"
                );
            }
        }

        return $changes;
    }

    public function updateStatus($id)
    {
        $opportunity = $this->opportunityModel->find($id);

        // Check authorization
        if (session()->get('role') === 'sales' && $opportunity['sales_id'] != session()->get('user_id')) {
            return redirect()->to('/sales/opportunities')
                ->with('error', 'You are not authorized to update this opportunity');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'opportunity_status' => 'required',
            'progress_percentage' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('errors', $validation->getErrors());
        }

        $oldData = $this->opportunityModel->find($id);
        $newData = [
            'opportunity_status' => $this->request->getPost('opportunity_status'),
            'progress_percentage' => $this->request->getPost('progress_percentage'),
            'progress' => $this->request->getPost('progress')
        ];

        if ($this->opportunityModel->update($id, $newData)) {
            // Log the status update
            $this->logModel->addLog(
                $id,
                session()->get('user_id'),
                'status_updated',
                "Status: {$oldData['opportunity_status']}, Progress: {$oldData['progress_percentage']}%",
                "Status: {$newData['opportunity_status']}, Progress: {$newData['progress_percentage']}%"
            );

            return redirect()->to('/sales/opportunities/' . $id)
                ->with('success', 'Opportunity status updated successfully!');
        }

        return redirect()->back()
            ->with('error', 'Failed to update opportunity status');
    }
}
