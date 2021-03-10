<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Share;
use App\Models\Traffic;
use App\Models\User;
use App\Services\SetupService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class ShareContentAccessTest extends TestCase
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
    public function it_get_public_file_record_and_download_them()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        $document = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1000, 'application/pdf');

        Storage::putFileAs("files/$user->id", $document, $document->name);

        $file = File::factory(File::class)
            ->create([
                'filesize' => $document->getSize(),
                'user_id'  => $user->id,
                'basename' => $document->name,
                'name'     => 'fake-file.pdf',
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $user->id,
                'type'         => 'file',
                'is_protected' => false,
            ]);

        // Get share record
        $this->get("/api/files/$share->token/public")
            ->assertStatus(200)
            ->assertJsonFragment([
                'basename' => $document->name
            ]);

        // Get shared file
        $this->get("/file/$document->name/public/$share->token")
            ->assertStatus(200);

        $this->assertDatabaseHas('traffic', [
            'user_id'  => $user->id,
            'download' => '1024000',
        ]);
    }

    /**
     * @test
     */
    public function it_get_public_thumbnail()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        $thumbnail = UploadedFile::fake()
            ->image(Str::random() . '-fake-thumbnail.jpg');

        Storage::putFileAs("files/$user->id", $thumbnail, $thumbnail->name);

        $file = File::factory(File::class)
            ->create([
                'user_id'   => $user->id,
                'thumbnail' => $thumbnail->name,
                'name'      => 'fake-thumbnail.jpg',
            ]);

        $share = Share::factory(Share::class)
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $user->id,
                'type'         => 'file',
                'is_protected' => false,
            ]);

        // Get shared file
        $this->get("/thumbnail/$thumbnail->name/public/$share->token")
            ->assertStatus(200);

        $this->assertDatabaseMissing('traffic', [
            'user_id'  => $user->id,
            'download' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_try_to_get_protected_file_record()
    {
        $share = Share::factory(Share::class)
            ->create([
                'type'         => 'file',
                'is_protected' => true,
            ]);

        // Get share record
        $this->get("/api/files/$share->token/public")
            ->assertStatus(403);
    }
}
