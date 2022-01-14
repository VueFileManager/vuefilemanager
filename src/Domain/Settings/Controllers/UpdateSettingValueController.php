<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class UpdateSettingValueController extends Controller
{
    /**
     * Update setting item.
     */
    public function __invoke(Request $request): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        // Store image if exist
        if ($request->hasFile($request->input('name'))) {
            // Find and update image path
            Setting::updateOrCreate([
                'name' => $request->input('name'),
            ], [
                'value' => store_system_image($request, $request->input('name')),
            ]);

            return response('Done', 204);
        }

        // Set paypal live option
        if ($request->input('name') === 'paypal_live') {
            setEnvironmentValue([
                'PAYPAL_IS_LIVE' => $request->input('value') ? 'true' : 'false',
            ]);

            // Clear config cache
            if (! is_dev()) {
                Artisan::call('config:clear');
                Artisan::call('config:cache');
            }

            return response('Done', 204);
        }

        // Find and update variable
        Setting::updateOrCreate(
            ['name' => $request->input('name')],
            ['value' => $request->input('value')]
        );

        return response('Done', 204);
    }
}
