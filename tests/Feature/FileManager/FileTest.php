<?php

namespace Tests\Feature\FileManager;

use App\Models\File;
use App\Models\Folder;
use App\Models\Setting;
use App\Models\User;
use App\Models\Zip;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class FileTest extends TestCase
{
    use DatabaseMigrations;

    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

    /**
     * @test
     */
    public function it_test_file_factory()
    {
        $file = File::factory(File::class)
            ->create();

        $this->assertDatabaseHas('files', [
            'id' => $file->id
        ]);
    }

    /**
     * @test
     */
    public function it_upload_image_file_and_create_thumbnail()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $file = UploadedFile::fake()
            ->image('fake-image.jpg');

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/upload', [
            'filename'  => $file->name,
            'file'      => $file,
            'folder_id' => null,
            'is_last'   => true,
        ])->assertStatus(201);

        $disk = Storage::disk('local');

        $disk->assertMissing(
            "chunks/fake-image.jpg"
        );

        $disk->assertExists(
            "files/$user->id/fake-image.jpg"
        );

        $disk->assertExists(
            "files/$user->id/thumbnail-fake-image.jpg"
        );

        $this->assertDatabaseHas('traffic', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     */
    public function it_upload_new_file()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 1200, 'application/pdf');

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/upload', [
            'filename'  => $file->name,
            'file'      => $file,
            'folder_id' => null,
            'is_last'   => true,
        ])->assertStatus(201);

        $disk = Storage::disk('local');

        $disk->assertMissing(
            "chunks/fake-file.pdf"
        );

        $disk->assertExists(
            "files/$user->id/fake-file.pdf"
        );

        $this->assertDatabaseHas('traffic', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     */
    public function it_upload_blacklisted_mimetype_file()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        Setting::create([
            'name'  => 'mimetypes_blacklist',
            'value' => 'pdf',
        ]);

        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 1200, 'application/pdf');

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/upload', [
            'file'      => $file,
            'folder_id' => null,
            'is_last'   => true,
        ])->assertStatus(422);

        Storage::disk('local')
            ->assertMissing("files/$user->id/fake-file.pdf");
    }

    /**
     * @test
     */
    public function it_rename_file()
    {
        $file = File::factory(File::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->patchJson("/api/rename/{$file->id}", [
            'name' => 'Renamed Item',
            'type' => 'file',
        ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed Item',
            ]);

        $this->assertDatabaseHas('files', [
            'name' => 'Renamed Item'
        ]);
    }

    /**
     * @test
     */
    public function it_move_file_to_another_folder()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $file = File::factory(File::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/move", [
            'to_id' => $folder->id,
            'items' => [
                [
                    'type' => 'file',
                    'id'   => $file->id,
                ]
            ],
        ])->assertStatus(204);

        $this->assertDatabaseHas('files', [
            'id'        => $file->id,
            'folder_id' => $folder->id,
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
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/remove", [
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
        Storage::fake('local');

        $this->setup->create_directories();

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
                    'folder_id' => null,
                    'is_last'   => true,
                ])->assertStatus(201);
            });

        $file_ids = File::all()->pluck('id');

        $this->postJson("/api/remove", [
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

    /**
     * @test
     */
    public function it_zip_multiple_files_and_download_it()
    {
        Storage::fake('local');

        $this->setup->create_directories();

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
                    'folder_id' => null,
                    'is_last'   => true,
                ])->assertStatus(201);
            });

        $file_ids = File::all()->pluck('id');

        $this->postJson("/api/zip/files", [
            'items' => $file_ids,
        ])->assertStatus(201);

        $this->assertDatabaseHas('zips', [
            'user_id' => $user->id
        ]);

        Storage::disk('local')
            ->assertExists(
                'zip/' . Zip::first()->basename
            );
    }
}
