<?php
namespace Tests\Domain\Teams;

use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;

class TeamsTest extends TestCase
{
    /**
     * @test
     *
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
                    'john@internal.com',
                    'jane@external.com',
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
     *
     */
    public function it_convert_team_folder()
    {
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
    public function member_accept_team_folder_invite()
    {
    }

    /**
     *
     */
    public function member_reject_team_folder_invite()
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
