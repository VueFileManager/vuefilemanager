<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Services\SetupService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
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

    /**
     * @test
     */
    public function it_get_private_user_file()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/upload', [
            'file'      => $file,
            'folder_id' => null,
            'is_last'   => true,
        ])->assertStatus(201);

        $this->get("file/$file->name")
            ->assertOk();
    }

    /**
     * @test
     */
    public function guest_try_to_get_private_user_file()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $file, $file->name);

        File::factory(File::class)
            ->create([
                'basename' => $file->name,
                'name'     => 'fake-file.pdf',
            ]);

        $this->get("file/$file->name")
            ->assertStatus(302);
    }

    /**
     * @test
     */
    public function logged_user_try_to_get_another_private_user_file()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $file, $file->name);

        File::factory(File::class)
            ->create([
                'basename' => $file->name,
                'name'     => 'fake-file.pdf',
            ]);

        Sanctum::actingAs($user);

        $this->get("file/$file->name")
            ->assertNotFound();
    }
}
