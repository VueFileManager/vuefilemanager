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
                "data" => [
                    "id"            => (string)$user->id,
                    "type"          => "user",
                    "attributes"    => [
                        "storage_capacity"     => "5",
                        "subscription"         => false,
                        "has_billing_profile"  => false,
                        "incomplete_payment"   => null,
                        "stripe_customer"      => false,
                        "email"                => $user->email,
                        "role"                 => $user->role,
                        "folders"              => [],
                        "storage"              => [
                            "used"               => 0,
                            "used_formatted"     => "0.00%",
                            "capacity"           => "5",
                            "capacity_formatted" => "5GB",
                        ],
                        "created_at_formatted" => format_date($user->created_at, '%d. %B. %Y'),
                        "created_at"           => $user->created_at->toJson(),
                        "updated_at"           => $user->updated_at->toJson(),
                    ],
                    "relationships" => [
                        "settings"   => [
                            "data" => [
                                "id"         => (string)$user->id,
                                "type"       => "settings",
                                "attributes" => [
                                    'avatar'             => $user->settings->avatar,
                                    'name'               => $user->settings->name,
                                    'address'            => $user->settings->address,
                                    'state'              => $user->settings->state,
                                    'city'               => $user->settings->city,
                                    'postal_code'        => $user->settings->postal_code,
                                    'country'            => $user->settings->country,
                                    'phone_number'       => $user->settings->phone_number,
                                    'timezone'           => $user->settings->timezone,
                                    'payment_activation' => 0,
                                ]
                            ]
                        ],
                        "favourites" => [
                            "data" => [
                                "id"         => (string)$user->id,
                                "type"       => "favourite_folders",
                                "attributes" => [
                                    "folders" => []
                                ]
                            ]
                        ],
                    ]
                ],
            ]);
    }
}
