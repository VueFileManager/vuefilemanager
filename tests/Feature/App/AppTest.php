<?php

namespace Tests\Feature\App;

use App\Http\Mail\SendContactMessage;
use App\Models\File;
use App\Models\Folder;
use App\Models\Setting;
use App\Models\Share;
use App\Models\User;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mail;
use Tests\TestCase;

class AppTest extends TestCase
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
    public function it_get_index_page()
    {
        $this->setup->seed_default_pages();

        $this->setup->seed_default_settings('Extended');

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
                'name' => 'Folder Title',
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
                'user_id' => $user->id,
                'name' => 'Fake Image',
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
                'user_id' => $user->id,
                'name' => 'Fake Image',
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
    public function it_get_legal_page()
    {
        $this->setup->seed_default_pages();

        $this->getJson('/api/page/terms-of-service')
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Terms of Service',
            ]);
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

    /**
     * @test
     */
    public function it_get_settings()
    {
        Setting::create([
            'name'  => 'get_started_title',
            'value' => 'Hello World!',
        ]);

        Setting::create([
            'name'  => 'pricing_description',
            'value' => 'Give me a money!',
        ]);

        $this->getJson('/api/content?column=get_started_title|pricing_description')
            ->assertStatus(200)
            ->assertExactJson([
                "get_started_title"   => "Hello World!",
                "pricing_description" => "Give me a money!",
            ]);
    }

    /**
     * @test
     */
    public function it_try_get_secured_settings_via_public_api()
    {
        Setting::create([
            'name'  => 'purchase_code',
            'value' => '15a53561-d387-4e0a-8de1-5d1bff34c1ed',
        ]);

        $this->getJson('/api/content?column=purchase_code')
            ->assertStatus(401);
    }
}
