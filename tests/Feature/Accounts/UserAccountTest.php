<?php

namespace Tests\Feature\Accounts;

use App\Models\User;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class UserAccountTest extends TestCase
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
    public function it_change_user_password_in_profile_settings()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/user/password', [
            'current_password'      => 'secret',
            'password'              => 'VerySecretPassword',
            'password_confirmation' => 'VerySecretPassword',
        ])->assertStatus(204);

        // TODO: login s novym heslom
    }

    /**
     * @test
     */
    public function it_update_user_settings()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->patchJson('/api/user/relationships/settings', [
            'name'  => 'address',
            'value' => 'Jantar',
        ])->assertStatus(204);

        $this->assertDatabaseHas('user_settings', [
            'address' => 'Jantar',
        ]);
    }

    /**
     * @test
     */
    public function it_update_user_avatar()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this->patchJson('/api/user/relationships/settings', [
            'avatar' => $avatar,
        ])->assertStatus(204);

        Storage::disk('local')
            ->assertExists($user->settings->getRawOriginal('avatar'));
    }

    /**
     * @test
     */
    public function it_get_user_data()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/user')
            ->assertStatus(200)
            ->assertExactJson([
                "data"          => [
                    "id"         => (string) $user->id,
                    "type"       => "user",
                    "attributes" => [
                        "storage_capacity"     => "5",
                        "subscription"         => false,
                        "incomplete_payment"   => null,
                        "stripe_customer"      => false,
                        "email"                => $user->email,
                        "role"                 => $user->role,
                        "created_at_formatted" => format_date($user->created_at, '%d. %B. %Y'),
                        "created_at"           => $user->created_at->toJson(),
                        "updated_at"           => $user->updated_at->toJson(),
                    ]
                ],
                "relationships" => [
                    "settings"   => [
                        "data" => [
                            "id"         => (string) $user->id,
                            "type"       => "settings",
                            "attributes" => [
                                'avatar'               => $user->settings->avatar,
                                'billing_name'         => $user->settings->name,
                                'billing_address'      => $user->settings->address,
                                'billing_state'        => $user->settings->state,
                                'billing_city'         => $user->settings->city,
                                'billing_postal_code'  => $user->settings->postal_code,
                                'billing_country'      => $user->settings->country,
                                'billing_phone_number' => $user->settings->phone_number,
                                'timezone'             => $user->settings->timezone
                            ]
                        ]
                    ],
                    "storage"    => [
                        "data" => [
                            "id"         => "1",
                            "type"       => "storage",
                            "attributes" => [
                                "used"               => 0,
                                "used_formatted"     => "0.00%",
                                "capacity"           => "5",
                                "capacity_formatted" => "5GB",
                            ]
                        ]
                    ],
                    "favourites" => [
                        "data" => [
                            "id"         => "1",
                            "type"       => "folders_favourite",
                            "attributes" => [
                                "folders" => []
                            ]
                        ]
                    ],
                    "tree"       => [
                        "data" => [
                            "id"         => "1",
                            "type"       => "folders_tree",
                            "attributes" => [
                                "folders" => [],
                            ]
                        ]
                    ],
                ]
            ]);
    }
}
