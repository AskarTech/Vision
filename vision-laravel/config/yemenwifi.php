<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Reservation Timeout
    |--------------------------------------------------------------------------
    |
    | The number of minutes a card reservation remains valid before being
    | automatically released. This prevents cards from being held indefinitely
    | during the checkout process.
    |
    */
    'reservation_timeout_minutes' => env('RESERVATION_TIMEOUT_MINUTES', 15),

    /*
    |--------------------------------------------------------------------------
    | Platform Commission Rate
    |--------------------------------------------------------------------------
    |
    | The default commission rate the platform takes from each sale.
    | Expressed as a decimal (0.10 = 10%).
    |
    */
    'commission_rate' => env('PLATFORM_COMMISSION_RATE', 0.10),

    /*
    |--------------------------------------------------------------------------
    | Wallet Transaction Types
    |--------------------------------------------------------------------------
    |
    | Available transaction types for wallet operations.
    |
    */
    'transaction_types' => [
        'deposit' => 'Deposit',
        'withdrawal' => 'Withdrawal',
        'purchase' => 'Purchase',
        'refund' => 'Refund',
        'adjustment' => 'Adjustment',
        'commission' => 'Commission',
        'payout' => 'Payout to Seller',
    ],

    /*
    |--------------------------------------------------------------------------
    | Order Statuses
    |--------------------------------------------------------------------------
    |
    | Available statuses for card orders.
    |
    */
    'order_statuses' => [
        'reserved' => 'Reserved',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
    ],

    /*
    |--------------------------------------------------------------------------
    | Card Statuses
    |--------------------------------------------------------------------------
    |
    | Available statuses for inventory cards.
    |
    */
    'card_statuses' => [
        'available' => 'Available',
        'reserved' => 'Reserved',
        'sold' => 'Sold',
    ],
];
