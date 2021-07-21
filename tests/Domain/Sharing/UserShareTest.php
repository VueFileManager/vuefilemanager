<?php
namespace Tests\Domain\Sharing;

use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Notification;
use Domain\Sharing\Notifications\SharedSendViaEmail;

class UserShareTest extends TestCase
{
    /**
     * @test
     */
    public function it_share_single_file_without_password()
    {
        $file = File::factory(File::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/share', [
                'isPassword' => false,
                'permission' => 'editor',
                'type'       => 'file',
                'id'         => $file->id,
            ])->assertStatus(201)->assertJsonFragment([
                'item_id' => $file->id,
                'type'    => 'file',
            ]);

        $this->assertDatabaseHas('shares', [
            'user_id'      => $user->id,
            'item_id'      => $file->id,
            'type'         => 'file',
            'is_protected' => false,
            'password'     => null,
            'expire_in'    => null,
        ]);
    }

    /**
     * @test
     */
    public function it_share_folder_without_password()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/share', [
                'isPassword' => false,
                'permission' => 'editor',
                'type'       => 'folder',
                'id'         => $folder->id,
            ])->assertStatus(201)->assertJsonFragment([
                'item_id' => $folder->id,
                'type'    => 'folder',
            ]);

        $this->assertDatabaseHas('shares', [
            'user_id'      => $user->id,
            'item_id'      => $folder->id,
            'type'         => 'folder',
            'is_protected' => false,
            'password'     => null,
            'expire_in'    => null,
        ]);
    }

    /**
     * @test
     */
    public function it_share_folder_with_password()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/share', [
                'isPassword' => true,
                'password'   => 'secret',
                'permission' => 'editor',
                'type'       => 'folder',
                'id'         => $folder->id,
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'item_id' => $folder->id,
                'type'    => 'folder',
            ]);

        $this->assertDatabaseHas('shares', [
            'user_id'      => $user->id,
            'item_id'      => $folder->id,
            'type'         => 'folder',
            'is_protected' => true,
        ]);

        $this->assertDatabaseMissing('shares', [
            'item_id'  => $folder->id,
            'password' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_share_folder_with_expiration_time()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/share', [
                'isPassword' => false,
                'permission' => 'editor',
                'type'       => 'folder',
                'id'         => $folder->id,
                'expiration' => 12,
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'item_id'   => $folder->id,
                'expire_in' => 12,
            ]);
    }

    /**
     * @test
     */
    public function it_share_folder_and_send_link_for_multiple_email()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/share', [
                'isPassword' => false,
                'permission' => 'editor',
                'type'       => 'folder',
                'id'         => $folder->id,
                'emails'     => [
                    'john@doe.com',
                    'jane@doe.com',
                ],
            ])->assertStatus(201);

        Notification::assertTimesSent(2, SharedSendViaEmail::class);
    }

    /**
     * @test
     */
    public function it_send_existing_shared_folder_for_multiple_email_once_again()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/share', [
            'isPassword' => false,
            'permission' => 'editor',
            'type'       => 'folder',
            'id'         => $folder->id,
        ])->assertStatus(201);

        $this->postJson("/api/share/$folder->id/email", [
            'emails' => [
                'john@doe.com',
                'jane@doe.com',
            ],
        ])->assertStatus(204);

        Notification::assertTimesSent(2, SharedSendViaEmail::class);
    }

    /**
     * @test
     *
     * TODO: pridat test na zmazanie zip
     */
    public function it_revoke_single_share_record()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/share', [
            'isPassword' => false,
            'permission' => 'editor',
            'type'       => 'folder',
            'id'         => $folder->id,
        ])->assertStatus(201);

        $this->deleteJson('/api/share', [
            'tokens' => [
                $folder->shared->token,
            ],
        ])->assertStatus(204);

        $this->assertDatabaseMissing('shares', [
            'item_id' => $folder->id,
        ]);
    }
}
