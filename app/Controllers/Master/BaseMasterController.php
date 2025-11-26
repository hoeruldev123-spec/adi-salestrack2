<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;

class BaseMasterController extends BaseController
{
    protected $model;
    protected $modelName;
    protected $routeGroup;
    protected $viewFolder;
    protected $singularName;
    protected $pluralName;

    public function index()
    {
        $data = [
            'title' => $this->pluralName,
            'active_menu' => 'master',
            'active_submenu' => $this->routeGroup,
            'items' => $this->model->findAll(),
            'route_group' => $this->routeGroup,
            'singular_name' => $this->singularName,
            'plural_name' => $this->pluralName
        ];

        return $this->render("master/{$this->viewFolder}/index", $data);
    }

    public function create()
    {
        $data = [
            'title' => "Create {$this->singularName}",
            'active_menu' => 'master',
            'active_submenu' => $this->routeGroup,
            'route_group' => $this->routeGroup,
            'singular_name' => $this->singularName,
            'plural_name' => $this->pluralName
        ];

        return $this->render("master/{$this->viewFolder}/create", $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules($this->getValidationRules());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = $this->getFormData();

        if ($this->model->insert($data)) {
            return redirect()->to("/master/{$this->routeGroup}")
                ->with('success', "{$this->singularName} created successfully!");
        }

        return redirect()->back()
            ->withInput()
            ->with('error', "Failed to create {$this->singularName}");
    }

    public function edit($id)
    {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->to("/master/{$this->routeGroup}")
                ->with('error', "{$this->singularName} not found");
        }

        $data = [
            'title' => "Edit {$this->singularName}",
            'active_menu' => 'master',
            'active_submenu' => $this->routeGroup,
            'item' => $item,
            'route_group' => $this->routeGroup,
            'singular_name' => $this->singularName,
            'plural_name' => $this->pluralName
        ];

        return $this->render("master/{$this->viewFolder}/edit", $data);
    }

    public function update($id)
    {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->to("/master/{$this->routeGroup}")
                ->with('error', "{$this->singularName} not found");
        }

        $validation = \Config\Services::validation();
        $validation->setRules($this->getValidationRules($id));

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = $this->getFormData();

        if ($this->model->update($id, $data)) {
            return redirect()->to("/master/{$this->routeGroup}")
                ->with('success', "{$this->singularName} updated successfully!");
        }

        return redirect()->back()
            ->withInput()
            ->with('error', "Failed to update {$this->singularName}");
    }

    public function delete($id)
    {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->to("/master/{$this->routeGroup}")
                ->with('error', "{$this->singularName} not found");
        }

        if ($this->model->delete($id)) {
            return redirect()->to("/master/{$this->routeGroup}")
                ->with('success', "{$this->singularName} deleted successfully!");
        }

        return redirect()->to("/master/{$this->routeGroup}")
            ->with('error', "Failed to delete {$this->singularName}");
    }

    public function toggle($id)
    {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->to("/master/{$this->routeGroup}")
                ->with('error', "{$this->singularName} not found");
        }

        $newStatus = $item['is_active'] ? 0 : 1;
        $statusText = $newStatus ? 'activated' : 'deactivated';

        if ($this->model->update($id, ['is_active' => $newStatus])) {
            return redirect()->to("/master/{$this->routeGroup}")
                ->with('success', "{$this->singularName} {$statusText} successfully!");
        }

        return redirect()->to("/master/{$this->routeGroup}")
            ->with('error', "Failed to toggle {$this->singularName} status");
    }

    protected function getValidationRules($id = null)
    {
        // Default validation rules - override in child controllers if needed
        return [
            'name' => 'required|min_length[2]|max_length[200]'
        ];
    }

    protected function getFormData()
    {
        // Default form data - override in child controllers if needed
        return [
            'name' => $this->request->getPost('name'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 1
        ];
    }
}
