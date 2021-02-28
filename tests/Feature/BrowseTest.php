<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BrowseTest extends TestCase
{
    use DatabaseMigrations;

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
            ->assertJson([
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
        $root = Folder::factory(Folder::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'parent_id' => $root->id
            ]);

        $file = File::factory(File::class)
            ->create([
                'folder_id' => $root->id
            ]);

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/browse/folders/$root->id")
            ->assertStatus(200);

        collect([$folder, $file])
            ->each(function ($item) use ($response) {
                $response->assertJsonFragment([
                    'id'   => $item->id,
                    'type' => $item->type,
                ]);
            });
    }

    public function it_get_searched_file()
    {

    }

    public function it_get_searched_folder()
    {

    }

    public function it_get_recent_files()
    {

    }

    public function it_get_trash()
    {

    }

    public function it_get_shared_files()
    {

    }

    public function it_get_participant_uploads()
    {

    }
}
