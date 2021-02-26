<?php

namespace Tests\Feature;

use App\Models\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FileTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_test_file_factory()
    {
        $folder = File::factory(File::class)
            ->create();

        $this->assertDatabaseHas('files', [
            'id' => $folder->id
        ]);
    }

    public function it_upload_new_file()
    {

    }

    public function it_rename_file()
    {

    }

    public function it_move_file_to_another_folder()
    {

    }

    public function it_zip_and_download_multiple_files()
    {

    }

    public function it_delete_file_softly()
    {

    }

    public function it_delete_file_hardly()
    {

    }
}
