<?php
namespace Tests\Domain\SetupWizard;

use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Http\UploadedFile;
use Domain\Settings\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SetupWizardTest extends TestCase
{
    /**
     * CAVEAT:
     *
     * The route '/api/setup/stripe-plans' which is part of setup wizard is moved to
     * SubscriptionTest.php to group all live API test. For more info how to test
     * subscription integration in VueFileManager platform visit https://laravel.com/docs/8.x/billing#testing
     */

    /**
     * @test
     */
    public function it_verify_purchase_code_successfully()
    {
        Http::fake([
            'https://verify.vuefilemanager.com/api/verify-code/*' => Http::response([], 204),
        ]);

        $this->postJson('/api/setup/purchase-code', [
            'purchaseCode' => '8624194e-3156-4cd0-944e-3440fcecdacb',
        ])->assertStatus(204);
    }

    /**
     * @test
     */
    public function it_verify_purchase_code_unsuccessfully()
    {
        Http::fake([
            'https://verify.vuefilemanager.com/api/verify-code/*' => Http::response([], 400),
        ]);

        $this->postJson('/api/setup/purchase-code', [
            'purchaseCode' => '8624194e-3156-4cd0-944e-3440fcecdacb',
        ])->assertStatus(400);
    }

    /**
     * @test
     */
    public function it_setup_database()
    {
        $this->postJson('/api/setup/database', [
            'connection' => 'sqlite',
            'host'       => 'null',
            'port'       => 'null',
            'name'       => 'database/test.sqlite',
            'username'   => 'null',
            'password'   => 'null',
        ])->assertStatus(204);

        $this->assertDatabaseHas('settings', [
            'name'  => 'setup_wizard_database',
            'value' => 1,
        ]);
    }

    /**
     * @test
     */
    public function it_store_stripe_credentials()
    {
        $this->postJson('/api/setup/stripe-credentials', [
            'currency'      => 'EUR',
            'key'           => '123456789',
            'secret'        => '123456789',
            'webhookSecret' => '123456789',
        ])->assertStatus(204);

        $this->assertDatabaseHas('settings', [
            'name'  => 'stripe_currency',
            'value' => 'EUR',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'payments_configured',
            'value' => 1,
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'payments_active',
            'value' => 1,
        ]);
    }

    /**
     * @test
     */
    public function it_store_stripe_billings()
    {
        $payload = collect([
            'billing_phone_number' => '+421123456789',
            'billing_postal_code'  => '04001',
            'billing_vat_number'   => 'SK20042134234',
            'billing_address'      => 'Does 20',
            'billing_country'      => 'Doeland',
            'billing_state'        => 'Doeslandia',
            'billing_city'         => 'Does',
            'billing_name'         => 'John Doe Ltd.',
        ]);

        $this->postJson('/api/setup/stripe-billings', $payload->toArray())
            ->assertStatus(204);

        $payload
            ->each(function ($value, $key) {
                $this->assertDatabaseHas('settings', [
                    'name'  => $key,
                    'value' => $value,
                ]);
            });
    }

    /**
     *
     */
    public function it_store_stripe_plans()
    {
    }

    /**
     * @test
     */
    public function it_store_environment()
    {
        $this->postJson('/api/setup/environment-setup', [
            'storage' => [
                'driver' => 'local',
            ],
            'mail'    => [
                'driver'     => 'smtp',
                'host'       => 'smtp.email.com',
                'port'       => '25',
                'username'   => 'john@doe.com',
                'password'   => 'secret',
                'encryption' => 'tls',
            ],
        ])->assertStatus(204);
    }

    /**
     * @test
     */
    public function it_store_app_settings()
    {
        $this->postJson('/api/setup/app-setup', [
            'title'             => 'VueFileManager',
            'description'       => 'The best file manager on the internet',
            'googleAnalytics'   => 'UA-12345678-1',
            'contactMail'       => 'john@doe.com',
            'userRegistration'  => 1,
            'storageLimitation' => 1,
            'defaultStorage'    => 10,
            'logo'              => UploadedFile::fake()->image('fake-logo.jpg'),
            'logo_horizontal'   => UploadedFile::fake()->image('fake-logo-horizontal.jpg'),
            'favicon'           => UploadedFile::fake()->image('fake-favicon.jpg'),
        ])->assertStatus(204);

        $this->assertDatabaseHas('settings', [
            'name'  => 'app_title',
            'value' => 'VueFileManager',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'app_description',
            'value' => 'The best file manager on the internet',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'google_analytics',
            'value' => 'UA-12345678-1',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'contact_email',
            'value' => 'john@doe.com',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'storage_default',
            'value' => '10',
        ]);

        collect(['app_logo', 'app_logo_horizontal', 'app_favicon'])
            ->each(function ($file) {
                $path = get_settings($file);

                $this->assertNotNull($path);

                Storage::assertExists($path);
            });
    }

    /**
     * @test
     */
    public function it_create_admin_account()
    {
        $this->post('/admin-setup', [
            'email'                 => 'john@doe.com',
            'password'              => 'VerySecretPassword',
            'password_confirmation' => 'VerySecretPassword',
            'name'                  => 'John Doe',
            'purchase_code'         => '8624194e-3156-4cd0-944e-3440fcecdacb',
            'license'               => 'Regular',
            'avatar'                => UploadedFile::fake()->image('fake-logo.jpg'),
        ])->assertStatus(204);

        $this->assertDatabaseHas('users', [
            'email' => 'john@doe.com',
            'role'  => 'admin',
        ]);

        $this->assertDatabaseHas('user_settings', [
            'user_id' => User::first()->id,
            'name'    => 'John Doe',
        ]);

        $this->assertDatabaseMissing('user_settings', [
            'avatar' => null,
        ]);

        $this->assertDatabaseHas('pages', [
            'title' => 'Terms of Service',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'feature_title_1',
            'value' => 'Truly Freedom',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'setup_wizard_success',
            'value' => '1',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'license',
            'value' => 'Regular',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'purchase_code',
            'value' => '8624194e-3156-4cd0-944e-3440fcecdacb',
        ]);

        $this->assertDatabaseHas('languages', [
            'name'   => 'English',
            'locale' => 'en',
        ]);

        $this->assertDatabaseHas('language_translations', [
            'key'   => 'actions.close',
            'value' => 'Close',
            'lang'  => 'en',
        ]);

        $avatar = User::first()
            ->settings
            ->getRawOriginal('avatar');

        $this->assertNotNull($avatar);

        collect(config('vuefilemanager.avatar_sizes'))
            ->each(fn ($size) =>
            Storage::disk('local')
                ->assertExists("avatars/{$size['name']}-{$avatar}")
            );
    }

    /**
     * @test
     */
    public function it_try_to_create_admin_account_after_setup_wizard_success()
    {
        Setting::updateOrCreate(
            ['name' => 'setup_wizard_success'],
            ['value' => '1']
        );

        $this->postJson('/admin-setup', [
            'email'                 => 'john@doe.com',
            'password'              => 'VerySecretPassword',
            'password_confirmation' => 'VerySecretPassword',
            'name'                  => 'John Doe',
            'purchase_code'         => '8624194e-3156-4cd0-944e-3440fcecdacb',
            'license'               => 'Regular',
        ])->assertStatus(410);

        $this->assertDatabaseMissing('users', [
            'email' => 'john@doe.com',
            'role'  => 'admin',
        ]);
    }
}
