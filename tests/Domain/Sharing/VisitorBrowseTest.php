<?php
namespace Tests\Domain\Sharing;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;

class VisitorBrowseTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_share_record()
    {
        $share = Share::factory()
            ->create([
                'is_protected' => 0,
            ]);

        $this->get("/api/sharing/$share->token")
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id'         => $share->id,
                    'type'       => 'shared',
                    'attributes' => [
                        'permission' => $share->permission,
                        'protected'  => false,
                        'item_id'    => $share->item_id,
                        'expire_in'  => $share->expire_in,
                        'token'      => $share->token,
                        'link'       => $share->link,
                        'type'       => $share->type,
                    ],
                ],
            ]);
    }

    /**
     * @test
     */
    public function it_get_share_page()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $share = Share::factory()
            ->create([
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->get("/share/$share->token")
            ->assertRedirect("/share/$share->token/files/$share->item_id");
    }

    /**
     * @test
     */
    public function it_try_to_get_deleted_share_record()
    {
        $this->get('/api/sharing/19ZMPNiass4ZqWwQ')
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function it_try_to_get_deleted_share_page()
    {
        $this->get('/share/19ZMPNiass4ZqWwQ')
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function it_authenticate_protected_file_with_correct_password()
    {
        $file = File::factory()
            ->create();

        $share = Share::factory()
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $file->user_id,
                'type'         => 'file',
                'is_protected' => true,
                'password'     => bcrypt('secret'),
            ]);

        $this->postJson("/api/sharing/authenticate/$share->token", [
            'password' => 'secret',
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
        $file = File::factory()
            ->create();

        $share = Share::factory()
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $file->user_id,
                'type'         => 'file',
                'is_protected' => true,
                'password'     => bcrypt('secret'),
            ]);

        $this->postJson("/api/sharing/authenticate/$share->token", [
            'password' => 'bad-password',
        ])
            ->assertStatus(401)
            ->assertCookieMissing('share_session');
    }

    /**
     * @test
     */
    public function visitor_get_folder_content()
    {
        // check private or public share record
        collect([true, false])
            ->each(function ($is_protected) {
                $user = User::factory()
                    ->hasSettings()
                    ->create();

                $root = Folder::factory()
                    ->create([
                        'name'    => 'root',
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory()
                    ->create([
                        'item_id'      => $root->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'is_protected' => $is_protected,
                        'permission'   => 'editor',
                    ]);

                $folder = Folder::factory()
                    ->create([
                        'parent_id' => $root->id,
                        'name'      => 'Documents',
                        'user_id'   => $user->id,
                    ]);

                $file = File::factory()
                    ->create([
                        'parent_id' => $root->id,
                        'name'      => 'Document',
                        'basename'  => 'document.pdf',
                        'mimetype'  => 'application/pdf',
                        'type'      => 'file',
                        'user_id'   => $user->id,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {
                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this
                        ->withUnencryptedCookies($cookie)
                        ->get("/api/sharing/folders/$root->id/$share->token")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'id' => $file->id,
                        ])
                        ->assertJsonFragment([
                            'id' => $folder->id,
                        ]);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->getJson("/api/sharing/folders/$root->id/$share->token")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'id' => $file->id,
                        ])
                        ->assertJsonFragment([
                            'id' => $folder->id,
                        ]);
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
                $user = User::factory()
                    ->create();

                $folder_level_1 = Folder::factory()
                    ->create([
                        'name'    => 'level 1',
                        'author'  => 'user',
                        'user_id' => $user->id,
                    ]);

                $share = Share::factory()
                    ->create([
                        'item_id'      => $folder_level_1->id,
                        'user_id'      => $user->id,
                        'type'         => 'folder',
                        'permission'   => 'editor',
                        'is_protected' => $is_protected,
                        'password'     => bcrypt('secret'),
                    ]);

                $folder_level_2 = Folder::factory()
                    ->create([
                        'name'      => 'level 2',
                        'parent_id' => $folder_level_1->id,
                        'user_id'   => $user->id,
                    ]);

                $folder_level_3 = Folder::factory()
                    ->create([
                        'name'      => 'level 3',
                        'parent_id' => $folder_level_2->id,
                        'user_id'   => $user->id,
                    ]);

                $folder_level_2_sibling = Folder::factory()
                    ->create([
                        'name'      => 'level 2 Sibling',
                        'parent_id' => $folder_level_1->id,
                        'user_id'   => $user->id,
                    ]);

                $tree = [
                    [
                        'name'      => 'Home',
                        'location'  => 'public',
                        'isMovable' => true,
                        'isOpen'    => true,
                        'folders'   => [
                            [
                                'id'            => $folder_level_2->id,
                                'parent_id'     => $folder_level_1->id,
                                'name'          => 'level 2',
                                'items'         => 1,
                                'trashed_items' => 1,
                                'folders'       => [
                                    [
                                        'id'            => $folder_level_3->id,
                                        'parent_id'     => $folder_level_2->id,
                                        'name'          => 'level 3',
                                        'items'         => 0,
                                        'trashed_items' => 0,
                                        'folders'       => [],
                                    ],
                                ],
                            ],
                            [
                                'id'            => $folder_level_2_sibling->id,
                                'parent_id'     => $folder_level_1->id,
                                'name'          => 'level 2 Sibling',
                                'items'         => 0,
                                'trashed_items' => 0,
                                'folders'       => [],
                            ],
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
                        ->get("/api/sharing/navigation/$share->token")
                        ->assertStatus(200)
                        ->assertExactJson($tree);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->getJson("/api/sharing/navigation/$share->token")
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
                $folder = Folder::factory()
                    ->create();

                $share = Share::factory()
                    ->create([
                        'item_id'      => $folder->id,
                        'user_id'      => $folder->user_id,
                        'type'         => 'folder',
                        'permission'   => 'editor',
                        'is_protected' => $is_protected,
                        'password'     => bcrypt('secret'),
                    ]);

                $file = File::factory()
                    ->create([
                        'name'      => 'Document',
                        'parent_id' => $folder->id,
                        'user_id'   => $folder->user_id,
                    ]);

                // Check shared item protected by password
                if ($is_protected) {
                    $cookie = ['share_session' => json_encode([
                        'token'         => $share->token,
                        'authenticated' => true,
                    ])];

                    $this->withUnencryptedCookies($cookie)
                        ->get("/api/sharing/search/$share->token?query=doc")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'id' => $file->id,
                        ]);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->getJson("/api/sharing/search/$share->token?query=doc")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'id' => $file->id,
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
                $folder = Folder::factory()
                    ->create();

                $share = Share::factory()
                    ->create([
                        'item_id'      => $folder->id,
                        'user_id'      => $folder->user_id,
                        'type'         => 'folder',
                        'permission'   => 'editor',
                        'is_protected' => $is_protected,
                        'password'     => bcrypt('secret'),
                    ]);

                File::factory()
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
                        ->get("/api/sharing/search/$share->token?query=doc")
                        ->assertStatus(200)
                        ->assertJsonFragment([]);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->getJson("/api/sharing/search/$share->token?query=doc")
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
                $file = File::factory()
                    ->create([
                        'name' => 'Document',
                    ]);

                $share = Share::factory()
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
                        ->get("/api/sharing/file/$share->token")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'name' => 'Document',
                        ]);
                }

                // Check public shared item
                if (! $is_protected) {
                    $this->getJson("/api/sharing/file/$share->token")
                        ->assertStatus(200)
                        ->assertJsonFragment([
                            'name' => 'Document',
                        ]);
                }
            });
    }
}
