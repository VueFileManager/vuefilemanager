<?php

return [
    'driver'            => env('SUBSCRIPTION_DRIVER', 'stripe'),

    /*
     * Activate drivers to handle subscription
     */
    'available_drivers' => [
        'paystack',
        'paypal',
        'stripe',
    ],
    'metered_billing'   => [
        'settlement_period' => 30,
        /*
         * Drivers which have native support for metered billing.
         * This native support doesn't use subscription package credit system, but prefer
         * auto-renew and maintain metered subscription on behalf of external service.
         */
        'native_support' => [
            'stripe',
        ],
    ],
    'paystack'          => [
        /*
         * List of allowed ip address to verify paystack webhook request
         */
        'allowed_ips' => [
            '52.214.14.220',
            '52.49.173.169',
            '52.31.139.75',
        ],
    ],

    /*
     * Get gateway credentials
     */
    'credentials'       => [
        'stripe'   => [
            'secret'     => env('STRIPE_SECRET_KEY'),
            'public_key' => env('STRIPE_PUBLIC_KEY'),
        ],
        'paystack' => [
            'secret'     => env('PAYSTACK_SECRET'),
            'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        ],
        'paypal'   => [
            'id'         => env('PAYPAL_CLIENT_ID'),
            'secret'     => env('PAYPAL_CLIENT_SECRET'),
            'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        ],
    ],
];
