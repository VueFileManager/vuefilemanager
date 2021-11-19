<?php
namespace App\Console\Commands;

use App\Users\Models\User;
use Illuminate\Console\Command;
use Domain\Settings\Models\Setting;
use Domain\Pages\Actions\SeedDefaultPagesAction;
use Domain\Settings\Actions\SeedDefaultSettingsAction;
use Domain\Localization\Actions\SeedDefaultLanguageAction;
use Domain\SetupWizard\Actions\CreateDiskDirectoriesAction;

class SetupProdEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'setup:prod';

    protected string $license = 'Extended';

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
        ($this->seedDefaultSettings)($this->license);
        ($this->seedDefaultLanguage)();

        $this->info('Creating default admin...');
        $this->create_admin();

        $this->info('Clearing application cache...');
        $this->clear_cache();

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
                'value' => 1,
            ],
            [
                'name'  => 'storage_limitation',
                'value' => 1,
            ],
            [
                'name'  => 'default_storage_amount',
                'value' => 5,
            ],
            [
                'name'  => 'setup_wizard_success',
                'value' => 1,
            ],
            [
                'name'  => 'license',
                'value' => $this->license,
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
        ])->each(function ($col) {
            Setting::forceCreate([
                'name'  => $col['name'],
                'value' => $col['value'],
            ]);
        });
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
                'name'               => 'Admin',
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
