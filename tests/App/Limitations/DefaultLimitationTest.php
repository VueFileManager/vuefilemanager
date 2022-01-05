<?php
namespace Tests\App\Limitations;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Settings\Models\Setting;
use Domain\Teams\Models\TeamFolderMember;

class DefaultLimitationTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_upload()
    {
        $user = User::factory()
            ->create();

        $this->assertEquals(true, $user->canUpload(9999999));
    }

    /**
     * @test
     */
    public function it_cant_upload_because_storage_limit_exceeded()
    {
        $user = User::factory()
            ->create();

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'filesize' => 99999999,
            ]);

        $this->assertEquals(false, $user->canUpload(999999999));
    }

    /**
     * @test
     */
    public function it_can_upload_because_storage_limitation_is_turned_off_and_user_has_unlimited_limit()
    {
        $user = User::factory()
            ->create();

        // Turn off storage limitation
        Setting::updateOrCreate([
            'name' => 'storage_limitation',
        ], [
            'value' => 0,
        ]);

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'filesize' => 99999999,
            ]);

        $this->assertEquals(1, $user->limitations->max_storage_amount);
        $this->assertEquals(true, $user->canUpload(999999999));
    }

    /**
     * @test
     */
    public function it_cant_upload_because_storage_limitation_is_turned_on_and_user_exceeded_limit()
    {
        $user = User::factory()
            ->create();

        // Turn on storage limitation
        Setting::updateOrCreate([
            'name' => 'storage_limitation',
        ], [
            'value' => 1,
        ]);

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'filesize' => 99999999,
            ]);

        $this->assertEquals(1, $user->limitations->max_storage_amount);
        $this->assertEquals(false, $user->canUpload(999999999));
    }

    /**
     * @test
     */
    public function it_can_create_new_folder()
    {
        $user = User::factory()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/create-folder', [
                'name'      => 'New Folder',
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('folders', [
            'name' => 'New Folder',
        ]);
    }

    /**
     * @test
     */
    public function it_cant_invite_team_members_into_team_folder_because_user_exceeded_members_limit()
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

        // Create team folder members
        $members = User::factory()
            ->count(5)
            ->create()
            ->each(
                fn ($member) => TeamFolderMember::factory()
            ->create([
                'parent_id' => $user->folders[0]->id,
                'user_id'   => $member->id,
            ])
            );

        // Try invite new members, it has to fail
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
            ->assertStatus(401);

        // Invite existing member, it has to go through
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
