<?php
namespace Tests\Domain\Spotlight;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;

class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_searched_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $english = File::factory()
            ->create([
                'name'    => 'Document',
                'user_id' => $user->id,
            ]);

        $russian = File::factory()
            ->create([
                'name'    => 'Сохранить изменения',
                'user_id' => $user->id,
            ]);

        $turkish = File::factory()
            ->create([
                'name'    => 'käbir tötänleýin',
                'user_id' => $user->id,
            ]);

        collect([$english, $russian, $turkish])
            ->each(
                fn ($file) => $this
                    ->actingAs($user)
                    ->getJson('/api/search?query=' . mb_strtolower(mb_substr($file->name, 0, 3)))
                    ->assertStatus(200)
                    ->assertJsonFragment([
                        'id'   => $file->id,
                        'name' => $file->name,
                    ])
            );
    }

    /**
     * @test
     */
    public function it_get_searched_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'name'    => 'Documents',
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->getJson('/api/search?query=doc')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $folder->id,
            ]);
    }

    /**
     * @test
     */
    public function it_get_searched_shared_with_me_file_and_folders()
    {
        $owner = User::factory()
            ->hasSettings()
            ->create();

        $member = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'name'    => "Alice's files",
                'user_id' => $owner->id,
            ]);

        $folderWithin = Folder::factory()
            ->create([
                'name'      => 'Folder within Alice',
                'parent_id' => $folder->id,
                'user_id'   => $owner->id,
            ]);

        $document = File::factory()
            ->create([
                'name'      => 'Document',
                'user_id'   => $owner->id,
                'parent_id' => $folderWithin->id,
            ]);

        DB::table('team_folder_members')
            ->insert([
                'parent_id'  => $folder->id,
                'user_id'    => $member->id,
                'permission' => 'can-edit',
            ]);

        $this
            ->actingAs($member)
            ->getJson('/api/search?query=ali')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $folder->id,
            ]);

        $this
            ->actingAs($member)
            ->getJson('/api/search?query=Fol')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $folder->id,
            ]);

        $this
            ->actingAs($member)
            ->getJson('/api/search?query=doc')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $document->id,
            ]);
    }
}
