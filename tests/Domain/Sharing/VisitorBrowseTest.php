<?php

namespace Tests\Feature\Share;

use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Models\User;
use App\Models\Zip;
use App\Services\SetupService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Storage;
use Tests\TestCase;

class VisitorBrowseTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

    /**
     * @test
     */
    public function it_get_share_record()
    {
        $share = Share::factory(Share::class)
            ->create([
                'is_protected' => 0,
            ]);

        $this->get("/api/browse/share/$share->token")
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id'         => $share->id,
                    'type'       => 'shares',
                    'attributes' => [
                        'permission'   => $share->permission,
                        'is_protected' => false,
                        'item_id'      => $share->item_id,
                        'expire_in'    => $share->expire_in,
                        'token'        => $share->token,
                        'link'         => $share->link,
                        'type'         => $share->type,
                        'created_at'   => $share->created_at->toJson(),
                        'updated_at'   => $share->updated_at->toJson(),
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_get_share_page()
    {
        $share = Share::factory(Share::class)
            ->create([
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->get("/share/$share->token")
            ->assertViewIs('index')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_try_to_get_deleted_share_record()
    {
        $this->get("/api/browse/share/19ZMPNiass4ZqWwQ")
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function it_try_to_get_deleted_share_page()
    {
        $this->get('/share/19ZMPNiass4ZqWwQ')
            ->assertNotFound(404);
    }

    /**
     * @test
     */
    public function it_authenticate_protected_file_with_correct_password()
    {
        $file = File::factory(File::class)
            ->create();

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $file->user_id,
                'type'         => 'file',
                'is_protected' => true,
                'password'     => bcrypt('secret'),
            ]);

        $this->postJson("/api/browse/authenticate/$share->token", [
            'password' => 'secret'
        ])
            ->assertStatus(200)
            ->assertCookie('share_session', json_encode([
                'token'         => $share->token,
                'authenticated' => true,
            ]), false);
    }

    /**
     * @test
     */
    public function it_authenticate_protected_file_with_incorrect_password()
    {
        $file = File::factory(File::class)
            ->create();

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $file->user_id,
                'type'         => 'file',
                'is_protected' => true,
                'password'     => bcrypt('secret'),
            ]);

        $this->postJson("/api/browse/authenticate/$share->token", [
            'password' => 'bad-password'
        ])
            ->assertStatus(401)
            ->assertCookieMissing('share_session');
    }

    /**
     * @test
     */
    public function visitor_zip_shared_multiple_files()
    {
        $this->setup->create_directories();

        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->post("/api/zip/files/$share->token", [
                            'items' => File::all()->pluck('id')
                        ])->assertStatus(201);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->postJson("/api/zip/files/$share->token", [
                        'items' => File::all()->pluck('id')
                    ])->assertStatus(201);
                }

                $this->assertDatabaseHas('zips', [
                    'user_id'      => $user->id,
                    'shared_token' => $share->token,
                ]);

                Storage::assertExists("zip/" . Zip::first()->basename);
            });
    }

    /**
     * @test
     */
    public function visitor_try_zip_not_shared_file_with_already_shared_multiple_files()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->post("/api/zip/files/$share->token", [
                            'items' => File::all()->pluck('id')
                        ])->assertStatus(403);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->postJson("/api/zip/files/$share->token", [
                        'items' => File::all()->pluck('id')
                    ])->assertStatus(403);
                }
            });
    }

    /**
     * @test
     */
    public function visitor_zip_shared_folder()
    {
        $this->setup->create_directories();

        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/zip/folder/$children->id/$share->token")
                        ->assertStatus(201);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->getJson("/api/zip/folder/$children->id/$share->token")
                        ->assertStatus(201);
                }

                $this->assertDatabaseHas('zips', [
                    'user_id'      => $user->id,
                    'shared_token' => $share->token,
                ]);

                Zip::all()
                    ->each(function ($zip) {
                        Storage::assertExists("zip/$zip->basename");
                    });
            });
    }

    /**
     * @test
     */
    public function visitor_try_zip_not_shared_folder()
    {
        $this->setup->create_directories();

        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/zip/folder/$folder->id/$share->token")
                        ->assertStatus(403);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->getJson("/api/zip/folder/$folder->id/$share->token")
                        ->assertStatus(403);
                }
            });
    }

    /**
     * @test
     */
    public function visitor_get_folder_content()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                $folder = Folder::factory(Folder::class)
                    ->create([
                        'parent_id' => $root->id,
                        'name'      => 'Documents',
                        "author"    => "user",
                        'user_id'   => $user->id,
                    ]);

                $file = File::factory(File::class)
                    ->create([
                        'folder_id' => $root->id,
                        'name'      => 'Document',
                        'basename'  => 'document.pdf',
                        "mimetype"  => "application/pdf",
                        "author"    => "user",
                        "type"      => "file",
                        'user_id'   => $user->id,
                    ]);

                $json = [
                    [
                        "id"            => $folder->id,
                        "user_id"       => $user->id,
                        "parent_id"     => $root->id,
                        "name"          => "Documents",
                        "color"         => null,
                        "emoji"         => null,
                        "author"        => "user",
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
                        "author"     => "user",
                        "deleted_at" => null,
                        "created_at" => $file->created_at,
                        "updated_at" => $file->updated_at->toJson(),
                        "file_url"   => "http://localhost/file/document.pdf/$share->token",
                    ]
                ];

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/browse/folders/$root->id/$share->token")
                        ->assertStatus(200)
                        ->assertExactJson($json);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->getJson("/api/browse/folders/$root->id/$share->token")
                        ->assertStatus(200)
                        ->assertExactJson($json);
                }
            });
    }

    /**
     * @test
     */
    public function visitor_get_navigator_tree()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

                $user = User::factory(User::class)
                    ->create();

                $folder_level_1 = Folder::factory(Folder::class)
                    ->create([
                        'name'    => 'level 1',
                        'author'  => 'user',
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $folder_level_1->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'permission'   => 'editor',
                        'is_protected' => $is_protected,
                        'password'     => bcrypt('secret'),
                    ]);

                $folder_level_2 = Folder::factory(Folder::class)
                    ->create([
                        'name'      => 'level 2',
                        'parent_id' => $folder_level_1->id,
                        'author'    => 'user',
                        'user_id'   => $user->id,
                    ]);

                $folder_level_3 = Folder::factory(Folder::class)
                    ->create([
                        'name'      => 'level 3',
                        'parent_id' => $folder_level_2->id,
                        'author'    => 'user',
                        'user_id'   => $user->id,
                    ]);

                $folder_level_2_sibling = Folder::factory(Folder::class)
                    ->create([
                        'name'      => 'level 2 Sibling',
                        'parent_id' => $folder_level_1->id,
                        'author'    => 'user',
                        'user_id'   => $user->id,
                    ]);

                $tree = [
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
                ];

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/browse/navigation/$share->token")
                        ->assertStatus(200)
                        ->assertExactJson($tree);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->getJson("/api/browse/navigation/$share->token")
                        ->assertStatus(200)
                        ->assertExactJson($tree);
                }
            });
    }

    /**
     * @test
     */
    public function visitor_search_file()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

                $folder = Folder::factory(Folder::class)
                    ->create();

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $folder->id,
                        'user_id'      => $folder->user_id,
                        'type'         => 'folder',
                        'permission'   => 'editor',
                        'is_protected' => $is_protected,
                        'password'     => bcrypt('secret'),
                    ]);

                $file = File::factory(File::class)
                    ->create([
                        'name'      => 'Document',
                        'folder_id' => $folder->id,
                        'user_id'   => $folder->user_id,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this->withUnencryptedCookies($cookie)
                        ->get("/api/browse/search/$share->token?query=doc")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'id' => $file->id
                        ]);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->getJson("/api/browse/search/$share->token?query=doc")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'id' => $file->id
                        ]);
                }
            });
    }

    /**
     * @test
     */
    public function visitor_try_search_not_shared_user_file()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

                $folder = Folder::factory(Folder::class)
                    ->create();

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $folder->id,
                        'user_id'      => $folder->user_id,
                        'type'         => 'folder',
                        'permission'   => 'editor',
                        'is_protected' => $is_protected,
                        'password'     => bcrypt('secret'),
                    ]);

                File::factory(File::class)
                    ->create([
                        'name'    => 'Document',
                        'user_id' => $folder->user_id,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this->withUnencryptedCookies($cookie)
                        ->get("/api/browse/search/$share->token?query=doc")
                        ->assertStatus(200)
                        ->assertJsonFragment([]);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->getJson("/api/browse/search/$share->token?query=doc")
                        ->assertStatus(200)
                        ->assertJsonFragment([]);
                }
            });
    }

    /**
     * @test
     */
    public function visitor_get_file_detail()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

                $file = File::factory(File::class)
                    ->create([
                        'name' => 'Document',
                    ]);

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $file->id,
                        'user_id'      => $file->user_id,
                        'type'         => 'file',
                        'permission'   => 'editor',
                        'is_protected' => $is_protected,
                        'password'     => bcrypt('secret'),
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this->withUnencryptedCookies($cookie)
                        ->get("/api/browse/file/$share->token")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'name' => 'Document'
                        ]);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->getJson("/api/browse/file/$share->token")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'name' => 'Document'
                        ]);
                }
            });
    }
}
