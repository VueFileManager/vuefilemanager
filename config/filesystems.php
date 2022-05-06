<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],
        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        's3' => [
            'driver'     => 's3',
            'key'        => env('S3_ACCESS_KEY_ID'),
            'secret'     => env('S3_SECRET_ACCESS_KEY'),
            'region'     => env('S3_DEFAULT_REGION'),
            'bucket'     => env('S3_BUCKET'),
            'endpoint'   => env('S3_URL'),
            'visibility' => 'private',
        ],
        'ftp' => [
            'driver'   => 'ftp',
            'host'     => env('FTP_HOST'),
            'username' => env('FTP_USERNAME'),
            'password' => env('FTP_PASSWORD'),
        ],
        'azure' => [
            'driver'            => 'azure',
            'name'              => env('AZURE_STORAGE_NAME'),
            'key'               => env('AZURE_STORAGE_KEY'),
            'container'         => env('AZURE_STORAGE_CONTAINER'),
            'url'               => env('AZURE_STORAGE_URL'),
            'prefix'            => null,
            'connection_string' => env('AZURE_STORAGE_CONNECTION_STRING'), // optional, will override default endpoint builder
        ],
    ],
];
