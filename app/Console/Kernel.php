<?php
namespace App\Console;

use App\Services\SchedulerService;
use App\Services\Oasis\OasisService;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SetupDevEnvironment;
use App\Console\Commands\SetupProdEnvironment;
use App\Console\Commands\SetupOasisEnvironment;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        SetupOasisEnvironment::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $scheduler = resolve(SchedulerService::class);

        $schedule->call(function () use ($scheduler) {
            $scheduler->delete_expired_shared_links();
        })->everyTenMinutes();

        $schedule->call(function () use ($scheduler) {
            $scheduler->delete_old_zips();

            if (! is_storage_driver(['local'])) {
                $scheduler->delete_failed_files();
            }
        })->everySixHours();

        // Oasis Drive
        $schedule->call(function () {
            resolve(OasisService::class)->order_reminder();
        })->hourly();

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
