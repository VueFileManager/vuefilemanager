<?php
namespace Domain\SetupWizard\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Domain\SetupWizard\Requests\StoreAppSetupRequest;

class StoreAppSettingsController extends Controller
{
    /**
     * Store app settings
     */
    public function __invoke(
        StoreAppSetupRequest $request
    ): Response {
        collect([
            [
                'name'  => 'app_color',
                'value' => $request->input('color'),
            ],
            [
                'name'  => 'app_title',
                'value' => $request->input('title'),
            ],
            [
                'name'  => 'app_description',
                'value' => $request->input('description'),
            ],
            [
                'name'  => 'app_logo',
                'value' => store_system_image($request, 'logo'),
            ],
            [
                'name'  => 'app_logo_dark',
                'value' => store_system_image($request, 'logo_dark'),
            ],
            [
                'name'  => 'app_logo_horizontal',
                'value' => store_system_image($request, 'logo_horizontal'),
            ],
            [
                'name'  => 'app_logo_horizontal_dark',
                'value' => store_system_image($request, 'logo_horizontal_dark'),
            ],
            [
                'name'  => 'app_favicon',
                'value' => store_system_image($request, 'favicon'),
            ],
            [
                'name'  => 'app_og_image',
                'value' => store_system_image($request, 'og_image'),
            ],
            [
                'name'  => 'app_touch_icon',
                'value' => store_system_image($request, 'touch_icon'),
            ],
            [
                'name'  => 'google_analytics',
                'value' => $request->input('googleAnalytics'),
            ],
            [
                'name'  => 'contact_email',
                'value' => $request->input('contactMail'),
            ],
            [
                'name'  => 'storage_limitation',
                'value' => $request->input('storageLimitation'),
            ],
            [
                'name'  => 'default_max_storage_amount',
                'value' => $request->input('defaultStorage') ?? 5,
            ],
        ])->each(function ($col) {
            Setting::updateOrCreate([
                'name'  => $col['name'],
            ], [
                'value' => $col['value'],
            ]);
        });

        if (! app()->runningUnitTests()) {
            setEnvironmentValue([
                'APP_NAME' => Str::camel($request->input('title')),
            ]);
        }

        return response('Done', 204);
    }
}
