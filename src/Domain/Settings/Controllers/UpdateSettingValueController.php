<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class UpdateSettingValueController extends Controller
{
    /**
     * Update setting item.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $message = [
            'type'    => 'success',
            'message' => 'The value was successfully updated',
        ];

        $inputName = $request->input('name');

        // Abort in demo mode
        if (is_demo()) {
            return response()->json($message);
        }

        // Store image if exist
        if ($request->hasFile($inputName)) {
            // Find and update image path
            Setting::updateOrCreate([
                'name' => $inputName,
            ], [
                'value' => store_system_image($request, $inputName),
            ]);

            return response()->json($message);
        }

        // Set paypal live option
        if ($inputName === 'paypal_live') {
            setEnvironmentValue([
                'PAYPAL_IS_LIVE' => $request->input('value') ? 'true' : 'false',
            ]);

            // Clear config cache
            if (! is_dev()) {
                Artisan::call('config:clear');
                Artisan::call('config:cache');
            }

            return response()->json($message);
        }

        // Clear language cache
        if ($inputName === 'language') {
            cache()->forget('language');
        }

        // Find and update variable
        Setting::updateOrCreate(
            ['name' => $inputName],
            ['value' => $request->input('value')]
        );

        return response()->json($message);
    }
}
