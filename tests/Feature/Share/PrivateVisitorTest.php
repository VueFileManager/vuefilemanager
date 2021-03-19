<?php

namespace Tests\Feature\Share;

use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Models\User;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PrivateVisitorTest extends TestCase
{
    use DatabaseMigrations;

    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

    /**
     * @test
     */
    public function authenticated_visitor_get_folder_content()
    {
        $user = User::factory(User::class)
            ->create();

        $root = Folder::factory(Folder::class)
            ->create([
                'name'    => 'root',
                'user_id' => $user->id,
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $root->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => true,
                'permission'   => 'editor',
            ]);

        $folder = Folder::factory(Folder::class)
            ->create([
                'parent_id'  => $root->id,
                'name'       => 'Documents',
                "user_scope" => "master",
                'user_id'    => $user->id,
            ]);

        $file = File::factory(File::class)
            ->create([
                'folder_id'  => $root->id,
                'name'       => 'Document',
                'basename'   => 'document.pdf',
                "mimetype"   => "application/pdf",
                "user_scope" => "master",
                "type"       => "file",
                'user_id'    => $user->id,
            ]);

        $this->withUnencryptedCookie('share_session', json_encode([
            'token'         => $share->token,
            'authenticated' => true,
        ]))
            ->get("/api/browse/folders/$root->id/private/$share->token")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    "id"            => $folder->id,
                    "user_id"       => $user->id,
                    "parent_id"     => $root->id,
                    "name"          => "Documents",
                    "color"         => null,
                    "emoji"         => null,
                    "user_scope"    => "master",
                    "deleted_at"    => null,
                    "created_at"    => $folder->created_at,
                    "updated_at"    => $folder->updated_at->toJson(),
                    "items"         => 0,
                    "trashed_items" => 0,
                    "type"          => "folder",
                ],
                [
                    "id"         => $file->id,
                    "user_id"    => $user->id,
                    "folder_id"  => $root->id,
                    "thumbnail"  => null,
                    "name"       => "Document",
                    "basename"   => "document.pdf",
                    "mimetype"   => "application/pdf",
                    "filesize"   => $file->filesize,
                    "type"       => "file",
                    "metadata"   => null,
                    "user_scope" => "master",
                    "deleted_at" => null,
                    "created_at" => $file->created_at,
                    "updated_at" => $file->updated_at->toJson(),
                    "file_url"   => "http://localhost/file/document.pdf/private/$share->token",
                ]
            ]);
    }
}
