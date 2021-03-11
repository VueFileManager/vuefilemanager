<?php

namespace Tests\Feature\Share;

use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Models\User;
use App\Models\Zip;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Services\SetupService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class ShareEditorTest extends TestCase
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
    public function editor_create_new_folder_in_shared_folder()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $folder->user_id,
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
        $folder = Folder::factory(Folder::class)
            ->create();

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $folder->user_id,
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

    /**
     * @test
     */
    public function guest_zip_shared_multiple_files()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        collect([0, 1])
            ->each(function ($index) use ($folder, $user) {

                $file = UploadedFile::fake()
                    ->create(Str::random() . "-fake-file-$index.pdf", 1000, 'application/pdf');

                Storage::putFileAs("files/$user->id", $file, $file->name);

                File::factory(File::class)
                    ->create([
                        'filesize'  => $file->getSize(),
                        'folder_id' => $folder->id,
                        'user_id'   => $user->id,
                        'basename'  => $file->name,
                        'name'      => "fake-file-$index.pdf",
                    ]);
            });

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->postJson("/api/zip/files/public/$share->token", [
            'items' => File::all()->pluck('id')
        ])->assertStatus(201);

        $this->assertDatabaseHas('zips', [
            'user_id'      => $user->id,
            'shared_token' => $share->token,
        ]);

        Storage::assertExists("zip/" . Zip::first()->basename);
    }

    /**
     * @test
     */
    public function guest_try_zip_non_shared_file_with_already_shared_multiple_files()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        File::factory(File::class)
            ->create([
                'folder_id' => $folder->id,
                'user_id'   => $user->id,
            ]);

        File::factory(File::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->postJson("/api/zip/files/public/$share->token", [
            'items' => File::all()->pluck('id')
        ])->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_zip_shared_folder()
    {
        Storage::fake('local');

        $this->setup->create_directories();

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

        collect([0, 1])
            ->each(function ($index) use ($children, $user) {

                $file = UploadedFile::fake()
                    ->create(Str::random() . "-fake-file-$index.pdf", 1000, 'application/pdf');

                Storage::putFileAs("files/$user->id", $file, $file->name);

                File::factory(File::class)
                    ->create([
                        'filesize'  => $file->getSize(),
                        'folder_id' => $children->id,
                        'user_id'   => $user->id,
                        'basename'  => $file->name,
                        'name'      => "fake-file-$index.pdf",
                    ]);
            });

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $children->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->getJson("/api/zip/folder/$children->id/public/$share->token")
            ->assertStatus(201);

        $this->assertDatabaseHas('zips', [
            'user_id'      => $user->id,
            'shared_token' => $share->token,
        ]);

        Storage::assertExists("zip/" . Zip::first()->basename);
    }

    /**
     * @test
     */
    public function guest_try_zip_non_shared_folder()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->getJson("/api/zip/folder/$folder->id/public/$share->token")
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_get_folder_content()
    {
        $user = User::factory(User::class)
            ->create();

        $root = Folder::factory(Folder::class)
            ->create([
                'name'    => 'root',
                'user_id' => $user->id,
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $root->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $folder = Folder::factory(Folder::class)
            ->create([
                'parent_id'  => $root->id,
                'name'       => 'Documents',
                "user_scope" => "master",
                'user_id'    => $user->id,
            ]);

        $file = File::factory(File::class)
            ->create([
                'folder_id'  => $root->id,
                'name'       => 'Document',
                'basename'   => 'document.pdf',
                "mimetype"   => "application/pdf",
                "user_scope" => "master",
                "type"       => "file",
                'user_id'    => $user->id,
            ]);

        $this->getJson("/api/browse/folders/$root->id/public/$share->token")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    "id"            => $folder->id,
                    "user_id"       => $user->id,
                    "parent_id"     => $root->id,
                    "name"          => "Documents",
                    "color"         => null,
                    "emoji"         => null,
                    "user_scope"    => "master",
                    "deleted_at"    => null,
                    "created_at"    => $folder->created_at,
                    "updated_at"    => $folder->updated_at->toJson(),
                    "items"         => 0,
                    "trashed_items" => 0,
                    "type"          => "folder",
                ],
                [
                    "id"         => $file->id,
                    "user_id"    => $user->id,
                    "folder_id"  => $root->id,
                    "thumbnail"  => null,
                    "name"       => "Document",
                    "basename"   => "document.pdf",
                    "mimetype"   => "application/pdf",
                    "filesize"   => $file->filesize,
                    "type"       => "file",
                    "metadata"   => null,
                    "user_scope" => "master",
                    "deleted_at" => null,
                    "created_at" => $file->created_at,
                    "updated_at" => $file->updated_at->toJson(),
                    "file_url"   => "http://localhost/file/document.pdf/public/$share->token",
                ]
            ]);
    }

    /**
     * @test
     */
    public function guest_get_navigator_tree()
    {
        $user = User::factory(User::class)
            ->create();

        $folder_level_1 = Folder::factory(Folder::class)
            ->create([
                'name'       => 'level 1',
                'user_scope' => 'master',
                'user_id'    => $user->id,
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder_level_1->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $folder_level_2 = Folder::factory(Folder::class)
            ->create([
                'name'       => 'level 2',
                'parent_id'  => $folder_level_1->id,
                'user_scope' => 'master',
                'user_id'    => $user->id,
            ]);

        $folder_level_3 = Folder::factory(Folder::class)
            ->create([
                'name'       => 'level 3',
                'parent_id'  => $folder_level_2->id,
                'user_scope' => 'master',
                'user_id'    => $user->id,
            ]);

        $folder_level_2_sibling = Folder::factory(Folder::class)
            ->create([
                'name'       => 'level 2 Sibling',
                'parent_id'  => $folder_level_1->id,
                'user_scope' => 'master',
                'user_id'    => $user->id,
            ]);

        $this->getJson("/api/browse/navigation/public/$share->token")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    'id'       => $share->item_id,
                    "name"     => "Home",
                    "location" => "public",
                    "folders"  => [
                        [
                            "id"            => $folder_level_2->id,
                            "parent_id"     => $folder_level_1->id,
                            "name"          => "level 2",
                            "items"         => 1,
                            "trashed_items" => 1,
                            "type"          => "folder",
                            "folders"       => [
                                [
                                    "id"            => $folder_level_3->id,
                                    "parent_id"     => $folder_level_2->id,
                                    "name"          => "level 3",
                                    "items"         => 0,
                                    "trashed_items" => 0,
                                    "type"          => "folder",
                                    "folders"       => [],
                                ],
                            ],
                        ],
                        [
                            "id"            => $folder_level_2_sibling->id,
                            "parent_id"     => $folder_level_1->id,
                            "name"          => "level 2 Sibling",
                            "items"         => 0,
                            "trashed_items" => 0,
                            "type"          => "folder",
                            "folders"       => []
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function guest_search_file()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $folder->user_id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        $file = File::factory(File::class)
            ->create([
                'name'      => 'Document',
                'folder_id' => $folder->id,
                'user_id'   => $folder->user_id,
            ]);

        $this->getJson("/api/browse/search/public/$share->token?query=doc")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $file->id
            ]);
    }

    /**
     * @test
     */
    public function guest_try_search_non_shared_user_file()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $folder->user_id,
                'type'         => 'folder',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);

        File::factory(File::class)
            ->create([
                'name'    => 'Document',
                'user_id' => $folder->user_id,
            ]);

        $this->getJson("/api/browse/search/public/$share->token?query=doc")
            ->assertStatus(200)
            ->assertJsonFragment([]);
    }

    /**
     * @test
     */
    public function guest_get_file_detail()
    {
        $file = File::factory(File::class)
            ->create([
                'name' => 'Document',
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $file->user_id,
                'type'         => 'file',
                'is_protected' => false,
                'permission'   => 'editor',
            ]);


        $this->getJson("/api/browse/files/$share->token/public")
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Document'
            ]);
    }
}
