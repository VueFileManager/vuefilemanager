<?php
namespace Tests\Domain\Browsing;

use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;
use Domain\Files\Models\File;
use Illuminate\Bus\Queueable;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BrowseTest extends TestCase
{
    use DatabaseMigrations, Queueable;

    /**
     * @test
     */
    public function it_get_navigator_tree()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder_level_1 = Folder::factory()
            ->create([
                'name'    => 'level 1',
                'author'  => 'user',
                'user_id' => $user->id,
            ]);

        $folder_level_2 = Folder::factory()
            ->create([
                'name'      => 'level 2',
                'parent_id' => $folder_level_1->id,
                'author'    => 'user',
                'user_id'   => $user->id,
            ]);

        $folder_level_3 = Folder::factory()
            ->create([
                'name'      => 'level 3',
                'parent_id' => $folder_level_2->id,
                'author'    => 'user',
                'user_id'   => $user->id,
            ]);

        $folder_level_2_sibling = Folder::factory()
            ->create([
                'name'      => 'level 2 Sibling',
                'parent_id' => $folder_level_1->id,
                'author'    => 'user',
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->getJson('/api/browse/navigation')
            ->assertStatus(200)
            ->assertExactJson([
                [
                    'location'  => 'files',
                    'name'      => 'Files',
                    'folders'   => [
                        [
                            'id'            => $folder_level_1->id,
                            'parent_id'     => null,
                            'name'          => 'level 1',
                            'items'         => 2,
                            'trashed_items' => 2,
                            'team_folder'   => false,
                            'folders'       => [
                                [
                                    'id'            => $folder_level_2->id,
                                    'parent_id'     => $folder_level_1->id,
                                    'name'          => 'level 2',
                                    'items'         => 1,
                                    'trashed_items' => 1,
                                    'team_folder'   => false,
                                    'folders'       => [
                                        [
                                            'id'            => $folder_level_3->id,
                                            'user_id'       => $user->id,
                                            'parent_id'     => $folder_level_2->id,
                                            'name'          => 'level 3',
                                            'color'         => null,
                                            'emoji'         => null,
                                            'author'        => 'user',
                                            'deleted_at'    => null,
                                            'created_at'    => $folder_level_3->created_at,
                                            'updated_at'    => $folder_level_3->updated_at->toJson(),
                                            'items'         => 0,
                                            'trashed_items' => 0,
                                            'team_folder'   => false,
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
                                    'team_folder'   => false,
                                    'folders'       => [],
                                ],
                            ],
                        ],
                    ],
                    'isMovable' => true,
                    'isOpen'    => true,
                ],
                [
                    'location'  => 'team-folders',
                    'name'      => 'Team Folders',
                    'folders'   => [],
                    'isMovable' => false,
                    'isOpen'    => false,
                ],
                [
                    'location'  => 'shared-with-me',
                    'name'      => 'Shared with Me',
                    'folders'   => [],
                    'isMovable' => false,
                    'isOpen'    => false,
                ],
            ]);
    }

    /**
     * @test
     */
    public function it_get_folder_content()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $root = Folder::factory()
            ->create([
                'name'    => 'root',
                'user_id' => $user->id,
            ]);

        $folder = Folder::factory()
            ->create([
                'parent_id' => $root->id,
                'name'      => 'Documents',
                'author'    => 'user',
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

        $this
            ->actingAs($user)
            ->getJson("/api/browse/folders/$root->id")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $root->id,
            ])
            ->assertJsonFragment([
                'id' => $file->id,
            ])
            ->assertJsonFragment([
                'id' => $folder->id,
            ]);
    }

    /**
     * @test
     */
    public function it_get_recent_files()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $root = Folder::factory()
            ->create([
                'name'    => 'root',
                'user_id' => $user->id,
            ]);

        $file_1 = File::factory()
            ->create([
                'parent_id'  => $root->id,
                'name'       => 'Document 1',
                'basename'   => 'document-1.pdf',
                'mimetype'   => 'application/pdf',
                'type'       => 'file',
                'user_id'    => $user->id,
                'created_at' => now(),
            ]);

        $this->travel(5)->minutes();

        $file_2 = File::factory()
            ->create([
                'parent_id'  => $root->id,
                'name'       => 'Document 2',
                'basename'   => 'document-2.pdf',
                'mimetype'   => 'application/pdf',
                'type'       => 'file',
                'user_id'    => $user->id,
                'created_at' => now(),
            ]);

        $this
            ->actingAs($user)
            ->getJson('/api/browse/latest')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $file_1->id,
            ])
            ->assertJsonFragment([
                'id' => $file_2->id,
            ]);
    }

    /**
     * @test
     */
    public function it_get_trash_root()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'parent_id'  => null,
                'name'       => 'root',
                'user_id'    => $user->id,
                'author'     => 'user',
                'deleted_at' => now(),
            ]);

        $file = File::factory()
            ->create([
                'parent_id'  => null,
                'name'       => 'Document',
                'basename'   => 'document.pdf',
                'mimetype'   => 'application/pdf',
                'type'       => 'file',
                'user_id'    => $user->id,
                'deleted_at' => now(),
            ]);

        File::factory()
            ->create([
                'parent_id'  => $folder->id,
                'user_id'    => $user->id,
                'deleted_at' => now(),
            ]);

        $this
            ->actingAs($user)
            ->getJson('/api/browse/trash/undefined')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $folder->id,
            ])
            ->assertJsonFragment([
                'id' => $file->id,
            ]);
    }

    /**
     * @test
     */
    public function it_get_shared_items()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        Sanctum::actingAs($user);

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $file = File::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        Share::factory()
            ->create([
                'type'    => 'folder',
                'item_id' => $folder->id,
                'user_id' => $user->id,
            ]);

        Share::factory()
            ->create([
                'type'    => 'file',
                'item_id' => $file->id,
                'user_id' => $user->id,
            ]);

        $this->getJson('/api/browse/share')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $folder->id,
            ])
            ->assertJsonFragment([
                'id' => $file->id,
            ]);
    }
}
