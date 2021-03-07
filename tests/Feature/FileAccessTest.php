<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Services\SetupService;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class FileAccessTest extends TestCase
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
    public function it_get_public_user_avatar()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $avatar = UploadedFile::fake()
            ->image('fake-avatar.jpg');

        Storage::putFileAs('avatars', $avatar, 'fake-avatar.jpg');

        $this->get('avatars/fake-avatar.jpg')
            ->assertStatus(200);

        Storage::assertExists('avatars/fake-avatar.jpg');
    }

    /**
     * @test
     */
    public function it_get_public_system_image()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $system = UploadedFile::fake()
            ->image('fake-logo.jpg');

        Storage::putFileAs('system', $system, 'fake-logo.jpg');

        $this->get('system/fake-logo.jpg')
            ->assertStatus(200);

        Storage::assertExists('system/fake-logo.jpg');
    }
}
