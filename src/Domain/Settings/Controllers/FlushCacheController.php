<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class FlushCacheController extends Controller
{
    /**
     * Clear application cache
     */
    public function __invoke(): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if (! app()->runningUnitTests()) {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
