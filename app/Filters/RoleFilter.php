<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        try {
            // Cek apakah user sudah login
            if (!session()->get('isLoggedIn')) {
                session()->setFlashdata('error', 'Anda harus login terlebih dahulu!');
                return redirect()->to('/login');
            }

            // Ambil role user dari session
            $role = session()->get('role');

            // Validasi role exists
            if (empty($role)) {
                session()->setFlashdata('error', 'Role user tidak ditemukan!');
                return redirect()->to('/login');
            }

            // Jika ada arguments (role yang diizinkan) dan role user tidak termasuk di dalamnya
            if (!empty($arguments) && !in_array($role, $arguments)) {
                // Untuk request AJAX - perbaiki method isAJAX()
                if ($request->isAJAX()) {
                    return service('response')->setJSON([
                        'status' => 'error',
                        'message' => 'Akses ditolak! Anda tidak memiliki izin untuk mengakses resource ini.'
                    ])->setStatusCode(403);
                }

                session()->setFlashdata('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.');
                return redirect()->to('/dashboard');
            }

            // Jika semua pengecekan berhasil, return true untuk melanjutkan
            return true;
        } catch (\Exception $e) {
            log_message('error', 'RoleFilter Error: ' . $e->getMessage());

            // Untuk request AJAX - perbaiki method isAJAX()
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan sistem.'
                ])->setStatusCode(500);
            }

            session()->setFlashdata('error', 'Terjadi kesalahan sistem.');
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
