<?php
namespace Tests\Domain\Folders;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Folders\Models\Folder;

class FavouriteFolderTest extends TestCase
{
    /**
     * @test
     */
    public function it_add_folder_to_favourites()
    {
        $folder = Folder::factory()
            ->create();

        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/favourites', [
                'ids' => [
                    $folder->id,
                ],
            ])->assertStatus(201);

        $this->assertDatabaseHas('favourite_folder', [
            'user_id'   => $user->id,
            'parent_id' => $folder->id,
        ]);
    }

    /**
     * @test
     */
    public function it_remove_folder_from_favourites()
    {
        $folder = Folder::factory()
            ->create();

        $user = User::factory()
            ->hasSettings()
            ->create();

        $user
            ->favouriteFolders()
            ->attach($folder->id);

        $this
            ->actingAs($user)
            ->deleteJson("/api/favourites/$folder->id")
            ->assertStatus(201);

        $this->assertDatabaseMissing('favourite_folder', [
            'user_id'   => $user->id,
            'parent_id' => $folder->id,
        ]);
    }
}
