<?php
namespace Domain\Homepage\Controllers;

use DB;
use PDOException;
use Domain\Pages\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class IndexController
{
    /**
     * Show index page
     */
    public function __invoke(): Application|Factory|View
    {
        try {
            // Try to connect to database
            DB::getPdo();

            // Get setup status
            $setup_status = get_setup_status();

            // Get app pages
            $pages = Page::all();

            // Get all settings
            $settings = get_settings_in_json();
        } catch (PDOException $e) {
            $setup_status = 'setup-database';

            // Required parameters
            $upload_max_filesize = 128;
            $post_max_size = 128;
            $memory_limit = 512;
            $max_execution_time = 600;
            $php_version = '8.0';

            $status_check = [
                'modules'     => [
                    'tokenizer' => extension_loaded('tokenizer'),
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

        return view('index')
            ->with('status_check', $status_check ?? [])
            ->with('settings', $settings ?? null)
            ->with('legal', $pages ?? null)
            ->with('installation', $setup_status);
    }
}
