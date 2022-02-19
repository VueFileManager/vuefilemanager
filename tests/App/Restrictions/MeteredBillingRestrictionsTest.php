<?php
namespace Tests\App\Restrictions;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Settings\Models\Setting;

class MeteredBillingRestrictionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Setting::updateOrCreate([
            'name'  => 'subscription_type',
        ], [
            'value' => 'metered',
        ]);
    }

    /**
     * @test
     */
    public function it_can_upload()
    {
        $user = User::factory()
            ->hasFailedpayments(2)
            ->create();

        $this->assertEquals(true, $user->canUpload());
    }

    /**
     * @test
     */
    public function it_cant_upload_because_user_has_3_failed_payments()
    {
        $user = User::factory()
            ->hasFailedpayments(3)
            ->create();

        $this->assertEquals(false, $user->canUpload());
    }

    /**
     * @test
     */
    public function it_can_create_new_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        // Create basic folder
        $this
            ->actingAs($user)
            ->postJson('/api/create-folder', [
                'name' => 'New Folder',
            ])
            ->assertStatus(201);

        // Create team folder
        $this
            ->actingAs($user)
            ->postJson('/api/teams/folders', [
                'name'        => 'New Team Folder',
                'invitations' => [
                    [
                        'email'      => 'john@doe.com',
                        'permission' => 'can-edit',
                        'type'       => 'invitation',
                    ],
                ],
            ])
            ->assertStatus(201);

        $this->assertDatabaseCount('folders', 2);
    }

    /**
     * @test
     */
    public function it_cant_create_new_folder_because_user_has_3_failed_payments()
    {
        $user = User::factory()
            ->hasFailedpayments(3)
            ->create();

        // Create basic folder
        $this
            ->actingAs($user)
            ->postJson('/api/create-folder', [
                'name' => 'New Folder',
            ])
            ->assertStatus(401);

        // Create team folder
        $this
            ->actingAs($user)
            ->postJson('/api/teams/folders', [
                'name'        => 'New Folder',
                'invitations' => [
                    [
                        'email'      => 'john@doe.com',
                        'permission' => 'can-edit',
                        'type'       => 'invitation',
                    ],
                ],
            ])
            ->assertStatus(401);

        $this->assertDatabaseCount('folders', 0);
    }

    /**
     * @test
     */
    public function it_cant_get_private_file_because_user_has_3_failed_payments()
    {
        $user = User::factory()
            ->hasFailedpayments(3)
            ->create();

        $file = File::factory()
            ->create([
                'user_id'  => $user->id,
                'basename' => 'fake-file.pdf',
                'name'     => 'fake-file.pdf',
            ]);

        $this
            ->actingAs($user)
            ->get("file/$file->name")
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_can_get_private_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = File::factory()
            ->create([
                'user_id'  => $user->id,
                'basename' => 'fake-file.pdf',
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
    public function it_cant_get_shared_file_because_user_has_3_failed_payments()
    {
        $user = User::factory()
            ->hasFailedpayments(3)
            ->create();

        $file = File::factory()
            ->create([
                'user_id'  => $user->id,
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

        $this
            ->get("file/$file->name/shared/$share->token")
            ->assertStatus(401);
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
                'user_id'  => $user->id,
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
    public function it_cant_get_share_page()
    {
        $user = User::factory()
            ->hasFailedpayments(3)
            ->create();

        $share = Share::factory()
            ->create([
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->get("/share/$share->token")
            ->assertRedirect('/temporary-unavailable');
    }
}
