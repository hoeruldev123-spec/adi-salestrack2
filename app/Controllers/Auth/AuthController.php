<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        // Jika sudah login, redirect ke dashboard berdasarkan role
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard(session()->get('role'));
        }

        $data = [
            'title' => 'Login - SalesTrack'
        ];

        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        // Validasi input
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        // Cek user exists, password verify, dan user active
        if ($user && password_verify($password, $user['password']) && $user['is_active'] == 1) {

            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);
            session()->setFlashdata('success', 'Login berhasil! Selamat datang ' . $user['name']);

            return $this->redirectToDashboard($user['role']);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Username atau password salah!');
    }

    public function logout()
    {
        $userName = session()->get('name');
        session()->destroy();
        session()->setFlashdata('success', 'Logout berhasil! Sampai jumpa ' . $userName);

        return redirect()->to('/login');
    }

    /**
     * Redirect ke dashboard berdasarkan role
     */
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'bod':
                return redirect()->to('/bod/dashboard');
            case 'manager':
                return redirect()->to('/manager/dashboard');
            case 'sales':
            default:
                return redirect()->to('/sales/dashboard');
        }
    }
}
