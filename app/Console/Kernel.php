<?php

namespace App\Console;

use App\Console\Commands\Deploy;
//use App\Console\Commands\SetupDevelopmentEnvironment;
use App\Console\Commands\SetupDevEnvironment;
use App\Console\Commands\SetupProductionEnvironment;
use App\Console\Commands\UpgradeApp;
use App\Share;
use Carbon\Carbon;
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
        Deploy::class,
        //SetupDevelopmentEnvironment::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $this->delete_expired_shared_links();
        })->hourly();
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

    /**
     * Get and delete expired shared links
     */
    protected function delete_expired_shared_links(): void
    {
        // Get all shares with expiration time
        $shares = Share::whereNotNull('expire_in')->get();

        $shares->each(function ($share) {

            // Get dates
            $created_at = Carbon::parse($share->created_at);

            // If time was over, then delete share record
            if ($created_at->diffInHours(Carbon::now()) >= $share->expire_in) {
                $share->delete();
            }
        });
    }
}
