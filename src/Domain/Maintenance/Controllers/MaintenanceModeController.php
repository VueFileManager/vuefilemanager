<?php
namespace Domain\Maintenance\Controllers;

use Gate;
use Artisan;
use App\Http\Controllers\Controller;

class MaintenanceModeController extends Controller
{
    /**
     *  Start maintenance mode
     */
    public function up()
    {
        // Check admin permission
        Gate::authorize('maintenance');

        $command = Artisan::call('up');

        if ($command === 0) {
            echo 'System is in production mode';
        }
    }

    /**
     *  End maintenance mode
     */
    public function down()
    {
        // Check admin permission
        Gate::authorize('maintenance');

        $command = Artisan::call('down');

        if ($command === 0) {
            echo 'System is in maintenance mode';
        }
    }
}
