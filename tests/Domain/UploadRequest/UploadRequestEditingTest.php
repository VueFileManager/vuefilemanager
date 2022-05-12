<?php
namespace Tests\Domain\UploadRequest;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use Domain\UploadRequest\Models\UploadRequest;

class UploadRequestEditingTest extends TestCase
{
    /**
     * @test
     */
    public function it_rename_folder_item()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $folder = Folder::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/file-request/$uploadRequest->id/rename/$folder->id", [
                'name' => 'Renamed Folder',
                'type' => 'folder',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed Folder',
            ]);

        $this->assertDatabaseHas('folders', [
            'name' => 'Renamed Folder',
        ]);
    }

    /**
     * @test
     */
    public function it_rename_file_item()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $file = File::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->patchJson("/api/file-request/$uploadRequest->id/rename/$file->id", [
                'name' => 'Renamed File',
                'type' => 'file',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Renamed File',
            ]);

        $this->assertDatabaseHas('files', [
            'name' => 'Renamed File',
        ]);
    }

    /**
     * @test
     */
    public function it_create_new_folder_in_upload_request()
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
            ->actingAs($user)
            ->postJson("/api/file-request/$uploadRequest->id/create-folder", [
                'name'      => 'New Folder',
                'parent_id' => $uploadRequest->id,
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'New Folder',
            ]);

        $this->assertDatabaseHas('folders', [
            'name'      => 'New Folder',
            'parent_id' => $uploadRequest->id,
        ]);
    }

    /**
     * @test
     */
    public function it_delete_image_with_their_thumbnails()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $image = File::factory()
            ->create([
                'mimetype'  => 'jpeg',
                'type'      => 'image',
                'basename'  => 'fake-image.jpeg',
                'user_id'   => $user->id,
                'parent_id' => $uploadRequest->id,
            ]);

        // Mock files
        $thumbnail_sizes = collect([
            config('vuefilemanager.image_sizes.later'),
            config('vuefilemanager.image_sizes.immediately'),
        ])->collapse();

        $fakeFile = UploadedFile::fake()
            ->create('fake-image.jpeg', 2000, 'image/jpeg');

        Storage::putFileAs("files/$user->id", $fakeFile, $fakeFile->name);

        // Create fake image thumbnails
        $thumbnail_sizes
            ->each(function ($item) use ($user) {
                $fakeFile = UploadedFile::fake()
                    ->create("{$item['name']}-fake-image.jpeg", 2000, 'image/jpeg');

                Storage::putFileAs("files/$user->id", $fakeFile, $fakeFile->name);
            });

        $this
            ->postJson("/api/file-request/$uploadRequest->id/remove", [
                'items' => [
                    [
                        'id'           => $image->id,
                        'type'         => 'file',
                        'force_delete' => true,
                    ],
                ],
            ])->assertStatus(200);

        // Assert primary file was deleted
        Storage::assertMissing("files/$user->id/fake-image.jpeg");

        // Assert thumbnail was deleted
        getThumbnailFileList('fake-image.jpeg')
            ->each(fn ($thumbnail) => Storage::assertMissing("files/$user->id/$thumbnail"));
    }

    /**
     * @test
     */
    public function it_delete_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $file = File::factory()
            ->create([
                'type'      => 'file',
                'basename'  => 'fake-file.pdf',
                'user_id'   => $user->id,
                'parent_id' => $uploadRequest->id,
            ]);

        $fakeFile = UploadedFile::fake()
            ->create('fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $fakeFile, $fakeFile->name);

        $this
            ->postJson("/api/file-request/$uploadRequest->id/remove", [
                'items' => [
                    [
                        'id'           => $file->id,
                        'type'         => 'file',
                        'force_delete' => true,
                    ],
                ],
            ])->assertStatus(200);

        // Assert primary file was deleted
        Storage::assertMissing("files/$user->id/fake-file.pdf");
    }

    /**
     * @test
     */
    public function it_delete_folder_with_file_within()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $folder = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $uploadRequest->id,
            ]);

        $folderWithin = Folder::factory()
            ->create([
                'user_id'   => $user->id,
                'parent_id' => $folder->id,
            ]);

        $file = File::factory()
            ->create([
                'type'      => 'file',
                'basename'  => 'fake-file.pdf',
                'user_id'   => $user->id,
                'parent_id' => $folder->id,
            ]);

        $fakeFile = UploadedFile::fake()
            ->create('fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $fakeFile, $fakeFile->name);

        $this
            ->postJson("/api/file-request/$uploadRequest->id/remove", [
                'items' => [
                    [
                        'id'           => $folder->id,
                        'type'         => 'folder',
                        'force_delete' => true,
                    ],
                ],
            ])->assertStatus(200);

        $this
            ->assertDatabaseMissing('folders', [
                'id' => $folder->id,
            ])
            ->assertDatabaseMissing('folders', [
                'id' => $folderWithin->id,
            ])
            ->assertDatabaseMissing('files', [
                'id' => $file->id,
            ]);

        // Assert primary file was deleted
        Storage::assertMissing("files/$user->id/fake-file.pdf");
    }

    /**
     * @test
     */
    public function it_move_file_to_another_folder_in_upload_request()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $folder = Folder::factory()
            ->create([
                'id'      => $uploadRequest->id,
                'user_id' => $user->id,
            ]);

        $file = File::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->postJson("/api/file-request/$uploadRequest->id/move", [
                'to_id' => $folder->id,
                'items' => [
                    [
                        'type' => 'file',
                        'id'   => $file->id,
                    ],
                ],
            ])->assertStatus(200);

        $this->assertDatabaseHas('files', [
            'id'        => $file->id,
            'parent_id' => $folder->id,
        ]);
    }
}
