<?php
namespace Tests\Domain\Homepage;

use Domain\Pages\Actions\SeedDefaultPagesAction;
use Domain\Settings\Actions\SeedDefaultSettingsAction;
use Mail;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Domain\Settings\Models\Setting;
use Domain\Homepage\Mail\SendContactMessage;

class HomepageTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_index_page()
    {
        resolve(SeedDefaultPagesAction::class)();

        resolve(SeedDefaultSettingsAction::class)('Extended');

        Setting::create([
            'name'  => 'setup_wizard_success',
            'value' => 'setup-done',
        ]);

        Setting::create([
            'name'  => 'license',
            'value' => 'Extended',
        ]);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('setup-done')
            ->assertSee('VueFileManager');
    }

    /**
     * @test
     */
    public function it_get_index_page_without_setup()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('setup-disclaimer')
            ->assertSee('VueFileManager');
    }

    /**
     * @test
     */
    public function it_get_og_page_for_folder()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id,
                'name'    => 'Folder Title',
            ]);

        $share = Share::factory(Share::class)
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
        $user = User::factory(User::class)
            ->create();

        $file = File::factory(File::class)
            ->create([
                'user_id'   => $user->id,
                'name'      => 'Fake Image',
                'thumbnail' => 'fake-image-thumbnail.jpg',
            ]);

        $share = Share::factory(Share::class)
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
            ->assertSee('fake-image-thumbnail.jpg');
    }

    /**
     * @test
     */
    public function it_get_og_page_for_protected_file()
    {
        $user = User::factory(User::class)
            ->create();

        $file = File::factory(File::class)
            ->create([
                'user_id'   => $user->id,
                'name'      => 'Fake Image',
                'thumbnail' => 'fake-image-thumbnail.jpg',
            ]);

        $share = Share::factory(Share::class)
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

    /**
     * @test
     */
    public function it_send_contact_form()
    {
        Mail::fake();

        Setting::create([
            'name'  => 'contact_email',
            'value' => 'jane@doe.com',
        ]);

        $this->postJson('/api/contact', [
            'email'   => 'john@doe.com',
            'message' => 'Whaats is up!',
        ])
            ->assertStatus(201);

        Mail::assertSent(SendContactMessage::class);
    }
}
