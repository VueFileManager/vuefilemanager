<?php
namespace Tests\Domain\Notifications;

use DB;
use Str;
use Tests\TestCase;
use App\Users\Models\User;

class NotificationsTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_all_notifications()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        DB::table('notifications')
            ->insert([
                'id'              => Str::uuid(),
                'type'            => 'Domain\UploadRequest\Notifications\UploadRequestFulfilledNotification',
                'notifiable_type' => 'App\Users\Models\User',
                'notifiable_id'   => $user->id,
                'data'            => json_encode([
                    'category'        => 'file-request',
                    'title'           => 'File Request Filled',
                    'description'     => "Your file request for 'Documents' folder was filled successfully.",
                    'action'          => [
                        'type'   => 'route',
                        'params' => [
                            'route'  => 'Files',
                            'button' => 'Show Files',
                            'id'     => Str::uuid(),
                        ],
                    ],
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

        $this
            ->actingAs($user)
            ->getJson('/api/notifications')
            ->assertJsonFragment([
                'category' => 'file-request',
            ])
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_mark_as_read_notifications()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        DB::table('notifications')
            ->insert([
                'id'              => Str::uuid(),
                'type'            => 'Domain\UploadRequest\Notifications\UploadRequestFulfilledNotification',
                'notifiable_type' => 'App\Users\Models\User',
                'notifiable_id'   => $user->id,
                'data'            => json_encode([
                    'type'        => 'file-request',
                    'title'       => 'File Request Filled',
                    'description' => "Your file request for 'Documents' folder was filled successfully.",
                    'action'      => [
                        'type'   => 'route',
                        'params' => [
                            'route'  => 'Files',
                            'button' => 'Show Files',
                            'id'     => Str::uuid(),
                        ],
                    ],
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

        $this
            ->actingAs($user)
            ->postJson('/api/notifications/read')
            ->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'read_at' => now(),
        ]);
    }

    /**
     * @test
     */
    public function it_delete_all_notifications()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        DB::table('notifications')
            ->insert([
                'id'              => Str::uuid(),
                'type'            => 'Domain\UploadRequest\Notifications\UploadRequestFulfilledNotification',
                'notifiable_type' => 'App\Users\Models\User',
                'notifiable_id'   => $user->id,
                'data'            => json_encode([
                    'type'        => 'file-request',
                    'title'       => 'File Request Filled',
                    'description' => "Your file request for 'Documents' folder was filled successfully.",
                    'action'      => [
                        'type'   => 'route',
                        'params' => [
                            'route'  => 'Files',
                            'button' => 'Show Files',
                            'id'     => Str::uuid(),
                        ],
                    ],
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

        $this
            ->actingAs($user)
            ->deleteJson('/api/notifications')
            ->assertStatus(200);

        $this->assertDatabaseCount('notifications', 0);
    }
}
