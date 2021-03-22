<?php

namespace Tests\Feature\Oasis;

use App\Models\User;
use App\Notifications\Oasis\PaymentRequiredNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Notification;
use Tests\TestCase;

class OasisAdminTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_get_register_by_ico()
    {
        $admin = User::factory(User::class)
            ->create();

        Sanctum::actingAs($admin);

        $response = [
            "name"          => "GDPR Cloud Solution, s.r.o.",
            "ico"           => "08995281",
            "city"          => "Praha",
            "addr_city"     => "Praha 5",
            "addr_zip"      => "15900",
            "addr_streetnr" => "Zbraslavská 12/11",
            "addr_full"     => "Zbraslavská 12/11, Malá Chuchle, 159 00 Praha 5",
        ];

        Http::fake([
            'https://or.justice.cz/ias/ui/rejstrik-08995281' => Http::response($response, 200),
        ]);

        $this->getJson('/api/oasis/admin/company-details?ico=08995281')
            ->assertStatus(200)
            ->assertExactJson($response);
    }

    /**
     * @test
     */
    public function it_try_to_get_register_by_ico_with_not_found_result()
    {
        $admin = User::factory(User::class)
            ->create();

        Sanctum::actingAs($admin);

        Http::fake([
            'https://or.justice.cz/ias/ui/rejstrik-0899528199' => Http::response([], 200),
        ]);

        $this->getJson('/api/oasis/admin/company-details?ico=0899528199')
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function it_register_new_client_from_admin_panel()
    {
        Notification::fake();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->postJson('/api/oasis/admin/users/create', [
            'ico'          => '08995281',
            'name'         => 'GDPR Cloud Solution, s.r.o.',
            'email'        => 'john@doe.com',
            'phone_number' => '+420950123456',
            'address'      => 'Zbraslavská 12/11',
            'state'        => 'CZ',
            'city'         => 'Praha',
            'postal_code'  => '15900',
            'country'      => 'CZ',
            'plan'         => 'virtualni-sanon-basic',
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'john@doe.com',
        ]);

        $this->assertDatabaseHas('user_settings', [
            'ico'                => '08995281',
            'payment_activation' => 0,
            'requested_plan'     => 'virtualni-sanon-basic',
        ]);

        $newbie = User::whereEmail('john@doe.com')
            ->first();

        Notification::assertSentTo($newbie, PaymentRequiredNotification::class);
    }
}
