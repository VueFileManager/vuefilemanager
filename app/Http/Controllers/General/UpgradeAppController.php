<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Page;
use App\Setting;
use Artisan;
use Illuminate\Http\Request;

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
}
