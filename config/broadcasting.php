<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "reverb", "pusher", "ably", "redis", "log", "null"
    |
    | BROADCAST_DRIVER is still supported as a fallback for older installations.
    |
    */

    'default' => env('BROADCAST_CONNECTION', env('BROADCAST_DRIVER', 'null')),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over WebSockets. Samples of
    | each available type of connection are provided inside this array.
    |
    | "reverb" is the self-hosted, Pusher protocol compatible websocket server
    | (php artisan reverb:start), "pusher" is the hosted Pusher service.
    |
    */

    'connections' => [

        'reverb' => [
            'driver'  => 'reverb',
            'key'     => env('REVERB_APP_KEY'),
            'secret'  => env('REVERB_APP_SECRET'),
            'app_id'  => env('REVERB_APP_ID'),
            'options' => [
                'host'   => env('REVERB_HOST'),
                'port'   => env('REVERB_PORT', 443),
                'scheme' => env('REVERB_SCHEME', 'https'),
                'useTLS' => env('REVERB_SCHEME', 'https') === 'https',
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
            // Public websocket endpoint handed to the browser (Laravel Echo). Useful when the
            // application reaches the Reverb server internally on a different host/port.
            'frontend' => [
                'host'   => env('REVERB_FRONTEND_HOST') ?: env('REVERB_HOST'),
                'port'   => env('REVERB_FRONTEND_PORT') ?: env('REVERB_PORT', 443),
                'scheme' => env('REVERB_FRONTEND_SCHEME') ?: env('REVERB_SCHEME', 'https'),
            ],
        ],

        'pusher' => [
            'driver'  => 'pusher',
            'key'     => env('PUSHER_APP_KEY'),
            'secret'  => env('PUSHER_APP_SECRET'),
            'app_id'  => env('PUSHER_APP_ID'),
            // Null values are removed, so the hosted Pusher service resolves its host from the cluster
            'options' => array_filter([
                'cluster'   => env('PUSHER_APP_CLUSTER'),
                'encrypted' => true,
                'host'      => env('PUSHER_APP_HOST') ?: null,
                'port'      => env('PUSHER_APP_PORT') ?: null,
                'scheme'    => env('PUSHER_APP_TLS', true) ? 'https' : 'http',
                'useTLS'    => (bool) env('PUSHER_APP_TLS', true),
            ], fn ($value) => ! is_null($value)),
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key'    => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
