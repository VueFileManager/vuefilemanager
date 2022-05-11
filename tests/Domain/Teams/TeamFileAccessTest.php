<?php
namespace Tests\Domain\Teams;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use Domain\Teams\Models\TeamFolderMember;

class TeamFileAccessTest extends TestCase
{
    /**
     * @test
     */
    public function team_member_download_folder_as_zip()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        collect([0, 1])
            ->each(function ($index) use ($folder, $user) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this
                    ->actingAs($user)
                    ->postJson('/api/upload/chunks', [
                        'name'            => $file->name,
                        'extension'       => 'pdf',
                        'chunk'           => $file,
                        'parent_id'       => $folder->id,
                        'is_last_chunk'   => 1,
                    ])->assertStatus(201);
            });

        $member = User::factory()
            ->hasSettings()
            ->create();

        // Attach user into members
        TeamFolderMember::create([
            'parent_id'  => $folder->id,
            'user_id'    => $member->id,
            'permission' => 'can-edit',
        ]);

        $this
            ->actingAs($member)
            ->getJson("/api/zip?items=$folder->id|folder")
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/x-zip');
    }

    /**
     * @test
     */
    public function team_member_download_files_as_zip()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        collect([0, 1])
            ->each(function ($index) use ($folder, $user) {
                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this
                    ->actingAs($user)
                    ->postJson('/api/upload/chunks', [
                        'name'            => $file->name,
                        'extension'       => 'pdf',
                        'chunk'           => $file,
                        'parent_id'       => $folder->id,
                        'is_last_chunk'   => 1,
                    ])->assertStatus(201);
            });

        $member = User::factory()
            ->hasSettings()
            ->create();

        // Attach user into members
        TeamFolderMember::create([
            'parent_id'  => $folder->id,
            'user_id'    => $member->id,
            'permission' => 'can-edit',
        ]);

        $files = File::all();

        $this
            ->actingAs($member)
            ->getJson("/api/zip?items={$files->first()->id}|file,{$files->last()->id}|file")
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/x-zip');
    }
}
