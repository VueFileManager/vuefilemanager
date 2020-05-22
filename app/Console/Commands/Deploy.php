<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic deployment for production';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Start deployment
        $this->info('Running auto deployment');
        $this->call('down');

        // Exec commands
        exec('git pull origin ' . config('app.deploy_branch'));
        //exec('composer update --no-interaction --prefer-dist');
        $this->migrateDatabase();

        // Stop deployment
        $this->call('up');
        $this->info('Everything is done, congratulations! 🥳🥳🥳');

        Log::info('Application was updated!');
    }

    /**
     * Migrate database
     */
    public function migrateDatabase()
    {
        $this->call('migrate', [
            '--force' => true,
        ]);
    }
}
