<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Cek apakah user belum login
        if (!$session->get('isLoggedIn')) {
            // Untuk API/AJAX requests
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized. Please login first.'
                ])->setStatusCode(401);
            }

            $session->setFlashdata('error', 'Silakan login terlebih dahulu');
            return redirect()->to('/login');
        }

        // Role-based access control
        if (!empty($arguments)) {
            $userRole = $session->get('role');



            // Jika ada argumen role yang dibutuhkan
            if (!in_array($userRole, $arguments)) {
                if ($request->isAJAX()) {
                    return service('response')->setJSON([
                        'status' => 'error',
                        'message' => 'Access denied. Insufficient privileges.'
                    ])->setStatusCode(403);
                }

                $session->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin.');
                return redirect()->to('/dashboard');
            }
        }

        // Jika sudah login, lanjutkan request
        return true;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
