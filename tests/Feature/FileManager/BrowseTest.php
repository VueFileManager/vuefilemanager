<?php

namespace Tests\Feature\FileManager;

use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BrowseTest extends TestCase
{
    use DatabaseMigrations, Queueable;

    /**
     * @test
     */
    public function it_get_navigator_tree()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder_level_1 = Folder::factory(Folder::class)
            ->create([
                'name'       => 'level 1',
                'user_scope' => 'master',
                'user_id'    => $user->id,
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

        $this->getJson("/api/browse/navigation")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    "name"     => "Home",
                    "location" => "base",
                    "folders"  => [
                        [
                            "id"            => $folder_level_1->id,
                            "parent_id"     => null,
                            "name"          => "level 1",
                            "items"         => 2,
                            "trashed_items" => 2,
                            "type"          => "folder",
                            "folders"       => [
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
                                            "user_id"       => $user->id,
                                            "parent_id"     => $folder_level_2->id,
                                            "name"          => "level 3",
                                            "color"         => null,
                                            "emoji"         => null,
                                            "user_scope"    => "master",
                                            "deleted_at"    => null,
                                            "created_at"    => $folder_level_3->created_at,
                                            "updated_at"    => $folder_level_3->updated_at->toJson(),
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
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_get_folder_content()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $root = Folder::factory(Folder::class)
            ->create([
                'name'    => 'root',
                'user_id' => $user->id,
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

        $this->getJson("/api/browse/folders/$root->id")
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
                    "parent"        => [
                        "id"            => $root->id,
                        "name"          => "root",
                        "items"         => 2,
                        "trashed_items" => 2,
                        "type"          => "folder",
                    ],
                    "shared"        => null,
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
                    "file_url"   => "http://localhost/file/document.pdf",
                    "parent"     => [
                        "id"            => $root->id,
                        "name"          => "root",
                        "items"         => 2,
                        "trashed_items" => 2,
                        "type"          => "folder",
                    ],
                    "shared"     => null,
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_get_recent_files()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $root = Folder::factory(Folder::class)
            ->create([
                'name'    => 'root',
                'user_id' => $user->id,
            ]);

        $file_1 = File::factory(File::class)
            ->create([
                'folder_id'  => $root->id,
                'name'       => 'Document 1',
                'basename'   => 'document-1.pdf',
                "mimetype"   => "application/pdf",
                "user_scope" => "master",
                "type"       => "file",
                'user_id'    => $user->id,
                'created_at' => Carbon::now(),
            ]);

        $this->travel(5)->minutes();

        $file_2 = File::factory(File::class)
            ->create([
                'folder_id'  => $root->id,
                'name'       => 'Document 2',
                'basename'   => 'document-2.pdf',
                "mimetype"   => "application/pdf",
                "user_scope" => "master",
                "type"       => "file",
                'user_id'    => $user->id,
                'created_at' => Carbon::now(),
            ]);

        $this->getJson("/api/browse/latest")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    "id"         => $file_2->id,
                    "user_id"    => $user->id,
                    "folder_id"  => $root->id,
                    "thumbnail"  => null,
                    "name"       => "Document 2",
                    "basename"   => "document-2.pdf",
                    "mimetype"   => "application/pdf",
                    "filesize"   => $file_2->filesize,
                    "type"       => "file",
                    "metadata"   => null,
                    "user_scope" => "master",
                    "deleted_at" => null,
                    "created_at" => $file_2->created_at,
                    "updated_at" => $file_2->updated_at->toJson(),
                    "file_url"   => "http://localhost/file/document-2.pdf",
                    "parent"     => [
                        "id"            => $root->id,
                        "name"          => "root",
                        "items"         => 2,
                        "trashed_items" => 2,
                        "type"          => "folder",
                    ],
                ],
                [
                    "id"         => $file_1->id,
                    "user_id"    => $user->id,
                    "folder_id"  => $root->id,
                    "thumbnail"  => null,
                    "name"       => "Document 1",
                    "basename"   => "document-1.pdf",
                    "mimetype"   => "application/pdf",
                    "filesize"   => $file_1->filesize,
                    "type"       => "file",
                    "metadata"   => null,
                    "user_scope" => "master",
                    "deleted_at" => null,
                    "created_at" => $file_1->created_at,
                    "updated_at" => $file_1->updated_at->toJson(),
                    "file_url"   => "http://localhost/file/document-1.pdf",
                    "parent"     => [
                        "id"            => $root->id,
                        "name"          => "root",
                        "items"         => 2,
                        "trashed_items" => 2,
                        "type"          => "folder",
                    ],
                ],
            ]);
    }

    /**
     * @test
     */
    public function it_get_participant_uploads()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $file = File::factory(File::class)
            ->create([
                "user_scope" => "editor",
                "type"       => "file",
                'user_id'    => $user->id,
            ]);

        $this->getJson("/api/browse/participants")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $file->id
            ]);
    }

    /**
     * @test
     */
    public function it_get_trash_root()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory(Folder::class)
            ->create([
                'parent_id'  => null,
                'name'       => 'root',
                'user_id'    => $user->id,
                "user_scope" => "master",
                'deleted_at' => Carbon::now(),
            ]);

        $file = File::factory(File::class)
            ->create([
                'folder_id'  => null,
                'name'       => 'Document',
                'basename'   => 'document.pdf',
                "mimetype"   => "application/pdf",
                "user_scope" => "master",
                "type"       => "file",
                'user_id'    => $user->id,
                'deleted_at' => Carbon::now(),
            ]);

        File::factory(File::class)
            ->create([
                'folder_id'  => $folder->id,
                'user_id'    => $user->id,
                'deleted_at' => Carbon::now(),
            ]);

        $this->getJson("/api/browse/trash")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    "id"            => $folder->id,
                    "user_id"       => $user->id,
                    "parent_id"     => null,
                    "name"          => "root",
                    "color"         => null,
                    "emoji"         => null,
                    "user_scope"    => "master",
                    "deleted_at"    => $folder->deleted_at,
                    "created_at"    => $folder->created_at,
                    "updated_at"    => $folder->updated_at->toJson(),
                    "items"         => 0,
                    "trashed_items" => 1,
                    "type"          => "folder",
                    "parent"        => null,
                ],
                [
                    "id"         => $file->id,
                    "user_id"    => $user->id,
                    "folder_id"  => null,
                    "thumbnail"  => null,
                    "name"       => "Document",
                    "basename"   => "document.pdf",
                    "mimetype"   => "application/pdf",
                    "filesize"   => $file->filesize,
                    "type"       => "file",
                    "metadata"   => null,
                    "user_scope" => "master",
                    "deleted_at" => $file->deleted_at,
                    "created_at" => $file->created_at,
                    "updated_at" => $file->updated_at->toJson(),
                    "file_url"   => "http://localhost/file/document.pdf",
                    "parent"     => null
                ],
            ]);
    }

    /**
     * @test
     */
    public function it_get_shared_items()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $file = File::factory(File::class)
            ->create([
                'user_id' => $user->id,
            ]);

        collect([$folder, $file])
            ->each(function ($item) use ($user) {
                Share::factory(Share::class)
                    ->create([
                        "type"    => $item->type === 'folder' ? 'folder' : 'file',
                        "item_id" => $item->id,
                        'user_id' => $user->id,
                    ]);
            });

        collect([$folder, $file])
            ->each(function ($item) use ($user) {
                $this->getJson("/api/browse/shared")
                    ->assertStatus(200)
                    ->assertJsonFragment([
                        'id' => $item->id
                    ]);
            });
    }

    /**
     * @test
     */
    public function it_get_searched_file()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $file = File::factory(File::class)
            ->create([
                'name'       => 'Document',
                'user_id'    => $user->id,
            ]);

        $this->getJson("/api/browse/search?query=doc")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $file->id
            ]);
    }

    /**
     * @test
     */
    public function it_get_searched_folder()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory(Folder::class)
            ->create([
                'name'       => 'Documents',
                'user_id'    => $user->id,
            ]);

        $this->getJson("/api/browse/search?query=doc")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $folder->id
            ]);
    }
}
