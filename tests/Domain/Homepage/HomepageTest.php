<?php
namespace Tests\Domain\Homepage;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Domain\Settings\Models\Setting;
use Domain\Settings\Actions\SeedDefaultSettingsAction;

class HomepageTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_index_page()
    {
        resolve(SeedDefaultSettingsAction::class)();

        Setting::create([
            'name'  => 'setup_wizard_success',
            'value' => 'installation-done',
        ]);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('installation-done')
            ->assertSee('VueFileManager');
    }

    /**
     * @test
     */
    public function it_get_index_page_without_setup()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('installation-needed')
            ->assertSee('VueFileManager');
    }

    /**
     * @test
     */
    public function it_get_og_page_for_folder()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $folder = Folder::factory()
            ->create([
                'user_id' => $user->id,
                'name'    => 'Folder Title',
            ]);

        $share = Share::factory()
            ->create([
                'item_id'      => $folder->id,
                'user_id'      => $user->id,
                'type'         => 'folder',
                'is_protected' => false,
            ]);

        $this
            ->get("/api/og-site/$share->token")
            ->assertStatus(200)
            ->assertSee('Folder Title');
    }

    /**
     * @test
     */
    public function it_get_og_page_for_image()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = File::factory()
            ->create([
                'user_id'  => $user->id,
                'name'     => 'Fake Image',
                'basename' => 'fake-image.jpg',
                'type'     => 'image',
            ]);

        $share = Share::factory()
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $user->id,
                'type'         => 'file',
                'is_protected' => false,
            ]);

        $this
            ->get("/api/og-site/$share->token")
            ->assertStatus(200)
            ->assertSee('Fake Image')
            ->assertSee('lg-fake-image.jpg');
    }

    /**
     * @test
     */
    public function it_get_og_page_for_protected_file()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $file = File::factory()
            ->create([
                'user_id'  => $user->id,
                'name'     => 'Fake Image',
                'basename' => 'fake-image.jpg',
                'type'     => 'image',
            ]);

        $share = Share::factory()
            ->create([
                'item_id'      => $file->id,
                'user_id'      => $user->id,
                'type'         => 'file',
                'is_protected' => true,
            ]);

        $this
            ->get("/api/og-site/$share->token")
            ->assertStatus(200)
            ->assertSee('This link is protected by password');
    }
}
