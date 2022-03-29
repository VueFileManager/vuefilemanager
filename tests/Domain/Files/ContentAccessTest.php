<?php
namespace Tests\Domain\Files;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;

class ContentAccessTest extends TestCase
{
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
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $file, $file->name);

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'basename' => $file->name,
                'name'     => $file->name,
            ]);

        $this
            ->actingAs($user)
            ->get("file/$file->name")
            ->assertDownload($file->name);
    }

    /**
     * @test
     */
    public function it_get_private_user_image_thumbnail()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $thumbnail = UploadedFile::fake()
            ->image('fake-thumbnail.jpg');

        Storage::putFileAs("files/$user->id", $thumbnail, $thumbnail->name);

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'basename' => 'fake-thumbnail.jpg',
            ]);

        $this
            ->actingAs($user)
            ->get("thumbnail/xs-$thumbnail->name")
            ->assertDownload("xs-$thumbnail->name");
    }

    /**
     * @test
     */
    public function logged_user_try_to_get_another_private_user_image_thumbnail()
    {
        $users = User::factory()
            ->count(2)
            ->create();

        $thumbnail = UploadedFile::fake()
            ->image('fake-thumbnail.jpg', 2000, 2000);

        Storage::putFileAs("files/{$users[0]->id}", $thumbnail, $thumbnail->name);

        File::factory()
            ->create([
                'user_id'  => $users[0]->id,
                'basename' => 'fake-thumbnail.jpg',
            ]);

        $this->actingAs($users[1])
            ->get("thumbnail/xs-$thumbnail->name")
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function logged_user_try_to_get_another_private_user_file()
    {
        $users = User::factory()
            ->count(2)
            ->create();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/{$users[0]->id}", $file, $file->name);

        File::factory()
            ->create([
                'user_id'  => $users[0]->id,
                'basename' => $file->name,
                'name'     => 'fake-file.pdf',
            ]);

        $this->actingAs($users[1])
            ->get("file/$file->name")
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function guest_try_to_get_private_user_file()
    {
        $this->get('file/fake-file.pdf')
            ->assertRedirect();
    }

    /**
     * @test
     */
    public function guest_try_to_get_private_user_image_thumbnail()
    {
        $this->get('thumbnail/fake-thumbnail.jpg')
            ->assertRedirect();
    }

    /**
     * @test
     */
    public function guest_try_to_get_private_user_folder()
    {
        $folder = Folder::factory()
            ->create();

        $this->getJson("/api/browse/folders/$folder->id")
            ->assertUnauthorized();
    }
}
