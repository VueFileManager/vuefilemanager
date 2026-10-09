<?php

use App\BaseApplication;
use Illuminate\Http\Request;
use Support\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;
use Illuminate\Console\Scheduling\Schedule;
use Support\Scheduler\Actions\ReportUsageAction;
use Support\Upgrading\Actions\UpdateSystemAction;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Support\Demo\Actions\ClearHowdyDemoDataAction;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Support\Middleware\ProtectSetupWizardRoutes;
use Support\Scheduler\Actions\DeleteFailedFilesAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Support\Scheduler\Actions\DeleteUnverifiedUsersAction;
use Support\Scheduler\Actions\DeleteExpiredShareLinksAction;
use Domain\UploadRequest\Middleware\ProtectUploadRequestRoutes;
use Support\Scheduler\Actions\ExpireUnfilledUploadRequestAction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return BaseApplication::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        using: function () {
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
        },
    )
    ->withBroadcasting(__DIR__ . '/../routes/channels.php')
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO |
                Request::HEADER_X_FORWARDED_AWS_ELB
        );

        $middleware->validateCsrfTokens(except: [
            '/deploy',
            '/stripe/*',
        ]);

        $middleware->redirectGuestsTo(fn () => url('/'));
        $middleware->redirectUsersTo('/platform/files');

        $middleware->group('api', [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
        ]);

        $middleware->alias([
            'admin'          => AdminCheck::class,
            'setup-wizard'   => ProtectSetupWizardRoutes::class,
            'upload-request' => ProtectUploadRequestRoutes::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontFlash([
            'password',
            'password_confirmation',
        ]);

        // Render missing eloquent models as custom json response or redirect to not-found page
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            $previous = $exception->getPrevious();

            if (! $previous instanceof ModelNotFoundException) {
                return null;
            }

            if (in_array('application/json', $request->getAcceptableContentTypes())) {
                // Get ids
                $ids = implode(', ', $previous->getIds());

                return response()->json([
                    'type'    => 'error',
                    'message' => "We couldn't find the resource you requested with id $ids",
                ], 404);
            }

            return response()
                ->redirectTo('/not-found')
                ->setStatusCode(404);
        });
    })
    ->withSchedule(function (Schedule $schedule): void {
        if (! isStorageDriver('local')) {
            $schedule->call(
                fn () => resolve(DeleteFailedFilesAction::class)()
            )->everySixHours();
        }

        if (is_demo()) {
            $schedule->call(
                fn () => resolve(ClearHowdyDemoDataAction::class)()
            )->daily()->at('00:00');
        }

        $schedule->call(
            fn () => resolve(DeleteExpiredShareLinksAction::class)()
        )->everyTenMinutes();

        $schedule->call(
            fn () => resolve(ExpireUnfilledUploadRequestAction::class)()
        )->hourly();

        $schedule->call(
            fn () => resolve(UpdateSystemAction::class)()
        )->everyMinute();

        $schedule->call(
            fn () => resolve(DeleteUnverifiedUsersAction::class)()
        )->daily()->at('00:05');

        $schedule->call(
            fn () => resolve(ReportUsageAction::class)()
        )->daily()->at('00:10');

        // Run queue jobs every five minutes
        $schedule->command('queue:work --queue=high,default --max-time=300')
            ->everyFiveMinutes()
            ->withoutOverlapping();

        // Backup app database daily
        $schedule->command('backup:clean')
            ->daily()
            ->at('00:15');

        $schedule->command('backup:run --only-db')
            ->daily()
            ->at('00:20');

        $schedule->command('config:clear')
            ->daily();

        $schedule->call(fn () => cache()->set('latest_cron_update', now()->toString()))
            ->everyMinute();
    })
    ->create();
