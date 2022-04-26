<?php
namespace Tests\Support\Gate;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;

class GateTest extends TestCase
{
    /**
     * @test
     */
    public function owner_rename_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/rename/{$folder->id}", [
                'name' => 'Renamed Folder',
                'type' => 'folder',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed Folder',
            ]);

        $this->assertDatabaseHas('folders', [
            'name' => 'Renamed Folder',
        ]);
    }

    /**
     * @test
     */
    public function guest_rename_folder()
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
                if (! $is_protected) {
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
                    'id'   => $children->id,
                ]);
            });
    }
}
