<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Services\SetupService;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Faker;

class SetupDevEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up development environment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->faker = Faker\Factory::create();
        $this->setup = app()->make(SetupService::class);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Setting up development environment');

        $this->info('Creating system directories...');
        $this->setup->create_directories();

        $this->migrate_and_generate();
        $this->store_data();
        $this->seed_default_content();
        $this->create_admin();
        $this->clear_cache();

        $this->info('Everything is done, congratulations! 🥳🥳🥳');
    }

    /**
     * Create default admin account
     */
    private function create_admin(): void
    {
        $user = User::forceCreate([
            'role'     => 'admin',
            'email'    => 'john@doe.com',
            'password' => Hash::make('secret'),
        ]);

        $user
            ->settings()
            ->create([
                'storage_capacity' => 5,
                'name'             => 'John Doe',
                'address'          => $this->faker->address,
                'state'            => $this->faker->state,
                'city'             => $this->faker->city,
                'postal_code'      => $this->faker->postcode,
                'country'          => $this->faker->randomElement(['SK', 'CZ', 'DE', 'FR']),
                'phone_number'     => $this->faker->phoneNumber,
                'timezone'         => $this->faker->randomElement(['+1.0', '+2.0', '+3.0']),
            ]);

        // Show user credentials
        $this->info('Default admin account created. Email: john@doe.com and Password: secret');
    }

    /**
     * Seed default content to database
     */
    private function seed_default_content(): void
    {
        collect(config('content.content'))
            ->each(function ($content) {
                Setting::updateOrCreate($content);
            });
    }

    /**
     * Store main app settings into database
     */
    private function store_data(): void
    {
        // Get options
        $settings = collect([
            [
                'name'  => 'setup_wizard_database',
                'value' => 1,
            ],
            [
                'name'  => 'app_title',
                'value' => 'VueFileManager',
            ],
            [
                'name'  => 'app_description',
                'value' => 'Your self-hosted storage cloud software powered by Laravel and Vue',
            ],
            [
                'name'  => 'app_logo',
                'value' => null,
            ],
            [
                'name'  => 'app_logo_horizontal',
                'value' => null,
            ],
            [
                'name'  => 'app_favicon',
                'value' => null,
            ],
            [
                'name'  => 'google_analytics',
                'value' => '',
            ],
            [
                'name'  => 'contact_email',
                'value' => '',
            ],
            [
                'name'  => 'registration',
                'value' => 1,
            ],
            [
                'name'  => 'storage_limitation',
                'value' => 1,
            ],
            [
                'name'  => 'storage_default',
                'value' => 5,
            ],
            [
                'name'  => 'setup_wizard_success',
                'value' => 1,
            ],
            [
                'name'  => 'license',
                'value' => 'Extended',
            ],
            [
                'name'  => 'purchase_code',
                'value' => '26b889eb-3602-4bf2-beb3-3sc378fcf484',
            ]
        ]);

        // Store options
        $settings->each(function ($col) {
            Setting::updateOrCreate(['name' => $col['name']], $col);
        });
    }

    /**
     * Migrate database and generate application keys
     */
    private function migrate_and_generate(): void
    {
        // Generate app key
        $this->call('key:generate', [
            '--force' => true
        ]);

        // Migrate database
        $this->call('migrate:fresh', [
            '--force' => true
        ]);

        $this->setup->seed_default_pages();
    }

    /**
     * Clear app cache
     */
    private function clear_cache(): void
    {
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');
    }
}