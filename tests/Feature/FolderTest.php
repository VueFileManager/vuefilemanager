<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

// TODO: pridat foldre do api skupiny

class FolderTest extends TestCase
{
    use DatabaseMigrations;

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
    public function it_set_folder_icon()
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

    public function it_zip_and_download_folder_with_content_within()
    {

    }

    public function it_delete_single_folder()
    {

    }

    public function it_delete_multiple_folder()
    {

    }

    public function it_delete_folder_softly()
    {

    }

    public function it_delete_folder_hardly()
    {

    }

    public function it_delete_folder_with_their_content_within_softly()
    {

    }

    public function it_delete_folder_with_their_content_within_hardly()
    {

    }
}
