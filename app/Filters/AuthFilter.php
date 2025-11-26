<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user belum login
        if (!session()->get('isLoggedIn')) {
            // Untuk API/AJAX requests - perbaiki method isAJAX()
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized. Please login first.'
                ])->setStatusCode(401);
            }

            session()->setFlashdata('error', 'Silakan login terlebih dahulu');
            return redirect()->to('/login');
        }

        // Jika sudah login, lanjutkan request
        return true;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
