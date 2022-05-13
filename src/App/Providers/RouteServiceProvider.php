<?php
namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
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
    protected $namespace = null;

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::prefix('api/sharing')
                ->middleware('api')
                ->group(base_path('routes/share.php'));

            Route::prefix('api/file-request')
                ->middleware('api')
                ->group(base_path('routes/file-request.php'));

            Route::prefix('api/admin')
                ->middleware(['api', 'auth:sanctum', 'admin'])
                ->group(base_path('routes/admin.php'));

            Route::middleware(['setup-wizard'])
                ->group(base_path('routes/setup.php'));

            Route::prefix('api/user')
                ->middleware('api')
                ->group(base_path('routes/user.php'));

            Route::middleware('web')
                ->group(base_path('routes/maintenance.php'));

            Route::middleware('web')
                ->group(base_path('routes/file.php'));

            Route::prefix('api/teams')
                ->middleware(['api'])
                ->group(base_path('routes/teams.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(1000)->by($request->user()->id)
                : Limit::perMinute(100)->by($request->ip());
        });

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
    }
}
