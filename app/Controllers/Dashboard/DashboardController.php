<?php
// app/Controllers/DashboardController.php
namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $role = session()->get('role');

        // Redirect ke dashboard sesuai role
        switch ($role) {
            case 'admin':
                return redirect()->to('admin/users');
            case 'manager':
                return redirect()->to('manager/dashboard');
            case 'sales':
                return redirect()->to('sales/dashboard');
            case 'bod':
                return redirect()->to('bod/dashboard');
            default:
                return redirect()->to('login');
        }
    }
}
