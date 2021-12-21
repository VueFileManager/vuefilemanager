<?php

return [
    'version' => '2.0',

    'is_demo' => env('APP_DEMO', false),

    // Define size of chunk uploaded by MB.
    // E.g. integer 128 means chunk size will be 128 MB.
    'chunk_size' => env('CHUNK_SIZE', '128'),

    'colors' => [
        '#9ad2bf', '#9ad2cd', '#d29a9a', '#d2ce9a', '#9aadd2', '#c59ad2',
    ],

    'avatar_sizes' => [
        [
            'size' => 160,
            'name' => 'md',
        ],
        [
            'size' => 60,
            'name' => 'sm',
        ],
        [
            'size' => 42,
            'name' => 'xs',
        ],
    ],

    'image_sizes' => [
        'immediately' => [
            [
                'size' => 120,
                'name' => 'xs',
            ],
            [
                'size' => 240,
                'name' => 'sm',
            ],
        ],

        'later' => [
            [
                'size' => 480,
                'name' => 'md',
            ],
            [
                'size' => 960,
                'name' => 'lg',
            ],
            [
                'size' => 1440,
                'name' => 'xl',
            ],
        ],
    ],
];
