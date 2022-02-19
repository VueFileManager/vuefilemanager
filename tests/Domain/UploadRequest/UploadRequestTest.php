<?php

namespace Tests\Domain\UploadRequest;

use Domain\Files\Models\File;
use Domain\UploadRequest\Notifications\UploadRequestNotification;
use Domain\UploadRequest\Models\UploadRequest;
use App\Users\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Storage;
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
    public function it_upload_file_and_create_upload_request_folder()
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
            ->postJson("/api/upload-request/$uploadRequest->id", [
                'filename'  => $file->name,
                'file'      => $file,
                'parent_id' => null,
                'path'      => "/$file->name",
                'is_last'   => 'true',
            ])->assertStatus(201);

        $this
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
    public function it_upload_file_into_non_active_upload_request()
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
            ->postJson("/api/upload-request/$uploadRequest->id", [
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
    public function it_get_file_from_upload_request_folder()
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
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $file, $file->name);

        File::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'basename'  => $file->name,
                'user_id'   => $user->id,
                'name'      => 'fake-file.pdf',
            ]);

        $this
            ->get("/file/$file->name/upload-request/$uploadRequest->id")
            ->assertOk();
    }

    /**
     * @test
     */
    public function it_get_thumbnail_from_upload_request_folder()
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

        $thumbnail = UploadedFile::fake()
            ->image('fake-thumbnail.jpg');

        Storage::putFileAs("files/$user->id", $thumbnail, $thumbnail->name);

        File::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'basename'  => 'fake-thumbnail.jpg',
                'user_id'   => $user->id,
            ]);

        $this
            ->get("/thumbnail/xs-$thumbnail->name/upload-request/$uploadRequest->id")
            ->assertOk();
    }
}