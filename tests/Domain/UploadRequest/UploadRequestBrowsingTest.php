<?php
namespace Tests\Domain\UploadRequest;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Domain\UploadRequest\Models\UploadRequest;

class UploadRequestBrowsingTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_navigator_tree_for_upload_request_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        Folder::factory()
            ->create([
                'id'      => $uploadRequest->id,
                'name'    => 'Upload request',
                'user_id' => $user->id,
            ]);

        $folder_level_1 = Folder::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'name'      => 'level 1',
                'user_id'   => $user->id,
            ]);

        $folder_level_2 = Folder::factory()
            ->create([
                'name'      => 'level 2',
                'parent_id' => $folder_level_1->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->getJson("/api/file-request/$uploadRequest->id/navigation")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    'location'  => 'upload-request',
                    'name'      => 'Upload Request',
                    'folders'   => [
                        [
                            'id'            => $folder_level_1->id,
                            'parent_id'     => $uploadRequest->id,
                            'name'          => 'level 1',
                            'items'         => 1,
                            'trashed_items' => 1,
                            'folders'       => [
                                [
                                    'id'            => $folder_level_2->id,
                                    'parent_id'     => $folder_level_1->id,
                                    'name'          => 'level 2',
                                    'items'         => 0,
                                    'trashed_items' => 0,
                                    'folders'       => [],
                                ],
                            ],
                        ],
                    ],
                    'isMovable' => true,
                    'isOpen'    => true,
                ],
            ]);
    }

    /**
     * @test
     */
    public function it_get_folder_content_for_upload_request_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $root = Folder::factory()
            ->create([
                'id'      => $uploadRequest->id,
                'name'    => 'root',
                'user_id' => $user->id,
            ]);

        $folder = Folder::factory()
            ->create([
                'parent_id' => $root->id,
                'author'    => 'user',
                'user_id'   => $user->id,
            ]);

        $file = File::factory()
            ->create([
                'parent_id' => $root->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->getJson("/api/file-request/$uploadRequest->id/browse/$root->id")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $root->id,
            ])
            ->assertJsonFragment([
                'id' => $file->id,
            ])
            ->assertJsonFragment([
                'id' => $folder->id,
            ]);
    }

    /**
     * @test
     */
    public function it_get_folder_content_from_not_existed_upload_request_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $uploadRequest = UploadRequest::factory()
            ->create([
                'status'  => 'active',
                'user_id' => $user->id,
            ]);

        $root = Folder::factory()
            ->create([
                'id'      => $uploadRequest->id,
                'name'    => 'root',
                'user_id' => $user->id,
            ]);

        $folder = Folder::factory()
            ->create([
                'parent_id' => $root->id,
                'author'    => 'user',
                'user_id'   => $user->id,
            ]);

        $file = File::factory()
            ->create([
                'parent_id' => $root->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->getJson("/api/file-request/$uploadRequest->id/browse/$root->id")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $root->id,
            ])
            ->assertJsonFragment([
                'id' => $file->id,
            ])
            ->assertJsonFragment([
                'id' => $folder->id,
            ]);
    }
}
