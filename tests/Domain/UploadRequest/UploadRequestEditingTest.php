<?php
namespace Tests\Domain\UploadRequest;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
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
            ->postJson("/api/upload-request/$uploadRequest->id/rename/$folder->id", [
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
            ->postJson("/api/upload-request/$uploadRequest->id/rename/$file->id", [
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
}
