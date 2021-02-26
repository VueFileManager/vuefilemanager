<?php

namespace Tests\Feature;

use App\Models\Folder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FolderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_test_folder_factory()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $this->assertDatabaseHas('folders', [
            'id' => $folder->id
        ]);
    }

    public function it_create_new_folder()
    {

    }

    public function it_rename_folder()
    {

    }

    public function it_set_folder_emoji()
    {

    }

    public function it_set_folder_color()
    {

    }

    public function it_move_folder_to_another_folder()
    {

    }

    public function it_add_to_favourites_folder()
    {

    }

    public function it_zip_and_download_folder_with_content_within()
    {

    }

    public function it_delete_folder_softly()
    {

    }

    public function it_delete_folder_hardly()
    {

    }

    public function it_delete_folder_with_their_content_within_softly()
    {

    }

    public function it_delete_folder_with_their_content_within_hardly()
    {

    }
}
