<?php
namespace Tests\Domain\Sharing;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
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
                $user = User::factory()
                    ->create();

                $document = UploadedFile::fake()
                    ->create(Str::random() . '-fake-file.pdf', 1000, 'application/pdf');

                Storage::putFileAs("files/$user->id", $document, $document->name);

                $file = File::factory()
                    ->create([
                        'filesize' => $document->getSize(),
                        'user_id'  => $user->id,
                        'basename' => $document->name,
                        'name'     => $document->name,
                    ]);

                $share = Share::factory()
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
                        ->get("/file/$document->name/shared/$share->token")
                        ->assertStatus(200)
                        ->assertDownload($document->name);
                }

                if (! $is_protected) {
                    // Get shared file
                    $this->get("/file/$document->name/shared/$share->token")
                        ->assertStatus(200)
                        ->assertDownload($document->name);
                }
            });
    }

    /**
     * @test
     */
    public function it_directly_download_file()
    {
        $user = User::factory()
            ->create();

        $document = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1000, 'application/pdf');

        Storage::putFileAs("files/$user->id", $document, $document->name);

        $file = File::factory()
            ->create([
                'filesize' => $document->getSize(),
                'user_id'  => $user->id,
                'basename' => $document->name,
                'name'     => $document->name,
            ]);

        $share = Share::factory()
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $user->id,
                'type'         => 'file',
                'is_protected' => false,
            ]);

        // Get shared file
        $this->get("/share/$share->token/direct")
            ->assertStatus(200)
            ->assertDownload($document->name);
    }

    /**
     * @test
     */
    public function it_try_directly_download_protected_file()
    {
        $share = Share::factory()
            ->create([
                'type'         => 'file',
                'is_protected' => true,
            ]);

        // Get shared file
        $this->get("/share/$share->token/direct")
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_try_to_get_protected_file_record()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = File::factory()
            ->create([
                'user_id' => $user->id,
                'name'    => 'fake-thumbnail.jpg',
            ]);

        $share = Share::factory()
            ->create([
                'type'         => 'file',
                'is_protected' => true,
                'user_id'      => $user->id,
                'item_id'      => $file->id,
            ]);

        // Get share record
        $this->get("/api/sharing/file/$share->token")
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_get_shared_image()
    {
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory()
                    ->create();

                $fileName = Str::random() . '-fake-image.jpg';

                $thumbnail = UploadedFile::fake()
                    ->image($fileName, 2000, 2000);

                Storage::putFileAs("files/$user->id", $thumbnail, $fileName);

                $file = File::factory()
                    ->create([
                        'user_id'   => $user->id,
                        'basename'  => $fileName,
                        'name'      => $fileName,
                        'type'      => 'image',
                        'mimetype'  => 'jpg',
                    ]);

                $share = Share::factory()
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
                        ->assertRedirect("/share/$share->token/authenticate");
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
                $user = User::factory()
                    ->create();

                $fileName = Str::random() . '-fake-thumbnail.jpg';

                $thumbnail = UploadedFile::fake()
                    ->image($fileName);

                Storage::putFileAs("files/$user->id", $thumbnail, $fileName);

                $file = File::factory()
                    ->create([
                        'user_id'  => $user->id,
                        'name'     => $fileName,
                        'basename' => $fileName,
                    ]);

                $share = Share::factory()
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
                        ->get("/thumbnail/xs-$fileName/shared/$share->token")
                        ->assertDownload("xs-$fileName");
                }

                if (! $is_protected) {
                    $this->get("/thumbnail/xs-$fileName/shared/$share->token")
                        ->assertDownload("xs-$fileName");
                }

                $this->assertDatabaseMissing('traffic', [
                    'user_id'  => $user->id,
                    'download' => null,
                ]);
            });
    }
}
