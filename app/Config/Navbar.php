<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Navbar extends BaseConfig
{
    public $brand = [
        'logo' => 'fas fa-boxes',
        'text' => 'Inventory System',
        'url' => '/dashboard'
    ];

    public function getMenu($currentUrl, $userRole)
    {
        $baseMenu = [
            [
                'text' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'active' => $currentUrl == base_url('/dashboard'),
                'roles' => ['admin', 'staff']
            ]
        ];

        $masterMenu = [
            [
                'text' => 'Master Data',
                'url' => '#',
                'icon' => 'fas fa-database',
                'active' => strpos($currentUrl, '/items') !== false || 
                           strpos($currentUrl, '/suppliers') !== false ||
                           strpos($currentUrl, '/customers') !== false,
                'roles' => ['admin', 'staff'],
                'children' => [
                    [
                        'text' => 'Items',
                        'url' => '/items',
                        'icon' => 'fas fa-cube',
                        'active' => strpos($currentUrl, '/items') !== false,
                        'roles' => ['admin', 'staff']
                    ],
                    [
                        'text' => 'Suppliers',
                        'url' => '/suppliers',
                        'icon' => 'fas fa-truck',
                        'active' => strpos($currentUrl, '/suppliers') !== false,
                        'roles' => ['admin', 'staff']
                    ],
                    [
                        'text' => 'Customers',
                        'url' => '/customers',
                        'icon' => 'fas fa-users',
                        'active' => strpos($currentUrl, '/customers') !== false,
                        'roles' => ['admin', 'staff']
                    ]
                ]
            ]
        ];

        $transactionMenu = [
            [
                'text' => 'Transactions',
                'url' => '#',
                'icon' => 'fas fa-exchange-alt',
                'active' => strpos($currentUrl, '/purchase-orders') !== false || 
                           strpos($currentUrl, '/sales-orders') !== false,
                'roles' => ['admin', 'staff'],
                'children' => [
                    [
                        'text' => 'Purchase Orders',
                        'url' => '/purchase-orders',
                        'icon' => 'fas fa-shopping-cart',
                        'active' => strpos($currentUrl, '/purchase-orders') !== false,
                        'roles' => ['admin', 'staff']
                    ],
                    [
                        'text' => 'Goods Receipt',
                        'url' => '/goods-receipts',
                        'icon' => 'fas fa-warehouse',
                        'active' => strpos($currentUrl, '/goods-receipts') !== false,
                        'roles' => ['admin', 'staff']
                    ],
                    [
                        'text' => 'Sales Orders',
                        'url' => '/sales-orders',
                        'icon' => 'fas fa-file-invoice-dollar',
                        'active' => strpos($currentUrl, '/sales-orders') !== false,
                        'roles' => ['admin', 'staff']
                    ],
                    [
                        'text' => 'Delivery',
                        'url' => '/deliveries',
                        'icon' => 'fas fa-shipping-fast',
                        'active' => strpos($currentUrl, '/deliveries') !== false,
                        'roles' => ['admin', 'staff']
                    ]
                ]
            ]
        ];

        $reportMenu = [
            [
                'text' => 'Reports',
                'url' => '/reports',
                'icon' => 'fas fa-chart-bar',
                'active' => strpos($currentUrl, '/reports') !== false,
                'roles' => ['admin', 'staff']
            ]
        ];

        $adminMenu = [
            [
                'text' => 'Admin',
                'url' => '#',
                'icon' => 'fas fa-cogs',
                'active' => strpos($currentUrl, '/users') !== false ||
                           strpos($currentUrl, '/approvals') !== false,
                'roles' => ['admin'],
                'children' => [
                    [
                        'text' => 'User Management',
                        'url' => '/users',
                        'icon' => 'fas fa-user-cog',
                        'active' => strpos($currentUrl, '/users') !== false,
                        'roles' => ['admin']
                    ],
                    [
                        'text' => 'Approval Queue',
                        'url' => '/approvals',
                        'icon' => 'fas fa-check-circle',
                        'active' => strpos($currentUrl, '/approvals') !== false,
                        'roles' => ['admin']
                    ]
                ]
            ]
        ];

        $allMenu = array_merge($baseMenu, $masterMenu, $transactionMenu, $reportMenu);
        
        if ($userRole === 'admin') {
            $allMenu = array_merge($allMenu, $adminMenu);
        }

        // Filter menu berdasarkan role user
        return array_filter($allMenu, function($menu) use ($userRole) {
            return in_array($userRole, $menu['roles']);
        });
    }

    public function getUserMenu($userName, $userRole)
    {
        return [
            'isLoggedIn' => true,
            'userName' => $userName,
            'userRole' => $userRole,
            'dropdown' => [
                [
                    'text' => 'Profile',
                    'url' => '/profile',
                    'icon' => 'fas fa-user'
                ],
                [
                    'text' => 'Settings',
                    'url' => '/settings',
                    'icon' => 'fas fa-cog'
                ],
                [
                    'type' => 'divider'
                ],
                [
                    'text' => 'Logout',
                    'url' => '/logout',
                    'icon' => 'fas fa-sign-out-alt'
                ]
            ]
        ];
    }
}