<?php
// app/Controllers/Admin/UserController.php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Show add user form
     */
    public function add()
    {
        $currentRole = session()->get('role');
        $allowedRoles = $this->userModel->getAllowedRolesForRole($currentRole);

        $data = [
            'title' => 'Tambah User Baru',
            'current_user' => [
                'role' => session()->get('role'),
                'name' => session()->get('name'),
                'username' => session()->get('username')
            ],
            'active_menu' => 'users',
            'active_submenu' => 'add_user',
            'allowedRoles' => $allowedRoles,
            'currentRole' => $currentRole,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/users/add', $data);
    }

    /**
     * Process add user
     */
    public function store()
    {
        $currentRole = session()->get('role');
        $currentUserId = session()->get('user_id');

        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'name' => 'required|min_length[3]|max_length[200]',
            'email' => 'required|valid_email|max_length[200]|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[sales,manager,bod,admin]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        // Cek apakah role yang dimasukkan diperbolehkan
        $inputRole = $this->request->getPost('role');

        if (!$this->userModel->canCreateRole($currentRole, $inputRole)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Role yang dipilih tidak diperbolehkan untuk akun Anda.');
        }

        // Data untuk disimpan
        $data = [
            'username' => $this->request->getPost('username'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $inputRole,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        try {
            if ($this->userModel->save($data)) {
                return redirect()->to('/admin/users')
                    ->with('success', 'User berhasil ditambahkan!');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal menambahkan user. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Di method edit() - tambahkan permission check untuk manager
    public function edit($id)
    {
        $currentRole = session()->get('role');
        $currentUserId = session()->get('user_id');

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User tidak ditemukan.');
        }

        // Cek akses edit
        // Admin bisa edit semua
        // Manager hanya bisa edit sales
        if ($currentRole === 'manager' && $user['role'] !== 'sales') {
            return redirect()->to('/admin/users')
                ->with('error', 'Anda hanya dapat mengedit user dengan role Sales.');
        }

        $allowedRoles = $this->userModel->getAllowedRolesForRole($currentRole);

        $data = [
            'title' => 'Edit User',
            'current_user' => [
                'id' => $currentUserId,
                'role' => $currentRole,
                'name' => session()->get('name'),
                'username' => session()->get('username')
            ],
            'active_menu' => 'users',
            'user' => $user,
            'allowedRoles' => $allowedRoles,
            'currentRole' => $currentRole,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/users/edit', $data);
    }

    // Di method update() - tambahkan permission check
    public function update($id)
    {
        $currentRole = session()->get('role');
        $currentUserId = session()->get('user_id');

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User tidak ditemukan.');
        }

        // Cek akses update
        if ($currentRole === 'manager' && $user['role'] !== 'sales') {
            return redirect()->to('/admin/users')
                ->with('error', 'Anda hanya dapat mengedit user dengan role Sales.');
        }

        // Validasi input
        $rules = [
            'username' => "required|min_length[3]|max_length[100]|is_unique[users.username,id,$id]",
            'name' => 'required|min_length[3]|max_length[200]',
            'email' => "required|valid_email|max_length[200]|is_unique[users.email,id,$id]",
            'role' => 'required|in_list[sales,manager,bod,admin]',
        ];

        // Password optional untuk update
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        // Cek apakah role yang dimasukkan diperbolehkan
        $inputRole = $this->request->getPost('role');

        if (!$this->userModel->canCreateRole($currentRole, $inputRole)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Role yang dipilih tidak diperbolehkan untuk akun Anda.');
        }

        // Data untuk diupdate
        $data = [
            'username' => $this->request->getPost('username'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $inputRole,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Update password hanya jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        try {
            if ($this->userModel->update($id, $data)) {
                return redirect()->to('/admin/users')
                    ->with('success', 'User berhasil diperbarui!');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal memperbarui user. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Di method delete() - tambahkan permission untuk manager
    public function delete($id)
    {
        $currentRole = session()->get('role');
        $currentUserId = session()->get('user_id');

        // Cek user yang akan dihapus
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User tidak ditemukan.'
            ])->setStatusCode(404);
        }

        // Permission rules:
        // 1. Admin bisa delete semua kecuali admin lain dan diri sendiri
        // 2. Manager hanya bisa delete sales

        if ($currentRole === 'admin') {
            // Admin tidak bisa delete admin lain atau diri sendiri
            if ($user['role'] === 'admin') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus user dengan role Admin.'
                ])->setStatusCode(403);
            }

            if ($user['id'] == $currentUserId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus akun sendiri.'
                ])->setStatusCode(403);
            }
        } elseif ($currentRole === 'manager') {
            // Manager hanya bisa delete sales
            if ($user['role'] !== 'sales') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Manager hanya dapat menghapus user dengan role Sales.'
                ])->setStatusCode(403);
            }
        } else {
            // Role lain tidak bisa delete
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus user.'
            ])->setStatusCode(403);
        }

        try {
            if ($this->userModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'User berhasil dihapus.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menghapus user.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    // Di method index() - filter untuk manager
    public function index()
    {
        $currentRole = session()->get('role');
        $currentUserId = session()->get('user_id');

        // Filter data berdasarkan role
        if ($currentRole === 'admin') {
            $users = $this->userModel->select('id, username, name, email, role, is_active, created_at')->findAll();
        } elseif ($currentRole === 'manager') {
            // Manager hanya melihat sales
            $users = $this->userModel->select('id, username, name, email, role, is_active, created_at')
                ->where('role', 'sales')
                ->findAll();
        } else {
            $users = [];
        }

        $data = [
            'title' => 'Manajemen User',
            'current_user' => [
                'id' => $currentUserId,
                'role' => $currentRole,
                'name' => session()->get('name'),
                'username' => session()->get('username')
            ],
            'active_menu' => 'users',
            'users' => $users,
            'currentRole' => $currentRole,
            'currentUserId' => $currentUserId
        ];

        return view('admin/users/index', $data);
    }
}
