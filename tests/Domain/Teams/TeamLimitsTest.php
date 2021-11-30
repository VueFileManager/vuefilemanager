<?php
namespace Tests\Domain\Teams;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Teams\Models\TeamFolderMember;

class TeamLimitsTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_team_folder_with_exceeded_members_limit()
    {
        $user = User::factory()
            ->hasFolders([
                'team_folder' => true,
            ])
            ->create();

        TeamFolderMember::create([
            'parent_id'  => $user->folders[0]->id,
            'user_id'    => $user->id,
            'permission' => 'owner',
        ]);

        $members = User::factory()
            ->count(5)
            ->create();

        $members->each(
            fn ($member) => TeamFolderMember::factory()
            ->create([
                'parent_id' => $user->folders[0]->id,
                'user_id'   => $member->id,
            ])
        );

        // Try invite new member
        $this
            ->actingAs($user)
            ->post('/api/teams/folders', [
                'name'        => 'Company Project',
                'invitations' => [
                    [
                        'email'      => 'test@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'email'      => 'test2@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'email'      => 'test3@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'email'      => 'test4@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'email'      => 'test5@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'email'      => 'test6@doe.com',
                        'permission' => 'can-edit',
                    ],
                ],
            ])
            ->assertStatus(423);

        // Invite existing member
        $this
            ->actingAs($user)
            ->post('/api/teams/folders', [
                'name'        => 'Company Project',
                'invitations' => [
                    [
                        'email'      => $members[0]->email,
                        'permission' => 'can-edit',
                    ],
                ],
            ])
            ->assertCreated();
    }
}
