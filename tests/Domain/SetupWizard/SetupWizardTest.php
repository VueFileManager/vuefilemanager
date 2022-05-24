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
     * @test
     */
    public function it_verify_purchase_code_successfully()
    {
        Http::fake([
            'https://verify.vuefilemanager.com/api/verify-code/*' => Http::response([], 204),
        ]);

        $this->postJson('/api/setup/purchase-code', [
            'purchaseCode' => '8624194e-3156-4cd0-944e-3440fcecdacb',
        ])->assertStatus(201);
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
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_store_app_settings()
    {
        Setting::all()->each->delete();

        $this->postJson('/api/setup/app-setup', [
            'color'                  => '#00BC72',
            'title'                  => 'VueFileManager',
            'description'            => 'The best file manager on the internet',
            'googleAnalytics'        => 'UA-12345678-1',
            'contactMail'            => 'john@doe.com',
            'subscriptionType'       => 'metered',
            'userVerification'       => 1,
            'userRegistration'       => 1,
            'storageLimitation'      => 1,
            'defaultStorage'         => 10,
            'logo'                   => UploadedFile::fake()->image('fake-logo.jpg'),
            'logo_dark'              => UploadedFile::fake()->image('fake-logo-dark.jpg'),
            'logo_horizontal'        => UploadedFile::fake()->image('fake-logo-horizontal.jpg'),
            'logo_horizontal_dark'   => UploadedFile::fake()->image('fake-logo-horizontal-dark.jpg'),
            'favicon'                => UploadedFile::fake()->image('fake-favicon.jpg'),
        ])->assertStatus(200);

        $this
            ->assertDatabaseHas('settings', [
                'name'  => 'subscription_type',
                'value' => 'metered',
            ])
            ->assertDatabaseHas('settings', [
                'name'  => 'user_verification',
                'value' => 0,
            ])
            ->assertDatabaseHas('settings', [
                'name'  => 'app_color',
                'value' => '#00BC72',
            ])
            ->assertDatabaseHas('settings', [
                'name'  => 'app_title',
                'value' => 'VueFileManager',
            ])
            ->assertDatabaseHas('settings', [
                'name'  => 'app_description',
                'value' => 'The best file manager on the internet',
            ])
            ->assertDatabaseHas('settings', [
                'name'  => 'google_analytics',
                'value' => 'UA-12345678-1',
            ])
            ->assertDatabaseHas('settings', [
                'name'  => 'contact_email',
                'value' => 'john@doe.com',
            ])
            ->assertDatabaseHas('settings', [
                'name'  => 'default_max_storage_amount',
                'value' => '10',
            ]);

        collect(['app_logo', 'app_logo_dark', 'app_logo_horizontal', 'app_logo_horizontal_dark', 'app_favicon'])
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
        Setting::all()->each->delete();

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
            'user_id'    => User::first()->id,
            'first_name' => 'John',
            'last_name'  => 'Doe',
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
            'value' => 'regular',
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
            'key'   => 'close',
            'value' => 'Close',
            'lang'  => 'en',
        ]);

        $avatar = User::first()
            ->settings
            ->getRawOriginal('avatar');

        $this->assertNotNull($avatar);

        collect(config('vuefilemanager.avatar_sizes'))
            ->each(
                fn ($size) => Storage::disk('local')
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
