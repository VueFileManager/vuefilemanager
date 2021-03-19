<?php

namespace Tests\Feature\Share;

use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Models\User;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Storage;
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
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->patch("/api/editor/rename/{$file->id}/$share->token", [
                            'name' => 'Renamed Item',
                            'type' => 'file',
                        ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Renamed Item',
                        ]);
                }

                // Check public shared item
                if (!$is_protected) {
                    $this->patchJson("/api/editor/rename/{$file->id}/$share->token", [
                        'name' => 'Renamed Item',
                        'type' => 'file',
                    ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Renamed Item',
                        ]);
                }

                $this->assertDatabaseHas('files', [
                    'name' => 'Renamed Item',
                    'id'   => $file->id,
                ]);
            });
    }

    /**
     * @test
     */
    public function editor_rename_shared_folder()
    {
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

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $root->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->patch("/api/editor/rename/{$children->id}/$share->token", [
                            'name' => 'Renamed Folder',
                            'type' => 'folder',
                        ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Renamed Folder',
                        ]);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->patchJson("/api/editor/rename/{$children->id}/$share->token", [
                        'name' => 'Renamed Folder',
                        'type' => 'folder',
                    ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Renamed Folder',
                        ]);
                }

                $this->assertDatabaseHas('folders', [
                    'name' => 'Renamed Folder',
                    'id'   => $children->id
                ]);
            });
    }

    /**
     * @test
     */
    public function editor_create_new_folder_in_shared_folder()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->post("/api/editor/create-folder/$share->token", [
                            'name'      => 'Awesome New Folder',
                            'parent_id' => $folder->id,
                        ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Awesome New Folder',
                        ]);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->postJson("/api/editor/create-folder/$share->token", [
                        'name'      => 'Awesome New Folder',
                        'parent_id' => $folder->id,
                    ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Awesome New Folder',
                        ]);
                }

                $this->assertDatabaseHas('folders', [
                    'name'      => 'Awesome New Folder',
                    'parent_id' => $folder->id,
                    'author'    => 'visitor',
                ]);
            });
    }

    /**
     * @test
     */
    public function editor_delete_multiple_files_in_shared_folder()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                $files = File::factory(File::class)
                    ->count(2)
                    ->create([
                        'folder_id' => $folder->id
                    ]);

                $payload = [
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
                ];

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->post("/api/editor/remove/$share->token", $payload)
                        ->assertStatus(204);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->postJson("/api/editor/remove/$share->token", $payload)
                        ->assertStatus(204);
                }

                $files
                    ->each(function ($file) {
                        $this->assertSoftDeleted('files', [
                            'id' => $file->id,
                        ]);
                    });
            });
    }

    /**
     * @test
     */
    public function editor_upload_file_into_shared_folder()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

                $user = User::factory(User::class)
                    ->create();

                $folder = Folder::factory(Folder::class)
                    ->create([
                        'user_id' => $user->id,
                        'author'  => 'user',
                    ]);

                $share = Share::factory(Share::class)
                    ->create([
                        'item_id'      => $folder->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                $file = UploadedFile::fake()
                    ->create('fake-file.pdf', 1000, 'application/pdf');

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->post("/api/editor/upload/$share->token", [
                            'file'      => $file,
                            'folder_id' => $folder->id,
                            'is_last'   => true,
                        ])->assertStatus(201);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->postJson("/api/editor/upload/$share->token", [
                        'file'      => $file,
                        'folder_id' => $folder->id,
                        'is_last'   => true,
                    ])->assertStatus(201);
                }

                $this->assertDatabaseHas('traffic', [
                    'user_id' => $user->id,
                ]);

                $this->assertDatabaseHas('files', [
                    'author' => 'visitor',
                ]);

                Storage::disk('local')
                    ->assertExists(
                        "files/$user->id/fake-file.pdf"
                    );
            });
    }

    /**
     * @test
     */
    public function editor_move_file_to_another_folder()
    {
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
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                $payload = [
                    'to_id' => $children->id,
                    'items' => [
                        [
                            'type' => 'file',
                            'id'   => $file->id,
                        ]
                    ],
                ];

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->post("/api/editor/move/$share->token", $payload)
                        ->assertStatus(204);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->postJson("/api/editor/move/$share->token", $payload)
                        ->assertStatus(204);
                }

                $this->assertDatabaseHas('files', [
                    'id'        => $file->id,
                    'folder_id' => $children->id,
                ]);
            });
    }

    /**
     * @test
     */
    public function editor_move_folder_to_another_folder()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {

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
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                $payload = [
                    'to_id' => $brother->id,
                    'items' => [
                        [
                            'type' => 'folder',
                            'id'   => $sister->id,
                        ]
                    ],
                ];

                // Check shared item protected by password
                if ($is_protected) {

                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->post("/api/editor/move/$share->token", $payload)
                        ->assertStatus(204);
                }

                // Check public shared item
                if (!$is_protected) {

                    $this->postJson("/api/editor/move/$share->token", $payload)
                        ->assertStatus(204);
                }

                $this->assertDatabaseHas('folders', [
                    'id'        => $sister->id,
                    'parent_id' => $brother->id,
                ]);
            });
    }
}
