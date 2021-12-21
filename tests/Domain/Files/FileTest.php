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
        $file = File::factory(File::class)
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
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/upload', [
                'filename'  => $file->name,
                'file'      => $file,
                'parent_id' => null,
                'is_last'   => 'true',
            ])->assertStatus(201);

        $disk = Storage::disk('local');

        $file = File::first();

        $disk->assertMissing(
            "chunks/$file->basename"
        );

        collect([
            config('vuefilemanager.image_sizes.later'),
            config('vuefilemanager.image_sizes.immediately'),
        ])
            ->collapse()
            ->each(
                fn($item) => $disk->assertExists(
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

        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/upload', [
                'filename'  => $file->name,
                'file'      => $file,
                'parent_id' => null,
                'is_last'   => 'true',
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
        Setting::create([
            'name'  => 'storage_limitation',
            'value' => 1,
        ]);

        $file = UploadedFile::fake()
            ->image('fake-file.jpeg', 1000);

        $user = User::factory(User::class)
            ->create();

        $user->settings()->update([
            'storage_capacity' => 1,
        ]);

        File::factory(File::class)
            ->create([
                'user_id'  => $user->id,
                'filesize' => '1000000000',
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/upload', [
                'filename'  => $file->name,
                'file'      => $file,
                'parent_id' => null,
                'is_last'   => 'true',
            ])->assertStatus(423);

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

        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/upload', [
                'file'      => $file,
                'parent_id' => null,
                'is_last'   => 'true',
            ])->assertStatus(422);

        Storage::disk('local')
            ->assertMissing("files/$user->id/fake-file.pdf");
    }

    /**
     * @test
     */
    public function it_rename_file()
    {
        $user = User::factory(User::class)
            ->create();

        $file = File::factory(File::class)
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
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $file = File::factory(File::class)
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
            ])->assertStatus(204);

        $this->assertDatabaseHas('files', [
            'id'        => $file->id,
            'parent_id' => $folder->id,
        ]);
    }

    /**
     * @test
     */
    public function it_delete_multiple_files_softly()
    {
        $user = User::factory(User::class)
            ->create();

        $files = File::factory(File::class)
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
            ])->assertStatus(204);

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
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        collect([0, 1])
            ->each(function ($index) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload', [
                    'filename'  => $file->name,
                    'file'      => $file,
                    'parent_id' => null,
                    'is_last'   => 'true',
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
        ])->assertStatus(204);

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
}
