<?php

return [
    // Try to predict the zip size up front and send a Content-Length header
    'predict_size' => env('ZIPSTREAM_PREDICT_SIZE', true),

    // Compression method used only if we don't (or can't) predict the zip size
    'compression_method' => env('ZIPSTREAM_COMPRESSION_METHOD', env('ZIPSTREAM_FILE_METHOD', 'store')),

    // Remove all non-ascii characters from filenames
    'ascii_filenames' => env('ZIPSTREAM_ASCII_FILENAMES', false),

    // What to do when a file with the same zip path is added twice. Options are 'skip', 'replace', 'rename'
    'conflict_strategy' => env('ZIPSTREAM_CONFLICT_STRATEGY', 'rename'),

    // Don't allow 'Text.txt' and 'text.TXT' by default
    'case_insensitive_conflicts' => env('ZIPSTREAM_CASE_INSENSITIVE_CONFLICTS', true),

    // Configs for S3 files
    'aws' => [
        'credentials' => [
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
