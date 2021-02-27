<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
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
        $folder = File::factory(File::class)
            ->create();

        $this->assertDatabaseHas('files', [
            'id' => $folder->id
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
            'file'      => $file,
            'folder_id' => null,
            'is_last'   => true,
        ])->assertStatus(201);

        Storage::disk('local')->assertMissing(
            "chunks/fake-image.jpg"
        );

        Storage::disk('local')->assertExists(
            "files/$user->id/fake-image.jpg"
        );

        Storage::disk('local')->assertExists(
            "files/$user->id/thumbnail-fake-image.jpg"
        );

        $this->assertDatabaseHas('traffic', [
            'user_id' => $user->id,
        ]);
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

        $file_1 = File::factory(File::class)
            ->create();

        $file_2 = File::factory(File::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/remove", [
            'items' => [
                [
                    'id'           => $file_1->id,
                    'type'         => 'file',
                    'force_delete' => false,
                ],
                [
                    'id'           => $file_2->id,
                    'type'         => 'file',
                    'force_delete' => false,
                ],
            ],
        ])->assertStatus(204);

        $this->assertSoftDeleted('files', [
            'id' => $file_1->id,
        ]);

        $this->assertSoftDeleted('files', [
            'id' => $file_2->id,
        ]);
    }

    public function it_delete_multiple_files_hardly()
    {

    }

    public function it_zip_and_download_multiple_files()
    {

    }
}
