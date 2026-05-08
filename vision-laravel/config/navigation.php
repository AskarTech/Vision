<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard Navigation
    |--------------------------------------------------------------------------
    */
    'admin' => [
        [
            'label' => 'الرئيسية',
            'route' => 'admin.dashboard',
            'icon' => 'home',
            'permission' => 'view_dashboard',
        ],
        [
            'label' => 'الإيداعات',
            'route' => 'admin.topups.index',
            'icon' => 'banknotes',
            'permission' => 'manage_topups',
        ],
        [
            'label' => 'المستخدمون',
            'route' => 'admin.customers.index',
            'icon' => 'users',
            'permission' => 'manage_customers',
        ],
        [
            'label' => 'البائعون',
            'route' => 'admin.sellers.index',
            'icon' => 'store',
            'permission' => 'manage_sellers',
        ],
        [
            'label' => 'الشبكات',
            'route' => 'admin.networks.index',
            'icon' => 'wifi',
            'permission' => 'manage_sellers',
        ],
        [
            'label' => 'الباقات',
            'route' => 'admin.packages.index',
            'icon' => 'cube',
            'permission' => 'manage_packages',
        ],
        [
            'label' => 'المخزون',
            'route' => 'admin.inventory.index',
            'icon' => 'inbox',
            'permission' => 'manage_inventory',
        ],
        [
            'label' => 'الطلبات',
            'route' => 'admin.orders.index',
            'icon' => 'shopping-cart',
            'permission' => 'manage_orders',
        ],
        [
            'label' => 'السحوبات',
            'route' => 'admin.withdrawals.index',
            'icon' => 'arrow-down-tray',
            'permission' => 'manage_withdrawals',
        ],
        [
            'label' => 'النزاعات',
            'route' => 'admin.disputes.index',
            'icon' => 'exclamation-triangle',
            'permission' => 'manage_disputes',
        ],
        [
            'label' => 'التقارير',
            'route' => 'admin.reports.index',
            'icon' => 'chart-bar',
            'permission' => 'view_reports',
        ],
        [
            'label' => 'سجل التدقيق',
            'route' => 'admin.audit.index',
            'icon' => 'document-text',
            'permission' => 'view_audit_logs',
        ],
        [
            'label' => 'الإعدادات',
            'route' => 'admin.settings.index',
            'icon' => 'cog-6-tooth',
            'permission' => 'manage_settings',
        ],
        [
            'label' => 'الإشعارات',
            'route' => 'admin.notifications.index',
            'icon' => 'banknotes',
            'permission' => 'view_dashboard',
        ],
        [
            'label' => 'الأدوار والصلاحيات',
            'route' => 'admin.roles.index',
            'icon' => 'users',
            'permission' => 'manage_settings',
        ],
        [
            'label' => 'مراقبة النشاط',
            'route' => 'admin.activity.index',
            'icon' => 'chart-bar',
            'permission' => 'view_audit_logs',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Seller Dashboard Navigation
    |--------------------------------------------------------------------------
    */
    'seller' => [
        [
            'label' => 'نظرة عامة',
            'route' => 'seller.dashboard',
            'icon' => 'home',
            'permission' => 'view_dashboard',
        ],
        [
            'label' => 'الشبكات',
            'route' => 'seller.networks.index',
            'icon' => 'wifi',
            'permission' => 'manage_networks',
        ],
        [
            'label' => 'الباقات',
            'route' => 'seller.packages.index',
            'icon' => 'cube',
            'permission' => 'manage_packages',
        ],
        [
            'label' => 'المخزون',
            'route' => 'seller.inventory.index',
            'icon' => 'inbox',
            'permission' => 'manage_inventory',
        ],
        [
            'label' => 'الطلبات',
            'route' => 'seller.orders.index',
            'icon' => 'shopping-cart',
            'permission' => 'view_orders',
        ],
        [
            'label' => 'المبيعات',
            'route' => 'seller.sales.index',
            'icon' => 'chart-pie',
            'permission' => 'view_sales',
        ],
        [
            'label' => 'رفع البطاقات',
            'route' => 'seller.inventory.create',
            'icon' => 'inbox',
            'permission' => 'manage_inventory',
        ],
        [
            'label' => 'السحوبات',
            'route' => 'seller.withdrawals.index',
            'icon' => 'arrow-down-tray',
            'permission' => 'request_withdrawal',
        ],
        [
            'label' => 'المحفظة',
            'route' => 'seller.wallet.index',
            'icon' => 'wallet',
            'permission' => 'view_wallet',
        ],
        [
            'label' => 'الإعدادات',
            'route' => 'seller.settings.index',
            'icon' => 'cog-6-tooth',
            'permission' => 'view_dashboard',
        ],
    ],
];
