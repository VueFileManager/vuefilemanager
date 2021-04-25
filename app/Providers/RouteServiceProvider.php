<?php
namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapShareRoutes();

        $this->mapAdminApiRoutes();

        $this->mapSetupWizardApiRoutes();

        $this->mapUserApiRoutes();

        $this->mapMaintenanceRoutes();

        $this->mapFileRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapMaintenanceRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/maintenance.php'));
    }

    protected function mapFileRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/file.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapShareRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/share.php'));
    }

    protected function mapAdminApiRoutes()
    {
        Route::prefix('api/admin')
            ->middleware(['api', 'auth:sanctum'])
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    protected function mapUserApiRoutes()
    {
        Route::prefix('api/user')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/user.php'));
    }

    protected function mapSetupWizardApiRoutes()
    {
        Route::prefix('api/setup')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/setup.php'));
    }
}
