<?php
namespace Domain\SetupWizard\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
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
    ): JsonResponse {
        collect([
            [
                'name'  => 'subscription_type',
                'value' => $request->input('subscriptionType') ?? 'none',
            ],
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
                'name'  => 'registration',
                'value' => $request->input('userRegistration'),
            ],
            [
                'name'  => 'storage_limitation',
                'value' => $request->input('storageLimitation'),
            ],
            [
                'name'  => 'default_max_storage_amount',
                'value' => $request->input('defaultStorage') ?? 5,
            ],
            [
                'name'  => 'default_max_team_member',
                'value' => $request->input('teamsDefaultMembers') ?? 10,
            ],
            [
                'name'  => 'user_verification',
                'value' => 0,
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

        return response()->json([
            'type'    => 'success',
            'message' => 'The app settings was set successfully',
        ]);
    }
}
