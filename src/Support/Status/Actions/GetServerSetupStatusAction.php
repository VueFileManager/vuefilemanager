<?php
namespace Support\Status\Actions;

class GetServerSetupStatusAction
{
    public function __invoke(): array
    {
        // Required parameters
        $upload_max_filesize = 128;
        $post_max_size = 128;
        $memory_limit = 512;
        $max_execution_time = 120;
        $php_version = '8.1';

        // Writable
        $storageDirectory = dirname(storage_path('/storage'));
        $bootstrapDirectory = dirname(storage_path('/bootstrap'));
        $envFile = dirname(storage_path('/.env'));

        return [
            'writable' => [
                'bootstrap' => is_writable($bootstrapDirectory),
                'storage'   => is_writable($storageDirectory),
                'env'       => is_writable($envFile),
            ],
            'modules'     => [
                'tokenizer' => extension_loaded('tokenizer'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'fileinfo'  => extension_loaded('fileinfo'),
                'mbstring'  => extension_loaded('mbstring'),
                'openssl'   => extension_loaded('openssl'),
                'sqlite3'   => extension_loaded('sqlite3'),
                'bcmath'    => extension_loaded('bcmath'),
                'ctype'     => extension_loaded('ctype'),
                'json'      => extension_loaded('json'),
                'exif'      => extension_loaded('exif'),
                'intl'      => extension_loaded('intl'),
                'pdo'       => extension_loaded('pdo'),
                'xml'       => extension_loaded('xml'),
                'gd'        => extension_loaded('gd'),
            ],
            'ini'         => [
                'upload_max_filesize' => [
                    'current' => intval(ini_get('upload_max_filesize')),
                    'minimal' => $upload_max_filesize,
                    'status'  => intval(ini_get('upload_max_filesize')) >= $upload_max_filesize,
                ],
                'post_max_size'       => [
                    'current' => intval(ini_get('post_max_size')),
                    'minimal' => $post_max_size,
                    'status'  => intval(ini_get('post_max_size')) >= $post_max_size,
                ],
                'memory_limit'        => [
                    'current' => intval(ini_get('memory_limit')),
                    'minimal' => $memory_limit,
                    'status'  => intval(ini_get('memory_limit')) >= $memory_limit,
                ],
                'max_execution_time'  => [
                    'current' => intval(ini_get('max_execution_time')),
                    'minimal' => $max_execution_time,
                    'status'  => intval(ini_get('max_execution_time')) >= $max_execution_time,
                ],
            ],
            'php_version' => [
                'acceptable' => version_compare(PHP_VERSION, $php_version, '>='),
                'current'    => phpversion(),
                'minimal'    => $php_version,
            ],
        ];
    }
}
