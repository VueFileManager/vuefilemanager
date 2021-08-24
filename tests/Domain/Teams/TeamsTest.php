<?php

namespace Tests\Domain\Teams;

use Domain\Folders\Models\Folder;
use Domain\Teams\Models\TeamFoldersInvitation;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;

class TeamsTest extends TestCase
{
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
            ->post('/api/teams/team-folders', [
                'name'    => 'Company Project',
                'members' => [
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
            ->assertDatabaseHas('team_folders_invitations', [
                'email' => 'john@internal.com',
            ])
            ->assertDatabaseHas('team_folders_invitations', [
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
            ->post("/api/teams/team-folders/convert/{$folder->id}", [
                'members' => [
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

        $invitation = TeamFoldersInvitation::factory()
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
            ->assertDatabaseHas('team_folders_invitations', [
                'folder_id' => $folder->id,
                'status'    => 'accepted',
            ])
            ->assertDatabaseHas('team_folder_members', [
                'folder_id'  => $folder->id,
                'member_id'  => $member->id,
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

        $invitation = TeamFoldersInvitation::factory()
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
            ->assertDatabaseHas('team_folders_invitations', [
                'folder_id' => $folder->id,
                'status'    => 'rejected',
            ])
            ->assertDatabaseMissing('team_folder_members', [
                'folder_id' => $folder->id,
                'member_id' => $member->id,
            ]);
    }

    /**
     *
     */
    public function it_add_member_into_team_folder()
    {
    }

    /**
     *
     */
    public function it_remove_member_from_team_folder()
    {
    }

    /**
     *
     */
    public function it_dissolve_team_folder()
    {
    }

    /**
     *
     */
    public function it_move_items_into_team_folder()
    {
    }

    /**
     *
     */
    public function it_get_all_team_folders()
    {
    }

    /**
     *
     */
    public function it_get_team_folders_shared_with_another_user()
    {
    }
}
