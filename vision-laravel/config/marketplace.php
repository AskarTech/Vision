<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Points ↔ cash (Stage 6 / Stage 10 controls)
    |--------------------------------------------------------------------------
    |
    | Economic meaning: each 1000 points redeem toward checkout as this much
    | wallet currency (same unit as package prices). Set to 0 to disable redemption.
    |
    */
    'points' => [
        'cash_equivalent_per_1000' => (float) env('MARKETPLACE_POINTS_CASH_PER_1000', 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | External gateway placeholders (Stage 6 adapters)
    |--------------------------------------------------------------------------
    |
    | Product codes align with card_orders.payment_channel enum where possible.
    | Driver classes are wired when real integrations ship.
    |
    */
    'payment_gateways' => [
        'floosak' => [
            'label' => 'Floosak',
            'driver' => env('PAYMENT_FLOOSAK_DRIVER', 'stub'),
        ],
        'jawali' => [
            'label' => 'Jawali',
            'driver' => env('PAYMENT_JAWALI_DRIVER', 'stub'),
        ],
        'kuraimi' => [
            'label' => 'Kuraimi',
            'driver' => env('PAYMENT_KURAIMI_DRIVER', 'stub'),
        ],
        'onecash' => [
            'label' => 'OneCash',
            'driver' => env('PAYMENT_ONECASH_DRIVER', 'stub'),
        ],
        'cash' => [
            'label' => 'Cash / OTC',
            'driver' => env('PAYMENT_CASH_DRIVER', 'stub'),
        ],
    ],

];
