<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SetupDevEnvironment;
use App\Console\Commands\SetupProdEnvironment;
use Support\Upgrading\Actions\UpdateSystemAction;
use Support\Demo\Actions\ClearHowdyDemoDataAction;
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
        // Basic demo content generator
        SetupDevEnvironment::class,
        SetupProdEnvironment::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
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
            fn () => resolve(UpdateSystemAction::class)()
        )->everyMinute();

        $schedule->call(
            fn () => resolve(DeleteUnverifiedUsersAction::class)()
        )->daily()->at('00:05');

        // Run queue jobs every minute
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
