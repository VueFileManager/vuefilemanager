<?php

namespace Tests\Domain\Files;

use Domain\Settings\Models\File;
use Domain\Settings\Models\Folder;
use Domain\Settings\Models\User;
use Domain\Settings\Models\Zip;
use Domain\SetupWizard\Services\SetupService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class ContentAccessTest extends TestCase
{
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
        $user = User::factory(User::class)
            ->create();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $file, $file->name);

        File::factory(File::class)
            ->create([
                'user_id'  => $user->id,
                'basename' => $file->name,
                'name'     => 'fake-file.pdf',
            ]);

        $this
            ->actingAs($user)
            ->get("file/$file->name")
            ->assertOk();
    }

    /**
     * @test
     */
    public function it_get_private_user_image_thumbnail()
    {
        $user = User::factory(User::class)
            ->create();

        $thumbnail = UploadedFile::fake()
            ->image(Str::random() . '-fake-thumbnail.jpg');

        Storage::putFileAs("files/$user->id", $thumbnail, $thumbnail->name);

        File::factory(File::class)
            ->create([
                'user_id'   => $user->id,
                'thumbnail' => $thumbnail->name,
                'name'      => 'fake-thumbnail.jpg',
            ]);

        $this
            ->actingAs($user)
            ->get("thumbnail/$thumbnail->name")
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_get_private_user_zip()
    {
        $user = User::factory(User::class)
            ->create();

        $file = UploadedFile::fake()
            ->create('archive.zip', 2000, 'application/zip');

        Storage::putFileAs('zip', $file, 'EHWKcuvKzA4Gv29v-archive.zip');

        $zip = Zip::factory(Zip::class)->create([
            'basename' => 'EHWKcuvKzA4Gv29v-archive.zip',
            'user_id'  => $user->id,
        ]);

        $this
            ->actingAs($user)
            ->get("zip/$zip->id")
            ->assertOk();
    }

    /**
     * @test
     */
    public function logged_user_try_to_get_another_private_user_image_thumbnail()
    {
        $users = User::factory(User::class)
            ->count(2)
            ->create();

        $thumbnail = UploadedFile::fake()
            ->image(Str::random() . '-fake-thumbnail.jpg');

        Storage::putFileAs("files/{$users[0]->id}", $thumbnail, $thumbnail->name);

        File::factory(File::class)
            ->create([
                'user_id'   => $users[0]->id,
                'thumbnail' => $thumbnail->name,
                'name'      => 'fake-thumbnail.jpg',
            ]);

        Sanctum::actingAs($users[1]);

        $this->get("thumbnail/$thumbnail->name")
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function logged_user_try_to_get_another_private_user_file()
    {
        $users = User::factory(User::class)
            ->count(2)
            ->create();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/{$users[0]->id}", $file, $file->name);

        File::factory(File::class)
            ->create([
                'user_id'  => $users[0]->id,
                'basename' => $file->name,
                'name'     => 'fake-file.pdf',
            ]);

        Sanctum::actingAs($users[1]);

        $this->get("file/$file->name")
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function logged_user_try_to_get_another_private_user_zip()
    {
        $user = User::factory(User::class)
            ->create();

        $file = UploadedFile::fake()
            ->create('archive.zip', 2000, 'application/zip');

        Storage::putFileAs('zip', $file, 'EHWKcuvKzA4Gv29v-archive.zip');

        $zip = Zip::factory(Zip::class)->create([
            'basename' => 'EHWKcuvKzA4Gv29v-archive.zip',
        ]);

        $this
            ->actingAs($user)
            ->get("zip/$zip->id")
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function guest_try_to_get_private_user_file()
    {
        $this->get("file/fake-file.pdf")
            ->assertRedirect();
    }

    /**
     * @test
     */
    public function guest_try_to_get_private_user_zip()
    {
        $this->get("zip/EHWKcuvKzA4Gv29v-archive.zip")
            ->assertRedirect();
    }

    /**
     * @test
     */
    public function guest_try_to_get_private_user_image_thumbnail()
    {
        $this->get("thumbnail/fake-thumbnail.jpg")
            ->assertRedirect();
    }

    /**
     * @test
     */
    public function guest_try_to_get_private_user_folder()
    {
        $folder = Folder::factory(Folder::class)
            ->create();

        $this->getJson("/api/browse/folders/$folder->id")
            ->assertUnauthorized();
    }
}
