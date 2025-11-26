<?php

namespace App\Controllers\Master;

use App\Models\AccountModel;

class AccountController extends BaseMasterController
{
    public function __construct()
    {
        $this->model = new AccountModel();
        $this->modelName = 'AccountModel';
        $this->routeGroup = 'accounts';
        $this->viewFolder = 'account';
        $this->singularName = 'Account';
        $this->pluralName = 'Accounts';
    }

    protected function getValidationRules($id = null)
    {
        return [
            'account_name' => "required|min_length[2]|max_length[200]|is_unique[master_accounts.account_name,id,{$id}]"
        ];
    }

    protected function getFormData()
    {
        return [
            'account_name' => $this->request->getPost('account_name'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 1
        ];
    }
}
