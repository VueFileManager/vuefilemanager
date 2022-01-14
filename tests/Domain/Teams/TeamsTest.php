<?php
namespace Tests\Domain\Teams;

use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;

class TeamsTest extends TestCase
{
    /**
     * @test
     */
    public function it_find_team_parent_id_from_children()
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

        $teamRoot = $level_2->getLatestParent();

        $this->assertEquals($teamFolder->id, $teamRoot->id);
    }

    /**
     * @test
     */
    public function it_create_team_folder()
    {
        User::factory()
            ->create([
                'email' => 'john@internal.com',
            ]);

        $user = User::factory()
            ->hasSettings()
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
    public function it_mark_newly_created_folder_as_team_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $teamFolder = Folder::factory()
            ->create([
                'team_folder' => 1,
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/create-folder', [
                'name'      => 'Inner Folder',
                'parent_id' => $teamFolder->id,
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'isTeamFolder' => true,
            ]);
    }

    /**
     * @test
     */
    public function it_convert_folder_into_team_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $folderWithin = Folder::factory()
            ->create([
                'parent_id' => $folder->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->post("/api/teams/folders/{$folder->id}/convert", [
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
            'id'          => $folderWithin->id,
            'team_folder' => 1,
        ]);

        Notification::assertTimesSent(2, InvitationIntoTeamFolder::class);
    }

    /**
     * @test
     */
    public function it_get_all_team_folders()
    {
        $user = User::factory()
            ->hasSettings()
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
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id'     => $user->id,
                'team_folder' => 1,
            ]);

        $file = File::factory()
            ->create([
                'parent_id' => $folder->id,
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
        $user = User::factory()
            ->hasSettings()
            ->create();

        $member = User::factory()
            ->hasSettings()
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
                    'parent_id'  => $folders[0]->id,
                    'user_id'    => $member->id,
                    'permission' => 'can-edit',
                ],
                [
                    'parent_id'  => $folders[1]->id,
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
