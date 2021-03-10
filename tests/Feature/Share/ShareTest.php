<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Models\User;
use App\Notifications\SharedSendViaEmail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShareTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_share_single_file_without_password()
    {
        $file = File::factory(File::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/share/$file->id", [
            'isPassword' => false,
            'permission' => 'editor',
            'type'       => 'file',
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

        Sanctum::actingAs($user);

        $this->postJson("/api/share/$folder->id", [
            'isPassword' => false,
            'permission' => 'editor',
            'type'       => 'folder',
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

        Sanctum::actingAs($user);

        $this->postJson("/api/share/$folder->id", [
            'isPassword' => true,
            'password'   => 'secret',
            'permission' => 'editor',
            'type'       => 'folder',
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

        Sanctum::actingAs($user);

        $this->postJson("/api/share/$folder->id", [
            'isPassword' => false,
            'permission' => 'editor',
            'type'       => 'folder',
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
    public function it_share_folder_for_multiple_email()
    {
        Notification::fake();

        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/share/$folder->id", [
            'isPassword' => false,
            'permission' => 'editor',
            'type'       => 'folder',
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
        Notification::fake();

        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/share/$folder->id", [
            'isPassword' => false,
            'permission' => 'editor',
            'type'       => 'folder',
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
    public function it_revoke_single_sharing()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/share/$folder->id", [
            'isPassword' => false,
            'permission' => 'editor',
            'type'       => 'folder',
        ])->assertStatus(201);

        $this->deleteJson("/api/share/revoke", [
            'tokens' => [
                $folder->shared->token
            ],
        ])->assertStatus(204);

        $this->assertDatabaseMissing('shares', [
            'item_id' => $folder->id
        ]);
    }

    /**
     * @test
     */
    public function it_get_shared_record()
    {
        $share = Share::factory(Share::class)
            ->create([
                'is_protected' => 0,
            ]);

        $this->get("/api/shared/$share->token")
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id'         => $share->id,
                    'type'       => 'shares',
                    'attributes' => [
                        'permission'   => $share->permission,
                        'is_protected' => '0',
                        'item_id'      => $share->item_id,
                        'expire_in'    => $share->expire_in,
                        'token'        => $share->token,
                        'link'         => $share->link,
                        'type'         => $share->type,
                        'created_at'   => $share->created_at->toJson(),
                        'updated_at'   => $share->updated_at->toJson(),
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_get_deleted_shared_record()
    {
        $this->get("/api/shared/19ZMPNiass4ZqWwQ")
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function it_get_shared_page()
    {
        $share = Share::factory(Share::class)
            ->create([
                'type'         => 'file',
                'is_protected' => false,
            ]);

        $this->get("/shared/$share->token")
            ->assertViewIs('index')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_get_deleted_shared_page()
    {
        $this->get('/shared/19ZMPNiass4ZqWwQ')
            ->assertNotFound();
    }
}
