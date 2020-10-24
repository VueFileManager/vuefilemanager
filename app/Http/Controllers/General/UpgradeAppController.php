<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Page;
use App\Setting;
use Artisan;
use Illuminate\Http\Request;
use Schema;

class UpgradeAppController extends Controller
{
    /**
     * Upgrade account from 1.6 to 1.7
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function upgrade(Request $request)
    {
        $upgraded = Setting::where('name', 'latest_upgrade')->first();

        if ($upgraded && $upgraded->value === '1.7') abort(401);

        // Create legal pages and index content
        if ($request->license === 'Extended') {

            $pages = collect(config('content.pages'));
            $content = collect(config('content.content'));

            $content->each(function ($content) {
                Setting::updateOrCreate($content);
            });

            $pages->each(function ($page) {
                Page::updateOrCreate($page);
            });
        }

        // Store Logo
        if ($request->hasFile('logo')) {
            $logo = store_system_image($request->file('logo'), 'system');
        }

        // Store Logo horizontal
        if ($request->hasFile('logo_horizontal')) {
            $logo_horizontal = store_system_image($request->file('logo_horizontal'), 'system');
        }

        // Store favicon
        if ($request->hasFile('favicon')) {
            $favicon = store_system_image($request->file('favicon'), 'system');
        }

        // Get options
        $settings = collect([
            [
                'name'  => 'setup_wizard_database',
                'value' => 1,
            ],
            [
                'name'  => 'setup_wizard_success',
                'value' => 1,
            ],
            [
                'name'  => 'license',
                'value' => $request->license,
            ],
            [
                'name'  => 'purchase_code',
                'value' => $request->purchase_code,
            ],
            [
                'name'  => 'app_title',
                'value' => $request->title,
            ],
            [
                'name'  => 'app_description',
                'value' => $request->description,
            ],
            [
                'name'  => 'app_logo',
                'value' => $request->hasFile('logo') ? $logo : null,
            ],
            [
                'name'  => 'app_logo_horizontal',
                'value' => $request->hasFile('logo_horizontal') ? $logo_horizontal : null,
            ],
            [
                'name'  => 'app_favicon',
                'value' => $request->hasFile('favicon') ? $favicon : null,
            ],
            [
                'name'  => 'google_analytics',
                'value' => $request->googleAnalytics,
            ],
            [
                'name'  => 'contact_email',
                'value' => $request->contactMail,
            ],
            [
                'name'  => 'registration',
                'value' => $request->userRegistration,
            ],
            [
                'name'  => 'storage_limitation',
                'value' => $request->storageLimitation,
            ],
            [
                'name'  => 'storage_default',
                'value' => $request->defaultStorage ? $request->defaultStorage : 5,
            ],
            [
                'name'  => 'latest_upgrade',
                'value' => '1.7',
            ],
        ]);

        // Store options
        $settings->each(function ($col) {
            Setting::updateOrCreate(['name' => $col['name']], $col);
        });

        return response('Done', 200);
    }

    /**
     *  Start maintenance mode
     */
    public function up() {
        $command = Artisan::call('up');

        if ($command === 0) {
            echo 'System is in production mode';
        }
    }

    /**
     *  End maintenance mode
     */
    public function down() {
        $command = Artisan::call('down');

        if ($command === 0) {
            echo 'System is in maintenance mode';
        }
    }

    /**
     *  Upgrade database
     */
    public function upgrade_database()
    {
        /*
         * Upgrade expire_in in shares table
         *
         * @since v1.7.9
        */
        if (! Schema::hasColumn('shares', 'expire_in')) {

            $command = Artisan::call('migrate', [
                '--force' => true
            ]);

            if ($command === 0) {
                echo 'Operation was successful.';
            }

            if ($command === 1) {
                echo 'Operation failed.';
            }
        }

        /*
         * Upgrade expire_in in shares table
         *
         * @since v1.7.11
        */
        if (! Schema::hasColumn('file_manager_files', 'metadata')) {

            $command = Artisan::call('migrate', [
                '--force' => true
            ]);

            if ($command === 0) {
                echo 'Operation was successful.';
            }

            if ($command === 1) {
                echo 'Operation failed.';
            }
        }
    }
}
