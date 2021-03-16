<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Folder;
use App\Models\Page;
use App\Models\Share;
use App\Services\HelperService;
use App\Services\SetupService;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
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
    protected $description = 'Set up development environment with demo data';

    private $setup;
    private $helper;

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
        $this->helper = resolve(HelperService::class);
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

        $this->info('Creating demo users...');
        $this->create_demo_users();

        $this->info('Creating default admin content...');
        $this->create_admin_default_content();

        $this->info('Clearing application cache...');
        $this->clear_cache();

        $this->info('Dispatching jobs...');
        $this->call('queue:work', [
            '--stop-when-empty' => true,
        ]);

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
                'avatar'           => 'avatars/avatar-01.png',
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

        \File::copy(storage_path("demo/avatars/avatar-01.png"), storage_path("app/avatars/avatar-01.png"));

        // Show user credentials
        $this->info('Default admin account created. Email: howdy@hi5ve.digital and Password: vuefilemanager');
    }

    /**
     * Create default admin account
     */
    private function create_demo_users(): void
    {
        collect([
            [
                'avatar' => 'avatar-02.png',
            ],
            [
                'avatar' => 'avatar-03.png',
            ],
        ])->each(function ($user) {

            $newbie = User::forceCreate([
                'role'     => 'user',
                'email'    => $this->faker->email,
                'password' => Hash::make('vuefilemanager'),
            ]);

            $newbie
                ->settings()
                ->create([
                    'avatar'           => "avatars/{$user['avatar']}",
                    'storage_capacity' => 5,
                    'name'             => $this->faker->name,
                    'address'          => $this->faker->address,
                    'state'            => $this->faker->state,
                    'city'             => $this->faker->city,
                    'postal_code'      => $this->faker->postcode,
                    'country'          => $this->faker->randomElement(['SK', 'CZ', 'DE', 'FR']),
                    'phone_number'     => $this->faker->phoneNumber,
                    'timezone'         => $this->faker->randomElement(['+1.0', '+2.0', '+3.0']),
                ]);

            \File::copy(storage_path("demo/avatars/{$user['avatar']}"), storage_path("app/avatars/{$user['avatar']}"));

            $this->info("Generated user with email: $newbie->email and Password: vuefilemanager");
        });
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
                'emoji'      => [
                    "codes"    => "1F680",
                    "char"     => "🚀",
                    "name"     => "rocket",
                    "category" => "Travel & Places (transport-air)",
                    "group"    => "Travel & Places",
                    "subgroup" => "transport-air"
                ],
                'created_at' => Carbon::now(),
            ]);

        Share::factory(Share::class)
            ->create([
                'type'         => 'folder',
                'item_id'      => $shared_folder->id,
                'user_id'      => $user->id,
                'permission'   => 'editor',
                'is_protected' => false,
                'password'     => null,
                'expire_in'    => null,
            ]);

        $peters_files = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $shared_folder->id,
                'user_scope' => 'editor',
                'name'       => "Peter's Files",
            ]);

        // 2.
        $random_pics = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
                'name'       => 'Random Pics',
                'emoji'      => [
                    'codes'    => '1F4F7',
                    'char'     => '📷',
                    'name'     => 'camera',
                    'category' => 'Objects (light & video)',
                    'group'    => 'Objects',
                    'subgroup' => 'light & video',
                ],
                'created_at' => Carbon::now()->subMinutes(1),
            ]);

        $nature = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $random_pics->id,
                'user_scope' => 'master',
                'name'       => "Nature",
                'emoji'      => [
                    'codes'    => '26F0',
                    'char'     => '⛰',
                    'name'     => 'mountain',
                    'category' => 'Travel & Places (place-geographic)',
                    'group'    => 'Travel & Places',
                    'subgroup' => 'place-geographic',
                ],
            ]);

        $apartments = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'parent_id'  => $random_pics->id,
                'user_scope' => 'master',
                'name'       => "Apartments",
                'emoji'      => [
                    'codes'    => '1F3E0',
                    'char'     => '🏠',
                    'name'     => 'house',
                    'category' => 'Travel & Places (place-building)',
                    'group'    => 'Travel & Places',
                    'subgroup' => 'place-building',
                ],
            ]);

        // 3.
        $playable_media = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
                'name'       => 'Playable Media',
                'created_at' => Carbon::now()->subMinutes(2),
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
                'created_at' => Carbon::now()->subMinutes(3),
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
                'created_at' => Carbon::now()->subMinutes(4),
            ]);

        Share::factory(Share::class)
            ->create([
                'type'         => 'folder',
                'item_id'      => $documents->id,
                'user_id'      => $user->id,
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
                'created_at' => Carbon::now()->subMinutes(5),
            ]);

        $user
            ->favouriteFolders()
            ->sync([
                $shared_folder->id,
                $random_pics->id,
                $documents->id,
                $peters_files->id,
            ]);

        // Get documents to root directory
        collect([
            [
                'name'     => 'Random Document',
                'basename' => 'Licence.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'School Report',
                'basename' => 'Project Notes.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Personal Savings',
                'basename' => 'School Report.pages',
                'mimetype' => 'pages',
            ],
            [
                'name'     => 'Top Secret Files',
                'basename' => 'Stories of the Night Skies.pages',
                'mimetype' => 'pages',
            ],
        ])
            ->each(function ($file) use ($user) {

                // Copy file into app storage
                \File::copy(storage_path("demo/documents/{$file['basename']}"), storage_path("app/files/$user->id/{$file['basename']}"));

                // Create file record
                File::create([
                    'folder_id'  => null,
                    'user_id'    => $user->id,
                    'name'       => $file['name'],
                    'basename'   => $file['basename'],
                    'type'       => 'file',
                    'user_scope' => 'master',
                    'mimetype'   => $file['mimetype'],
                    'filesize'   => rand(1000000, 4000000),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get documents to documents folder
        collect([
            [
                'name'     => 'Home Improvement',
                'basename' => 'Licence.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Project Notes',
                'basename' => 'Project Notes.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Personal Savings',
                'basename' => 'School Report.pages',
                'mimetype' => 'pages',
            ],
            [
                'name'     => 'License',
                'basename' => 'Stories of the Night Skies.pages',
                'mimetype' => 'pages',
            ],
        ])
            ->each(function ($file) use ($user, $documents) {

                // Copy file into app storage
                \File::copy(storage_path("demo/documents/{$file['basename']}"), storage_path("app/files/$user->id/{$file['basename']}"));

                // Create file record
                File::create([
                    'folder_id'  => $documents->id,
                    'user_id'    => $user->id,
                    'name'       => $file['name'],
                    'basename'   => $file['basename'],
                    'type'       => 'file',
                    'user_scope' => 'master',
                    'mimetype'   => $file['mimetype'],
                    'filesize'   => rand(1000000, 4000000),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get documents to shared folder
        collect([
            [
                'name'     => 'Home plan',
                'basename' => 'Licence.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Software Licence',
                'basename' => 'Project Notes.pdf',
                'mimetype' => 'pdf',
            ]
        ])
            ->each(function ($file) use ($user, $shared_folder) {

                // Copy file into app storage
                \File::copy(storage_path("demo/documents/{$file['basename']}"), storage_path("app/files/$user->id/{$file['basename']}"));

                // Create file record
                File::create([
                    'folder_id'  => $shared_folder->id,
                    'user_id'    => $user->id,
                    'name'       => $file['name'],
                    'basename'   => $file['basename'],
                    'type'       => 'file',
                    'user_scope' => 'master',
                    'mimetype'   => $file['mimetype'],
                    'filesize'   => rand(1000000, 4000000),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get documents to peter's files folder
        collect([
            [
                'name'     => 'Project Backup',
                'basename' => 'Licence.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Yearly report',
                'basename' => 'Project Notes.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Work Update',
                'basename' => 'School Report.pages',
                'mimetype' => 'pages',
            ],
            [
                'name'     => 'Person Writing on Notebook',
                'basename' => 'Stories of the Night Skies.pages',
                'mimetype' => 'pages',
            ],
            [
                'name'     => 'Blank Business Composition Computer',
                'basename' => 'Licence.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => '2020 April - Export',
                'basename' => 'Project Notes.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Ballpen Blur Close Up Computer',
                'basename' => 'School Report.pages',
                'mimetype' => 'pages',
            ],
        ])
            ->each(function ($file) use ($user, $peters_files) {

                // Copy file into app storage
                \File::copy(storage_path("demo/documents/{$file['basename']}"), storage_path("app/files/$user->id/{$file['basename']}"));

                // Create file record
                File::create([
                    'folder_id'  => $peters_files->id,
                    'user_id'    => $user->id,
                    'name'       => $file['name'],
                    'basename'   => $file['basename'],
                    'type'       => 'file',
                    'user_scope' => 'editor',
                    'mimetype'   => $file['mimetype'],
                    'filesize'   => rand(1000000, 4000000),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get videos
        collect([
            'Apple Watch App Video Promotion.mp4',
            'Professional 3D Device Pack for Element 3D.mp4',
            'Smart Watch 3D Device Pack for Element 3D.mp4',
            'Sphere Bound 3D Titles.mp4',
        ])
            ->each(function ($file) use ($user, $videohive) {

                // Copy file into app storage
                \File::copy(storage_path("demo/video/$file"), storage_path("app/files/$user->id/$file"));

                // Create file record
                File::create([
                    'folder_id'  => $videohive->id,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $file,
                    'type'       => 'video',
                    'user_scope' => 'master',
                    'mimetype'   => 'mp4',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get video into video folder
        collect([
            'Apple Watch App Video Promotion.mp4',
        ])
            ->each(function ($file) use ($user, $video) {

                // Copy file into app storage
                \File::copy(storage_path("demo/video/$file"), storage_path("app/files/$user->id/$file"));

                // Create file record
                File::create([
                    'folder_id'  => $video->id,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $file,
                    'type'       => 'video',
                    'user_scope' => 'master',
                    'mimetype'   => 'mp4',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get audios
        collect([
            'D-Block & S-te-Fan - Bla Bla.mp3',
        ])
            ->each(function ($file) use ($user, $audio) {

                // Copy file into app storage
                \File::copy(storage_path("demo/audio/$file"), storage_path("app/files/$user->id/$file"));

                // Create file record
                File::create([
                    'folder_id'  => $audio->id,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $file,
                    'type'       => 'audio',
                    'user_scope' => 'master',
                    'mimetype'   => 'mp3',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get meme gallery
        collect([
            'Eggcited bro.jpg',
            'Get a Rest.jpg',
            'Get Your Shit Together.jpg',
            'Happiness is when you are right beside me.jpg',
            'Have a Nice Day.jpg',
            'It Works On My Machine.jpg',
            'I am Just Trying to shine.jpg',
            'It Works On My Machine.jpg',
            'Missing you It is Pig Time.jpg',
            'Sofishticated.jpg',
            'whaaaaat.jpg',
            'You Are My Sunshine.jpg',
        ])
            ->each(function ($file) use ($user, $apartments) {

                // Copy file into app storage
                \File::copy(storage_path("demo/images/memes/$file"), storage_path("app/files/$user->id/$file"));

                $this->info("Creating thumbnail for image: $file");

                // Create file record
                File::create([
                    'folder_id'  => null,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $file,
                    'type'       => 'image',
                    'user_scope' => 'master',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'thumbnail'  => $this->helper->create_image_thumbnail("files/$user->id/$file", $file, $user->id),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get apartments gallery
        collect([
            'Apartment Architecture Ceiling Chairs.jpg',
            'Apartment Chair.jpg',
            'Apartment Contemporary Couch Curtains.jpg',
            'Brown Wooden Center Table.jpg',
            'Home.jpg',
            'Kitchen Appliances.jpg',
            'Kitchen Island.jpg',
        ])
            ->each(function ($file) use ($user, $apartments) {

                // Copy file into app storage
                \File::copy(storage_path("demo/images/apartments/$file"), storage_path("app/files/$user->id/$file"));

                $this->info("Creating thumbnail for image: $file");

                // Create file record
                File::create([
                    'folder_id'  => $apartments->id,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $file,
                    'type'       => 'image',
                    'user_scope' => 'master',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'thumbnail'  => $this->helper->create_image_thumbnail("files/$user->id/$file", $file, $user->id),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get nature gallery
        collect([
            'Bird Patterncolorful Green.jpg',
            'Close Up Of Peacock.jpg',
            'Close Up Photography Of Tiger.jpg',
            'Cold Nature Cute Ice.jpg',
            'Landscape Photo of Forest.jpg',
            'Photo of Hawksbill Sea Turtle.jpg',
            'Photo Of Reindeer in The Snow.jpg',
            'View Of Elephant in Water.jpg',
            'Waterfall Between Trees.jpg',
            'Wildlife Photography of Elephant During Golden Hour.jpg',
            'Yellow Animal Eyes Fur.jpg',
        ])
            ->each(function ($file) use ($user, $nature) {

                // Copy file into app storage
                \File::copy(storage_path("demo/images/nature/$file"), storage_path("app/files/$user->id/$file"));

                $this->info("Creating thumbnail for image: $file");

                // Create file record
                File::create([
                    'folder_id'  => $nature->id,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $file,
                    'type'       => 'image',
                    'user_scope' => 'master',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'thumbnail'  => $this->helper->create_image_thumbnail("files/$user->id/$file", $file, $user->id),
                    'created_at'   => Carbon::now()->subMinutes(rand(1, 5)),
                ]);
            });
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
                'value' => 'system/logo.svg',
            ],
            [
                'name'  => 'app_logo_horizontal',
                'value' => 'system/logo-horizontal.svg',
            ],
            [
                'name'  => 'app_favicon',
                'value' => 'system/favicon.png',
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

        // Get system images
        collect(['logo.svg', 'logo-horizontal.svg', 'favicon.png'])
            ->each(function ($file) {
                \File::copy(storage_path("demo/app/$file"), storage_path("app/system/$file"));
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