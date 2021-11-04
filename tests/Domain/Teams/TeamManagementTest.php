<?php

namespace Tests\Domain\Teams;

use Str;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Domain\Teams\Models\TeamFolderInvitation;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;

class TeamManagementTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_team_folder_invite()
    {
        $inviter = User::factory()
            ->create();

        $member = User::factory()
            ->create();

        $invitation = TeamFolderInvitation::factory()
            ->create([
                'inviter_id' => $inviter->id,
                'parent_id'  => Str::uuid(),
                'email'      => $member->email,
                'status'     => 'pending',
                'permission' => 'can-edit',
            ]);

        $this->getJson("/api/teams/invitations/{$invitation->id}")
            ->assertOk()
            ->assertJsonFragment([
                'name' => $inviter->settings->name,
            ]);
    }

    /**
     * @test
     */
    public function it_accept_team_folder_invite()
    {
        $member = User::factory()
            ->create([
                'email' => 'john@internal.com',
            ]);

        $folder = Folder::factory()
            ->create();

        $invitation = TeamFolderInvitation::factory()
            ->create([
                'parent_id'  => $folder->id,
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
                'parent_id' => $folder->id,
                'status'    => 'accepted',
            ])
            ->assertDatabaseHas('team_folder_members', [
                'parent_id'  => $folder->id,
                'user_id'    => $member->id,
                'permission' => 'can-edit',
            ]);
    }

    /**
     * @test
     */
    public function it_get_used_team_folder_invite()
    {
        $invitation = TeamFolderInvitation::factory()
            ->create(['status' => 'accepted']);

        $this
            ->getJson("/api/teams/invitations/{$invitation->id}")
            ->assertStatus(410);
    }

    /**
     * @test
     */
    public function it_reject_team_folder_invite()
    {
        $member = User::factory()
            ->create([
                'email' => 'john@internal.com',
            ]);

        $folder = Folder::factory()
            ->create();

        $invitation = TeamFolderInvitation::factory()
            ->create([
                'parent_id'  => $folder->id,
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
                'parent_id' => $folder->id,
                'status'    => 'rejected',
            ])
            ->assertDatabaseMissing('team_folder_members', [
                'parent_id' => $folder->id,
                'user_id'   => $member->id,
            ]);
    }

    /**
     * @test
     */
    public function it_invite_member_into_team_folder()
    {
        $user = User::factory()
            ->create();

        $members = User::factory()
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'parent_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
                'email'      => 'existing@member.com',
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folder->id,
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
        $user = User::factory()
            ->create();

        $members = User::factory()
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'parent_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
                'email'      => 'deleted@member.com',
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'parent_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
                'email'      => 'existing@member.com',
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folder->id,
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
        $user = User::factory()
            ->create();

        $members = User::factory()
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
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folder->id,
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
        $user = User::factory()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'parent_id'  => $folder->id,
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
        $user = User::factory()
            ->create();

        $members = User::factory()
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
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folder->id,
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
    public function member_try_update_permission_in_team_folder()
    {
        $user = User::factory()
            ->create();

        $members = User::factory()
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
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[1]->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs(
                User::find($members[0]->id)
            )
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
            ->assertForbidden();

        $this->assertDatabaseHas('team_folder_members', [
            'user_id'    => $members[1]->id,
            'permission' => 'can-edit',
        ]);
    }

    /**
     * @test
     */
    public function it_dissolve_team_folder()
    {
        $user = User::factory()
            ->create();

        $members = User::factory()
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        $folderWithin = Folder::factory()
            ->create([
                'parent_id'   => $folder->id,
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'parent_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folder->id,
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
            ->assertDatabaseCount('team_folder_invitations', 0)
            ->assertDatabaseHas('folders', [
                'id'          => $folder->id,
                'team_folder' => 0,
            ])
            ->assertDatabaseHas('folders', [
                'id'          => $folderWithin->id,
                'team_folder' => 0,
            ]);
    }

    /**
     * @test
     */
    public function it_leave_team_folder()
    {
        $user = User::factory()
            ->create();

        $member = User::factory()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $member->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs($member)
            ->deleteJson("/api/teams/folders/{$folder->id}/leave")
            ->assertNoContent();

        $this
            ->assertDatabaseMissing('team_folder_members', [
                'parent_id'  => $folder->id,
                'user_id'    => $member->id,
                'permission' => 'can-edit',
            ]);
    }

    /**
     * @test
     */
    public function member_try_dissolve_team_folder()
    {
        $user = User::factory()
            ->create();

        $members = User::factory()
            ->count(2)
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        TeamFolderInvitation::factory()
            ->create([
                'parent_id'  => $folder->id,
                'status'     => 'pending',
                'permission' => 'can-edit',
            ]);

        DB::table('team_folder_members')
            ->insert([
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[0]->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folder->id,
                    'user_id'    => $members[1]->id,
                    'permission' => 'can-edit',
                ],
            ]);

        $this
            ->actingAs(
                User::find($members[0]->id)
            )
            ->deleteJson("/api/teams/folders/{$folder->id}")
            ->assertForbidden();

        $this
            ->assertDatabaseCount('team_folder_members', 2)
            ->assertDatabaseCount('team_folder_invitations', 1);
    }
}
