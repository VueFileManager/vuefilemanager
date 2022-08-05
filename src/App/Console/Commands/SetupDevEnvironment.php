<?php
namespace App\Console\Commands;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Illuminate\Console\Command;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Domain\Settings\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Domain\Teams\Models\TeamFolderInvitation;
use Domain\Pages\Actions\SeedDefaultPagesAction;
use Intervention\Image\ImageManagerStatic as Image;
use Domain\Settings\Actions\SeedDefaultSettingsAction;
use Domain\Localization\Actions\SeedDefaultLanguageAction;
use Domain\SetupWizard\Actions\CreateDiskDirectoriesAction;

class SetupDevEnvironment extends Command
{
    use WithFaker;

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'setup:dev';

    /**
     * The console command description.
     */
    protected $description = 'Set up development environment with demo data';

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
     */
    public function handle(): void
    {
        $this->info('Setting up development environment');

        $this->info('Creating system directories...');
        ($this->createDiskDirectories)();

        $this->info('Migrating Databases...');
        $this->migrate_and_generate();

        $this->info('Storing default settings and content...');
        ($this->seedDefaultPages)();
        ($this->seedDefaultSettings)();
        ($this->seedDefaultLanguage)();
        $this->store_default_settings();

        $this->info('Creating default admin...');
        $this->create_admin();

        $this->info('Creating demo users...');
        $this->create_demo_users();

        $this->info('Creating default demo content...');
        $this->create_admin_default_content();
        $this->create_team_folders_content();
        $this->create_share_with_me_team_folders_content();
        $this->create_share_records();
        $this->generate_traffic();

        $this->generateCommonNotification();
        $this->generateTeamInvitationNotification();
        $this->generateFileRequestFilledNotification();

        $this->info('Clearing application cache...');
        $this->clear_cache();

        $this->info('Dispatching jobs...');
        $this->call('queue:work', [
            '--stop-when-empty' => true,
        ]);

        $this->warn('Please make sure your current host/domain where you are running app is included in your .env SANCTUM_STATEFUL_DOMAINS variable.');

        $this->info('Everything is done, congratulations! 🥳🥳🥳');
    }

    private function generateCommonNotification()
    {
        $howdy = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        DB::table('notifications')
            ->insert([
                'id'              => Str::uuid(),
                'type'            => 'App\Users\Notifications\RegistrationBonusAddedNotification',
                'notifiable_type' => 'App\Users\Models\User',
                'notifiable_id'   => $howdy->id,
                'data'            => json_encode([
                    'category'    => 'gift',
                    'title'       => 'You Received $10.00',
                    'description' => 'You received credit bonus $10.00 for your registration. Happy spending!',
                ]),
                'read_at'         => now()->subMinutes(5),
                'created_at'      => now()->subMinutes(5),
                'updated_at'      => now()->subMinutes(5),
            ]);
    }

    private function generateTeamInvitationNotification()
    {
        $alice = User::whereEmail('alice@hi5ve.digital')
            ->first();

        $howdy = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        $newV2Wallpaper = Folder::factory()
            ->create([
                'user_id'     => $alice->id,
                'team_folder' => true,
                'name'        => 'New v2 Wallpaper',
            ]);

        $invitation = TeamFolderInvitation::factory()
            ->create([
                'email'      => 'howdy@hi5ve.digital',
                'parent_id'  => $newV2Wallpaper->id,
                'inviter_id' => $newV2Wallpaper->user_id,
                'status'     => 'pending',
                'permission' => 'can-edit',
            ]);

        DB::table('notifications')
            ->insert([
                'id'              => Str::uuid(),
                'type'            => 'Domain\UploadRequest\Notifications\UploadRequestFulfilledNotification',
                'notifiable_type' => 'App\Users\Models\User',
                'notifiable_id'   => $howdy->id,
                'data'            => json_encode([
                    'category'    => 'team-invitation',
                    'title'       => 'New Team Invitation',
                    'description' => 'Jane Doe invite you to join into Team Folder.',
                    'action'      => [
                        'type'   => 'invitation',
                        'params' => [
                            'id' => $invitation->id,
                        ],
                    ],
                ]),
                'read_at'         => now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
    }

    private function generateFileRequestFilledNotification()
    {
        $howdy = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        $sharedFolder = Folder::where('name', 'Shared Folder')
            ->first();

        $fileRequestFolder = Folder::factory()
            ->create([
                'parent_id'   => $sharedFolder->id,
                'user_id'     => $howdy->id,
                'team_folder' => false,
                'name'        => 'Upload Request from 10. Mar. 2022',
            ]);

        DB::table('notifications')
            ->insert([
                'id'              => Str::uuid(),
                'type'            => 'Domain\UploadRequest\Notifications\UploadRequestFulfilledNotification',
                'notifiable_type' => 'App\Users\Models\User',
                'notifiable_id'   => $howdy->id,
                'data'            => json_encode([
                    'category'    => 'file-request',
                    'title'       => 'File Request Filled',
                    'description' => "Your file request for 'Shared Folder' folder was filled successfully.",
                    'action'      => [
                        'type'   => 'route',
                        'params' => [
                            'route'  => 'Files',
                            'button' => 'Show Files',
                            'id'     => $fileRequestFolder->id,
                        ],
                    ],
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

        // Get meme gallery
        collect([
            'demo/request/v2-wallpaper.jpg',
        ])
            ->each(function ($file) use ($howdy, $fileRequestFolder) {
                $thumbnail = $this->generate_thumbnails($file, $howdy);

                // Create file record
                File::create([
                    'parent_id'  => $fileRequestFolder->id,
                    'user_id'    => $howdy->id,
                    'name'       => $thumbnail['name'],
                    'basename'   => $thumbnail['basename'],
                    'type'       => 'image',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
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

        $avatar_name = $this->generate_avatar('avatar-01.png');

        $user
            ->settings()
            ->create([
                'avatar'       => $avatar_name,
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

        // Create personal tokens
        $user->createToken('Workplace App');
        $user->createToken('Web App');

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
                'email'  => 'alice@hi5ve.digital',
            ],
            [
                'avatar' => 'avatar-03.png',
                'email'  => 'johan@hi5ve.digital',
            ],
            [
                'avatar' => 'avatar-04.png',
                'email'  => $this->faker->email,
            ],
            [
                'avatar' => 'avatar-05.png',
                'email'  => $this->faker->email,
            ],
            [
                'avatar' => 'avatar-06.png',
                'email'  => $this->faker->email,
            ],
            [
                'avatar' => 'avatar-07.png',
                'email'  => $this->faker->email,
            ],
        ])->each(function ($user) {
            $newbie = User::forceCreate([
                'role'              => 'user',
                'email'             => $user['email'],
                'password'          => bcrypt('vuefilemanager'),
                'email_verified_at' => now(),
            ]);

            $avatar_name = $this->generate_avatar($user['avatar']);

            $newbie
                ->settings()
                ->create([
                    'avatar'       => $avatar_name,
                    'first_name'   => $this->faker->name,
                    'last_name'    => $this->faker->lastName,
                    'address'      => $this->faker->address,
                    'state'        => $this->faker->state,
                    'city'         => $this->faker->city,
                    'postal_code'  => $this->faker->postcode,
                    'country'      => $this->faker->randomElement(['SK', 'CZ', 'DE', 'FR']),
                    'phone_number' => $this->faker->phoneNumber,
                    'timezone'     => $this->faker->randomElement(['+1.0', '+2.0', '+3.0']),
                ]);

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
        $shared_folder = Folder::factory()
            ->create([
                'user_id'    => $user->id,
                'name'       => 'Shared Folder',
                'emoji'      => [
                    'codes'    => '1F680',
                    'char'     => '🚀',
                    'name'     => 'rocket',
                    'category' => 'Travel & Places (transport-air)',
                    'group'    => 'Travel & Places',
                    'subgroup' => 'transport-air',
                ],
                'created_at' => now(),
            ]);

        Share::factory()
            ->create([
                'type'         => 'folder',
                'item_id'      => $shared_folder->id,
                'user_id'      => $user->id,
                'permission'   => 'editor',
                'is_protected' => false,
                'password'     => null,
                'expire_in'    => null,
            ]);

        $peters_files = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $shared_folder->id,
                'author'    => 'visitor',
                'name'      => "Peter's Files",
            ]);

        // 2.
        $random_pics = Folder::factory()
            ->create([
                'user_id'    => $user->id,
                'name'       => 'Random Pics',
                'emoji'      => [
                    'codes'    => '1F4F7',
                    'char'     => '📷',
                    'name'     => 'camera',
                    'category' => 'Objects (light & video)',
                    'group'    => 'Objects',
                    'subgroup' => 'light & video',
                ],
                'created_at' => now()->subMinutes(1),
            ]);

        $nature = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $random_pics->id,
                'author'    => 'user',
                'name'      => 'Nature',
                'emoji'     => [
                    'codes'    => '26F0',
                    'char'     => '⛰',
                    'name'     => 'mountain',
                    'category' => 'Travel & Places (place-geographic)',
                    'group'    => 'Travel & Places',
                    'subgroup' => 'place-geographic',
                ],
            ]);

        $apartments = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $random_pics->id,
                'author'    => 'user',
                'name'      => 'Apartments',
                'emoji'     => [
                    'codes'    => '1F3E0',
                    'char'     => '🏠',
                    'name'     => 'house',
                    'category' => 'Travel & Places (place-building)',
                    'group'    => 'Travel & Places',
                    'subgroup' => 'place-building',
                ],
            ]);

        // 3.
        $playable_media = Folder::factory()
            ->create([
                'user_id'    => $user->id,
                'name'       => 'Playable Media',
                'created_at' => now()->subMinutes(2),
            ]);

        $video = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $playable_media->id,
                'author'    => 'user',
                'name'      => 'Video',
            ]);

        $audio = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $playable_media->id,
                'author'    => 'user',
                'name'      => 'Audio',
            ]);

        // 4.
        $multi_level = Folder::factory()
            ->create([
                'user_id'    => $user->id,
                'name'       => 'Multi Level Folder',
                'created_at' => now()->subMinutes(3),
            ]);

        $first_level = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $multi_level->id,
                'author'    => 'user',
                'name'      => 'First Level',
            ]);

        $second_level = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $first_level->id,
                'author'    => 'user',
                'name'      => 'Second Level',
            ]);

        Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $second_level->id,
                'author'    => 'user',
                'name'      => 'Third Level',
            ]);

        // 5.
        $documents = Folder::factory()
            ->create([
                'user_id'    => $user->id,
                'name'       => 'Documents',
                'created_at' => now()->subMinutes(4),
            ]);

        Share::factory()
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
        $videohive = Folder::factory()
            ->create([
                'user_id'    => $user->id,
                'name'       => 'Videohive',
                'created_at' => now()->subMinutes(5),
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
                $basename = Str::random(12) . '-' . $file['basename'];

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/documents/{$file['basename']}"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'  => null,
                    'user_id'    => $user->id,
                    'name'       => $file['name'],
                    'basename'   => $basename,
                    'type'       => 'file',
                    'mimetype'   => $file['mimetype'],
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
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
                $basename = Str::random(12) . '-' . $file['basename'];

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/documents/{$file['basename']}"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'  => $documents->id,
                    'user_id'    => $user->id,
                    'name'       => $file['name'],
                    'basename'   => $basename,
                    'type'       => 'file',
                    'mimetype'   => $file['mimetype'],
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });

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
            ],
        ])
            ->each(function ($file) use ($user, $shared_folder) {
                $basename = Str::random(12) . '-' . $file['basename'];

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/documents/{$file['basename']}"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'  => $shared_folder->id,
                    'user_id'    => $user->id,
                    'name'       => $file['name'],
                    'basename'   => $basename,
                    'type'       => 'file',
                    'mimetype'   => $file['mimetype'],
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
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
                $basename = Str::random(12) . '-' . $file['basename'];

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/documents/{$file['basename']}"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'  => $peters_files->id,
                    'user_id'    => $user->id,
                    'name'       => $file['name'],
                    'basename'   => $basename,
                    'type'       => 'file',
                    'mimetype'   => $file['mimetype'],
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
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
                $basename = Str::random(12) . '-' . $file;

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/video/$file"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'  => $videohive->id,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $basename,
                    'type'       => 'video',
                    'mimetype'   => 'mp4',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get video into video folder
        collect([
            'Apple Watch App Video Promotion.mp4',
        ])
            ->each(function ($file) use ($user, $video) {
                $basename = Str::random(12) . '-' . $file;

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/video/$file"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'  => $video->id,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $basename,
                    'type'       => 'video',
                    'mimetype'   => 'mp4',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get audios
        collect([
            'D-Block & S-te-Fan - Bla Bla.mp3',
        ])
            ->each(function ($file) use ($user, $audio) {
                $basename = Str::random(12) . '-' . $file;

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/audio/$file"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'  => $audio->id,
                    'user_id'    => $user->id,
                    'name'       => $file,
                    'basename'   => $basename,
                    'type'       => 'audio',
                    'mimetype'   => 'mp3',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get meme gallery
        collect([
            'demo/images/memes/Eggcited bro.jpg',
            'demo/images/memes/Get a Rest.jpg',
            'demo/images/memes/Get Your Shit Together.jpg',
            'demo/images/memes/Happiness is when you are right beside me.jpg',
            'demo/images/memes/Have a Nice Day.jpg',
            'demo/images/memes/It Works On My Machine.jpg',
            'demo/images/memes/I am Just Trying to shine.jpg',
            'demo/images/memes/Missing you It is Pig Time.jpg',
            'demo/images/memes/Sofishticated.jpg',
            'demo/images/memes/whaaaaat.jpg',
            'demo/images/memes/You Are My Sunshine.jpg',
        ])
            ->each(function ($file) use ($user) {
                $thumbnail = $this->generate_thumbnails($file, $user);

                // Create file record
                File::create([
                    'parent_id'  => null,
                    'user_id'    => $user->id,
                    'name'       => $thumbnail['name'],
                    'basename'   => $thumbnail['basename'],
                    'type'       => 'image',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get apartments gallery
        collect([
            'demo/images/apartments/Apartment Architecture Ceiling Chairs.jpeg',
            'demo/images/apartments/Apartment Chair.jpeg',
            'demo/images/apartments/Apartment Contemporary Couch Curtains.jpeg',
            'demo/images/apartments/Brown Wooden Center Table.jpeg',
            'demo/images/apartments/Home.jpeg',
            'demo/images/apartments/Kitchen Appliances.jpeg',
            'demo/images/apartments/Kitchen Island.jpeg',
        ])
            ->each(function ($file) use ($user, $apartments) {
                $thumbnail = $this->generate_thumbnails($file, $user);

                // Create file record
                File::create([
                    'parent_id'  => $apartments->id,
                    'user_id'    => $user->id,
                    'name'       => $thumbnail['name'],
                    'basename'   => $thumbnail['basename'],
                    'type'       => 'image',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get nature gallery
        collect([
            'demo/images/nature/Bird Patterncolorful Green.jpeg',
            'demo/images/nature/Close Up Of Peacock.jpeg',
            'demo/images/nature/Close Up Photography Of Tiger.jpeg',
            'demo/images/nature/Cold Nature Cute Ice.jpeg',
            'demo/images/nature/Landscape Photo of Forest.jpeg',
            'demo/images/nature/Photo of Hawksbill Sea Turtle.jpeg',
            'demo/images/nature/Photo Of Reindeer in The Snow.jpeg',
            'demo/images/nature/View Of Elephant in Water.jpeg',
            'demo/images/nature/Waterfall Between Trees.jpeg',
            'demo/images/nature/Wildlife Photography of Elephant During Golden Hour.jpeg',
            'demo/images/nature/Yellow Animal Eyes Fur.jpeg',
        ])
            ->each(function ($file) use ($user, $nature) {
                $thumbnail = $this->generate_thumbnails($file, $user);

                // Create file record
                File::create([
                    'parent_id'  => $nature->id,
                    'user_id'    => $user->id,
                    'name'       => $thumbnail['name'],
                    'basename'   => $thumbnail['basename'],
                    'type'       => 'image',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });
    }

    private function create_team_folders_content(): void
    {
        $user = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        $alice = User::whereEmail('alice@hi5ve.digital')
            ->first();

        $johan = User::whereEmail('johan@hi5ve.digital')
            ->first();

        $users = [$user, $alice, $johan];

        // 1. Company project
        $companyProjectFolder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => true,
                'name'        => 'Company Project',
            ]);

        $presentationMaterial = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'parent_id'   => $companyProjectFolder->id,
                'name'        => 'Presentation Materials',
                'team_folder' => true,
            ]);

        $teamGallery = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'parent_id'   => $companyProjectFolder->id,
                'name'        => 'Team Gallery',
                'team_folder' => true,
            ]);

        collect([
            'demo/images/team-gallery/photo-1.jpeg',
            'demo/images/team-gallery/photo-2.jpeg',
            'demo/images/team-gallery/photo-3.jpeg',
        ])
            ->each(function ($file) use ($users, $user, $teamGallery) {
                $author = $users[rand(0, 2)];

                $thumbnail = $this->generate_thumbnails($file, $user);

                // Create file record
                File::create([
                    'parent_id'   => $teamGallery->id,
                    'user_id'     => $user->id,
                    'creator_id'  => $author->id,
                    'name'        => $thumbnail['name'],
                    'basename'    => $thumbnail['basename'],
                    'type'        => 'image',
                    'mimetype'    => 'jpg',
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        collect([
            'demo/images/presentation/photo-1.jpeg',
            'demo/images/presentation/photo-2.jpeg',
        ])
            ->each(function ($file) use ($users, $user, $presentationMaterial) {
                $author = $users[rand(0, 2)];

                $thumbnail = $this->generate_thumbnails($file, $user);

                // Create file record
                File::create([
                    'parent_id'   => $presentationMaterial->id,
                    'user_id'     => $user->id,
                    'creator_id'  => $author->id,
                    'name'        => $thumbnail['name'],
                    'basename'    => $thumbnail['basename'],
                    'type'        => 'image',
                    'mimetype'    => 'jpg',
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // 2. Finance Documents
        $financeDocumentsFolder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => true,
                'name'        => 'Finance Documents',
            ]);

        $reserves = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'parent_id'   => $financeDocumentsFolder->id,
                'name'        => 'Reserves',
                'team_folder' => true,
            ]);

        $otherDocuments = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'parent_id'   => $financeDocumentsFolder->id,
                'name'        => 'Other Documents',
                'team_folder' => true,
            ]);

        collect([
            'demo/images/finance-documents/photo-1.jpeg',
            'demo/images/finance-documents/photo-2.jpeg',
            'demo/images/finance-documents/photo-3.jpeg',
        ])
            ->each(function ($file) use ($users, $user, $financeDocumentsFolder) {
                $author = $users[rand(0, 2)];

                $thumbnail = $this->generate_thumbnails($file, $user);

                // Create file record
                File::create([
                    'parent_id'   => $financeDocumentsFolder->id,
                    'user_id'     => $user->id,
                    'creator_id'  => $author->id,
                    'name'        => $thumbnail['name'],
                    'basename'    => $thumbnail['basename'],
                    'type'        => 'image',
                    'mimetype'    => 'jpg',
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        collect([
            'demo/images/finance-documents/photo-4.jpeg',
            'demo/images/finance-documents/photo-5.jpeg',
        ])
            ->each(function ($file) use ($users, $user, $reserves) {
                $author = $users[rand(0, 2)];

                $thumbnail = $this->generate_thumbnails($file, $user);

                // Create file record
                File::create([
                    'parent_id'   => $reserves->id,
                    'user_id'     => $user->id,
                    'creator_id'  => $author->id,
                    'name'        => $thumbnail['name'],
                    'basename'    => $thumbnail['basename'],
                    'type'        => 'image',
                    'mimetype'    => 'jpg',
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get documents to root directory
        collect([
            [
                'name'     => 'Next Year Projection',
                'basename' => 'Licence.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Budget.pdf',
                'basename' => 'Project Notes.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => '2022 Yearly Report.pages',
                'basename' => 'School Report.pages',
                'mimetype' => 'pages',
            ],
            [
                'name'     => 'Company Project Notes.pages',
                'basename' => 'Stories of the Night Skies.pages',
                'mimetype' => 'pages',
            ],
            [
                'name'     => 'Finance Stories.pages',
                'basename' => 'Stories of the Night Skies.pages',
                'mimetype' => 'pages',
            ],
        ])
            ->each(function ($file) use ($users, $user, $financeDocumentsFolder, $otherDocuments) {
                $author = $users[rand(0, 2)];
                $folder = [$financeDocumentsFolder, $otherDocuments][rand(0, 1)];

                $basename = Str::random(12) . '-' . $file['basename'];

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/documents/{$file['basename']}"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'   => $folder->id,
                    'user_id'     => $user->id,
                    'creator_id'  => $author->id,
                    'name'        => $file['name'],
                    'basename'    => $basename,
                    'type'        => 'file',
                    'mimetype'    => $file['mimetype'],
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // 3. Holiday 2022
        $holiday2022Folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => true,
                'name'        => 'Holiday 2022',
            ]);

        $destinationGallery = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'parent_id'   => $holiday2022Folder->id,
                'name'        => 'Destination Gallery',
                'team_folder' => true,
            ]);

        collect([
            'demo/images/destination-gallery/photo-1.jpeg',
            'demo/images/destination-gallery/photo-2.jpeg',
            'demo/images/destination-gallery/photo-3.jpeg',
            'demo/images/destination-gallery/photo-4.jpeg',
            'demo/images/destination-gallery/photo-5.jpeg',
        ])
            ->each(function ($file) use ($users, $user, $destinationGallery) {
                $author = $users[rand(0, 2)];

                $thumbnail = $this->generate_thumbnails($file, $user);

                // Create file record
                File::create([
                    'parent_id'   => $destinationGallery->id,
                    'user_id'     => $user->id,
                    'creator_id'  => $author->id,
                    'name'        => $thumbnail['name'],
                    'basename'    => $thumbnail['basename'],
                    'type'        => 'image',
                    'mimetype'    => 'jpg',
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get documents to root directory
        collect([
            [
                'name'     => 'Finance.pages',
                'basename' => 'Licence.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Fly tickets.pdf',
                'basename' => 'Project Notes.pdf',
                'mimetype' => 'pdf',
            ],
            [
                'name'     => 'Documentation.pdf',
                'basename' => 'School Report.pages',
                'mimetype' => 'pages',
            ],
        ])
            ->each(function ($file) use ($users, $user, $holiday2022Folder) {
                $author = $users[rand(0, 2)];

                $basename = Str::random(12) . '-' . $file['basename'];

                // Copy file into app storage
                Storage::putFileAs("files/$user->id", storage_path("demo/documents/{$file['basename']}"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'   => $holiday2022Folder->id,
                    'user_id'     => $user->id,
                    'creator_id'  => $author->id,
                    'name'        => $file['name'],
                    'basename'    => $basename,
                    'type'        => 'file',
                    'mimetype'    => $file['mimetype'],
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        collect([$companyProjectFolder, $financeDocumentsFolder, $holiday2022Folder])
            ->each(function ($folder) use ($user) {
                DB::table('team_folder_members')
                    ->insert([
                        'parent_id'  => $folder->id,
                        'user_id'    => $user->id,
                        'permission' => 'owner',
                    ]);
            });

        // Attach members
        $members = User::whereNotIn('email', ['howdy@hi5ve.digital'])
            ->get();

        collect([$members[0]->id, $members[1]->id, $members[5]->id])
            ->each(
                fn ($id) => DB::table('team_folder_members')
                    ->insert([
                        'parent_id'  => $companyProjectFolder->id,
                        'user_id'    => $id,
                        'permission' => 'can-edit',
                    ])
            );

        collect([$members[3]->id, $members[2]->id])
            ->each(
                fn ($id) => DB::table('team_folder_members')
                    ->insert([
                        'parent_id'  => $financeDocumentsFolder->id,
                        'user_id'    => $id,
                        'permission' => 'can-edit',
                    ])
            );

        collect([$members[2]->id, $members[3]->id, $members[5]->id, $members[0]->id])
            ->each(
                fn ($id) => DB::table('team_folder_members')
                    ->insert([
                        'parent_id'  => $holiday2022Folder->id,
                        'user_id'    => $id,
                        'permission' => 'can-edit',
                    ])
            );

        // Create invitations
        collect([$members[4], $members[5]])
            ->each(
                fn ($user) => TeamFolderInvitation::factory()
                    ->create([
                        'email'      => $user->email,
                        'parent_id'  => $companyProjectFolder->id,
                        'inviter_id' => $companyProjectFolder->user_id,
                        'status'     => 'pending',
                        'permission' => 'can-edit',
                    ])
            );
    }

    public function create_share_with_me_team_folders_content(): void
    {
        $member = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        $owner = User::whereEmail('alice@hi5ve.digital')
            ->first();

        $johan = User::whereEmail('johan@hi5ve.digital')
            ->first();

        $users = [$member, $johan];

        $folder = Folder::factory()
            ->create([
                'user_id'     => $owner->id,
                'team_folder' => true,
                'name'        => "Alice's Project Files",
            ]);

        $videos = Folder::factory()
            ->create([
                'user_id'     => $owner->id,
                'parent_id'   => $folder->id,
                'name'        => 'Videos',
                'team_folder' => true,
            ]);

        $hug = Folder::factory()
            ->create([
                'user_id'     => $owner->id,
                'parent_id'   => $folder->id,
                'name'        => 'Digital Hug',
                'team_folder' => true,
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $member->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $owner->id,
                    'permission' => 'owner',
                ],
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $johan->id,
                    'permission' => 'can-edit',
                ],
            ]);

        // Get videos
        collect([
            'Apple Watch App Video Promotion.mp4',
            'Smart Watch 3D Device Pack for Element 3D.mp4',
        ])
            ->each(function ($file) use ($users, $owner, $videos) {
                $author = $users[rand(0, 1)];

                $basename = Str::random(12) . '-' . $file;

                // Copy file into app storage
                Storage::putFileAs("files/$owner->id", storage_path("demo/video/$file"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'   => $videos->id,
                    'user_id'     => $owner->id,
                    'creator_id'  => $author->id,
                    'name'        => $file,
                    'basename'    => $basename,
                    'type'        => 'video',
                    'mimetype'    => 'mp4',
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        collect([
            [
                'name'     => 'Notes',
                'basename' => 'Licence.pdf',
                'mimetype' => 'pdf',
            ],
        ])
            ->each(function ($file) use ($users, $owner, $folder) {
                $basename = Str::random(12) . '-' . $file['basename'];

                $author = $users[rand(0, 1)];

                // Copy file into app storage
                Storage::putFileAs("files/$owner->id", storage_path("demo/documents/{$file['basename']}"), $basename, 'private');

                // Create file record
                File::create([
                    'parent_id'   => $folder->id,
                    'user_id'     => $owner->id,
                    'creator_id'  => $author->id,
                    'name'        => $file['name'],
                    'basename'    => $basename,
                    'type'        => 'file',
                    'mimetype'    => $file['mimetype'],
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get meme gallery
        collect([
            'demo/images/memes/Sofishticated.jpg',
            'demo/images/memes/whaaaaat.jpg',
        ])
            ->each(function ($file) use ($owner, $folder) {
                $thumbnail = $this->generate_thumbnails($file, $owner);

                // Create file record
                File::create([
                    'parent_id'  => $folder->id,
                    'user_id'    => $owner->id,
                    'name'       => $thumbnail['name'],
                    'basename'   => $thumbnail['basename'],
                    'type'       => 'image',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });

        // Get meme gallery
        collect([
            'demo/images/memes/You Are My Sunshine.jpg',
        ])
            ->each(function ($file) use ($johan, $owner, $folder) {
                $thumbnail = $this->generate_thumbnails($file, $owner);

                // Create file record
                File::create([
                    'parent_id'   => $folder->id,
                    'user_id'     => $owner->id,
                    'creator_id'  => $johan->id,
                    'name'        => $thumbnail['name'],
                    'basename'    => $thumbnail['basename'],
                    'type'        => 'image',
                    'mimetype'    => 'jpg',
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });

        collect([
            'demo/images/memes/Eggcited bro.jpg',
            'demo/images/memes/Get a Rest.jpg',
        ])
            ->each(function ($file) use ($member, $owner, $hug) {
                $thumbnail = $this->generate_thumbnails($file, $owner);

                // Create file record
                File::create([
                    'parent_id'   => $hug->id,
                    'user_id'     => $owner->id,
                    'creator_id'  => $member->id,
                    'name'        => $thumbnail['name'],
                    'basename'    => $thumbnail['basename'],
                    'type'        => 'image',
                    'mimetype'    => 'jpg',
                    'filesize'    => rand(1000000, 4000000),
                    'created_at'  => now()->subMinutes(rand(1, 5)),
                ]);
            });
    }

    private function create_share_records(): void
    {
        $user = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        $images = File::whereType('image')
            ->whereParentId(null)
            ->take(3)
            ->pluck('id');

        $images->each(function ($id) use ($user) {
            Share::create([
                'user_id'      => $user->id,
                'item_id'      => $id,
                'type'         => 'file',
                'is_protected' => false,
                'permission'   => 'editor',
                'password'     => null,
                'expire_in'    => null,
            ]);
        });

        $files = File::whereType('file')
            ->whereParentId(null)
            ->take(2)
            ->pluck('id');

        $files->each(function ($id) use ($user) {
            Share::create([
                'user_id'      => $user->id,
                'item_id'      => $id,
                'type'         => 'file',
                'is_protected' => false,
                'permission'   => 'editor',
                'password'     => null,
                'expire_in'    => null,
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
                'value' => 'system/logo.svg',
            ],
            [
                'name'  => 'app_logo_dark',
                'value' => 'system/logo-dark.svg',
            ],
            [
                'name'  => 'app_logo_horizontal',
                'value' => 'system/logo-horizontal.svg',
            ],
            [
                'name'  => 'app_logo_horizontal_dark',
                'value' => 'system/logo-horizontal-dark.svg',
            ],
            [
                'name'  => 'app_favicon',
                'value' => 'system/favicon.png',
            ],
            [
                'name'  => 'app_og_image',
                'value' => 'system/og-image.jpg',
            ],
            [
                'name'  => 'app_touch_icon',
                'value' => 'system/touch-icon.png',
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
                'name'  => 'user_verification',
                'value' => 0,
            ],
            [
                'name'  => 'allowed_payments',
                'value' => 1,
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
                'value' => 'Palo Alto 20',
            ],
            [
                'name'  => 'billing_city',
                'value' => 'Palo Alto',
            ],
            [
                'name'  => 'billing_country',
                'value' => 'US',
            ],
            [
                'name'  => 'billing_name',
                'value' => 'VueFileManager Inc.',
            ],
            [
                'name'  => 'billing_phone_number',
                'value' => '312343141243214',
            ],
            [
                'name'  => 'billing_postal_code',
                'value' => '43213',
            ],
            [
                'name'  => 'billing_state',
                'value' => 'California',
            ],
            [
                'name'  => 'billing_vat_number',
                'value' => '41241241234',
            ],
            [
                'name'  => 'allowed_registration_bonus',
                'value' => 1,
            ],
            [
                'name'  => 'registration_bonus_amount',
                'value' => 10,
            ],
            [
                'name'  => 'allowed_paypal',
                'value' => 1,
            ],
            [
                'name'  => 'allowed_paystack',
                'value' => 1,
            ],
            [
                'name'  => 'allowed_stripe',
                'value' => 1,
            ],
            [
                'name'  => 'subscription_type',
                'value' => 'none',
            ],
            [
                'name'  => 'allowed_recaptcha',
                'value' => 0,
            ],
        ])->each(function ($col) {
            Setting::updateOrCreate([
                'name' => $col['name'],
            ], [
                'value' => $col['value'],
            ]);
        });

        // Get system images
        collect(['logo.svg', 'logo-dark.svg', 'logo-horizontal.svg', 'logo-horizontal-dark.svg', 'favicon.png', 'og-image.jpg', 'touch-icon.png'])
            ->each(function ($file) {
                Storage::putFileAs('system', storage_path("demo/app/$file"), $file, 'private');
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
     * Generate demo traffic data
     */
    private function generate_traffic(): void
    {
        $user = User::all();

        foreach (range(0, 45) as $day) {
            $user
                ->each(
                    fn ($user) => DB::table('traffic')
                        ->insert([
                            'id'         => Str::uuid(),
                            'user_id'    => $user->id,
                            'upload'     => random_int(1111111, 9999999),
                            'download'   => random_int(11111111, 99999999),
                            'created_at' => now()->subDays($day),
                            'updated_at' => now()->subDays($day),
                        ])
                );
        }
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

    /**
     * @param $avatar
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function generate_avatar($avatar): string
    {
        $image = \Illuminate\Support\Facades\File::get(storage_path("/demo/avatars/{$avatar}"));

        // Create avatar name
        $avatar_name = Str::uuid() . '.png';

        // Create intervention image
        $img = Image::make($image);

        $this->info("Generating thumbnails for $avatar...");

        // Generate avatar
        collect(config('vuefilemanager.avatar_sizes'))
            ->each(function ($size) use ($img, $avatar_name) {
                // fit thumbnail
                $img->fit($size['size'], $size['size'])->stream();

                // Store thumbnail to disk
                Storage::put("avatars/{$size['name']}-{$avatar_name}", $img);
            });

        return $avatar_name;
    }

    /**
     * @param $file
     * @param $user
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function generate_thumbnails($file, $user): array
    {
        // Create image name
        $file_name = Str::uuid() . '.jpg';

        $this->info("Generating thumbnails for $file...");

        // Generate avatar sizes
        collect([
            config('vuefilemanager.image_sizes.later'),
            config('vuefilemanager.image_sizes.immediately'),
        ])->collapse()
            ->each(function ($size) use ($file_name, $user, $file) {
                $image = \Illuminate\Support\Facades\File::get(storage_path($file));

                // Create intervention image
                $intervention = Image::make($image)->orientate();

                // Create thumbnail only if image is larger than predefined image sizes
                if ($intervention->getWidth() > $size['size']) {
                    // Generate thumbnail
                    $intervention->resize($size['size'], null, fn ($constraint) => $constraint->aspectRatio())->stream();

                    // Store thumbnail to disk
                    Storage::put("files/$user->id/{$size['name']}-{$file_name}", $intervention);
                }
            });

        // Store original to disk
        Storage::putFileAs("files/$user->id", storage_path($file), $file_name, 'private');

        return [
            'basename' => $file_name,
            'name'     => head(explode('.', last(explode('/', $file)))),
        ];
    }
}
