<?php

namespace Tests\Feature\FileManager;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use App\Models\Zip;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

// TODO: pridat foldre do api skupiny

class FolderTest extends TestCase
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
    public function it_test_folder_factory()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $this->assertDatabaseHas('folders', [
            'id' => $folder->id
        ]);
    }

    /**
     * @test
     */
    public function it_create_new_folder()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/create-folder', [
            'name'      => 'New Folder',
            'parent_id' => null,
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'New Folder',
            ]);

        $this->assertDatabaseHas('folders', [
            'name' => 'New Folder'
        ]);
    }

    /**
     * @test
     */
    public function it_rename_folder()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->patchJson("/api/rename/{$folder->id}", [
            'name' => 'Renamed Folder',
            'type' => 'folder',
        ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed Folder',
            ]);

        $this->assertDatabaseHas('folders', [
            'name' => 'Renamed Folder'
        ]);
    }

    /**
     * @test
     */
    public function it_set_folder_emoji()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $emoji_fragment = [
            'category' => 'Smileys & Emotion (face-smiling)',
            'char'     => '😁',
            'name'     => 'beaming face with smiling eyes',
        ];

        $this->patchJson("/api/rename/{$folder->id}", [
            'name'  => 'Renamed Folder',
            'type'  => 'folder',
            'emoji' => $emoji_fragment
        ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'  => 'Renamed Folder',
                'emoji' => $emoji_fragment
            ]);

        $this->assertDatabaseHas('folders', [
            'color' => null,
            'emoji' => json_encode($emoji_fragment)
        ]);
    }

    /**
     * @test
     */
    public function it_set_folder_color()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->patchJson("/api/rename/{$folder->id}", [
            'name'  => 'Folder Name',
            'type'  => 'folder',
            'color' => '#AD6FFE'
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
    public function it_add_folder_to_favourites()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/folders/favourites", [
            'folders' => [
                $folder->id
            ],
        ])->assertStatus(204);

        $this->assertDatabaseHas('favourite_folder', [
            'user_id'   => $user->id,
            'folder_id' => $folder->id,
        ]);
    }

    /**
     * @test
     */
    public function it_remove_folder_from_favourites()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $user
            ->favouriteFolders()
            ->attach($folder->id);

        $this->deleteJson("/api/folders/favourites/$folder->id")
            ->assertStatus(204);

        $this->assertDatabaseMissing('favourite_folder', [
            'user_id'   => $user->id,
            'folder_id' => $folder->id,
        ]);
    }

    /**
     * @test
     */
    public function it_move_folder_to_another_folder()
    {
        $root = Folder::factory(Folder::class)
            ->create();

        $children = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/move", [
            'to_id' => $root->id,
            'items' => [
                [
                    'type' => 'folder',
                    'id'   => $children->id,
                ]
            ],
        ])->assertStatus(204);

        $this->assertEquals(
            $root->id, Folder::find($children->id)->parent_id
        );
    }

    /**
     * @test
     */
    public function it_delete_multiple_folder_softly()
    {
        $user = User::factory(User::class)
            ->create();

        $folder_1 = Folder::factory(Folder::class)
            ->create();

        $folder_2 = Folder::factory(Folder::class)
            ->create();

        $user->favouriteFolders()->attach($folder_1->id);
        $user->favouriteFolders()->attach($folder_2->id);

        Sanctum::actingAs($user);

        $this->postJson("/api/remove", [
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
        ])->assertStatus(204);

        collect([$folder_1, $folder_2])
            ->each(function ($folder) {

                $this->assertSoftDeleted('folders', [
                    'id' => $folder->id,
                ]);

                $this->assertDatabaseMissing('favourite_folder', [
                    'folder_id' => $folder->id,
                ]);
            });
    }

    /**
     * @test
     */
    public function it_delete_multiple_folder_hardly()
    {
        $user = User::factory(User::class)
            ->create();

        $folder_1 = Folder::factory(Folder::class)
            ->create();

        $folder_2 = Folder::factory(Folder::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/remove", [
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
        ])->assertStatus(204);

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
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder_root = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        $folder_children = Folder::factory(Folder::class)
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $folder_root->id,
            ]);

        $file_1 = File::factory(File::class)
            ->create([
                'folder_id' => $folder_root->id,
                'user_id'   => $user->id,
            ]);

        $file_2 = File::factory(File::class)
            ->create([
                'folder_id' => $folder_children->id,
                'user_id'   => $user->id,
            ]);

        $this->postJson("/api/remove", [
            'items' => [
                [
                    'id'           => $folder_root->id,
                    'type'         => 'folder',
                    'force_delete' => false,
                ],
            ],
        ])->assertStatus(204);

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
        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder_root = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        $folder_children = Folder::factory(Folder::class)
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $folder_root->id,
            ]);

        collect([$folder_root, $folder_children])
            ->each(function ($folder, $index) {

                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload', [
                    'filename'  => $file->name,
                    'file'      => $file,
                    'folder_id' => $folder->id,
                    'is_last'   => true,
                ])->assertStatus(201);
            });

        $uploaded_files = File::all();

        collect([0, 1])
            ->each(function ($index) use ($folder_root) {
                $this->postJson("/api/remove", [
                    'items' => [
                        [
                            'id'           => $folder_root->id,
                            'type'         => 'folder',
                            'force_delete' => $index,
                        ],
                    ],
                ])->assertStatus(204);
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

    /**
     * @test
     */
    public function it_zip_folder_with_content_within_and_download()
    {
        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        collect([0, 1])
            ->each(function ($index) use ($folder) {

                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload', [
                    'filename'  => $file->name,
                    'file'      => $file,
                    'folder_id' => $folder->id,
                    'is_last'   => true,
                ])->assertStatus(201);
            });

        $this->getJson("/api/zip/folder/$folder->id")
            ->assertStatus(201);

        $this->assertDatabaseHas('zips', [
            'user_id' => $user->id
        ]);

        Storage::disk('local')
            ->assertExists(
                'zip/' . Zip::first()->basename
            );
    }
}
