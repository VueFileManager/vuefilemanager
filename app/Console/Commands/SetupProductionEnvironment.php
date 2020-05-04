<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupProductionEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:prod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting production environment';

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
        $this->info('Setting up production environment');

        $this->migrateDatabase();
        $this->generateKey();
        $this->createPassportKeys();
        $this->createPassportClientPassword();
        $this->createPassportClientPersonal();

        $this->info('Everything is done, congratulations! 🥳🥳🥳');

    }

    /**
     * Migrate database
     */
    public function generateKey()
    {
        $this->call('key:generate');
    }

    /**
     * Migrate database
     */
    public function migrateDatabase()
    {
        $this->call('migrate:fresh');
    }

    /**
     * Create Passport Encryption keys
     */
    public function createPassportKeys()
    {
        $this->call('passport:keys', [
            '--force' => true
        ]);
    }

    /**
     * Create Password grant client
     */
    public function createPassportClientPassword()
    {
        $this->call('passport:client', [
            '--password' => true,
            '--name'     => 'vuefilemanager',
        ]);

        $this->alert('Please copy these first password grant Client ID & Client secret above to your /.env file.');
    }

    /**
     * Create Personal access client
     */
    public function createPassportClientPersonal()
    {
        $this->call('passport:client', [
            '--personal' => true,
            '--name'     => 'shared',
        ]);
    }
}
