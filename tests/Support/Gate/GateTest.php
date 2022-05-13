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
    public function team_member_with_can_edit_privilege_rename_folder()
    {
        $owner = User::factory()
            ->hasSettings()
            ->create();

        $member = User::factory()
            ->hasSettings()
            ->create();

        $teamFolder = Folder::factory()
            ->create([
                'user_id'     => $owner->id,
                'team_folder' => 1,
                'name'        => 'Team Folder',
            ]);

        $parent = Folder::factory()
            ->create([
                'user_id'   => $owner->id,
                'parent_id' => $teamFolder->id,
            ]);

        $children = Folder::factory()
            ->create([
                'user_id'   => $owner->id,
                'parent_id' => $parent->id,
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $teamFolder->id,
                    'user_id'    => $member->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($member)
            ->patchJson("/api/rename/{$children->id}", [
                'name' => 'Renamed Folder',
                'type' => 'folder',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed Folder',
            ]);
    }

    /**
     * @test
     */
    public function team_member_with_can_visit_privilege_try_rename_folder()
    {
        $owner = User::factory()
            ->create();

        $member = User::factory()
            ->create();

        $teamFolder = Folder::factory()
            ->create([
                'user_id'     => $owner->id,
                'team_folder' => 1,
                'name'        => 'Team Folder',
            ]);

        $parent = Folder::factory()
            ->create([
                'user_id'   => $owner->id,
                'parent_id' => $teamFolder->id,
            ]);

        $children = Folder::factory()
            ->create([
                'user_id'   => $owner->id,
                'parent_id' => $parent->id,
                'name'      => 'Captivating',
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $teamFolder->id,
                    'user_id'    => $member->id,
                    'permission' => 'can-view',
                ],
            ]);

        $this
            ->actingAs($member)
            ->patchJson("/api/rename/{$children->id}", [
                'name' => 'Renamed Folder',
                'type' => 'folder',
            ])
            ->assertStatus(403);

        $this->assertDatabaseHas('folders', [
            'name' => 'Captivating',
        ]);
    }

    /**
     * @test
     */
    public function team_member_rename_file()
    {
        $owner = User::factory()
            ->hasSettings()
            ->create();

        $member = User::factory()
            ->hasSettings()
            ->create();

        $teamFolder = Folder::factory()
            ->create([
                'user_id'     => $owner->id,
                'team_folder' => 1,
                'name'        => 'Team Folder',
            ]);

        $file = File::factory()
            ->create([
                'user_id'   => $owner->id,
                'parent_id' => $teamFolder->id,
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $teamFolder->id,
                    'user_id'    => $member->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($member)
            ->patchJson("/api/rename/{$file->id}", [
                'name' => 'Renamed File',
                'type' => 'file',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed File',
            ]);
    }
}
