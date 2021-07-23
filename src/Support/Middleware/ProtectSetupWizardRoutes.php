<?php
namespace Support\Middleware;

use Schema;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\Driver\PDOException;

class ProtectSetupWizardRoutes
{
    /**
     * Prevent access for setup wizard controllers after initial app installation.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            // Check database connections
            DB::getPdo();

            // Get setup_wizard status
            if (Schema::hasTable('settings') && get_settings('setup_wizard_success')) {
                return response('Gone', 410);
            }

            return $next($request);
        } catch (PDOException $e) {
            return $next($request);
        }
    }
}
