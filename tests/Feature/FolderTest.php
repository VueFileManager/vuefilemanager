<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

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

        // TODO: pridat do api skupiny
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

        // TODO: pridat do api skupiny
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

    public function it_set_folder_emoji()
    {

    }

    public function it_set_folder_color()
    {

    }

    public function it_move_folder_to_another_folder()
    {

    }

    public function it_add_to_favourites_folder()
    {

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
