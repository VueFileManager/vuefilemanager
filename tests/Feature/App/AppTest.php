<?php

namespace Tests\Feature\App;

use App\Http\Mail\SendContactMessage;
use App\Models\Setting;
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

        $this->get('/')
            ->assertStatus(200);
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
