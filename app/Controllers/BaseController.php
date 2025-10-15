<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form', 'url', 'auth', 'number'];

    /**
     * Session instance
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Validation instance
     *
     * @var \CodeIgniter\Validation\Validation
     */
    protected $validation;

    /**
     * Database instance
     *
     * @var \CodeIgniter\Database\BaseConnection
     */
    protected $db;

    /**
     * User data
     *
     * @var array|null
     */
    protected $userData;

    /**
     * Current user role
     *
     * @var string
     */
    protected $currentRole;

    /**
     * View data
     *
     * @var array
     */
    protected $viewData = [];

    /**
     * Initialize the controller
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload services
        $this->initializeServices();

        // Set user data if logged in
        $this->initializeUserData();

        // Set common view data
        $this->initializeViewData();
    }

    /**
     * Initialize common services
     *
     * @return void
     */
    protected function initializeServices()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();
    }

    /**
     * Initialize user data from session
     *
     * @return void
     */
    protected function initializeUserData()
    {
        if ($this->session->get('logged_in')) {
            $this->userData = [
                'id' => $this->session->get('user_id'),
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ];

            $this->currentRole = $this->session->get('role');
        } else {
            $this->userData = null;
            $this->currentRole = 'guest';
        }
    }

    /**
     * Initialize common view data
     *
     * @return void
     */
    protected function initializeViewData()
    {
        $this->viewData = [
            'currentUrl' => current_url(),
            'userData' => $this->userData,
            'currentRole' => $this->currentRole,
            'pageTitle' => 'Inventory System',
            'breadcrumbs' => []
        ];

        // Add notification count for logged-in users
        if ($this->isLoggedIn()) {
            $this->viewData['notificationCount'] = $this->getNotificationCount();
            $this->viewData['quickStats'] = $this->getQuickStats();
        }
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    protected function isLoggedIn()
    {
        return $this->session->get('logged_in') === true;
    }

    /**
     * Check if user has specific role
     *
     * @param string|array $roles
     * @return bool
     */
    protected function hasRole($roles)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->currentRole, $roles);
    }

    /**
     * Check permission for current user
     *
     * @param string $permission
     * @return bool
     */
    protected function hasPermission($permission)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        $permissions = [
            'admin' => [
                'master_create', 'master_read', 'master_update', 'master_delete',
                'transaction_create', 'transaction_read', 'transaction_update', 'transaction_delete',
                'approval', 'reporting', 'user_management'
            ],
            'staff' => [
                'master_create', 'master_read', 'master_update',
                'transaction_create', 'transaction_read', 'transaction_update',
                'reporting'
            ]
        ];

        return in_array($permission, $permissions[$this->currentRole] ?? []);
    }

    /**
     * Require authentication
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    protected function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            $this->session->set('redirect_url', current_url());
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return null;
    }

    /**
     * Require specific role
     *
     * @param string|array $roles
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    protected function requireRole($roles)
    {
        $authCheck = $this->requireAuth();
        if ($authCheck !== null) {
            return $authCheck;
        }

        if (!$this->hasRole($roles)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return null;
    }

    /**
     * Require specific permission
     *
     * @param string $permission
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    protected function requirePermission($permission)
    {
        $authCheck = $this->requireAuth();
        if ($authCheck !== null) {
            return $authCheck;
        }

        if (!$this->hasPermission($permission)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki izin untuk melakukan aksi tersebut.');
        }

        return null;
    }

    /**
     * Get notification count for current user
     *
     * @return int
     */
    protected function getNotificationCount()
    {
        if (!$this->isLoggedIn()) {
            return 0;
        }

        $count = 0;

        try {
            // Count pending approvals for admin
            if ($this->hasRole('admin')) {
                $count += $this->db->table('purchase_orders')
                    ->where('status', 'pending')
                    ->countAllResults();

                $count += $this->db->table('sales_orders')
                    ->where('status', 'pending')
                    ->countAllResults();
            }

            // Count low stock items
            $count += $this->db->table('items')
                ->join('item_stocks', 'items.id = item_stocks.item_id')
                ->where('items.min_stock >', 0)
                ->where('item_stocks.quantity <= items.min_stock')
                ->countAllResults();

        } catch (\Exception $e) {
            // Log error but don't break the application
            log_message('error', 'Error counting notifications: ' . $e->getMessage());
        }

        return $count;
    }

    /**
     * Get quick stats for dashboard
     *
     * @return array
     */
    protected function getQuickStats()
    {
        if (!$this->isLoggedIn()) {
            return [];
        }

        $stats = [];

        try {
            // Low stock count
            $stats['low_stock'] = $this->db->table('items')
                ->join('item_stocks', 'items.id = item_stocks.item_id')
                ->where('items.min_stock >', 0)
                ->where('item_stocks.quantity <= items.min_stock')
                ->countAllResults();

            // Pending purchase orders
            $stats['pending_po'] = $this->db->table('purchase_orders')
                ->where('status', 'pending')
                ->countAllResults();

            // Pending sales orders
            $stats['pending_so'] = $this->db->table('sales_orders')
                ->where('status', 'pending')
                ->countAllResults();

            // Total items
            $stats['total_items'] = $this->db->table('items')
                ->where('deleted_at', null)
                ->countAllResults();

        } catch (\Exception $e) {
            // Log error but don't break the application
            log_message('error', 'Error getting quick stats: ' . $e->getMessage());
            $stats = [
                'low_stock' => 0,
                'pending_po' => 0,
                'pending_so' => 0,
                'total_items' => 0
            ];
        }

        return $stats;
    }

    /**
     * Set page title
     *
     * @param string $title
     * @return void
     */
    protected function setPageTitle($title)
    {
        $this->viewData['pageTitle'] = $title . ' - Inventory System';
    }

    /**
     * Add breadcrumb
     *
     * @param string $text
     * @param string $url
     * @return void
     */
    protected function addBreadcrumb($text, $url = '')
    {
        $this->viewData['breadcrumbs'][] = [
            'text' => $text,
            'url' => $url
        ];
    }

    /**
     * Render view with common data
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    protected function render($view, $data = [])
    {
        $mergedData = array_merge($this->viewData, $data);
        return view($view, $mergedData);
    }

    /**
     * Success response for AJAX
     *
     * @param mixed $data
     * @param string $message
     * @return \CodeIgniter\HTTP\Response
     */
    protected function successResponse($data = null, $message = 'Success')
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Error response for AJAX
     *
     * @param string $message
     * @param int $statusCode
     * @return \CodeIgniter\HTTP\Response
     */
    protected function errorResponse($message = 'Error', $statusCode = 400)
    {
        return $this->response->setStatusCode($statusCode)->setJSON([
            'success' => false,
            'message' => $message,
            'data' => null
        ]);
    }

    /**
     * Validate form data
     *
     * @param array $rules
     * @param array $messages
     * @return bool
     */
    protected function validateForm($rules, $messages = [])
    {
        if ($this->request->getMethod() === 'post') {
            if ($this->validation->setRules($rules, $messages)->run($this->request->getPost())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get validation errors
     *
     * @return array
     */
    protected function getValidationErrors()
    {
        return $this->validation->getErrors();
    }

    /**
     * Log activity
     *
     * @param string $action
     * @param string $description
     * @param mixed $data
     * @return void
     */
    protected function logActivity($action, $description = '', $data = null)
    {
        if (!$this->isLoggedIn()) {
            return;
        }

        try {
            $logData = [
                'user_id' => $this->userData['id'],
                'action' => $action,
                'description' => $description,
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'data' => $data ? json_encode($data) : null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->table('activity_logs')->insert($logData);
        } catch (\Exception $e) {
            log_message('error', 'Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Format currency
     *
     * @param float $amount
     * @return string
     */
    protected function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format date
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    protected function formatDate($date, $format = 'd/m/Y')
    {
        if (empty($date)) {
            return '';
        }

        return date($format, strtotime($date));
    }
}