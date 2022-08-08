<?php
namespace Tests\App\Restrictions;

use Illuminate\Http\UploadedFile;
use Storage;
use Str;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Settings\Models\Setting;
use Domain\Teams\Models\TeamFolderMember;

class FixedBillingRestrictionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Setting::updateOrCreate([
            'name' => 'subscription_type',
        ], [
            'value' => 'fixed',
        ]);
    }

    /**
     * @test
     */
    public function it_can_upload()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this->assertEquals(true, $user->canUpload(9999999));
    }

    /**
     * @test
     */
    public function it_cant_upload_because_storage_limit_exceeded()
    {
        $user = User::factory()
            ->hasSettings()
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
    public function it_can_create_new_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/create-folder', [
                'name' => 'New Folder',
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
            ->hasSettings()
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
                        'type'       => 'invitation',
                        'email'      => 'test@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'type'       => 'invitation',
                        'email'      => 'test2@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'type'       => 'invitation',
                        'email'      => 'test3@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'type'       => 'invitation',
                        'email'      => 'test4@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'type'       => 'invitation',
                        'email'      => 'test5@doe.com',
                        'permission' => 'can-edit',
                    ],
                    [
                        'type'       => 'invitation',
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
                        'type'       => 'invitation',
                        'email'      => $members[0]->email,
                        'permission' => 'can-edit',
                    ],
                ],
            ])
            ->assertCreated();
    }

    /**
     * @test
     */
    public function it_can_get_private_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $file, $file->name);

        $file = File::factory()
            ->create([
                'user_id'  => $user->id,
                'basename' => $file->name,
                'name'     => 'fake-file.pdf',
            ]);

        // 404 but, ok, because there is not stored temporary file in test
        $this
            ->actingAs($user)
            ->get("file/$file->name")
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function it_can_get_shared_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = File::factory()
            ->create([
                'basename' => 'fake-file.pdf',
                'name'     => 'fake-file.pdf',
            ]);

        $share = Share::factory()
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $user->id,
                'type'         => 'file',
                'is_protected' => false,
            ]);

        // 404 but, ok, because there is not stored temporary file in test
        $this
            ->get("file/$file->name/shared/$share->token")
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function it_can_get_share_page()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $share = Share::factory()
            ->create([
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->get("/share/$share->token")
            ->assertRedirect("/share/$share->token/files/$share->item_id");
    }
}
