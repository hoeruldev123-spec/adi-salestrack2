<?php

namespace App\Controllers\Master;

use App\Models\SolutionArchitectModel;

class SolutionArchitectController extends BaseMasterController
{
    public function __construct()
    {
        $this->model = new SolutionArchitectModel();
        $this->modelName = 'SolutionArchitectModel';
        $this->routeGroup = 'solution-architects';
        $this->viewFolder = 'solution_architect';
        $this->singularName = 'Solution Architect';
        $this->pluralName = 'Solution Architects';
    }

    protected function getValidationRules($id = null)
    {
        return [
            'sa_name' => "required|min_length[2]|max_length[100]|is_unique[master_solution_architects.sa_name,id,{$id}]"
        ];
    }

    protected function getFormData()
    {
        return [
            'sa_name' => $this->request->getPost('sa_name'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 1
        ];
    }
}
