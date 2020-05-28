<?php

namespace App\Console\Commands;

use App\User;
use App\UserSettings;
use Illuminate\Console\Command;

class UpgradeApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade:app {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade application to new version';

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
        $this->info('Upgrading your application to version ' . $this->argument('version'));
        $this->call('down');

        // Version 1.6
        if ($this->argument('version') === 'v1.6') {
            $this->version_1_6();
        }

        $this->call('up');
        $this->info('Your application was upgraded! 🥳🥳🥳');
    }

    /**
     * Upgrade script to version 1.6
     */
    public function version_1_6() {

        // Migrate new tables and changes
        $this->call('migrate');

        // Create user settings records
        $this->info('Updating users options...');

        User::all()->each(function ($user) {
            $this->info('Update user with id: ' . $user->id);
            UserSettings::create(['user_id' => $user->id]);
        });

        $this->info('Updating user options is done!');

        // Set up admin
        $email = $this->ask('Which user would you like set up as admin? Please type user email');

        $admin = User::where('email', $email)->first();

        if (! $admin) {
            $email = $this->ask('We can\'t find user with this email, please try it again');
            $admin = User::where('email', $email)->first();
        }

        // Save new role for selected user
        $admin->role = 'admin';
        $admin->save();

        $this->info('Admin was set up successfully');
    }
}
