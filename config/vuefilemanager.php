<?php

return [
    'version' => '2.2.7',

    'is_demo' => env('APP_DEMO', false),

    'is_prefilled_users'          => env('IS_PREFILLED_USERS', false),
    'is_setup_wizard_demo'        => env('IS_SETUP_WIZARD_DEMO', false),
    'is_setup_wizard_debug'       => env('IS_SETUP_WIZARD_DEBUG', false),
    'is_admin_vuefilemanager_bar' => env('IS_ADMIN_VUEFILEMANAGER_BAR', true),

    'colors' => [
        '#1BE7FF',
        '#6eeb83',
        '#e4ff1a',
        '#E8AA14',
        '#FF5714',
        '#541388',
        '#D90368',
        '#F1E9DA',
        '#2E294E',
        '#FFD400',
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
                'size' => 960,
                'name' => 'lg',
            ],
            [
                'size' => 1440,
                'name' => 'xl',
            ],
        ],
    ],

    'paginate' => [
        'perPage' => env('PAGINATE_RECORDS_PER_PAGE', 50),
    ],

    // The update versions which need to run upgrade process
    'updates' => [
        '2_0_10',
        '2_0_13',
        '2_0_14',
        '2_0_16',
        '2_1_1',
        '2_1_2',
        '2_2_0',
        '2_2_0_13',
        '2_2_1',
        '2_2_2',
        '2_2_3',
        '2_2_4',
    ],
];
