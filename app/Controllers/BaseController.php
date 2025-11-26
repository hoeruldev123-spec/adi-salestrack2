<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class BaseController extends Controller
{
    protected $helpers = ['form', 'url', 'custom_helper'];

    /**
     * Data yang akan di-share ke semua views
     */
    protected $globalData = [];

    /**
     * Model instances
     */
    protected $userModel;
    protected $opportunityModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Initialize models
        $this->userModel = new \App\Models\UserModel();
        $this->opportunityModel = new \App\Models\OpportunityModel();

        // Set global data untuk view
        $this->setGlobalViewData();
    }

    /**
     * Set data global untuk semua view
     */
    protected function setGlobalViewData()
    {
        if (session()->get('isLoggedIn')) {
            $this->globalData = [
                'current_user' => [
                    'id' => session()->get('user_id'),
                    'name' => session()->get('name'),
                    'username' => session()->get('username'),
                    'role' => session()->get('role')
                ],
                'page_title' => 'SalesTrack'
            ];
        } else {
            $this->globalData = [
                'page_title' => 'SalesTrack'
            ];
        }
    }

    /**
     * Render view dengan layout dan global data
     */
    protected function render($view, $data = [])
    {
        // Merge global data dengan data spesifik
        $mergedData = array_merge($this->globalData, $data);

        $mergedData['content'] = view($view, $mergedData);
        return view('layouts/main', $mergedData);
    }
}
