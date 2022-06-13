<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class FlushCacheController extends Controller
{
    /**
     * Clear application cache
     */
    public function __invoke(): JsonResponse
    {
        $message = [
            'type'    => 'success',
            'message' => 'The cache was successfully flushed',
        ];

        // Abort in demo mode
        if (is_demo()) {
            return response()->json($message);
        }

        if (! app()->runningUnitTests()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response()->json($message);
    }
}
