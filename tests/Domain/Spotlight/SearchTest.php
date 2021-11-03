<?php
namespace Tests\Domain\Spotlight;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;

class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_searched_file()
    {
        $user = User::factory()
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
                    ->getJson('/api/browse/search?query=' . mb_strtolower(mb_substr($file->name, 0, 3)))
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
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'name'    => 'Documents',
                'user_id' => $user->id,
            ]);

        $this
            ->actingAs($user)
            ->getJson('/api/browse/search?query=doc')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $folder->id,
            ]);
    }
}
