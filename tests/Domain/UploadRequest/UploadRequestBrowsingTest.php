<?php

namespace Tests\Domain\UploadRequest;

use App\Users\Models\User;
use Domain\Folders\Models\Folder;
use Domain\UploadRequest\Models\UploadRequest;
use Tests\TestCase;

class UploadRequestBrowsingTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_navigator_tree()
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
                'id' => $uploadRequest->id,
                'name'    => 'Upload request',
                'user_id' => $user->id,
            ]);

        $folder_level_1 = Folder::factory()
            ->create([
                'parent_id' => $uploadRequest->id,
                'name'    => 'level 1',
                'user_id' => $user->id,
            ]);

        $folder_level_2 = Folder::factory()
            ->create([
                'name'      => 'level 2',
                'parent_id' => $folder_level_1->id,
                'user_id'   => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->getJson("/api/upload-request/$uploadRequest->id/navigation")
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
}