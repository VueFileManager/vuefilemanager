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
    public function it_dump_trash()
    {
        Storage::fake('local');

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
