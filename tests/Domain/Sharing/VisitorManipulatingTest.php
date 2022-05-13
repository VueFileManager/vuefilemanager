<?php
namespace Tests\Domain\Sharing;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;

class VisitorManipulatingTest extends TestCase
{
    /**
     * @test
     */
    public function editor_rename_shared_file()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory()
                    ->hasSettings()
                    ->create();

                $folder = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $file = File::factory()
                    ->create([
                        'parent_id' => $folder->id,
                        'user_id'   => $user->id,
                    ]);

                $share = Share::factory()
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
                        ->patch("/api/sharing/rename/{$file->id}/$share->token", [
                            'name' => 'Renamed Item',
                            'type' => 'file',
                        ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Renamed Item',
                        ]);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->patchJson("/api/sharing/rename/{$file->id}/$share->token", [
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
                $user = User::factory()
                    ->hasSettings()
                    ->create();

                $root = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $children = Folder::factory()
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $root->id,
                    ]);

                $share = Share::factory()
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
                        ->patch("/api/sharing/rename/{$children->id}/$share->token", [
                            'name' => 'Renamed Folder',
                            'type' => 'folder',
                        ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Renamed Folder',
                        ]);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->patchJson("/api/sharing/rename/{$children->id}/$share->token", [
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
                    'id'   => $children->id,
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
                $user = User::factory()
                    ->hasSettings()
                    ->create();

                $folder = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory()
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
                        ->post("/api/sharing/create-folder/$share->token", [
                            'name'      => 'Awesome New Folder',
                            'parent_id' => $folder->id,
                        ])
                        ->assertStatus(201)
                        ->assertJsonFragment([
                            'name' => 'Awesome New Folder',
                        ]);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->postJson("/api/sharing/create-folder/$share->token", [
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
                $user = User::factory()
                    ->create();

                $folder = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory()
                    ->create([
                        'item_id'      => $folder->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                $files = File::factory()
                    ->count(2)
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $folder->id,
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
                        ->post("/api/sharing/remove/$share->token", $payload)
                        ->assertStatus(204);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->postJson("/api/sharing/remove/$share->token", $payload)
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
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory()
                    ->hasSettings()
                    ->create();

                $folder = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory()
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
                        ->post("/api/sharing/upload/chunks/$share->token", [
                            'name'            => $file->name,
                            'extension'       => 'pdf',
                            'chunk'           => $file,
                            'parent_id'       => $folder->id,
                            'is_last_chunk'   => 1,
                        ])->assertStatus(201);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->postJson("/api/sharing/upload/chunks/$share->token", [
                        'name'            => $file->name,
                        'extension'       => 'pdf',
                        'chunk'           => $file,
                        'parent_id'       => $folder->id,
                        'is_last_chunk'   => 1,
                    ])->assertStatus(201);
                }

                $file = File::all()->last();

                Storage::disk('local')
                    ->assertExists(
                        "files/$user->id/$file->basename"
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
                $user = User::factory()
                    ->create();

                $root = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $children = Folder::factory()
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $root->id,
                    ]);

                $file = File::factory()
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $root->id,
                    ]);

                $share = Share::factory()
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
                        ->post("/api/sharing/move/$share->token", $payload)
                        ->assertStatus(200);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->postJson("/api/sharing/move/$share->token", $payload)
                        ->assertStatus(200);
                }

                $this->assertDatabaseHas('files', [
                    'id'        => $file->id,
                    'parent_id' => $children->id,
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
                $user = User::factory()
                    ->create();

                $root = Folder::factory()
                    ->create([
                        'user_id' => $user->id,
                    ]);

                $brother = Folder::factory()
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $root->id,
                    ]);

                $sister = Folder::factory()
                    ->create([
                        'user_id'   => $user->id,
                        'parent_id' => $root->id,
                    ]);

                $share = Share::factory()
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
                        ->post("/api/sharing/move/$share->token", $payload)
                        ->assertStatus(200);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->postJson("/api/sharing/move/$share->token", $payload)
                        ->assertStatus(200);
                }

                $this->assertDatabaseHas('folders', [
                    'id'        => $sister->id,
                    'parent_id' => $brother->id,
                ]);
            });
    }
}
