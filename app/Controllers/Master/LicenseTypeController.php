<?php

namespace App\Controllers\Master;

use App\Models\LicenseTypeModel;

class LicenseTypeController extends BaseMasterController
{
    public function __construct()
    {
        $this->model = new LicenseTypeModel();
        $this->modelName = 'LicenseTypeModel';
        $this->routeGroup = 'license-types';
        $this->viewFolder = 'license_type';
        $this->singularName = 'License Type';
        $this->pluralName = 'License Types';
    }

    protected function getValidationRules($id = null)
    {
        return [
            'license_type' => "required|min_length[2]|max_length[100]|is_unique[master_license_types.license_type,id,{$id}]"
        ];
    }

    protected function getFormData()
    {
        return [
            'license_type' => $this->request->getPost('license_type'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 1
        ];
    }
}
