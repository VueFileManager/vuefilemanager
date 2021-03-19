<?php

namespace Tests\Feature\Share;

use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Models\User;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VisitorManipulatingTest extends TestCase
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
    public function editor_rename_shared_file()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        $file = File::factory(File::class)
            ->create([
                'folder_id' => $folder->id
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $this->patchJson("/api/editor/rename/{$file->id}/public/$share->token", [
            'name' => 'Renamed Item',
            'type' => 'file',
        ])
            ->assertStatus(201)
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
    public function editor_rename_shared_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $root = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        $children = Folder::factory(Folder::class)
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $root->id
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $root->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $this->patchJson("/api/editor/rename/{$children->id}/public/$share->token", [
            'name' => 'Renamed Folder',
            'type' => 'folder',
        ])
            ->assertStatus(201)
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
    public function editor_create_new_folder_in_shared_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $this->postJson("/api/editor/create-folder/public/$share->token", [
            'name'      => 'Awesome New Folder',
            'parent_id' => $folder->id,
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Awesome New Folder',
            ]);

        $this->assertDatabaseHas('folders', [
            'name'       => 'Awesome New Folder',
            'parent_id'  => $folder->id,
            'user_scope' => 'editor',
        ]);
    }

    /**
     * @test
     */
    public function editor_delete_multiple_files_in_shared_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $files = File::factory(File::class)
            ->count(2)
            ->create([
                'folder_id' => $folder->id
            ]);

        $this->postJson("/api/editor/remove/public/$share->token", [
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
    public function editor_upload_file_into_shared_folder()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'user_scope' => 'master',
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 1000, 'application/pdf');

        $this->postJson("/api/editor/upload/public/$share->token", [
            'file'      => $file,
            'folder_id' => $folder->id,
            'is_last'   => true,
        ])->assertStatus(201);

        $this->assertDatabaseHas('traffic', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('files', [
            'user_scope' => 'editor',
        ]);

        Storage::disk('local')
            ->assertExists(
                "files/$user->id/fake-file.pdf"
            );
    }

    /**
     * @test
     */
    public function editor_move_file_to_another_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $root = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        $children = Folder::factory(Folder::class)
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $root->id,
            ]);

        $file = File::factory(File::class)
            ->create([
                'user_id'   => $user->id,
                'folder_id' => $root->id
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $root->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $this->postJson("/api/editor/move/public/$share->token", [
            'to_id' => $children->id,
            'items' => [
                [
                    'type' => 'file',
                    'id'   => $file->id,
                ]
            ],
        ])->assertStatus(204);

        $this->assertDatabaseHas('files', [
            'id'        => $file->id,
            'folder_id' => $children->id,
        ]);
    }

    /**
     * @test
     */
    public function editor_move_folder_to_another_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $root = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        $brother = Folder::factory(Folder::class)
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $root->id,
            ]);

        $sister = Folder::factory(Folder::class)
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $root->id,
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $root->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $this->postJson("/api/editor/move/public/$share->token", [
            'to_id' => $brother->id,
            'items' => [
                [
                    'type' => 'folder',
                    'id'   => $sister->id,
                ]
            ],
        ])->assertStatus(204);

        $this->assertDatabaseHas('folders', [
            'id'        => $sister->id,
            'parent_id' => $brother->id,
        ]);
    }
}
