<?php

namespace Tests\Feature\FileManager;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use App\Services\SetupService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class TrashTest extends TestCase
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
    public function it_restore_items_from_trash()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $attributes = [
            'user_id'    => $user->id,
            'deleted_at' => now(),
        ];

        $folder = Folder::factory(Folder::class)
            ->create($attributes);

        $file = File::factory(File::class)
            ->create($attributes);

        $this->postJson("/api/trash/restore", [
            'items' => [
                [
                    'id'   => $file->id,
                    'type' => 'file',
                ],
                [
                    'id'   => $folder->id,
                    'type' => 'folder',
                ],
            ],
        ])->assertStatus(204);

        $this->assertDatabaseHas('files', [
            'deleted_at' => null
        ]);

        $this->assertDatabaseHas('folders', [
            'deleted_at' => null
        ]);
    }

    /**
     * @test
     */
    public function it_dump_trash()
    {
        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        $image = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this->postJson('/api/upload', [
            'filename'  => $image->name,
            'file'      => $image,
            'folder_id' => null,
            'is_last'   => true,
        ])->assertStatus(201);

        $file = File::first();

        $this->postJson("/api/remove", [
            'items' => [
                [
                    'id'           => $file->id,
                    'type'         => 'file',
                    'force_delete' => false,
                ],
                [
                    'id'           => $folder->id,
                    'type'         => 'folder',
                    'force_delete' => false,
                ],
            ],
        ])->assertStatus(204);

        $this->deleteJson("/api/trash/dump")
            ->assertStatus(204);

        $this->assertDatabaseMissing('files', [
            'id' => $file->id
        ]);

        $this->assertDatabaseMissing('folders', [
            'id' => $folder->id
        ]);

        $disk = Storage::disk('local');

        $disk->assertMissing(
            "files/$user->id/fake-image.jpg"
        );

        $disk->assertMissing(
            "files/$user->id/thumbnail-fake-image.jpg"
        );
    }
}
