<?php

return [
    /*
     * Get gateway credentials
     */
    'credentials'       => [
        'stripe'   => [
            'secret'      => env('STRIPE_SECRET_KEY'),
            'public_key'  => env('STRIPE_PUBLIC_KEY'),
            'webhook_key' => env('STRIPE_WEBHOOK_SECRET'),
        ],
        'paystack' => [
            'secret'     => env('PAYSTACK_SECRET'),
            'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        ],
        'paypal'   => [
            'id'         => env('PAYPAL_CLIENT_ID'),
            'secret'     => env('PAYPAL_CLIENT_SECRET'),
            'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
            'is_live'    => env('PAYPAL_IS_LIVE', false),
        ],
    ],

    /*
     * App default middlewares. Rewrite if you need your custom middleware
     */
    'middlewares' => [
        'admin' => \VueFileManager\Subscription\Support\Middleware\AdminCheck::class,
    ],

    /*
     * App default notifications. Rewrite if you need your custom notification
     */
    'notifications' => [
        'ChargeFromCreditCardFailedAgainNotification' => \VueFileManager\Subscription\Domain\FailedPayments\Notifications\ChargeFromCreditCardFailedAgainNotification::class,
        'DunningEmailToCoverAccountUsageNotification' => \VueFileManager\Subscription\Domain\DunningEmails\Notifications\DunningEmailToCoverAccountUsageNotification::class,
        'ConfirmStripePaymentNotification'            => \VueFileManager\Subscription\Support\Miscellaneous\Stripe\Notifications\ConfirmStripePaymentNotification::class,
        'ChargeFromCreditCardFailedNotification'      => \VueFileManager\Subscription\Domain\FailedPayments\Notifications\ChargeFromCreditCardFailedNotification::class,
        'SubscriptionWasCreatedNotification'          => \VueFileManager\Subscription\Domain\Subscriptions\Notifications\SubscriptionWasCreatedNotification::class,
        'BillingAlertTriggeredNotification'           => \VueFileManager\Subscription\Domain\BillingAlerts\Notifications\BillingAlertTriggeredNotification::class,
        'InsufficientBalanceNotification'             => \VueFileManager\Subscription\Domain\Credits\Notifications\InsufficientBalanceNotification::class,
        'BonusCreditAddedNotification'                => \VueFileManager\Subscription\Domain\Credits\Notifications\BonusCreditAddedNotification::class,
    ],

    'metered_billing' => [
        'settlement_period' => 30,

        'fraud_prevention_mechanism' => [
            'usage_bigger_than_balance'   => [
                'active' => true,
            ],
            'limit_usage_in_new_accounts' => [
                'active' => true,
                'amount' => 5,
            ],
        ],
    ],

    'is_demo'  => env('APP_DEMO', false),
    'is_local' => env('APP_ENV', 'production') === 'local',
];
