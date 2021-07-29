<?php
namespace Tests\Domain\Sharing;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Zip\Models\Zip;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Illuminate\Http\UploadedFile;

class VisitorAccessToItemsTest extends TestCase
{
    /**
     * @test
     */
    public function it_download_file()
    {
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory(User::class)
                    ->create();

                $document = UploadedFile::fake()
                    ->create(Str::random() . '-fake-file.pdf', 1000, 'application/pdf');

                Storage::putFileAs("files/$user->id", $document, $document->name);

                $file = File::factory(File::class)
                    ->create([
                        'filesize' => $document->getSize(),
                        'user_id'  => $user->id,
                        'basename' => $document->name,
                        'name'     => $document->name,
                    ]);

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $file->id,
                        'user_id'      => $user->id,
                        'type'         => 'file',
                        'is_protected' => $is_protected,
                    ]);

                if ($is_protected) {
                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this->withCookies($cookie)
                        ->get("/file/$document->name/$share->token")
                        ->assertStatus(200);
                }

                if (! $is_protected) {
                    // Get shared file
                    $this->get("/file/$document->name/$share->token")
                        ->assertStatus(200);
                }
            });
    }

    /**
     * @test
     */
    public function it_try_to_get_protected_file_record()
    {
        $share = Share::factory(Share::class)
            ->create([
                'type'         => 'file',
                'is_protected' => true,
            ]);

        // Get share record
        $this->get("/api/browse/file/$share->token")
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_get_shared_image()
    {
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory(User::class)
                    ->create();

                $thumbnail = UploadedFile::fake()
                    ->image(Str::random() . '-fake-image.jpg');

                Storage::putFileAs("files/$user->id", $thumbnail, $thumbnail->name);

                $file = File::factory(File::class)
                    ->create([
                        'user_id'   => $user->id,
                        'thumbnail' => $thumbnail->name,
                        'basename'  => $thumbnail->name,
                        'name'      => 'fake-thumbnail.jpg',
                        'type'      => 'image',
                        'mimetype'  => 'jpg',
                    ]);

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $file->id,
                        'user_id'      => $user->id,
                        'type'         => 'file',
                        'is_protected' => $is_protected,
                    ]);

                if ($is_protected) {
                    $cookie = [
                        'share_session' => json_encode([
                            'token'         => $share->token,
                            'authenticated' => true,
                        ]),
                    ];

                    $this->withCookies($cookie)
                        ->get("/share/$share->token")
                        ->assertStatus(200);
                }

                if (! $is_protected) {
                    $this->get("/share/$share->token")
                        ->assertStatus(200);
                }
            });
    }

    /**
     * @test
     */
    public function it_get_public_thumbnail()
    {
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory(User::class)
                    ->create();

                $thumbnail = UploadedFile::fake()
                    ->image(Str::random() . '-fake-thumbnail.jpg');

                Storage::putFileAs("files/$user->id", $thumbnail, $thumbnail->name);

                $file = File::factory(File::class)
                    ->create([
                        'user_id'   => $user->id,
                        'thumbnail' => $thumbnail->name,
                        'name'      => 'fake-thumbnail.jpg',
                    ]);

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $file->id,
                        'user_id'      => $user->id,
                        'type'         => 'file',
                        'is_protected' => $is_protected,
                    ]);

                // Get thumbnail file
                if ($is_protected) {
                    $cookie = [
                        'share_session' => json_encode([
                            'token'         => $share->token,
                            'authenticated' => true,
                        ]),
                    ];

                    $this->withCookies($cookie)
                        ->get("/thumbnail/$thumbnail->name/$share->token")
                        ->assertStatus(200);
                }

                if (! $is_protected) {
                    $this->get("/thumbnail/$thumbnail->name/$share->token")
                        ->assertStatus(200);
                }

                $this->assertDatabaseMissing('traffic', [
                    'user_id'  => $user->id,
                    'download' => null,
                ]);
            });
    }
}
