<?php

namespace App\Console\Commands;

use App\Models\Folder;
use App\Models\Page;
use App\Models\Share;
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

    private $setup;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->faker = Faker\Factory::create();
        $this->setup = resolve(SetupService::class);
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

        $this->info('Migrating Databases...');
        $this->migrate_and_generate();

        $this->info('Storing default settings and content...');
        $this->store_default_settings();
        $this->setup->seed_default_pages();
        $this->setup->seed_default_settings('Extended');

        $this->info('Creating default admin...');
        $this->create_admin();

        $this->info('Creating default admin content...');
        $this->create_admin_default_content();

        $this->info('Clearing application cache...');
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
            'email'    => 'howdy@hi5ve.digital',
            'password' => Hash::make('vuefilemanager'),
        ]);

        $user
            ->settings()
            ->create([
                'storage_capacity' => 5,
                'name'             => 'Jane Doe',
                'address'          => $this->faker->address,
                'state'            => $this->faker->state,
                'city'             => $this->faker->city,
                'postal_code'      => $this->faker->postcode,
                'country'          => $this->faker->randomElement(['SK', 'CZ', 'DE', 'FR']),
                'phone_number'     => $this->faker->phoneNumber,
                'timezone'         => $this->faker->randomElement(['+1.0', '+2.0', '+3.0']),
            ]);

        // Show user credentials
        $this->info('Default admin account created. Email: howdy@hi5ve.digital and Password: vuefilemanager');
    }

    /**
     * Create default admin content
     */
    private function create_admin_default_content(): void
    {
        $user = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        // 1.
        $shared_folder = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
                'name'       => 'Shared Folder',
            ]);

        Share::factory(Share::class)
            ->create([
                'type'         => 'folder',
                'item_id'      => $shared_folder->id,
                'permission'   => 'editor',
                'is_protected' => false,
                'password'     => null,
                'expire_in'    => null,
            ]);

        $peters_files = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $shared_folder->id,
                'user_scope' => 'master',
                'name'       => "Peter's Files",
            ]);

        // 2.
        $random_pics = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
                'name'       => 'Random Pics',
            ]);

        $nature = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $random_pics->id,
                'user_scope' => 'master',
                'name'       => "Nature",
            ]);

        $apartments = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $random_pics->id,
                'user_scope' => 'master',
                'name'       => "Apartments",
            ]);

        // 3.
        $playable_media = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
                'name'       => 'Playable Media',
            ]);

        $video = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $playable_media->id,
                'user_scope' => 'master',
                'name'       => "Video",
            ]);

        $audio = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $playable_media->id,
                'user_scope' => 'master',
                'name'       => "Audio",
            ]);

        // 4.
        $multi_level = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
                'name'       => 'Multi Level Folder',
            ]);

        $first_level = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $multi_level->id,
                'user_scope' => 'master',
                'name'       => "First Level",
            ]);

        $second_level = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $first_level->id,
                'user_scope' => 'master',
                'name'       => "Second Level",
            ]);

        $third_level = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $second_level->id,
                'user_scope' => 'master',
                'name'       => "Third Level",
            ]);

        // 5.
        $documents = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
                'name'       => 'Documents',
            ]);

        Share::factory(Share::class)
            ->create([
                'type'         => 'folder',
                'item_id'      => $documents->id,
                'permission'   => 'editor',
                'is_protected' => false,
                'password'     => null,
                'expire_in'    => null,
            ]);

        // 6.
        $videohive = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
                'name'       => 'Videohive by MakingCG',
            ]);

        $user
            ->favouriteFolders()
            ->sync([
                $shared_folder->id,
                $random_pics->id,
                $documents->id,
                $peters_files->id,
            ]);

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
        ])->each(function ($col) {
            Setting::forceCreate([
                'name'  => $col['name'],
                'value' => $col['value']
            ]);
        });
    }

    /**
     * Migrate database and generate application keys
     */
    private function migrate_and_generate(): void
    {
        // Migrate database
        $this->call('migrate:fresh', [
            '--force' => true
        ]);

        // Generate app key
        $this->call('key:generate', [
            '--force' => true
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