<?php
namespace Tests\Domain\Trash;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;

class TrashTest extends TestCase
{
    /**
     * @test
     */
    public function it_restore_items_from_trash()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $attributes = [
            'user_id'    => $user->id,
            'deleted_at' => now(),
        ];

        $folder = Folder::factory()
            ->create($attributes);

        $file = File::factory()
            ->create($attributes);

        $this
            ->actingAs($user)
            ->postJson('/api/trash/restore', [
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
            ])->assertStatus(200);

        $this->assertDatabaseHas('files', [
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('folders', [
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_dump_trash()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $image = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this->postJson('/api/upload/chunks', [
            'name'           => $image->name,
            'chunk'          => $image,
            'is_last_chunk'  => 1,
            'extension'      => 'jpg',
        ])->assertStatus(201);

        $file = File::first();

        $this->postJson('/api/remove', [
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
        ])->assertStatus(200);

        $this->deleteJson('/api/trash/dump')
            ->assertStatus(200);

        $this->assertDatabaseMissing('files', [
            'id' => $file->id,
        ]);

        $this->assertDatabaseMissing('folders', [
            'id' => $folder->id,
        ]);

        $disk = Storage::disk('local');

        $thumbnail_sizes = collect(config('vuefilemanager.image_sizes'))
            ->collapse()
            ->all();

        $disk->assertMissing(
            "files/$user->id/fake-image.jpg"
        );

        foreach ($thumbnail_sizes as $size) {
            $disk->assertMissing(
                "files/$user->id/{$size['name']}-fake-image.jpg"
            );
        }
    }
}
