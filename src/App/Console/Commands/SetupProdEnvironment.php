<?php
namespace App\Console\Commands;

use App\Users\Models\User;
use Illuminate\Console\Command;
use Domain\Settings\Models\Setting;
use Illuminate\Foundation\Testing\WithFaker;
use Domain\Pages\Actions\SeedDefaultPagesAction;
use Domain\Settings\Actions\SeedDefaultSettingsAction;
use Domain\Localization\Actions\SeedDefaultLanguageAction;
use Domain\SetupWizard\Actions\CreateDiskDirectoriesAction;

class SetupProdEnvironment extends Command
{
    use WithFaker;

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'setup:prod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up production environment';

    public function __construct(
        private CreateDiskDirectoriesAction $createDiskDirectories,
        private SeedDefaultSettingsAction $seedDefaultSettings,
        private SeedDefaultLanguageAction $seedDefaultLanguage,
        private SeedDefaultPagesAction $seedDefaultPages,
    ) {
        parent::__construct();
        $this->setUpFaker();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Setting up production environment');

        $this->info('Creating system directories...');
        ($this->createDiskDirectories)();

        $this->info('Migrating Databases...');
        $this->migrate_and_generate();

        $this->info('Storing default settings and content...');
        $this->store_default_settings();

        ($this->seedDefaultPages)();
        ($this->seedDefaultSettings)();
        ($this->seedDefaultLanguage)();

        $this->info('Creating default admin...');
        $this->create_admin();

        $this->info('Clearing application cache...');
        $this->clear_cache();

        $this->info('Dispatching jobs...');
        $this->call('queue:work', [
            '--stop-when-empty' => true,
        ]);

        $this->warn('Please make sure your current host/domain where you are running app is included in your .env SANCTUM_STATEFUL_DOMAINS variable.');

        $this->info('Everything is done, congratulations! 🥳🥳🥳');
    }

    /**
     * Store main app settings into database
     */
    private function store_default_settings(): void
    {
        // Get options
        collect([
            [
                'name'  => 'app_color',
                'value' => '#00BC7E',
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
                'name'  => 'app_logo_dark',
                'value' => null,
            ],
            [
                'name'  => 'app_logo_horizontal',
                'value' => null,
            ],
            [
                'name'  => 'app_logo_horizontal_dark',
                'value' => null,
            ],
            [
                'name'  => 'app_favicon',
                'value' => null,
            ],
            [
                'name'  => 'app_og_image',
                'value' => null,
            ],
            [
                'name'  => 'app_touch_icon',
                'value' => null,
            ],
            [
                'name'  => 'google_analytics',
                'value' => null,
            ],
            [
                'name'  => 'contact_email',
                'value' => null,
            ],
            [
                'name'  => 'registration',
                'value' => 0,
            ],
            [
                'name'  => 'user_verification',
                'value' => 0,
            ],
            [
                'name'  => 'storage_limitation',
                'value' => 1,
            ],
            [
                'name'  => 'default_max_storage_amount',
                'value' => 5,
            ],
            [
                'name'  => 'default_max_team_member',
                'value' => 10,
            ],
            [
                'name'  => 'setup_wizard_success',
                'value' => 1,
            ],
            [
                'name'  => 'license',
                'value' => 'regular',
            ],
            [
                'name'  => 'purchase_code',
                'value' => '26b889eb-3602-4bf2-beb3-3sc378fcf484',
            ],
            [
                'name'  => 'billing_address',
                'value' => null,
            ],
            [
                'name'  => 'billing_city',
                'value' => null,
            ],
            [
                'name'  => 'billing_country',
                'value' => null,
            ],
            [
                'name'  => 'billing_name',
                'value' => null,
            ],
            [
                'name'  => 'billing_phone_number',
                'value' => null,
            ],
            [
                'name'  => 'billing_postal_code',
                'value' => null,
            ],
            [
                'name'  => 'billing_state',
                'value' => null,
            ],
            [
                'name'  => 'billing_vat_number',
                'value' => null,
            ],
            [
                'name'  => 'allowed_recaptcha',
                'value' => 0,
            ],
        ])->each(function ($col) {
            Setting::forceCreate([
                'name'  => $col['name'],
                'value' => $col['value'],
            ]);
        });

        $choice = $this->choice('Choose subscription type', [
            'metered' => 'Metered',
            'fixed'   => 'Fixed',
            'none'    => 'None',
        ]);

        Setting::updateOrCreate([
            'name'  => 'subscription_type',
        ], [
            'value' => $choice,
        ]);
    }

    /**
     * Create default admin account
     */
    private function create_admin(): void
    {
        $user = User::forceCreate([
            'role'              => 'admin',
            'email'             => 'howdy@hi5ve.digital',
            'password'          => bcrypt('vuefilemanager'),
            'email_verified_at' => now(),
        ]);

        $user
            ->settings()
            ->create([
                'first_name'   => 'Jane',
                'last_name'    => 'Doe',
                'address'      => $this->faker->address,
                'state'        => $this->faker->state,
                'city'         => $this->faker->city,
                'postal_code'  => $this->faker->postcode,
                'country'      => $this->faker->randomElement(['SK', 'CZ', 'DE', 'FR']),
                'phone_number' => $this->faker->phoneNumber,
                'timezone'     => $this->faker->randomElement(['+1.0', '+2.0', '+3.0']),
            ]);

        // Show user credentials
        $this->info('Default admin account created. Email: howdy@hi5ve.digital and Password: vuefilemanager');
    }

    /**
     * Migrate database and generate application keys
     */
    private function migrate_and_generate(): void
    {
        // Migrate database
        $this->call('migrate:fresh', [
            '--force' => true,
        ]);

        // Generate app key
        $this->call('key:generate', [
            '--force' => true,
        ]);

        $currentHost = request()->getHost() . ',' . request()->getHost() . ':' . request()->getPort();

        setEnvironmentValue([
            'SANCTUM_STATEFUL_DOMAINS' => "localhost,localhost:8000,127.0.0.1,127.0.0.1:8000,::1,$currentHost",
        ]);
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
