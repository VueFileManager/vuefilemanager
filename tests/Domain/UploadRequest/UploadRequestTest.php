<?php
namespace Tests\Domain\UploadRequest;

use Storage;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Illuminate\Http\UploadedFile;
use Domain\UploadRequest\Models\UploadRequest;
use Support\Scheduler\Actions\ExpireUnfilledUploadRequestAction;
use Domain\UploadRequest\Notifications\UploadRequestNotification;
use Domain\UploadRequest\Notifications\UploadRequestFulfilledNotification;

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
            ->postJson('/api/upload-request', [
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
            ->postJson('/api/upload-request', [
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
    public function user_create_upload_request_with_name()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/upload-request', [
                'folder_id' => '00cacdb9-1d09-4a32-8ad7-c0d45d66b758',
                'notes'     => 'Please send me your files...',
                'name'      => 'My name',
            ])
            ->assertCreated();

        $this->assertDatabasehas('upload_requests', [
            'folder_id' => '00cacdb9-1d09-4a32-8ad7-c0d45d66b758',
            'notes'     => 'Please send me your files...',
            'email'     => null,
            'name'      => 'My name',
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
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $this->getJson("/api/upload-request/$uploadRequest->id")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $uploadRequest->id,
            ]);
    }

    /**
     * @test
     */
    public function it_upload_file_and_create_upload_request_folder_without_custom_folder_name()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'     => 'active',
                'user_id'    => $user->id,
                'created_at' => now(),
                'name'       => null,
            ]);

        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 12000000, 'application/pdf');

        $this
            ->postJson("/api/upload-request/$uploadRequest->id/upload", [
                'filename'  => $file->name,
                'file'      => $file,
                'parent_id' => null,
                'path'      => "/$file->name",
                'is_last'   => 'true',
            ])->assertStatus(201);

        $this
            ->assertDatabaseHas('upload_requests', [
                'status' => 'filling',
            ])
            ->assertDatabaseHas('folders', [
                'id'   => $uploadRequest->id,
                'name' => 'Upload Request from 01. Jan. 2021',
            ])->assertDatabaseHas('files', [
                'parent_id' => $uploadRequest->id,
            ]);

        $file = File::first();

        Storage::assertExists("files/$user->id/$file->basename");
    }

    /**
     * @test
     */
    public function it_upload_file_and_create_upload_request_folder_with_custom_folder_name()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'     => 'active',
                'user_id'    => $user->id,
                'created_at' => now(),
                'name'       => 'My Documents',
            ]);

        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 12000000, 'application/pdf');

        $this
            ->postJson("/api/upload-request/$uploadRequest->id/upload", [
                'filename'  => $file->name,
                'file'      => $file,
                'parent_id' => null,
                'path'      => "/$file->name",
                'is_last'   => 'true',
            ])->assertStatus(201);

        $this
            ->assertDatabaseHas('upload_requests', [
                'status' => 'filling',
            ])
            ->assertDatabaseHas('folders', [
                'id'   => $uploadRequest->id,
                'name' => 'My Documents',
            ])->assertDatabaseHas('files', [
                'parent_id' => $uploadRequest->id,
            ]);

        $file = File::first();

        Storage::assertExists("files/$user->id/$file->basename");
    }

    /**
     * @test
     */
    public function it_try_upload_file_into_non_active_upload_request()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'     => 'expired',
                'user_id'    => $user->id,
                'created_at' => now(),
            ]);

        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 12000000, 'application/pdf');

        $this
            ->postJson("/api/upload-request/$uploadRequest->id/upload", [
                'filename'  => $file->name,
                'file'      => $file,
                'parent_id' => null,
                'path'      => "/$file->name",
                'is_last'   => 'true',
            ])->assertStatus(410);
    }

    /**
     * @test
     */
    public function it_mark_upload_request_as_filled()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $this
            ->deleteJson("/api/upload-request/$uploadRequest->id")
            ->assertStatus(201)
            ->assertJsonFragment([
                'id'     => $uploadRequest->id,
                'status' => 'filled',
            ]);

        Notification::assertSentTo($user, UploadRequestFulfilledNotification::class);
    }

    /**
     * @test
     */
    public function it_mark_upload_request_as_expired_after_72_hours()
    {
        UploadRequest::factory()
            ->create([
                'status'     => 'active',
                'created_at' => now()->subHours(72),
            ]);

        resolve(ExpireUnfilledUploadRequestAction::class)();

        $this->assertDatabaseHas('upload_requests', [
            'status' => 'expired',
        ]);
    }

    /**
     * @test
     */
    public function it_mark_upload_request_as_filled_3_hours_after_last_upload()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'     => 'active',
                'user_id'    => $user->id,
                'created_at' => now(),
            ]);

        $file = UploadedFile::fake()
            ->create('fake-file.pdf', 12000000, 'application/pdf');

        $this
            ->postJson("/api/upload-request/$uploadRequest->id/upload", [
                'filename'  => $file->name,
                'file'      => $file,
                'parent_id' => null,
                'path'      => "/$file->name",
                'is_last'   => 'true',
            ])->assertCreated();

        $this->travel(3)->hours();

        resolve(ExpireUnfilledUploadRequestAction::class)();

        $this->assertDatabaseHas('upload_requests', [
            'status' => 'filled',
        ]);
    }
}
