<?php

namespace App\Console;

use App\Console\Commands\SetupDevEnvironment;
use App\Services\Oasis\OasisService;
use App\Services\SchedulerService;
use Illuminate\Console\Scheduling\Schedule;
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

            if (!is_storage_driver(['local'])) {
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
