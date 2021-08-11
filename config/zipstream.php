<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    // Default options for our archives
    'archive' => [
        'predict' => env('ZIPSTREAM_PREDICT_SIZE', true),
    ],

    // Default options for files added
    'file'    => [
        'method' => env('ZIPSTREAM_FILE_METHOD', 'store'),

        'deflate' => env('ZIPSTREAM_FILE_DEFLATE'),
    ],

    // Configs for S3 files
    'aws'     => [
        'credentials'             => [
            'key'    => env('S3_ACCESS_KEY_ID'),
            'secret' => env('S3_SECRET_ACCESS_KEY'),
        ],
        'version'                 => 'latest',
        'endpoint'                => env('S3_URL'),
        'use_path_style_endpoint' => env('ZIPSTREAM_S3_PATH_STYLE_ENDPOINT', false),
        'region'                  => env('ZIPSTREAM_S3_REGION', env('S3_DEFAULT_REGION', 'us-east-1')),
    ],

    // https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials_anonymous.html
    'aws_anonymous_client' => env('S3_ANONYMOUS', false),
];
