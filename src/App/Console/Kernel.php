<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SetupDevEnvironment;
use App\Console\Commands\SetupProdEnvironment;
use Support\Scheduler\Actions\DeleteOldZipsAction;
use Support\Scheduler\Actions\DeleteFailedFilesAction;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Support\Scheduler\Actions\DeleteUnverifiedUsersAction;
use Support\Scheduler\Actions\DeleteExpiredShareLinksAction;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SetupDevEnvironment::class,
        SetupProdEnvironment::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(
            fn () => resolve(DeleteExpiredShareLinksAction::class)()
        )->everyTenMinutes();

        $schedule->call(
            fn () => resolve(DeleteUnverifiedUsersAction::class)()
        )->daily();

        $schedule->call(function () {
            resolve(DeleteOldZipsAction::class)();

            if (! is_storage_driver(['local'])) {
                resolve(DeleteFailedFilesAction::class)();
            }
        })->everySixHours();

        // Run queue jobs every minute
        $schedule->command('queue:work --stop-when-empty')
            ->everyMinute()
            ->withoutOverlapping();

        // Backup app database daily
        $schedule->command('backup:clean')
            ->daily()
            ->at('01:00');
        $schedule->command('backup:run --only-db')
            ->daily()
            ->at('01:30');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
