<?php
namespace Tests\App\Restrictions;

use Illuminate\Http\UploadedFile;
use Storage;
use Str;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Settings\Models\Setting;

class DefaultRestrictionsTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_upload()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this->assertEquals(true, $user->canUpload(9999999));
    }

    /**
     * @test
     */
    public function it_cant_upload_because_storage_limit_exceeded()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'filesize' => 99999999,
            ]);

        $this->assertEquals(false, $user->canUpload(999999999));
    }

    /**
     * @test
     */
    public function it_can_upload_because_storage_limitation_is_turned_off_and_user_has_unlimited_limit()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        // Turn off storage limitation
        Setting::updateOrCreate([
            'name' => 'storage_limitation',
        ], [
            'value' => 0,
        ]);

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'filesize' => 99999999,
            ]);

        $this->assertEquals(1, $user->limitations->max_storage_amount);
        $this->assertEquals(true, $user->canUpload(999999999));
    }

    /**
     * @test
     */
    public function it_cant_upload_because_storage_limitation_is_turned_on_and_user_exceeded_limit()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        // Turn on storage limitation
        Setting::updateOrCreate([
            'name' => 'storage_limitation',
        ], [
            'value' => 1,
        ]);

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'filesize' => 99999999,
            ]);

        $this->assertEquals(1, $user->limitations->max_storage_amount);
        $this->assertEquals(false, $user->canUpload(999999999));
    }

    /**
     * @test
     */
    public function it_can_create_new_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/create-folder', [
                'name'      => 'New Folder',
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('folders', [
            'name' => 'New Folder',
        ]);
    }

    /**
     * @test
     */
    public function it_can_get_private_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = UploadedFile::fake()
            ->create(Str::random() . '-fake-file.pdf', 1200, 'application/pdf');

        Storage::putFileAs("files/$user->id", $file, $file->name);

        $file = File::factory()
            ->create([
                'user_id'  => $user->id,
                'basename' => $file->name,
                'name'     => 'fake-file.pdf',
            ]);

        // 404 but, ok, because there is not stored temporary file in test
        $this
            ->actingAs($user)
            ->get("file/$file->name")
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function it_can_get_shared_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = File::factory()
            ->create([
                'basename' => 'fake-file.pdf',
                'name'     => 'fake-file.pdf',
            ]);

        $share = Share::factory()
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $user->id,
                'type'         => 'file',
                'is_protected' => false,
            ]);

        // 404 but, ok, because there is not stored temporary file in test
        $this
            ->get("file/$file->name/shared/$share->token")
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function it_can_get_share_page()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $share = Share::factory()
            ->create([
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this->get("/share/$share->token")
            ->assertRedirect("/share/$share->token/files/$share->item_id");
    }
}
