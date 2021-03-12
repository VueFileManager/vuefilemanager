<?php

namespace Tests\Feature\App;

use App\Http\Mail\SendContactMessage;
use App\Models\Setting;
use App\Notifications\SharedSendViaEmail;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
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
    public function it_send_contact_form()
    {
        Mail::fake();

        Setting::create([
            'name' => 'contact_email',
            'value' => 'jane@doe.com',
        ]);

        $this->postJson('/api/contact', [
            'email'   => 'john@doe.com',
            'message' => 'Whaats is up!',
        ])
            ->assertStatus(201);

        Mail::assertSent( SendContactMessage::class);
    }
}
