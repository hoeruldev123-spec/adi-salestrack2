<?php

namespace App\Controllers\Master;

use App\Models\PrincipalModel;

class PrincipalController extends BaseMasterController
{
    public function __construct()
    {
        $this->model = new PrincipalModel();
        $this->modelName = 'PrincipalModel';
        $this->routeGroup = 'principals';
        $this->viewFolder = 'principal';
        $this->singularName = 'Principal';
        $this->pluralName = 'Principals';
    }

    protected function getValidationRules($id = null)
    {
        return [
            'principal_name' => "required|min_length[2]|max_length[200]|is_unique[master_principals.principal_name,id,{$id}]"
        ];
    }

    protected function getFormData()
    {
        return [
            'principal_name' => $this->request->getPost('principal_name'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 1
        ];
    }
}
