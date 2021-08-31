<?php
namespace Tests\Domain\Teams;

use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Domain\Teams\Models\TeamFolderInvitation;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;

class TeamsTest extends TestCase
{
    /**
     * @test
     */
    public function it_find_team_folder_id_from_children()
    {
        $teamFolder = Folder::factory()
            ->create([
                'team_folder' => 1,
            ]);

        $level_1 = Folder::factory()
            ->create([
                'parent_id' => $teamFolder->id,
            ]);

        $level_2 = Folder::factory()
            ->create([
                'parent_id' => $level_1->id,
            ]);

        $teamRoot = recursiveFind($level_2->teamRoot->toArray(), 'id');

        $this->assertEquals($teamFolder->id, end($teamRoot));
    }

    /**
     * @test
     */
    public function it_create_team_folder()
    {
        User::factory(User::class)
            ->create([
                'email' => 'john@internal.com',
            ]);

        $user = User::factory()
            ->create();

        $this
            ->actingAs($user)
            ->post('/api/teams/folders', [
                'name'        => 'Company Project',
                'invitations' => [
                    [
                        'email'      => 'john@internal.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'email'      => 'jane@external.com',
                        'permission' => 'can-view',
                    ],
                ],
            ])
            ->assertCreated()
            ->assertJsonFragment([
                'name' => 'Company Project',
            ]);

        $this
            ->assertDatabaseHas('folders', [
                'name'        => 'Company Project',
                'team_folder' => 1,
            ])
            ->assertDatabaseHas('team_folder_invitations', [
                'email' => 'john@internal.com',
            ])
            ->assertDatabaseHas('team_folder_invitations', [
                'email' => 'jane@external.com',
            ]);

        Notification::assertTimesSent(2, InvitationIntoTeamFolder::class);
    }

    /**
     * @test
     */
    public function it_convert_folder_into_team_folder()
    {
        $user = User::factory()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->post("/api/teams/convert/{$folder->id}", [
                'invitations' => [
                    [
                        'email'      => 'john@internal.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'email'      => 'jane@external.com',
                        'permission' => 'can-view',
                    ],
                ],
            ])
            ->assertCreated()
            ->assertJsonFragment([
                'name' => $folder->name,
            ]);

        $this->assertDatabaseHas('folders', [
            'id'          => $folder->id,
            'team_folder' => 1,
        ]);

        Notification::assertTimesSent(2, InvitationIntoTeamFolder::class);
    }

    /**
     * @test
     */
    public function it_accept_team_folder_invite()
    {
        $member = User::factory(User::class)
            ->create([
                'email' => 'john@internal.com',
            ]);

        $folder = Folder::factory()
            ->create();

        $invitation = TeamFolderInvitation::factory()
            ->create([
                'folder_id'  => $folder->id,
                'email'      => $member->email,
                'status'     => 'pending',
                'permission' => 'can-edit',
            ]);

        $this
            ->actingAs($member)
            ->putJson("/api/teams/invitations/{$invitation->id}")
            ->assertNoContent();

        $this
            ->assertDatabaseHas('team_folder_invitations', [
                'folder_id' => $folder->id,
                'status'    => 'accepted',
            ])
            ->assertDatabaseHas('team_folder_members', [
                'folder_id'  => $folder->id,
                'user_id'    => $member->id,
                'permission' => 'can-edit',
            ]);
    }

    /**
     * @test
     */
    public function it_reject_team_folder_invite()
    {
        $member = User::factory(User::class)
            ->create([
                'email' => 'john@internal.com',
            ]);

        $folder = Folder::factory()
            ->create();

        $invitation = TeamFolderInvitation::factory()
            ->create([
                'folder_id'  => $folder->id,
                'email'      => $member->email,
                'status'     => 'pending',
                'permission' => 'can-edit',
            ]);

        $this
            ->actingAs($member)
            ->deleteJson("/api/teams/invitations/{$invitation->id}")
            ->assertNoContent();

        $this
            ->assertDatabaseHas('team_folder_invitations', [
                'folder_id' => $folder->id,
                'status'    => 'rejected',
            ])
            ->assertDatabaseMissing('team_folder_members', [
                'folder_id' => $folder->id,
                'user_id'   => $member->id,
            ]);
    }

    /**
     * @test
     */
    public function it_invite_member_into_team_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $members = User::factory(User::class)
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'folder_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
                'email'      => 'existing@member.com',
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[1]->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/teams/folders/{$folder->id}", [
                'members'     => [
                    [
                        'id'         => $members[0]->id,
                        'permission' => 'can-edit',
                    ],
                    [
                        'id'         => $members[1]->id,
                        'permission' => 'can-edit',
                    ],
                ],
                'invitations' => [
                    [
                        'id'         => null,
                        'email'      => 'existing@member.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'id'         => null,
                        'email'      => 'added@member.com',
                        'permission' => 'can-view',
                    ],
                ],
            ])
            ->assertCreated();

        $this
            ->assertDatabaseCount('team_folder_members', 2)
            ->assertDatabaseCount('team_folder_invitations', 2)
            ->assertDatabaseHas('team_folder_invitations', [
                'email'      => 'added@member.com',
                'permission' => 'can-view',
            ]);

        Notification::assertTimesSent(1, InvitationIntoTeamFolder::class);
    }

    /**
     * @test
     */
    public function it_delete_invited_member_from_team_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $members = User::factory(User::class)
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'folder_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
                'email'      => 'deleted@member.com',
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'folder_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
                'email'      => 'existing@member.com',
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[1]->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/teams/folders/{$folder->id}", [
                'members'     => [
                    [
                        'id'         => $members[0]->id,
                        'permission' => 'can-edit',
                    ],
                    [
                        'id'         => $members[1]->id,
                        'permission' => 'can-view',
                    ],
                ],
                'invitations' => [
                    [
                        'id'         => null,
                        'email'      => 'existing@member.com',
                        'permission' => 'can-view',
                    ],
                ],
            ])
            ->assertCreated();

        $this
            ->assertDatabaseCount('team_folder_members', 2)
            ->assertDatabaseCount('team_folder_invitations', 1)
            ->assertDatabaseHas('team_folder_invitations', [
                'email' => 'existing@member.com',
            ]);
    }

    /**
     * @test
     */
    public function it_remove_member_from_team_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $members = User::factory(User::class)
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[1]->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/teams/folders/{$folder->id}", [
                'members'     => [
                    [
                        'id'         => $members[0]->id,
                        'permission' => 'can-edit',
                    ],
                ],
                'invitations' => [],
            ])
            ->assertCreated();

        $this
            ->assertDatabaseCount('team_folder_members', 1)
            ->assertDatabaseMissing('team_folder_members', [
                'user_id' => $members[1]->id,
            ]);
    }

    /**
     * @test
     */
    public function it_update_invited_member_permission_in_team_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'folder_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-view',
                'email'      => 'existing@member.com',
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/teams/folders/{$folder->id}", [
                'members'     => [],
                'invitations' => [
                    [
                        'id'         => null,
                        'email'      => 'existing@member.com',
                        'permission' => 'can-edit',
                    ],
                ],
            ])
            ->assertCreated();

        $this
            ->assertDatabaseCount('team_folder_members', 0)
            ->assertDatabaseCount('team_folder_invitations', 1)
            ->assertDatabaseHas('team_folder_invitations', [
                'email'      => 'existing@member.com',
                'permission' => 'can-edit',
            ]);

        Notification::assertTimesSent(0, InvitationIntoTeamFolder::class);
    }

    /**
     * @test
     */
    public function it_update_member_permission_in_team_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $members = User::factory(User::class)
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[1]->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/teams/folders/{$folder->id}", [
                'members'     => [
                    [
                        'id'         => $members[0]->id,
                        'permission' => 'can-edit',
                    ],
                    [
                        'id'         => $members[1]->id,
                        'permission' => 'can-view',
                    ],
                ],
                'invitations' => [],
            ])
            ->assertCreated();

        $this->assertDatabaseHas('team_folder_members', [
            'user_id'    => $members[1]->id,
            'permission' => 'can-view',
        ]);
    }

    /**
     * @test
     */
    public function it_dissolve_team_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $members = User::factory(User::class)
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'folder_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'folder_id'  => $folder->id,
                    'user_id'    => $members[1]->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($user)
            ->deleteJson("/api/teams/folders/{$folder->id}")
            ->assertNoContent();

        $this
            ->assertDatabaseCount('team_folder_members', 0)
            ->assertDatabaseCount('team_folder_invitations', 0);
    }

    /**
     * @test
     */
    public function it_get_all_team_folders()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        $this
            ->actingAs($user)
            ->getJson('/api/teams/folders/undefined')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $folder->id,
            ]);
    }

    /**
     * @test
     */
    public function it_get_content_of_team_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        $file = File::factory()
            ->create([
                'folder_id' => $folder->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->getJson("/api/teams/folders/{$folder->id}")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $file->id,
            ]);
    }

    /**
     * @test
     */
    public function it_get_team_folders_shared_with_another_user()
    {
        $user = User::factory(User::class)
            ->create();

        $member = User::factory(User::class)
            ->create();

        $folders = Folder::factory()
            ->count(2)
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'folder_id'  => $folders[0]->id,
                    'user_id'    => $member->id,
                    'permission' => 'can-edit',
                ],
                [
                    'folder_id'  => $folders[1]->id,
                    'user_id'    => $member->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($member)
            ->getJson('/api/teams/shared-with-me/undefined')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $folders[0]->id,
            ]);
    }
}
