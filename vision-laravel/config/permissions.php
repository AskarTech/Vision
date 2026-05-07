<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Permission Definitions
    |--------------------------------------------------------------------------
    |
    | Define all permissions used in the application. Each permission has:
    | - name: Unique identifier
    | - description: Human-readable description
    | - roles: Array of roles that have this permission by default
    |
    */

    'admin' => [
        'view_dashboard' => [
            'description' => 'View admin dashboard',
            'roles' => ['admin'],
        ],
        'manage_topups' => [
            'description' => 'Review and approve customer topups',
            'roles' => ['admin'],
        ],
        'manage_customers' => [
            'description' => 'Manage customer accounts',
            'roles' => ['admin'],
        ],
        'manage_sellers' => [
            'description' => 'Manage seller accounts and networks',
            'roles' => ['admin'],
        ],
        'manage_packages' => [
            'description' => 'Manage package definitions',
            'roles' => ['admin'],
        ],
        'manage_inventory' => [
            'description' => 'Manage card inventory',
            'roles' => ['admin'],
        ],
        'manage_orders' => [
            'description' => 'View and manage orders',
            'roles' => ['admin'],
        ],
        'manage_withdrawals' => [
            'description' => 'Process seller withdrawal requests',
            'roles' => ['admin'],
        ],
        'manage_disputes' => [
            'description' => 'Handle customer disputes',
            'roles' => ['admin'],
        ],
        'view_audit_logs' => [
            'description' => 'View system audit logs',
            'roles' => ['admin'],
        ],
        'view_reports' => [
            'description' => 'View financial and operational reports',
            'roles' => ['admin'],
        ],
        'manage_settings' => [
            'description' => 'Manage system settings',
            'roles' => ['admin'],
        ],
    ],

    'seller' => [
        'view_dashboard' => [
            'description' => 'View seller dashboard',
            'roles' => ['seller', 'seller_manager'],
        ],
        'manage_networks' => [
            'description' => 'Manage own networks',
            'roles' => ['seller', 'seller_manager'],
        ],
        'manage_packages' => [
            'description' => 'Manage own packages',
            'roles' => ['seller', 'seller_manager'],
        ],
        'manage_inventory' => [
            'description' => 'Manage own card inventory',
            'roles' => ['seller', 'seller_manager'],
        ],
        'view_orders' => [
            'description' => 'View own orders',
            'roles' => ['seller', 'seller_manager'],
        ],
        'view_sales' => [
            'description' => 'View sales analytics',
            'roles' => ['seller', 'seller_manager'],
        ],
        'request_withdrawal' => [
            'description' => 'Request withdrawals',
            'roles' => ['seller', 'seller_manager'],
        ],
        'view_wallet' => [
            'description' => 'View wallet balance and transactions',
            'roles' => ['seller', 'seller_manager'],
        ],
    ],

    'customer' => [
        'view_dashboard' => [
            'description' => 'View customer dashboard',
            'roles' => ['customer'],
        ],
        'purchase_cards' => [
            'description' => 'Purchase internet cards',
            'roles' => ['customer'],
        ],
        'view_orders' => [
            'description' => 'View own orders',
            'roles' => ['customer'],
        ],
        'view_wallet' => [
            'description' => 'View wallet balance and transactions',
            'roles' => ['customer'],
        ],
        'request_topup' => [
            'description' => 'Request wallet topup',
            'roles' => ['customer'],
        ],
    ],
];
