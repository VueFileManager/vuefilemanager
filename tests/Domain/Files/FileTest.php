<?php
namespace Tests\Domain\Files;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use Domain\Settings\Models\Setting;

class FileTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_file_factory()
    {
        $file = File::factory()
            ->create();

        $this->assertDatabaseHas('files', [
            'id' => $file->id,
        ]);
    }

    /**
     * @test
     */
    public function it_upload_image_file_and_create_thumbnail()
    {
        $file = UploadedFile::fake()
            ->image('fake-image.jpg', 2000, 2000);

        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/upload/chunks', [
                'name'            => $file->name,
                'extension'       => '.jpg',
                'chunk'           => $file,
                'is_last_chunk'   => 1,
            ])->assertStatus(201);

        $file = File::first();

        Storage::assertMissing(
            "chunks/$file->basename"
        );

        collect([
            config('vuefilemanager.image_sizes.later'),
            config('vuefilemanager.image_sizes.immediately'),
        ])
            ->collapse()
            ->each(
                fn ($item) => Storage::assertExists(
                    "files/{$user->id}/{$item['name']}-{$file->basename}"
                )
            );
    }

    /**
     * @test
     */
    public function it_upload_new_file()
    {
        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 12000000, 'application/pdf');

        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/upload/chunks', [
                'name'            => $file->name,
                'extension'       => 'pdf',
                'chunk'           => $file,
                'is_last_chunk'   => 1,
            ])->assertStatus(201);

        $disk = Storage::disk('local');

        $file = File::first();

        $disk->assertMissing(
            "chunks/$file->basename"
        );

        $disk->assertExists(
            "files/$user->id/$file->basename"
        );
    }

    /**
     * @test
     */
    public function user_with_full_storage_capacity_try_to_upload_new_file()
    {
        $file = UploadedFile::fake()
            ->image('fake-file.jpeg', 1000);

        $user = User::factory()
            ->hasSettings()
            ->create();

        $user->limitations()->update([
            'max_storage_amount' => 1,
        ]);

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'filesize' => '1000000000',
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/upload/chunks', [
                'name'            => $file->name,
                'extension'       => 'jpeg',
                'chunk'           => $file,
                'is_last_chunk'   => 1,
            ])->assertStatus(401);

        Storage::disk('local')->assertMissing(
            "files/$user->id/fake-file.jpeg"
        );
    }

    /**
     * @test
     */
    public function it_upload_blacklisted_mimetype_file()
    {
        Setting::create([
            'name'  => 'mimetypes_blacklist',
            'value' => 'pdf',
        ]);

        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 1200, 'application/pdf');

        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/upload/chunks', [
                'chunk'           => $file,
                'extension'       => 'pdf',
                'is_last_chunk'   => 1,
            ])->assertStatus(422);

        Storage::disk('local')
            ->assertMissing("files/$user->id/fake-file.pdf");
    }

    /**
     * @test
     */
    public function it_rename_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = File::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/rename/{$file->id}", [
                'name' => 'Renamed Item',
                'type' => 'file',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed Item',
            ]);

        $this->assertDatabaseHas('files', [
            'name' => 'Renamed Item',
        ]);
    }

    /**
     * @test
     */
    public function it_move_file_to_another_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $file = File::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/move', [
                'to_id' => $folder->id,
                'items' => [
                    [
                        'type' => 'file',
                        'id'   => $file->id,
                    ],
                ],
            ])->assertStatus(200);

        $this->assertDatabaseHas('files', [
            'id'        => $file->id,
            'parent_id' => $folder->id,
        ]);
    }

    /**
     * @test
     */
    public function it_delete_image_with_their_thumbnails()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $image = File::factory()
            ->create([
                'mimetype' => 'jpeg',
                'type'     => 'image',
                'basename' => 'fake-image.jpeg',
                'user_id'  => $user->id,
            ]);

        // Mock files
        $thumbnail_sizes = collect([
            config('vuefilemanager.image_sizes.later'),
            config('vuefilemanager.image_sizes.immediately'),
        ])->collapse();

        $fakeFile = UploadedFile::fake()
            ->create('fake-image.jpeg', 2000, 'image/jpeg');

        Storage::putFileAs("files/$user->id", $fakeFile, $fakeFile->name);

        // Create fake image thumbnails
        $thumbnail_sizes
            ->each(function ($item) use ($user) {
                $fakeFile = UploadedFile::fake()
                    ->create("{$item['name']}-fake-image.jpeg", 2000, 'image/jpeg');

                Storage::putFileAs("files/$user->id", $fakeFile, $fakeFile->name);
            });

        $this
            ->actingAs($user)
            ->postJson('/api/remove', [
                'items' => [
                    [
                        'id'           => $image->id,
                        'type'         => 'file',
                        'force_delete' => true,
                    ],
                ],
            ])->assertStatus(200);

        // Assert primary file was deleted
        Storage::assertMissing("files/$user->id/fake-image.jpeg");

        // Assert thumbnail was deleted
        getThumbnailFileList('fake-image.jpeg')
            ->each(fn ($thumbnail) => Storage::assertMissing("files/$user->id/$thumbnail"));
    }

    /**
     * @test
     */
    public function it_delete_multiple_files_softly()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $files = File::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/remove', [
                'items' => [
                    [
                        'id'           => $files[0]->id,
                        'type'         => 'file',
                        'force_delete' => false,
                    ],
                    [
                        'id'           => $files[1]->id,
                        'type'         => 'file',
                        'force_delete' => false,
                    ],
                ],
            ])->assertStatus(200);

        $files
            ->each(function ($file) {
                $this->assertSoftDeleted('files', [
                    'id' => $file->id,
                ]);
            });
    }

    /**
     * @test
     */
    public function it_delete_multiple_files_hardly()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        Sanctum::actingAs($user);

        collect([0, 1])
            ->each(function ($index) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload/chunks', [
                    'name'            => $file->name,
                    'extension'       => 'pdf',
                    'chunk'           => $file,
                    'is_last_chunk'   => 1,
                ])->assertStatus(201);
            });

        $file_ids = File::all()->pluck('id');

        $this->postJson('/api/remove', [
            'items' => [
                [
                    'id'           => $file_ids->first(),
                    'type'         => 'file',
                    'force_delete' => true,
                ],
                [
                    'id'           => $file_ids->last(),
                    'type'         => 'file',
                    'force_delete' => true,
                ],
            ],
        ])->assertStatus(200);

        $file_ids
            ->each(function ($id, $index) use ($user) {
                $this->assertDatabaseMissing('files', [
                    'id' => $id,
                ]);

                Storage::disk('local')
                    ->assertMissing(
                        "files/$user->id/fake-file-$index.pdf"
                    );
            });
    }

    /**
     * @test
     */
    public function it_store_file_exif_data_after_file_upload()
    {
        $file = UploadedFile::fake()
            ->image('fake-image.jpg', 2000, 2000);

        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/upload/chunks', [
                'name'            => $file->name,
                'extension'       => 'jpg',
                'chunk'           => $file,
                'is_last_chunk'   => 1,
            ])->assertStatus(201);

        $file = File::first();

        $this->assertDatabaseHas('exifs', [
            'file_id' => $file->id,
            'height'  => 2000,
            'width'   => 2000,
        ]);
    }
}
