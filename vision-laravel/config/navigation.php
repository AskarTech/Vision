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
            'items' => [
                [
                    'id' => 'view-dashboard',
                    'label' => 'لوحة الإدارة',
                    'icon' => '📊',
                    'url' => '#view-dashboard',
                ],
            ],
        ],
        [
            'label' => 'العمليات',
            'items' => [
                [
                    'id' => 'view-deposits',
                    'label' => 'الإيداعات',
                    'icon' => '💰',
                    'url' => '#view-deposits',
                ],
                [
                    'id' => 'view-withdrawals',
                    'label' => 'السحوبات',
                    'icon' => '💸',
                    'url' => '#view-withdrawals',
                ],
                [
                    'id' => 'view-orders',
                    'label' => 'الطلبات',
                    'icon' => '🛒',
                    'url' => '#view-orders',
                ],
            ],
        ],
        [
            'label' => 'المستخدمون',
            'items' => [
                [
                    'id' => 'view-customers',
                    'label' => 'المستخدمون',
                    'icon' => '👥',
                    'url' => '#view-customers',
                ],
                [
                    'id' => 'view-partners',
                    'label' => 'الشركاء',
                    'icon' => '🤝',
                    'url' => '#view-partners',
                ],
                [
                    'id' => 'view-managers',
                    'label' => 'المدراء',
                    'icon' => '👨‍💼',
                    'url' => '#view-managers',
                ],
            ],
        ],
        [
            'label' => 'المخزون والمنتجات',
            'items' => [
                [
                    'id' => 'view-products',
                    'label' => 'المنتجات',
                    'icon' => '📦',
                    'url' => '#view-products',
                ],
                [
                    'id' => 'view-inventory',
                    'label' => 'المخزون',
                    'icon' => '📋',
                    'url' => '#view-inventory',
                ],
            ],
        ],
        [
            'label' => 'المالية والتقارير',
            'items' => [
                [
                    'id' => 'view-finance',
                    'label' => 'المالية',
                    'icon' => '📈',
                    'url' => '#view-finance',
                ],
                [
                    'id' => 'view-audit',
                    'label' => 'المراجعات المالية',
                    'icon' => '🔍',
                    'url' => '#view-audit',
                ],
            ],
        ],
        [
            'label' => 'الدعم',
            'items' => [
                [
                    'id' => 'view-disputes',
                    'label' => 'النزاعات',
                    'icon' => '⚠️',
                    'url' => '#view-disputes',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Seller Dashboard Navigation
    |--------------------------------------------------------------------------
    */
    'seller' => [
        [
            'label' => 'الرئيسية',
            'items' => [
                [
                    'id' => 'seller-overview',
                    'label' => 'نظرة عامة',
                    'icon' => '📊',
                    'url' => '#seller-overview',
                ],
            ],
        ],
        [
            'label' => 'إدارة المخزون',
            'items' => [
                [
                    'id' => 'seller-networks',
                    'label' => 'الشبكات',
                    'icon' => '📡',
                    'url' => '#seller-networks',
                ],
                [
                    'id' => 'seller-packages',
                    'label' => 'الباقات',
                    'icon' => '📦',
                    'url' => '#seller-packages',
                ],
                [
                    'id' => 'seller-inventory',
                    'label' => 'المخزون',
                    'icon' => '📋',
                    'url' => '#seller-inventory',
                ],
            ],
        ],
        [
            'label' => 'المبيعات',
            'items' => [
                [
                    'id' => 'seller-orders',
                    'label' => 'الطلبات',
                    'icon' => '🛒',
                    'url' => '#seller-orders',
                ],
                [
                    'id' => 'seller-sales',
                    'label' => 'المبيعات',
                    'icon' => '💰',
                    'url' => '#seller-sales',
                ],
            ],
        ],
        [
            'label' => 'المالية',
            'items' => [
                [
                    'id' => 'seller-wallet',
                    'label' => 'المحفظة',
                    'icon' => '💳',
                    'url' => '#seller-wallet',
                ],
                [
                    'id' => 'seller-withdrawals',
                    'label' => 'السحوبات',
                    'icon' => '💸',
                    'url' => '#seller-withdrawals',
                ],
            ],
        ],
        [
            'label' => 'الإعدادات',
            'items' => [
                [
                    'id' => 'seller-settings',
                    'label' => 'الإعدادات',
                    'icon' => '⚙️',
                    'url' => '#seller-settings',
                ],
            ],
        ],
    ],
];
