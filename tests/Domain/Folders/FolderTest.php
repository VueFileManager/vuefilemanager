<?php
namespace Tests\Domain\Folders;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;

class FolderTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_folder_factory()
    {
        $folder = Folder::factory()
            ->create();

        $this->assertDatabaseHas('folders', [
            'id' => $folder->id,
        ]);
    }

    /**
     * @test
     */
    public function it_create_new_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/create-folder', [
                'name' => 'New Folder',
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'New Folder',
            ]);

        $this->assertDatabaseHas('folders', [
            'name' => 'New Folder',
        ]);
    }

    /**
     * @test
     */
    public function it_rename_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/rename/{$folder->id}", [
                'name' => 'Renamed Folder',
                'type' => 'folder',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed Folder',
            ]);

        $this->assertDatabaseHas('folders', [
            'name' => 'Renamed Folder',
        ]);
    }

    /**
     * @test
     */
    public function it_set_folder_emoji()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $emoji_fragment = [
            'category' => 'Smileys & Emotion (face-smiling)',
            'char'     => '😁',
            'name'     => 'beaming face with smiling eyes',
        ];

        $this
            ->actingAs($user)
            ->patchJson("/api/rename/{$folder->id}", [
                'name'  => 'Renamed Folder',
                'type'  => 'folder',
                'emoji' => $emoji_fragment,
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'  => 'Renamed Folder',
                'emoji' => $emoji_fragment,
            ]);

        $this->assertDatabaseHas('folders', [
            'color' => null,
            'emoji' => json_encode($emoji_fragment),
        ]);
    }

    /**
     * @test
     */
    public function it_set_folder_color()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/rename/{$folder->id}", [
                'name'  => 'Folder Name',
                'type'  => 'folder',
                'color' => '#AD6FFE',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'  => 'Folder Name',
                'emoji' => null,
                'color' => '#AD6FFE',
            ]);

        $this->assertDatabaseHas('folders', [
            'color' => '#AD6FFE',
            'emoji' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_move_folder_to_another_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $root = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $children = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/move', [
                'to_id' => $root->id,
                'items' => [
                    [
                        'type' => 'folder',
                        'id'   => $children->id,
                    ],
                ],
            ])->assertStatus(200);

        $this->assertEquals(
            $root->id,
            Folder::find($children->id)->parent_id
        );
    }

    /**
     * @test
     */
    public function it_delete_multiple_folder_softly()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder_1 = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $folder_2 = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $user->favouriteFolders()->attach($folder_1->id);
        $user->favouriteFolders()->attach($folder_2->id);

        $this
            ->actingAs($user)
            ->postJson('/api/remove', [
                'items' => [
                    [
                        'id'           => $folder_1->id,
                        'type'         => 'folder',
                        'force_delete' => false,
                    ],
                    [
                        'id'           => $folder_2->id,
                        'type'         => 'folder',
                        'force_delete' => false,
                    ],
                ],
            ])->assertStatus(200);

        collect([$folder_1, $folder_2])
            ->each(function ($folder) {
                $this->assertSoftDeleted('folders', [
                    'id' => $folder->id,
                ]);

                $this->assertDatabaseMissing('favourite_folder', [
                    'parent_id' => $folder->id,
                ]);
            });
    }

    /**
     * @test
     */
    public function it_delete_multiple_folder_hardly()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder_1 = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $folder_2 = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/remove', [
                'items' => [
                    [
                        'id'           => $folder_1->id,
                        'type'         => 'folder',
                        'force_delete' => true,
                    ],
                    [
                        'id'           => $folder_2->id,
                        'type'         => 'folder',
                        'force_delete' => true,
                    ],
                ],
            ])->assertStatus(200);

        $this->assertDatabaseMissing('folders', [
            'id' => $folder_1->id,
        ]);

        $this->assertDatabaseMissing('folders', [
            'id' => $folder_2->id,
        ]);
    }

    /**
     * @test
     */
    public function it_delete_folder_with_their_content_within_softly()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder_root = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $folder_children = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $folder_root->id,
            ]);

        $file_1 = File::factory()
            ->create([
                'parent_id' => $folder_root->id,
                'user_id'   => $user->id,
            ]);

        $file_2 = File::factory()
            ->create([
                'parent_id' => $folder_children->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/remove', [
                'items' => [
                    [
                        'id'           => $folder_root->id,
                        'type'         => 'folder',
                        'force_delete' => false,
                    ],
                ],
            ])->assertStatus(200);

        collect([$file_1, $file_2])
            ->each(function ($file) {
                $this->assertSoftDeleted('files', [
                    'id' => $file->id,
                ]);
            });

        collect([$folder_root, $folder_children])
            ->each(function ($file) {
                $this->assertSoftDeleted('folders', [
                    'id' => $file->id,
                ]);
            });
    }

    /**
     * @test
     */
    public function it_delete_folder_with_their_content_within_hardly()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        Sanctum::actingAs($user);

        $folder_root = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $folder_children = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $folder_root->id,
            ]);

        collect([$folder_root, $folder_children])
            ->each(function ($folder, $index) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload/chunks', [
                    'name'            => $file->name,
                    'extension'       => 'pdf',
                    'chunk'           => $file,
                    'parent_id'       => $folder->id,
                    'is_last_chunk'   => 1,
                ])->assertStatus(201);
            });

        $uploaded_files = File::all();

        collect([0, 1])
            ->each(function ($index) use ($folder_root) {
                $this->postJson('/api/remove', [
                    'items' => [
                        [
                            'id'           => $folder_root->id,
                            'type'         => 'folder',
                            'force_delete' => $index,
                        ],
                    ],
                ])->assertStatus(200);
            });

        $uploaded_files
            ->each(function ($file, $index) use ($user) {
                $this->assertDatabaseMissing('files', [
                    'id' => $file->id,
                ]);

                Storage::disk('local')
                    ->assertMissing(
                        "files/$user->id/fake-file-$index.pdf"
                    );
            });

        collect([$folder_root, $folder_children])
            ->each(function ($id) {
                $this->assertDatabaseMissing('folders', [
                    'id' => $id,
                ]);
            });
    }
}
