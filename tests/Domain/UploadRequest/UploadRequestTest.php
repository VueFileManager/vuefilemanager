<?php

namespace Tests\Domain\UploadRequest;

use Domain\UploadRequest\Notifications\UploadRequestNotification;
use Domain\UploadRequest\Models\UploadRequest;
use App\Users\Models\User;
use Tests\TestCase;
use Notification;

class UploadRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_upload_request_factory()
    {
        $uploadRequest = UploadRequest::factory()
            ->create();

        $this->assertModelExists($uploadRequest);
    }

    /**
     * @test
     */
    public function user_create_upload_request_with_email()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson("/api/upload-request", [
                'folder_id' => '00cacdb9-1d09-4a32-8ad7-c0d45d66b758',
                'email'     => 'howdy@hi5ve.digital',
                'notes'     => 'Please send me your files...',
            ])
            ->assertCreated();

        $this->assertDatabasehas('upload_requests', [
            'folder_id' => '00cacdb9-1d09-4a32-8ad7-c0d45d66b758',
            'email'     => 'howdy@hi5ve.digital',
            'notes'     => 'Please send me your files...',
        ]);

        Notification::assertTimesSent(1, UploadRequestNotification::class);
    }

    /**
     * @test
     */
    public function user_create_upload_request_without_email()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson("/api/upload-request", [
                'folder_id' => '00cacdb9-1d09-4a32-8ad7-c0d45d66b758',
                'notes'     => 'Please send me your files...',
            ])
            ->assertCreated();

        $this->assertDatabasehas('upload_requests', [
            'folder_id' => '00cacdb9-1d09-4a32-8ad7-c0d45d66b758',
            'notes'     => 'Please send me your files...',
            'email'     => null,
        ]);

        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function it_get_upload_request_detail()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $this->getJson("/api/upload-request/$uploadRequest->id")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $uploadRequest->id,
            ]);
    }
}