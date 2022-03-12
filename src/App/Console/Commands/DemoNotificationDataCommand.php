<?php
namespace App\Console\Commands;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Illuminate\Console\Command;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Domain\Teams\Models\TeamFolderInvitation;
use Intervention\Image\ImageManagerStatic as Image;

class DemoNotificationDataCommand extends Command
{
    use WithFaker;

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'demo:notifications';

    /**
     * The console command description.
     */
    protected $description = 'Set up demo notifications data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Setting up notification demo data');

        $this->generateTeamInvitationNotification();
        $this->generateFileRequestFilledNotification();

        $this->info('Everything is done, congratulations! 🥳🥳🥳');
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
                    'type'        => 'team-invitation',
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
                    'type'        => 'file-request',
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
                    'author'     => 'user',
                    'mimetype'   => 'jpg',
                    'filesize'   => rand(1000000, 4000000),
                    'created_at' => now()->subMinutes(rand(1, 5)),
                ]);
            });
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
